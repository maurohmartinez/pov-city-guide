{{-- text input --}}
<?php

$value = old_empty_or_null($field['name'], '') ?? $field['value'] ?? $field['default'] ?? '';

// if attribute casting is used, convert to JSON
if (is_array($value)) {
    $value = json_encode((object) $value);
} elseif (is_object($value)) {
    $value = json_encode($value);
} else {
    $value = $value;
}

$field['youtube_api_key'] = $field['youtube_api_key'] ?? '';

$field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
$field['wrapper']['data-init-function'] = 'bpFieldInitVideoElement';
$field['wrapper']['data-youtube-api-key'] = $field['youtube_api_key'];
$field['wrapper']['data-video'] = '';
$field['wrapper']['data-video-unknown-error'] = trans('backpack::crud.video_unknown_error');
$field['wrapper']['data-video-invalid-url'] = trans('backpack::crud.video_invalid_url');
$field['wrapper']['data-video-id-not-detected'] = trans('backpack::crud.video_id_not_detected');
$field['wrapper']['data-video-still-loading'] = trans('backpack::crud.video_still_loading');
?>


@include('crud::fields.inc.wrapper_start')
    <label for="{{ $field['name'] }}_link">{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    <input class="video-json" type="hidden" name="{{ $field['name'] }}" value="{{ $value }}">
    <div class="input-group">
        <input @include('crud::fields.inc.attributes', ['default_class' => 'video-link form-control']) type="url" id="{{ $field['name'] }}_link" bp-field-main-input>
        <div class="input-group-append video-previewSuffix video-noPadding">
            <div class="video-preview">
                <span class="video-previewImage"></span>
                <a class="video-previewLink hidden" target="_blank" href="">
                    <i class="la la-lg video-previewIcon"></i>
                </a>
            </div>
            <div class="video-dummy">
                <a class="video-previewLink youtube dummy" target="_blank" href="">
                    <i class="la la-lg la-youtube video-previewIcon dummy"></i>
                </a>
                <a class="video-previewLink vimeo dummy" target="_blank" href="">
                    <i class="la la-lg la-vimeo video-previewIcon dummy"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}


    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        @bassetBlock('backpack/pro/fields/video-field.css')
        <style media="screen">
            .video-previewSuffix {
                border: 0;
                min-width: 68px; }
            .video-noPadding {
                padding: 0; }
            .video-preview {
                display: none; }
            .video-previewLink {
                 color: #fff;
                 display: block;
                 width: 2.375rem; height: 2.375rem;
                 text-align: center;
                 float: left; }
            .video-previewLink.youtube {
                background: #DA2724; }
            .video-previewLink.vimeo {
                background: #00ADEF; }
            .video-previewIcon {
                transform: translateY(7px); }
            .video-previewImage {
                float: left;
                display: block;
                width: 2.375rem; height: 2.375rem;
                background-size: cover;
                background-position: center center; }
        </style>
        @endBassetBlock
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    {{--
        NOTE: Local override of vendor/backpack/pro/resources/views/fields/video.blade.php
        Changes vs. the packaged field:
          1. tryYouTube() now also recognises YouTube Shorts URLs (youtube.com/shorts/ID).
          2. Vimeo detection uses the oEmbed endpoint instead of the legacy api/v2 endpoint,
             so numeric, unlisted (vimeo.com/ID/HASH), player.vimeo.com and channel URLs all resolve.
             (The legacy endpoint also silently hung on failure because it never called the callback.)
        Note: Vimeo "Share > Copy link" URLs of the form vimeo.com/share/<uuid> are NOT public
        video URLs (Vimeo returns 404 for them), so they cannot be resolved to an embeddable id.
        Use the canonical vimeo.com/<number> link instead.
    --}}
    @push('crud_fields_scripts')
        @bassetBlock('backpack/pro/fields/video-field.js')
        <script>

        var tryYouTube = function( link ){

            // RegExps for the supported YouTube link forms. `group` is the capture group holding the video ID.
            var patterns = [
                { re: /^https?:\/\/(www\.)?youtube\.com\/watch\?v=([^?&]+)/i, group: 2 }, // standard
                { re: /^https?:\/\/(www\.)?youtube\.com\/v\/([^\/\?]+)/i,     group: 2 }, // alternate
                { re: /^https?:\/\/youtu\.be\/([^\/\?]+)/i,                   group: 1 }, // short link
                { re: /^https?:\/\/(www\.)?youtube\.com\/embed\/([^\/\?]+)/i, group: 2 }, // embed
                { re: /^https?:\/\/(www\.)?youtube\.com\/shorts\/([^\/\?]+)/i, group: 2 }, // shorts
            ];

            for (var i = 0; i < patterns.length; i++) {
                var match = link.match(patterns[i].re);
                if (match != null) {
                    return match[patterns[i].group];
                }
            }

            return null;
        };

        var tryVimeo = function( link ){

            // Accept any standard Vimeo URL; the numeric id is resolved via oEmbed in fetchVimeo().
            // Covers vimeo.com/ID, vimeo.com/ID/HASH (unlisted), player.vimeo.com/video/ID,
            // vimeo.com/channels/.../ID, vimeo.com/groups/.../videos/ID, etc.
            var regExp = /^https?:\/\/(www\.|player\.)?vimeo\.com\/.+/i;

            return regExp.test(link) ? link : null;
        };

        var fetchYouTube = function( videoId, callback, apiKey ){

            var video = {
                provider: 'youtube',
                id: videoId,
                title: null,
                image: null,
                url: 'https://www.youtube.com/watch?v=' + videoId
            };

            if (!apiKey) {
                console.info('Backpack Video Field: No YouTube API key set. Provide one via the "youtube_api_key" attribute to fetch video title and thumbnail. The video URL will still be saved.');
                video.image = 'https://img.youtube.com/vi/' + videoId + '/hqdefault.jpg';
                return callback(video);
            }

            var api = 'https://www.googleapis.com/youtube/v3/videos?id='+videoId+'&key='+apiKey+'&part=snippet';

            $.ajax({
                dataType: "jsonp",
                url: api,
                crossDomain: true,
                success: function (data) {
                    if (typeof(data.items[0]) != "undefined") {
                        var v = data.items[0].snippet;

                        video.title = v.title;
                        video.image = v.thumbnails.maxres ? v.thumbnails.maxres.url : v.thumbnails.default.url;

                        callback(video);
                    }
                }
            });
        };

        var fetchVimeo = function( link, callback ){

            // oEmbed resolves the numeric id (and title/thumbnail) for any public Vimeo URL,
            // including unlisted vimeo.com/ID/HASH and player.vimeo.com URLs.
            var api = 'https://vimeo.com/api/oembed.json?url=' + encodeURIComponent(link);

            var video = {
                provider: 'vimeo',
                id: null,
                title: null,
                image: null,
                url: link
            };

            fetch(api).then(function(response) {
                if(response.ok) {
                    response.json().then(function(v) {
                        video.id = v.video_id;
                        video.title = v.title;
                        video.image = v.thumbnail_url;
                        video.url = 'https://vimeo.com/' + v.video_id;
                        callback(video);
                    });
                } else {
                    // Not a resolvable public video (e.g. a vimeo.com/share/<uuid> link).
                    callback(null);
                }
            }).catch(function() {
                callback(null);
            });
        };

        var parseVideoLink = function( link, callback, apiKey, messages ){
            messages = messages || {};

            var response = {success: false, message: messages.unknownError || '', data: [] };

            try {
                var parser = document.createElement('a');
            } catch(e){
                response.message = messages.invalidUrl || '';
                return response;
            }


            var id = tryYouTube(link, apiKey);

            if( id ){

                return fetchYouTube(id, function(video){

                    if( video ){
                        response.success = true;
                        response.message = 'video found';
                        response.data = video;
                    }

                    callback(response);
                },apiKey);
            }
            else {

                var vimeoLink = tryVimeo(link);

                if( vimeoLink ){

                    return fetchVimeo(vimeoLink, function(video){

                        if( video ){
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

        var updateVideoPreview = function(video, container){

            var pWrap = container.find('.video-preview'),
                pLink = container.find('.video-previewLink').not('.dummy'),
                pImage = container.find('.video-previewImage').not('dummy'),
                pIcon  = container.find('.video-previewIcon').not('.dummy'),
                pSuffix = container.find('.video-previewSuffix'),
                pDummy  = container.find('.video-dummy');

            pDummy.hide();

            pLink
            .attr('href', video.url)
            .removeClass('youtube vimeo hidden')
            .addClass(video.provider);

            pImage
            .css('backgroundImage', 'url('+video.image+')');

            pIcon
            .removeClass('la-vimeo la-youtube')
            .addClass('la-' + video.provider);
            pWrap.fadeIn();
        };

        var videoParsing = false;

        function bpFieldInitVideoElement(element) {
            var $this = element,
                jsonField = $this.find('.video-json'),
                linkField = $this.find('.video-link'),
                pDummy = $this.find('.video-dummy'),
                pWrap = $this.find('.video-preview'),
                apiKey = $this.attr('data-youtube-api-key');

            var videoMessages = {
                unknownError: element.data('video-unknown-error'),
                invalidUrl: element.data('video-invalid-url'),
                idNotDetected: element.data('video-id-not-detected'),
            };

                try {
                    var videoJson = JSON.parse(jsonField.val());
                    jsonField.val( JSON.stringify(videoJson) );
                    linkField.val( videoJson.url );
                    updateVideoPreview(videoJson, $this);
                }
                catch(e){
                    pDummy.show();
                    pWrap.hide();
                    jsonField.val('');
                    linkField.val('');
                }

            linkField.on('focus', function(){
                linkField.originalState = linkField.val();
            });

            linkField.on('change', function(){

                if( linkField.originalState != linkField.val() ){

                    if( linkField.val().length ){

                        videoParsing = true;

                        parseVideoLink( linkField.val(), function( videoJson ){

                            if( videoJson.success ){
                                linkField.val( videoJson.data.url );
                                jsonField.val( JSON.stringify(videoJson.data) );
                                updateVideoPreview(videoJson.data, $this);
                            }
                            else {
                                pDummy.show();
                                pWrap.hide();
                                new Noty({
                                    type: "error",
                                    text: videoJson.message
                                }).show();
                            }

                            videoParsing = false;
                        }, apiKey, videoMessages);
                    }
                    else {
                        videoParsing = false;
                        jsonField.val('');
                        $this.find('.video-preview').fadeOut();
                        pDummy.show();
                        pWrap.hide();
                    }
                }
            });

            element.closest('form').on('submit.bpVideo', function(e){
                if( videoParsing ){
                    new Noty({
                        type: "error",
                        text: element.data('video-still-loading')
                    }).show();
                    e.preventDefault();
                    return false;
                }
            });
        }
        </script>
        @endBassetBlock

    @endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
