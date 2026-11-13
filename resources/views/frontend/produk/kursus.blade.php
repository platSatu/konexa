@extends('layouts.frontend')

{{--
    Halaman produk Management Kursus (/kursus), disusun AIDA seperti halaman Tiket. Isi mengikuti fitur
    yang ada di app.bizbos: Jadwal (murid, pengajar, sesi, absensi, honor), portal pendaftaran & portal
    orang tua/pengajar, Invoice, WhatsApp, Pages (kuis, form + pembayaran), Saldo & tarik saldo.
--}}
@php
    $registerUrl = 'https://app.bizbos.id/auth/register';

    $courseTypes = [
        ['music-note-beamed', 'Kursus Musik', 'lilac'],
        ['book', 'Bimbel & Les Privat', 'sky'],
        ['translate', 'Les Bahasa Inggris', 'mint'],
        ['code-slash', 'Coding & Robotik', 'sun'],
        ['water', 'Renang & Olahraga', 'sky'],
        ['palette', 'Seni & Menggambar', 'peach'],
        ['mic', 'Vokal & Tari', 'lilac'],
        ['grid', 'Kursus Lainnya', 'mint'],
    ];

    $flow = [
        ['ui-checks', 'Form pendaftaran', 'Bagikan link daftar online. Calon murid atau orang tua mengisi data sendiri, Anda tinggal menyetujui.', 'peach'],
        ['patch-question', 'Placement test', 'Calon murid ikut tes penempatan online, bisa gratis atau berbayar. Skornya langsung keluar, kelas yang pas pun mudah dipilih.', 'sun'],
        ['person-check', 'Jadi murid', 'Murid masuk ke kelas bulanan atau paket pertemuan. Jadwal pengajar dan ruangan dicek otomatis supaya tidak bentrok.', 'mint'],
        ['qr-code-scan', 'Belajar & absen scan QR', 'Pengajar absen dengan scan QR di tempat les, lalu scan kartu QR murid dari HP. Jam hadir keduanya tercatat, dan pengingat les terkirim lewat WhatsApp sehari sebelumnya.', 'sky'],
        ['receipt', 'Tagihan & bayar', 'Tagihan SPP terbit otomatis dari jadwal, lengkap dengan link bayar online. Pengingat jatuh tempo dan bukti lunas terkirim lewat WhatsApp.', 'lilac'],
        ['house-heart', 'Pantau bersama', 'Orang tua melihat jadwal, kehadiran, dan tagihan anak dari portalnya sendiri.', 'peach'],
    ];

    $highlights = [
        ['patch-question', 'Kuis & tes online', 'Buat tes penempatan, kuis latihan, atau ujian akhir lewat link. Jawaban dan skor tercatat rapi untuk setiap peserta.', 'lilac'],
        ['credit-card-2-front', 'Pembayaran online', 'Murid membayar lewat Virtual Account, e-wallet, atau QRIS. Status lunas tercatat sendiri, tanpa cek mutasi manual.', 'sun'],
        ['bank', 'Tarik saldo ke rekening', 'Uang yang masuk terkumpul di Saldo dan bisa ditarik ke rekening atas nama Anda kapan pun dibutuhkan.', 'mint'],
    ];

    $notifications = [
        ['bell', 'Notifikasi pengajar & murid', 'Kabari pengajar dan murid lewat WhatsApp dari satu tempat.'],
        ['calendar2-week', 'Pesan terjadwal', 'Siapkan pengingat atau ucapan, kirim otomatis di jam yang Anda tentukan.'],
        ['megaphone', 'Broadcast', 'Umumkan libur, kelas baru, atau promo ke banyak orang tua sekaligus.'],
        ['robot', 'Auto reply', 'Pertanyaan calon murid tetap terjawab di luar jam kerja.'],
    ];

    $benefits = [
        ['folder-check', 'Data tidak lagi tercecer', 'Murid, jadwal, absensi, dan tagihan tersimpan di satu tempat, mudah dicari kapan saja.', 'sky'],
        ['person-workspace', 'Kehadiran & honor lebih tertib', 'Jam hadir pengajar dan murid tercatat dari scan QR, bagian honor pengajar pun terhitung otomatis. Pembagian hasil jadi lebih rapi dan transparan.', 'mint'],
        ['emoji-smile', 'Orang tua lebih tenang', 'Orang tua dapat memantau jadwal dan kehadiran anak, serta menerima kabar penting tepat waktu.', 'peach'],
    ];

    $steps = [
        ['Buat kelas', 'Isi mata pelajaran, paket harga per bulan, dan jumlah pertemuan.'],
        ['Bagikan link daftar', 'Kirim link pendaftaran murid dan pengajar, lalu setujui pendaftarnya.'],
        ['Atur jadwal', 'Pasang jadwal rutin, pengajar, dan ruangan. Sesi dibuat otomatis.'],
        ['Tagih & pantau', 'Kirim tagihan bulanan, pantau absensi, dan honor pengajar terhitung sendiri.'],
    ];
@endphp

@section('title_full', 'Aplikasi Kursus: Kelola Murid, Absensi & SPP | '.config('app.name'))
@section('meta_description', 'Aplikasi kursus musik, bimbel, dan les bahasa: pendaftaran online, placement test, absensi scan QR, tagihan SPP, sampai pengingat WhatsApp ke orang tua.')
@section('meta_image', asset('images/produk/tiket-dashboard.webp'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/produk.css') }}?v={{ filemtime(public_path('css/produk.css')) }}">
@endpush

@section('content')
    @include('frontend.partials.topbar')

    {{-- ATTENTION --}}
    <header class="pd-hero">
        <div class="container position-relative">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <span class="pd-eyebrow"><i class="bi bi-mortarboard"></i> bizbos Kursus</span>
                    <h1 class="pd-hero-title">Halo, <span class="pd-script">Pemilik Kursus!</span></h1>
                    <p class="pd-hero-ask">Punya tempat <span class="pd-rotate" data-words="kursus musik?|bimbel?|les bahasa Inggris?|les privat?|kursus coding?|kursus renang?">kursus musik?</span></p>
                    <p class="pd-hero-lead">Aplikasi kursus yang mengurus pendaftaran murid, jadwal dan absensi, tagihan bulanan (SPP), sampai honor pengajar. Semuanya berjalan otomatis, orang tua pun bisa ikut memantau.</p>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ $registerUrl }}" class="btn btn-primary btn-lg pd-btn">Mulai Kelola Kursus <i class="bi bi-arrow-right"></i></a>
                        <a href="#alur" class="btn btn-lg pd-btn pd-btn-ghost"><i class="bi bi-signpost-split"></i> Lihat Alurnya</a>
                    </div>
                    <ul class="pd-chips list-unstyled mb-0">
                        <li><i class="bi bi-check-circle-fill"></i> Pendaftaran online</li>
                        <li><i class="bi bi-check-circle-fill"></i> Absensi scan QR</li>
                        <li><i class="bi bi-check-circle-fill"></i> Portal orang tua</li>
                        <li><i class="bi bi-check-circle-fill"></i> Pengingat WhatsApp</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="pd-hero-visual">
                        <figure class="pd-shot pd-shot--back">
                            <img src="{{ asset('images/produk/tiket-dashboard.webp') }}" alt="Dashboard bizbos dengan jadwal hari ini" width="1600" height="805">
                        </figure>
                        <div class="pd-float pd-float--sold"><i class="bi bi-person-plus-fill"></i><span><strong>Pendaftar baru</strong><small>Menunggu persetujuan Anda</small></span></div>
                        <div class="pd-float pd-float--scan"><i class="bi bi-whatsapp"></i><span><strong>Pengingat terkirim</strong><small>Kelas besok jam 16.00</small></span></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- INTEREST: jenis kursus --}}
    <section class="pd-section" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center">Aplikasi kursus untuk<br>semua jenis tempat les</h2>
            <div class="row g-3 justify-content-center mt-2">
                @foreach ($courseTypes as [$icon, $label, $tone])
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="pd-type pd-tone--{{ $tone }}"><i class="bi bi-{{ $icon }}"></i><span>{{ $label }}</span></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- INTEREST: alur dari daftar sampai bayar --}}
    <section class="pd-section pd-section--soft" id="alur" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center">Satu alur, dari daftar sampai bayar</h2>
            <p class="pd-lead text-center mx-auto mb-5" style="max-width: 40rem;">Tidak perlu lagi mencatat di buku, mengecek transferan satu per satu, atau mengingatkan orang tua lewat chat pribadi.</p>
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

    {{-- INTEREST: tiga peran --}}
    <section class="pd-section" data-reveal>
        <div class="container">
            <h2 class="pd-title">Setiap orang punya<br class="d-none d-md-inline"> halamannya sendiri</h2>
            <div class="row g-4 mt-1">
                <div class="col-lg-6">
                    <article class="pd-bento pd-tone--peach">
                        <div class="pd-bento-text">
                            <span class="pd-role-tag">Pemilik kursus</span>
                            <h3>Dashboard yang langsung menunjukkan yang perlu ditangani</h3>
                            <p>Pemasukan bulan ini, sesi yang belum diabsen, tagihan yang hampir jatuh tempo, dan jadwal hari ini dalam satu layar.</p>
                        </div>
                        <img src="{{ asset('images/produk/tiket-dashboard.webp') }}" alt="Dashboard pemilik kursus" class="pd-bento-img" loading="lazy" width="1600" height="805">
                    </article>
                </div>
                <div class="col-lg-6 d-flex flex-column gap-4">
                    <article class="pd-role pd-tone--lilac">
                        <div>
                            <span class="pd-role-tag">Orang tua & murid</span>
                            <h3>Portal untuk memantau anak</h3>
                            <p>Orang tua masuk dengan akunnya sendiri untuk melihat jadwal les dan riwayat kehadiran anak.</p>
                        </div>
                        <ul class="pd-mock list-unstyled" aria-hidden="true">
                            <li><span>Sab · 10.00</span><b>Kelas Piano A</b><em class="is-ok">Hadir</em></li>
                            <li><span>Sab · 10.00</span><b>Kelas Piano A</b><em class="is-warn">Izin</em></li>
                            <li><span>Sab · 10.00</span><b>Kelas Piano A</b><em>Terjadwal</em></li>
                        </ul>
                    </article>
                    <article class="pd-role pd-tone--mint">
                        <div>
                            <span class="pd-role-tag">Pengajar</span>
                            <h3>Absen scan QR dari HP, honor dihitung sendiri</h3>
                            <p>Sampai di tempat les, pengajar scan QR untuk absen, lalu scan kartu QR murid satu per satu. Setiap kartu hanya terhitung sekali per sesi, dan bagian honornya ikut tercatat dari absensi tersebut.</p>
                        </div>
                        <ul class="pd-mock list-unstyled" aria-hidden="true">
                            <li><span>16.00</span><b>Matematika SD</b><em class="is-ok">Pengajar hadir 15.52</em></li>
                            <li><span>16.00</span><b>Scan kartu murid</b><em class="is-ok">3 dari 4</em></li>
                            <li><span>18.00</span><b>English Kids</b><em class="is-warn">Belum absen</em></li>
                        </ul>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- INTEREST: sorotan kuis, pembayaran, tarik saldo --}}
    <section class="pd-section pd-section--soft" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center">Lebih dari sekadar jadwal</h2>
            <p class="pd-lead text-center mx-auto" style="max-width: 38rem;">Buat kuis, terima pembayaran, dan cairkan hasilnya tanpa pindah aplikasi.</p>
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
                    <span class="pd-card-icon pd-notify-badge"><i class="bi bi-whatsapp"></i></span>
                    <div>
                        <h3 class="mb-1">Notifikasi WhatsApp yang bekerja sendiri</h3>
                        <p class="mb-0">Orang tua, murid, dan pengajar selalu tahu kabar terbaru tanpa Anda kirim satu per satu.</p>
                    </div>
                </div>
                <div class="row g-3 mt-1">
                    @foreach ($notifications as [$icon, $title, $text])
                        <div class="col-sm-6 col-lg-3">
                            <div class="pd-notify-item"><i class="bi bi-{{ $icon }}"></i><strong>{{ $title }}</strong><small>{{ $text }}</small></div>
                        </div>
                    @endforeach
                </div>
            </div>
            <p class="small text-muted text-center mt-4 mb-0">Semua fitur di halaman ini tersedia di Paket Komplit (Pages, Jadwal, Invoice, WhatsApp).</p>
        </div>
    </section>

    {{-- DESIRE: kenapa pemilik kursus memilih bizbos --}}
    <section class="pd-section" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center">Kursus lebih tertib, semua pihak lebih tenang</h2>
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
    @include('frontend.produk._steps', ['title' => 'Mulai kelola kursus dalam 4 langkah', 'steps' => $steps])
    @include('frontend.produk._cta', ['before' => 'Siap bikin kursus Anda lebih', 'highlight' => 'rapi', 'after' => '?', 'subtitle' => 'Daftar gratis, buat kelas pertama, dan bagikan link pendaftarannya hari ini.', 'button' => 'Mulai Kelola Kursus'])

    @include('frontend.partials.footer')
@endsection

@push('scripts')
    <script src="{{ asset('js/produk.js') }}?v={{ filemtime(public_path('js/produk.js')) }}" defer></script>
@endpush
