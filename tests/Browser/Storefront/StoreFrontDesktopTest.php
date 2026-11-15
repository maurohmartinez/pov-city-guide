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
            $browser->visit(CategoryPage::simple());
            $browser->visit(CategoryPage::withSubcategories());
            $browser->visit(CategoryPage::withVideos());
        });
    }

    /**
     * @throws Throwable
     */
    public function test_search_modal_displays_over_the_header()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(CategoryPage::simple())
                ->click('header.header a[data-bs-target="#searchModal"]')
                ->assertVisible('#searchModal');

            $this->assertTrue($browser->driver->executeScript(<<<'JS'
                const header = document.querySelector('header.header');
                const rect = header.getBoundingClientRect();
                const element = document.elementFromPoint(rect.left + 1, rect.top + 1);

                return element?.classList.contains('modal-backdrop') ?? false;
            JS));

            $browser->click('#searchModal .btn-close')
                ->waitUntilMissing('.modal-backdrop')
                ->click('header.header a[data-bs-target="#searchModal"]')
                ->assertVisible('#searchModal');
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
