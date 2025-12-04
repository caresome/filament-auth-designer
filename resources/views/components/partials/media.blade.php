@props([
    'config',
    'imageClass' => '',
    'videoClass' => '',
])

@if($config->isVideo())
    <video
        autoplay
        loop
        muted
        playsinline
        class="{{ $videoClass }}"
    >
        <source src="{{ $config->media }}" type="{{ $config->mediaMimeType }}">
    </video>
@else
    <img
        src="{{ $config->media }}"
        alt="{{ $config->mediaAlt ?? 'Authentication' }}"
        class="{{ $imageClass }}"
    />
@endif
