@extends('layouts.app')

@section('content')
    @include('inc.hero', ['minimalistic' => true])
    @include('inc.stripe-menu')

    <section class="container my-5">
        <div class="card-surface rounded mb-3 overflow-hidden">
            <div class="row position-relative p-3">
                <h5 class="card-category text-truncate">
                    <small>{{ implode(', ', $article->categories->pluck('name')->toArray()) }}&nbsp;</small>
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
        <div class="my-4">
            <p>{!! $article->content !!}</p>
        </div>
        <div>
            @foreach($article->tags as $tag)
                <a href="#" class="btn btn-secondary tags rounded-pill me-2">{{ $tag->name }}</a>
            @endforeach
        </div>
    </section>

    @php($relatedArticles = $article->related)
    @if($relatedArticles)
        <section class="my-5">
            <h5 class="container">{{ __('common.you_might_also_be_intered_in') }}</h5>
            <div class="container-lg p-0">
                @include('components.articles.cards-sm-carousel', ['articles' => $relatedArticles])
            </div>
        </section>
    @endif
@endsection
