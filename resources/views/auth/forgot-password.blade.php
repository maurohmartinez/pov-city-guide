@extends('layouts.auth')

@push('title', __('Forgot Password'))

@section('content')
    <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('storefront/images/pov.svg') }}" alt="POV" style="max-width: 100px;" class="mb-3">
                        </a>

                        <h3 class="mb-3">{{ __('Forgot your password?') }}</h3>

                        <p class="text-muted">
                            {{ __('No problem. Just let us know your email address and we will email you a password reset link.') }}
                        </p>

                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       required autofocus autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <a class="text-custom-accent" href="{{ route('login') }}">{{ __('Back to log in') }}</a>
                                <button type="submit" class="btn btn-primary text-dark">{{ __('Email password reset link') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
