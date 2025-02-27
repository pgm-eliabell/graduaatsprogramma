<?php
// src/Controller/CarsApiController.php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarsApiController extends AbstractController
{
    #[Route('/cars', name: 'app_cars_list')]
    public function list(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findAll();

        return $this->render('cars/list.html.twig', [
            'cars' => $cars,
        ]);
    }
}