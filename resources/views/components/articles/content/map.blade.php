@php
    // $value is the object stored by the dynamic_content field: {lat, lng, formatted_address}
    $map = is_array($value ?? null) ? $value : [];
    $lat = $map['lat'] ?? null;
    $lng = $map['lng'] ?? null;
    $address = $map['formatted_address'] ?? '';
@endphp

@if($lat !== null && $lng !== null)
    <div class="ratio ratio-16x9 rounded overflow-hidden">
        <iframe
            src="https://maps.google.com/maps?q={{ $lat }},{{ $lng }}&z=16&output=embed"
            title="{{ $address }}"
            frameborder="0"
            allowfullscreen
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
        ></iframe>
    </div>
    @if($address !== '')
        <p class="text-muted small mt-2 mb-0">
            <i class="la la-map-marker"></i> {{ $address }}
        </p>
    @endif
@endif
