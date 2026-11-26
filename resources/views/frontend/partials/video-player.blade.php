{{--
    Komponen video bizbos yang SAMA dipakai di semua halaman:
    sampul 16:9 + tombol play merah besar. Saat diklik, video YouTube diputar
    di tempat (tanpa popup), jadi nyaman di HP.

    Pemakaian:
        @include('frontend.partials.video-player', ['key' => 'absensi'])
        @include('frontend.partials.video-player', ['key' => 'pengajar', 'size' => 'sm'])

    Data video: config/cuplikan.php. Script cukup dimuat sekali per halaman (@once).
--}}
@php
    $video = config('cuplikan.videos.'.$key);
    $size = $size ?? 'lg';
@endphp
@if ($video)
<div class="vp vp--{{ $size }}" data-vp data-yt="{{ $video['youtube'] }}" data-title="{{ $video['title'] }}">
    <button type="button" class="vp-poster" aria-label="Putar video: {{ $video['title'] }}">
        <img src="{{ asset($video['poster']) }}" alt="" loading="lazy" decoding="async" width="1280" height="720">
        <span class="vp-play" aria-hidden="true">
            <svg viewBox="0 0 68 48" focusable="false"><path class="vp-play-bg" d="M66.5 7.7c-.8-2.9-3-5.1-5.9-5.9C55.3.4 34 .4 34 .4S12.7.4 7.4 1.8C4.5 2.6 2.3 4.8 1.5 7.7.1 13 .1 24 .1 24s0 11 1.4 16.3c.8 2.9 3 5.1 5.9 5.9 5.3 1.4 26.6 1.4 26.6 1.4s21.3 0 26.6-1.4c2.9-.8 5.1-3 5.9-5.9C67.9 35 67.9 24 67.9 24s0-11-1.4-16.3z"/><path d="M45 24 27 14v20z" fill="#fff"/></svg>
        </span>
        <span class="vp-meta" aria-hidden="true"><i class="bi bi-youtube"></i> {{ $video['duration'] }}</span>
    </button>
</div>
@endif

@once
<script>
(function () {
    function stopAll(except) {
        document.querySelectorAll('[data-vp].is-playing').forEach(function (vp) {
            if (vp === except) return;
            var f = vp.querySelector('iframe');
            if (f) f.remove();
            vp.classList.remove('is-playing');
        });
    }
    function play(vp) {
        stopAll(vp);
        if (vp.classList.contains('is-playing')) return;
        var id = vp.getAttribute('data-yt');
        var f = document.createElement('iframe');
        f.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(id) +
            '?autoplay=1&mute=1&playsinline=1&rel=0&modestbranding=1&loop=1&playlist=' + encodeURIComponent(id);
        f.title = vp.getAttribute('data-title') || 'Video bizbos';
        f.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
        f.allowFullscreen = true;
        vp.appendChild(f);
        vp.classList.add('is-playing');
    }
    window.bizbosVideo = { play: play, stopAll: stopAll };
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-vp] .vp-poster');
        if (btn) { play(btn.closest('[data-vp]')); }
    });
})();
</script>
@endonce
