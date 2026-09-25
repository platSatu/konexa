{{-- Judul + subjudul section. $section, $defaultTitle, $defaultSubtitle opsional. --}}
@php
    $headingTitle = ($section['title'] ?? null) ?: ($defaultTitle ?? null);
    $headingSubtitle = ($section['subtitle'] ?? null) ?: ($defaultSubtitle ?? null);
    $headingLeft = ($section['text_align'] ?? 'center') === 'left';
@endphp
@if ($headingTitle || $headingSubtitle)
    <div class="mb-5 {{ $headingLeft ? 'text-start' : 'text-center' }}">
        @if ($headingTitle)
            <h2 class="fw-bold mb-2">{{ $headingTitle }}</h2>
        @endif
        @if ($headingSubtitle)
            <p class="{{ ! empty($section['is_dark']) ? 'text-white-50' : 'text-muted' }} mb-0 {{ $headingLeft ? '' : 'mx-auto' }}" style="max-width: 640px;">{{ $headingSubtitle }}</p>
        @endif
    </div>
@endif
