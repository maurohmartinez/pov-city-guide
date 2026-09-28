<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function waitForCondition(callable $callback, $timeout = 5000, $interval = 100): bool
    {
        $start = microtime(true);

        while (microtime(true) - $start < $timeout / 1000) {
            if ($callback()) {
                return true;
            }
            usleep($interval * 1000); // Sleep for $interval milliseconds
        }

        return false;
    }
}
