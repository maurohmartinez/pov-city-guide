@extends('layouts.app')

@section('content')
    @include('inc.hero')
    @include('inc.stripe-menu')
    <section class="container background-spin">
        <div class="py-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="m-0">Articles</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                    @include('components.articles.hero', ['article' => $articles->first()])
                </div>
                <div class="col-md-5">
                    @include('components.articles.horizontal-card', ['articles' => $articles->slice(1)])
                </div>
            </div>
        </div>
        @foreach($sections as $section)
            <div class="pb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="m-0">{{ $section['category']->name }}</h5>
                    <a class="btn btn-sm btn-dark rounded-pill py-0" href="#">{{ __('common.view_all') }}</a>
                </div>
                @include('components.articles.' . $section['layout_type'], ['articles' => $section['articles']])
            </div>
        @endforeach
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            // Get all instances of Swipe and makes sure <a> links do not navigate to event
            // unless they are in focus!
            // document.querySelectorAll('.swiper').forEach((swiperEl) => {
            //     const swiper = swiperEl.swiper;
            //     swiperEl.querySelectorAll('.swiper-slide a').forEach((item) => {
            //         item.addEventListener('click', (e) => {
            //             if (e.target.closest('.card-heart')) return;
            //             // Is the item not active? Do not navigate to event!
            //             if (!e.currentTarget.parentElement.classList.contains('swiper-slide-active')) {
            //                 e.preventDefault();
            //                 // Move next or previous based on item clicked
            //                 swiper.activeIndex < e.currentTarget.parentElement.dataset.swiperSlideIndex
            //                     ? swiper.slidePrev()
            //                     : swiper.slideNext();
            //             }
            //         });
            //     });
            // });
        })();
    </script>
@endpush
