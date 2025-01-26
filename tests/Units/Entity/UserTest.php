<?php
/* This test check user entity */

namespace App\Tests\Units\Entity;


use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEntity()
    {
        $user = new User();
        $user->setEmail('test@test.com');
        $this->assertEquals('test@test.com', $user->getEmail());

        $user->setDescription('Description');
        $this->assertEquals('Description', $user->getDescription());

        $user->setName('Name');
        $this->assertEquals('Name', $user->getName());


    }
}