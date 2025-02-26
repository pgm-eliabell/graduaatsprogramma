<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/')]
    public function homepage(): Response
    {
        $myCars = [
            'name' => 'BMW',
            'model' => 'X5',
            'year' => 2021,
            'color' => 'black',
            'price' => 100000,
            
        ];
        $ProofOfConceptCount = 403;
        return $this -> render('main/homepage.html.twig', 
        [
            'ProofOfConceptCount' => $ProofOfConceptCount,
            'myCars' => $myCars,
        ]
    );
    }
}