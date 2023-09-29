<?php

namespace App\Service;

use App\Storage\StorageInterface;

class CsvFileManager
{
    public function __construct(
        private readonly StorageInterface $storage,
    )
    {
    }

    public function upload($content): string
    {
        $name = $this->generateName();

        $this->storage->save($content, $name);

        return $name;
    }

    public function getPath($name)
    {
        return $this->storage->get($name);
    }

    private function generateName(): string
    {
        return uniqid('csv_') . '.csv';
    }

}