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

    // Features slider (frontend/partials/features.blade.php) — geser
    // horizontalnya sendiri sudah jalan native lewat CSS scroll-snap
    // (lihat public/css/frontend.css), JS di bawah nambahin tiga hal:
    // (1) tombol panah kiri/kanan (desktop) yang scroll per-1 kartu,
    // (2) dot indicator (mobile) yang nunjukin kartu mana yang lagi aktif,
    // disinkronkan dari posisi scroll asli (bukan sebaliknya), dan
    // (3) drag-to-scroll pakai mouse (klik-tahan-geser, kursor berubah
    // jadi ikon tangan "grab"/"grabbing" — lihat .features-slider &
    // .features-slider.is-dragging di frontend.css) untuk desktop, di
    // luar touch-scroll native yang sudah otomatis jalan di mobile.
    var featuresSlider = document.getElementById('featuresSlider');

    if (featuresSlider) {
        var featureSlides = featuresSlider.querySelectorAll('.feature-slide');
        var dotsWrap = document.getElementById('featuresSliderDots');
        var prevBtn = document.querySelector('.features-slider-btn--prev');
        var nextBtn = document.querySelector('.features-slider-btn--next');

        if (dotsWrap && featureSlides.length > 1) {
            featureSlides.forEach(function (slide, index) {
                var dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'features-slider-dot' + (index === 0 ? ' active' : '');
                dot.setAttribute('aria-label', 'Ke fitur ' + (index + 1));
                dot.addEventListener('click', function () {
                    slide.scrollIntoView({ behavior: 'smooth', inline: 'start', block: 'nearest' });
                });
                dotsWrap.appendChild(dot);
            });
        }

        var updateActiveFeatureDot = function () {
            if (!dotsWrap) {
                return;
            }

            var dots = dotsWrap.querySelectorAll('.features-slider-dot');
            var sliderLeft = featuresSlider.getBoundingClientRect().left;
            var closestIndex = 0;
            var closestDistance = Infinity;

            featureSlides.forEach(function (slide, index) {
                var distance = Math.abs(slide.getBoundingClientRect().left - sliderLeft);
                if (distance < closestDistance) {
                    closestDistance = distance;
                    closestIndex = index;
                }
            });

            dots.forEach(function (dot, index) {
                dot.classList.toggle('active', index === closestIndex);
            });
        };

        var scrollFeaturesByOneCard = function (direction) {
            var firstSlide = featureSlides[0];
            if (!firstSlide) {
                return;
            }

            // Lebar kartu + gap (gap cuma ada di breakpoint >=768px,
            // lihat frontend.css — di situ juga tombol panah ini
            // disembunyikan di mobile, jadi aman diasumsikan 20px gap).
            var amount = firstSlide.getBoundingClientRect().width + 20;
            featuresSlider.scrollBy({ left: amount * direction, behavior: 'smooth' });
        };

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                scrollFeaturesByOneCard(-1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                scrollFeaturesByOneCard(1);
            });
        }

        featuresSlider.addEventListener('scroll', function () {
            window.requestAnimationFrame(updateActiveFeatureDot);
        }, { passive: true });

        updateActiveFeatureDot();

        // Drag-to-scroll pakai mouse. Native touch scroll (HP/trackpad)
        // tetap jalan seperti biasa lewat overflow-x:auto bawaan browser
        // — ini cuma nambahin dukungan yang sama buat mouse di desktop,
        // karena mouse tidak punya gestur swipe/touch.
        var isDragging = false;
        var dragMoved = false;
        var dragStartX = 0;
        var scrollStartLeft = 0;
        var DRAG_MOVE_THRESHOLD = 5; // px — di bawah ini dianggap klik biasa, bukan drag

        var startDrag = function (pageX) {
            isDragging = true;
            dragMoved = false;
            dragStartX = pageX;
            scrollStartLeft = featuresSlider.scrollLeft;
            featuresSlider.classList.add('is-dragging');
        };

        var moveDrag = function (pageX) {
            if (!isDragging) {
                return;
            }

            var walk = pageX - dragStartX;
            if (Math.abs(walk) > DRAG_MOVE_THRESHOLD) {
                dragMoved = true;
            }

            featuresSlider.scrollLeft = scrollStartLeft - walk;
        };

        var endDrag = function () {
            isDragging = false;
            featuresSlider.classList.remove('is-dragging');
        };

        featuresSlider.addEventListener('mousedown', function (e) {
            startDrag(e.pageX);
        });

        featuresSlider.addEventListener('mousemove', function (e) {
            if (!isDragging) {
                return;
            }
            // preventDefault selama drag aktif supaya browser tidak ikut
            // nge-select teks kartu di sepanjang jalur mouse.
            e.preventDefault();
            moveDrag(e.pageX);
        });

        window.addEventListener('mouseup', endDrag);
        featuresSlider.addEventListener('mouseleave', endDrag);

        // Cegah ghost-drag bawaan browser untuk gambar di dalam kartu
        // (drag native pada <img> akan bentrok dengan drag-to-scroll di
        // atas kalau tidak dicegah).
        featuresSlider.addEventListener('dragstart', function (e) {
            e.preventDefault();
        });

        // Kalau mouse habis dipakai buat drag (bukan klik biasa), cegah
        // event click di bawahnya supaya elemen apa pun di dalam kartu
        // (mis. link, kalau nanti ditambahkan) tidak "kepencet" tidak
        // sengaja pas user cuma geser slider.
        featuresSlider.addEventListener('click', function (e) {
            if (dragMoved) {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true);
    }
});
