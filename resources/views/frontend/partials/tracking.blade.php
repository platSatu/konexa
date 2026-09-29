{{--
    Tracking & pixel retargeting -- semua ID diisi di Teleios (Superadmin >
    Web > Pengaturan Web), script hanya dipasang kalau ID-nya terisi.

    - Google: GA4 (G-...) & Google Ads (AW-..., remarketing YouTube/Search/
      Display) berbagi satu loader gtag.js.
    - Meta Pixel (Instagram/Facebook) & TikTok Pixel.
    - PageView otomatis dari masing-masing base code. Event tambahan lewat
      window.bizbosTrack(nama, data), dikirim ke semua platform aktif:
        view_packages -> Meta ViewContent / TikTok ViewContent / Google view_item_list
        contact_wa    -> Meta Contact     / TikTok Contact     / Google generate_lead
        lead_form     -> Meta Lead        / TikTok SubmitForm  / Google generate_lead
      Pemasangan otomatis ada di bawah: bagian #packages terlihat, klik link
      wa.me; lead_form dipanggil dari halaman Kontak setelah pesan terkirim.

    ID dicetak lewat Js::from() (JSON yang aman di dalam <script>), selain
    validasi format di Teleios.
--}}
@php
    $ga4Id = data_get($webSetting, 'google_analytics');
    $adsId = data_get($webSetting, 'google_ads_id');
    $metaPixelId = data_get($webSetting, 'meta_pixel_id');
    $tiktokPixelId = data_get($webSetting, 'tiktok_pixel_id');
    $gtagIds = array_values(array_filter([$ga4Id, $adsId]));
@endphp

@if ($gtagIds !== [])
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ urlencode($gtagIds[0]) }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        {{ \Illuminate\Support\Js::from($gtagIds) }}.forEach(function (id) { gtag('config', id); });
    </script>
@endif

@if ($metaPixelId)
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', {{ \Illuminate\Support\Js::from((string) $metaPixelId) }});
        fbq('track', 'PageView');
    </script>
@endif

@if ($tiktokPixelId)
    <script>
        !function (w, d, t) {w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=d.createElement("script");o.type="text/javascript",o.async=!0,o.src=r+"?sdkid="+e+"&lib="+t;var a=d.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
            ttq.load({{ \Illuminate\Support\Js::from((string) $tiktokPixelId) }});
            ttq.page();
        }(window, document, 'ttq');
    </script>
@endif

@if ($gtagIds !== [] || $metaPixelId || $tiktokPixelId)
    <script>
        (function () {
            var EVENTS = {
                view_packages: { meta: 'ViewContent', tiktok: 'ViewContent', google: 'view_item_list' },
                contact_wa: { meta: 'Contact', tiktok: 'Contact', google: 'generate_lead' },
                lead_form: { meta: 'Lead', tiktok: 'SubmitForm', google: 'generate_lead' }
            };

            window.bizbosTrack = function (name, data) {
                var event = EVENTS[name];
                data = data || {};
                if (!event) return;
                // TikTok/Meta minta content_id; pakai content_name (nama paket) bila tidak diisi.
                if (!data.content_id && data.content_name) data.content_id = String(data.content_name).toLowerCase().replace(/[^a-z0-9]+/g, '-');
                if (!data.content_type) data.content_type = 'product';
                try {
                    if (window.fbq) fbq('track', event.meta, data);
                    if (window.ttq) ttq.track(event.tiktok, data);
                    if (window.gtag) gtag('event', event.google, Object.assign({ method: name }, data));
                } catch (e) {}
            };

            document.addEventListener('DOMContentLoaded', function () {
                // Bagian harga terlihat (sekali per halaman).
                var packages = document.getElementById('packages');
                if (packages && 'IntersectionObserver' in window) {
                    var observer = new IntersectionObserver(function (entries) {
                        if (entries.some(function (entry) { return entry.isIntersecting; })) {
                            bizbosTrack('view_packages', { content_name: 'Paket Harga' });
                            observer.disconnect();
                        }
                    }, { threshold: 0.3 });
                    observer.observe(packages);
                }

                // Klik ke WhatsApp (tombol paket, tombol melayang, dll).
                document.addEventListener('click', function (e) {
                    var link = e.target.closest && e.target.closest('a[href*="wa.me/"]');
                    if (link) bizbosTrack('contact_wa', { content_name: link.dataset.trackName || 'WhatsApp' });
                });
            });
        })();
    </script>
@endif
