@if(\App\Services\LocalizationService::supportsMultipleLocales())
    <div class="dropdown" data-pov-component="select-language">
        <button class="btn {{ $lightColor ? 'btn-outline-light' : 'btn-outline-dark pov-btn-outline-dark' }} dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <x-dynamic-component component="flag-language-{{ \Illuminate\Support\Facades\App::getLocale() }}" style="width: 1.5rem" class="me-1"/> {{ \App\Services\LocalizationService::getAppLocaleNativeName() }}
        </button>
        <ul class="dropdown-menu">
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <li>
                    <a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                        <span class="nav-link-icon" style="width: fit-content">
                            <x-dynamic-component component="flag-language-{{ $localeCode }}" style="width: 1.5rem" class="me-1"/> {{ ucfirst($properties['native']) }}
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
