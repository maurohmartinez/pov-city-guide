/**
 * =============================================================================
 * Storefront Custom Scripts
 * =============================================================================
 *
 * This file contains custom JavaScript for the storefront, layered on top of
 * the theme (theme.min.js). It is loaded after theme.min.js so Bootstrap and
 * other theme utilities are available.
 *
 * Keep vendor/library code in separate files (e.g. swiper-bundle.min.js).
 * Add all project-specific JS behaviour here.
 * =============================================================================
 */

// ---------------------------------------------------------------------------
// Bootstrap Popovers
// Initialize on page load and re-initialize after Livewire DOM updates.
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => new bootstrap.Popover(el));
});

document.addEventListener('livewire:morph', function () {
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
        bootstrap.Popover.getInstance(el)?.dispose();
        new bootstrap.Popover(el);
    });
});
