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
    /**
     * Display the initial page with the first set of users.
     */
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(UserRepository $userRepo): Response
    {
        // Load the first chunk of users (e.g., 9)
        $limit = 9;
        $offset = 0;

        $users = $userRepo->findBy([], null, $limit, $offset);
        $formattedUsers = $this->formatUsers($users);

        // Renders 'homepage.html.twig', passing initial chunk of users and the limit
        return $this->render('home/homepage.html.twig', [
            'initialUsers' => $formattedUsers,
            'limit' => $limit,
        ]);
    }

    /**
     * API endpoint for loading more users (infinite scroll).
     *
     * Example call: GET /api/users?page=2&limit=9
     */
    #[Route('/api/users', name: 'api_users', methods: ['GET'])]
    public function loadMoreUsers(UserRepository $userRepo, Request $request): JsonResponse
    {
        $limit = $request->query->getInt('limit', 9);
        $page = $request->query->getInt('page', 2);

        // Calculate offset based on page & limit
        $offset = ($page - 1) * $limit;

        $users = $userRepo->findBy([], null, $limit, $offset);
        $formattedUsers = $this->formatUsers($users);

        // Return JSON with the new set of users
        return $this->json($formattedUsers);
    }

    /**
     * Helper to convert User entities into arrays for twig/JSON.
     */
    private function formatUsers(array $users): array
    {
        $data = [];
        foreach ($users as $u) {
            $data[] = [
                'id' => $u->getId(),
                'username' => $u->getUsername(),
                'profilePicture' => $u->getProfilePicture(),
                // add more fields if you like
            ];
        }

        return $data;
    }
}
