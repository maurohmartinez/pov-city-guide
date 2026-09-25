@foreach($articles as $article)
    <div class="card-surface card-variant-sm p-2 rounded mb-3">
        <div class="row position-relative">
            <div class="col-5 col-lg-4">
                <a href="{{ route('article', $article) }}" class="text-decoration-none">
                    <div class="overflow-hidden ratio-5x4 rounded hover-effect-scale">
                        <div
                            class="image-container hover-effect-target"
                            style="background-image: url('{{ $article->small_image }}');"
                        >
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-7 col-lg-8 my-auto ps-0">
                @foreach($article->categories as $category)
                    @if(!$loop->first) <span class="fw-light card-category">-</span> @endif
                    <a class="card-category text-decoration-none" href="{{ route('category', $category) }}">
                        <small>{{ $category->name }}</small>
                    </a>
                @endforeach
                <a href="{{ route('article', $article) }}" class="text-decoration-none">
                    <p class="card-title text-truncate">{{ $article->title }}</p>
                    <p class="card-description text-light pe-4 lh-1">
                        <small>{{ \Illuminate\Support\Str::words(strip_tags($article->content), 7) }}</small>
                    </p>
                </a>
            </div>
            <a href="{{ route('article', $article) }}" class="text-decoration-none">
                <div class="card-arrow card-arrow-positioned text-dark bottom-0">
                    <i class="fi-arrow-up-right"></i>
                </div>
            </a>
        </div>
    </div>
@endforeach
