
<?php

use App\Services\VersionService;
use Carbon\Carbon;
use Illuminate\Support\Str;

if (!function_exists('datetime_to_string')) {
    function datetime_to_string(?Carbon $date = null): string
    {
        if ($date === null) {
            return '-';
        }

        return ucfirst(
            now()->parse($date)
                ->locale(config('app.locale'))
                ->isoFormat('dddd, DD MMM YYYY, [ora] HH:mm')
        );
    }
}

if (!function_exists('money_to_string')) {
    function money_to_string(float $amount, int $decimals = 2, bool $omitZeroDecimals = false): string
    {
        // Round the amount first if decimals are set to 0
        if ($decimals === 0) {
            return (string)round($amount);
        }

        // Format the price with the specified decimals
        $formattedPrice = number_format((float)$amount, $decimals, '.', '');

        // If the flag is set to omit .00 and the price ends in .00, strip the decimals
        if ($omitZeroDecimals && (int)$amount == $amount) {
            return number_format((int)$amount);
        }

        return $formattedPrice;
    }
}

if (!function_exists('minimize_string')) {
    function minimize_string(string $string, int $beginning = 30, int $end = 30): string
    {
        if (Str::length($string) <= ($beginning + $end)) {
            return $string;
        }

        return Str::substr($string, 0, $beginning) . ' [...] ' . Str::substr($string, -$end);
    }
}

if (!function_exists('app_version')) {
    /**
     * Get the application version for cache busting.
     * Uses VersionService which caches the git-based version for 24 hours.
     *
     * @return string
     */
    function app_version(): string
    {
        return app(VersionService::class)->getVersion();
    }
}
