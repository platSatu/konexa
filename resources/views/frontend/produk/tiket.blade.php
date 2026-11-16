@extends('layouts.frontend')

{{--
    Halaman produk Tiket (/tiket), disusun AIDA: Attention (hero) -> Interest (jenis event, fitur)
    -> Desire (kalkulator, dashboard) -> Action (langkah + ajakan). Gaya di public/css/produk.css.
    $ticketFee dari app.bizbos (Superadmin > Biaya Tiket); null = kalkulator disembunyikan.
--}}
@php
    $registerUrl = 'https://app.bizbos.id/auth/register';
    $feePercent = $ticketFee['fee_percent'] ?? null;
    $freeAllowance = (int) ($ticketFee['free_allowance'] ?? 0);
    $percentLabel = $feePercent !== null ? rtrim(rtrim(number_format((float) $feePercent, 2, ',', '.'), '0'), ',').'%' : null;

    $eventTypes = [
        ['easel2', 'Seminar & Talkshow', 'peach'],
        ['tools', 'Workshop & Kelas', 'sun'],
        ['music-note-beamed', 'Konser & Festival', 'lilac'],
        ['camera-video', 'Webinar', 'sky'],
        ['people', 'Ibadah & Komunitas', 'mint'],
        ['trophy', 'Olahraga & Fun Run', 'peach'],
        ['shop', 'Pameran & Bazar', 'sun'],
        ['balloon-heart', 'Reuni & Gathering', 'lilac'],
    ];

    $features = [
        ['ui-checks', 'Form data pembeli', 'Tanyakan ukuran kaos, asal instansi, atau data lain yang Anda butuhkan saat checkout.', 'mint'],
        ['person-badge', 'ID card peserta', 'Peserta menukar e-ticket jadi kartu ber-QR di kiosk. Ukuran KTP untuk kartu PVC, atau A5 untuk tali lanyard.', 'sun'],
        ['wallet2', 'Hasil masuk ke Saldo', 'Uang tiket masuk otomatis setiap pembayaran lunas dan bisa ditarik ke rekening.', 'peach'],
        ['credit-card', 'Banyak metode bayar', 'Virtual Account, e-wallet, dan QRIS. Status lunas tercatat sendiri.', 'sky'],
        ['palette', 'Desain e-ticket sendiri', 'Pasang gambar event sebagai latar tiket dan pilih warnanya. ID card peserta ikut menyesuaikan.', 'lilac'],
        ['people', 'Akun petugas scan', 'Kru masuk dengan PIN 6 angka milik posnya sendiri, tanpa menyentuh akun utama Anda.', 'mint'],
    ];

    $steps = [
        ['Buat page tiket', 'Isi informasi event dan pilih siapa yang menanggung biaya layanan.'],
        ['Atur kategori', 'Tentukan nama, harga, dan kuota untuk setiap jenis tiket.'],
        ['Bagikan link', 'Sebar link ke media sosial, grup WhatsApp, atau email.'],
        ['Scan di hari-H', 'Peserta datang, QR di-scan, langsung tercatat hadir.'],
    ];

    // Data terstruktur Schema.org, pola sama dengan halaman Kursus. Isinya WAJIB sama dengan yang
    // benar-benar tampil di halaman ini -- menulis fitur yang tidak ada bisa membuat halaman kena penalti.
    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Aplikasi Tiket Event', 'item' => url()->current()],
                ],
            ],
            [
                '@type' => 'SoftwareApplication',
                'name' => config('app.name').' Tiket',
                'applicationCategory' => 'BusinessApplication',
                'operatingSystem' => 'Web',
                'url' => url()->current(),
                'inLanguage' => 'id-ID',
                'description' => 'Aplikasi jual tiket event online: halaman event, pembayaran online, e-ticket QR, registrasi ulang di pintu masuk, cetak ID card peserta di kiosk, dan absensi tiap kelas.',
                'featureList' => [
                    'Halaman event dan kategori tiket',
                    'Pembayaran Virtual Account, e-wallet, dan QRIS',
                    'E-ticket QR terkirim otomatis',
                    'Registrasi ulang dengan scan QR',
                    'Akun petugas scan dengan PIN per pos',
                    'Cetak ID card peserta di kiosk',
                    'Absensi peserta di setiap kelas',
                    'Desain e-ticket dan ID card sendiri',
                ],
            ],
        ],
    ];
@endphp

@section('title_full', 'Aplikasi Jual Tiket Event, Registrasi Ulang | '.config('app.name'))
@section('meta_description', 'Aplikasi jual tiket event online: terima pembayaran, kirim e-ticket QR, scan registrasi ulang di pintu masuk, dan cetak ID card peserta di kiosk. Tanpa biaya langganan.')
@section('meta_image', asset('images/produk/tiket-eticket.webp'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/produk.css') }}?v={{ filemtime(public_path('css/produk.css')) }}">
@endpush

@section('content')
    @include('frontend.partials.topbar')

    {{-- ATTENTION --}}
    <header class="pd-hero">
        <div class="container position-relative">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="pd-eyebrow"><i class="bi bi-ticket-perforated"></i> bizbos Tiket</span>
                    <h1 class="pd-hero-title">Hi, <span class="pd-script">Event Creator!</span></h1>
                    <p class="pd-hero-ask">Mau bikin <span class="pd-rotate" data-words="seminar?|workshop?|konser?|kelas?|fun run?">seminar?</span></p>
                    <p class="pd-hero-lead">Aplikasi jual tiket online untuk event apa pun: terima pembayaran, kirim e-ticket QR, dan scan peserta di pintu masuk. Semuanya dari satu dashboard, tanpa biaya di depan.</p>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ $registerUrl }}" class="btn btn-primary btn-lg pd-btn">Buat Event Sekarang <i class="bi bi-arrow-right"></i></a>
                        @if ($percentLabel)
                            <a href="#hitung" class="btn btn-lg pd-btn pd-btn-ghost"><i class="bi bi-calculator"></i> Hitung Pendapatan</a>
                        @endif
                    </div>
                    <ul class="pd-chips list-unstyled mb-0">
                        <li><i class="bi bi-check-circle-fill"></i> Tanpa biaya di depan</li>
                        <li><i class="bi bi-check-circle-fill"></i> E-ticket otomatis</li>
                        <li><i class="bi bi-check-circle-fill"></i> Scan QR di pintu masuk</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="pd-hero-visual">
                        <figure class="pd-shot pd-shot--back">
                            <img src="{{ asset('images/produk/tiket-kategori.webp') }}" alt="Dashboard kategori tiket di bizbos" width="1600" height="768">
                        </figure>
                        <figure class="pd-shot pd-shot--front">
                            <img src="{{ asset('images/produk/tiket-eticket.webp') }}" alt="Contoh e-ticket dengan QR dari bizbos" width="1638" height="626">
                        </figure>
                        <div class="pd-float pd-float--sold"><i class="bi bi-bag-check-fill"></i><span><strong>Tiket lunas</strong><small>E-ticket terkirim ke email</small></span></div>
                        <div class="pd-float pd-float--scan"><i class="bi bi-qr-code-scan"></i><span><strong>Hadir</strong><small>Scan berhasil</small></span></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- INTEREST: jenis event --}}
    <section class="pd-section" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center">Jual tiket online untuk<br>beragam jenis event</h2>
            <div class="row g-3 justify-content-center mt-2">
                @foreach ($eventTypes as [$icon, $label, $tone])
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="pd-type pd-tone--{{ $tone }}"><i class="bi bi-{{ $icon }}"></i><span>{{ $label }}</span></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- INTEREST: fitur --}}
    <section class="pd-section pd-section--soft" data-reveal>
        <div class="container">
            <h2 class="pd-title">Tingkatkan event dengan<br class="d-none d-md-inline"> fitur unggulan</h2>
            <div class="row g-4 mt-1">
                <div class="col-lg-7">
                    <article class="pd-bento pd-tone--peach">
                        <div class="pd-bento-text">
                            <h3>Scan QR di pintu masuk</h3>
                            <p>Cukup alat scanner dan laptop. Kode terbaca langsung tercatat hadir tanpa perlu menekan Enter, dan tiket yang sudah dipakai otomatis ditolak.</p>
                        </div>
                        <img src="{{ asset('images/produk/tiket-eticket.webp') }}" alt="E-ticket yang sudah di-scan" class="pd-bento-img" loading="lazy" width="1638" height="626">
                    </article>
                </div>
                <div class="col-lg-5">
                    <article class="pd-bento pd-tone--sun">
                        <div class="pd-bento-text">
                            <h3>Pantau penjualan real-time</h3>
                            <p>Lihat tiket terjual, menunggu bayar, dan sisa kuota per kategori kapan saja.</p>
                        </div>
                        <img src="{{ asset('images/produk/tiket-kategori.webp') }}" alt="Tabel penjualan per kategori tiket" class="pd-bento-img pd-bento-img--edge" loading="lazy" width="1600" height="768">
                    </article>
                </div>
                @foreach ($features as [$icon, $title, $text, $tone])
                    <div class="col-md-6 col-lg-4">
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

    {{-- DESIRE: kalkulator (angka dari superadmin) --}}
    @if ($percentLabel)
        <section class="pd-section" id="hitung" data-reveal>
            <div class="container">
                <div class="row g-4 g-lg-5 align-items-center">
                    <div class="col-lg-5">
                        <h2 class="pd-title">Hitung perkiraan pendapatan event Anda</h2>
                        <p class="pd-lead">Biaya layanan hanya <strong>{{ $percentLabel }}</strong> dari tiket berbayar yang terjual. Tidak ada biaya di depan, jadi Anda baru membayar saat tiket laku.</p>
                        <ul class="pd-checks list-unstyled">
                            <li><strong>Ditanggung Anda:</strong> biaya dipotong dari hasil penjualan.</li>
                            <li><strong>Ditanggung pembeli:</strong> biaya ditambahkan saat checkout, Anda terima harga utuh.</li>
                            <li><strong>Event gratis:</strong> tanpa biaya apa pun, sampai {{ number_format($freeAllowance, 0, ',', '.') }} tiket.</li>
                        </ul>
                    </div>
                    <div class="col-lg-7">
                        <div class="pd-calc" id="pd-calc" data-bps="{{ (int) round((float) $feePercent * 100) }}">
                            <div class="pd-calc-head"><i class="bi bi-calculator"></i> Coba hitung sendiri</div>
                            <div class="pd-calc-modes" role="radiogroup" aria-label="Siapa yang menanggung biaya">
                                <label><input type="radio" name="pd-mode" value="organizer" checked> Ditanggung saya</label>
                                <label><input type="radio" name="pd-mode" value="buyer"> Ditanggung pembeli</label>
                            </div>
                            <div class="pd-calc-inputs">
                                <label>Harga tiket (Rp)
                                    <input type="number" min="0" step="1000" value="50000" data-calc="price" inputmode="numeric">
                                </label>
                                <label>Perkiraan terjual
                                    <input type="number" min="0" step="1" value="100" data-calc="qty" inputmode="numeric">
                                </label>
                            </div>
                            <dl class="pd-calc-rows">
                                <div><dt>Total penjualan</dt><dd data-out="sales">Rp 0</dd></div>
                                <div><dt>Biaya layanan ({{ $percentLabel }}) <small data-out="who"></small></dt><dd class="text-danger" data-out="fee">Rp 0</dd></div>
                                <div data-buyer-only hidden><dt>Dibayar semua pembeli</dt><dd data-out="paid">Rp 0</dd></div>
                            </dl>
                            <div class="pd-calc-net"><span>Pendapatan Anda</span><strong data-out="net">Rp 0</strong></div>
                            <p class="pd-calc-note">Estimasi. Biaya dihitung per pesanan dan dibulatkan ke atas sampai rupiah.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- DESIRE: satu dashboard --}}
    <section class="pd-section pd-section--soft" data-reveal>
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <h2 class="pd-title">Event, tagihan, dan WhatsApp dalam satu dashboard</h2>
                    <p class="pd-lead">Pemasukan tiket tampil bersama invoice dan pesan pelanggan. Pembeli tiket otomatis tersimpan sebagai customer, siap Anda sapa lagi di event berikutnya.</p>
                    <ul class="pd-checks list-unstyled mb-0">
                        <li>Ringkasan pemasukan dan pesanan baru setiap hari</li>
                        <li>Pengingat hal yang perlu ditindaklanjuti</li>
                        <li>Data pembeli tidak tercecer di spreadsheet</li>
                    </ul>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <figure class="pd-shot pd-shot--flat mb-0">
                        <img src="{{ asset('images/produk/tiket-dashboard.webp') }}" alt="Dashboard bizbos" loading="lazy" width="1600" height="805">
                    </figure>
                </div>
            </div>
        </div>
    </section>

    {{-- DESIRE: operasional hari-H (petugas, registrasi ulang, ID card, absensi kelas) --}}
    <section class="pd-section" id="hari-h" aria-labelledby="hari-h-title" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center" id="hari-h-title">Hari-H berjalan rapi, dari<br class="d-none d-md-inline"> pintu masuk sampai kartu peserta</h2>
            <p class="pd-lead text-center mx-auto mb-5" style="max-width: 42rem;">Untuk event yang butuh lebih dari sekadar scan masuk — konferensi berhari-hari, pelatihan dengan banyak kelas, atau acara dengan beberapa pintu.</p>
            <ol class="pd-flow list-unstyled">
                <li class="pd-flow-item">
                    <span class="pd-flow-icon pd-tone--sky"><i class="bi bi-shield-lock"></i></span>
                    <span class="pd-flow-num">1</span>
                    <h3>Petugas masuk dengan PIN</h3>
                    <p>Tiap pos punya halaman dan PIN 6 angka sendiri — Gate A, Gate B, atau meja kiosk. Kru cukup diberi PIN-nya, dan akun utama Anda tidak pernah berpindah tangan.</p>
                </li>
                <li class="pd-flow-item">
                    <span class="pd-flow-icon pd-tone--peach"><i class="bi bi-qr-code-scan"></i></span>
                    <span class="pd-flow-num">2</span>
                    <h3>Registrasi ulang di pintu masuk</h3>
                    <p>E-ticket peserta di-scan, kehadirannya langsung tercatat. Tiket yang sudah dipakai otomatis ditolak, jadi satu kode tidak bisa masuk dua kali.</p>
                </li>
                <li class="pd-flow-item">
                    <span class="pd-flow-icon pd-tone--sun"><i class="bi bi-person-badge"></i></span>
                    <span class="pd-flow-num">3</span>
                    <h3>Tukar jadi ID card</h3>
                    <p>Di kiosk, e-ticket ditukar menjadi kartu peserta yang langsung tercetak. Pilih ukuran KTP untuk kartu PVC atau A5 untuk tali lanyard, tegak atau mendatar. Tulisan di kartu Anda tentukan sendiri — PESERTA, DELEGASI, atau VISITOR — lengkap dengan kategori tiketnya.</p>
                </li>
                <li class="pd-flow-item">
                    <span class="pd-flow-icon pd-tone--mint"><i class="bi bi-clipboard-check"></i></span>
                    <span class="pd-flow-num">4</span>
                    <h3>Absensi di setiap kelas</h3>
                    <p>Kartu itu punya QR sendiri dan dipakai lagi untuk absen di tiap sesi. Anda tahu siapa hadir di kelas mana sepanjang acara, bukan sekadar siapa yang masuk gedung.</p>
                </li>
            </ol>

            <div class="row g-4 g-lg-5 mt-1">
                <div class="col-md-6">
                    <figure class="pd-shot pd-shot--flat mb-0">
                        <img src="{{ asset('images/produk/tiket-registrasi.webp') }}" alt="Halaman registrasi ulang: petugas Gate B memasukkan PIN 6 angka" loading="lazy" width="1200" height="1116">
                    </figure>
                    <p class="small text-muted text-center mt-3 mb-0">Tiap pos masuk dengan PIN-nya sendiri.</p>
                </div>
                <div class="col-md-6">
                    <figure class="pd-shot pd-shot--flat mb-0">
                        <img src="{{ asset('images/produk/tiket-idcard.webp') }}" alt="Preview ID card peserta ukuran KTP dengan QR untuk absensi kelas" loading="lazy" width="1200" height="1116">
                    </figure>
                    <p class="small text-muted text-center mt-3 mb-0">Kartu peserta ber-QR, tercetak di kiosk.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ACTION --}}
    @include('frontend.produk._steps', ['title' => 'Mulai jual tiket dalam 4 langkah', 'steps' => $steps])
    @include('frontend.produk._cta', ['before' => 'Siap jual tiket', 'highlight' => 'event', 'after' => ' Anda?', 'subtitle' => 'Daftar gratis, buat page tiket, dan bagikan link-nya hari ini juga.', 'button' => 'Buat Event Sekarang'])

    {{-- Sengaja paling bawah dan dibuat tenang: ini layanan tambahan, bukan syarat memakai aplikasi,
         jadi tidak boleh bersaing dengan ajakan utama di atasnya. --}}
    <section class="pd-section pd-section--soft" aria-labelledby="tim-register-title" data-reveal>
        <div class="container">
            <div class="pd-notify">
                <div class="pd-notify-head">
                    <span class="pd-card-icon pd-notify-badge"><i class="bi bi-people"></i></span>
                    <div>
                        <h2 class="h5 fw-bold mb-1" id="tim-register-title">Butuh tim registrasi di hari-H?</h2>
                        <p class="mb-0">Kalau Anda belum punya kru untuk menjaga pintu masuk dan meja kiosk, kami bisa bantu sediakan. Sifatnya tambahan saja — aplikasinya tetap bisa Anda jalankan sendiri tanpa kami.</p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('frontend.contact') }}" class="btn pd-btn pd-btn-ghost"><i class="bi bi-headset"></i> Hubungi Kami</a>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.partials.footer')
@endsection

@push('scripts')
    {{-- JSON-LD dibiarkan di akhir halaman: Google tetap membacanya di mana pun letaknya,
         dan di sini ia tidak menunda tampilnya halaman. --}}
    <script type="application/ld+json">@json($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)</script>
    <script src="{{ asset('js/produk.js') }}?v={{ filemtime(public_path('js/produk.js')) }}" defer></script>
    <script>
        (function () {
            // Kalkulator: rumus sama dengan app.bizbos (TicketSetting::feeFor, basis poin, bulat ke atas).
            var calc = document.getElementById('pd-calc');
            if (!calc) return;
            var bps = Number(calc.dataset.bps) || 0;
            var money = function (n) { return 'Rp ' + n.toLocaleString('id-ID'); };
            var out = function (key, text) { calc.querySelector('[data-out=' + key + ']').textContent = text; };
            var num = function (key) { return Math.max(0, Math.floor(Number(calc.querySelector('[data-calc=' + key + ']').value) || 0)); };

            function update() {
                var buyer = calc.querySelector('[name=pd-mode]:checked').value === 'buyer';
                var sales = num('price') * num('qty');
                var fee = sales > 0 && bps > 0 ? Math.floor((sales * bps + 9999) / 10000) : 0;
                out('sales', money(sales));
                out('fee', (buyer ? '+ ' : '- ') + money(fee));
                out('who', buyer ? '· ditanggung pembeli' : '· ditanggung Anda');
                out('paid', money(sales + fee));
                out('net', money(buyer ? sales : sales - fee));
                calc.querySelector('[data-buyer-only]').hidden = !buyer;
            }

            calc.addEventListener('input', update);
            calc.addEventListener('change', update);
            update();
        })();
    </script>
@endpush
