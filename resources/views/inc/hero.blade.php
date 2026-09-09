<section>
    <p class="custom-hero-slogan">OUR<br>STORIES.<br>YOUR<br>PLANS.</p>
    <div class="container custom-hero">
        <div class="custom-hero-media">
            <video autoplay loop muted webkit-playsinline playsinline id="hero-video"></video>
        </div>
        <div class="custom-hero-mask"></div>
    </div>
    <div class="custom-hero-arrow fs-1 text-light">
        <a href="#more" class="text-light text-decoration-none">
            <i class="fi-arrow-down text-custom-accent"></i>
        </a>
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
