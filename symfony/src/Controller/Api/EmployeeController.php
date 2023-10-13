<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Message\CsvUploadedMessage;
use App\Repository\EmployeeRepository;
use App\Service\CsvFileManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    #[Route('/api/employee', name: 'app_employee_list', methods: ['GET', 'OPTIONS'])]
    public function list(EmployeeRepository $employeeRepository): JsonResponse
    {
        return $this->json($employeeRepository->findLatest(), Response::HTTP_OK);
    }

    #[Route('/api/employee/{eid}', name: 'app_employee_one', methods: ['GET', 'OPTIONS'])]
    public function one(#[MapEntity(mapping: ['eid' => 'eid'])] Employee $employee): JsonResponse
    {
        return $this->json($employee, Response::HTTP_OK);
    }

    #[Route('/api/employee/{eid}', name: 'app_employee_delete', methods: ['DELETE'])]
    public function delete(
        #[MapEntity(mapping: ['eid' => 'eid'])] Employee $employee,
        EntityManagerInterface $entityManager,
    ): JsonResponse
    {
        $employee->setStatus(Employee::STATUS_DELETED);

        $entityManager->persist($employee);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/employee', name: 'app_employee_import', methods: ['POST', 'OPTIONS'])]
    public function import(
        Request $request,
        CsvFileManager $csvManager,
        MessageBusInterface $bus,
        LoggerInterface $logger,
    ): JsonResponse
    {
        $content = $request->getContent(true);

        try {
            $filename = $csvManager->upload($content);
        } catch (\Throwable $exception) {
            $logger->error('Unable to save csv', [
                'exception' => $exception,
                'content_meta_data' => stream_get_meta_data($content),
            ]);
            return $this->json(['status' => 'server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $bus->dispatch(new CsvUploadedMessage($filename));

        return $this->json(['status' => 'done'], Response::HTTP_OK);
    }
}
