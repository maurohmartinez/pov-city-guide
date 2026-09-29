<?php

namespace Tests\Browser\Storefront\Pages;

use App\Models\Category;
use Laravel\Dusk\Browser;
use Tests\Browser\Utils\Page;

class CategoryPage extends Page
{
    use \Tests\Browser\Utils\ChecksForConsoleErrors;

    private Category $category;

    public function __construct()
    {
        $this->category = Category::factory()->create();
    }

    public function url(): string
    {
        return '/category/' . $this->category->slug;
    }

    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url())
            ->assertSee($this->category->name)
            ->assertVisible('@top')
            ->assertVisible('@related');

        $this->assertNoConsoleErrors($browser);
    }

    public function elements(): array
    {
        return [
            '@top' => '[dusk="top"]',
            '@related' => '[dusk="related"]',
        ];
    }
}
