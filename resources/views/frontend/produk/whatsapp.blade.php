@extends('layouts.frontend')

{{--
    Halaman produk Management WhatsApp (/whatsapp), disusun AIDA seperti halaman produk lain. Isi mengikuti
    fitur WhatsApp di app.bizbos: banyak nomor (scan QR), inbox tim (label, catatan, bagi chat, quick reply,
    media, polling), kontak & grup, broadcast terjadwal, template, chatbot menu, auto reply, AI bot,
    Google Form (balas otomatis, QR registrasi ulang, link bayar), API & dokumentasi.
--}}
@php
    $registerUrl = 'https://app.bizbos.id/auth/register';

    $useCases = [
        ['bag-heart', 'Toko Online', 'peach'],
        ['mortarboard', 'Kursus & Sekolah', 'sun'],
        ['heart-pulse', 'Klinik & Kecantikan', 'lilac'],
        ['house-door', 'Properti', 'sky'],
        ['airplane', 'Travel & Umrah', 'mint'],
        ['ticket-perforated', 'Event', 'peach'],
        ['tools', 'Jasa & Servis', 'sun'],
        ['people', 'Komunitas', 'mint'],
    ];

    $flow = [
        ['qr-code-scan', 'Hubungkan nomor', 'Scan QR dari HP, nomor WhatsApp Anda langsung terhubung.', 'mint'],
        ['robot', 'Dibalas otomatis', 'Auto reply, chatbot menu, atau AI menjawab pertanyaan umum.', 'sun'],
        ['headset', 'Diteruskan ke CS', 'Chat yang butuh manusia dibagikan ke anggota tim.', 'peach'],
        ['tags', 'Ditandai & dicatat', 'Beri label dan catatan supaya tindak lanjutnya jelas.', 'sky'],
        ['megaphone', 'Disapa lagi', 'Kirim broadcast atau pesan terjadwal untuk promo berikutnya.', 'lilac'],
    ];

    $highlights = [
        ['calendar2-week', 'Broadcast & pesan terjadwal', 'Kirim sekarang, di jam tertentu, atau rutin setiap hari, minggu, dan bulan. Bisa dijeda dan dilanjutkan kapan saja.', 'sun'],
        ['ui-checks', 'Integrasi Google Form', 'Setiap pendaftar dibalas WhatsApp otomatis, lengkap dengan QR registrasi ulang dan link bayar bila diperlukan.', 'mint'],
        ['code-slash', 'API untuk developer', 'Kirim pesan dari website atau aplikasi Anda sendiri. Dokumentasinya bisa langsung dibagikan ke developer.', 'lilac'],
    ];

    $extras = [
        ['file-earmark-text', 'Template pesan', 'Simpan pesan yang sering dipakai, lengkap dengan format tebal dan miring.'],
        ['lightning-charge', 'Quick reply', 'Balas pertanyaan yang sama cukup dengan beberapa ketukan.'],
        ['bar-chart', 'Polling', 'Kirim polling ke pelanggan dan lihat hasilnya langsung.'],
        ['file-earmark-spreadsheet', 'Import kontak', 'Masukkan kontak dari Excel atau dari grup WhatsApp yang sudah ada.'],
        ['phone', 'Banyak nomor', 'Hubungkan nomor berbeda untuk setiap cabang atau divisi.'],
        ['shield-check', 'Batas kirim harian', 'Atur batas pesan per hari supaya nomor tetap aman.'],
    ];

    $benefits = [
        ['lightning', 'Balas lebih cepat', 'Pertanyaan umum terjawab otomatis, tim fokus pada chat yang benar-benar butuh perhatian.', 'sun'],
        ['people', 'Tim lebih kompak', 'Semua CS melihat percakapan yang sama, jadi tidak ada pelanggan yang dibalas dua kali atau terlewat.', 'mint'],
        ['graph-up-arrow', 'Peluang tidak hilang', 'Pelanggan lama tetap disapa lewat broadcast dan pesan terjadwal.', 'peach'],
    ];

    $steps = [
        ['Hubungkan nomor', 'Scan QR dari aplikasi WhatsApp di HP Anda.'],
        ['Masukkan kontak', 'Import dari Excel atau grup WhatsApp, lalu kelompokkan.'],
        ['Atur balasan otomatis', 'Pasang auto reply, chatbot menu, atau AI bot.'],
        ['Ajak tim', 'Bagikan inbox ke CS dan mulai kirim broadcast.'],
    ];
@endphp

@section('title', 'Management WhatsApp')
@section('meta_description', 'Kelola WhatsApp bisnis dengan bizbos: inbox untuk tim, chatbot dan AI, auto reply, broadcast terjadwal, integrasi Google Form, sampai API untuk developer.')
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
                    <span class="pd-eyebrow"><i class="bi bi-whatsapp"></i> bizbos WhatsApp</span>
                    <h1 class="pd-hero-title">Chat pelanggan <span class="pd-script">numpuk?</span></h1>
                    <p class="pd-hero-ask">Balas lebih cepat <span class="pd-rotate" data-words="bersama tim.|pakai chatbot.|dengan AI.|lewat broadcast.">bersama tim.</span></p>
                    <p class="pd-hero-lead">Satu nomor WhatsApp untuk banyak CS, balasan otomatis 24 jam, broadcast terjadwal, sampai tersambung ke website dan Google Form Anda.</p>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ $registerUrl }}" class="btn btn-primary btn-lg pd-btn">Mulai Pakai WhatsApp <i class="bi bi-arrow-right"></i></a>
                        <a href="#alur" class="btn btn-lg pd-btn pd-btn-ghost"><i class="bi bi-signpost-split"></i> Lihat Alurnya</a>
                    </div>
                    <ul class="pd-chips list-unstyled mb-0">
                        <li><i class="bi bi-check-circle-fill"></i> Inbox untuk tim</li>
                        <li><i class="bi bi-check-circle-fill"></i> Chatbot & AI</li>
                        <li><i class="bi bi-check-circle-fill"></i> Broadcast terjadwal</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    {{-- Ilustrasi percakapan (bukan data asli). --}}
                    <div class="pd-hero-visual">
                        <div class="pd-chat" aria-hidden="true">
                            <div class="pd-chat-head"><span class="pd-chat-avatar">A</span><span><strong>Andi</strong><small>Label: Calon pelanggan</small></span></div>
                            <div class="pd-chat-body">
                                <p class="pd-bubble">Halo kak, kelas piano masih ada slot?</p>
                                <p class="pd-bubble pd-bubble--out">Halo! Silakan pilih:<br>1. Jadwal kelas<br>2. Biaya<br>3. Bicara dengan admin</p>
                                <p class="pd-bubble">3</p>
                                <p class="pd-bubble pd-bubble--out">Baik, admin kami segera membalas ya 🙏</p>
                            </div>
                        </div>
                        <div class="pd-float pd-float--sold"><i class="bi bi-person-check-fill"></i><span><strong>Chat dibagikan</strong><small>Ke CS yang sedang online</small></span></div>
                        <div class="pd-float pd-float--scan"><i class="bi bi-send-check-fill"></i><span><strong>Broadcast terjadwal</strong><small>Setiap Senin 08.00</small></span></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- INTEREST: dipakai siapa --}}
    <section class="pd-section" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center">Untuk bisnis yang ramai<br>chat pelanggan</h2>
            <div class="row g-3 justify-content-center mt-2">
                @foreach ($useCases as [$icon, $label, $tone])
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
            <h2 class="pd-title text-center">Dari pesan masuk sampai pelanggan kembali</h2>
            <p class="pd-lead text-center mx-auto mb-5" style="max-width: 40rem;">Tidak ada lagi chat yang tenggelam atau lupa dibalas.</p>
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

    {{-- INTEREST: inbox tim, chatbot, AI --}}
    <section class="pd-section" data-reveal>
        <div class="container">
            <h2 class="pd-title">Manusia dan bot,<br class="d-none d-md-inline"> bekerja bersama</h2>
            <div class="row g-4 mt-1">
                <div class="col-lg-6">
                    <article class="pd-role pd-tone--peach h-100">
                        <div>
                            <span class="pd-role-tag">Inbox tim</span>
                            <h3>Satu nomor, banyak CS</h3>
                            <p>Bagikan chat ke anggota tim, beri label dan catatan, kirim foto, dokumen, atau polling. Semua orang melihat riwayat yang sama.</p>
                        </div>
                        <ul class="pd-mock list-unstyled" aria-hidden="true">
                            <li><span>CS: Rina</span><b>Budi</b><em class="is-ok">Pelanggan</em></li>
                            <li><span>CS: Dimas</span><b>Sari</b><em class="is-warn">Follow up</em></li>
                            <li><span>Belum dibagikan</span><b>Andi</b><em>Baru</em></li>
                        </ul>
                    </article>
                </div>
                <div class="col-lg-6 d-flex flex-column gap-4">
                    <article class="pd-role pd-tone--sun">
                        <div>
                            <span class="pd-role-tag">Chatbot & auto reply</span>
                            <h3>Menu otomatis 24 jam</h3>
                            <p>Buat menu bertingkat atau balasan berdasarkan kata kunci. Kalau pelanggan minta bicara dengan admin, chat diteruskan ke tim.</p>
                        </div>
                    </article>
                    <article class="pd-role pd-tone--lilac">
                        <div>
                            <span class="pd-role-tag">AI bot</span>
                            <h3>Jawaban yang paham bisnis Anda</h3>
                            <p>Isi pengetahuan tentang produk dan layanan Anda, lalu AI membantu menjawab. Memakai API key penyedia AI pilihan Anda sendiri.</p>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- INTEREST: sorotan --}}
    <section class="pd-section pd-section--soft" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center">Menjangkau lebih banyak orang</h2>
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
                        <h3 class="mb-1">Fitur kecil yang sering dipakai</h3>
                        <p class="mb-0">Hal-hal sederhana yang membuat pekerjaan CS jauh lebih ringan.</p>
                    </div>
                </div>
                <div class="row g-3 mt-1">
                    @foreach ($extras as [$icon, $title, $text])
                        <div class="col-sm-6 col-lg-4">
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
            <h2 class="pd-title text-center">Pelanggan senang, tim tidak kewalahan</h2>
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
            <p class="small text-muted text-center mt-4 mb-0">bizbos tidak berafiliasi dengan WhatsApp. Kirim pesan hanya ke kontak yang sudah memberi izin supaya nomor Anda tetap aman.</p>
        </div>
    </section>

    {{-- ACTION --}}
    @include('frontend.produk._steps', ['title' => 'Mulai dalam 4 langkah', 'steps' => $steps])
    @include('frontend.produk._cta', ['before' => 'Siap balas chat lebih', 'highlight' => 'cepat', 'after' => '?', 'subtitle' => 'Daftar gratis, hubungkan nomor WhatsApp Anda, dan biarkan balasan otomatis bekerja.', 'button' => 'Mulai Pakai WhatsApp'])

    @include('frontend.partials.footer')
@endsection

@push('scripts')
    <script src="{{ asset('js/produk.js') }}?v={{ filemtime(public_path('js/produk.js')) }}" defer></script>
@endpush
