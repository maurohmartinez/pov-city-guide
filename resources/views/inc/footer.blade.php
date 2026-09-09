@php
    $socialMediaLinks = json_decode(\Backpack\Settings\app\Models\Setting::get('social_media_links'), true);
    // The bright pink footer is a deliberate homepage flourish; on interior pages it just shouts
    // for attention where we'd rather the eye stay on buying/browsing, so we mute it to the gradient.
    $isHomepage = ($page->slug ?? null) === '/';
@endphp

<footer @unless($isHomepage) class="footer-interior" @endunless>
    @if(\App\Services\PageService::isUsingMarketplace())
        @php
            $webUser = auth()->guard('web')->user();
            $alreadySubscribed = $webUser && $webUser->newsletterSubscription?->isActive();
        @endphp
        @if($webUser && !$alreadySubscribed)
            {{-- Deliberately loud pink CTA on every page — this is the one footer element we DO want to grab attention. --}}
            <div class="row p-4 bg-primary newsletter-cta mb-n1">
                <div class="col-12 col-lg-6 offset-lg-3">
                    <p class="text-center mb-3">{{ __('marketplace.newsletter_subscribe') }}</p>
                    <livewire:marketplace.account-newsletter-form />
                </div>
            </div>
        @endif
    @endif

    <div class="container">
        <div class="row">
            {{-- Only show this footer section if it will show something --}}
            @if($socialMediaLinks)
                <div class="col-lg-4 social-media d-flex justify-content-center p-4 p-lg-5 mb-3 mb-lg-0 order-lg-2">
                    <div class="d-flex justify-content-center overflow-hidden">
                        @foreach($socialMediaLinks as $socialMediaLink)
                            <a
                                href="{{ $socialMediaLink['link'] }}"
                                target="_blank"
                                class="px-3"
                                data-bs-toggle="tooltip"
                                data-bs-template='<div class="tooltip fs-xs" role="tooltip"><div class="tooltip-inner text-white opacity-75 py-1"></div></div>'
                                title="{{ ucfirst($socialMediaLink['type']) }}"
                                aria-label="Follow us on {{ ucfirst($socialMediaLink['type']) }}"
                            >
                                <i class="fi-{{ $socialMediaLink['type'] }} fs-2 text-light"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="col-lg-8 @if (!$socialMediaLinks) offset-lg-2 @endif text-center d-block d-lg-flex justify-content-between legal align-items-center px-5 my-2 my-lg-0 order-lg-1">
                <div><a class="text-decoration-none hover-effect-underline text-light" href="{{ url('terms-and-conditions') }}">@lang('common.terms_and_conditions')</a></div>
                <div><a class="text-decoration-none hover-effect-underline text-light" href="{{ url('privacy-policy') }}">@lang('common.privacy_policy')</a></div>
                <div><a class="text-decoration-none hover-effect-underline text-light" href="{{ url('cookie-policy') }}">@lang('common.cookie_policy')</a></div>
                <div><a class="text-decoration-none hover-effect-underline text-light" href="{{ url('delivery-and-refund-policy') }}">@lang('common.delivery_and_refund_policy')</a></div>
            </div>

            @if (config('custom.currency_iso') === 'RON')
                <div class="col-12 mb-4 mt-2 order-3">
                    {{-- Only show this footer if it will show something --}}
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        {{-- Only show Romanian regulatory logos if currency is RON --}}
                        @if(config('custom.currency_iso') === 'RON')
                            <div class="d-flex px-3">
                                <div class="m-2">
                                    <a href="https://anpc.ro/ce-este-sal/" target="_blank">
                                        <img src="{{ asset('storefront/imgs/regulatory/pictogramaSAL.png') }}" width="200px">
                                    </a>
                                </div>
                                <div class="m-2">
                                    <a href="https://ec.europa.eu/consumers/odr" target="_blank">
                                        <img src="{{ asset('storefront/imgs/regulatory/pictogramaSOL.png') }}" width="200px">
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

    </div>
</footer>
