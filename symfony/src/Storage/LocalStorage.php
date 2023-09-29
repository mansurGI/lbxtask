<?php

namespace App\Storage;

use Symfony\Component\Filesystem\Filesystem;

class LocalStorage implements StorageInterface
{
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly string $storagePath,
    )
    {
    }

    public function save($content, $name): string
    {
        $this->filesystem->dumpFile($this->storagePath . '/' . $name, $content);

        return $this->storagePath . '/' . $name;
    }

    public function get($name): string
    {
        return $this->storagePath . '/' . $name;
    }
}