{{--
    Bingkai section tambahan beranda: background (warna/gambar/video),
    judul, subjudul, isi sesuai tipe, tombol CTA. Style background sudah
    disanitasi di FrontendController::sectionStyle().
--}}
@php
    $videoUrl = ($section['background']['type'] ?? null) === 'video' ? ($section['background']['video_url'] ?? null) : null;
@endphp
<section class="home-section position-relative overflow-hidden py-5 {{ $section['is_dark'] ? 'home-section--dark text-white' : '' }}" style="{{ $section['style'] }}">
    @if ($videoUrl)
        <video autoplay muted loop playsinline class="home-section-video" aria-hidden="true">
            <source src="{{ $videoUrl }}">
        </video>
        <div class="home-section-overlay" aria-hidden="true"></div>
    @endif

    <div class="container position-relative py-lg-4">
        @include('frontend.partials.sections._heading')
        @includeIf('frontend.partials.sections.'.$section['type'])
        @include('frontend.partials.sections._buttons')
    </div>
</section>
