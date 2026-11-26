{{--
    Bingkai section tambahan beranda: background (warna/gambar/video),
    judul, subjudul, isi sesuai tipe, tombol CTA. Style background sudah
    disanitasi di FrontendController::sectionStyle().

    Mode "alur": kalau section bertipe icon_grid dan SEMUA judul item diawali
    nomor ("1. ...", "2. ..."), section ditampilkan sebagai stepper bersambung
    (lihat icon_grid.blade.php) dan diberi id="cara-kerja" supaya bisa dituju
    dari tombol "Lihat caranya" di section rasa sakit (kenal-situasi.blade.php).
--}}
@php
    $videoUrl = ($section['background']['type'] ?? null) === 'video' ? ($section['background']['video_url'] ?? null) : null;
    $items = $section['items'] ?? [];
    $isSteps = ($section['type'] ?? '') === 'icon_grid' && count($items) > 1
        && collect($items)->every(fn ($item) => preg_match('/^\s*\d+[.)]\s+/', (string) ($item['title'] ?? '')));
@endphp
<section @if ($isSteps) id="cara-kerja" @endif data-reveal
    class="home-section position-relative overflow-hidden py-5 {{ $isSteps ? 'home-section--steps' : '' }} {{ $section['is_dark'] ? 'home-section--dark text-white' : '' }}" style="{{ $section['style'] }}">
    @if ($videoUrl)
        <video autoplay muted loop playsinline class="home-section-video" aria-hidden="true">
            <source src="{{ $videoUrl }}">
        </video>
        <div class="home-section-overlay" aria-hidden="true"></div>
    @endif

    <div class="container position-relative py-lg-4">
        @include('frontend.partials.sections._heading')
        @includeIf('frontend.partials.sections.'.$section['type'], ['isSteps' => $isSteps])
        @include('frontend.partials.sections._buttons')
    </div>
</section>
@if ($isSteps && ($showVideoAfterSteps ?? false))
    @include('frontend.partials.lihat-bizbos')
@endif
