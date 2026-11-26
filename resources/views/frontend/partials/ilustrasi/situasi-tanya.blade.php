{{-- Ilustrasi "HP tidak berhenti bunyi ditanya jadwal" untuk kenal-situasi.blade.php. --}}
<svg viewBox="0 0 280 170" class="situasi-art" aria-hidden="true">
    <rect width="280" height="170" rx="18" fill="#e5f6ec"/>
    {{-- getaran --}}
    <g stroke="#23885a" stroke-width="3" stroke-linecap="round" fill="none" class="sa-vibe">
        <path d="M70 60q-10 25 0 50"/><path d="M56 50q-14 35 0 70"/>
        <path d="M210 60q10 25 0 50"/><path d="M224 50q14 35 0 70"/>
    </g>
    {{-- HP bergetar --}}
    <g class="sa-shake">
        <rect x="96" y="14" width="88" height="146" rx="16" fill="#1f1a17"/>
        <rect x="102" y="24" width="76" height="128" rx="10" fill="#2f3549"/>
        <text x="140" y="46" text-anchor="middle" font-size="15" font-weight="700" fill="#fff">21.30</text>
    </g>
    {{-- tumpukan notifikasi --}}
    <g font-size="7" fill="#1f1a17">
        <g class="sa-pop" style="animation-delay:0s">
            <rect x="40" y="58" width="150" height="26" rx="8" fill="#fff"/>
            <circle cx="53" cy="71" r="6" fill="#25a35a"/>
            <text x="64" y="68" font-weight="700">Mama Kevin</text>
            <text x="64" y="78" fill="#6b6461">Kak, besok les jam berapa ya?</text>
        </g>
        <g class="sa-pop" style="animation-delay:.4s">
            <rect x="72" y="90" width="160" height="26" rx="8" fill="#fff"/>
            <circle cx="85" cy="103" r="6" fill="#25a35a"/>
            <text x="96" y="100" font-weight="700">Papa Salsa</text>
            <text x="96" y="110" fill="#6b6461">Kak, minggu ini kelasnya libur?</text>
        </g>
        <g class="sa-pop" style="animation-delay:.8s">
            <rect x="48" y="122" width="150" height="26" rx="8" fill="#fff"/>
            <circle cx="61" cy="135" r="6" fill="#25a35a"/>
            <text x="72" y="132" font-weight="700">Bunda Aira</text>
            <text x="72" y="142" fill="#6b6461">Kak, jadwalnya dikirim lagi dong</text>
        </g>
    </g>
    {{-- badge --}}
    <g class="sa-pulse">
        <circle cx="186" cy="20" r="15" fill="#db5b4c"/>
        <text x="186" y="24.5" text-anchor="middle" font-size="11" font-weight="700" fill="#fff">99+</text>
    </g>
</svg>
