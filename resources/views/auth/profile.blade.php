@extends('layouts.app')

@push('title', __('auth.login') ?? 'Profile')

@section('content')
    @include('inc.hero', ['minimalistic' => true])
    @include('inc.stripe-menu')
    <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('storefront/images/pov.svg') }}" alt="POV" style="max-width: 100px;" class="mb-3">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
