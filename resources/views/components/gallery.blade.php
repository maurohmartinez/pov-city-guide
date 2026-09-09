@php
    $allImages    = array_merge($mainImage ? [$mainImage] : [], $images ?? []);
    $thumbImages  = array_slice($allImages, 1);
    $galleryId    = 'gallery-narrow-' . str_replace('.', '', microtime(true));
    $thumbsId     = 'gallery-narrow-thumbs-' . str_replace('.', '', microtime(true));
    $autoplayDelay = 5000;
    $imageClass   = ($grayscale ?? false) ? 'grayscale' : '';
@endphp

@if(count($allImages) > 0)

@once
@push('styles')
    <link rel="stylesheet" href="{{ asset('storefront/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('storefront/css/glightbox.css') }}">
    <style>
        .gallery-narrow-bar-fill {
            height: 100%;
            transform-origin: left center;
            transform: scaleX(0);
            background: rgba(255, 255, 255, 0.9);
            transition: none;
        }
        .gallery-narrow-bar-fill.is-done {
            transform: scaleX(1);
        }
        .gallery-narrow-bar-fill.is-active {
            animation: gallery-narrow-fill var(--gallery-narrow-delay, 5s) linear forwards;
        }
        @keyframes gallery-narrow-fill {
            from { transform: scaleX(0); }
            to   { transform: scaleX(1); }
        }
    </style>
@endpush
@push('before_scripts')
    <script src="{{ asset('storefront/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('storefront/js/glightbox.min.js') }}"></script>
@endpush
@endonce

<div data-pov-component="gallery" class="mt-2 mb-2 mb-md-5 pov-gallery">

    {{-- Narrow: carousel with stories progress bars + thumbnails row (mobile & tablet) --}}
    <div class="d-block d-lg-none">
        <div class="swiper rounded overflow-hidden hover-effect-opacity" id="{{ $galleryId }}">
            <div class="swiper-wrapper">
                @foreach($allImages as $image)
                    <div class="swiper-slide no-opacity">
                        <div class="ratio bg-body-tertiary {{ $imageClass }}"
                             style="--fn-aspect-ratio: calc(482 / 856 * 100%); background-image: url({{ url('storage/'.$image) }}); background-size: cover; background-position: center;">
                        </div>
                    </div>
                @endforeach
            </div>

            @if(count($allImages) > 1)
                <!-- Stories-style progress bars overlaid at the top -->
                <div class="d-flex gap-1 position-absolute top-0 start-0 w-100 z-2 px-3 pt-3">
                    @foreach($allImages as $image)
                        <div class="flex-fill rounded-pill overflow-hidden" style="height: 3px; background: rgba(255,255,255,0.35);">
                            <div class="gallery-narrow-bar-fill {{ $loop->first ? 'is-active' : '' }}"
                                 style="--gallery-narrow-delay: {{ $autoplayDelay }}ms"></div>
                        </div>
                    @endforeach
                </div>

                <!-- Prev / next buttons -->
                <div class="position-absolute top-50 start-0 z-2 translate-middle-y ms-3 ms-sm-4 hover-effect-target opacity-0">
                    <button type="button" class="btn {{ $galleryId }}-prev btn-icon btn-secondary bg-body border-0 rounded-circle animate-slide-start" aria-label="Prev" data-bs-theme="light">
                        <i class="fi-chevron-left fs-lg animate-target"></i>
                    </button>
                </div>
                <div class="position-absolute top-50 end-0 z-2 translate-middle-y me-3 me-sm-4 hover-effect-target opacity-0">
                    <button type="button" class="btn {{ $galleryId }}-next btn-icon btn-secondary bg-body border-0 rounded-circle animate-slide-end" aria-label="Next" data-bs-theme="light">
                        <i class="fi-chevron-right fs-lg animate-target"></i>
                    </button>
                </div>
            @endif
        </div>

        @if(count($allImages) > 1)
            <!-- Thumbnails row -->
            <div class="swiper swiper-load swiper-thumbs pt-2 mt-1" id="{{ $thumbsId }}">
                <div class="swiper-wrapper">
                    @foreach($allImages as $image)
                        <div class="swiper-slide swiper-thumb overflow-hidden rounded no-opacity">
                            <div class="ratio bg-body-tertiary {{ $imageClass }}" style="--fn-aspect-ratio: calc(115 / 156 * 100%); background-image: url({{ url('storage/'.$image) }}); background-size: cover; background-position: center;">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Wide: main image + thumbnail grid with lightbox (desktop) --}}
    <div class="d-none d-lg-block">
        <div class="row g-3 g-lg-4 pb-sm-2">
            <div class="{{ count($thumbImages) > 0 ? 'col-md-8' : 'col-12' }}">
                <a class="hover-effect-scale hover-effect-opacity position-relative d-flex rounded overflow-hidden"
                   href="{{ url('storage/'.$allImages[0]) }}" data-glightbox data-gallery="image-gallery">
                    <i class="fi-zoom-in hover-effect-target fs-3 text-white position-absolute top-50 start-50 translate-middle opacity-0 z-2"></i>
                    <span class="hover-effect-target position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-25 opacity-0 z-1"></span>
                    <div class="ratio hover-effect-target bg-body-tertiary rounded {{ $imageClass }}"
                         style="--fn-aspect-ratio: calc(432 / 856 * 100%); background-image: url({{ url('storage/'.$allImages[0]) }}); background-size: cover; background-position: center;">
                    </div>
                    @if(count($allImages) > 1)
                        <div class="btn btn-sm btn-light pe-none position-absolute start-0 bottom-0 z-2 mb-3 ms-3">
                            <i class="fi-camera fs-sm me-1 ms-n1"></i>
                            {{ count($allImages) }}
                        </div>
                    @endif
                </a>
            </div>

            @if(count($thumbImages) > 0)
                <div class="col-md-4">
                    <div class="row row-cols-2 g-3 g-lg-4">
                        @foreach(array_slice($thumbImages, 0, 4) as $image)
                            <div class="col">
                                <a class="hover-effect-scale hover-effect-opacity position-relative d-flex rounded overflow-hidden"
                                   href="{{ url('storage/'.$image) }}" data-glightbox data-gallery="image-gallery">
                                    <i class="fi-zoom-in hover-effect-target fs-3 text-white position-absolute top-50 start-50 translate-middle opacity-0 z-2"></i>
                                    <span class="hover-effect-target position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-25 opacity-0 z-1"></span>
                                    <div class="ratio hover-effect-target bg-body-tertiary rounded {{ $imageClass }}"
                                         style="--fn-aspect-ratio: calc(204 / 196 * 100%); background-image: url({{ url('storage/'.$image) }}); background-size: cover; background-position: center;">
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Hidden links for images beyond the 4 displayed thumbs, so they appear in the lightbox --}}
            @foreach(array_slice($thumbImages, 4) as $image)
                <a href="{{ url('storage/'.$image) }}" data-glightbox data-gallery="image-gallery" style="display:none"></a>
            @endforeach
        </div>
    </div>

</div>

@if(count($allImages) > 1)
    @push('scripts')
        <script>
        (function () {
            const delay = {{ $autoplayDelay }};
            const fills = document.querySelectorAll('#{{ $galleryId }} .gallery-narrow-bar-fill');

            function updateBars(index) {
                fills.forEach((fill, i) => {
                    fill.classList.remove('is-active', 'is-done');
                    fill.style.animationPlayState = '';
                    if (i < index) {
                        fill.classList.add('is-done');
                    } else if (i === index) {
                        void fill.offsetWidth; // force reflow to restart animation
                        fill.classList.add('is-active');
                    }
                });
            }

            const thumbsSwiper = new Swiper('#{{ $thumbsId }}', {
                loop: false,
                spaceBetween: 16,
                slidesPerView: 3,
                watchSlidesProgress: true,
                observer: true,
                observeParents: true,
                breakpoints: {
                    340: { slidesPerView: 4 },
                    500: { slidesPerView: 5 },
                    600: { slidesPerView: 6 },
                },
            });

            const swiper = new Swiper('#{{ $galleryId }}', {
                loop: false,
                observer: true,
                observeParents: true,
                autoplay: {
                    delay: delay,
                    disableOnInteraction: false,
                },
                navigation: {
                    prevEl: '.{{ $galleryId }}-prev',
                    nextEl: '.{{ $galleryId }}-next',
                },
                thumbs: {
                    swiper: thumbsSwiper,
                },
                on: {
                    slideChange: function () {
                        updateBars(this.activeIndex);
                    },
                },
            });
        })();
        </script>
    @endpush
@endif

@endif
