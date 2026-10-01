{{--
    Fitur unggulan dari API Teleios (TeleiosApiService::getFeatures(),
    Superadmin > Web > Fitur), tampil sebagai Bento Grid: kartu pertama
    (urutan teratas) besar, sisanya kecil -- pola & ukuran di frontend.css
    bagian "Fitur Unggulan -- Bento Grid". Background mengikuti Susunan
    Beranda; kalau belum diatur, pakai warna lembut supaya terpisah dari
    section putih di sekitarnya.
--}}
@php
    // Bingkai dari Susunan Beranda (Teleios) -- judul, background, tombol.
    $section = ($section ?? []) + ['style' => '', 'is_dark' => false];
@endphp
<section id="features" class="py-5 features-section {{ $section['style'] === '' ? 'features-section--tinted' : '' }} {{ $section['is_dark'] ? 'text-white' : '' }}" style="{{ $section['style'] }}">
    <div class="container">
        @include('frontend.partials.sections._heading', [
            'defaultTitle' => 'Fitur Unggulan',
            'defaultSubtitle' => 'Semua yang Anda butuhkan untuk mengelola percakapan WhatsApp bisnis dalam satu platform — dari otomasi berbasis AI sampai manajemen pelanggan yang terintegrasi.',
        ])

        @if (empty($features))
            <p class="text-center text-muted mb-0">Belum ada fitur saat ini.</p>
        @else
            <div class="features-bento">
                @foreach ($features as $feature)
                    @php
                        // Fallback kalau deskripsi belum diisi di Superadmin > Web > Fitur.
                        $featureDescription = trim((string) ($feature['description'] ?? ''))
                            ?: 'Fitur ini dirancang untuk membantu bisnis Anda berjalan lebih efisien dan otomatis, tanpa ribet.';
                    @endphp
                    <article class="feature-card">
                        <div class="feature-card-body">
                            <h3 class="feature-card-title">{{ $feature['name'] }}</h3>
                            <p class="feature-card-text text-muted mb-0">{{ $featureDescription }}</p>
                        </div>
                        @if (! empty($feature['images_url']))
                            <div class="feature-card-media">
                                <img src="{{ $feature['images_url'] }}" alt="{{ $feature['name'] }}" loading="lazy">
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif

        @include('frontend.partials.sections._buttons')
    </div>
</section>
