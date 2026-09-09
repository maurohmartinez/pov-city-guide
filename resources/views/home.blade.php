@extends('layouts.app')

@push('title', $page?->title ?? null)
@section('navbarColor', 'home')

@section('content')
    <div class="bg-primary">
        @include('inc.hero')

{{--        @includeWhen(!$recommendedEvents->isEmpty(), 'marketplace.sections.recommended-for-you')--}}

{{--        @includeWhen(!$spotlightEvent->isEmpty(), 'marketplace.sections.spotlight')--}}

{{--        @includeWhen(!$upcomingEvents->isEmpty(), 'marketplace.sections.upcoming-events')--}}

{{--        @includeWhen(!$recentlyAddedEvents->isEmpty(), 'marketplace.sections.recently-added')--}}

        @foreach(($categorySections ?? []) as $categorySection)
            @includeWhen(!$categorySection['events']->isEmpty(), 'marketplace.partials.category-types.' . $categorySection['layoutType'], [
                'events' => $categorySection['events'],
                'category' => $categorySection['category'],
            ])
        @endforeach
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
