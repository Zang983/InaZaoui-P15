<?php
/* This test check media entity */

namespace App\Tests\Units\Entity;


use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaTest extends TestCase
{
    public function testMediaEntity()
    {
        $media = new Media();
        $this->assertNull($media->getId());

        $media->setPath('path');
        $this->assertEquals('path', $media->getPath());

        $media->setTitle('title');
        $this->assertEquals('title', $media->getTitle());

        $user = new User();
        $media->setUser($user);
        $this->assertEquals($user, $media->getUser());

        $media->setTitle('title');
        $this->assertEquals('title', $media->getTitle());

        $album = new Album();
        $media->setAlbum($album);
        $this->assertEquals($album, $media->getAlbum());
    }

    public function testUploadedFile()
    {
        $media = new Media();
        $filePath = 'test.txt';
        file_put_contents($filePath, 'test');
        $fakeFile = new UploadedFile(
            $filePath,
            'test.txt',
            'text/plain',
            0,
            true
        );
        $media->setFile($fakeFile);
        $this->assertEquals($fakeFile, $media->getFile());
        unlink($filePath);
    }
}