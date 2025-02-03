<?php

namespace App\Tests\Functional\admin;

use App\Tests\Functional\FunctionalTestCase;

final class SecurityTest extends FunctionalTestCase
{

    public function testAdminLoginWithForm(): void
    {
        $this->get('/login');
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Connexion', [
            '_username' => 'ina@zaoui.com',
            '_password' => 'password']
        );
        self::assertResponseRedirects('/');
        $this->get('/admin/album');
        self::assertResponseIsSuccessful();
    }

    public function testLogout(): void
    {
        $this->loginAdmin();
        $this->get('/logout');
        self::assertResponseRedirects('/');
        $this->get('/admin/album');
        self::assertResponseRedirects('/login');
    }
    public function testGuestLoginWithForm(): void
    {
        $this->get('/login');
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Connexion', [
                '_username' => 'user1@example.com',
                '_password' => 'password']
        );
        self::assertResponseRedirects('/');
        $this->get('/admin/media');
        self::assertResponseIsSuccessful();
    }

}