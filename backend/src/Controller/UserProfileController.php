<?php 

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    // #[Route('/user/{id}', name: 'user_profile')]
    // public function profile(int $id, UserRepository $userRepository): Response
    // {
    //     $user = $userRepository->find($id);

    //     if (!$user) {
    //         throw $this->createNotFoundException('The user does not exist');
    //     }

    //     return $this->render('user/profile.html.twig', [
    //         'user' => $user,
    //     ]);
    // }
}