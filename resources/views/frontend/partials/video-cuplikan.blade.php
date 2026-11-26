{{--
    Popup cuplikan video singkat (±10 detik) dari aplikasi bizbos.
    Pemakaian tombol (di halaman mana saja):
        <button type="button" class="vc-btn" data-cuplikan="absensi"><i class="bi bi-play-circle-fill"></i> Lihat cara absen <small>10 detik</small></button>
    Lalu sertakan partial ini SEKALI di halaman: @include('frontend.partials.video-cuplikan')

    Sumber video:
      - 'youtube' : ID video YouTube (bagian setelah watch?v=). Kalau diisi, video diputar dari YouTube
                    (views ikut tercatat di channel). Kosongkan untuk memakai file mp4.
      - 'mp4'     : file cadangan di public/videos/.
    Video baru dimuat saat tombol diklik, jadi halaman tetap ringan.
--}}
@php
    $cuplikan = [
        'absensi' => [
            'title' => 'Absensi di bizbos: klik Hadir, rekap & honor jalan sendiri',
            'youtube' => '97M5Cp-Vz_Y',
            'mp4' => 'videos/cuplikan-absensi.mp4',
            'poster' => 'videos/cuplikan-absensi.jpg',
        ],
        'jadwal' => [
            'title' => 'Jadwal kelas di bizbos: semua ruangan dalam satu layar',
            'youtube' => 'lBwNK_3bwIE',
            'mp4' => 'videos/cuplikan-jadwal.mp4',
            'poster' => 'videos/cuplikan-jadwal.jpg',
        ],
    ];
    $cuplikanData = [];
    foreach ($cuplikan as $key => $item) {
        $cuplikanData[$key] = [
            'title' => $item['title'],
            'youtube' => $item['youtube'],
            'mp4' => asset($item['mp4']),
            'poster' => asset($item['poster']),
        ];
    }
@endphp
@once
<div class="vc-modal" id="vcModal" hidden role="dialog" aria-modal="true" aria-labelledby="vcTitle">
    <div class="vc-backdrop" data-vc-close></div>
    <div class="vc-dialog">
        <div class="vc-head">
            <p class="vc-title" id="vcTitle"></p>
            <button type="button" class="vc-close" data-vc-close aria-label="Tutup video"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="vc-frame" id="vcFrame"></div>
        <div class="vc-foot">
            <span>Ingin melihatnya langsung di tempat kursus Anda?</span>
            <a href="https://app.bizbos.id/auth/register" class="vc-cta">Coba Gratis 30 Hari <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>
<script>
(function () {
    var data = @json($cuplikanData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    var modal = document.getElementById('vcModal');
    var frame = document.getElementById('vcFrame');
    var title = document.getElementById('vcTitle');
    var lastBtn = null;

    function open(key, btn) {
        var item = data[key];
        if (!item) return;
        lastBtn = btn;
        title.textContent = item.title;
        if (item.youtube) {
            frame.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + encodeURIComponent(item.youtube) +
                '?autoplay=1&mute=1&rel=0&modestbranding=1&playsinline=1&loop=1&playlist=' + encodeURIComponent(item.youtube) +
                '" title="' + item.title.replace(/"/g, '&quot;') + '" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>';
        } else {
            frame.innerHTML = '<video src="' + item.mp4 + '" poster="' + item.poster + '" autoplay muted loop playsinline controls preload="auto"></video>';
        }
        modal.hidden = false;
        document.body.classList.add('vc-open');
        modal.querySelector('.vc-close').focus();
    }

    function close() {
        modal.hidden = true;
        frame.innerHTML = '';
        document.body.classList.remove('vc-open');
        if (lastBtn) lastBtn.focus();
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-cuplikan]');
        if (btn) { e.preventDefault(); open(btn.getAttribute('data-cuplikan'), btn); return; }
        if (e.target.closest('[data-vc-close]')) close();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.hidden) close();
    });
})();
</script>
@endonce
