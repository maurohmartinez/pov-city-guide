<!-- header-start -->
<header class="header">
    <div class="container position-relative z-1">
        <div class="header-wrapper d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center ms-auto">
{{--                <div class="animate-shake">--}}
{{--                    <i class="fi-search animate-target text-custom-accent fs-3 mx-1"></i>--}}
{{--                </div>--}}
                <a href="{{ route(auth()->check() ? 'profile' : 'login') }}" class="mx-3 animate-shake text-custom-accent">
                    <i class="fi-user animate-target fs-3"></i>
                </a>
                @include('inc.menu', ['alwaysExpanded' => true, 'switchesInOffcanvas' => true])
            </div>
        </div>
    </div>
</header>
<!-- header-end -->
