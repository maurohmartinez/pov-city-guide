<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * This test file will test that the browser tests are working as expected.
 * It will test that the proper env file is used and that the config
 * is properly set for each environment where we run the tests.
 *
 * @package Tests\Browser
 */
class TestSuiteTest extends DuskTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_env_file_is_properly_used(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/'); // Any route works
        });

        $this->assertEquals('testing', env('APP_ENV'));

        if (env('DB_CONNECTION') === 'mysql') {
            $this->assertEquals('povcityguide_testing', config('database.connections.sqlite.database'));
        }
    }
}
