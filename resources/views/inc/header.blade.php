<!-- header-start -->
<header class="header">
    <div class="container position-relative z-1">
        <div class="header-wrapper d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center ms-auto">
                {{--                <div class="animate-shake">--}}
                {{--                    <i class="fi-search animate-target text-custom-accent fs-3 mx-1"></i>--}}
                {{--                </div>--}}
                @if(auth()->check())
                    <div class="dropdown">
                        <a class="mx-3 animate-shake text-custom-accent" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fi-user animate-target fs-3"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('favourites') }}"><i class="la la-heart-o me-1"></i>Favourites</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="la la-user me-1"></i>Edit profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger" href="#">
                                        <i class="la la-sign-out me-1"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    @if(config('custom.use_login_function'))
                        <div class="dropdown">
                            <a class="mx-3 animate-shake text-custom-accent" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fi-user animate-target fs-3"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('register') }}"><i class="la la-sign-in me-1"></i>Register</a></li>
                                <li><a class="dropdown-item" href="{{ route('login') }}"><i class="la la-user me-1"></i>Login</a></li>
                            </ul>
                        </div>
                    @endif
                @endif
                @include('inc.menu', ['alwaysExpanded' => true, 'switchesOffCanvas' => true])
            </div>
        </div>
    </div>
</header>
<!-- header-end -->
