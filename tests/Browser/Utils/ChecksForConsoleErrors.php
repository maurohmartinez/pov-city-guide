<?php

namespace Tests\Browser\Utils;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Assert as PHPUnit;

trait ChecksForConsoleErrors
{
    /**
     * Assert that there are no JavaScript errors in the console.
     *
     * @param Browser $browser
     */
    public function assertNoConsoleErrors(Browser $browser): void
    {
        $logs = $browser->driver->manage()->getLog('browser');

        $errors = array_filter($logs, function ($log) {
            $ignored_browser_console_errors = [
                'favicon.ico - Failed to load resource: the server responded with a status of 404', // not a real problem, we don't have a favicon right now
            ];

            foreach ($ignored_browser_console_errors as $error) {
                if (str_contains($log['message'], $error)) {
                    return false;
                }
            }

            return $log['level'] === 'SEVERE' || $log['level'] === 'ERROR';
        });

        // Assert that there are no console errors
        PHPUnit::assertCount(0, $errors, 'Found JavaScript errors in console: ' . json_encode($errors, JSON_PRETTY_PRINT));
    }
}
