<?php

namespace App\MessageHandler;

use App\Message\CsvUploaded;
use App\Service\CsvFileManager;
use App\Service\CsvImportService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CsvUploadedHandler
{
    public function __construct(
        private readonly CsvFileManager   $csvFileManager,
        private readonly LoggerInterface  $logger,
        private readonly CsvImportService $importService,
    )
    {
    }

    public function __invoke(CsvUploaded $csvUploaded): void
    {
        $path = $this->csvFileManager->getPath($csvUploaded->getFileName());

        try {
            $this->importService->process($path);
        } catch (\Throwable $exception) {
            $this->logger->error('Error on csv import', [
                'exception' => $exception,
            ]);
            throw new \LogicException('Error on csv importing');
        }

        $this->logger->info('Completed csv import for {path}', [
            'path' => $path,
        ]);
    }
}