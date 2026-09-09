@props(['testimonials'])

@php
    // Filter out testimonials missing required keys to prevent errors
    $validTestimonials = array_filter($testimonials, function($t) {
        return isset($t['name']) && isset($t['text']);
    });
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('storefront/css/swiper-bundle.min.css?v='.app_version()) }}">
@endpush
@push('before_scripts')
    <script src="{{ asset('storefront/js/swiper-bundle.min.js?v='.app_version()) }}"></script>
@endpush

@if(count($validTestimonials) > 0)
<div class="swiper pb-5 swiper-initialized swiper-horizontal swiper-backface-hidden " data-pov-component="testimonials-carousel" data-swiper='{
    "slidesPerView": 1,
    "spaceBetween": 24,
    "loop": true,
    "navigation": {
        "prevEl": "#testimonial-prev",
        "nextEl": "#testimonial-next"
    },
    "pagination": {
        "el": ".swiper-pagination",
        "clickable": true
    },
    "breakpoints": {
        "460": {
            "slidesPerView": 2,
            "spaceBetween": 16
        },
        "768": {
            "slidesPerView": 2,
            "spaceBetween": 24
        },
        "860": {
            "slidesPerView": 3
        },
        "1200": {
            "slidesPerView": 4
        }
    }
}'>
    <div class="swiper-wrapper" id="swiper-wrapper-testimonials" aria-live="polite">
        @foreach($validTestimonials as $testimonial)
            <div class="swiper-slide h-auto no-opacity" role="group">
                <!-- Review: Variant 1 -->
                <div class="card h-100 hover-effect-scale bg-transparent">
                    <div class="card-body">
                        <p class="fs-sm pb-2 mb-1">"{{ $testimonial['text'] }}"</p>
                        <div class="d-flex gap-1 fs-sm pb-2 mb-1">
                            @for($i=0; $i<5; $i++)
                                <i class="fi-star-filled text-warning"></i>
                            @endfor
                        </div>
                        <div class="mb-2">
                            <h6 class="mb-0">{{ $testimonial['name'] }}</h6>
                            @if (isset($testimonial['bio']))
                                <p class="text-body-secondary fs-sm ms-auto mb-0">{{ $testimonial['bio'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination (Bullets) -->
    <div class="swiper-pagination position-static pt-lg-1 mt-3 mt-sm-4 swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal">
        @foreach($validTestimonials as $testimonial)
            <span class="swiper-pagination-bullet"></span>
        @endforeach
    </div>
    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
</div>
@endif
