<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CarsApiController extends AbstractController
{
    #[Route ('/api/cars')]
    public function getCars(): Response
    {
        $cars = [
            [
                'name' => 'BMW',
                'model' => 'X5',
                'year' => 2021,
                'color' => 'black',
                'price' => 100000,
            ],
            [
                'name' => 'Audi',
                'model' => 'A6',
                'year' => 2020,
                'color' => 'white',
                'price' => 80000,
            ],
            [
                'name' => 'Mercedes',
                'model' => 'S-Class',
                'year' => 2022,
                'color' => 'blue',
                'price' => 120000,
            ],
        ];
        return $this -> json($cars);

    }
}