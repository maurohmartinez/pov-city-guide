<?php

use App\Services\ImageProcessingService;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {

    use WithFileUploads;

    private const string IMAGES_DISK = 'articles';

    private const string TEXT = 'text';

    private const string IMAGES = 'images';

    private const string VIDEO = 'video';

    private const array TYPES_MAPPED = [
        self::TEXT => 'Text',
        self::IMAGES => 'Images',
        self::VIDEO => 'Video',
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

    public array $content = []; // ['type', 'size', 'value']

    public string $fieldName = 'content';

    // Transient per-row uploads, keyed by row index. Moved to disk in updatedPhotos().
    public array $photos = [];

    public function mount(mixed $value = null, string $fieldName = 'content'): void
    {
        $this->fieldName = $fieldName;

        $decoded = is_array($value) ? $value : json_decode((string)$value, true);
        $this->content = is_array($decoded) ? $decoded : [];
    }

    public function addRow(): void
    {
        $this->content[] = [
            'type' => 'text',
            'size' => '12',
            'value' => null,
        ];
    }

    // Fires when a row's file input changes. Moves the freshly uploaded files to the
    // images disk and appends their paths to content[index]['value'].
    public function updatedPhotos(mixed $value, ?string $key = null): void
    {
        $index = (int) $key;

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

        if (! is_array($files) || ! isset($files[$photoIndex])) {
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

<div x-data="{ content: $wire.entangle('content') }">
    {{-- Mirrors $content into the Backpack form as JSON so the plain POST submit picks it up. --}}
    <input type="hidden" name="{{ $fieldName }}" :value="JSON.stringify(content)">

    <div class="row">
        @foreach($content as $index => $el)
            <div class="col-md-{{ $el['size'] }} px-2 mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-control type" wire:model.live="content.{{ $index }}.type">
                            @foreach(self::TYPES_MAPPED as $type => $name)
                                <option value="{{ $type }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control size" wire:model.live="content.{{ $index }}.size">
                            @foreach(self::SIZES_MAPPED as $size => $name)
                                <option value="{{ $size }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($el['type'] === self::TEXT)
                    <div
                        class="col-12 mt-2"
                        wire:key="ckeditor-{{ $index }}"
                        wire:ignore
                        x-data="{
                        init() {
                            const setData = (html) => $wire.set('content.{{ $index }}.value', html, false);
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
                            $el._ckeditor?.destroy();
                            $el._ckeditor = null;
                        },
                    }"
                    >
                        <textarea x-ref="input">{!! $el['value'] !!}</textarea>
                    </div>
                @endif

                @if($el['type'] === self::IMAGES)
                    <div class="mt-2" wire:key="images-{{ $index }}">
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
                        wire:key="video-{{ $index }}"
                        wire:ignore
                        x-data="videoField({ index: {{ $index }}, value: @js($el['value']) })"
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
            </div>

        @endforeach
    </div>

    <button class="btn btn-outline-primary mt-5" type="button" wire:click="addRow">
        <i class="la la-plus"></i> Add content
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
                var id = null;
                var youtubeStandardExpr = /^https?:\/\/(www\.)?youtube.com\/watch\?v=([^?&]+)/i;
                var youtubeAlternateExpr = /^https?:\/\/(www\.)?youtube.com\/v\/([^\/\?]+)/i;
                var youtubeShortExpr = /^https?:\/\/youtu.be\/([^\/]+)/i;
                var youtubeEmbedExpr = /^https?:\/\/(www\.)?youtube.com\/embed\/([^\/]+)/i;
                var match = link.match(youtubeStandardExpr);
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
                var id = null;
                var regExp = /(http|https):\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;
                var match = link.match(regExp);
                if (match) {
                    id = match[3];
                }
                return id;
            };

            window.fetchYouTube = function (videoId, callback, apiKey) {
                var video = {
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
                var api = 'https://www.googleapis.com/youtube/v3/videos?id=' + videoId + '&key=' + apiKey + '&part=snippet';
                $.ajax({
                    dataType: "jsonp",
                    url: api,
                    crossDomain: true,
                    success: function (data) {
                        if (typeof (data.items[0]) != "undefined") {
                            var v = data.items[0].snippet;
                            video.title = v.title;
                            video.image = v.thumbnails.maxres ? v.thumbnails.maxres.url : v.thumbnails.default.url;
                            callback(video);
                        }
                    }
                });
            };

            window.fetchVimeo = function (videoId, callback) {
                var api = 'https://vimeo.com/api/v2/video/' + videoId + '.json';
                var video = {provider: 'vimeo', id: null, title: null, image: null, url: null};
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
                var response = {success: false, message: messages.unknownError || '', data: []};
                try {
                    document.createElement('a');
                } catch (e) {
                    response.message = messages.invalidUrl || '';
                    return response;
                }

                var id = window.tryYouTube(link, apiKey);
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

        // Alpine factory: bridges the parsed video object into content[index].value.
        window.videoField = (config) => ({
            preview: {},
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
                    this.$wire.set('content.' + config.index + '.value', null, false);
                    return;
                }
                window.parseVideoLink(url, (res) => {
                    if (res.success) {
                        this.preview = res.data;
                        this.$refs.link.value = res.data.url;
                        this.$wire.set('content.' + config.index + '.value', res.data, false);
                    } else {
                        console.error('Video field:', res.message);
                    }
                }, '', {});
            },
        });
    </script>
@endpush
