{{--
    withLabel => render as a labelled dropdown (icon + current theme name), matching the
    language switcher. Used where there's room to spare, e.g. the marketplace offcanvas.
    Default is the compact icon-only toggle used in the navbar.
--}}
@props(['lightColor' => false, 'withLabel' => false])
<div class="dropdown @unless($withLabel) me-1 me-sm-2 @endunless" data-pov-component="select-theme">
    @if($withLabel)
        <button type="button" class="light-dark-mode-switcher btn {{ $lightColor ? 'btn-outline-light' : 'btn-outline-dark pov-btn-outline-dark' }} dropdown-toggle d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-display="dynamic" aria-expanded="false" aria-label="Toggle theme (light)">
            <span class="theme-icon-active d-flex me-1">
                <i class="fi-sun"></i>
            </span>
            <span class="theme-label-active">{{ __('common.theme_light') }}</span>
        </button>
    @else
        <button type="button" class="light-dark-mode-switcher btn btn-icon btn-outline-secondary fs-lg border-0 animate-scale" data-bs-toggle="dropdown" data-bs-display="dynamic" aria-expanded="false" aria-label="Toggle theme (light)">
            <span class="theme-icon-active d-flex animate-target @if($lightColor) text-white @endif">
                <i class="fi-sun"></i>
            </span>
        </button>
    @endif
    {{-- the labelled variant sits at the right edge of the offcanvas, so its menu opens leftwards --}}
    <ul class="dropdown-menu @if($withLabel) dropdown-menu-end @endif">
        <li>
            <button type="button" class="dropdown-item active" data-bs-theme-value="light" aria-pressed="true">
                <span class="theme-icon d-flex fs-base me-2">
                    <i class="fi-sun"></i>
                </span>
                <span class="theme-label">{{ __('common.theme_light') }}</span>
                <i class="item-active-indicator fi-check ms-auto"></i>
            </button>
        </li>
        <li>
            <button type="button" class="dropdown-item" data-bs-theme-value="dark" aria-pressed="false">
                <span class="theme-icon d-flex fs-base me-2">
                    <i class="fi-moon"></i>
                </span>
                <span class="theme-label">{{ __('common.theme_dark') }}</span>
                <i class="item-active-indicator fi-check ms-auto"></i>
            </button>
        </li>
        <li>
            <button type="button" class="dropdown-item" data-bs-theme-value="auto" aria-pressed="false">
                <span class="theme-icon d-flex fs-base me-2">
                    <i class="fi-auto"></i>
                </span>
                <span class="theme-label">{{ __('common.theme_auto') }}</span>
                <i class="item-active-indicator fi-check ms-auto"></i>
            </button>
        </li>
    </ul>
</div>
