@extends('layouts.app')

@section('content')
    @include('inc.hero')
    @include('inc.stripe-menu')
    <section class="background-spin">
        <livewire:article-home />
        @foreach($sections as $section)
            <div class="py-3">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0">{{ $section['category']->name }}</h5>
                        <a class="btn btn-sm btn-dark rounded-pill py-0" href="{{ route('category', $section['category']) }}">{{ __('common.view_all') }}</a>
                    </div>
                </div>
                <div class="{{ \Illuminate\Support\Str::contains($section['layout_type'], 'carousel') ? 'container-lg p-0' : 'container' }}">
                    @include('components.articles.' . $section['layout_type'], ['articles' => $section['articles']])
                </div>
            </div>
        @endforeach
    </section>
@endsection
