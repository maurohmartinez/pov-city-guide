<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;

class TestSuiteTest extends DuskTestCase
{
    /**
     * @throws Throwable
     */
    public function test_env_file_is_properly_used(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/'); // Any route works
        });

        $this->assertEquals('testing', env('APP_ENV'));

        if (config('database.default') === 'mysql') {
            $this->assertEquals('povcityguide_testing', config('database.connections.sqlite.database'));
        }
    }
}
