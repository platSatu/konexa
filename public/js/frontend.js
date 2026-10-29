// File JS ini di-load langsung dari public/js/frontend.js (bukan lewat Vite).
// Edit, save, reload browser -> perubahan langsung terlihat, tanpa build.

document.addEventListener('DOMContentLoaded', function () {
    // Tempat menaruh script custom frontend.

    // Tombol "kembali ke atas" (partials/chat-widget.blade.php): muncul setelah
    // scroll 400px; body.bz-scrolled juga menaikkan tombol chat & WhatsApp
    // (lihat public/css/chat-widget.css) supaya tidak bertumpuk.
    var scrollTopButton = document.querySelector('[data-scroll-top]');

    if (scrollTopButton) {
        var toggleScrollTop = function () {
            document.body.classList.toggle('bz-scrolled', window.scrollY > 400);
        };

        window.addEventListener('scroll', toggleScrollTop, { passive: true });
        toggleScrollTop();

        scrollTopButton.addEventListener('click', function () {
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
        });
    }

    // Topbar homepage (frontend/partials/topbar.blade.php, class
    // .site-topbar--overlay): transparan + teks putih (navbar-dark)
    // selama di paling atas, jadi SOLID putih + teks gelap
    // (navbar-light) begitu halaman di-scroll, supaya nama menu tetap
    // kelihatan jelas menempel di atas konten hero. Halaman lain (tanpa
    // hero) tidak punya class .site-topbar--overlay jadi listener ini
    // otomatis tidak ngapa-ngapain di sana.
    var topbar = document.getElementById('siteTopbar');

    if (topbar && topbar.classList.contains('site-topbar--overlay')) {
        var SCROLL_THRESHOLD = 40;

        var updateTopbarOnScroll = function () {
            var isScrolled = window.scrollY > SCROLL_THRESHOLD;

            var onLight = topbar.classList.contains('site-topbar--on-light'); // hero terang: teks menu selalu gelap

            topbar.classList.toggle('topbar-scrolled', isScrolled);
            topbar.classList.toggle('navbar-light', isScrolled || onLight);
            topbar.classList.toggle('navbar-dark', !isScrolled && !onLight);
        };

        updateTopbarOnScroll();
        window.addEventListener('scroll', updateTopbarOnScroll, { passive: true });
    }

    // Animasi muncul saat di-scroll (lihat frontend.css bagian "Animasi"):
    // section ber-[data-reveal] diberi .is-visible sekali saat pertama
    // terlihat. Dilewati kalau pengunjung memilih "kurangi gerakan".
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var revealSections = document.querySelectorAll('[data-reveal]');

    if (!reduceMotion && 'IntersectionObserver' in window && revealSections.length) {
        document.documentElement.classList.add('reveal-ready');

        var revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        revealSections.forEach(function (section) {
            revealObserver.observe(section);
        });
    }

    // Indikator tab yang meluncur ke tab aktif (tab paket, [data-sliding-tabs]).
    document.querySelectorAll('[data-sliding-tabs]').forEach(function (tabs) {
        var indicator = document.createElement('li');
        indicator.className = 'package-tabs-indicator';
        indicator.setAttribute('role', 'presentation');
        indicator.setAttribute('aria-hidden', 'true');
        tabs.appendChild(indicator);
        tabs.classList.add('has-indicator');

        var moveIndicator = function () {
            var active = tabs.querySelector('.nav-link.active');

            if (!active) {
                return;
            }

            indicator.style.width = active.offsetWidth + 'px';
            indicator.style.height = active.offsetHeight + 'px';
            indicator.style.transform = 'translate(' + active.offsetLeft + 'px, ' + active.offsetTop + 'px)';

            // Di HP tab bisa digeser: tab aktif digeser ke tengah supaya selalu terlihat
            // (hanya menggeser deretan tab, halaman tidak ikut bergulir).
            if (tabs.scrollWidth > tabs.clientWidth) {
                tabs.scrollTo({
                    left: active.offsetLeft - (tabs.clientWidth - active.offsetWidth) / 2,
                    behavior: 'smooth'
                });
            }
        };

        tabs.addEventListener('shown.bs.tab', moveIndicator);
        window.addEventListener('resize', moveIndicator);
        window.addEventListener('load', moveIndicator);
        moveIndicator();
    });
});
