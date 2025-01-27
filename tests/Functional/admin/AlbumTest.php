<?php

namespace App\Tests\Functional\admin;

use App\Entity\Album;
use App\Tests\Functional\FunctionalTestCase;

final class AlbumTest extends FunctionalTestCase
{
    public function testGuestIsForbidden()
    {
        $this->loginGuest();
        $this->get('/admin/album');
        self::assertResponseStatusCodeSame(403);
        $this->get('/admin/album/add');
        self::assertResponseStatusCodeSame(403);
        $this->get('/admin/album/update/1');
        self::assertResponseStatusCodeSame(403);
        $this->get('/admin/album/delete/1');
        self::assertResponseStatusCodeSame(403);
    }

    public function testAdminIndex()
    {
        $this->loginAdmin();
        $this->get('/admin/album');
        $crawler = $this->client->getCrawler();
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('main h1', 'Albums');
        $bodyTableNode = $crawler->filter('table tbody');
        self::assertCount(5, $bodyTableNode->filter('tr'));
        $albumsName = $bodyTableNode->filter('tr td:nth-child(1)');
        self::assertCount(5, $albumsName);
        self::assertEquals('Album 1', $albumsName->eq(0)->text());
    }

    public function testAdminAdd()
    {
        $this->loginAdmin();
        $this->get('/admin/album/add');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Ajouter', [
            'album[name]' => 'Album 6'
        ]);
        self::assertResponseRedirects('/admin/album');
        $this->get('/admin/album');
        $crawler = $this->client->getCrawler();
        $bodyTableNode = $crawler->filter('table tbody');
        $albumsName = $bodyTableNode->filter('tr td:nth-child(1)');
        self::assertCount(6, $albumsName);
        self::assertEquals('Album 6', $albumsName->eq(5)->text());
    }

    public function testAdminUpdate()
    {
        $this->loginAdmin();
        $this->get('/admin/album/update/1');
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Modifier', [
            'album[name]' => 'Album 1 modifié'
        ]);
        self::assertResponseRedirects('/admin/album');
        $this->get('/admin/album');
        $crawler = $this->client->getCrawler();
        $bodyTableNode = $crawler->filter('table tbody');
        $albumsName = $bodyTableNode->filter('tr td:nth-child(1)');
        self::assertEquals('Album 1 modifié', $albumsName->eq(0)->text());
        self::assertCount(5, $albumsName);
    }

    public function testAdminDelete()
    {
        $this->loginAdmin();

        /* Création nouvel album */
        $this->get('/admin/album/add');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Ajouter', [
            'album[name]' => 'Album 6'
        ]);
        self::assertResponseRedirects('/admin/album');
        $this->client->followRedirect();
        $crawler = $this->client->getCrawler();
        $bodyTableNode = $crawler->filter('table tbody');
        $albumsName = $bodyTableNode->filter('tr td:nth-child(1)');
        self::assertCount(6, $albumsName);
        /* Fin création album*/

        $lastAlbum = $this->getEntityManager()->getRepository(Album::class)->findOneBy(['name' => 'Album 6']);
        $this->get('/admin/album/delete/'.$lastAlbum->getId());
        self::assertResponseRedirects('/admin/album');
        $this->client->followRedirect();
        $crawler = $this->client->getCrawler();
        $bodyTableNode = $crawler->filter('table tbody');
        self::assertCount(5, $bodyTableNode->filter('tr'));
    }

    public function testDeleteAlbumWichDoesNotExist()
    {
        $this->loginAdmin();
        $this->get('/admin/album/delete/-1');
        self::assertResponseRedirects('/admin/album');
    }

}