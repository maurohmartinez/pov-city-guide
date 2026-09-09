<section class="container custom-hero-container">
    <div class="d-md-flex justify-content-between align-items-center">
        <p class="custom-hero-slogan">OUR<br>STORIES.<br>YOUR<br>PLANS.</p>
        <h2 class="text-center">The new kind of city guide</h2>
        <div></div>
    </div>

    <div class="custom-hero">
        <div class="custom-hero-media">
            <video autoplay loop muted webkit-playsinline playsinline id="hero-video"></video>
        </div>
        <div class="custom-hero-mask"></div>
    </div>
</section>

@push('after_styles')
    <style>
        .custom-hero-mask:after {
            content: '{{ $page->heading ?? '' }}';
        }
    </style>
@endpush

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
