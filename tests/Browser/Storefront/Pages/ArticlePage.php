<?php

namespace Tests\Browser\Storefront\Pages;

use App\Models\Article;
use Laravel\Dusk\Browser;
use Tests\Browser\Utils\Page;

class ArticlePage extends Page
{
    private Article $article;

    public function __construct()
    {
        $this->article = Article::factory()->create();
    }

    public function url(): string
    {
        return '/article/' . $this->article->slug;
    }

    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url())
                ->assertSee($this->article->title)
                ->assertVisible('@top')
                ->assertVisible('@related');
    }

    public function elements(): array
    {
        return [
            '@top' => '[dusk="top"]',
            '@related' => '[dusk="related"]',
        ];
    }
}
