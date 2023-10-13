<?php

namespace App\MessageHandler;

use App\Message\CsvUploadedMessage;
use App\Service\CsvFileManager;
use App\Service\CsvImportService\CsvImportService;
use App\Service\CsvImportService\Exceptions\MaxDatabaseInsertErrorsReached;
use App\Service\CsvImportService\Exceptions\ParsingException;
use App\Storage\Exceptions\NotFoundException;
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

    public function __invoke(CsvUploadedMessage $csvUploadedMessage): void
    {
        try {
            $stream = $this->csvFileManager->download($csvUploadedMessage->getFilename());
        } catch (NotFoundException $exception) {
            $this->logger->error('Csv file not found', [
                'exception' => $exception,
                'filename' => $csvUploadedMessage->getFilename(),
            ]);
            return;
        }

        try {
            $this->importService->process($stream);
        } catch (\Doctrine\DBAL\Exception\ConnectionException $exception) {
            $this->logger->error('Doctrine connection error', [
                'exception' => $exception,
            ]);
            throw new \LogicException('Doctrine connection error');
        } catch (\Doctrine\DBAL\Exception $exception) {
            $this->logger->error('Doctrine error', [
                'exception' => $exception,
            ]);
            return;
        } catch (MaxDatabaseInsertErrorsReached $exception) {
            $this->logger->error('Maximum insert errors reached', [
                'exception' => $exception,
                'filename' => $csvUploadedMessage->getFilename(),
            ]);
            return;
        } catch (ParsingException $exception) {
            $this->logger->error('Error on csv parsing', [
                'exception' => $exception,
                'filename' => $csvUploadedMessage->getFilename(),
            ]);
            return;
        } catch (\Throwable $exception) {
            $this->logger->error('Error on csv import', [
                'exception' => $exception,
                'filename' => $csvUploadedMessage->getFilename(),
            ]);
            return;
        }

        $this->logger->info('Completed csv import for {filename}', [
            'filename' => $csvUploadedMessage->getFilename(),
        ]);
    }
}