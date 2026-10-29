<?php

use Illuminate\Database\Eloquent\Builder;

new class extends \Livewire\Component {

    public \App\Models\Category $category;

    public int $perPage = 6;

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
        return \App\Models\Video::query()
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
    <div class="row">
        @foreach ($this->items as $video)
            <div class="col-md-4 mb-3">
                <div class="card-surface rounded">
                    <a href="{{ $video->data['url'] }}" target="_blank" class="text-decoration-none">
                        <div class="overflow-hidden ratio-16x9 rounded hover-effect-scale position-relative">
                            <div class="play"><i class="fi-play-circle"></i></div>
                            <div
                                dusk="image"
                                class="image-container hover-effect-target"
                                style="background-image: url('{{ $video->medium_image ?? $video['data']['image'] }}');"
                            >
                            </div>
                        </div>
                    </a>
                    <div class="row position-relative p-3">
                        <h5 class="card-category text-truncate">
                            @foreach($video->categoriesToDisplay as $category)
                                @if(!$loop->first)
                                    <span class="fw-light">-</span>
                                @endif
                                <a class="card-category text-decoration-none fw-bold" href="{{ route('category', $category) }}"><small>{{ $category->name }}</small></a>
                            @endforeach
                        </h5>
                        <a href="{{ $video->data['url'] }}" target="_blank" class="text-decoration-none">
                            <h5 class="card-title text-truncate">{{ $video->title }}</h5>
                            <p class="card-description text-light pe-4 lh-1 text-truncate-2">
                                <small>{{ \Illuminate\Support\Str::words($video->description, $words ?? 10) }}</small>
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

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
