{{--
    Fitur unggulan dari API Teleios (TeleiosApiService::getFeatures(),
    Superadmin > Web > Fitur) sebagai Bento Grid. Ukuran kotak mengikuti
    pola 12 kotak berulang berdasarkan urutan fitur (kotak pertama besar);
    warna & tata letak responsif di frontend.css bagian "Fitur Unggulan --
    Bento Grid". Background mengikuti Susunan Beranda; kalau belum diatur,
    pakai warna lembut supaya terpisah dari section putih di sekitarnya.
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
            @php
                $tileSizes = ['big', 'wide', 'small', 'small', 'tall', 'wide', 'small', 'tall', 'wide', 'small', 'small', 'small'];
            @endphp
            <div class="features-bento">
                @foreach ($features as $feature)
                    @php
                        $tileSize = $tileSizes[$loop->index % count($tileSizes)];
                        // Fallback kalau deskripsi belum diisi di Superadmin > Web > Fitur.
                        $featureDescription = trim((string) ($feature['description'] ?? ''))
                            ?: 'Fitur ini dirancang untuk membantu bisnis Anda berjalan lebih efisien dan otomatis, tanpa ribet.';
                    @endphp
                    <article class="feature-tile feature-tile--{{ $tileSize }}">
                        <div class="feature-tile-text">
                            <h3 class="feature-tile-title">{{ $feature['name'] }}</h3>
                            <p class="feature-tile-desc">{{ $featureDescription }}</p>
                        </div>
                        @if (! empty($feature['images_url']))
                            <div class="feature-tile-art">
                                <img src="{{ $feature['images_url'] }}" alt="{{ $feature['name'] }}" loading="lazy">
                            </div>
                        @endif
                        @if (in_array($tileSize, ['small', 'tall'], true))
                            <p class="feature-tile-hover" aria-hidden="true">{{ $featureDescription }}</p>
                        @endif
                    </article>
                @endforeach
            </div>

            @if (count($features) > 2)
                <p class="features-swipe-hint mb-0">Geser untuk melihat fitur lainnya &rarr;</p>
            @endif
        @endif

        @include('frontend.partials.sections._buttons')
    </div>
</section>
