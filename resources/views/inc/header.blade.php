{{--
    navbarColor can be:
        "home" => home look from Figma template
        "light" => light solid background + dark text
        "transparentLight" => transparent background + light text
        "transparentDark" => transparent background + dark text (e.g.: useful for pages without banne rimage - checkout)
--}}
@php
    $isNavbarLight = \App\Services\PageService::getIsNavbarLightColor($__env->yieldContent('navbarColor'));
@endphp

<!-- header-start -->
<header data-pov-section="header" class="header w-100 py-3 ps-3 @if($__env->yieldContent('navbarColor') !== 'light') position-absolute top-0 start-0 @else bg-primary @endif">
    <div class="container position-relative z-1">
        <div class="header-wrapper d-flex align-items-center justify-content-between">
            @php
                $userHref = null; // guests → open auth modal
                $seeAllUrl = \App\Services\PageService::getSeeAllUrl();
                $iconColor = $isNavbarLight ? 'text-white' : 'text-default';
                // the homepage hero already carries the logo, so the navbar doesn't repeat it
                $showLogo = ($page->slug ?? null) !== '/';
            @endphp

            @if($showLogo)
                <!-- header-logo -->
                <div class="header-logo">
                    <a href="{{ url('/') }}" class="navbar-brand d-block py-1 py-md-2 py-xl-1 me-2 {{ $iconColor }}">
                        <div class="d-block" style="height: 1.75rem; width: auto">
                            <svg viewBox="0 0 703 346" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" aria-label="{{ config('app.name') }}">
                                <path d="M170.505 5.43213H9.77329L4.11108 11.0885V325.333L9.77329 330.99H86.3538L92.016 325.333V266.28H170.494L193.164 243.634V28.0783L170.494 5.43213H170.505ZM105.249 189.237L103.361 191.123H93.9139L92.0265 189.237V72.2978L93.9139 70.4124H103.361L105.249 72.2978V189.237Z" fill="currentColor"/>
                                <path d="M373.093 5.43213H229.369L206.699 28.0783V308.354L229.369 331H373.093L395.763 308.354V28.0783L373.093 5.43213ZM307.847 264.395L305.96 266.28H296.512L294.625 264.395V72.2978L296.512 70.4124H305.96L307.847 72.2978V264.395Z" fill="currentColor"/>
                                <path d="M597.756 11.6406L574.159 310.541L553.867 331H453.792L433.511 310.541L409.903 11.6406L415.544 5.43213H474.543L497.213 28.0783V264.395L499.101 266.28H508.548L510.435 264.395V28.0783L533.105 5.43213H592.126L597.756 11.6406Z" fill="currentColor"/>
                                <path d="M653.203 157.229L662.679 166.715L672.156 157.229L696.901 157.229L700.681 161.018L702.431 162.764L702.431 177.328L701.062 178.698L667.096 178.698L667.096 181.928L701.062 181.928L701.062 181.94L702.431 183.304L702.431 202.015L701.05 203.397L624.315 203.397L622.928 202.015L622.928 183.304L622.934 183.298L624.303 181.928L657.786 181.928L658.251 181.469L658.251 179.163L657.786 178.698L624.303 178.698L622.934 177.328L622.934 175.665L622.928 175.665L622.928 162.764L628.458 157.229L653.203 157.229Z" fill="currentColor"/>
                                <path d="M638.738 131.188L658.263 131.188L658.263 117.096L658.269 117.09L659.632 115.726L661.298 115.726L674.555 115.726L675.924 117.09L675.93 117.096L675.93 131.188L686.627 131.188L686.627 107.86L687.996 106.489L689.656 106.489L701.05 106.489L702.431 107.872L702.431 111.995L702.46 112.025L702.46 147.122L696.93 152.658L628.488 152.658L622.958 147.122L622.958 134.448L622.928 134.448L622.928 107.872L624.309 106.489L637.369 106.489L638.732 107.854L638.738 107.86L638.738 131.188Z" fill="currentColor"/>
                                <path d="M649.399 228.077L649.399 209.349L648.036 207.985L628.458 207.985L622.928 213.52L622.928 248.617L628.458 254.153L696.901 254.153L702.431 248.617L702.431 213.52L696.901 207.985L677.216 207.985L675.847 209.349L675.847 228.089L677.21 229.454L686.162 229.454L686.621 229.913L686.621 232.219L686.162 232.678L639.25 232.678L638.792 232.219L638.792 229.913L639.25 229.454L648.03 229.454L649.393 228.089L649.399 228.077Z" fill="currentColor"/>
                                <path d="M622.928 286.133L622.928 302.669L622.928 304.897L622.928 310.767L622.928 312.995L622.928 329.531L624.315 330.913L637.411 330.913L638.798 329.531L638.798 318.525L696.901 318.525L702.431 312.995L702.431 310.767L702.431 304.897L702.431 302.669L696.901 297.133L638.798 297.133L638.798 286.133L637.411 284.745L626.553 284.745L624.315 284.745L622.928 286.133Z" fill="currentColor"/>
                                <path d="M622.928 57.1278L622.928 73.6692L622.928 75.8918L622.928 81.7671L622.928 83.9897L622.928 100.531L624.315 101.914L637.411 101.914L638.798 100.531L638.798 89.5254L696.901 89.5254L702.431 83.9897L702.431 81.7671L702.431 75.8918L702.431 73.6692L696.901 68.1335L638.798 68.1335L638.798 57.1278L637.411 55.7453L626.553 55.7453L624.315 55.7453L622.928 57.1278Z" fill="currentColor"/>
                                <path d="M622.958 264.246L622.94 268.036L622.928 268.036L622.934 269.442L622.928 270.848L622.94 270.848L622.958 274.638L628.488 280.174L696.93 280.174L702.46 274.638L702.46 269.46L702.46 269.43L702.46 264.246L696.93 258.716L628.488 258.716L622.958 264.246Z" fill="currentColor"/>
                                <path d="M667.144 4.99925L658.31 29.6982L639.256 29.6982L638.792 29.2394L638.792 26.9333L639.256 26.4685L648.036 26.4685L649.399 25.11L649.399 6.36975L648.03 4.99925L628.458 4.99925L622.928 10.5349L622.928 45.6318L628.458 51.1675L658.31 51.1675L667.144 26.4685L686.162 26.4685L686.627 26.9333L686.627 29.2394L686.162 29.6982L677.204 29.6982L675.852 31.0568L675.852 49.797L677.222 51.1675L696.901 51.1675L702.431 45.6318L702.431 10.5349L696.901 4.99924L667.144 4.99925L667.144 5.00521L667.144 4.99925Z" fill="currentColor"/>
                            </svg>
                        </div>
                    </a>
                </div>
            @endif
            <div class="d-flex align-items-center ms-auto">
                @if($seeAllUrl)
                    <a href="{{ url($seeAllUrl) }}" class="animate-scale">
                @endif
                    <i class="fi-search animate-target {{ $iconColor }} fs-3 mx-1"></i>
                @if($seeAllUrl)
                    </a>
                @endif
                @if($userHref)
                    <a href="{{ $userHref }}" class="mx-3 animate-shake {{ $iconColor }}">
                        <i class="fi-user animate-target fs-3"></i>
                    </a>
                @else
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#auth-modal" class="mx-3 animate-shake {{ $iconColor }}">
                        <i class="fi-user animate-target fs-3"></i>
                    </a>
                @endif
                @include('inc.menu', ['alwaysExpanded' => true, 'isNavbarLight' => $isNavbarLight, 'switchesInOffcanvas' => true])
            </div>
        </div>
    </div>
    @if($__env->yieldContent('navbarColor') === 'transparentLight')
        <div class="header-top-overlay"></div>
    @endif
</header>
<!-- header-end -->
