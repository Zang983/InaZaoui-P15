<?php

/* This test check album entity */

namespace App\Tests\Functional;


use App\Tests\Functional\FunctionalTestCase;


final class HomeTest extends FunctionalTestCase
{
    public function testHomePage()
    {
        $this->get('/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('main a', 'découvrir');
    }

    public function testPublicGuestsPage()
    {
        $this->get('/guests');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorCount(50, '.guest');

        for ($i = 1; $i <= 50; $i++) {
            $this->assertSelectorTextContains('.guest:nth-child(' . $i . ')', 'Utilisateur ' . $i . ' (10)');
        }

    }
}