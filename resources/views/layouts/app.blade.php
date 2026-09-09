<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Inline light/dark theme script to prevent white flash in dark mode -->
    <script>
        (function() {
{{--            @if(\App\Services\PageService::hasAvailableColorModes())--}}
{{--            const theme = localStorage.getItem('theme') || 'light';--}}
{{--            @else--}}
{{--            const theme = '{{ \App\Services\PageService::getColorMode() }}';--}}
            const theme = 'light';
{{--            @endif--}}
            if (theme === 'auto') {
                const defaultTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                document.documentElement.setAttribute('data-bs-theme', defaultTheme);
                localStorage.setItem('theme', defaultTheme);
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme);
                localStorage.setItem('theme', theme);
            }
        })();
    </script>

    <title>@stack('title', config('app.name'))</title>
    <meta name="description" content="@stack('meta-description', '')"/>

    @stack('metas')

    {!! config('settings.custom_head_start_code') ?? '' !!}

    @stack('before_styles')

    <link rel="stylesheet" href="{{ asset('storefront/css/theme.min.css') }}" id="theme-styles">
    <link rel="stylesheet" href="{{ asset('storefront/css/colors.css') }}" id="theme-colors">

    <link rel="stylesheet" href="{{ asset('storefront/css/style.css?v='.app_version()) }}"/>
    <link rel="stylesheet" href="{{ asset('storefront/css/responsive.css?v='.app_version()) }}"/>
    <link rel="stylesheet" href="{{ asset('storefront/css/ribbons.css?v='.app_version()) }}"/>

    <!-- finder template starts -->
    <!-- Preloaded local web font (Inter) -->
    <link rel="preload" href="{{ asset('storefront/fonts/inter-variable-latin.woff2') }}" as="font" type="font/woff2" crossorigin>

    <!-- Font icons -->
    <link rel="preload" href="{{ asset('storefront/webfonts/finder-icons.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('storefront/css/finder-icons.min.css?v='.app_version()) }}">

    <link rel="stylesheet" href="{{ asset('storefront/css/swiper-bundle.min.css?v='.app_version()) }}">
    <link rel="stylesheet" href="{{ asset('storefront/css/marketplace.css?v='.app_version()) }}"/>

    <link rel="icon" type="image/png" href="{{ asset('favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="POVT" />
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}" />

    @stack('styles')
    @stack('after_styles')

    {!! config('settings.custom_head_end_code') ?? '' !!}
</head>
<body>

{!! config('settings.custom_body_start_code') ?? '' !!}

@include('inc.header')

<main>
    @yield('content')
    @include('inc.footer')
</main>

@stack('before_scripts')

<!-- Critical JS for interactive components (accordion, modals, dropdowns, etc.) -->
<script src="{{ asset('storefront/js/light-dark-mode-switcher.js?v='.app_version()) }}"></script>
<script src="{{ asset('storefront/js/swiper-bundle.min.js?v='.app_version()) }}"></script>
<script src="{{ asset('storefront/js/theme.min.js?v='.app_version()) }}"></script>
<script src="{{ asset('storefront/js/scripts.js?v='.app_version()) }}"></script>

@stack('scripts')
@stack('after_scripts')

{!! config('settings.custom_body_end_code') ?? '' !!}
<!-- scripts end -->
</body>
</html>
