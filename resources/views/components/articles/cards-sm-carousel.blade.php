{{--MOBILE--}}
<div class="position-relative mt-4 @if($articles->count() <= 4) d-block d-md-none @endif">
    <div
        class="swiper px-4 cards-sm-swiper"
        data-swiper='{
            "spaceBetween": 25,
            "pagination": {
              "el": ".swiper-pagination",
              "clickable": false
            },
            "slidesPerView": 1.05,
            "breakpoints": {
              "992": {
                "slidesPerView": 3
              },
              "1200": {
                "slidesPerView": 4
              },
              "1400": {
                "slidesPerView": 5
              }
            }
        }'
    >
        <div class="swiper-wrapper">
            @foreach($articles as $article)
                <div class="swiper-slide h-auto no-opacity">
                    @include('components.articles.hero', ['article' => $article, 'words' => 5])
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination position-static mt-3"></div>
    </div>
</div>

{{--DESKTOP - When LESS than or EQUAL to 4 events we show simple col-xx cards--}}
@if($articles->count() <= 4)
    <div class="d-none d-md-block">
        <div class="position-relative mt-4">
            <div class="row">
                @foreach($articles as $article)
                    <div class="col-md-{{ $articles->count() === 4 ? 3 : 4 }} col-lg-3">
                        @include('components.articles.hero', ['article' => $article, 'words' => 5])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
