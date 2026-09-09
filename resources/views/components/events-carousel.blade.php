@props(['events'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('storefront/css/swiper-bundle.min.css?v='.app_version()) }}">
@endpush
@push('before_scripts')
    <script src="{{ asset('storefront/js/swiper-bundle.min.js?v='.app_version()) }}"></script>
@endpush

<div class="col-md-8" data-pov-component="events-carousel">
    <div
        class="swiper z-2"
        data-swiper='{
            "effect": "creative",
            "loop": true,
            "speed": 450,
            "autoplay": {
              "delay": 7000,
              "disableOnInteraction": false
            },
            "creativeEffect": {
              "prev": {
                "translate": [0, 0, -800],
                "rotate": [180, 0, 0]
              },
              "next": {
                "translate": [0, 0, -800],
                "rotate": [-180, 0, 0]
              }
            },
            "navigation": {
              "prevEl": "#hero-prev",
              "nextEl": "#hero-next"
            }
        }'>
        <div class="swiper-wrapper">
            @foreach($events as $event)
                <div class="swiper-slide no-opacity">
                    <article class="position-relative w-100 rounded overflow-hidden">
                        <div class="ratio bg-body-tertiary rtl-flip" style="--fn-aspect-ratio: calc(520 / 966 * 100%)">
                            <img src="{{ asset('storage/'.($event->image_small_or_larger)) }}" alt="Image" class="event">
                        </div>
                        <div class="position-absolute start-0 top-0 d-flex align-items-end w-100 h-100 p-xl-5">
                            <div class="p-2 p-sm-3 p-md-4 p-xl-3 m-3">
                                <ul class="list-inline fs-sm text-body mb-2" data-bs-theme="dark">
                                    <li class="d-flex align-items-center gap-1">
                                        <i class="fi-calendar"></i>
                                        {{ $event->formatted_date }}
                                    </li>
                                    <li class="d-flex align-items-center gap-1">
                                        <i class="fi-clock"></i>
                                        {{ $event->formatted_time }}
                                    </li>
                                </ul>
                                <h3 class="pb-sm-2 pb-xl-3" data-bs-theme="dark">{{ $event->display_title }}</h3>
                                @if($event->is_free_entry)
                                <a class="btn btn-lg btn-light pov-btn-light rounded-pill d-none d-sm-inline-flex" href="{{ route('event.details', $event) }}">
                                    <i class="fi-info me-1"></i><span>@lang('common.show_details')</span>
                                    <i class="fi-chevron-right fs-xl ms-1 me-n1"></i>
                                </a>
                                <a class="btn btn-light pov-btn-light rounded-pill d-sm-none" href="{{ route('event.details', $event) }}">
                                    <i class="fi-info me-1"></i><span>@lang('common.show_details')</span>
                                    <i class="fi-chevron-right fs-base ms-1 me-n1"></i>
                                </a>
                                @else
                                <a class="btn btn-lg btn-light pov-btn-light rounded-pill d-none d-sm-inline-flex" href="{{ $event->checkout_url }}">
                                    <span class="me-1">@lang('event.tickets_from')</span><x-component::price :amount="$event->price_from / 100" :omitZeroDecimals="true" />
                                    <i class="fi-chevron-right fs-xl ms-1 me-n1"></i>
                                </a>
                                <a class="btn btn-light pov-btn-light rounded-pill d-sm-none" href="{{ $event->checkout_url }}">
                                    <span class="me-1">@lang('event.tickets_from')</span><x-component::price :amount="$event->price_from / 100" :omitZeroDecimals="true" />
                                    <i class="fi-chevron-right fs-base ms-1 me-n1"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</div>
