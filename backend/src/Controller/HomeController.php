<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(UserRepository $userRepo): Response
    {
        $limit = 9;
        $offset = 0;
        $users = $userRepo->findBy([], null, $limit, $offset);
        $formattedUsers = $this->formatUsers($users);
        // render homepage w initial users
        return $this->render('home/homepage.html.twig', [
            'initialUsers' => $formattedUsers,
            'limit' => $limit,
        ]);
    }

    #[Route('/api/users', name: 'api_users', methods: ['GET'])]
    public function loadMoreUsers(UserRepository $userRepo, Request $request): JsonResponse
    {
        $limit = $request->query->getInt('limit', 9);
        $page = $request->query->getInt('page', 2);
        $offset = ($page - 1) * $limit;
        $users = $userRepo->findBy([], null, $limit, $offset);
        $formattedUsers = $this->formatUsers($users);
        return $this->json($formattedUsers);
    }

 
    private function formatUsers(array $users): array
    {
        $data = [];
        foreach ($users as $u) {
            $portfolio = $u->getPortfolios();
            $portfolioId = $portfolio ? $portfolio->getId() : null;
            $data[] = [
                'id' => $u->getId(),
                'username' => $u->getUsername(),
                'profilePicture' => $u->getProfilePicture(),
                'portfolioId' => $portfolioId,
            ];
        }

        return $data;
    }
}
