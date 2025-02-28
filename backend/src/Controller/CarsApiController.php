<?php
// src/Controller/CarsApiController.php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CarsApiController extends AbstractController
{
    #[Route('/api/cars', name: 'api_cars_list')]
    public function list(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->findAll();
        $data = [];

        foreach ($cars as $car) {
            $data[] = [
                'id' => $car->getId(),
                'name' => $car->getName(),
                'year' => $car->getYear(),
                'color' => $car->getColor(),
                'price' => $car->getPrice(),
            ];
        }

        return new JsonResponse($data);
    }
}