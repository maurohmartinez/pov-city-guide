<?php

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

new class extends Component {

    public int $perPage;

    public int $limit;

    public string $input = '';

    public function mount(): void
    {
        $this->perPage = 5;
        $this->limit = $this->perPage;
    }

    public function updatedInput(): void
    {
        $this->limit = $this->perPage;
    }

    #[\Livewire\Attributes\Computed]
    public function query(): ?Builder
    {
        if (strlen($this->input) < 2) {
            return null;
        }

        return Article::query()
            ->public()
            ->with(['categoriesToDisplay', 'author'])
            ->withCount('favourites')
            ->where(function (Builder $query) {
                $query->whereLike('title', '%' . $this->input . '%')
                    ->orWhereLike('description', '%' . $this->input . '%')
                    ->orWhereLike('content', '%' . $this->input . '%')
                    ->orWhereHas('categories', fn (Builder $query) => $query->whereLike('name', '%' . $this->input . '%'))
                    ->orWhereHas('tags', fn (Builder $query) => $query->whereLike('name', '%' . $this->input . '%'));
            })
            ->orderBy('rgt');
    }

    #[\Livewire\Attributes\Computed]
    public function total(): int
    {
        return $this->query?->count() ?? 0;
    }

    #[\Livewire\Attributes\Computed]
    public function items(): Collection
    {
        return $this->query
            ? $this->query
                ->orderByDesc('rgt')
                ->limit($this->limit)
                ->get()
            : new Collection;
    }

    public function loadItems(): void
    {
        $this->limit += $this->perPage;
    }
};
?>

<div>
    <h5>{{ __('common.search') }}</h5>
    <input type="text" wire:model.live.debounce="input" class="form-control text-dark pov-input" placeholder="{{ __('common.type_to_search') }}...">

    <div class="w-100 mt-4" wire:loading>
        <div class="w-100 d-flex justify-content-center p-4">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    @if(strlen($this->input) > 2 && $this->total === 0)
        <div class="mt-4 text-dark">
            {{ __('common.no_articles') }}
        </div>
    @endif

    <div wire:loading.remove>
        @if(strlen($this->input) > 2 && $this->total > 0)
            {{--More List--}}
            <div class="row my-4">
                @include('components.articles.horizontal-card', [
                    'articles' => $this->items,
                    'ratio' => 'ratio-5x4',
                    'colClass' => 'col-12 col-md-6',
                ])
            </div>

            {{--Action Load More--}}
            @if($this->total > $this->items->count())
                <div class="text-center mt-5">
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
        @endif
    </div>
</div>

@push('after_scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            const modal = document.getElementById('searchModal');

            if (! modal) {
                return;
            }

            const searchInput = () => modal.querySelector('.modal-body input');
            const viewport = document.querySelector('meta[name="viewport"]');
            const defaultViewport = viewport?.getAttribute('content');
            let proxy = null;

            // Disable zooming while the modal is open, so focusing the input can't zoom the page.
            // It's restored on close, so the rest of the site stays zoomable.
            const lockZoom = () => viewport?.setAttribute('content', `${defaultViewport}, maximum-scale=1, user-scalable=no`);
            const unlockZoom = () => viewport?.setAttribute('content', defaultViewport);

            // iOS only opens the keyboard when focus() runs synchronously inside the tap, but the
            // modal input is still hidden then. Focus an invisible proxy input during the tap and
            // hand focus over to the real input once it is visible: iOS keeps the keyboard open.
            document.querySelectorAll('[data-bs-target="#searchModal"]').forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    lockZoom();
                    proxy?.remove();
                    proxy = document.createElement('input');
                    proxy.type = 'text';
                    proxy.tabIndex = -1;
                    // Not aria-hidden: a focused element must stay exposed to assistive technology
                    proxy.setAttribute('aria-label', @js(__('common.search')));
                    proxy.dataset.searchFocusProxy = '';
                    proxy.style.cssText = 'position:fixed;top:0;left:0;width:1px;height:1px;opacity:0;border:0;padding:0;font-size:16px;pointer-events:none;';
                    document.body.appendChild(proxy);
                    proxy.focus();
                });
            });

            // Bootstrap's focus trap calls modal.focus() right before `shown.bs.modal`, which would
            // blur the proxy and close the keyboard, so redirect that call to the search input.
            modal.addEventListener('show.bs.modal', () => {
                lockZoom();
                modal.focus = () => {
                    const input = searchInput();

                    input ? input.focus() : HTMLElement.prototype.focus.call(modal);
                };
            });

            modal.addEventListener('shown.bs.modal', () => {
                delete modal.focus;
                searchInput()?.focus();
                proxy?.remove();
                proxy = null;
            });

            modal.addEventListener('hidden.bs.modal', unlockZoom);
        });
    </script>
@endpush
