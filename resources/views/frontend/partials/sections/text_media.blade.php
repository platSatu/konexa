@php
    $paragraphs = preg_split('/\R\s*\R/', trim((string) ($section['content'] ?? ''))) ?: [];
    $mediaLeft = ($section['media_position'] ?? 'right') === 'left';
@endphp
<div class="row g-5 align-items-center">
    <div class="{{ ! empty($section['media_image_url']) ? 'col-lg-6' : 'col-lg-10 mx-auto' }} {{ $mediaLeft ? 'order-lg-2' : '' }}">
        @foreach ($paragraphs as $paragraph)
            @if (trim($paragraph) !== '')
                <p class="home-text">{!! nl2br(e(trim($paragraph))) !!}</p>
            @endif
        @endforeach
    </div>
    @if (! empty($section['media_image_url']))
        <div class="col-lg-6 {{ $mediaLeft ? 'order-lg-1' : '' }}">
            <img src="{{ $section['media_image_url'] }}" alt="{{ $section['title'] ?? '' }}" class="img-fluid rounded-4 shadow-sm w-100" loading="lazy">
        </div>
    @endif
</div>
