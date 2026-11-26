{{--
    Hero/header homepage (tema terang, senada dengan halaman produk /tiket, /kursus, dst).
    Judul, deskripsi & tombol per slide dari Superadmin > Web > Headers (TeleiosApiService::getHeaders());
    kalau belum ada slide aktif dipakai teks bawaan di bawah. Latar dibuat dengan CSS (krem + pola titik), jadi gambar/video & warna teks slide dari superadmin tidak dipakai di tema ini.
    Sisi kanan: ilustrasi alur (hero-flow.blade.php), kartunya bisa diklik ke halaman produk.
    Gaya: public/css/frontend.css bagian "Hero terang".
--}}
@php
    $slides = array_values($headers ?? []) ?: [[
        'text' => 'Urus murid, jadwal, dan SPP tanpa pusing tiap akhir bulan.',
        'descriptions' => 'Murid daftar sendiri, absen cukup scan QR, tagihan dan pengingat les terkirim otomatis ke WhatsApp orang tua. Anda tinggal pantau dari HP, dan bisa kembali fokus mengajar.',
        'button_action' => 'active',
        'button_text' => 'Coba Gratis 30 Hari',
        'button_link' => 'https://app.bizbos.id/auth/register',
    ]];
    $multi = count($slides) > 1;
@endphp

<section id="heroCarousel" class="hero-light {{ $multi ? 'carousel slide hero-carousel' : '' }}" @if ($multi) data-bs-ride="carousel" data-bs-interval="7000" @endif>

    @if ($multi)
        <div class="carousel-indicators">
            @foreach ($slides as $index => $slide)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"
                    @if ($index === 0) aria-current="true" @endif aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
    @endif

    <div class="{{ $multi ? 'carousel-inner' : '' }}">
        @foreach ($slides as $index => $slide)
            <div class="{{ $multi ? 'carousel-item'.($index === 0 ? ' active' : '') : '' }}">
                <div class="position-relative d-flex align-items-center hero-slide">
                    <div class="container py-5">
                        <div class="row align-items-center g-4 g-lg-5">
                            <div class="col-12 col-lg-6 hero-content">
                                <span class="hero-eyebrow"><i class="bi bi-stars"></i> Untuk pemilik <span class="pd-rotate" data-words="tempat kursus|kursus bahasa Inggris|kursus Mandarin|kursus bahasa Korea|kursus bahasa Jepang|bimbel|les musik|kursus coding">tempat kursus</span></span>
                                @if (! empty($slide['text']))
                                    <h1 class="display-4 fw-bold">{{ $slide['text'] }}</h1>
                                @endif
                                @if (! empty($slide['descriptions']))
                                    <p class="lead hero-description">{{ $slide['descriptions'] }}</p>
                                @endif
                                <div class="d-flex flex-wrap gap-2 mt-4">
                                    @if (($slide['button_action'] ?? 'inactive') === 'active' && ! empty($slide['button_text']) && ! empty($slide['button_link']))
                                        <a href="{{ $slide['button_link'] }}" class="btn btn-primary btn-lg hero-btn">{{ $slide['button_text'] }} <i class="bi bi-arrow-right"></i></a>
                                    @endif
                                    <a href="#kenal-situasi" class="btn btn-lg hero-btn hero-btn-ghost"><i class="bi bi-play-circle"></i> Lihat bedanya</a>
                                </div>
                                <ul class="hero-chips list-unstyled mb-0">
                                    <li><i class="bi bi-check-circle-fill"></i> Gratis 30 hari</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Bisa dipakai dari HP</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Satu akun untuk semua cabang</li>
                                </ul>
                            </div>
                            <div class="col-12 col-lg-6 hero-visual">
                                <div class="hero-parallax" data-hero-parallax>
                                    @include('frontend.partials.hero-flow', ['uid' => 'hero-flow-'.$index])
                                </div>
                                {{-- Kartu "momen lega" melayang: contoh kabar yang diterima pemilik kursus (data contoh, bukan data asli). --}}
                                <div class="hero-moment hero-moment--paid" aria-hidden="true">
                                    <span class="hero-moment-icon"><i class="bi bi-check2-circle"></i></span>
                                    <span><strong>SPP Nadia sudah lunas</strong><small>Dibayar lewat QRIS · baru saja</small></span>
                                </div>
                                <div class="hero-moment hero-moment--reminder" aria-hidden="true">
                                    <span class="hero-moment-icon"><i class="bi bi-whatsapp"></i></span>
                                    <span><strong>Pengingat les terkirim</strong><small>Ke semua orang tua kelas besok</small></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

@push('scripts')
    <script src="{{ asset('js/produk.js') }}?v={{ filemtime(public_path('js/produk.js')) }}" defer></script>
    <script>
        // Ilustrasi bergeser sedikit mengikuti mouse (desktop saja, dimatikan bila "kurangi gerakan").
        (function () {
            var hero = document.getElementById('heroCarousel');
            if (!hero || !window.matchMedia('(hover: hover) and (prefers-reduced-motion: no-preference)').matches) return;
            hero.addEventListener('mousemove', function (e) {
                var r = hero.getBoundingClientRect(), x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
                hero.querySelectorAll('[data-hero-parallax]').forEach(function (el) { el.style.transform = 'translate(' + (x * -18) + 'px,' + (y * -12) + 'px)'; });
            });
            hero.addEventListener('mouseleave', function () {
                hero.querySelectorAll('[data-hero-parallax]').forEach(function (el) { el.style.transform = ''; });
            });
        })();
    </script>
@endpush
