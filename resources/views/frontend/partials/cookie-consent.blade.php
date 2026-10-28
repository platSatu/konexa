{{--
    Persetujuan cookie (lihat halaman /page/kebijakan-cookie).
    - Cookie yang diperlukan (sesi, keamanan, live chat) selalu aktif.
    - Dua kategori yang butuh izin: analytics (GA4) dan marketing (Google Ads, Meta Pixel, TikTok Pixel).
      Google Tag Manager hanya dimuat kalau keduanya diizinkan (isi GTM bisa analitik maupun iklan).
    - Pilihan disimpan di cookie bz_cookie_consent (1 tahun), hanya di browser pengunjung:
      all | analytics | marketing | essential.
    - window.bizbosConsent.onGrant(kategori, fn): dipanggil oleh layout (GTM, tanpa kategori = keduanya)
      & tracking.blade.php (pixel).
    - window.bizbosCookieSettings(): membuka pengaturan (link "Pengaturan Cookie" di footer).
    Bagian <head> (fungsi) & <body> (banner) dipisah lewat $part.
--}}
@if (($part ?? 'head') === 'head')
    <script>
        (function () {
            var NAME = 'bz_cookie_consent', waiting = [];
            var read = function () { var m = document.cookie.match(/(?:^|; )bz_cookie_consent=(all|analytics|marketing|essential)/); return m ? m[1] : null; };
            var has = function (category) { var value = read(); return value === 'all' || (category !== 'all' && value === category); };
            window.bizbosConsent = {
                value: read,
                has: has,
                onGrant: function (category, fn) {
                    if (typeof category === 'function') { fn = category; category = 'all'; }
                    has(category) ? fn() : waiting.push([category, fn]);
                },
                set: function (value) {
                    document.cookie = NAME + '=' + value + ';path=/;max-age=31536000;SameSite=Lax' + (location.protocol === 'https:' ? ';Secure' : '');
                    var list = waiting;
                    waiting = [];
                    list.forEach(function (item) { has(item[0]) ? item[1]() : waiting.push(item); });
                }
            };
        })();
    </script>
@else
    {{-- Bar penuh dari ujung kiri sampai kanan layar, naik dari bawah. JS di bawah tetap sama alurnya. --}}
    <div class="cookie-banner" id="cookie-banner" role="dialog" aria-live="polite" aria-label="Persetujuan cookie" hidden>
        <div class="cookie-banner-inner">
            <div class="cookie-banner-icon" aria-hidden="true">🍪</div>

            <div class="cookie-banner-text">
                <strong>Kami pakai cookie</strong>
                <span>Cookie yang diperlukan menjaga website tetap berjalan. Dengan izin Anda, kami juga memakai cookie analitik dan iklan untuk memahami kunjungan dan meningkatkan layanan.
                    <a href="{{ url('/page/kebijakan-cookie') }}">Baca Kebijakan Cookie</a>.</span>
            </div>

            <div class="cookie-banner-actions">
                <button type="button" class="btn cookie-manage" data-cookie-manage>Atur</button>
                <button type="button" class="btn cookie-btn-ghost" data-cookie="essential">Hanya yang Diperlukan</button>
                <button type="button" class="btn cookie-btn-ghost" data-cookie="custom" hidden>Simpan Pilihan</button>
                <button type="button" class="btn btn-primary" data-cookie="all">Terima Semua</button>
            </div>

            <div class="cookie-options" data-cookie-options hidden>
                <label class="cookie-option form-switch">
                    <span><b>Diperlukan</b><small>Menjaga login, keamanan, dan live chat tetap berjalan.</small></span>
                    <input class="form-check-input" type="checkbox" checked disabled aria-label="Cookie diperlukan selalu aktif">
                </label>
                <label class="cookie-option form-switch">
                    <span><b>Analitik</b><small>Membantu kami memahami halaman yang paling sering dibuka (Google Analytics).</small></span>
                    <input class="form-check-input" type="checkbox" data-category="analytics">
                </label>
                <label class="cookie-option form-switch">
                    <span><b>Pemasaran</b><small>Menampilkan informasi bizbos yang relevan di Google, Instagram/Facebook, dan TikTok.</small></span>
                    <input class="form-check-input" type="checkbox" data-category="marketing">
                </label>
            </div>
        </div>
    </div>
    <style>
        .cookie-banner { position: fixed; left: 0; right: 0; bottom: 0; z-index: 1082; color: #f3e9e4; background: #2a201d; box-shadow: 0 -18px 50px rgba(20, 12, 10, .28); font-size: .92rem; animation: cookie-up .7s cubic-bezier(.22, 1, .36, 1) both; }
        /* Garis tipis warna brand di tepi atas, dari ujung ke ujung. */
        .cookie-banner::before { content: ""; position: absolute; inset: 0 0 auto; height: 3px; background: linear-gradient(90deg, var(--bs-primary), #f2a65a, var(--bs-primary)); }
        .cookie-banner[hidden], .cookie-banner [hidden] { display: none !important; }
        .cookie-banner-inner { max-width: 1320px; margin: 0 auto; padding: 1.25rem 1.5rem; display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 1rem 1.5rem; }
        .cookie-banner-icon { width: 3.25rem; height: 3.25rem; display: grid; place-items: center; font-size: 1.6rem; border-radius: 50%; background: rgba(var(--bs-primary-rgb), .18); box-shadow: inset 0 0 0 1px rgba(var(--bs-primary-rgb), .45); animation: cookie-wobble 2.4s ease-in-out .8s 2; }
        .cookie-banner-text { display: flex; flex-direction: column; gap: .2rem; line-height: 1.55; min-width: 0; }
        .cookie-banner-text strong { color: #fff; font-size: 1.05rem; }
        .cookie-banner-text span { color: #d6c8c2; }
        .cookie-banner-text a { color: #ffb4a8; text-underline-offset: 3px; }
        .cookie-banner-actions { display: flex; gap: .5rem; flex-wrap: wrap; align-items: center; justify-content: flex-end; }
        .cookie-banner-actions .btn { border-radius: 999px; padding: .55rem 1.15rem; font-size: .9rem; white-space: nowrap; }
        .cookie-manage { color: #d6c8c2; text-decoration: underline; text-underline-offset: 3px; border: 0; }
        .cookie-manage:hover { color: #fff; }
        .cookie-btn-ghost { color: #fff; border: 1px solid rgba(255, 255, 255, .35); background: transparent; }
        .cookie-btn-ghost:hover { color: #fff; background: rgba(255, 255, 255, .1); border-color: rgba(255, 255, 255, .6); }
        .cookie-options { grid-column: 1 / -1; display: grid; grid-template-columns: repeat(3, 1fr); gap: .75rem; animation: cookie-fade .35s ease both; }
        .cookie-option { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .8rem 1rem; border-radius: .9rem; background: rgba(255, 255, 255, .06); border: 1px solid rgba(255, 255, 255, .1); cursor: pointer; }
        .cookie-option span { display: flex; flex-direction: column; gap: .15rem; }
        .cookie-option b { color: #fff; }
        .cookie-option small { color: #bfb0aa; line-height: 1.45; }
        .cookie-option.form-switch { padding: .8rem 1rem; }
        .cookie-option .form-check-input { flex-shrink: 0; width: 2.4em; height: 1.3em; margin: 0; float: none; }
        .cookie-option .form-check-input:checked { background-color: var(--bs-primary); border-color: var(--bs-primary); }
        /* Saat banner tampil, tombol melayang (chat, WhatsApp, kembali ke atas) naik di atasnya lewat --cookie-h (public/css/chat-widget.css). */
        @keyframes cookie-up { from { transform: translateY(100%); } to { transform: translateY(0); } }
        @keyframes cookie-fade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
        @keyframes cookie-wobble { 0%, 100% { transform: rotate(0); } 20% { transform: rotate(-14deg); } 40% { transform: rotate(10deg); } 60% { transform: rotate(-6deg); } 80% { transform: rotate(3deg); } }
        @media (max-width: 991.98px) {
            .cookie-banner-inner { grid-template-columns: auto 1fr; }
            /* Di layar kecil, pilihan kategori tampil dulu baru tombolnya. */
            .cookie-banner-actions { grid-column: 1 / -1; justify-content: stretch; order: 2; }
            .cookie-options { order: 1; }
            .cookie-options { grid-template-columns: 1fr; }
        }
        @media (max-width: 575.98px) {
            .cookie-banner-inner { padding: 1rem; gap: .75rem 1rem; }
            .cookie-banner-icon { width: 2.6rem; height: 2.6rem; font-size: 1.25rem; }
            .cookie-banner-text span { font-size: .85rem; }
            .cookie-banner-actions .btn { flex: 1 1 auto; }
            .cookie-manage { flex: 0 0 auto !important; }
            .cookie-banner { max-height: 85dvh; overflow-y: auto; }
        }
        @media (prefers-reduced-motion: reduce) { .cookie-banner, .cookie-banner-icon, .cookie-options { animation: none; } }
    </style>
    <script>
        (function () {
            var banner = document.getElementById('cookie-banner');
            var consent = window.bizbosConsent;
            if (!banner || !consent) return;
            var options = banner.querySelector('[data-cookie-options]');
            var boxes = banner.querySelectorAll('[data-category]');

            // Tinggi banner -> --cookie-h, supaya tombol chat/WhatsApp tidak tertutup.
            var fit = function () { document.documentElement.style.setProperty('--cookie-h', banner.offsetHeight + 'px'); };
            var show = function () { banner.hidden = false; document.body.classList.add('cookie-open'); fit(); };
            var hide = function () { banner.hidden = true; document.body.classList.remove('cookie-open'); document.documentElement.style.setProperty('--cookie-h', '0px'); };
            if (window.ResizeObserver) new ResizeObserver(fit).observe(banner);

            // Tampilkan pilihan per kategori, dicentang sesuai pilihan sekarang.
            var manage = function () {
                boxes.forEach(function (box) { box.checked = consent.has(box.dataset.category); });
                options.hidden = false;
                banner.querySelector('[data-cookie-manage]').hidden = true;
                banner.querySelector('[data-cookie=custom]').hidden = false;
            };

            window.bizbosCookieSettings = function () { manage(); show(); };
            if (!consent.value()) show();

            banner.addEventListener('click', function (event) {
                if (event.target.closest('[data-cookie-manage]')) return manage();
                var button = event.target.closest('[data-cookie]');
                if (!button) return;

                var value = button.dataset.cookie;
                if (value === 'custom') {
                    var analytics = banner.querySelector('[data-category=analytics]').checked;
                    var marketing = banner.querySelector('[data-category=marketing]').checked;
                    value = analytics && marketing ? 'all' : (analytics ? 'analytics' : (marketing ? 'marketing' : 'essential'));
                }

                // Izin yang dicabut: pelacak yang sudah dimuat baru benar-benar berhenti setelah halaman dimuat ulang.
                var revoked = ['analytics', 'marketing'].some(function (category) {
                    return consent.has(category) && value !== 'all' && value !== category;
                });
                consent.set(value);
                hide();
                if (revoked && (window.bizbosPixelsLoaded || window.google_tag_manager)) location.reload();
            });
        })();
    </script>
@endif
