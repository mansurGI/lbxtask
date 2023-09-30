<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use App\Service\CsvFileManager;
use App\Validator as CustomAssert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmployeeController extends AbstractController
{
    #[Route('/api/employee', name: 'app_employee_list', methods: ['GET', 'OPTIONS'])]
    public function list(EmployeeRepository $employeeRepository): JsonResponse
    {
        return $this->json($employeeRepository->findLatest(), Response::HTTP_OK);
    }

    #[Route('/api/employee/{uid}', name: 'app_employee_one', methods: ['GET', 'OPTIONS'])]
    public function one(#[MapEntity(mapping: ['uid' => 'uid'])] Employee $employee): JsonResponse
    {
        return $this->json($employee, Response::HTTP_OK);
    }

    #[Route('/api/employee/{uid}', name: 'app_employee_delete', methods: ['DELETE'])]
    public function delete(#[MapEntity(mapping: ['uid' => 'uid'])] Employee $employee, EntityManagerInterface $entityManager): JsonResponse
    {
        $employee->setStatus(Employee::STATUS_DELETED);

        $entityManager->persist($employee);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/employee', name: 'app_employee_import', methods: ['POST', 'OPTIONS'])]
    public function import(Request $request, ValidatorInterface $validator, CsvFileManager $csvManager): JsonResponse
    {
        $content = $request->getContent();

        $errors = $validator->validate($content, [
            new Assert\Sequentially([
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('string'),
                new Assert\Length(min: 1),
                new CustomAssert\IsCsv(),
            ]),
        ]);

        if (count($errors) > 0) {
            return $this->json(['status' => 'error in fields', 'errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        try {
            $path = $csvManager->upload($content);
        } catch (\Exception $exception) {
            //TODO: logging or handling exceptions
            return $this->json(['status' => 'server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //send to rabbit
        //catch any errors and send a msg to user

        return $this->json(['status' => 'done'], Response::HTTP_OK);
    }
}
