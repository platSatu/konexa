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
});
