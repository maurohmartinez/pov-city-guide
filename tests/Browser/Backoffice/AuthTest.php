<?php

namespace Tests\Browser\Backoffice;

use Laravel\Dusk\Browser;
use Tests\Browser\Backoffice\Pages\DashboardPage;
use Tests\Browser\Backoffice\Pages\LoginPage;
use Throwable;

class AuthTest extends BackofficeTest
{
    /**
     * @throws Throwable
     */
    public function test_login_page_loads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                ->assertSee(__('backpack::base.login'))
                ->assertSee(__('backpack::base.password'));
        });
    }

    /**
     * @throws Throwable
     */
    public function test_login_page_no_JS_errors()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage);

            $this->assertNoConsoleErrors($browser);
        });
    }

    /**
     * @throws Throwable
     */
    public function test_admin_can_log_in_manually()
    {
        $this->browse(function (Browser $browser) {
            $browser = $this->loginAsAdmin($browser);

            $browser->visit(new DashboardPage)
                ->waitFor('@page', 10)
                ->assertSee(__('backpack::base.logout'));

            $this->assertNoConsoleErrors($browser);
        });
    }
}
