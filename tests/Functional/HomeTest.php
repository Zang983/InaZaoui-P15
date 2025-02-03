<?php

/* This test check album entity */

namespace App\Tests\Functional;

final class HomeTest extends FunctionalTestCase
{
    public function testHomePage(): void
    {
        $this->get('/');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('main a', 'découvrir');
    }

    public function testPublicGuestsPage(): void
    {
        $this->get('/guests');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(50, '.guest');

        for ($i = 1; $i <= 50; $i++) {
            self::assertSelectorTextContains('.guest:nth-child(' . $i . ')', 'Utilisateur ' . $i . ' (10)');
        }
    }

    public function testPublicGuestGalleryPage(): void
    {
        $this->get('/guests/2');
        $crawler = $this->client->getCrawler();
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h3', 'Utilisateur 1');
        self::assertSelectorCount(10, '.media');
        self::assertSelectorTextContains('p.mb-5.w-100', 'Description de l\'utilisateur 1');
    }

    public function testPortfolio(): void
    {
        $this->get('/portfolio');
        $crawler = $this->client->getCrawler();

        self::assertResponseIsSuccessful();
        self::assertSelectorTextSame('h3', 'Portfolio');

        /*
         * Vérification du nombre d'album
         * Check the number of album
        */
        $albumListNode = $crawler->filter('main .mb-5.row');
        self::assertCount(6, $albumListNode->filter('a'));

        /*
        Vérification du nombre total de média
        * Check the number of media
        */
        $mediaNode = $crawler->filter('.media');
        self::assertCount(50, $mediaNode->filter('img'));

        /*
        * Vérification du nombre de média dans chaque album
        * Check the number of media in each album
        */
        for ($i = 1; $i < 5; $i++) {
            $this->get('/portfolio/' . $i);
            $crawler = $this->client->getCrawler();
            $mediaNode = $crawler->filter('.media');
            self::assertCount(10, $mediaNode->filter('img'));
        }
    }

    public function testAboutPage(): void
    {
        $this->get('/about');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h2', 'Qui suis-je ?');
        $description = 'Ina Zaoui est une photographe globe-trotteuse, réputée pour son engagement à explorer les paysages du monde entier en utilisant exclusivement des moyens non motorisés tels que la marche, le vélo ou la voile';
        self::assertSelectorTextContains('.about-description', $description);
    }

}