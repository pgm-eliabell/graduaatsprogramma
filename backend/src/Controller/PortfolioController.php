<?php

namespace App\Controller;

use App\Entity\PortfolioComponents;
use App\Entity\Portfolios;
use App\Repository\PortfolioComponentsRepository;
use App\Repository\PortfoliosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioController extends AbstractController
{
    /**
     * Displays the Vue-based page builder.
     */
    #[Route('/create-portfolio', name: 'create_portfolio')]
    public function createPortfolio(): Response
    {
        return $this->render('portfolio/create.html.twig');
    }

    /**
     * Endpoint to get existing portfolio components for the logged-in user (for editing).
     */
    #[Route('/api/portfolios/edit', name: 'edit_portfolio', methods: ['GET'])]
    public function editPortfolio(PortfoliosRepository $portfoliosRepo): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        // Grab or create the portfolio
        $portfolio = $portfoliosRepo->findOneBy(['user' => $user])
            ?? (new Portfolios())->setUser($user)->setVisible(true)->setViews(0);

        // Build an array of existing components
        $blocks = [];
        foreach ($portfolio->getPortfolioComponents() as $component) {
            $blocks[] = [
                'id' => $component->getId(),
                'type' => $component->getComponentType(),
                'content' => $component->getContent(),
            ];
        }

        return $this->json([
            'blocks' => $blocks,
            'layout_config' => $portfolio->getLayoutConfig() ?? [],
            'visible' => $portfolio->isVisible(),
        ]);
    }


    #[Route('/portfolio/{id}', name: 'show_portfolio')]
    public function viewPortfolio(int $id, PortfoliosRepository $portfoliosRepo): Response
    {
        $portfolio = $portfoliosRepo->find($id) ?? throw $this->createNotFoundException();
    
        // Build array of blocks
        $blocks = [];
        foreach ($portfolio->getPortfolioComponents() as $component) {
            $blocks[] = [
                'type' => $component->getComponentType(),
                'content' => $component->getContent(),
            ];
        }
    
        return $this->render('portfolio/show.html.twig', [
            'portfolio' => $portfolio,
            'blocksJson' => json_encode($blocks), // This is a JSON string
        ]);
    }

    #[Route('/api/portfolios/save', name: 'portfolio_save', methods: ['POST'])]
    public function saveComponents(
        Request $request,
        PortfoliosRepository $portfoliosRepository,
        PortfolioComponentsRepository $componentsRepo,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        // Find or create the user's portfolio
        $portfolio = $portfoliosRepository->findOneBy(['user' => $user])
            ?? (new Portfolios())->setUser($user)->setVisible(true)->setViews(0);
        $em->persist($portfolio);

        $data = json_decode($request->getContent(), true) ?? [];
        $blocks = $data['blocks'] ?? [];
        $layoutConfig = $data['layout_config'] ?? [];

        $updatedIds = [];

        foreach ($blocks as $block) {
            $componentId = $block['id'] ?? null;
            $type = $block['type'] ?? 'generic';

            $component = null;
            if ($componentId) {
                // Attempt to find existing component
                $component = $componentsRepo->find($componentId);
                // Check if it belongs to this portfolio
                if ($component && $component->getPortfolioId()->getId() !== $portfolio->getId()) {
                    $component = null;
                }
            }

            if ($component) {
                // Update existing
                $this->updateComponentByType($component, $block);
                $updatedIds[] = $component->getId();
            } else {
                // Create new
                $component = $this->createComponentByType($block, $portfolio);
                $em->persist($component);
                $em->flush(); // to get its ID
                $updatedIds[] = $component->getId();
            }
        }

        // We do not remove old components that are not mentioned
        $portfolio->setLayoutConfig($layoutConfig);
        $portfolio->setUpdatedAt(new \DateTimeImmutable());
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'Portfolio updated.',
            'updatedIds' => $updatedIds,
        ]);
    }

    // ------------------------------------------------------------------------------------
    // CREATE HELPER
    // ------------------------------------------------------------------------------------
    private function createComponentByType(array $block, Portfolios $portfolio): PortfolioComponents
    {
        $type = $block['type'] ?? 'generic';

        $component = new PortfolioComponents();
        $component->setPortfolioId($portfolio)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

        return match ($type) {
            'hero_section'   => $this->makeHeroSection($component, $block),
            'video_embed'    => $this->makeVideoEmbed($component, $block),
            'social_links'   => $this->makeSocialLinks($component, $block),
            'gallery_card'   => $this->makeGalleryCard($component, $block),
            'item_card'      => $this->makeItemCard($component, $block),
            default          => $this->makeGeneric($component, $block),
        };
    }

    private function makeHeroSection(PortfolioComponents $c, array $block): PortfolioComponents
    {
        $c->setComponentType('hero_section');
        $c->setContent($block['content'] ?? []);
        return $c;
    }

    private function makeVideoEmbed(PortfolioComponents $c, array $block): PortfolioComponents
    {
        $c->setComponentType('video_embed');
        $c->setContent([
            'embed_code' => $block['content']['embed_code'] ?? '',
        ]);
        return $c;
    }

    private function makeSocialLinks(PortfolioComponents $c, array $block): PortfolioComponents
    {
        $c->setComponentType('social_links');
        $c->setContent($block['content'] ?? []);
        return $c;
    }

    private function makeGalleryCard(PortfolioComponents $c, array $block): PortfolioComponents
    {
        $c->setComponentType('gallery_card');
        $c->setContent($block['content'] ?? []);
        return $c;
    }

    private function makeItemCard(PortfolioComponents $c, array $block): PortfolioComponents
    {
        $c->setComponentType('item_card');
        $c->setContent($block['content'] ?? []);
        return $c;
    }

    private function makeGeneric(PortfolioComponents $c, array $block): PortfolioComponents
    {
        $c->setComponentType($block['type'] ?? 'generic');
        $c->setContent($block['content'] ?? []);
        return $c;
    }

    // ------------------------------------------------------------------------------------
    // UPDATE HELPER
    // ------------------------------------------------------------------------------------
    private function updateComponentByType(PortfolioComponents $component, array $block): void
    {
        $component->setUpdatedAt(new \DateTimeImmutable());
        $type = $block['type'] ?? 'generic';

        match ($type) {
            'hero_section'  => $this->updateHeroSection($component, $block),
            'video_embed'   => $this->updateVideoEmbed($component, $block),
            'social_links'  => $this->updateSocialLinks($component, $block),
            'gallery_card'  => $this->updateGalleryCard($component, $block),
            'item_card'     => $this->updateItemCard($component, $block),
            default         => $this->updateGeneric($component, $block),
        };
    }

    private function updateHeroSection(PortfolioComponents $c, array $block): void
    {
        $old = $c->getContent() ?? [];
        $new = $block['content'] ?? [];
        $c->setContent(array_merge($old, $new));
    }

    private function updateVideoEmbed(PortfolioComponents $c, array $block): void
    {
        $content = $c->getContent() ?? [];
        $content['embed_code'] = $block['content']['embed_code'] ?? $content['embed_code'] ?? '';
        $c->setContent($content);
    }

    private function updateSocialLinks(PortfolioComponents $c, array $block): void
    {
        $old = $c->getContent() ?? [];
        $new = $block['content'] ?? [];
        $c->setContent(array_merge($old, $new));
    }

    private function updateGalleryCard(PortfolioComponents $c, array $block): void
    {
        $old = $c->getContent() ?? [];
        $new = $block['content'] ?? [];
        if (!empty($new)) {
            $merged = array_merge($old, $new);
            $c->setContent($merged);
        }
    }

    private function updateItemCard(PortfolioComponents $c, array $block): void
    {
        $old = $c->getContent() ?? [];
        $new = $block['content'] ?? [];
        $c->setContent(array_merge($old, $new));
    }

    private function updateGeneric(PortfolioComponents $c, array $block): void
    {
        $old = $c->getContent() ?? [];
        $new = $block['content'] ?? [];
        $c->setContent(array_merge($old, $new));
    }

    /**
     * Route for uploading hero images.
     */
    #[Route('/api/uploads/hero', name: 'upload_hero_image', methods: ['POST'])]
    public function uploadHeroImage(Request $request): Response
    {
        $file = $request->files->get('file');
        if (!$file) {
            return $this->json(['error' => 'No file provided'], 400);
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = preg_replace('/[^a-zA-Z0-9-_]/', '_', $originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $this->getParameter('hero_images_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            return $this->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
        }

        return $this->json(['filename' => $newFilename]);
    }

    /**
     * Route for uploading gallery images.
     */
    #[Route('/api/uploads/gallery', name: 'upload_gallery_image', methods: ['POST'])]
    public function uploadGalleryImage(Request $request): Response
    {
        $file = $request->files->get('file');
        if (!$file) {
            return $this->json(['error' => 'No file provided'], 400);
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = preg_replace('/[^a-zA-Z0-9-_]/', '_', $originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $this->getParameter('gallery_images_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            return $this->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
        }

        return $this->json(['filename' => $newFilename]);
    }
}
