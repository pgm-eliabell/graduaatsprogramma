<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        // Default values for pagination
        $limit = 9; // Number of items per page
        $currentPage = $request->query->getInt('page', 1); // Get the current page from the query parameters

        // Calculate the offset for the query
        $offset = ($currentPage - 1) * $limit;

        // Fetch paginated users from the repository
        $users = $userRepository->findBy([], null, $limit, $offset);
        $totalUsers = $userRepository->count([]);
        $totalPages = ceil($totalUsers / $limit);

        // Prepare the data for the template
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'email' => $user->getEmail(),
                'profilePicture' => $user->getProfilePicture(),
                'bio' => $user->getBio(),
                'createdAt' => $user->getCreatedAt(),
                'updatedAt' => $user->getUpdatedAt(),
            ];
        }

        // Render the template with paginated data
        return $this->render('home/homepage.html.twig', [
            'paginatedUsers' => $data,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'users' => $data,
        ]);
    }
}
