<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    #[Route('/api/employee', name: 'app_employee_list', methods: ['GET', 'OPTIONS'])]
    public function list(EmployeeRepository $employeeRepository): JsonResponse
    {
        return $this->json($employeeRepository->findAll(), 200);
    }

    #[Route('/api/employee/{uid}', name: 'app_employee_one', methods: ['GET', 'OPTIONS'])]
    public function one(Employee $employee): JsonResponse
    {
        return $this->json($employee, 200);
    }

    #[Route('/api/employee/{uid}', name: 'app_employee_delete', methods: ['DELETE'])]
    public function delete(Employee $employee, EntityManagerInterface $entityManager): JsonResponse
    {
        $employee->setStatus(Employee::STATUS_DELETED);

        $entityManager->persist($employee);
        $entityManager->flush();

        return $this->json(null, 204);
    }
}
