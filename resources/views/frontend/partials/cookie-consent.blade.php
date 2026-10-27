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
    <div class="cookie-banner" id="cookie-banner" role="dialog" aria-live="polite" aria-label="Persetujuan cookie" hidden>
        <div class="cookie-banner-text">
            <strong>Kami pakai cookie 🍪</strong>
            <span>Cookie yang diperlukan menjaga website tetap berjalan. Dengan izin Anda, kami juga memakai cookie analitik dan iklan untuk memahami kunjungan dan meningkatkan layanan.
                <a href="{{ url('/page/kebijakan-cookie') }}">Baca Kebijakan Cookie</a>.</span>
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

        <div class="cookie-banner-actions">
            <button type="button" class="btn btn-sm btn-link cookie-manage" data-cookie-manage>Atur</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-cookie="essential">Hanya yang Diperlukan</button>
            <button type="button" class="btn btn-sm btn-outline-primary" data-cookie="custom" hidden>Simpan Pilihan</button>
            <button type="button" class="btn btn-sm btn-primary" data-cookie="all">Terima Semua</button>
        </div>
    </div>
    <style>
        .cookie-banner { position: fixed; left: 1rem; right: 1rem; bottom: 1rem; z-index: 1080; max-width: 760px; margin: 0 auto; display: flex; flex-wrap: wrap; align-items: center; gap: .75rem 1.25rem; padding: 1rem 1.25rem; background: #fff; border-radius: 1rem; box-shadow: 0 12px 40px rgba(16, 24, 40, .18); font-size: .9rem; }
        .cookie-banner[hidden], .cookie-banner [hidden] { display: none !important; }
        .cookie-banner-text { flex: 1 1 320px; display: flex; flex-direction: column; gap: .25rem; color: #444; }
        .cookie-banner-text strong { color: #111; }
        .cookie-options { flex: 1 1 100%; display: grid; gap: .5rem; }
        .cookie-option { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .65rem .85rem; border-radius: .75rem; background: #faf6f4; cursor: pointer; }
        .cookie-option span { display: flex; flex-direction: column; }
        .cookie-option small { color: #6b6461; }
        .cookie-option.form-switch { padding: .65rem .85rem; }
        .cookie-option .form-check-input { flex-shrink: 0; width: 2.4em; height: 1.3em; margin: 0; float: none; }
        .cookie-option .form-check-input:checked { background-color: var(--bs-primary); border-color: var(--bs-primary); }
        .cookie-banner-actions { display: flex; gap: .5rem; flex-wrap: wrap; align-items: center; }
        .cookie-manage { color: #6b6461; text-decoration: underline; }
        .cookie-banner [data-cookie=custom] { color: var(--bs-primary); border-color: var(--bs-primary); }
        .cookie-banner [data-cookie=custom]:hover { background: var(--bs-primary-bg-subtle); }
        @media (max-width: 575.98px) { .cookie-banner { left: .5rem; right: .5rem; bottom: .5rem; } .cookie-banner-actions { width: 100%; } .cookie-banner-actions .btn { flex: 1; } }
    </style>
    <script>
        (function () {
            var banner = document.getElementById('cookie-banner');
            var consent = window.bizbosConsent;
            if (!banner || !consent) return;
            var options = banner.querySelector('[data-cookie-options]');
            var boxes = banner.querySelectorAll('[data-category]');

            // Tampilkan pilihan per kategori, dicentang sesuai pilihan sekarang.
            var manage = function () {
                boxes.forEach(function (box) { box.checked = consent.has(box.dataset.category); });
                options.hidden = false;
                banner.querySelector('[data-cookie-manage]').hidden = true;
                banner.querySelector('[data-cookie=custom]').hidden = false;
            };

            window.bizbosCookieSettings = function () { manage(); banner.hidden = false; };
            if (!consent.value()) banner.hidden = false;

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
                banner.hidden = true;
                if (revoked && (window.bizbosPixelsLoaded || window.google_tag_manager)) location.reload();
            });
        })();
    </script>
@endif
