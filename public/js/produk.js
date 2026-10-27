// Halaman produk (Tiket, Kursus, Invoice): kata bergantian di hero, mis. "Mau bikin seminar? workshop? ...".
document.addEventListener('DOMContentLoaded', function () {
    var rotate = document.querySelector('.pd-rotate');
    if (!rotate || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var words = rotate.dataset.words.split('|'), i = 0;
    setInterval(function () {
        rotate.classList.add('is-out');
        setTimeout(function () {
            i = (i + 1) % words.length;
            rotate.textContent = words[i];
            rotate.classList.remove('is-out');
        }, 250);
    }, 2400);
});
