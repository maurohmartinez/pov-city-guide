<?php

namespace Tests\Browser;

use Backpack\Settings\app\Models\Setting;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SmokeTest extends DuskTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testRootUrlLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Terms and Conditions');
        });
    }

//    public function testTermsUrlLoads(): void
//    {
//        $this->browse(function (Browser $browser) {
//            $browser->visit('/terms-and-conditions')
//                    ->assertSee('Terms and Conditions');
//        });
//    }
//
//    public function testPrivacyUrlLoads(): void
//    {
//        $this->browse(function (Browser $browser) {
//            $browser->visit('/privacy-policy')
//                    ->assertSee('Privacy Policy');
//        });
//    }
//
//    public function testCookiesUrlLoads(): void
//    {
//        $this->browse(function (Browser $browser) {
//            $browser->visit('/cookie-policy')
//                    ->assertSee('Cookie Policy');
//        });
//    }
}
