@extends('layouts.app')

@push('title', $article->name)
@push('metas')
    @include('partials.meta-open-graph', [
            'title' => $article->title,
            'description' => \Illuminate\Support\Str::words(strip_tags($article->content), 20),
            'image' => $article->large_image,
            'type' => 'article',
            'url' => url()->current(),
            'siteName' => config('app.name'),
        ])
@endpush
@push('meta-description', $article->title) {{--TODO!--}}

@section('content')
    @include('inc.hero', ['minimalistic' => true])
    @include('inc.stripe-menu')

    <section class="container my-5">
        <div class="card-surface rounded mb-3 overflow-hidden">
            <div class="row position-relative p-3">
                <h5 class="card-category text-truncate">
                    @foreach($article->categories as $category)
                        @if(!$loop->first) <span class="fw-light card-category">-</span> @endif
                        <a class="card-category text-decoration-none fw-bold" href="{{ route('category', $category) }}">
                            <small>{{ $category->name }}</small>
                        </a>
                    @endforeach
                </h5>
                <h4 class="card-title text-truncate">{{ $article->title }}</h4>
            </div>
            <div class="overflow-hidden ratio-5x2">
                <div
                    class="image-container"
                    style="background-image: url('{{ $article->large_image }}');"
                >
                </div>
            </div>
        </div>
        <div class="my-5">
            <p>{!! $article->content !!}</p>
        </div>
        <div class="my-5">
            @foreach($article->tags as $tag)
                <span class="tags rounded-pill me-2">{{ $tag->name }}</span>
            @endforeach
        </div>
    </section>

    @php($relatedArticles = $article->related)
    @if($relatedArticles)
        <section class="my-5 pt-3">
            <h5 class="container">{{ __('common.you_might_also_be_intered_in') }}</h5>
            <div class="container-lg p-0">
                @include('components.articles.cards-sm-carousel', ['articles' => $relatedArticles])
            </div>
        </section>
    @endif
@endsection
