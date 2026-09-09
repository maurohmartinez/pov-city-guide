{{-- External checkout sends the visitor off-site, so it gets a link icon rather than a ticket. --}}
@props(['event'])
<i class="{{ $event->has_external_checkout ? 'fi-external-link' : 'fi-ticket' }} me-2"></i>
