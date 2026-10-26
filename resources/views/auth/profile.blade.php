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
                        @session('error')
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endsession
                        @session('success')
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endsession
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       required autofocus autocomplete="name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       required autocomplete="username">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 text-dark mt-3">{{ __('Apply') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
