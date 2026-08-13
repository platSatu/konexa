{{--
    Fitur unggulan, diambil dari API backend Teleios (lihat
    App\Services\TeleiosApiService::getFeatures()).

    Ditampilkan sebagai slider horizontal yang digeser MANUAL (native
    browser scroll + CSS scroll-snap, bukan library carousel/JS berat) —
    bukan grid yang menumpuk ke bawah seperti versi sebelumnya:
    - Desktop/tablet (>=768px): beberapa kartu terlihat sekaligus,
      digeser lewat drag mouse/trackpad atau tombol panah kiri/kanan
      (lihat public/js/frontend.js bagian "Features slider").
    - Mobile (<768px): kartu EDGE-TO-EDGE sungguhan — bukan kartu
      mengambang dengan jarak/shadow/rounded corner dari tepi layar
      (itu namanya "inset carousel", beda dari edge-to-edge), tapi
      betul-betul menempel rata di kedua sisi layar (border-radius &
      shadow dimatikan khusus breakpoint ini, lihat frontend.css). HANYA
      1 kartu penuh layar per geseran, track-nya "bocor" sampai ke tepi
      layar lewat negative margin di .features-slider-wrap supaya area
      geser dimulai PERSIS dari ujung layar. Tombol panah disembunyikan
      di breakpoint ini, diganti indikator titik (dot) di bawah kartu.

    Ukuran kartu diperbesar (lihat .feature-card-media & .feature-card-body
    di frontend.css) untuk menyisakan ruang buat ikon-ikon yang akan
    ditambahkan menyusul — ada wadah kosong .feature-card-icons di bawah
    judul yang otomatis tersembunyi (CSS :empty) selama belum dipakai,
    jadi aman ditambahkan sekarang tanpa mengubah tampilan dulu.
--}}
<section id="features" class="py-5 features-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2">Fitur Unggulan</h2>
            <p class="text-muted mx-auto mb-0" style="max-width: 560px;">
                Semua yang Anda butuhkan untuk mengelola percakapan WhatsApp bisnis dalam satu platform — dari otomasi berbasis AI sampai manajemen pelanggan yang terintegrasi.
            </p>
        </div>

        @if (empty($features))
            <p class="text-center text-muted mb-0">Belum ada fitur saat ini.</p>
        @else
            <div class="features-slider-wrap">
                <div class="features-slider" id="featuresSlider">
                    @foreach ($features as $feature)
                        @php
                            // Fallback statis kalau deskripsi belum diisi
                            // di Superadmin > Web > Fitur, supaya kartu
                            // tidak tampil kosong/pincang di slider —
                            // pola yang sama seperti fallback statis di
                            // packages.blade.php.
                            $featureDescription = trim((string) ($feature['description'] ?? ''));
                            if ($featureDescription === '') {
                                $featureDescription = 'Fitur ini dirancang untuk membantu bisnis Anda berjalan lebih efisien dan otomatis, tanpa ribet.';
                            }
                        @endphp
                        <div class="feature-slide">
                            <div class="feature-card">
                                @if (! empty($feature['images_url']))
                                    <div class="feature-card-media">
                                        <img src="{{ $feature['images_url'] }}" alt="{{ $feature['name'] }}" loading="lazy">
                                    </div>
                                @endif
                                <div class="feature-card-body">
                                    <h5 class="feature-card-title">{{ $feature['name'] }}</h5>

                                    {{-- Wadah ikon-ikon yang menyusul — kosong untuk sekarang, otomatis hilang lewat CSS :empty --}}
                                    <div class="feature-card-icons"></div>

                                    <p class="feature-card-text text-muted mb-0">{{ $featureDescription }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if (count($features) > 1)
                <div class="features-slider-controls d-none d-md-flex justify-content-end gap-2">
                    <button type="button" class="features-slider-btn features-slider-btn--prev" aria-label="Sebelumnya">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button type="button" class="features-slider-btn features-slider-btn--next" aria-label="Berikutnya">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <div class="features-slider-dots" id="featuresSliderDots"></div>
            @endif
        @endif
    </div>
</section>
