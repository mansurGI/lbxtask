<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EmployeeRepository $employeeRepository): JsonResponse
    {
        $output = [];
        exec('php /app/bin/phpunit', $output);
        array_map(function ($value) { echo $value . PHP_EOL; }, $output);
        dd($output);
        //dd($employeeRepository->findAll());
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/IndexController.php',
        ]);
    }
}
