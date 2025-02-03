<?php
/* This test check user entity */

namespace App\Tests\Units\Entity;


use App\Entity\Media;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEntity(): void
    {
        $user = new User();

        $user->getId();
        $this->assertNull($user->getId());

        $user->setEmail('test@test.com');
        $this->assertEquals('test@test.com', $user->getEmail());

        $user->setDescription('Description');
        $this->assertEquals('Description', $user->getDescription());

        $user->setName('Name');
        $this->assertEquals('Name', $user->getName());

        $user->setRoles(['ROLE_USER']);
        $this->assertEquals(['ROLE_USER'], $user->getRoles());

        $user->setPassword('password');
        $this->assertEquals('password', $user->getPassword());

        $user->setEmail('test@letest.com');
        $this->assertEquals('test@letest.com',$user->getUserIdentifier());

        $media = new Media();
        $collection = new ArrayCollection([$media]);
        $user->setMedias($collection);
        $this->assertEquals($collection, $user->getMedias());

        $state = clone $user;
        $user->eraseCredentials();
        $this->assertEquals($state, $user, 'eraseCredentials should not change the object');
    }
}