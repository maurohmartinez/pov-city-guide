<?php

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

new class extends Component {

    public bool $showFilters = false;

    public int $perPage;

    public int $limit;

    public array $filterByCategoriesId = [];

    public bool $reverseOrder = false;

    public function mount(): void
    {
        $this->perPage = 4;
        $this->limit = $this->perPage;
    }

    #[\Livewire\Attributes\Computed]
    public function query(): Builder
    {
        return Article::query()
            ->public()
            ->with(['categoriesToDisplay', 'author'])
            ->when(!empty($this->filterByCategoriesId), function (Builder $query) {
                $query->whereHas('categoriesToDisplay', fn (Builder $query) => $query->whereIn('categories.id', $this->filterByCategoriesId));
            })
            ->orderBy('rgt');
    }

    #[\Livewire\Attributes\Computed]
    public function total(): int
    {
        return $this->query->count();
    }

    #[\Livewire\Attributes\Computed]
    public function items(): Collection
    {
        return $this->query
            ->orderBy('created_at', $this->reverseOrder ? 'asc' : 'desc')
            ->orderBy('id', $this->reverseOrder ? 'asc' : 'desc')
            ->limit($this->limit)
            ->get();
    }

    #[\Livewire\Attributes\Computed]
    public function categoriesToDisplay(): Collection
    {
        return Category::query()
            ->whereHas('articles', function (Builder $query) {
                $query->whereIn('articles.id', Article::query()->public()->get()->pluck('id')->toArray());
            })
            ->whereDoesntHave('children')
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

    public function toggleFilterCategory(int $id): void
    {
        if (in_array($id, $this->filterByCategoriesId)) {
            unset($this->filterByCategoriesId[array_find_key($this->filterByCategoriesId, fn ($value) => $value === $id)]);

            return;
        }

        $this->filterByCategoriesId [] = $id;
    }
};
?>

<div>
    @if($this->total > 0)
        <div class="container my-5">

            {{--Title--}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <h5 class="m-0 me-2">{{ __('common.articles') }}</h5>
                    <div wire:loading>
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div>
                    @if($this->categoriesToDisplay->count() > 0)
                        <button class="btn btn-link px-2 text-decoration-none" wire:click="toggleShowFilters">
                            <i class="fi-sliders text-custom-accent fs-3"></i>
                        </button>
                    @endif
                    <button class="btn btn-link px-2 text-decoration-none" wire:click="toggleOrder">
                        <i class="fi-sort text-custom-accent fs-3"></i>
                    </button>
                </div>
            </div>

            {{--Filters--}}
            @if($showFilters)
                <div class="d-flex flex-wrap mb-3">
                    @foreach($this->categoriesToDisplay as $category)
                        <button class="btn tags rounded-pill mb-1 d-inline me-2 @if(in_array($category->id, $this->filterByCategoriesId)) active @endif" wire:click="toggleFilterCategory({{ $category->id }})">{{ $category->name }}</button>
                    @endforeach
                </div>
            @endif

            {{--Articles Main--}}
            <div class="row mb-3">
                <div class="col-lg-{{ $this->items->count() > 1 ? 7 : 12 }} mb-3 mb-lg-0">
                    @includeWhen($this->items->isNotEmpty(), 'components.articles.hero', ['article' => $this->items->first(), 'ratio' => '5x2'])
                </div>
                @if($this->items->count() > 1)
                    <div class="col-lg-5 d-flex flex-column @if($this->items->count() > 3) justify-content-between @endif">
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
                    <div wire:loading.remove wire:target="loadItems" class="cursor-pointer" wire:click="loadItems">
                        <p class="mb-0">{{ __('common.load_more') }}</p>
                        <i class="fi-arrow-down fs-4 text-secondary"></i>
                    </div>
                    <div wire:loading class="mt-3 mb-1" wire:target="loadItems">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
