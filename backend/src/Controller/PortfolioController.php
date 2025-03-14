<?php

namespace App\Controller;

use App\Entity\PortfolioComponents;
use App\Entity\Portfolios;
use App\Repository\PortfoliosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/portfolio/{id}', name: 'view_portfolio')]
    public function viewPortfolio(int $id, PortfoliosRepository $portfoliosRepository): Response
    {
        $portfolio = $portfoliosRepository->find($id) ?? throw $this->createNotFoundException();
        return $this->render('portfolio/show.html.twig', ['portfolio' => $portfolio]);
    }

    #[Route('/api/portfolios/save', name: 'portfolio_save', methods: ['POST'])]
    public function saveComponents(Request $request, PortfoliosRepository $portfoliosRepository, EntityManagerInterface $em): Response
    {
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

        // Clear old components
        foreach ($portfolio->getPortfolioComponents() as $existing) {
            $em->remove($existing);
        }
        $em->flush();

        // Add new components
        foreach ($blocks as $block) {
            $this->saveComponentByType($block, $portfolio, $em);
        }

        // Update layout config and save
        $portfolio->setLayoutConfig($layoutConfig);
        $portfolio->setUpdatedAt(new \DateTimeImmutable());
        $em->flush();

        return $this->json(['success' => true, 'message' => 'Portfolio updated.']);
    }

    private function saveComponentByType(array $block, Portfolios $portfolio, EntityManagerInterface $em): void
    {
        $type = $block['type'] ?? 'generic';
        match($type) {
            'video_embed' => $this->saveVideoEmbed($block, $portfolio, $em),
            'image'       => $this->saveImageComponent($block, $portfolio, $em),
            default       => $this->saveGenericComponent($block, $portfolio, $em),
        };
    }

    private function saveVideoEmbed(array $block, Portfolios $portfolio, EntityManagerInterface $em): void
    {
        $component = new PortfolioComponents();
        $component->setPortfolioId($portfolio)
            ->setComponentType('video_embed')
            ->setContent(['embed_code' => $block['content']['embed_code'] ?? ''])

            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $em->persist($component);
    }

    private function saveImageComponent(array $block, Portfolios $portfolio, EntityManagerInterface $em): void
    {
        $component = new PortfolioComponents();
        $component->setPortfolioId($portfolio)
            ->setComponentType('image')
            ->setContent(['image_url' => $block['content']['image_url'] ?? ''])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $em->persist($component);
    }

    private function saveGenericComponent(array $block, Portfolios $portfolio, EntityManagerInterface $em): void
    {
        $component = new PortfolioComponents();
        $component->setPortfolioId($portfolio)
            ->setComponentType($block['type'] ?? 'generic')
            ->setContent($block['content'] ?? [])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $em->persist($component);
    }
}


// class PortfolioController extends AbstractController 
// {
//     // This method is for creating a new portfolio
//     #[Route('/create-portfolio', name: 'create_portfolio')]
//     public function createPortfolio(): Response
//     {
//         return $this->render('portfolio/create.html.twig');
//     }

//     // This method is for viewing a portfolio
//     #[Route('/portfolio/{id}', name: 'view_portfolio', requirements: ['id' => '\d+'])]
//     public function viewPortfolio(int $id, PortfoliosRepository $portfoliosRepository): Response
//     {
//         $portfolio = $portfoliosRepository->find($id);
//         if (!$portfolio) {
//             throw $this->createNotFoundException('Portfolio not found');
//         }
//         return $this->render('portfolio/show.html.twig', ['portfolio' => $portfolio]);
//     }

//     // This method is for saving all types of portfolio components
//     #[Route('/api/portfolios/save', name: 'portfolio_save', methods: ['POST'])]
//     public function saveComponents(
//         Request $request,
//         PortfoliosRepository $portfoliosRepository,
//         EntityManagerInterface $entityManager
//     ): Response {
//         $user = $this->getUser();
//         if (!$user) {
//             return $this->json(['error' => 'Not authenticated'], 401);
//         }

//         // Get or create the user's portfolio
//         $portfolio = $portfoliosRepository->findOneBy(['user' => $user]) ?? (new Portfolios())
//             ->setUser($user)
//             ->setVisible(true)
//             ->setViews(0);
//         $entityManager->persist($portfolio);

//         // Get JSON data
//         $data = json_decode($request->getContent(), true);
//         $blocks = $data['blocks'] ?? [];
//         $layoutConfig = $data['layout_config'] ?? [];

//         // Remove old components
//         foreach ($portfolio->getPortfolioComponents() as $existing) {
//             $entityManager->remove($existing);
//         }
//         $entityManager->flush();

//         // Save new components
//         foreach ($blocks as $block) {
//             switch ($block['type']) {
//                 case 'video_embed':
//                     $this->saveVideoEmbed($block, $portfolio, $entityManager);
//                     break;

//                 // Add more cases here for other component types
//                 case 'image':
//                     $this->saveImageComponent($block, $portfolio, $entityManager);
//                     break;

//                 // Handle other types as needed
//                 default:
//                     $this->saveGenericComponent($block, $portfolio, $entityManager);
//                     break;
//             }
//         }

//         // Save layout config
//         $portfolio->setLayoutConfig($layoutConfig);
//         $portfolio->setUpdatedAt(new \DateTimeImmutable());
//         $entityManager->flush();

//         return $this->json(['success' => true, 'message' => 'Portfolio updated.']);
//     }

//     // Method for saving a video embed component
//     private function saveVideoEmbed(array $block, Portfolios $portfolio, EntityManagerInterface $entityManager): void
//     {
//         $component = new PortfolioComponents();
//         $component->setPortfolioId($portfolio);
//         $component->setComponentType('video_embed');
//         $component->setContent([
//             'embed_code' => $block['content']['embed_code'] ?? '',
//         ]);
//         $component->setCreatedAt(new \DateTimeImmutable());
//         $component->setUpdatedAt(new \DateTimeImmutable());
//         $entityManager->persist($component);
//     }

//     // Example method for saving an image component
//     private function saveImageComponent(array $block, Portfolios $portfolio, EntityManagerInterface $entityManager): void
//     {
//         $component = new PortfolioComponents();
//         $component->setPortfolioId($portfolio);
//         $component->setComponentType('image');
//         $component->setContent([
//             'image_url' => $block['content']['image_url'] ?? '',
//         ]);
//         $component->setCreatedAt(new \DateTimeImmutable());
//         $component->setUpdatedAt(new \DateTimeImmutable());
//         $entityManager->persist($component);
//     }

//     // Generic method for handling other types of components
//     private function saveGenericComponent(array $block, Portfolios $portfolio, EntityManagerInterface $entityManager): void
//     {
//         $component = new PortfolioComponents();
//         $component->setPortfolioId($portfolio);
//         $component->setComponentType($block['type']);
//         $component->setContent($block['content'] ?? []);
//         $component->setCreatedAt(new \DateTimeImmutable());
//         $component->setUpdatedAt(new \DateTimeImmutable());
//         $entityManager->persist($component);
//     }

//     #[Route('/api/portfolios/{id}/layout', name: 'get_layout_config', methods: ['GET'])]
//     public function getLayoutConfig(int $id, PortfoliosRepository $portfoliosRepository): JsonResponse
//     {
//         $portfolio = $portfoliosRepository->find($id);
//         if (!$portfolio) {
//             return new JsonResponse(['error' => 'Portfolio not found'], 404);
//         }

//         return new JsonResponse(['layout_config' => $portfolio->getLayoutConfig()]);
//     }
// }


// // class PortfolioController extends AbstractController
// // {
// //     #[Route('/create-portfolio', name: 'create_portfolio')]
// //     function createPortfolio(EntityManagerInterface $entityManager ): Response
// //     {
// //         $portfolio = new Portfolios();
// //         $portfolio->setUser($this->getUser());
// //         $portfolio->setVisible(true);
// //         $portfolio->setViews(0);
// //         $portfolio->setCreatedAt(new \DateTimeImmutable());
// //         $portfolio->setUpdatedAt(new \DateTimeImmutable());
// //         $entityManager->persist($portfolio);
// //         $entityManager->flush();

// //         return $this->render('portfolio/create.html.twig', ['portfolio' => $portfolio]);
// //     }
// // }