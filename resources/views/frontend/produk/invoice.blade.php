@extends('layouts.frontend')

{{--
    Halaman produk Management Invoice (/invoice), disusun AIDA seperti halaman Tiket & Kursus. Isi mengikuti
    fitur Invoice di app.bizbos: invoice satuan & periode, kategori langganan, link bayar publik, dashboard
    Tagihan Saya untuk pembayar, pengingat & denda keterlambatan, Saldo, kirim dana, API & webhook.
--}}
@php
    $registerUrl = 'https://app.bizbos.id/auth/register';

    $businessTypes = [
        ['mortarboard', 'Sekolah & Kursus', 'sun'],
        ['house-door', 'Kos & Properti', 'peach'],
        ['people', 'Komunitas & Iuran', 'mint'],
        ['briefcase', 'Jasa & Agensi', 'sky'],
        ['wifi', 'Internet & Langganan', 'lilac'],
        ['heart-pulse', 'Klinik & Layanan', 'peach'],
        ['bank2', 'Koperasi', 'mint'],
        ['laptop', 'Freelancer', 'sun'],
    ];

    $flow = [
        ['file-earmark-plus', 'Buat tagihan', 'Satu invoice, atau satu periode untuk banyak pelanggan sekaligus.', 'peach'],
        ['send', 'Kirim link bayar', 'Setiap tagihan punya link bayar sendiri yang bisa dibagikan.', 'sun'],
        ['credit-card', 'Pelanggan bayar', 'Lewat Virtual Account, e-wallet, atau QRIS, tanpa perlu login.', 'mint'],
        ['check2-circle', 'Lunas otomatis', 'Status berubah sendiri dan uang masuk ke Saldo Anda.', 'sky'],
        ['bank', 'Cairkan', 'Tarik ke rekening atau kirim ke banyak rekening sekaligus.', 'lilac'],
    ];

    $highlights = [
        ['bell', 'Pengingat otomatis', 'Pelanggan diingatkan sebelum dan sesudah jatuh tempo. Anda tidak perlu menagih satu per satu.', 'sun'],
        ['hourglass-split', 'Denda keterlambatan', 'Atur denda nominal tetap atau persen. Besarnya dihitung sendiri saat pelanggan membayar.', 'peach'],
        ['arrow-repeat', 'Tagihan rutin', 'Kelompokkan pelanggan per kategori, lalu buat tagihan bulanan untuk semuanya dalam sekali klik.', 'mint'],
    ];

    $extras = [
        ['send-check', 'Kirim dana ke banyak rekening', 'Bayar vendor atau mitra langsung dari Saldo. Nama pemilik rekening dicek ke bank.'],
        ['code-slash', 'API & webhook', 'Buat invoice dari website atau aplikasi Anda sendiri, lengkap dengan dokumentasinya.'],
        ['wallet2', 'Tarik saldo', 'Uang tagihan terkumpul di Saldo dan bisa ditarik ke rekening atas nama Anda.'],
        ['ui-checks', 'Dari form pendaftaran', 'Tagihan juga bisa dibuat otomatis dari form yang diisi pelanggan.'],
    ];

    $benefits = [
        ['clock-history', 'Hemat waktu menagih', 'Tidak perlu lagi mengirim pesan tagihan dan mengecek transfer satu per satu.', 'sky'],
        ['shield-check', 'Tercatat rapi', 'Setiap pembayaran tercatat lengkap, mudah dicocokkan saat tutup buku.', 'mint'],
        ['emoji-smile', 'Pelanggan lebih nyaman', 'Pelanggan memilih cara bayar yang paling mudah baginya, dan statusnya langsung berubah lunas.', 'peach'],
    ];

    $steps = [
        ['Buat kategori', 'Misalnya "Iuran Bulanan", lengkap dengan pengingat dan aturan denda.'],
        ['Tambah pelanggan', 'Masukkan pelanggan yang ditagih rutin ke kategori tersebut.'],
        ['Buat periode', 'Buat tagihan bulan ini, satu invoice per pelanggan terbuat sekaligus.'],
        ['Pantau & cairkan', 'Lihat siapa yang sudah lunas, lalu tarik hasilnya ke rekening.'],
    ];
@endphp

@section('title', 'Management Invoice')
@section('meta_description', 'Kirim tagihan dengan link bayar online lewat bizbos: invoice satuan atau bulanan, pengingat dan denda otomatis, dashboard tagihan untuk pelanggan, sampai tarik saldo.')
@section('meta_image', asset('images/produk/tiket-dashboard.webp'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/produk.css') }}?v={{ filemtime(public_path('css/produk.css')) }}">
@endpush

@section('content')
    @include('frontend.partials.topbar')

    {{-- ATTENTION --}}
    <header class="pd-hero">
        <span class="pd-burst pd-burst--left" aria-hidden="true"></span>
        <span class="pd-burst pd-burst--right" aria-hidden="true"></span>
        <div class="container position-relative">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <span class="pd-eyebrow"><i class="bi bi-receipt"></i> bizbos Invoice</span>
                    <h1 class="pd-hero-title">Capek <span class="pd-script">nagih</span> satu per satu?</h1>
                    <p class="pd-hero-ask">Biar bizbos yang urus <span class="pd-rotate" data-words="iuran bulanan.|SPP sekolah.|sewa kos.|tagihan jasa.|langganan.">iuran bulanan.</span></p>
                    <p class="pd-hero-lead">Kirim tagihan dengan link bayar online, ingatkan pelanggan secara otomatis, dan lihat siapa yang sudah lunas tanpa mengecek mutasi rekening.</p>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ $registerUrl }}" class="btn btn-primary btn-lg pd-btn">Mulai Kirim Tagihan <i class="bi bi-arrow-right"></i></a>
                        <a href="#alur" class="btn btn-lg pd-btn pd-btn-ghost"><i class="bi bi-signpost-split"></i> Lihat Alurnya</a>
                    </div>
                    <ul class="pd-chips list-unstyled mb-0">
                        <li><i class="bi bi-check-circle-fill"></i> Link bayar online</li>
                        <li><i class="bi bi-check-circle-fill"></i> Pengingat otomatis</li>
                        <li><i class="bi bi-check-circle-fill"></i> Lunas tercatat sendiri</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="pd-hero-visual">
                        <figure class="pd-shot pd-shot--back">
                            <img src="{{ asset('images/produk/tiket-dashboard.webp') }}" alt="Dashboard bizbos dengan ringkasan invoice" width="1600" height="805">
                        </figure>
                        <div class="pd-float pd-float--sold"><i class="bi bi-check-circle-fill"></i><span><strong>Invoice lunas</strong><small>Dibayar lewat QRIS</small></span></div>
                        <div class="pd-float pd-float--scan"><i class="bi bi-bell-fill"></i><span><strong>Pengingat terkirim</strong><small>Jatuh tempo 3 hari lagi</small></span></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- INTEREST: jenis usaha --}}
    <section class="pd-section" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center">Untuk usaha apa pun<br>yang rutin menagih</h2>
            <div class="row g-3 justify-content-center mt-2">
                @foreach ($businessTypes as [$icon, $label, $tone])
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="pd-type pd-tone--{{ $tone }}"><i class="bi bi-{{ $icon }}"></i><span>{{ $label }}</span></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- INTEREST: alur --}}
    <section class="pd-section pd-section--soft" id="alur" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center">Dari tagihan sampai uang di rekening</h2>
            <p class="pd-lead text-center mx-auto mb-5" style="max-width: 40rem;">Satu alur yang berjalan sendiri. Anda cukup membuat tagihannya.</p>
            <ol class="pd-flow list-unstyled">
                @foreach ($flow as [$icon, $title, $text, $tone])
                    <li class="pd-flow-item">
                        <span class="pd-flow-icon pd-tone--{{ $tone }}"><i class="bi bi-{{ $icon }}"></i></span>
                        <span class="pd-flow-num">{{ $loop->iteration }}</span>
                        <h3>{{ $title }}</h3>
                        <p>{{ $text }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- INTEREST: dashboard Anda & dashboard pelanggan --}}
    <section class="pd-section" data-reveal>
        <div class="container">
            <h2 class="pd-title">Dua dashboard,<br class="d-none d-md-inline"> satu sumber data</h2>
            <div class="row g-4 mt-1">
                <div class="col-lg-6">
                    <article class="pd-bento pd-tone--peach">
                        <div class="pd-bento-text">
                            <span class="pd-role-tag">Untuk Anda</span>
                            <h3>Dashboard invoice</h3>
                            <p>Lihat tagihan yang belum dibayar, yang hampir jatuh tempo, dan pemasukan bulan ini. Kirim ulang atau batalkan tagihan dengan sekali klik.</p>
                        </div>
                        <img src="{{ asset('images/produk/tiket-dashboard.webp') }}" alt="Dashboard pemilik usaha" class="pd-bento-img" loading="lazy" width="1600" height="805">
                    </article>
                </div>
                <div class="col-lg-6 d-flex flex-column gap-4">
                    <article class="pd-role pd-tone--sky">
                        <div>
                            <span class="pd-role-tag">Untuk pelanggan</span>
                            <h3>Halaman Tagihan Saya</h3>
                            <p>Pelanggan melihat semua tagihan untuk emailnya di satu halaman, lalu membayar beberapa tagihan sekaligus.</p>
                        </div>
                        <ul class="pd-mock list-unstyled" aria-hidden="true">
                            <li><span>Jatuh tempo 10 Nov</span><b>Iuran November</b><em class="is-warn">Belum bayar</em></li>
                            <li><span>Jatuh tempo 15 Nov</span><b>Sewa Kamar 3</b><em class="is-warn">Belum bayar</em></li>
                            <li><span>10 Okt</span><b>Iuran Oktober</b><em class="is-ok">Lunas</em></li>
                        </ul>
                    </article>
                    <article class="pd-role pd-tone--mint">
                        <div>
                            <span class="pd-role-tag">Link bayar</span>
                            <h3>Bayar tanpa perlu daftar</h3>
                            <p>Setiap invoice punya link bayar sendiri. Pelanggan cukup membuka link, memilih metode bayar, dan selesai.</p>
                        </div>
                        <ul class="pd-mock list-unstyled" aria-hidden="true">
                            <li><span>Pilih metode bayar</span><b>QRIS</b><em class="is-ok">Tersedia</em></li>
                            <li><span>Pilih metode bayar</span><b>Virtual Account</b><em class="is-ok">Tersedia</em></li>
                        </ul>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- INTEREST: sorotan --}}
    <section class="pd-section pd-section--soft" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center">Menagih jadi lebih ringan</h2>
            <div class="row g-4 mt-2">
                @foreach ($highlights as [$icon, $title, $text, $tone])
                    <div class="col-lg-4">
                        <article class="pd-highlight pd-tone--{{ $tone }}">
                            <span class="pd-highlight-icon"><i class="bi bi-{{ $icon }}"></i></span>
                            <h3>{{ $title }}</h3>
                            <p>{{ $text }}</p>
                        </article>
                    </div>
                @endforeach
            </div>

            <div class="pd-notify mt-5">
                <div class="pd-notify-head">
                    <span class="pd-card-icon pd-notify-badge"><i class="bi bi-stars"></i></span>
                    <div>
                        <h3 class="mb-1">Lebih dari sekadar invoice</h3>
                        <p class="mb-0">Uang yang masuk bisa langsung Anda kelola dari tempat yang sama.</p>
                    </div>
                </div>
                <div class="row g-3 mt-1">
                    @foreach ($extras as [$icon, $title, $text])
                        <div class="col-sm-6 col-lg-3">
                            <div class="pd-notify-item"><i class="bi bi-{{ $icon }}"></i><strong>{{ $title }}</strong><small>{{ $text }}</small></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- DESIRE --}}
    <section class="pd-section" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center">Waktu Anda kembali untuk mengurus usaha</h2>
            <div class="row g-4 mt-2">
                @foreach ($benefits as [$icon, $title, $text, $tone])
                    <div class="col-md-4">
                        <article class="pd-card pd-tone--{{ $tone }}">
                            <span class="pd-card-icon"><i class="bi bi-{{ $icon }}"></i></span>
                            <h3>{{ $title }}</h3>
                            <p>{{ $text }}</p>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ACTION --}}
    @include('frontend.produk._steps', ['title' => 'Mulai menagih dalam 4 langkah', 'steps' => $steps])
    @include('frontend.produk._cta', ['before' => 'Siap menagih tanpa', 'highlight' => 'repot', 'after' => '?', 'subtitle' => 'Daftar gratis, buat tagihan pertama, dan kirim link bayarnya hari ini.', 'button' => 'Mulai Kirim Tagihan'])

    @include('frontend.partials.footer')
@endsection

@push('scripts')
    <script src="{{ asset('js/produk.js') }}?v={{ filemtime(public_path('js/produk.js')) }}" defer></script>
@endpush
