<?php

namespace Tests\Browser\Utils;

use Laravel\Dusk\Page as BasePage;

abstract class Page extends BasePage
{
    public static function siteElements(): array
    {
        return [
            '@element' => '#selector',
        ];
    }
}
