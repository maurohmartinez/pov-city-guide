@php
    // $value is the list stored by the dynamic_content "places" block:
    // [{name, note, formatted_address, lat, lng}, ...]. Keep only entries with valid coords.
    $places = collect(is_array($value ?? null) ? $value : [])
        ->map(function ($p) {
            if (! is_array($p)) {
                return null;
            }
            $lat = $p['lat'] ?? null;
            $lng = $p['lng'] ?? null;
            if (! is_numeric($lat) || ! is_numeric($lng)) {
                return null;
            }
            return [
                'name' => (string) ($p['name'] ?? ''),
                'note' => (string) ($p['note'] ?? ''),
                'formatted_address' => (string) ($p['formatted_address'] ?? ''),
                'lat' => (float) $lat,
                'lng' => (float) $lng,
            ];
        })
        ->filter()
        ->values();

    $mapId = 'places-map-'.uniqid();
@endphp

@if($places->isNotEmpty())
    <div class="card-surface rounded p-3">
        <div class="places-block row g-3">
            <div class="col-md-7">
                <div id="{{ $mapId }}" class="rounded overflow-hidden border" style="width: 100%; height: 420px;"></div>
            </div>
            <div class="col-md-5">
                <ul class="list-group places-list">
                    @foreach($places as $i => $place)
                        <li class="list-group-item list-group-item-action" role="button" data-places-index="{{ $i }}">
                            @if($place['name'] !== '')
                                <strong class="d-block">{{ $place['name'] }}</strong>
                            @endif
                            @if($place['formatted_address'] !== '')
                                <small class="d-block"><i class="las la-map-marker"></i> {{ $place['formatted_address'] }}</small>
                            @endif
                            @if($place['note'] !== '')
                                <small class="d-block mt-1">{{ $place['note'] }}</small>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    @push('after_styles')
        <style>
            .places-list .list-group-item-action {
                color: white;
            }
            .places-list .list-group-item-action:hover {
                color: black;
            }
            /* The storefront renders text white; force dark text inside Leaflet popups so the
               place name/address are visible on the popup's white background. */
            .leaflet-popup-content-wrapper {
                padding: 10px !important;
            }
            .leaflet-popup-content-wrapper,
            .leaflet-popup-content,
            .leaflet-popup-content * {
                color: #212529 !important;
            }
            .leaflet-popup-content small {
                color: #495057;
            }
        </style>
    @endpush

    {{-- Load Leaflet + OpenStreetMap once per page (no API key, keyless like the single map block). --}}
    @once
        @push('after_styles')
            @basset('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css')
        @endpush
        @push('after_scripts')
            @basset('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js')
        @endpush
    @endonce

    @push('after_scripts')
        <script>
            (function () {
                var places = @json($places);

                function escapeHtml(s) {
                    return String(s).replace(/[&<>"']/g, function (c) {
                        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
                    });
                }

                // Inline SVG pin, so markers need no external image (Leaflet's default icon
                // references PNGs that aren't served — this keeps everything self-hosted).
                function pinIcon() {
                    return L.divIcon({
                        className: 'places-pin',
                        html: '<svg width="28" height="40" viewBox="0 0 28 40" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">'
                            + '<path d="M14 0C6.27 0 0 6.27 0 14c0 10.5 14 26 14 26s14-15.5 14-26C28 6.27 21.73 0 14 0z" fill="#e4572e"/>'
                            + '<circle cx="14" cy="14" r="5" fill="#ffffff"/></svg>',
                        iconSize: [28, 40],
                        iconAnchor: [14, 40],
                        popupAnchor: [0, -36],
                    });
                }

                function init() {
                    // Leaflet is loaded async via @ basset; wait for it.
                    if (typeof L === 'undefined') { setTimeout(init, 50); return; }

                    var el = document.getElementById(@json($mapId));
                    if (! el || el._leafletInit) { return; }
                    el._leafletInit = true;

                    var map = L.map(el, { scrollWheelZoom: false });
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    var markers = [];
                    var coords = [];
                    places.forEach(function (p) {
                        var marker = L.marker([p.lat, p.lng], { icon: pinIcon() }).addTo(map);
                        var html = '';
                        if (p.name) { html += '<strong>' + escapeHtml(p.name) + '</strong>'; }
                        if (p.formatted_address) { html += (html ? '<br>' : '') + '<small>' + escapeHtml(p.formatted_address) + '</small>'; }
                        if (p.note) { html += (html ? '<br>' : '') + escapeHtml(p.note); }
                        if (html) { marker.bindPopup(html); }
                        markers.push(marker);
                        coords.push([p.lat, p.lng]);
                    });

                    if (coords.length === 1) {
                        map.setView(coords[0], 14);
                    } else {
                        map.fitBounds(coords, { padding: [30, 30] });
                    }

                    // Clicking a list item focuses that marker (scoped to this block only).
                    var block = el.closest('.places-block');
                    if (block) {
                        block.querySelectorAll('[data-places-index]').forEach(function (li) {
                            li.addEventListener('click', function () {
                                var idx = parseInt(li.getAttribute('data-places-index'), 10);
                                var m = markers[idx];
                                if (! m) { return; }
                                map.setView(m.getLatLng(), 15);
                                m.openPopup();
                            });
                        });
                    }

                    // Container has a fixed height but may have been laid out after init.
                    setTimeout(function () { map.invalidateSize(); }, 200);
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', init);
                } else {
                    init();
                }
            })();
        </script>
    @endpush
@endif
