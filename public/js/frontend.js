// File JS ini di-load langsung dari public/js/frontend.js (bukan lewat Vite).
// Edit, save, reload browser -> perubahan langsung terlihat, tanpa build.

document.addEventListener('DOMContentLoaded', function () {
    // Tempat menaruh script custom frontend.

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

            topbar.classList.toggle('topbar-scrolled', isScrolled);
            topbar.classList.toggle('navbar-light', isScrolled);
            topbar.classList.toggle('navbar-dark', !isScrolled);
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
        };

        tabs.addEventListener('shown.bs.tab', moveIndicator);
        window.addEventListener('resize', moveIndicator);
        window.addEventListener('load', moveIndicator);
        moveIndicator();
    });
});
