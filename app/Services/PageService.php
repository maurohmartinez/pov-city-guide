<?php

namespace App\Services;

use App\Models\Page;

class PageService
{
    private static Page|null $homepage = null;

    public static function getSeeAllUrl(): ?string
    {
        return self::getHomepage()?->extras['see_all_url'] ?? null;
    }

    public static function isUsingMarketplace(): bool
    {
        return config('custom.feature_marketplace') && self::getHomepage()?->template === 'pov_marketplace_homepage';
    }

    public static function hasAvailableColorModes(): bool
    {
        return self::getColorMode() === 'light_and_dark';
    }

    public static function getColorMode(): string
    {
        return config('custom.color_mode');
    }

    /*
     * When we return true, we are forcing the navbar to be light regardless of the active color mode.
     * If we return false, we let the active color mode determine the navbar color so that it can be adjusted automatically.
     */
    public static function getIsNavbarLightColor(string $navbarColorName): bool
    {
        return match (true) {
            $navbarColorName === 'transparentLight',
            $navbarColorName === 'home' => true,
            default => false,
        };
    }

    private static function getHomepage(): ?Page
    {
        if (is_null(self::$homepage) && config('custom.feature_marketplace')) {
            self::$homepage = Page::where('slug', '/')->first();
        }

        return self::$homepage;
    }
}
