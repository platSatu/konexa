{{--
    Section "rasa sakit" pemilik kursus -- tepat di bawah hero beranda (dipanggil dari
    sections/_render.blade.php, hanya di beranda). Pola copy: MASALAH (adegan spesifik yang
    dialami sehari-hari) -> DIPERDALAM (akibat & perasaannya) -> SOLUSI (kelegaan dengan bizbos).
    Tiap kartu punya ilustrasi SVG sendiri di partials/ilustrasi/situasi-*.blade.php.
    Teks sengaja HARDCODE (bukan dari Superadmin) dan dibuat umum untuk semua jenis tempat kursus.
    Gaya: public/css/frontend.css bagian "Kenal dengan situasi ini".
--}}
@php
    $situations = [
        [
            'art' => 'rekap',
            'tone' => 'night',
            'time' => 'Akhir bulan, 23.47',
            'title' => 'Sudah hampir tengah malam, Anda masih merekap absensi dari buku.',
            'pain' => 'Ada tanda tangan yang terlewat, ada halaman yang sobek. Besok pagi pengajar menanyakan honornya. Salah hitung sedikit saja, Anda yang tidak enak hati, atau Anda yang menanggung selisihnya.',
        ],
        [
            'art' => 'tagih',
            'tone' => 'sun',
            'time' => 'Tanggal 10, SPP belum masuk semua',
            'title' => 'Menagih SPP itu canggung. Apalagi kalau harus berkali-kali.',
            'pain' => 'Chat sudah dibaca, tapi tidak dibalas. Anda ragu mengingatkan lagi karena takut dianggap tidak sopan. Sementara itu, gaji pengajar dan sewa tempat tetap harus dibayar tepat waktu.',
        ],
        [
            'art' => 'tanya',
            'tone' => 'mint',
            'time' => 'Malam hari, saat makan bersama keluarga',
            'title' => '"Kak, besok les jam berapa ya?" Pertanyaan yang sama, dari puluhan orang tua.',
            'pain' => 'HP berbunyi saat Anda mengajar, saat makan malam, bahkan di hari Minggu. Telat membalas sebentar, Anda dianggap kurang profesional. Waktu istirahat pun tidak pernah benar-benar istirahat.',
        ],
        [
            'art' => 'data',
            'tone' => 'sky',
            'time' => 'Saat data paling dibutuhkan',
            'title' => 'Data murid ada di mana-mana. Begitu dicari, tidak ketemu di mana pun.',
            'pain' => 'Sebagian di Excel, sebagian di formulir online, sisanya di foto galeri HP. Begitu admin berganti atau HP rusak, catatan bertahun-tahun ikut hilang begitu saja.',
        ],
    ];
@endphp
<section id="kenal-situasi" class="situasi-section" data-reveal>
    <div class="container">
        <div class="text-center mb-5">
            <span class="situasi-eyebrow"><i class="bi bi-heart-pulse"></i> Kami paham rasanya</span>
            <h2 class="situasi-title">Tempat kursus Anda makin ramai.<br class="d-none d-md-inline"> Tapi kenapa Anda justru makin lelah?</h2>
            <p class="situasi-lead mx-auto">Bukan karena mengajarnya. Mengajar justru bagian yang Anda sukai. Yang menguras tenaga adalah urusan di luar kelas, yang datang lagi dan lagi setiap bulan.</p>
        </div>

        <div class="row g-4">
            @foreach ($situations as $situation)
                <div class="col-lg-6">
                    <article class="situasi-card" style="--i: {{ $loop->index }}">
                        <div class="situasi-pain situasi-tone--{{ $situation['tone'] }}">
                            <div class="situasi-art-wrap">
                                @include('frontend.partials.ilustrasi.situasi-'.$situation['art'])
                            </div>
                            <span class="situasi-time"><i class="bi bi-clock-history"></i> {{ $situation['time'] }}</span>
                            <h3 class="situasi-card-title">{{ $situation['title'] }}</h3>
                            <p class="situasi-pain-text">{{ $situation['pain'] }}</p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="situasi-closing">
            <div class="situasi-closing-text">
                <p class="situasi-closing-warn"><i class="bi bi-arrow-repeat"></i> Kalau dibiarkan, bulan depan polanya terulang lagi: lelah yang sama, chat tagihan yang sama, rekap yang sama.</p>
                <h3>Kabar baiknya, semua urusan ini bisa berjalan sendiri.</h3>
                <p>Waktu yang selama ini habis untuk mencatat dan menagih bisa Anda pakai untuk membuka kelas baru, melatih pengajar, atau sekadar beristirahat.</p>
            </div>
            <div class="situasi-closing-action">
                <a href="#cara-kerja" class="btn btn-light btn-lg hero-btn situasi-closing-btn">Lihat caranya <i class="bi bi-arrow-down"></i></a>
            </div>
        </div>
    </div>
</section>
