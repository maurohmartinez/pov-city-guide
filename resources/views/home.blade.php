@extends('layouts.app')

@section('content')
    @include('inc.hero')
    @include('inc.stripe-menu')
    <section class="background-spin">
        <div class="container mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="m-0">Articles</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                    @include('components.articles.hero', ['article' => $articles->first(), 'ratio' => '5x2'])
                </div>
                <div class="col-md-5">
                    @include('components.articles.horizontal-card', ['articles' => $articles->slice(1)])
                </div>
            </div>
        </div>
        @foreach($sections as $section)
            <div class="py-3">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0">{{ $section['category']->name }}</h5>
                        <a class="btn btn-sm btn-dark rounded-pill py-0" href="#">{{ __('common.view_all') }}</a>
                    </div>
                </div>
                <div class="{{ \Illuminate\Support\Str::contains($section['layout_type'], 'carousel') ? 'container-lg p-0' : 'container' }}">
                    @include('components.articles.' . $section['layout_type'], ['articles' => $section['articles']])
                </div>
            </div>
        @endforeach
    </section>
@endsection
