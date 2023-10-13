<?php

namespace App\Storage;

use App\Storage\Exceptions\NotFoundException;
use App\Storage\Exceptions\UnableToSaveException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class LocalStorage implements StorageInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Filesystem $filesystem,
        private readonly string $storagePath,
    )
    {
    }

    /**
     * @param resource $content
     * @param string $name
     * @return string
     * @throws UnableToSaveException
     */
    public function save($content, string $name): string
    {
        try {
            $this->filesystem->dumpFile($this->getPath($name), $content);
        } catch (\Throwable $exception) {
            $this->logger->error('Unable to save file by path = {path}', [
                'path' => $this->getPath($name),
                'exception' => $exception,
            ]);
            throw new UnableToSaveException('Unable to save file');
        }

        return $this->getPath($name);
    }

    /**
     * @param string $name
     * @return resource
     * @throws NotFoundException
     */
    public function get(string $name)
    {
        $stream = fopen($this->getPath($name), 'r');
        
        if (false === $stream) {
            throw new NotFoundException('File not found');
        }
        
        return $stream;
    }
    
    private function getPath(string $name): string
    {
        return $this->storagePath . '/' . $name;
    }
}