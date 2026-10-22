<?php

use App\Services\ImageProcessingService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {

    use WithFileUploads;

    private const string IMAGES_DISK = 'articles';

    private const string TEXT = 'text';

    private const string IMAGES = 'images';

    private const string VIDEO = 'video';

    private const string MAP = 'map';

    private const array TYPES_MAPPED = [
        self::TEXT => 'Text',
        self::IMAGES => 'Images',
        self::VIDEO => 'Video',
        self::MAP => 'Map',
    ];

    private const string SIZE_4_4 = '12';

    private const string SIZE_3_4 = '9';

    private const string SIZE_2_4 = '6';

    private const string SIZE_1_4 = '3';

    private const array SIZES_MAPPED = [
        self::SIZE_4_4 => '4/4',
        self::SIZE_3_4 => '3/4',
        self::SIZE_2_4 => '2/4',
        self::SIZE_1_4 => '1/4',
    ];

    public array $content = [];

    public string $fieldName = 'content';

    // Transient per-row uploads, keyed by row index. Moved to disk in updatedPhotos().
    public array $photos = [];

    public function mount(mixed $value = null, string $fieldName = 'content'): void
    {
        $this->fieldName = $fieldName;

        $decoded = is_array($value) ? $value : json_decode((string)$value, true);
        $this->content = is_array($decoded) ? $decoded : [];

        // Give every row a stable key so Livewire can track rows across add/remove.
        // Keys are stripped from the submitted JSON (see the hidden input), so they
        // never reach the database.
        foreach ($this->content as $i => $row) {
            if (empty($row['key'])) {
                $this->content[$i]['key'] = (string)Str::uuid();
            }
        }
    }

    public function addRow(): void
    {
        $this->content[] = [
            'key' => (string)Str::uuid(),
            'type' => 'text',
            'size' => '12',
            'value' => null,
        ];
    }

    public function removeRow(int $index): void
    {
        if (!isset($this->content[$index])) {
            return;
        }

        // Clean up any images this row uploaded so they don't linger on disk.
        $this->deleteRowImages($this->content[$index]['value'] ?? null);

        unset($this->content[$index]);
        $this->content = array_values($this->content);
    }

    // Swap a row with its neighbour. $direction is -1 (up/before) or +1 (down/after).
    public function moveRow(int $index, int $direction): void
    {
        $target = $index + $direction;

        if (!isset($this->content[$index], $this->content[$target])) {
            return;
        }

        [$this->content[$index], $this->content[$target]] = [$this->content[$target], $this->content[$index]];

        $this->content = array_values($this->content);
    }

    // Changing a row's type clears its value (the admin confirms this client-side), so the
    // new type never tries to parse data left over from the previous one.
    public function changeType(string $key, string $type): void
    {
        if (!array_key_exists($type, self::TYPES_MAPPED)) {
            return;
        }

        $index = array_search($key, array_column($this->content, 'key'), true);

        if ($index === false) {
            return;
        }

        $this->deleteRowImages($this->content[$index]['value'] ?? null);

        $this->content[$index]['type'] = $type;
        $this->content[$index]['value'] = null;
    }

    // Set a row's size by its stable key (index-based binding breaks when rows are reordered).
    public function changeSize(string $key, string $size): void
    {
        if (!array_key_exists($size, self::SIZES_MAPPED)) {
            return;
        }

        $index = array_search($key, array_column($this->content, 'key'), true);

        if ($index === false) {
            return;
        }

        $this->content[$index]['size'] = $size;
    }

    // Delete image files referenced by a row value (a plain list of paths). Video values are
    // associative arrays, so array_is_list keeps them from being treated as files to delete.
    private function deleteRowImages(mixed $value): void
    {
        if (is_array($value) && array_is_list($value)) {
            foreach ($value as $path) {
                if (is_string($path) && $path !== '') {
                    Storage::disk(self::IMAGES_DISK)->delete($path);
                }
            }
        }
    }

    // Fires when a row's file input changes. Moves the freshly uploaded files to the
    // images disk and appends their paths to content[index]['value'].
    public function updatedPhotos(mixed $value, ?string $key = null): void
    {
        $index = (int)$key;

        $this->validate([
            "photos.$index.*" => ['image', 'mimes:jpeg,png,webp,gif', 'max:5120'], // 5 MB per file
        ], attributes: [
            "photos.$index.*" => 'image',
        ]);

        $files = is_array($value) ? $value : [$value];

        $existing = $this->content[$index]['value'] ?? [];
        $existing = is_array($existing) ? $existing : [];

        foreach ($files as $file) {
            if ($file) {
                // Compress to the "large" size and store that instead of the raw upload.
                $existing[] = ImageProcessingService::storeCompressedLarge(
                    $file->getRealPath(),
                    self::IMAGES_DISK,
                );
            }
        }

        $this->content[$index]['value'] = array_values($existing);
        unset($this->photos[$index]);
    }

    public function removePhoto(int $index, int $photoIndex): void
    {
        $files = $this->content[$index]['value'] ?? [];

        if (!is_array($files) || !isset($files[$photoIndex])) {
            return;
        }

        Storage::disk(self::IMAGES_DISK)->delete($files[$photoIndex]);

        unset($files[$photoIndex]);
        $this->content[$index]['value'] = array_values($files);
    }

    public function ckeditorConfig(): array
    {
        return [
            'language' => app()->getLocale(),
            'toolbar' => [
                'undo', 'redo', '|', 'heading', '|',
                'bold', 'italic', '|',
                'link', 'blockQuote', 'bulletedList', 'numberedList', 'insertTable',
            ],
        ];
    }
};
?>

<div x-data="{
    content: $wire.entangle('content'),
    confirmMsg: @js(__('backoffice.confirm_change_block_type')),
    // Confirm before switching a block's type when it holds data. Reads the reactive `content`
    // (not $wire.content) so values written by deferred $wire.set(..., false) — map/video/CKEditor —
    // are seen, otherwise the prompt would only fire for values loaded from the server.
    changeType(key, newType, event) {
        const row = this.content.find(r => r.key === key);
        const current = row ? row.type : newType;
        const hasValue = row && row.value != null && row.value !== ''
            && ! (Array.isArray(row.value) && row.value.length === 0);
        if (hasValue && ! confirm(this.confirmMsg)) {
            event.target.value = current;
            return;
        }
        $wire.changeType(key, newType);
    },
}">
    {{-- Mirrors $content into the Backpack form as JSON so the plain POST submit picks it up.
         The per-row `key` is stripped here so it never gets persisted. --}}
    <input type="hidden" name="{{ $fieldName }}" :value="JSON.stringify(content.map(({ key, ...row }) => row))">

    <div class="row">
        @foreach($content as $index => $el)
            <div class="col-md-{{ $el['size'] }} px-2 my-5" wire:key="row-{{ $el['key'] }}">
                <div class="d-flex align-items-start gap-2">
                    <div class="row flex-grow-1">
                        <div class="col-md-6">
                            <select
                                class="form-control type"
                                x-on:change="changeType(@js($el['key']), $event.target.value, $event)"
                            >
                                @foreach(self::TYPES_MAPPED as $type => $name)
                                    <option value="{{ $type }}" @selected($type === $el['type'])>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select
                                class="form-control size"
                                x-on:change="$wire.changeSize(@js($el['key']), $event.target.value)"
                            >
                                @foreach(self::SIZES_MAPPED as $size => $name)
                                    {{-- cast both sides: numeric-string array keys become ints in PHP --}}
                                    <option value="{{ $size }}" @selected((string) $size === (string) $el['size'])>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        title="Move up"
                        wire:click="moveRow({{ $index }}, -1)"
                        @disabled($index === 0)
                    ><i class="la la-arrow-up"></i></button>
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        title="Move down"
                        wire:click="moveRow({{ $index }}, 1)"
                        @disabled($index === count($content) - 1)
                    ><i class="la la-arrow-down"></i></button>
                    <button
                        type="button"
                        class="btn btn-outline-danger"
                        title="{{ __('backoffice.delete_block') }}"
                        wire:click="removeRow({{ $index }})"
                        wire:confirm="{{ __('backoffice.confirm_delete_block') }}"
                    ><i class="la la-trash"></i></button>
                </div>

                @if($el['type'] === self::TEXT)
                    <div
                        class="col-12 mt-2"
                        wire:key="ckeditor-{{ $el['key'] }}"
                        wire:ignore
                        x-data="{
                        init() {
                            // Resolve the row's *current* index by its stable key so edits keep
                            // targeting the right row after other rows are added/removed.
                            const setData = (html) => {
                                // Don't write back while tearing down (e.g. on a type switch that
                                // just cleared this row's value) — that would resurrect stale data.
                                if ($el._destroying) return;
                                const i = $wire.content.findIndex(r => r.key === @js($el['key']));
                                if (i !== -1) $wire.set('content.' + i + '.value', html, false);
                            };
                            const create = () => window.ClassicEditor
                                .create($refs.input, @js($this->ckeditorConfig()))
                                .then(editor => {
                                    // Store on the DOM node, NOT on reactive `this`: Alpine would wrap
                                    // the editor in a Proxy and CKEditor's internals break on teardown.
                                    $el._ckeditor = editor;
                                    editor.model.document.on('change:data', () => setData(editor.getData()));
                                })
                                .catch(error => console.error(error));

                            // CKEditor loads once in after_scripts; wait for it if it isn't ready yet.
                            (function wait() {
                                window.ClassicEditor ? create() : setTimeout(wait, 50);
                            })();
                        },
                        destroy() {
                            $el._destroying = true;
                            $el._ckeditor?.destroy();
                            $el._ckeditor = null;
                        },
                    }"
                    >
                        <textarea x-ref="input">{!! $el['value'] !!}</textarea>
                    </div>
                @endif

                @if($el['type'] === self::IMAGES)
                    <div class="mt-2" wire:key="images-{{ $el['key'] }}">
                        <input
                            type="file"
                            class="form-control"
                            wire:model="photos.{{ $index }}"
                            multiple
                            accept="image/*"
                        >

                        <div class="form-text" wire:loading wire:target="photos.{{ $index }}">
                            <i class="la la-spinner la-spin"></i> Uploading…
                        </div>

                        @foreach($errors->get('photos.'.$index.'.*') as $messages)
                            @foreach((array) $messages as $message)
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @endforeach
                        @endforeach

                        @php($images = is_array($el['value'] ?? null) ? $el['value'] : [])
                        @if(count($images))
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach($images as $photoIndex => $path)
                                    @continue(! is_string($path))
                                    <div class="position-relative border rounded p-1" wire:key="img-{{ $index }}-{{ $photoIndex }}">
                                        <img src="{{ \Storage::disk('articles')->url($path) }}" alt="" style="height: 96px; width: auto; display: block;">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                            title="Remove image"
                                            wire:click="removePhoto({{ $index }}, {{ $photoIndex }})"
                                        ><i class="la la-remove"></i></button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                @if($el['type'] === self::VIDEO)
                    <div
                        class="mt-2"
                        wire:key="video-{{ $el['key'] }}"
                        wire:ignore
                        x-data="videoField({ key: @js($el['key']), value: @js($el['value']) })"
                    >
                        <div class="input-group">
                            <input
                                type="url"
                                class="form-control video-link"
                                placeholder="YouTube or Vimeo URL"
                                x-ref="link"
                                x-on:change="onChange($event.target.value)"
                            >
                            <span class="input-group-text" x-show="preview.url" x-cloak>
                            <a :href="preview.url" target="_blank" class="text-decoration-none">
                                <i class="la la-lg" :class="'la-' + preview.provider"></i>
                            </a>
                        </span>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-2" x-show="preview.url" x-cloak>
                            <img :src="preview.image" :alt="preview.title" style="max-height: 72px;" x-show="preview.image">
                            <small class="text-muted" x-text="preview.title"></small>
                        </div>
                    </div>
                @endif

                @if($el['type'] === self::MAP)
                    <div
                        class="mt-2"
                        wire:key="map-{{ $el['key'] }}"
                        wire:ignore
                        x-data="mapField({
                            key: @js($el['key']),
                            value: @js($el['value']),
                            defaultLat: @js((float) config('services.google_places.default_lat', 44.4267674)),
                            defaultLng: @js((float) config('services.google_places.default_lng', 26.1025384)),
                        })"
                    >
                        <div class="d-flex gap-2 align-items-center">
                            {{-- The Places Autocomplete (New) web component is injected here at runtime.
                                 The legacy SearchBox/Autocomplete need the legacy Places API, which is
                                 disabled on the project — the new element uses Places API (New). --}}
                            <div x-ref="searchContainer" class="flex-grow-1"></div>
                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                title="{{ __('backoffice.map_locate') }}"
                                x-on:click="locate()"
                            ><i class="la la-map-marker"></i></button>
                        </div>
                        <div x-ref="map" class="mt-2 rounded border" style="width: 100%; height: 350px;"></div>
                        <p class="text-muted small mt-1 mb-0" x-show="address" x-cloak>
                            <i class="la la-map-marker"></i> <span x-text="address"></span>
                        </p>
                    </div>
                @endif
            </div>

        @endforeach
    </div>

    <button class="btn btn-outline-primary mt-5" type="button" wire:click="addRow">
        <i class="la la-plus"></i> Add block
    </button>
</div>

@push('after_scripts')
    @basset('https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js')

    <script>
        // Video link parsing helpers, reused verbatim from Backpack's crud::fields.video.
        // Defined here (guarded) because that field view depends on $crud and its own
        // field-init loop, neither of which are available inside a Livewire component.
        if (typeof window.parseVideoLink !== 'function') {
            window.tryYouTube = function (link) {
                let id = null;
                let youtubeStandardExpr = /^https?:\/\/(www\.)?youtube.com\/watch\?v=([^?&]+)/i;
                let youtubeAlternateExpr = /^https?:\/\/(www\.)?youtube.com\/v\/([^\/\?]+)/i;
                let youtubeShortExpr = /^https?:\/\/youtu.be\/([^\/]+)/i;
                let youtubeEmbedExpr = /^https?:\/\/(www\.)?youtube.com\/embed\/([^\/]+)/i;
                let match = link.match(youtubeStandardExpr);
                if (match != null) {
                    id = match[2];
                } else {
                    match = link.match(youtubeAlternateExpr);
                    if (match != null) {
                        id = match[2];
                    } else {
                        match = link.match(youtubeShortExpr);
                        if (match != null) {
                            id = match[1];
                        } else {
                            match = link.match(youtubeEmbedExpr);
                            if (match != null) {
                                id = match[2];
                            }
                        }
                    }
                }
                return id;
            };

            window.tryVimeo = function (link) {
                let id = null;
                let regExp = /(http|https):\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;
                let match = link.match(regExp);
                if (match) {
                    id = match[3];
                }
                return id;
            };

            window.fetchYouTube = function (videoId, callback, apiKey) {
                let video = {
                    provider: 'youtube',
                    id: videoId,
                    title: null,
                    image: null,
                    url: 'https://www.youtube.com/watch?v=' + videoId
                };
                if (!apiKey) {
                    video.image = 'https://img.youtube.com/vi/' + videoId + '/hqdefault.jpg';
                    return callback(video);
                }
                let api = 'https://www.googleapis.com/youtube/v3/videos?id=' + videoId + '&key=' + apiKey + '&part=snippet';
                $.ajax({
                    dataType: "jsonp",
                    url: api,
                    crossDomain: true,
                    success: function (data) {
                        if (typeof (data.items[0]) != "undefined") {
                            let v = data.items[0].snippet;
                            video.title = v.title;
                            video.image = v.thumbnails.maxres ? v.thumbnails.maxres.url : v.thumbnails.default.url;
                            callback(video);
                        }
                    }
                });
            };

            window.fetchVimeo = function (videoId, callback) {
                let api = 'https://vimeo.com/api/v2/video/' + videoId + '.json';
                let video = {provider: 'vimeo', id: null, title: null, image: null, url: null};
                fetch(api).then(function (response) {
                    if (response.ok) {
                        response.json().then(function (v) {
                            v = v[0];
                            video.id = v.id;
                            video.title = v.title;
                            video.image = v.thumbnail_large || v.thumbnail_small;
                            video.url = v.url;
                            callback(video);
                        });
                    }
                });
            };

            window.parseVideoLink = function (link, callback, apiKey, messages) {
                messages = messages || {};
                let response = {success: false, message: messages.unknownError || '', data: []};
                try {
                    document.createElement('a');
                } catch (e) {
                    response.message = messages.invalidUrl || '';
                    return response;
                }

                let id = window.tryYouTube(link, apiKey);
                if (id) {
                    return window.fetchYouTube(id, function (video) {
                        if (video) {
                            response.success = true;
                            response.message = 'video found';
                            response.data = video;
                        }
                        callback(response);
                    }, apiKey);
                } else {
                    id = window.tryVimeo(link);
                    if (id) {
                        return window.fetchVimeo(id, function (video) {
                            if (video) {
                                response.success = true;
                                response.message = 'video found';
                                response.data = video;
                            }
                            callback(response);
                        });
                    }
                }
                response.message = messages.idNotDetected || '';
                return callback(response);
            };
        }

        // Alpine factory: bridges the parsed video object into the row's value, resolving the
        // row's current index by its stable key (robust to rows being added/removed).
        window.videoField = (config) => ({
            preview: {},
            setValue(val) {
                const i = this.$wire.content.findIndex(r => r.key === config.key);
                if (i !== -1) this.$wire.set('content.' + i + '.value', val, false);
            },
            init() {
                const v = config.value;
                if (v && v.url) {
                    this.preview = v;
                    this.$refs.link.value = v.url;
                }
            },
            onChange(url) {
                if (!url.length) {
                    this.preview = {};
                    this.setValue(null);
                    return;
                }
                window.parseVideoLink(url, (res) => {
                    if (res.success) {
                        this.preview = res.data;
                        this.$refs.link.value = res.data.url;
                        this.setValue(res.data);
                    } else {
                        console.error('Video field:', res.message);
                    }
                }, '', {});
            },
        });

        // Loads the Google Maps JS API once per page, regardless of how many map rows exist.
        // Returns a promise that resolves when `google.maps` is ready. The Backpack native
        // google_map field relies on $crud and a jQuery init loop that aren't available inside
        // a Livewire component, so the map is reimplemented here (same reason the video helpers
        // above are redefined).
        if (typeof window.loadGoogleMaps !== 'function') {
            window.loadGoogleMaps = function () {
                if (window._googleMapsPromise) {
                    return window._googleMapsPromise;
                }
                window._googleMapsPromise = new Promise((resolve, reject) => {
                    if (typeof google !== 'undefined' && google.maps) {
                        resolve();
                        return;
                    }
                    // If a Maps script is already on the page (e.g. a Backpack google_map field),
                    // wait for it instead of injecting a second copy — a double include is itself
                    // a cause of the "This page can't load Google Maps correctly" overlay.
                    if (document.querySelector('script[src*="maps.googleapis.com/maps/api/js"]')) {
                        const poll = setInterval(() => {
                            if (typeof google !== 'undefined' && google.maps) {
                                clearInterval(poll);
                                resolve();
                            }
                        }, 100);
                        return;
                    }
                    window._googleMapsInit = () => resolve();
                    const script = document.createElement('script');
                    const params = new URLSearchParams({
                        key: @js(config('services.google_places.key')),
                        libraries: 'places,marker',
                        callback: '_googleMapsInit',
                        loading: 'async',
                        language: @js(app()->getLocale()),
                    });
                    script.src = 'https://maps.googleapis.com/maps/api/js?' + params.toString();
                    script.async = true;
                    script.defer = true;
                    script.onerror = reject;
                    document.head.appendChild(script);
                });
                return window._googleMapsPromise;
            };
        }

        // Alpine factory: renders a Google Map with a draggable marker, a Places Autocomplete (New)
        // search box and a geolocate button. The value is stored as {lat, lng, formatted_address} —
        // the same shape Backpack's google_map field persists — keyed to the row by its stable key.
        window.mapField = (config) => ({
            map: null,
            marker: null,
            address: '',
            setValue(val) {
                const i = this.$wire.content.findIndex(r => r.key === config.key);
                if (i !== -1) this.$wire.set('content.' + i + '.value', val, false);
            },
            async init() {
                await window.loadGoogleMaps();

                const v = config.value || {};
                const hasValue = v && v.lat != null && v.lng != null;
                const center = hasValue
                    ? { lat: parseFloat(v.lat), lng: parseFloat(v.lng) }
                    : { lat: config.defaultLat, lng: config.defaultLng };

                this.address = hasValue ? (v.formatted_address || '') : '';

                this.map = new google.maps.Map(this.$refs.map, {
                    center,
                    zoom: hasValue ? 16 : 12,
                    mapTypeId: 'roadmap',
                    mapId: 'dynamic-map-' + config.key,
                });

                this.marker = new google.maps.marker.AdvancedMarkerElement({
                    map: this.map,
                    position: center,
                    gmpDraggable: true,
                });

                this.marker.addListener('dragend', () => this.savePosition(this.marker.position));
                this.map.addListener('click', ({ latLng }) => {
                    this.marker.position = latLng;
                    this.savePosition(latLng);
                });

                // Places Autocomplete (New) — a web component appended into the search container.
                const autocomplete = new google.maps.places.PlaceAutocompleteElement();
                autocomplete.style.width = '100%';
                this.$refs.searchContainer.appendChild(autocomplete);
                autocomplete.addEventListener('gmp-select', async ({ placePrediction }) => {
                    const place = placePrediction.toPlace();
                    await place.fetchFields({ fields: ['location', 'formattedAddress'] });
                    const loc = place.location;
                    if (!loc) return;
                    this.map.panTo(loc);
                    this.map.setZoom(16);
                    this.marker.position = loc;
                    this.savePosition(loc, place.formattedAddress);
                });
            },
            locate() {
                if (!navigator.geolocation) return;
                navigator.geolocation.getCurrentPosition((position) => {
                    const loc = { lat: position.coords.latitude, lng: position.coords.longitude };
                    this.map.panTo(loc);
                    this.map.setZoom(16);
                    this.marker.position = loc;
                    this.savePosition(loc);
                });
            },
            async savePosition(pos, address) {
                const lat = typeof pos.lat === 'function' ? pos.lat() : pos.lat;
                const lng = typeof pos.lng === 'function' ? pos.lng() : pos.lng;
                if (address === undefined) {
                    address = await this.reverseGeocode({ lat, lng });
                }
                this.address = address || '';
                this.setValue({ lat, lng, formatted_address: address || '' });
            },
            reverseGeocode(latlng) {
                return new Promise(resolve => {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ location: latlng }, (results, status) => {
                        resolve(status === 'OK' && results[0] ? results[0].formatted_address : '');
                    });
                });
            },
        });
    </script>
@endpush
