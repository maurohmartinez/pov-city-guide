@extends('layouts.app')

@push('title', $page?->title ?? null)

@section('content')
    <div class="bg-primary">
        @include('inc.hero')

        <section class="container py-5 background-spin">
            <div class="row">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="m-0">Articles</h3>
                    </div>
                    {{--            <div>--}}
                    {{--                <a href="#" class="text-decoration-none">--}}
                    {{--                    <i class="fi-sliders text-light fs-2 me-3"></i>--}}
                    {{--                </a>--}}
                    {{--                <a href="#" class="text-decoration-none">--}}
                    {{--                    <i class="fi-sort text-light fs-2"></i>--}}
                    {{--                </a>--}}
                    {{--            </div>--}}
                </div>
                @php($firstArticle = $articles->first())
                <div class="col-md-7">
                    <div class="card-surface rounded mb-3">
                        <a href="#" class="text-decoration-none">
                            <div class="overflow-hidden ratio-5x3 rounded hover-effect-scale">
                                <div
                                    class="image-container hover-effect-target"
                                    style="background-image: url('{{ $firstArticle->medium_image }}');"
                                >
                                </div>
                            </div>
                            <div class="row position-relative p-3">
                                <h5 class="card-category text-truncate">
                                    <small>{{ implode(', ', $firstArticle->categories->pluck('name')->toArray()) }}&nbsp;</small>
                                </h5>
                                <h4 class="card-title text-truncate">{{ $firstArticle->title }}</h4>
                                <p class="card-description text-light text-truncate d-block d-lg-none">
                                    <small>Something</small>
                                </p>
                                <p class="card-description text-light d-none d-lg-block pe-4 lh-1">
                                    <small>{{ \Illuminate\Support\Str::words(strip_tags($firstArticle->content), 20) }}</small>
                                </p>
                                <hr class="card-separator">
                                <div class="w-100 d-flex justify-content-end">
                                    <div class="card-arrow text-dark">
                                        <i class="fi-arrow-up-right"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-5">
                    @include('components.category-types.cards-sm', ['articles' => $articles->slice(1)])
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            // Get all instances of Swipe and makes sure <a> links do not navigate to event
            // unless they are in focus!
            document.querySelectorAll('.swiper').forEach((swiperEl) => {
                const swiper = swiperEl.swiper;
                swiperEl.querySelectorAll('.swiper-slide a').forEach((item) => {
                    item.addEventListener('click', (e) => {
                        if (e.target.closest('.card-heart')) return;
                        // Is the item not active? Do not navigate to event!
                        if (!e.currentTarget.parentElement.classList.contains('swiper-slide-active')) {
                            e.preventDefault();
                            // Move next or previous based on item clicked
                            swiper.activeIndex < e.currentTarget.parentElement.dataset.swiperSlideIndex
                                ? swiper.slidePrev()
                                : swiper.slideNext();
                        }
                    });
                });
            });
        })();
    </script>
@endpush
