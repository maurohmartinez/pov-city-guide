<div data-pov-component="event-chip" class="card col-12">
    <div class="card-body p-0">
        <div class="d-flex align-items-center p-3 rounded shadow-sm @if($event->is_sold_out) bg-secondary bg-opacity-10 @else bg-light-blue @endif">

            {{-- Event Image (Hidden on Small Screens) --}}
            <a href="{{ route('event.details', $event) }}" class="d-md-block flex-shrink-0 @if($event->is_sold_out) grayscale @endif ">
                <img src="{{ asset('storage/'.($event->image_small_or_larger)) }}"
                     alt="{{ $event->display_title }}"
                     class="event rounded"
                     style="width: 50px; height: 50px;">
            </a>

            {{-- Event Details --}}
            <div class="flex-grow-1 ms-3">
                <a href="{{ route('event.details', $event) }}" class="fw-bold text-primary text-decoration-none d-block mb-1 @if ($event->is_sold_out) text-muted @endif">
                    {{ $event->display_title }}

                    @if($event->is_sold_out)
                        <span class="badge bg-black ms-2 p-2">SOLD OUT</span>
                    @elseif($event->ribbon_text)
                        <span class="badge bg-{{ $event->ribbon_color }} ms-2 p-2" title="{{ $event->ribbon_text }}">{{ strtoupper($event->ribbon_text) }}</span>
                    @endif
                </a>


                {{-- Location & Date (Inline on Medium, Stacked on Mobile) --}}
                <div class="d-flex flex-wrap gap-1 align-items-center text-muted small">
                    <span class="d-flex align-items-center">
                        <img src="{{ asset('storefront/imgs/icons/location-pin.svg') }}" alt="" class="flex-shrink-0 me-1" style="width: 14px; height: 14px;">
                        <a class="text-body text-decoration-none hover-effect-underline me-n1" href="{{ route('city', $event->venue->city) }}">{{ $event->venue->city->name }}</a>, {{ $event->venue->name }}
                    </span>

                    <span class="d-flex align-items-center">
                        <img src="{{ asset('storefront/imgs/icons/calendar.svg') }}" alt="" class="flex-shrink-0 me-1" style="width: 14px; height: 14px;">
                        {{ $event->formatted_date_time }}
                    </span>
                </div>
            </div>

            {{-- Action Buttons (Hidden on Small Screens) --}}
            <div class="d-none d-lg-flex ms-3">
                @unless ($event->is_free_entry)
                    <a href="{{ route('event.details', $event) }}" class="btn btn-link @if ($event->is_sold_out) text-muted @endif">@lang('common.show_details')</a>
                @endunless

                @if ($event->is_free_entry)
                    <a href="{{ route('event.details', $event) }}" class="btn btn-primary event-btn me-2"><i class="fi-info me-2"></i>@lang('common.show_details')</a>
                @elseif ($event->is_sold_out)
                    <span class="btn btn-secondary event-btn" style="cursor: not-allowed; pointer-events: none;">SOLD OUT</span>
                @else
                    <a href="@if($event->checkout_type === \App\Enums\EventCheckoutTypeEnum::TIMESLOT){{ route('event.details', $event) }}#get-ticket @else {{ $event->checkout_url }} @endif" class="btn btn-primary event-btn me-2"><x-component::checkout-icon :event="$event"/>@lang('event.get_ticket')</a>
                @endif
            </div>
        </div>
    </div>
</div>
