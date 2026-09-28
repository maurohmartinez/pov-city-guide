<?php

namespace Tests\Browser\Backoffice;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BackofficeTest extends DuskTestCase
{
    use \Tests\Browser\Utils\ChecksForConsoleErrors;

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function loginAsAdmin(Browser $browser): Browser
    {
        return $browser->visit('/backoffice/login')
            ->type('email', 'admin@example.com')
            ->type('password', 'admin')
            ->press('button[type="submit"]')
            ->pause(2000) // Add a pause to wait for the redirect to complete
            ->assertPathIs('/backoffice/dashboard');
    }

    public function test_mock()
    {
        $this->assertTrue(true);
    }
}
