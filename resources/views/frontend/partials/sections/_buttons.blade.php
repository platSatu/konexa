{{-- Tombol CTA section (maks. 2), link sudah divalidasi di Teleios. --}}
@if (! empty($section['buttons']))
    <div class="d-flex flex-wrap gap-2 mt-5 {{ ($section['text_align'] ?? 'center') === 'left' ? 'justify-content-start' : 'justify-content-center' }}">
        @foreach ($section['buttons'] as $button)
            <a href="{{ $button['link'] }}" class="btn {{ $loop->first ? 'btn-primary' : (! empty($section['is_dark']) ? 'btn-outline-light' : 'btn-outline-primary') }} btn-lg px-4"
                @if (str_starts_with($button['link'], 'http')) target="_blank" rel="noopener" @endif>{{ $button['text'] }}</a>
        @endforeach
    </div>
@endif
