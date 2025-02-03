<?php

namespace App\Tests\Functional\admin;

use App\Entity\Album;
use App\Tests\Functional\FunctionalTestCase;

final class MediaTest extends FunctionalTestCase
{
    public function testGuestShouldShow(): void
    {
        $this->loginGuest();
        $this->get('/admin/media');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('main h1', 'Medias');
        self::assertSelectorCount(10, 'table tbody tr');
    }

    public function testAdminShouldShow(): void
    {
        $this->loginAdmin();
        $this->get('/admin/media');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('main h1', 'Medias');
        self::assertSelectorCount(25, 'table tbody tr');
    }

    public function testAdminShouldShowOnPage3(): void
    {
        $this->loginAdmin();
        $this->get('/admin/media?page=3');
        $crawler = $this->client->getCrawler();
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('main h1', 'Medias');
        self::assertSelectorCount(25, 'table tbody tr');
        $lines = $crawler->filter('tr');
        /* Vérification du contenu de la première ligne */
        self::assertStringContainsString('Titre 1', $lines->eq(1)->text());
        self::assertStringContainsString('Utilisateur 1', $lines->eq(1)->text());

        /*Vérification du contenu de la dernière ligne*/
        self::assertStringContainsString('Titre 25', $lines->eq(25)->text());
        self::assertStringContainsString('Utilisateur 25', $lines->eq(25)->text());

    }

}