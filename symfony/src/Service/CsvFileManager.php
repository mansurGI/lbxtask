<?php

namespace App\Service;

use App\Storage\Exceptions\NotFoundException;
use App\Storage\Exceptions\UnableToSaveException;
use App\Storage\StorageInterface;

class CsvFileManager
{
    public function __construct(
        private readonly StorageInterface $storage,
    )
    {
    }

    /**
     * @param resource $content
     * @return string
     * @throws UnableToSaveException
     */
    public function upload($content): string
    {
        $name = $this->generateName();

        $this->storage->save($content, $name);

        return $name;
    }

    /**
     * @param string $name
     * @return resource
     * @throws NotFoundException
     */
    public function download(string $name)
    {
        return $this->storage->get($name);
    }

    private function generateName(): string
    {
        return uniqid('csv_') . '.csv';
    }

}