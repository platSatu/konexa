{{--
    Ilustrasi alur di sisi kanan hero (frontend/partials/hero.blade.php):
    dashboard -> modul (lapis 1), lalu Jadwal -> pengguna (lapis 2).
    Murni SVG + CSS (frontend.css bagian "Ilustrasi alur hero"), titik
    bergerak pakai <animateMotion>. Teks di sini statis, bukan dari Teleios.

    $uid wajib unik per slide carousel supaya id <path>/<symbol> tidak
    bentrok kalau hero punya lebih dari satu slide.
--}}
@php
    $icons = [
        'form' => 'M8 4h8v3H8zM6 5H5v15h14V5h-1M8 11h8M8 15h5',
        'wa' => 'M4 20l1.3-3.9A8 8 0 1 1 8 19zM9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-1.8-1.8l.8-1-1-2z',
        'cal' => 'M4 6h16v14H4zM4 10h16M8 3v4M16 3v4M8 14h3M13 14h3M8 17h3',
        'pay' => 'M3 7h18v12H3zM3 11h18M7 15h4',
        'student' => 'M2 9l10-5 10 5-10 5zM6 11v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5M22 9v6',
        'teacher' => 'M3 4h18v11H11M7 9a3 3 0 1 0 0-.01M2 21c0-3 2.2-5 5-5s5 2 5 5M14 8h4M14 11h2',
        'admin' => 'M12 3l8 3v6c0 4.5-3.4 8-8 9-4.6-1-8-4.5-8-9V6zM8.5 12l2.5 2.5 4.5-5',
    ];
    // [judul, keterangan, ikon, posisi tengah (y), warna latar ikon, warna ikon]
    $modules = [
        ['Registrasi', 'Form online', 'form', 70, '#fdecea', '#db5b4c'],
        ['WhatsApp', 'Notifikasi otomatis', 'wa', 190, '#e6f6ec', '#25a35a'],
        ['Jadwal', 'Kelas & absensi', 'cal', 310, '#fff4d9', '#c98a00'],
        ['Pembayaran', 'Tagihan & bayar', 'pay', 430, '#eaf0fb', '#3b6fd1'],
    ];
    $users = [
        ['Murid', 'Jadwal kelas', 'student', 220, '#fff4d9', '#c98a00'],
        ['Pengajar', 'Jadwal mengajar', 'teacher', 310, '#fdecea', '#db5b4c'],
        ['Admin', 'Rekap absensi', 'admin', 400, '#e9f5ee', '#2f8f5b'],
    ];
    $hubIndex = 2; // Jadwal: titik cabang lapis 2

    // Lapis 1 keluar dari dashboard (210,260); lapis 2 dari kartu Jadwal (480,y).
    $hubY = $modules[$hubIndex][3];
    $paths = [];
    foreach ($modules as $i => $m) {
        $paths["a{$i}"] = "M210,260 C245,260 245,{$m[3]} 280,{$m[3]}";
    }
    foreach ($users as $i => $u) {
        $paths["b{$i}"] = "M480,{$hubY} C518,{$hubY} 518,{$u[3]} 555,{$u[3]}";
    }

    $cards = collect($modules)->map(fn ($m, $i) => [$m, 280, 200, 64, $i === $hubIndex, $i * 1.2])
        ->merge(collect($users)->map(fn ($u, $i) => [$u, 555, 190, 60, false, 0.6 + $i * 1.2]));
@endphp
<svg class="hero-flow" viewBox="0 0 755 470" role="img"
    aria-label="Dashboard {{ config('app.name') }} terhubung ke registrasi, WhatsApp, jadwal, dan pembayaran. Jadwal tersambung ke murid, pengajar, dan admin.">
    <defs>
        @foreach ($paths as $id => $d)
            <path id="{{ $uid }}-{{ $id }}" d="{{ $d }}"/>
        @endforeach
        @foreach ($icons as $name => $d)
            <symbol id="{{ $uid }}-i-{{ $name }}" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
            </symbol>
        @endforeach
    </defs>

    @foreach ($paths as $id => $d)
        <use href="#{{ $uid }}-{{ $id }}" class="hero-flow-rail"/>
        <use href="#{{ $uid }}-{{ $id }}" class="hero-flow-line"/>
    @endforeach
    @foreach (array_keys($paths) as $i => $id)
        <circle r="5" class="hero-flow-dot">
            <animateMotion dur="{{ str_starts_with($id, 'b') ? '2s' : '2.4s' }}" begin="-{{ ($i % 4) * 0.6 }}s" repeatCount="indefinite">
                <mpath href="#{{ $uid }}-{{ $id }}"/>
            </animateMotion>
        </circle>
    @endforeach

    {{-- Dashboard --}}
    <circle cx="110" cy="260" r="105" class="hero-flow-pulse"/>
    <rect x="10" y="175" width="200" height="170" rx="18" class="hero-flow-card"/>
    <path d="M10,193 a18,18 0 0 1 18,-18 h164 a18,18 0 0 1 18,18 v16 h-200 z" fill="#f4ece9"/>
    <circle cx="30" cy="192" r="4.5" fill="#db5b4c"/>
    <circle cx="44" cy="192" r="4.5" fill="#ffc857"/>
    <circle cx="58" cy="192" r="4.5" fill="#cfc3bf"/>
    <text x="28" y="240" class="hero-flow-brand">{{ \Illuminate\Support\Str::lower(config('app.name')) }}</text>
    <text x="28" y="259" class="hero-flow-sub">Dashboard</text>
    <rect x="28" y="301" width="16" height="26" rx="4" fill="#f6b8ae"/>
    <rect x="52" y="287" width="16" height="40" rx="4" fill="#db5b4c"/>
    <rect x="76" y="307" width="16" height="20" rx="4" fill="#f6b8ae"/>
    <rect x="100" y="279" width="16" height="48" rx="4" fill="#db5b4c"/>
    <rect x="132" y="279" width="60" height="10" rx="5" fill="#f4ece9"/>
    <rect x="132" y="297" width="46" height="10" rx="5" fill="#f4ece9"/>
    <rect x="132" y="315" width="54" height="10" rx="5" fill="#ffe3a3"/>

    {{-- Modul (lapis 1) & pengguna (lapis 2) --}}
    @foreach ($cards as $card)
        @php
            [[$title, $sub, $icon, $cy, $iconBg, $iconColor], $x, $w, $h, $isHub, $delay] = $card;
        @endphp
        <g class="hero-flow-node" style="animation-delay: -{{ $delay }}s">
            <rect x="{{ $x }}" y="{{ $cy - $h / 2 }}" width="{{ $w }}" height="{{ $h }}" rx="16"
                class="hero-flow-card {{ $isHub ? 'hero-flow-card--hub' : '' }}"/>
            <circle cx="{{ $x + 34 }}" cy="{{ $cy }}" r="20" fill="{{ $iconBg }}"/>
            <use href="#{{ $uid }}-i-{{ $icon }}" x="{{ $x + 21 }}" y="{{ $cy - 13 }}" width="26" height="26" style="color: {{ $iconColor }}"/>
            <text x="{{ $x + 64 }}" y="{{ $cy - 3 }}" class="hero-flow-title">{{ $title }}</text>
            <text x="{{ $x + 64 }}" y="{{ $cy + 16 }}" class="hero-flow-sub">{{ $sub }}</text>
        </g>
    @endforeach
</svg>
