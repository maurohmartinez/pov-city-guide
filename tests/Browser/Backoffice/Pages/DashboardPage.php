<?php

namespace Tests\Browser\Backoffice\Pages;

use Laravel\Dusk\Browser;
use Tests\Browser\Utils\Page;

class DashboardPage extends Page
{
    public function url(): string
    {
        return '/backoffice/dashboard';
    }

    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url())
                ->assertVisible('@page')
                ->assertVisible('@pageWrapper')
                ->assertVisible('@firstNavbar')
                ->assertVisible('@secondNavbar')
                ->assertVisible('@footer');
    }

    public function elements(): array
    {
        return [
            '@page' => '.page',
            '@pageWrapper' => '.page .page-wrapper',
            '@firstNavbar' => '.page-wrapper',
            '@secondNavbar' => '.page-wrapper header.navbar-expand-lg',
            '@footer' => '.page-wrapper footer.footer',
        ];
    }
}
