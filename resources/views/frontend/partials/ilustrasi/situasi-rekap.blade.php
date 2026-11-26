{{-- Ilustrasi "rekap absensi tengah malam" untuk kenal-situasi.blade.php. Murni SVG, warna senada brand. --}}
<svg viewBox="0 0 280 170" class="situasi-art" aria-hidden="true">
    <defs><clipPath id="sa-rekap-clip"><rect width="280" height="170" rx="18"/></clipPath></defs>
    <g clip-path="url(#sa-rekap-clip)">
        <rect width="280" height="170" fill="#2b2f4a"/>
        {{-- jendela malam --}}
        <rect x="20" y="16" width="74" height="58" rx="8" fill="#3a3f63"/>
        <circle cx="58" cy="42" r="13" fill="#ffe3a3"/>
        <circle cx="65" cy="37" r="12" fill="#3a3f63"/>
        <circle cx="32" cy="28" r="1.6" fill="#fff" class="sa-twinkle"/>
        <circle cx="82" cy="58" r="1.4" fill="#fff" class="sa-twinkle" style="animation-delay:-1s"/>
        <circle cx="80" cy="26" r="1.2" fill="#fff" class="sa-twinkle" style="animation-delay:-2s"/>
        {{-- jam digital --}}
        <rect x="178" y="18" width="84" height="36" rx="9" fill="#1c1f33" stroke="#db5b4c" stroke-width="1.5"/>
        <text x="220" y="43" text-anchor="middle" font-size="19" font-weight="700" fill="#ff8a7a">23<tspan class="sa-blink">:</tspan>47</text>
        {{-- cahaya lampu meja --}}
        <ellipse cx="190" cy="132" rx="96" ry="26" fill="#ffc857" opacity=".22"/>
        <path d="M252 132 L252 84 L222 70" stroke="#cfc3bf" stroke-width="4" fill="none" stroke-linecap="round"/>
        <path d="M206 62 L236 62 L228 78 L214 78 Z" fill="#ffc857"/>
        {{-- meja --}}
        <rect x="0" y="132" width="280" height="38" fill="#5b4a43"/>
        {{-- tumpukan buku absen --}}
        <rect x="26" y="118" width="92" height="14" rx="3" fill="#db5b4c"/>
        <rect x="32" y="104" width="86" height="14" rx="3" fill="#ffc857"/>
        <rect x="22" y="90" width="90" height="14" rx="3" fill="#8fb3e0"/>
        <rect x="30" y="76" width="84" height="14" rx="3" fill="#f4ece9" transform="rotate(-4 72 83)"/>
        <text x="72" y="114" text-anchor="middle" font-size="8" font-weight="700" fill="#7a5a00">ABSEN OKT</text>
        {{-- buku terbuka penuh coretan --}}
        <g transform="rotate(-3 172 112)">
            <rect x="128" y="94" width="88" height="40" rx="4" fill="#fff"/>
            <line x1="172" y1="94" x2="172" y2="134" stroke="#e6dcd8"/>
            <path d="M136 104h28M136 112h22M136 120h26M180 104h26M180 112h18M180 120h24" stroke="#cfc3bf" stroke-width="2" stroke-linecap="round"/>
            <path d="M150 124l8 8M158 124l-8 8M196 122l8 8M204 122l-8 8" stroke="#db5b4c" stroke-width="2" stroke-linecap="round"/>
        </g>
        {{-- kalkulator & kertas diremas --}}
        <rect x="226" y="104" width="28" height="30" rx="4" fill="#f4ece9"/>
        <rect x="230" y="108" width="20" height="7" rx="2" fill="#9fd8b5"/>
        <circle cx="20" cy="150" r="9" fill="#f4ece9"/><path d="M15 147l6 4M19 155l5-6" stroke="#cfc3bf" stroke-width="1.5"/>
        <circle cx="262" cy="154" r="8" fill="#f4ece9"/><path d="M258 151l7 5" stroke="#cfc3bf" stroke-width="1.5"/>
        <circle cx="112" cy="156" r="7" fill="#f4ece9"/>
    </g>
</svg>
