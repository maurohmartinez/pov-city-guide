@php
    $menuItems = \App\Models\MenuItem::getTree();
    // The marketplace navbar is too crowded on mobile to also hold the theme/language
    // switches, so it hosts them in the offcanvas instead. Shops keep them in the navbar.
    $switchesInOffcanvas = $switchesInOffcanvas ?? false;
@endphp
@unless($menuItems->isEmpty() && !$switchesInOffcanvas)
    <!-- Navbar with offcanvas menu on screens smaller than 500px (xs) -->
    <header class="navbar @if(!($alwaysExpanded ?? false)) navbar-expand-lg @endif @if($__env->yieldContent('navbarColor') === 'transparentLight') navbar-dark @endif p-0">
        {{-- When always expanded the toggler sits inline with other navbar icons, so strip the
             container padding and button chrome and optically center it against the font icons. --}}
        <div class="container @if($alwaysExpanded ?? false) px-0 @endif">
            <!-- Menu toggler -->
            <button type="button" class="navbar-toggler @if($alwaysExpanded ?? false) border-0 p-0 pov-toggler-inline @endif" data-bs-toggle="offcanvas" data-bs-target="#navbarOffCanvas" aria-controls="navbarOffCanvas" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon {{ ($isNavbarLight ?? false) ? 'pov-navbar-light' : 'pov-navbar-auto' }}"></span>
            </button>
            <!-- Offcanvas menu -->
            <div class="offcanvas offcanvas-end" id="navbarOffCanvas" tabindex="-1" aria-label="{{ config('app.name') }}">
                <div class="offcanvas-header">
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body pt-0">
                    <ul class="navbar-nav">
                        @include('inc.menu-item', ['items' => $menuItems, 'depth' => 0])
                    </ul>
                    @if($switchesInOffcanvas)
                        <div class="d-flex align-items-center flex-wrap gap-2 @unless($menuItems->isEmpty()) border-top pt-3 mt-3 @endunless">
                            <x-component::select-language :light-color="false"/>
                            @if(\App\Services\PageService::hasAvailableColorModes())
                                <x-component::select-theme :light-color="false" :with-label="true"/>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>
@endunless
