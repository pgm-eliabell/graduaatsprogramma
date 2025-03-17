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

    #[Route('/portfolio/{id}', name: 'show_portfolio')]
    public function viewPortfolio(int $id, PortfoliosRepository $portfoliosRepository): Response
    {
        $portfolio = $portfoliosRepository->find($id) ?? throw $this->createNotFoundException();
        return $this->render('portfolio/show.html.twig', ['portfolio' => $portfolio]);
    }

    #[Route('/api/portfolios/save', name: 'portfolio_save', methods: ['POST'])]
    public function saveComponents(
        Request $request,
        PortfoliosRepository $portfoliosRepository,
        PortfolioComponentsRepository $componentsRepository,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        // Fetch or create the portfolio for this user.
        $portfolio = $portfoliosRepository->findOneBy(['user' => $user])
            ?? (new Portfolios())->setUser($user)->setVisible(true)->setViews(0);
        $em->persist($portfolio);
        $data = json_decode($request->getContent(), true) ?? [];
        $blocks = $data['blocks'] ?? [];
        $layoutConfig = $data['layout_config'] ?? [];
        $updatedComponentIds = [];
        $uniqueTypes = ['hero_section'];
        foreach ($blocks as $block) {
            $id = $block['id'] ?? null;
            $type = $block['type'] ?? 'generic';
            $component = null;
            if ($id) {
                // If an ID is provided, attempt to find and update the existing component.
                $component = $componentsRepository->find($id);
                if ($component && $component->getPortfolioId()->getId() !== $portfolio->getId()) {
                    $component = null;
                }
                if ($component) {
                    $this->updateComponentByType($component, $block);
                    $updatedComponentIds[] = $component->getId();
                    continue;
                }
            } else {
                // If no ID is provided, check if a component of this type already exists.
                if (in_array($type, $uniqueTypes, true)) {
                    foreach ($portfolio->getPortfolioComponents() as $existing) {
                        if ($existing->getComponentType() === $type) {
                            $this->updateComponentByType($existing, $block);
                            $updatedComponentIds[] = $existing->getId();
                            $component = $existing;
                            break;
                        }
                    }
                }
            }

            // If no existing component was found, create a new one.
            if (!$component) {
                $component = $this->createComponentByType($block, $portfolio, $em);
                $em->persist($component);
                $em->flush(); // flush to obtain its ID
                $updatedComponentIds[] = $component->getId();
            }
        }
        $portfolio->setLayoutConfig($layoutConfig);
        $portfolio->setUpdatedAt(new \DateTimeImmutable());
        $em->flush();
        return $this->json([
            'success' => true,
            'message' => 'Portfolio updated.',
            'updatedComponentIds' => $updatedComponentIds
        ]);
    }
    private function createComponentByType(array $block, Portfolios $portfolio, EntityManagerInterface $em): PortfolioComponents
    {
        $type = $block['type'] ?? 'generic';
        $component = new PortfolioComponents();
        $component->setPortfolioId($portfolio)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

        return match($type) {
            'video_embed'   => $this->makeVideoEmbed($component, $block),
            'image'         => $this->makeImageComponent($component, $block),
            'hero_section'  => $this->makeHeroSection($component, $block),
            default         => $this->makeGeneric($component, $block),
        };
    }

    // Update an existing PortfolioComponents entity based on the block type.
    private function updateComponentByType(PortfolioComponents $component, array $block): void
    {
        $component->setUpdatedAt(new \DateTimeImmutable());
        $type = $block['type'] ?? 'generic';

        match($type) {
            'video_embed'   => $this->updateVideoEmbed($component, $block),
            'image'         => $this->updateImageComponent($component, $block),
            'hero_section'  => $this->updateHeroSection($component, $block),
            default         => $this->updateGeneric($component, $block),
        };
    }


    private function makeVideoEmbed(PortfolioComponents $c, array $block): PortfolioComponents
    {
        $c->setComponentType('video_embed');
        $c->setContent([
            'embed_code' => $block['content']['embed_code'] ?? '',
        ]);
        return $c;
    }

    private function makeImageComponent(PortfolioComponents $c, array $block): PortfolioComponents
    {
        $c->setComponentType('image');
        $c->setContent([
            'image_url' => $block['content']['image_url'] ?? '',
        ]);
        return $c;
    }

    private function makeHeroSection(PortfolioComponents $c, array $block): PortfolioComponents
    {
        $c->setComponentType('hero_section');
        $c->setContent($block['content'] ?? []); // e.g. name, occupation, profileImage, backgroundImage
        return $c;
    }

    private function makeGeneric(PortfolioComponents $c, array $block): PortfolioComponents
    {
        $c->setComponentType($block['type'] ?? 'generic');
        $c->setContent($block['content'] ?? []);
        return $c;
    }

    // ----- UPDATE HELPERS -----

    private function updateVideoEmbed(PortfolioComponents $c, array $block): void
    {
        $content = $c->getContent() ?? [];
        $content['embed_code'] = $block['content']['embed_code'] ?? $content['embed_code'] ?? '';
        $c->setContent($content);
    }

    private function updateImageComponent(PortfolioComponents $c, array $block): void
    {
        $content = $c->getContent() ?? [];
        $content['image_url'] = $block['content']['image_url'] ?? $content['image_url'] ?? '';
        $c->setContent($content);
    }

    private function updateHeroSection(PortfolioComponents $c, array $block): void
    {
        $newData = $block['content'] ?? [];
        $oldContent = $c->getContent() ?? [];
        // Merge the new data into the old data so that fields not present are preserved.
        $merged = array_merge($oldContent, $newData);
        $c->setContent($merged);
    }

    private function updateGeneric(PortfolioComponents $c, array $block): void
    {
        $newData = $block['content'] ?? [];
        $oldData = $c->getContent() ?? [];
        $merged = array_merge($oldData, $newData);
        $c->setContent($merged);
    }

    /**
     * Route for uploading hero images. Uploads file to local folder and returns the filename.
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
        } catch (\Exception $e) {
            return $this->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
        }

        return $this->json(['filename' => $newFilename]);
    }
}
