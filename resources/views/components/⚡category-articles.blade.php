<?php

use Illuminate\Database\Eloquent\Builder;

new class extends \Livewire\Component {

    public \App\Models\Category $category;

    public int $perPage = 7;

    public int $limit;

    public int $total;

    public function mount(): void
    {
        $this->limit = $this->perPage;
        $this->total = $this->query->count();
    }

    #[\Livewire\Attributes\Computed]
    public function items(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query
            ->latest()
            ->with('categoriesToDisplay')
            ->limit($this->limit)
            ->orderBy('rgt')
            ->get();
    }

    #[\Livewire\Attributes\Computed]
    public function query(): Builder
    {
        return \App\Models\Article::query()
            ->whereHas('categories', fn (Builder $query) => $query->whereKey($this->category->id))
            ->public();
    }

    public function loadItems(): void
    {
        $this->limit += $this->perPage;
    }
};
?>

<div>
    @if($this->items->isEmpty())
        <div class="text-dark">{{ __('common.no_articles') }}</div>
    @endif

    @foreach ($this->items as $article)
        <div class="card-surface card-variant-sm p-2 rounded mb-3">
            <div class="row position-relative">
                <div class="col-5 col-lg-4">
                    <a href="{{ route('article', $article->slug) }}" class="text-decoration-none">
                        <div class="overflow-hidden ratio-5x2 rounded hover-effect-scale">
                            <div
                                class="image-container hover-effect-target"
                                style="background-image: url('{{ $article->small_image }}');"
                            >
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-7 col-lg-8 my-auto ps-0">
                    @foreach($article->categoriesToDisplay as $category)
                        @if(!$loop->first)
                            <span class="fw-light card-category">-</span>
                        @endif
                        <a class="card-category text-decoration-none" href="{{ route('category', $category) }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                    <a href="{{ route('article', $article->slug) }}" class="text-decoration-none">
                        <p class="card-title fs-5 mb-2">{{ $article->title }}</p>
                        <p class="card-description text-light pe-4 lh-1">
                            {{ \Illuminate\Support\Str::words($article->description, 7) }}
                        </p>
                    </a>
                </div>
                <a href="{{ route('article', $article->slug) }}" class="text-decoration-none">
                    <div class="card-arrow card-arrow-positioned text-dark bottom-0">
                        <i class="fi-arrow-up-right"></i>
                    </div>
                </a>
            </div>
        </div>
    @endforeach

    @if($this->total > $this->items->count())
        <div class="text-center my-4">
            <div class="cursor-pointer" wire:click="loadItems" wire:loading.remove>
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
