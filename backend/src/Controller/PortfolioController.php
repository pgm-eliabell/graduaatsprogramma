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
