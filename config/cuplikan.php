<?php

/*
|--------------------------------------------------------------------------
| Cuplikan video aplikasi bizbos (YouTube)
|--------------------------------------------------------------------------
| Dipakai oleh komponen resources/views/frontend/partials/video-player.blade.php
| dan section "Lihat bizbos bekerja" di beranda.
|
| youtube : ID video (bagian setelah youtu.be/)
| poster  : gambar sampul di public/videos/ (dibuat sendiri, tanpa nama asli)
| role    : untuk siapa fitur ini
| label   : nama tab
| benefit : satu kalimat manfaat
|
| Menambah cuplikan baru: upload ke YouTube, simpan poster di public/videos/,
| lalu tambahkan satu entri di bawah. Urutan entri = urutan tab di beranda.
*/

return [
    'channel_url' => 'https://www.youtube.com/channel/UC8QkiCJkouidWXA-np3XmoA',

    'videos' => [
        'jadwal' => [
            'youtube' => 'lBwNK_3bwIE',
            'poster' => 'videos/cuplikan-jadwal.jpg',
            'duration' => '0:10',
            'icon' => 'calendar2-week',
            'role' => 'Untuk pemilik & admin',
            'label' => 'Jadwal kelas',
            'title' => 'Jadwal kelas di bizbos: semua ruangan dalam satu layar',
            'benefit' => 'Semua kelas, pengajar, dan ruangan terlihat dalam satu layar. Langsung tahu slot mana yang masih kosong.',
        ],
        'absensi' => [
            'youtube' => '97M5Cp-Vz_Y',
            'poster' => 'videos/cuplikan-absensi.jpg',
            'duration' => '0:11',
            'icon' => 'qr-code-scan',
            'role' => 'Untuk pemilik & admin',
            'label' => 'Absensi',
            'title' => 'Absensi di bizbos: klik Hadir, rekap & honor jalan sendiri',
            'benefit' => 'Scan QR atau klik Hadir. Rekap kehadiran dan honor pengajar terhitung sendiri setiap bulan.',
        ],
        'pengajar' => [
            'youtube' => 'rL9-0AbKw80',
            'poster' => 'videos/cuplikan-pengajar.jpg',
            'duration' => '0:13',
            'icon' => 'person-workspace',
            'role' => 'Untuk pengajar',
            'label' => 'Pengajar',
            'title' => 'bizbos untuk pengajar: jadwal & absen murid dari HP',
            'benefit' => 'Buka HP, jadwal mengajar hari ini sudah siap. Absen murid cukup satu ketukan.',
        ],
        'murid' => [
            'youtube' => '9n-w-fpKGqY',
            'poster' => 'videos/cuplikan-murid.jpg',
            'duration' => '0:18',
            'icon' => 'phone',
            'role' => 'Untuk murid & orang tua',
            'label' => 'Orang tua & murid',
            'title' => 'bizbos untuk orang tua: jadwal, kartu absen QR & bayar SPP dari HP',
            'benefit' => 'Jadwal les, kartu absen QR, dan tagihan SPP ada di HP. Bayar online, status lunas tercatat sendiri.',
        ],
    ],
];
