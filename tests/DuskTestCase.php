<?php

namespace Tests;

use Closure;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverDimension;
use Illuminate\Support\Collection;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;
use RuntimeException;
use Throwable;

abstract class DuskTestCase extends BaseTestCase
{
    // Static browser instance shared across all tests
    protected static ?Browser $browser;

    // Static flag to track if migrations have been run
    protected static bool $migrationsRun = false;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Set custom output directories
        Browser::$storeConsoleLogAt = __DIR__.'/Output/Browser/console';
        Browser::$storeSourceAt = __DIR__.'/Output/Browser/source';
        Browser::$storeScreenshotsAt = __DIR__.'/Output/Browser/screenshots';

        // Run migrations and seeders only once for the entire test suite
//        if (! static::$migrationsRun) {
//            $this->artisan('migrate:fresh --seed');
//
//            static::$migrationsRun = true;
//        }
    }

    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            static::upgradeChromeDriver();
            static::startChromeDriver(['--port='.static::driverPort()]);
            static::waitForChromeDriver();
        }
    }

    /**
     * The ChromeDriver endpoint, shared by prepare(), the readiness wait and driver()
     * so that all three always target the same host and port.
     */
    protected static function driverUrl(): string
    {
        return $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515';
    }

    protected static function driverHost(): string
    {
        return parse_url(static::driverUrl(), PHP_URL_HOST) ?: 'localhost';
    }

    protected static function driverPort(): int
    {
        return (int) (parse_url(static::driverUrl(), PHP_URL_PORT) ?: 9515);
    }

    /**
     * ChromeDriver needs a moment to bind its port; without this the first test of
     * every class after the first one races it and fails with a connection error.
     *
     * @throws RuntimeException when the driver is still unreachable after the timeout
     */
    protected static function waitForChromeDriver(int $timeoutMs = 5000): void
    {
        $host = static::driverHost();
        $port = static::driverPort();
        $deadline = microtime(true) + $timeoutMs / 1000;

        while (microtime(true) < $deadline) {
            // A plain TCP connect is enough: the race is on the port being bound.
            // Reading the HTTP status endpoint can hang for the full socket timeout.
            $socket = @fsockopen($host, $port, $errno, $errstr, 1);

            if ($socket !== false) {
                fclose($socket);

                return;
            }

            usleep(100000);
        }

        $process = static::$chromeProcess;

        throw new RuntimeException(sprintf(
            "ChromeDriver did not become reachable on %s:%d within %dms.\nstdout: %s\nstderr: %s",
            $host,
            $port,
            $timeoutMs,
            $process ? trim($process->getOutput()) : '(no process)',
            $process ? trim($process->getErrorOutput()) : '(no process)'
        ));
    }

    /**
     * Upgrade ChromeDriver to match the installed Chrome version.
     */
    protected static function upgradeChromeDriver(): void
    {
        // Kill any existing ChromeDriver processes first to ensure clean start
        exec('pkill -9 chromedriver 2>/dev/null');

        // Get the project root directory (one level up from tests/)
        $projectRoot = dirname(__DIR__, 1);

        // Build the full command with absolute path
        $artisan = $projectRoot.'/artisan';
        $command = escapeshellarg(PHP_BINARY).' '.escapeshellarg($artisan).' dusk:chrome-driver --detect 2>&1';

        // Execute from project root
        $currentDir = getcwd();
        chdir($projectRoot);
        exec($command, $output, $exitCode);
        chdir($currentDir);

        // Only show output if there was an error
        if ($exitCode !== 0) {
            echo "ChromeDriver upgrade failed:\n".implode("\n", $output)."\n";
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            '--start-maximized',
            '--disable-search-engine-choice-screen',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
                '--window-size=1366,768',
            ]);
        })->all());

        return RemoteWebDriver::create(
            static::driverUrl(),
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Override the default browse method to create a new browser instance for each test.
     * @throws Throwable
     */
    public function browse(Closure $callback): void
    {
        // Always create a new browser instance for each test
        $browser = new Browser($this->driver());

        // Set the browser window size
        $browser->driver->manage()->window()->setSize(
            new WebDriverDimension(1366, 768)
        );

        try {
            $callback($browser);
        } catch (Throwable $e) {
            // Always capture screenshots on failure, even in headless mode
            $this->captureFailuresFor(collect([$browser]));

            throw $e;
        } finally {
            // Close the browser after each test
            $browser->quit();
        }
    }

    /**
     * Close the browser if it exists.
     */
    public static function closeBrowser(): void
    {
        if (static::$browser) {
            static::$browser->quit();
            static::$browser = null;
        }
    }

    /**
     * Clean up after all tests have run.
     */
    public static function tearDownAfterClass(): void
    {
        static::closeBrowser();
        parent::tearDownAfterClass();
    }
}
