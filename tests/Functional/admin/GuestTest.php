<?php

namespace App\Tests\Functional\admin;

use App\Tests\Functional\FunctionalTestCase;

final class GuestTest extends FunctionalTestCase
{
    public function testGuestIsForbidden()
    {
        $this->loginGuest();
        $this->get('/admin/guests');
        self::assertResponseStatusCodeSame(403);
    }
    public function testAdminShouldGetGuests(){
        $this->loginAdmin();
        $this->get('/admin/guests');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('main h1', 'Invités !');
        self::assertSelectorCount(50,'table tbody tr');
    }

}