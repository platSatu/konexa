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
        ['translate', 'Bahasa Inggris, Mandarin, Korea, Jepang', 'mint'],
        ['code-slash', 'Coding & Robotik', 'sun'],
        ['water', 'Renang & Olahraga', 'sky'],
        ['palette', 'Seni & Menggambar', 'peach'],
        ['mic', 'Vokal & Tari', 'lilac'],
        ['grid', 'Kursus Lainnya', 'mint'],
    ];

    $flow = [
        ['ui-checks', 'Form pendaftaran', 'Bagikan link daftar online. Calon murid atau orang tua mengisi data sendiri, Anda tinggal menyetujui.', 'peach'],
        ['patch-question', 'Placement test', 'Kirim kode tes ke calon murid yang Anda undang. Hanya pemegang kode yang bisa membuka soalnya, dan nilainya keluar begitu selesai — kelas yang pas pun mudah ditentukan.', 'sun'],
        ['person-check', 'Jadi murid', 'Murid masuk ke kelas bulanan atau paket pertemuan. Jadwal pengajar dan ruangan dicek otomatis supaya tidak bentrok.', 'mint'],
        ['qr-code-scan', 'Belajar & absen scan QR', 'Pengajar absen dengan scan QR di tempat les, lalu scan kartu QR murid dari HP. Jam hadir keduanya tercatat, dan pengingat les terkirim lewat WhatsApp sehari sebelumnya.', 'sky'],
        ['receipt', 'Tagihan & bayar', 'Tagihan SPP terbit otomatis dari jadwal, lengkap dengan link bayar online. Pengingat jatuh tempo dan bukti lunas terkirim lewat WhatsApp.', 'lilac'],
        ['house-heart', 'Pantau bersama', 'Orang tua melihat jadwal, kehadiran, dan tagihan anak dari portalnya sendiri.', 'peach'],
    ];

    $highlights = [
        ['patch-question', 'Placement test & kuis online', 'Tes penempatan, kuis latihan, atau ujian akhir lewat satu link. Dikunci kode supaya hanya murid undangan yang bisa masuk, dan jawaban setiap peserta tercatat rapi.', 'lilac'],
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

    // Data terstruktur Schema.org. Google memakainya untuk mengenali halaman ini sebagai aplikasi
    // manajemen kursus, bukan sekadar artikel. Isinya WAJIB sama dengan yang benar-benar tampil
    // di halaman -- menulis fitur yang tidak ada di sini bisa membuat halaman kena penalti.
    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Aplikasi Kursus', 'item' => url()->current()],
                ],
            ],
            [
                '@type' => 'SoftwareApplication',
                'name' => config('app.name').' Kursus',
                'applicationCategory' => 'BusinessApplication',
                'operatingSystem' => 'Web',
                'url' => url()->current(),
                'inLanguage' => 'id-ID',
                'description' => 'Aplikasi manajemen kursus dan bimbel: pendaftaran murid online, placement test berkode undangan, absensi scan QR, tagihan SPP otomatis, honor pengajar, dan pengingat WhatsApp ke orang tua.',
                'featureList' => [
                    'Pendaftaran murid online',
                    'Placement test khusus murid undangan',
                    'Jadwal kelas dengan cek bentrok pengajar dan ruangan',
                    'Absensi scan QR untuk pengajar dan murid',
                    'Tagihan SPP otomatis dengan pembayaran online',
                    'Perhitungan honor pengajar',
                    'Portal orang tua dan pengajar',
                    'Pengingat dan notifikasi WhatsApp',
                ],
            ],
        ],
    ];
@endphp

{{-- Jangan pakai "&" di @section(...): Laravel meng-escape isinya, lalu layout
     meng-escape lagi, sehingga tampil "&amp;" di <title>. Tulis "dan" atau pakai koma. --}}
@section('title_full', 'Aplikasi Kursus: Placement Test, Absensi, SPP | '.config('app.name'))
@section('meta_description', 'Aplikasi kursus musik, bimbel, dan les bahasa: pendaftaran online, placement test berkode undangan, absensi scan QR, tagihan SPP, dan pengingat WhatsApp ke orang tua.')
{{-- meta_image sengaja tidak diisi: memakai gambar share bawaan dari Pengaturan Web (gambar dashboard tiket tidak cocok untuk halaman kursus). --}}

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
                    <p class="pd-hero-ask">Punya tempat <span class="pd-rotate" data-words="kursus bahasa Inggris?|kursus Mandarin?|kursus bahasa Korea?|kursus bahasa Jepang?|bimbel?|kursus musik?|kursus coding?">kursus bahasa Inggris?</span></p>
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
                            <div class="kd-mock" role="img" aria-label="Contoh dashboard kursus di bizbos: jadwal hari ini, tagihan, dan absensi">
                                <div class="kd-bar"><span></span><span></span><span></span><b>Dashboard Kursus</b></div>
                                <div class="kd-body">
                                    <div class="kd-stats">
                                        <div class="kd-stat kd-stat--mint"><small>Murid aktif</small><strong>128</strong></div>
                                        <div class="kd-stat kd-stat--sun"><small>SPP bulan ini</small><strong>86% lunas</strong></div>
                                        <div class="kd-stat kd-stat--peach"><small>Kelas hari ini</small><strong>9 sesi</strong></div>
                                    </div>
                                    <div class="kd-list">
                                        <div class="kd-list-title">Jadwal hari ini</div>
                                        <div class="kd-row"><span class="kd-time">15.00</span><span class="kd-name">English for Kids · Kelas A</span><em class="kd-ok">Sudah absen</em></div>
                                        <div class="kd-row"><span class="kd-time">16.30</span><span class="kd-name">Mandarin Dasar · Kelas B</span><em class="kd-ok">Sudah absen</em></div>
                                        <div class="kd-row"><span class="kd-time">18.00</span><span class="kd-name">Bahasa Korea · Pemula</span><em class="kd-warn">Belum mulai</em></div>
                                        <div class="kd-row"><span class="kd-time">19.00</span><span class="kd-name">Bahasa Jepang · N5</span><em>Terjadwal</em></div>
                                    </div>
                                </div>
                            </div>
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

    {{-- INTEREST: placement test khusus murid undangan (memperdalam langkah 2 di alur atas) --}}
    <section class="pd-section" id="placement-test" aria-labelledby="placement-test-title" data-reveal>
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <h2 class="pd-title" id="placement-test-title">Placement test khusus<br class="d-none d-md-inline"> murid yang Anda undang</h2>
                    <p class="pd-lead">Anda yang menentukan siapa boleh ikut. Berikan kode tes kepada calon murid, lalu hanya pemegang kode itu yang bisa membuka soalnya — link yang tersebar ke orang lain tidak akan bisa dipakai.</p>
                    <p class="pd-lead">Begitu selesai, nilainya langsung keluar mengikuti batas yang Anda tetapkan sendiri, misalnya Pemula, Menengah, atau Lanjutan. Anda tinggal menempatkan murid di kelas yang pas, tanpa perlu memeriksa jawaban satu per satu.</p>
                    <ul class="pd-chips list-unstyled mb-0">
                        <li><i class="bi bi-check-circle-fill"></i> Kode dari admin</li>
                        <li><i class="bi bi-check-circle-fill"></i> Nilai otomatis</li>
                        <li><i class="bi bi-check-circle-fill"></i> Hasil ke WhatsApp</li>
                        <li><i class="bi bi-check-circle-fill"></i> Gratis atau berbayar</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <article class="pd-role pd-tone--sun">
                        <div>
                            <span class="pd-role-tag">Yang dialami murid</span>
                            <h3>Masukkan kode, kerjakan, terima hasilnya</h3>
                            <p>Tanpa perlu membuat akun. Cukup buka link, masukkan kode dari Anda, lalu kerjakan.</p>
                        </div>
                        <ul class="pd-mock list-unstyled" aria-hidden="true">
                            <li><span>1</span><b>Masukkan kode tes</b><em>dari admin</em></li>
                            <li><span>2</span><b>Kerjakan soalnya</b><em class="is-warn">20 menit</em></li>
                            <li><span>3</span><b>Nilai: Lanjutan</b><em class="is-ok">Terkirim ke WhatsApp</em></li>
                        </ul>
                    </article>
                </div>
            </div>
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
                            <button type="button" class="vc-btn" data-cuplikan="jadwal"><i class="bi bi-play-circle-fill"></i> Lihat jadwalnya <small>10 detik</small></button>
                        </div>
                        <ul class="pd-mock list-unstyled mt-3" aria-hidden="true">
                            <li><span>Hari ini</span><b>Pemasukan bulan ini</b><em class="is-ok">Rp 18,4 jt</em></li>
                            <li><span>3 sesi</span><b>Belum diabsen</b><em class="is-warn">Cek sekarang</em></li>
                            <li><span>5 murid</span><b>Tagihan jatuh tempo minggu ini</b><em>Pengingat aktif</em></li>
                        </ul>
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
                            <button type="button" class="vc-btn" data-cuplikan="absensi"><i class="bi bi-play-circle-fill"></i> Lihat cara absen <small>10 detik</small></button>
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
    @include('frontend.partials.video-cuplikan')
@endsection

@push('scripts')
    {{-- JSON-LD dibiarkan di akhir halaman: Google tetap membacanya di mana pun letaknya,
         dan di sini ia tidak menunda tampilnya halaman. --}}
    <script type="application/ld+json">@json($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)</script>
    <script src="{{ asset('js/produk.js') }}?v={{ filemtime(public_path('js/produk.js')) }}" defer></script>
@endpush
