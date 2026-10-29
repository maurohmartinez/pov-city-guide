<?php

use App\Models\ArticleUserFavourite;
use Livewire\Component;

new class extends Component {
    public int $favouriteCount;

    public int $articleId;

    public int $isFavourite;

    public bool $isLogged;

    public function mount(int $articleId, bool $isFavourite, int $favouriteCount): void
    {
        $this->isLogged = auth()->check();
        $this->articleId = $articleId;
        $this->favouriteCount = $favouriteCount;
        $this->isFavourite = $isFavourite;
    }

    public function toggleFavourite(): void
    {
        if (!$this->isLogged) {
            return;
        }

        if ($this->isFavourite) {
            ArticleUserFavourite::query()
                ->where('article_id', $this->articleId)
                ->where('user_id', auth()->id())
                ->delete();

            $this->isFavourite = false;
            $this->favouriteCount--;

            return;
        }

        try {
            $favourite = new ArticleUserFavourite();
            $favourite->article_id = $this->articleId;
            $favourite->user_id = auth()->id();

            $this->isFavourite = $favourite->save();
            $this->favouriteCount++;
        } catch (\Exception $e) {
            $this->isFavourite = false;
        }
    }
};
?>

<div>
    @if($this->isLogged)
        <div class="article-like-action cursor-pointer" wire:click="toggleFavourite" wire:loading.remove>
            <div class="d-flex align-items-center">
                <span>{{ $favouriteCount }}</span>
                <i class="fi-heart{{ $isFavourite ? '-filled' : ''}}"></i>
            </div>
        </div>
        <div class="article-like-action me-1" wire:loading>
            <div class="spinner-border spinner-border-sm text-custom-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @endif
</div>
