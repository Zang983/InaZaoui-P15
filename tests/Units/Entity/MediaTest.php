<?php
/* This test check media entity */

namespace App\Tests\Units\Entity;


use App\Entity\Media;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    public function testAlbumEntity()
    {
        $media = new Media();
        $media->setPath('path');
        $this->assertEquals('path', $media->getPath());

        $media->setTitle('title');
        $this->assertEquals('title', $media->getTitle());

        $user = new User();
        $media->setUser($user);
        $this->assertEquals($user, $media->getUser());

        $media->setTitle('title');
        $this->assertEquals('title', $media->getTitle());

    }
}