@php
    // The marketplace navbar is too crowded on mobile to also hold the theme/language
    // switches, so it hosts them in the offcanvas instead. Shops keep them in the navbar.
    $switchesOffCanvas = $switchesOffCanvas ?? false;
@endphp
@unless($categoriesMenuItems->isEmpty() && !$switchesOffCanvas)
    <!-- Navbar with offcanvas menu on screens smaller than 500px (xs) -->
    <header class="navbar @if(!($alwaysExpanded ?? false)) navbar-expand-lg @endif">
        {{-- When always expanded the toggler sits inline with other navbar icons, so strip the
             container padding and button chrome and optically center it against the font icons. --}}
        <div class="container @if($alwaysExpanded ?? false) px-0 @endif">
            <!-- Menu toggler -->
            <button type="button" class="navbar-toggler @if($alwaysExpanded ?? false) border-0 p-0 pov-toggler-inline @endif" data-bs-toggle="offcanvas" data-bs-target="#navbarOffCanvas" aria-controls="navbarOffCanvas" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon pov-navbar"></span>
            </button>
            <!-- Offcanvas menu -->
            <div class="offcanvas offcanvas-end" id="navbarOffCanvas" tabindex="-1" aria-label="{{ config('app.name') }}">
                <div class="offcanvas-header">
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body pt-0">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                Home
                            </a>
                        </li>
                        @include('inc.menu-item', ['categories' => $categoriesMenuItems, 'depth' => 0])
                    </ul>
                    @if(auth()->check())
                        <hr class="mb-3">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('favourites') }}">
                                    <i class="la la-heart-o me-1"></i>Favourites
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile') }}">
                                    <i class="la la-user me-1"></i>Edit profile
                                </a>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}" class="nav-link">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-decoration-none">
                                        <i class="la la-sign-out me-1"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    @endif
                    @if($switchesOffCanvas)
                        <div class="d-flex align-items-center flex-wrap gap-2 @unless($categoriesMenuItems->isEmpty()) border-top pt-3 mt-3 @endunless">
                            <img class="px-3" src="{{ asset('/storefront/images/pov.svg') }}" width="80px" alt="{{ config('app.name') }}" />
                            <x-component::select-language :light-color="false"/>
{{--                            <x-component::select-theme :light-color="false" :with-label="true"/>--}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>
@endunless
