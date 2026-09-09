<div data-pov-component="event-card" class="col-sm-12 col-md">
    <article class="card h-100 shadow">
        @if($event->is_sold_out)
            <div class="d-flex flex-column gap-2 align-items-start position-absolute top-0 start-0 z-2 pt-1 pt-sm-0 ps-1 ps-sm-0 mt-2 mt-sm-3 ms-2 ms-sm-3">
                <span class="badge bg-black">@lang('event.sold_out')</span>
            </div>
        @elseif ($event->ribbon_text)
            <div class="d-flex flex-column gap-2 align-items-start position-absolute top-0 start-0 z-2 pt-1 pt-sm-0 ps-1 ps-sm-0 mt-2 mt-sm-3 ms-2 ms-sm-3">
                <span class="badge bg-{{ $event->ribbon_color }}">{{ $event->ribbon_text }}</span>
            </div>
        @endif
        <div class="bg-body-secondary rounded overflow-hidden">
            <div class="ratio hover-effect-target" style="--fn-aspect-ratio: calc(250 / 750 * 100%)">
                <a href="{{ route('event.details', $event) }}">
                    <img src="{{ asset('storage/'.($event->image_small_or_larger)) }}" loading="lazy" class="event @if($event->is_sold_out) grayscale @endif" alt="Image">
                </a>
            </div>
        </div>
        <div class="card-body pov-card-body">
            <h3 class="h5 pt-1 mb-2">
                <a class="@if ($event->is_sold_out) text-muted @endif pov-title" href="{{ route('event.details', $event) }}">{{ $event->display_title }}</a>
            </h3>
            <ul class="list-unstyled flex-row flex-wrap align-items-center gap-2 fs-sm mb-2">
                <li class="d-flex align-items-center">
                    <i class="fi-calendar me-1"></i>
                    {{ $event->formatted_date_time }}
                </li>
            </ul>
            <div class="d-flex align-items-start fs-sm">
                <i class="fi-map-pin flex-shrink-0 me-1 mt-1"></i>
                <span><a class="text-body text-decoration-none hover-effect-underline" href="{{ route('city', $event->venue->city) }}">{{ $event->venue->city->name }}</a>, {{ $event->venue->name }}</span>
            </div>
        </div>

        <div class="card-footer d-flex align-items-center justify-content-between gap-3 bg-transparent border-0 pt-0 pb-4">
            @if ($event->is_free_entry)
                <a href="{{ route('event.details', $event) }}" class="btn btn-outline-dark pov-btn-outline-dark position-relative z-2"><i class="fi-info me-2"></i>@lang('common.show_details')</a>
            @elseif ($event->is_sold_out)
                <span class="btn btn-secondary event-btn" style="cursor: not-allowed; pointer-events: none;">SOLD OUT</span>
            @else
                <a href="@if($event->checkout_type === \App\Enums\EventCheckoutTypeEnum::TIMESLOT){{ route('event.details', $event) }}#get-ticket @else {{ $event->checkout_url }} @endif" class="btn btn-outline-dark pov-btn-outline-dark position-relative z-2">
                    <x-component::checkout-icon :event="$event"/>@lang('event.get_ticket')
                </a>
            @endif
            @if ($event->is_free_entry)
                <div class="text-secondary mb-0 pov-text-secondary">
                    <span>@lang('event.free_entry')</span>
                </div>
            @elseif ($event->has_internal_ticketing && !$event->is_sold_out)
                <div class="text-secondary mb-0 pov-text-secondary">
                    <span>@lang('event.tickets_from')</span>
                    <x-component::price :amount="$event->price_from / 100" :omitZeroDecimals="true" />
                </div>
            @endif
        </div>
    </article>
</div>
