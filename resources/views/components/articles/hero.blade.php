<div class="card-surface rounded mb-3">
    <a href="{{ route('article', $article) }}" class="text-decoration-none">
        <div class="overflow-hidden ratio-{{ $ratio ?? '5x3' }} rounded hover-effect-scale">
            <div
                class="image-container hover-effect-target"
                style="background-image: url('{{ $article->medium_image }}');"
            >
            </div>
        </div>
        <div class="row position-relative p-3">
            <h5 class="card-category text-truncate">
                @foreach($article->categories as $category)
                    @if(!$loop->first)
                        <span class="fw-light">-</span>
                    @endif
                    <a class="card-category text-decoration-none fw-bold" href="{{ route('category', $category) }}"><small>{{ $category->name }}</small></a>
                @endforeach
            </h5>
            <h5 class="card-title text-truncate">{{ $article->title }}</h5>
            <p class="card-description text-light pe-4 lh-1 text-truncate-2">
                <small>{{ \Illuminate\Support\Str::words(strip_tags($article->content), $words ?? 20) }}</small>
            </p>
            <hr class="card-separator">
            <div class="w-100 d-flex justify-content-end">
                <a href="{{ route('article', $article) }}" class="text-decoration-none">
                    <div class="card-arrow text-dark">
                        <i class="fi-arrow-up-right"></i>
                    </div>
                </a>
            </div>
        </div>
    </a>
</div>
