<section class="container custom-hero-container {{ ($minimalistic ?? false) ? 'minimalistic' : '' }}">
    <div class="w-100 d-flex justify-content-between align-items-center">
        @if($minimalistic ?? false)
            <a href="{{ route('home') }}" class="text-decoration-none">
        @endif
                <p class="custom-hero-slogan">OUR<br>STORIES.<br>YOUR<br>PLANS.</p>
        @if($minimalistic ?? false)
            </a>
        @endif
        @unless($minimalistic ?? false)
            <h2 class="text-center d-none d-md-block">The new kind of city guide</h2>
        @endif
        @include('inc.header')
    </div>

    @unless($minimalistic ?? false)
        <h1 class="text-center d-block d-md-none">The new kind of city guide</h1>
    @endif

    @unless($minimalistic ?? false)
        <div class="custom-hero">
            <div class="custom-hero-media">
                <video autoplay loop muted webkit-playsinline playsinline id="hero-video"></video>
            </div>
            <div class="custom-hero-mask"></div>
        </div>
    @endunless
</section>

@unless($minimalistic ?? false)
    @push('after_scripts')
        <script>
            (function () {
                const videoUrl = '{{ asset('storefront/images/hero.mp4') }}';
                const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent) && !window.chrome;

                if (isSafari) {
                    document.getElementById('hero-video').poster = videoUrl;
                    return;
                }

                const s = document.createElement('source');
                s.src = videoUrl;
                s.type = 'video/mp4';
                document.getElementById('hero-video').appendChild(s);
            })();
        </script>
    @endpush
@endunless
