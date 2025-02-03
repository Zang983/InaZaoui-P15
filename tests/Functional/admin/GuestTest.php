<?php

namespace App\Tests\Functional\admin;

use App\Entity\User;
use App\Tests\Functional\FunctionalTestCase;

final class GuestTest extends FunctionalTestCase
{
    public function testGuestIsForbidden(): void
    {
        $this->loginGuest();
        $this->get('/admin/guests');
        self::assertResponseStatusCodeSame(403);
    }

    public function testAdminShouldGetGuests(): void
    {
        $this->loginAdmin();
        $this->get('/admin/guests');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('main h1', 'Invités !');
        self::assertSelectorCount(50, 'table tbody tr');
    }

    public function testAdminShouldCreateGuest(): void
    {
        $this->loginAdmin();
        $this->get('/admin/guests/add');
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Valider', [
            'guest[name]' => 'Utilisateur 51',
            'guest[email]' => 'invite+51@example.com',
            'guest[description]' => 'Description de l\'utilisateur 51',
        ]);
        self::assertResponseRedirects('/admin/guests');
        $this->client->followRedirect();
        self::assertSelectorCount(51, 'table tbody tr');

    }

    public function testAdminShouldDeleteGuest(): void
    {
        $this->loginAdmin();
        /* Création d'un utilisateur sans média */
        $this->get('/admin/guests/add');
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Valider', [
            'guest[name]' => 'Utilisateur 51',
            'guest[email]' => 'invite+51@example.com',
            'guest[description]' => 'Description de l\'utilisateur 51',
        ]);
        self::assertResponseRedirects('/admin/guests');
        $this->client->followRedirect();
        self::assertSelectorCount(51, 'table tbody tr');

        /* Fin création */
        $lastUser  = $this->getEntityManager()->getRepository(User::class)->findOneBy([], ['id' => 'DESC']);
        $lastId = $lastUser ? $lastUser->getId() : 0;
        $this->get('/admin/guests/delete/' . $lastId);
        self::assertResponseRedirects('/admin/guests');
        $this->client->followRedirect();
        self::assertSelectorCount(50, 'table tbody tr');
    }
    public function testAdminShouldNotDeleteNonExistentGuest(): void
    {
        $this->loginAdmin();
        $this->get('/admin/guests/delete/-1');
        self::assertResponseRedirects('/admin/guests');
        $this->client->followRedirect();
        self::assertSelectorCount(50, 'table tbody tr');
    }

    public function testAdminShouldBlockGuest(): void
    {
        $this->loginAdmin();
        $this->get('/admin/guests/block/2');
        /*Bloquage de l'utilisateur*/
        self::assertResponseRedirects('/admin/guests');
        $this->client->followRedirect();
        self::assertSelectorTextContains('table tbody tr:nth-child(1) td:nth-child(4)', 'Débloquer');
        /*Débloquage de l'utilisateur*/
        $this->get('/admin/guests/block/2');
        self::assertResponseRedirects('/admin/guests');
        $this->client->followRedirect();
        self::assertSelectorTextContains('table tbody tr:nth-child(1) td:nth-child(4)', 'Bloquer');
    }
    public function testAdminDoesNotBlockNonExistentGuest(): void
    {
        $this->loginAdmin();
        $this->get('/admin/guests/block/-1');
        self::assertResponseRedirects('/admin/guests');
        $this->client->followRedirect();
        self::assertSelectorTextContains('main h1', 'Invités !');
        foreach ($this->client->getCrawler()->filter('table tbody tr') as $tr) {
            self::assertStringNotContainsString('Débloquer', $tr->textContent);
        }

    }


}