<?php

use App\Models\Article;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {

    public bool $showFilters = false;

    public int $perPage;

    public int $limit;

    public int $total;

    public bool $reverseOrder = false;

    public function mount(): void
    {
        $this->perPage = 4;
        $this->limit = $this->perPage;
        $this->total = Article::query()->public()->with(['categories', 'tags', 'author'])->get()->count();
    }

    #[Computed]
    public function items(): \Illuminate\Database\Eloquent\Collection
    {
        $direction = $this->reverseOrder ? 'asc' : 'desc';

        return Article::query()
            ->public()
            ->orderBy('created_at', $direction)
            ->orderBy('id', $direction)
            ->with(['categories', 'tags', 'author'])
            ->limit($this->limit)
            ->get();
    }

    public function toggleShowFilters(): void
    {
        $this->showFilters = !$this->showFilters;
    }

    public function loadItems(): void
    {
        $this->limit += $this->perPage;
    }

    public function toggleOrder(): void
    {
        $this->reverseOrder = !$this->reverseOrder;
    }
};
?>

<div>
    @if($this->items->isNotEmpty())
        <div class="container my-5">

            {{--Title--}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="m-0">{{ __('common.articles') }}</h5>
                </div>
                <div>
                    <button class="btn btn-link px-2 text-decoration-none" wire:click="toggleShowFilters">
                        <i class="fi-sliders text-custom-accent fs-3"></i>
                    </button>
                    <button class="btn btn-link px-2 text-decoration-none" wire:click="toggleOrder">
                        <i class="fi-sort text-custom-accent fs-3"></i>
                    </button>
                </div>
            </div>

            {{--Filters--}}
            <div class="row">
                @if($showFilters)

                @endif
            </div>

            {{--Articles Main--}}
            <div class="row mb-3">
                <div class="col-lg-{{ $this->items->count() > 1 ? 7 : 12 }} mb-3 mb-lg-0">
                    @includeWhen($this->items->isNotEmpty(), 'components.articles.hero', ['article' => $this->items->first(), 'ratio' => '5x2'])
                </div>
                @if($this->items->count() > 1)
                    <div class="col-lg-5 d-flex flex-column justify-content-between">
                        @include('components.articles.horizontal-card', ['articles' => $this->items->take(4)->slice(1)])
                    </div>
                @endif
            </div>

            {{--More List--}}
            @if($this->items->count() > $this->perPage)
                <div class="col-12">
                    @include('components.articles.horizontal-card', ['articles' => $this->items->slice(4), 'ratio' => '5-2'])
                </div>
            @endif

            {{--Action Load More--}}
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
    @endif
</div>
