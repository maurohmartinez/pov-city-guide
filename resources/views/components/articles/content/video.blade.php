@php
    // $value is the object stored by the dynamic_content field: {provider, id, title, image, url}
    $video = is_array($value ?? null) ? $value : [];
    $id = $video['id'] ?? null;

    $embed = match ($video['provider'] ?? null) {
        'youtube' => $id ? 'https://www.youtube.com/embed/' . $id : null,
        'vimeo' => $id ? 'https://player.vimeo.com/video/' . $id : null,
        default => null,
    };
@endphp

@if($embed)
    <div class="ratio ratio-16x9">
        <iframe
            src="{{ $embed }}"
            title="{{ $video['title'] ?? '' }}"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen
            loading="lazy"
        ></iframe>
    </div>
@endif
