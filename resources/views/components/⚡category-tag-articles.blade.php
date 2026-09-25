<?php

use Illuminate\Database\Eloquent\Builder;

new class extends \Livewire\Component {

    public \App\Models\Category $category;

    public ?\App\Models\Tag $tag = null;

    public int $perPage;

    public int $limit;

    public int $total;

    public function mount(): void
    {
        $this->perPage = $this->tag ? 3 : 7;
        $this->limit = $this->perPage;
        $this->total = \App\Models\Article::query()
            ->when($this->tag, function (Builder $query) {
                $query->whereHas('tags', fn (Builder $query) => $query->whereKey($this->tag->id));
            })
            ->whereHas('categories', fn (Builder $query) => $query->whereKey($this->category->id))
            ->public()
            ->count();
    }

    #[\Livewire\Attributes\Computed]
    public function items(): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Article::query()
            ->when($this->tag, function (Builder $query) {
                $query->whereHas('tags', fn (Builder $query) => $query->whereKey($this->tag->id));
            })
            ->whereHas('categories', fn (Builder $query) => $query->whereKey($this->category->id))
            ->latest()
            ->public()
            ->with('categories')
            ->limit($this->limit)
            ->get();
    }

    public function loadArticles(): void
    {
        $this->limit += 7;
    }
};
?>

<div>
    @foreach ($this->items as $paginatedArticle)
        <div class="card-surface card-variant-sm p-2 rounded mb-3">
            <div class="row position-relative">
                <div class="col-5 col-lg-4">
                    <a href="{{ route('article', $paginatedArticle->slug) }}" class="text-decoration-none">
                        <div class="overflow-hidden ratio-5x2 rounded hover-effect-scale">
                            <div
                                class="image-container hover-effect-target"
                                style="background-image: url('{{ $paginatedArticle->small_image }}');"
                            >
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-7 col-lg-8 my-auto ps-0">
                    @foreach($paginatedArticle->categories as $category)
                        @if(!$loop->first)
                            <span class="fw-light card-category">-</span>
                        @endif
                        <a class="card-category text-decoration-none" href="{{ route('category', $category) }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                    <a href="{{ route('article', $paginatedArticle->slug) }}" class="text-decoration-none">
                        <p class="card-title fs-5 mb-2">{{ $paginatedArticle->title }}</p>
                        <p class="card-description text-light pe-4 lh-1">
                            {{ \Illuminate\Support\Str::words(strip_tags($paginatedArticle->content), 7) }}
                        </p>
                    </a>
                </div>
                <a href="{{ route('article', $paginatedArticle->slug) }}" class="text-decoration-none">
                    <div class="card-arrow card-arrow-positioned text-dark bottom-0">
                        <i class="fi-arrow-up-right"></i>
                    </div>
                </a>
            </div>
        </div>
    @endforeach

    @if($this->total > $this->items->count())
        <div class="text-center my-4">
            <div class="cursor-pointer" wire:click="loadArticles" wire:loading.remove>
                <p class="mb-0">{{ __('common.load_more') }}</p>
                <i class="fi-arrow-down fs-4 text-secondary"></i>
            </div>
            <div wire:loading class="mt-3 mb-1">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    @endif
</div>
