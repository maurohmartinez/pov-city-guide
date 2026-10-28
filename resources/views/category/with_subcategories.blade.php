@extends('layouts.app')

@push('title', $category->name)
@push('metas')
    @include('partials.meta-open-graph', [
            'title' => $category->name,
            'description' => 'The new kind of city guide :: ' . $category->name,
            'type' => 'category',
            'url' => url()->current(),
            'siteName' => config('app.name'),
        ])
@endpush
@push('meta-description', 'The new kind of city guide :: ' . $category->name)

@section('content')
    @include('inc.hero', ['minimalistic' => true])
    @include('inc.stripe-menu')

    <section dusk="top" class="container my-5">
        <h3>{{ $category->name }}</h3>
        @if($tags->isEmpty())
            <livewire:category-tag-articles :category="$category"/>
        @else
            @foreach($tags as $tag)
                <div>
                    <h5>{{ $tag->name }}</h5>
                    <livewire:category-tag-articles :category="$category" :tag="$tag" />
                </div>
            @endforeach
        @endif
    </section>

    @if($relatedArticles->isNotEmpty())
        <section class="my-5" dusk="related">
            <h5 class="container">{{ __('common.you_might_also_be_intered_in') }}</h5>
            <div class="container-lg p-0">
                @include('components.articles.cards-sm-carousel', ['articles' => $relatedArticles])
            </div>
        </section>
    @endif
@endsection
