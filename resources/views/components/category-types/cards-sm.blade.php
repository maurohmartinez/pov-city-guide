<div class="container">
    @foreach($articles as $article)
        <div class="card-surface-gradient card-variant-sm p-2 rounded mb-3">
            <a href="#" class="text-decoration-none">
                <div class="row position-relative">
                    <div class="col-5 col-lg-4">
                        <div class="overflow-hidden ratio-5x4 rounded hover-effect-scale">
                            <div
                                class="image-container hover-effect-target"
                                style="background-image: url('{{ $article->small_image }}');"
                            >
                            </div>
                        </div>
                    </div>
                    <div class="col-7 col-lg-8 my-auto ps-0">
                        <p class="card-category text-truncate">
                            <small>{{ implode(', ', $article->categories->pluck('name')->toArray()) }}&nbsp;</small>
                        </p>
                        <p class="card-title text-truncate">{{ $article->title }}</p>
                        <p class="card-description text-light text-truncate d-block d-lg-none">
                            <small>Something</small>
                        </p>
                        <p class="card-description text-light d-none d-lg-block pe-4 lh-1">
                            <small>{{ \Illuminate\Support\Str::words(strip_tags($article->content), 10) }}</small>
                        </p>
                    </div>
                    <div class="card-arrow card-arrow-positioned text-dark bottom-0">
                        <i class="fi-arrow-up-right"></i>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
