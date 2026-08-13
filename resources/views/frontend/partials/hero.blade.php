{{--
    Hero/header homepage — diambil dari API backend Teleios (lihat
    App\Services\TeleiosApiService::getHeaders(), sumber:
    App\Models\WebHeader / menu Superadmin > Web > Headers di teleios).
    Tiap slide bisa background gambar atau video (background_type),
    dengan headline (text) + deskripsi (descriptions) + tombol CTA
    opsional (button_action = 'active').

    Kalau belum ada slide aktif yang diatur di teleios, $headers kosong
    dan tampilan jatuh ke hero statis di bawah supaya homepage tidak
    terlihat kosong.

    Layout konten (headline + deskripsi + tombol) SENGAJA rata kiri
    (bukan center) — dibungkus .col-lg-6 supaya di layar besar cuma
    mengisi setengah lebar, background tetap fullsize di belakangnya.
    Deskripsi pakai class .hero-description (text-align: justify, lihat
    public/css/frontend.css) sesuai permintaan.

    Tipe Gambar juga punya "turunan" sendiri sekarang, sama seperti
    Video: thumbnail_background_images_url dirender sebagai placeholder
    blur-up (blur + scale via CSS) yang tampil di belakang gambar penuh
    selagi background_images_url dimuat — video pakai thumbnail_images_url
    sebagai poster, gambar pakai thumbnail_background_images_url sebagai
    blur-up, sama-sama "turunan" dari media utamanya.

    color_headline / color_description (hex, di-set per slide di
    Superadmin > Web > Headers) dipakai sebagai warna teks headline &
    deskripsi kalau diisi — kalau kosong, warna teks ikut default class
    text-white bawaan section ini.
--}}
@if (empty($headers))
    <section class="position-relative overflow-hidden text-white d-flex align-items-center hero-slide">
        <video autoplay muted loop playsinline class="position-absolute top-0 start-0 w-100 h-100"
            style="object-fit: cover; z-index: -2;">
            <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
        </video>
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark" style="opacity: 0.55; z-index: -1;"></div>

        <div class="container py-5">
            <div class="row">
                <div class="col-12 col-lg-6 hero-content">
                    <h1 class="display-4 fw-bold">Selamat Datang di Konexa</h1>
                    <p class="lead hero-description">Deskripsi singkat layanan atau produk Anda di sini.</p>
                    <a href="#about" class="btn btn-primary btn-lg mt-3">Selengkapnya</a>
                </div>
            </div>
        </div>
    </section>
@else
    <section id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        @if (count($headers) > 1)
            <div class="carousel-indicators">
                @foreach ($headers as $index => $header)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}"
                        class="{{ $index === 0 ? 'active' : '' }}"
                        @if ($index === 0) aria-current="true" @endif
                        aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif

        <div class="carousel-inner">
            @foreach ($headers as $index => $header)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="position-relative overflow-hidden text-white d-flex align-items-center hero-slide">
                        @if (($header['background_type'] ?? 'image') === 'video' && ! empty($header['videos_url']))
                            <video autoplay muted loop playsinline
                                @if (! empty($header['thumbnail_images_url'])) poster="{{ $header['thumbnail_images_url'] }}" @endif
                                class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover; z-index: -2;">
                                <source src="{{ $header['videos_url'] }}" type="video/mp4">
                            </video>
                        @elseif (! empty($header['background_images_url']))
                            @if (! empty($header['thumbnail_background_images_url']))
                                <img src="{{ $header['thumbnail_background_images_url'] }}" alt="" aria-hidden="true"
                                    class="position-absolute top-0 start-0 w-100 h-100"
                                    style="object-fit: cover; z-index: -3; filter: blur(20px); transform: scale(1.1);">
                            @endif
                            <img src="{{ $header['background_images_url'] }}" alt="{{ $header['text'] ?? '' }}"
                                class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover; z-index: -2;">
                        @endif
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark" style="opacity: 0.55; z-index: -1;"></div>

                        <div class="container py-5">
                            <div class="row">
                                <div class="col-12 col-lg-6 hero-content">
                                    @if (! empty($header['text']))
                                        <h1 class="display-4 fw-bold"
                                            @if (! empty($header['color_headline'])) style="color: {{ $header['color_headline'] }};" @endif>{{ $header['text'] }}</h1>
                                    @endif
                                    @if (! empty($header['descriptions']))
                                        <p class="lead hero-description"
                                            @if (! empty($header['color_description'])) style="color: {{ $header['color_description'] }};" @endif>{{ $header['descriptions'] }}</p>
                                    @endif
                                    @if (($header['button_action'] ?? 'inactive') === 'active' && ! empty($header['button_text']) && ! empty($header['button_link']))
                                        <a href="{{ $header['button_link'] }}" class="btn btn-primary btn-lg mt-3">{{ $header['button_text'] }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if (count($headers) > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Sebelumnya</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Berikutnya</span>
            </button>
        @endif
    </section>
@endif
