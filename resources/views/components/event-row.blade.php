{{-- Event list item - used for displaying events in a list format (dates, location, CTA buttons) --}}
<div data-pov-component="event-row" class="{{ $event->is_sold_out ? 'disabled' : '' }} d-md-flex align-items-center justify-content-between overflow-hidden pt-1 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
    <div class="d-flex">
        <h6 class="mb-0"><a href="{{ $event->checkoutUrl }}" class="@if($event->is_sold_out) text-muted @endif">{{ $event->formatted_date_time }}</a></h6>
        {{-- Ribbon (aligned with date/location) --}}
        @if($event->is_sold_out)
            <small>
                <span class="badge d-block bg-black ms-md-2 ms-2 mt-md-0 p-1 px-2">
                    @lang('event.sold_out')
                </span>
            </small>
        @elseif (!$event->hasEnded() && $event->ribbon_text)
            <small>
                <span class="badge d-block bg-{{ $event->ribbon_color }} ms-md-2 ms-2 mt-md-0 p-1 px-2" title="{{ $event->ribbon_text }}">
                    {{ strtoupper($event->ribbon_text) }}
                </span>
            </small>
        @endif
    </div>
    <p class="d-block d-md-inline mb-0"> <a href="{{ $event->checkoutUrl }}" class="text-muted text-decoration-none">{{ $event->formatted_location }}</a></p>
    @if ($event->is_free_entry && !$event->hasEnded())
        <a href="{{ route('event.details', $event) }}" class="d-none d-md-inline btn btn-dark pov-btn-dark btn-sm get-ticket-btn mt-sm-2 mt-md-0"><i class="fi-info me-2"></i>@lang('common.show_details')</a>
    @elseif ($event->is_sold_out || $event->hasEnded())
        <button class="d-none d-md-inline btn btn-outline-dark pov-btn-outline-dark btn-sm disabled get-ticket-btn text-uppercase mt-sm-2 mt-md-0">
            @lang($event->is_sold_out ? 'event.sold_out' : 'event.event_ended')
        </button>
    @else
        <a href="@if($event->checkout_type === \App\Enums\EventCheckoutTypeEnum::TIMESLOT){{ $event->checkoutUrl }}#get-ticket @else {{ $event->checkout_url }} @endif" class="d-none d-md-inline btn btn-dark pov-btn-dark btn-sm get-ticket-btn mt-sm-2 mt-md-0" dusk="get-tickets-button-for-current-event">
            <x-component::checkout-icon :event="$event"/>@lang('event.get_ticket')
        </a>
    @endif
</div>
