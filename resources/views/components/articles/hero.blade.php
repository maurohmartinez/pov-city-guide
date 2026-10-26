<div class="card-surface rounded">
    <a href="{{ route('article', $article) }}" class="text-decoration-none">
        <div class="overflow-hidden ratio-{{ $ratio ?? '5x3' }} rounded hover-effect-scale">
            <div
                dusk="image"
                class="image-container hover-effect-target"
                style="background-image: url('{{ $article->medium_image }}');"
            >
            </div>
        </div>
        <div class="row position-relative p-3">
            <h5 class="card-category text-truncate">
                @foreach($article->categoriesToDisplay as $category)
                    @if(!$loop->first)
                        <span class="fw-light">-</span>
                    @endif
                    <a class="card-category text-decoration-none fw-bold" href="{{ route('category', $category) }}"><small>{{ $category->name }}</small></a>
                @endforeach
            </h5>
            <h5 class="card-title text-truncate">{{ $article->title }}</h5>
            <p class="card-description text-light pe-4 lh-1 text-truncate-2">
                <small>{{ \Illuminate\Support\Str::words($article->description, $words ?? 20) }}</small>
            </p>
            <hr class="card-separator">
            <div class="w-100 d-flex justify-content-{{ ($ratio ?? null) === '5x2' ? 'between' : 'end'}}">
                @if($article->author && ($ratio ?? null) === '5x2')
                    <div class="d-flex align-items-center">
                        <img class="img-fluid rounded-circle" style="width: 35px; height: 35px;" src="{{ $article->author->small_image }}" alt="Author">
                        <div class="text-light lh-1 ms-2">
                            <small class="text-truncate fw-bold">{{ $article->author->name }}</small><br>
                            <small>{{ $article->created_at->isoFormat('L') }}</small>
                        </div>
                    </div>
                @endif
                <a href="{{ route('article', $article) }}" class="text-decoration-none">
                    <div class="card-arrow text-dark">
                        <i class="fi-arrow-up-right"></i>
                    </div>
                </a>
            </div>
        </div>
    </a>
</div>
