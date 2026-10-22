@extends('layouts.app')

@push('title', __('auth.login') ?? 'Log in')

@section('content')
    @include('inc.hero', ['minimalistic' => true])
    @include('inc.stripe-menu')

    <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-4">{{ __('Log in') }}</h3>

                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
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

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required autocomplete="current-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                                @endif
                                <button type="submit" class="btn btn-primary">{{ __('Log in') }}</button>
                            </div>
                        </form>

                        @if (Route::has('register'))
                            <p class="mt-4 mb-0 text-center">
                                {{ __("Don't have an account?") }}
                                <a href="{{ route('register') }}">{{ __('Register') }}</a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
