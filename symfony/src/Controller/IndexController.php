<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntityManager $em): JsonResponse
    {
        dd($em->getConnection()->executeQuery('select * from users;'));
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/IndexController.php',
        ]);
    }
}
