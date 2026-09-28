<?php

namespace Tests\Browser\Storefront;

use Closure;
use Facebook\WebDriver\WebDriverDimension;
use Laravel\Dusk\Browser;
use Tests\Browser\Storefront\Pages\ArticlePage;
use Tests\Browser\Storefront\Pages\CategoryPage;
use Tests\Browser\Utils\ChecksForConsoleErrors;
use Tests\DuskTestCase;
use Throwable;

class StoreFrontDesktopTest extends DuskTestCase
{
    use ChecksForConsoleErrors;

    protected array $browserSize = [1366, 768];

    /**
     * @throws Throwable
     */
    public function browse(Closure $callback): void
    {
        parent::browse(function (Browser $browser) use ($callback) {
            $browser->driver->manage()->window()->setSize(
                new WebDriverDimension($this->browserSize[0], $this->browserSize[1])
            );

            $callback($browser);
        });
    }

    /**
     * @throws Throwable
     */
    public function test_category_page_loads_correctly()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new CategoryPage);
        });
    }

    /**
     * @throws Throwable
     */
    public function test_article_page_loads_correctly()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new ArticlePage);
        });
    }
}
