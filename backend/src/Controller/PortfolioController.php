<?php

namespace App\Controller;

use App\Entity\PortfolioComponents;
use App\Entity\Portfolios;
use App\Repository\PortfoliosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/portfolio/{id}', name: 'view_portfolio', requirements: ['id' => '\d+'])]
    public function viewPortfolio(int $id, PortfoliosRepository $portfoliosRepository): Response
    {
        $portfolio = $portfoliosRepository->find($id);
        if (!$portfolio) {
            throw $this->createNotFoundException('Portfolio not found');
        }

        return $this->render('portfolio/show.html.twig', ['portfolio' => $portfolio]);
    }

    #[Route('/api/portfolios/save', name: 'portfolio_save', methods: ['POST'])]
    public function saveComponents(
        Request $request,
        PortfoliosRepository $portfoliosRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        // Find or create the user's portfolio
        $portfolio = $portfoliosRepository->findOneBy(['user' => $user]) ?? (new Portfolios())->setUser($user);
        $entityManager->persist($portfolio);

        // Parse blocks from JSON request
        $data = json_decode($request->getContent(), true);
        $blocks = $data['blocks'] ?? [];

        // Clear old components if you want a fresh save each time
        foreach ($portfolio->getPortfolioComponents() as $existing) {
            $entityManager->remove($existing);
        }
        $entityManager->flush();

        // Create new PortfolioComponents
        $position = 1;
        foreach ($blocks as $block) {
            $component = new PortfolioComponents();
            $component->setPortfolioId($portfolio);
            $component->setComponentType($block['type'] ?? 'unknown');
            $component->setContent($block['content'] ?? []);
            $component->setCreatedAt(new \DateTimeImmutable());
            $component->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($component);

            $position++;
        }

        $portfolio->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->flush();

        return $this->json(['success' => true, 'message' => 'Components saved.']);
    }
}