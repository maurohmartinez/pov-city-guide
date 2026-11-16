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
                ->waitFor('#searchModal.show')
                // Poll until the modal covers the header's search icon
                ->waitUntil(<<<'JS'
                    (() => {
                        const icon = document.querySelector('header.header a[data-bs-target="#searchModal"]');
                        const rect = icon.getBoundingClientRect();
                        const element = document.elementFromPoint(rect.left + rect.width / 2, rect.top + rect.height / 2);

                        return Boolean(element?.closest('#searchModal'));
                    })()
                    JS)
                // Focus moves to the input on `shown.bs.modal`, i.e. once Bootstrap finished opening;
                // closing earlier is ignored while the modal is still transitioning
                ->waitUntil("document.activeElement === document.querySelector('#searchModal .pov-input')")
                ->assertMissing('[data-search-focus-proxy]');

            $viewport = "document.querySelector('meta[name=\"viewport\"]').getAttribute('content')";

            $this->assertStringContainsString('user-scalable=no', $browser->script("return {$viewport}")[0]);

            $browser->click('#searchModal .btn-close')
                ->waitUntilMissing('.modal-backdrop')
                ->waitUntil("{$viewport} === 'width=device-width, initial-scale=1.0'")
                ->click('header.header a[data-bs-target="#searchModal"]')
                ->waitFor('#searchModal.show')
                ->assertVisible('#searchModal')
                ->waitUntil("document.activeElement === document.querySelector('#searchModal .pov-input')")
                ->assertMissing('[data-search-focus-proxy]');
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
