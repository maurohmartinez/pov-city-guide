<article data-pov-component="event-horizontal-card" class="card hover-effect-scale hover-effect-opacity border-0 bg-body-tertiary p-0 mb-2">
    <div class="row g-0">
        <div class="col-3 p-0">
            <div class="position-relative h-100 bg-body-secondary rounded overflow-hidden">
                <a href="{{ route('event.details', $event) }}">
                    <div class="ratio d-none d-lg-block" style="--fn-aspect-ratio: calc(520 / 966 * 100%)"></div>
                    <div class="ratio ratio-16x9 d-lg-none"></div>
                    <img
                        src="{{ asset('storage/'.($event->image_small_or_larger)) }}"
                        loading="lazy"
                        class="hover-effect-target position-absolute top-0 start-0 w-100 h-100 object-fit-cover @if($event->is_sold_out) grayscale @endif"
                        style="object-position: center top;"
                        alt="{{ $event->display_title }}"
                    >
                </a>
            </div>
        </div>
        <div class="col-9 d-flex">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1 ms-1 ms-lg-3">
                    @if($event->is_sold_out)
                        <small>
                            <span class="badge bg-black">@lang('event.sold_out')</span>
                        </small>
                    @elseif($event->ribbon_text)
                        <small>
                            <span class="badge bg-{{ $event->ribbon_color }}">{{ $event->ribbon_text }}</span>
                        </small>
                    @endif
                    <a href="{{ route('event.details', $event) }}" class="h1 mb-0 hover-effect-underline fw-bold text-decoration-none d-block fs-5 mt-1 @if($event->is_sold_out) text-muted @endif pov-title">
                        {{ $event->display_title }}
                    </a>
                    <div class="d-md-flex align-items-center text-muted small mt-2">
                        <span class="d-flex align-items-start gap-1">
                            <i class="fi-map-pin flex-shrink-0 mt-1"></i>
                            <span><a class="text-body text-decoration-none hover-effect-underline" href="{{ route('city', $event->venue->city) }}">{{ $event->venue->city->name }}</a>, {{ $event->venue->name }}</span>
                        </span>
                        <span class="d-flex align-items-center gap-1 ms-0 ms-md-3">
                            <i class="fi-calendar text-muted"></i>
                            {{ $event->formatted_date }}
                        </span>
                        <span class="d-flex align-items-center gap-1 ms-0 ms-md-3">
                            <i class="fi-clock text-muted"></i>
                            {{ $event->formatted_time }}
                        </span>
                    </div>
                </div>

                {{-- Action Buttons (Hidden on Small Screens) --}}
                <div class="d-none d-lg-flex ms-3">
                    @unless($event->is_free_entry)
                    <a href="{{ route('event.details', $event) }}" class="btn btn btn-link text-muted pov-btn-link">@lang('common.show_details')</a>
                    @endunless
                    @if($event->is_free_entry)
                    <a href="{{ route('event.details', $event) }}" class="btn btn-dark pov-btn-dark"><i class="fi-info me-2"></i>@lang('common.show_details')</a>
                    @elseif($event->is_sold_out)
                    <a href="{{ $event->checkout_url }}" class="btn btn-outline-dark pov-btn-outline-dark disabled">@lang('event.sold_out')</a>
                    @else
                    <a href="{{ $event->checkout_url }}" class="btn btn-dark pov-btn-dark"><x-component::checkout-icon :event="$event"/>@lang('event.get_ticket')</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</article>
