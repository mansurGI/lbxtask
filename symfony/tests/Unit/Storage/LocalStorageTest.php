<?php

namespace App\Tests\Unit\Storage;

use App\Storage\LocalStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class LocalStorageTest extends TestCase
{
    /**
     * @covers \App\Storage\LocalStorage::save()
     */
    public function testSave()
    {
        $filesystemMock = $this->createMock(Filesystem::class);
        $filesystemMock->method('dumpFile')->willReturn('success-file.txt');

        $result = (new LocalStorage($filesystemMock, '/not/real/path'))
            ->save('some content', 'success-file.txt');

        $this->assertEquals('/not/real/path/success-file.txt', $result);


        $this->expectException(IOException::class);

        $filesystemMock = $this->createMock(Filesystem::class);
        $filesystemMock->method('dumpFile')->willThrowException(
            new IOException('Unable to create a file')
        );

        (new LocalStorage($filesystemMock, '/not/real/path'))
            ->save('some content', 'success-file.txt');
    }

    public function testGet()
    {
        $filesystemMock = $this->createMock(Filesystem::class);

        $result = (new LocalStorage($filesystemMock, '/not/real/path'))->get('success-file.txt');

        $this->assertEquals('/not/real/path/success-file.txt', $result);
    }
}