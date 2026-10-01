{{--
    Section CTA (tipe "banner" di Teleios Superadmin > Web > Susunan Beranda),
    biasanya paling bawah sebelum footer. Kata di judul yang diapit *bintang*
    disorot. Tanpa background dari Teleios: kartu gelap dengan cahaya merah &
    kuning (frontend.css bagian "CTA"); kalau background diatur di Teleios,
    background itu yang dipakai di kartu.
--}}
@php
    $hasCustomBackground = $section['style'] !== '';
    $isDark = ! $hasCustomBackground || $section['is_dark'];
    // Escape dulu, baru *kata* diubah jadi sorotan -- aman dari HTML di judul.
    $ctaTitle = preg_replace('/\*([^*]+)\*/', '<span class="cta-highlight">$1</span>', e((string) ($section['title'] ?? '')));
    $videoUrl = ($section['background']['type'] ?? null) === 'video' ? ($section['background']['video_url'] ?? null) : null;
@endphp
<section class="cta-section py-5" data-reveal>
    <div class="container">
        <div class="cta-card {{ $hasCustomBackground ? '' : 'cta-card--default' }} {{ $isDark ? 'text-white' : '' }}" style="{{ $section['style'] }}">
            @if ($videoUrl)
                <video autoplay muted loop playsinline class="home-section-video" aria-hidden="true">
                    <source src="{{ $videoUrl }}">
                </video>
                <div class="home-section-overlay" aria-hidden="true"></div>
            @endif

            <div class="cta-content">
                @if ($ctaTitle !== '')
                    <h2 class="cta-title">{!! $ctaTitle !!}</h2>
                @endif
                @if (! empty($section['subtitle']))
                    <p class="cta-subtitle">{{ $section['subtitle'] }}</p>
                @endif

                @if (! empty($section['buttons']))
                    <div class="cta-buttons">
                        @foreach ($section['buttons'] as $button)
                            <a href="{{ $button['link'] }}" class="btn btn-lg {{ $loop->first ? 'cta-btn-primary' : 'cta-btn-secondary' }}"
                                @if (str_starts_with($button['link'], 'http')) target="_blank" rel="noopener" @endif>
                                {{ $button['text'] }}
                                @if ($loop->first)
                                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
