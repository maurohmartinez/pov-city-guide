<?php

namespace Tests\Browser\Backoffice\Pages;

use Laravel\Dusk\Browser;
use Tests\Browser\Utils\Page;

class LoginPage extends Page
{
    public function url(): string
    {
        return '/backoffice/login';
    }

    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url())
                ->assertVisible('@page')
                ->assertVisible('@container')
                ->assertVisible('@card')
                ->assertVisible('@form');
    }

    public function elements(): array
    {
        return [
            '@page' => '.page',
            '@container' => '.page .container',
            '@card' => '.page .container .card',
            '@form' => '.page .container .card form',
        ];
    }
}
