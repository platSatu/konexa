{{--
    Persetujuan cookie (lihat halaman /page/kebijakan-cookie).
    - Cookie yang diperlukan (sesi, keamanan, live chat) selalu aktif.
    - Analitik & pemasaran (Google Tag Manager, GA4, Google Ads, Meta Pixel, TikTok Pixel) HANYA dimuat
      setelah pengunjung memilih "Terima Semua". Pilihan disimpan di cookie bz_cookie_consent (1 tahun).
    - window.bizbosConsent.onGrant(fn): dipanggil oleh layout (GTM) & tracking.blade.php (pixel).
    - window.bizbosCookieSettings(): membuka banner lagi (link "Pengaturan Cookie" di footer).
    Bagian <head> (fungsi) & <body> (banner) dipisah lewat $part.
--}}
@if (($part ?? 'head') === 'head')
    <script>
        (function () {
            var NAME = 'bz_cookie_consent', waiting = [];
            var read = function () { var m = document.cookie.match(/(?:^|; )bz_cookie_consent=(all|essential)/); return m ? m[1] : null; };
            window.bizbosConsent = {
                value: read,
                granted: function () { return read() === 'all'; },
                onGrant: function (fn) { if (read() === 'all') fn(); else waiting.push(fn); },
                set: function (value) {
                    document.cookie = NAME + '=' + value + ';path=/;max-age=31536000;SameSite=Lax' + (location.protocol === 'https:' ? ';Secure' : '');
                    if (value === 'all') { var list = waiting; waiting = []; list.forEach(function (fn) { fn(); }); }
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
        <div class="cookie-banner-actions">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-cookie="essential">Hanya yang Diperlukan</button>
            <button type="button" class="btn btn-sm btn-primary" data-cookie="all">Terima Semua</button>
        </div>
    </div>
    <style>
        .cookie-banner { position: fixed; left: 1rem; right: 1rem; bottom: 1rem; z-index: 1080; max-width: 760px; margin: 0 auto; display: flex; flex-wrap: wrap; align-items: center; gap: .75rem 1.25rem; padding: 1rem 1.25rem; background: #fff; border-radius: 1rem; box-shadow: 0 12px 40px rgba(16, 24, 40, .18); font-size: .9rem; }
        .cookie-banner[hidden] { display: none; }
        .cookie-banner-text { flex: 1 1 320px; display: flex; flex-direction: column; gap: .25rem; color: #444; }
        .cookie-banner-text strong { color: #111; }
        .cookie-banner-actions { display: flex; gap: .5rem; flex-wrap: wrap; }
        @media (max-width: 575.98px) { .cookie-banner { left: .5rem; right: .5rem; bottom: .5rem; } .cookie-banner-actions { width: 100%; } .cookie-banner-actions .btn { flex: 1; } }
    </style>
    <script>
        (function () {
            var banner = document.getElementById('cookie-banner');
            if (!banner || !window.bizbosConsent) return;
            window.bizbosCookieSettings = function () { banner.hidden = false; };
            if (!window.bizbosConsent.value()) banner.hidden = false;
            banner.addEventListener('click', function (event) {
                var button = event.target.closest('[data-cookie]');
                if (!button) return;
                window.bizbosConsent.set(button.dataset.cookie);
                banner.hidden = true;
                // Sebelumnya setuju lalu menolak: pelacak yang sudah dimuat baru benar-benar berhenti setelah halaman dimuat ulang.
                if (button.dataset.cookie === 'essential' && window.bizbosPixelsLoaded) location.reload();
            });
        })();
    </script>
@endif
