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
<div class="bg-dark py-3 custom-hero-bottom">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="text-white text-decoration-none">News</a>
        <a class="text-white text-decoration-none">Things to do</a>
        <a class="text-white text-decoration-none">Festivals</a>
        <a class="text-white text-decoration-none">Food & Drink</a>
        <a class="text-white text-decoration-none">Cinema</a>
        <a class="text-white text-decoration-none">Theatre</a>
    </div>
</div>

@push('after_styles')
    <style>
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
