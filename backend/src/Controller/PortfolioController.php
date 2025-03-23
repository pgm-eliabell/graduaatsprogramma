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
    #[Route('/create-portfolio', name: 'create_portfolio')]
    public function createPortfolio(): Response
    {
        return $this->render('portfolio/create.html.twig');
    }

    #[Route('/api/portfolios/edit', name: 'edit_portfolio', methods: ['GET'])]
    public function editPortfolio(PortfoliosRepository $portfoliosRepo): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }
        $portfolio = $portfoliosRepo->findOneBy(['user' => $user])
            ?? (new Portfolios())->setUser($user)->setVisible(true)->setViews(0);
        $blocks = [];
        foreach ($portfolio->getPortfolioComponents() as $component) {
            $blocks[] = [
                'id' => $component->getId(),
                'type' => $component->getComponentType(),
                'content' => $component->getContent(),
            ];
        }
        return $this->json([
            'portfolio_id' => $portfolio->getId(),
            'name' => $portfolio->getName() ?? '',
            'blocks' => $blocks,
            'layout_config' => $portfolio->getLayoutConfig() ?? [],
            'visible' => $portfolio->isVisible(),
        ]);
    }

    #[Route('/portfolio/{id}', name: 'show_portfolio')]
    public function showPortfolio(Portfolios $portfolio): Response
    {
        return $this->render('portfolio/show.html.twig', [
            'portfolio' => $portfolio,
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
        $portfolio = $portfoliosRepository->findOneBy(['user' => $user])
            ?? (new Portfolios())->setUser($user)->setVisible(true)->setViews(0);
        $em->persist($portfolio);
        $data = json_decode($request->getContent(), true) ?? [];
        $blocks = $data['blocks'] ?? [];
        $layoutConfig = $data['layout_config'] ?? [];
        $updatedIds = [];
        foreach ($blocks as $block) {
            $componentId = $block['id'] ?? null;
            $component = null;
            if ($componentId) {
                $component = $componentsRepo->find($componentId);
                if ($component && $component->getPortfolioId()->getId() !== $portfolio->getId()) {
                    $component = null;
                }
            }
            if ($component) {
                $this->updateComponentByType($component, $block);
                $updatedIds[] = $component->getId();
            } else {
                $component = $this->createComponentByType($block, $portfolio);
                $em->persist($component);
                $em->flush();
                $updatedIds[] = $component->getId();
            }
        }
        $portfolio->setLayoutConfig($layoutConfig);
        $portfolio->setUpdatedAt(new \DateTimeImmutable());
        $em->flush();
        return $this->json([
            'success' => true,
            'message' => 'Portfolio updated.',
            'portfolio_id' => $portfolio->getId(),
            'updatedIds' => $updatedIds,
        ]);
    }

    #[Route('/api/portfolios/{id}/edit', name: 'portfolio_edit_existing', methods: ['PUT','PATCH'])]
    public function editExistingPortfolio(
        Portfolios $portfolio,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        if (!$user || $portfolio->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Unauthorized'], 403);
        }
        $data = json_decode($request->getContent(), true);
        if (isset($data['name'])) {
            $portfolio->setName($data['name']);
        }
        if (isset($data['visible'])) {
            $portfolio->setVisible((bool) $data['visible']);
        }
        $portfolio->setUpdatedAt(new \DateTimeImmutable());
        $em->flush();
        return $this->json([
            'success' => true,
            'message' => 'Portfolio updated.',
            'id' => $portfolio->getId(),
        ]);
    }

    #[Route('/api/portfolios/{id}/delete', name: 'portfolio_delete', methods: ['DELETE'])]
    public function deletePortfolio(Portfolios $portfolio, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user || $portfolio->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Unauthorized'], 403);
        }
        $em->remove($portfolio);
        $em->flush();
        return $this->json([
            'success' => true,
            'message' => 'Portfolio deleted.',
        ]);
    }

    #[Route('/api/components/{id}/edit', name: 'portfolio_component_edit', methods: ['PUT','PATCH'])]
    public function editSingleComponent(
        PortfolioComponents $component,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        if (!$user || $component->getPortfolioId()->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Unauthorized'], 403);
        }
        $data = json_decode($request->getContent(), true);
        $this->updateComponentByType($component, $data);
        $em->flush();
        return $this->json([
            'success' => true,
            'message' => 'Component updated.',
            'component_id' => $component->getId(),
        ]);
    }

    #[Route('/api/components/{id}/delete', name: 'portfolio_component_delete', methods: ['DELETE'])]
    public function deleteSingleComponent(
        PortfolioComponents $component,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        if (!$user || $component->getPortfolioId()->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Unauthorized'], 403);
        }
        $em->remove($component);
        $em->flush();
        return $this->json([
            'success' => true,
            'message' => 'Component deleted.',
        ]);
    }

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
