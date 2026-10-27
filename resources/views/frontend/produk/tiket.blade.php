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
        ['person-badge', 'ID card peserta', 'Cetak kartu peserta langsung dari data pendaftar, tanpa ketik ulang.', 'sun'],
        ['wallet2', 'Hasil masuk ke Saldo', 'Uang tiket masuk otomatis setiap pembayaran lunas dan bisa ditarik ke rekening.', 'peach'],
        ['credit-card', 'Banyak metode bayar', 'Virtual Account, e-wallet, dan QRIS. Status lunas tercatat sendiri.', 'sky'],
        ['palette', 'Desain e-ticket sendiri', 'Sesuaikan warna dan tampilan e-ticket dengan identitas event Anda.', 'lilac'],
        ['people', 'Akun petugas scan', 'Beri akses scan ke kru tanpa membagikan akun utama Anda.', 'mint'],
    ];

    $steps = [
        ['Buat page tiket', 'Isi informasi event dan pilih siapa yang menanggung biaya layanan.'],
        ['Atur kategori', 'Tentukan nama, harga, dan kuota untuk setiap jenis tiket.'],
        ['Bagikan link', 'Sebar link ke media sosial, grup WhatsApp, atau email.'],
        ['Scan di hari-H', 'Peserta datang, QR di-scan, langsung tercatat hadir.'],
    ];
@endphp

@section('title', 'Tiket Event')
@section('meta_description', 'Jual tiket event secara online dengan bizbos: pembayaran otomatis, e-ticket ber-QR, scan di pintu masuk, dan hasil penjualan langsung masuk ke Saldo.')
@section('meta_image', asset('images/produk/tiket-eticket.webp'))

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
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="pd-eyebrow"><i class="bi bi-ticket-perforated"></i> bizbos Tiket</span>
                    <h1 class="pd-hero-title">Hi, <span class="pd-script">Event Creator!</span></h1>
                    <p class="pd-hero-ask">Mau bikin <span class="pd-rotate" data-words="seminar?|workshop?|konser?|kelas?|fun run?">seminar?</span></p>
                    <p class="pd-hero-lead">Jual tiket, terima pembayaran online, dan scan peserta di pintu masuk. Semuanya dari satu dashboard, tanpa biaya di depan.</p>
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
            <h2 class="pd-title text-center">Beragam jenis event,<br>semua bisa pakai bizbos</h2>
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

    {{-- ACTION: langkah --}}
    <section class="pd-section" data-reveal>
        <div class="container">
            <h2 class="pd-title text-center">Mulai jual tiket dalam 4 langkah</h2>
            <ol class="pd-steps list-unstyled row g-4 mt-2">
                @foreach ($steps as [$title, $text])
                    <li class="col-md-6 col-lg-3">
                        <div class="pd-step">
                            <span class="pd-step-num">{{ $loop->iteration }}</span>
                            <h3>{{ $title }}</h3>
                            <p>{{ $text }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ACTION: ajakan (gaya CTA yang sama dengan beranda) --}}
    <section class="cta-section cta-section--default position-relative overflow-hidden" data-reveal>
        <div class="container position-relative">
            <div class="cta-card">
                <span class="cta-ring cta-ring--left" aria-hidden="true"></span>
                <span class="cta-ring cta-ring--right" aria-hidden="true"></span>
                <div class="cta-content">
                    <h2 class="cta-title">Siap jual tiket <span class="cta-highlight">event</span> Anda?</h2>
                    <p class="cta-subtitle">Daftar gratis, buat page tiket, dan bagikan link-nya hari ini juga.</p>
                    <div class="cta-buttons">
                        <a href="{{ $registerUrl }}" class="btn btn-lg cta-btn-primary">Buat Event Sekarang <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                        <a href="{{ route('frontend.contact') }}" class="btn btn-lg cta-btn-secondary">Tanya Tim Kami</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.partials.footer')
@endsection

@push('scripts')
    <script>
        (function () {
            // Kata bergantian di hero ("Mau bikin seminar? workshop? ...").
            var rotate = document.querySelector('.pd-rotate');
            if (rotate && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                var words = rotate.dataset.words.split('|'), i = 0;
                setInterval(function () {
                    rotate.classList.add('is-out');
                    setTimeout(function () {
                        i = (i + 1) % words.length;
                        rotate.textContent = words[i];
                        rotate.classList.remove('is-out');
                    }, 250);
                }, 2400);
            }

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
