@extends('layouts.app')

@push('title', __('common.terms_and_conditions'))

@section('content')
    @include('inc.hero', ['minimalistic' => true])
    @include('inc.stripe-menu')

    <section class="container my-5">
        <h3>{{ __('common.terms_and_conditions') }}</h3>
        {!! \Backpack\Settings\app\Models\Setting::get(\App\Services\SettingService::COOKIE_POLICY) !!}
    </section>
@endsection
