@extends('layouts.app')

@push('title', __('auth.login') ?? 'Profile')

@section('content')
    @include('inc.hero', ['minimalistic' => true])
    @include('inc.stripe-menu')
    <section class="container my-5">
        <div class="d-none d-md-block">
            <div class="position-relative mt-4">
                <div class="row">
                    @foreach($articles as $article)
                        <div class="col-md-{{ $articles->count() === 4 ? 3 : 4 }} col-lg-3 mb-3">
                            @include('components.articles.hero', ['article' => $article, 'words' => 5])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
