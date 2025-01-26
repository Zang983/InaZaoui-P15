<?php
/* This test check album entity */

namespace App\Tests\Units\Entity;

use App\Entity\Album;
use PHPUnit\Framework\TestCase;


class AlbumTest extends TestCase
{
    public function testAlbumEntity()
    {
        $album = new Album();
        $album->setName('Album name');
        $this->assertEquals('Album name', $album->getName());
        $this->assertNull($album->getId());

    }
}