<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;

class SmokeTest extends DuskTestCase
{
    /**
     * @throws Throwable
     */
    public function testRootUrlLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('The new kind of city guide');
        });
    }

    /**
     * @throws Throwable
     */
    public function testTermsUrlLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/terms-and-conditions')
                ->assertSee(__('common.terms_and_conditions'));
        });
    }

    /**
     * @throws Throwable
     */
    public function testPrivacyUrlLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/privacy-policy')
                ->assertSee(__('common.privacy_policy'));
        });
    }

    /**
     * @throws Throwable
     */
    public function testCookiesUrlLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cookie-policy')
                ->assertSee(__('common.cookie_policy'));
        });
    }
}
