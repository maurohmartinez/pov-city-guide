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
        @if(!empty($category->extras['description_top'] ?? null))
            <p class="text-dark mb-5">{{ $category->extras['description_top'] }}</p>
        @endif
        <livewire:category-articles :category="$category"/>
        <livewire:videos :category="$category"/>
        @if(!empty($category->extras['description_bottom'] ?? null))
            <p class="text-dark mt-5">{{ $category->extras['description_bottom'] }}</p>
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
