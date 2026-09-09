@props(['production', 'eventsCount' => 0])

<article class="card hover-effect-scale hover-effect-opacity bg-body-tertiary border-0" data-pov-component="production-chip">
    <div class="row">
        <div class="col-sm-2 px-1 px-md-0">
            <a href="{{ route('production', $production) }}">
                <div class="position-relative h-100 bg-body-secondary rounded overflow-hidden">
                    <div class="ratio d-none d-lg-block" style="--fn-aspect-ratio: calc(520 / 966 * 100%)"></div>
                    <div class="ratio ratio-16x9 d-lg-none"></div>
                    <img
                        src="{{ asset('storage/'.($production->image_small_or_larger)) }}"
                        class="hover-effect-target position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
                        style="object-position: center top; @if($eventsCount === 0) filter: grayscale(100%); @endif"
                        alt="{{ $production->title }}"
                    >
                </div>
            </a>
        </div>
        <div class="col-sm-10 d-flex">
            <div class="card-body d-flex align-items-center pov-card-body">
                <div class="flex-grow-1 ms-3">
                    <a href="{{ route('production', $production) }}" class="h4 text-decoration-none hover-effect-underline d-block mb-1 pov-title">
                        {{ $production->title }}
                    </a>
                    {{-- Location & Date (Inline on Medium, Stacked on Mobile) --}}
                    <div class="d-flex flex-wrap gap-1 align-items-center text-muted small">
                        <span class="d-flex align-items-center">
                            <i class="fi-calendar text-muted me-1"></i>
                            {{ $eventsCount }} {{ $eventsCount > 1 ? __('common.events') : __('common.event') }}
                        </span>
                    </div>
                </div>

                {{-- Action Buttons (Hidden on Small Screens) --}}
                <div class="d-none d-lg-flex ms-3">
                    <a href="{{ route('production', $production) }}" class="btn btn-outline-dark pov-btn-outline-dark">@lang('common.show_details')</a>
                </div>
            </div>
        </div>
    </div>
</article>
