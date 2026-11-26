{{--
    Section "Lihat bizbos bekerja" (beranda). Tab per fitur/peran + pemutar video
    besar (komponen video-player). Data dari config/cuplikan.php.
    - Desktop: daftar tab di kiri, video di kanan.
    - HP: tab jadi tombol geser horizontal di atas video.
    Tombol lain bisa membuka tab tertentu: <a href="#lihat-bizbos" data-video-goto="absensi">...</a>
--}}
@php
    $videos = config('cuplikan.videos', []);
    $first = array_key_first($videos);
@endphp
@if ($videos)
<section id="lihat-bizbos" class="lb-section" data-reveal>
    <div class="container">
        <div class="text-center mb-4 mb-lg-5">
            <span class="lb-eyebrow"><i class="bi bi-play-circle"></i> Lihat langsung</span>
            <h2 class="lb-title">Satu aplikasi, semua orang di tempat kursus terbantu</h2>
            <p class="lb-lead mx-auto">Pemilik, admin, pengajar, sampai orang tua murid. Pilih salah satu, lalu lihat sendiri betapa ringannya.</p>
        </div>

        <div class="lb-wrap">
            <div class="lb-tabs" role="tablist" aria-label="Pilih cuplikan">
                @foreach ($videos as $key => $video)
                    <button type="button" role="tab" class="lb-tab {{ $key === $first ? 'is-active' : '' }}"
                        id="lb-tab-{{ $key }}" aria-controls="lb-panel-{{ $key }}"
                        aria-selected="{{ $key === $first ? 'true' : 'false' }}" data-lb-tab="{{ $key }}">
                        <span class="lb-tab-icon"><i class="bi bi-{{ $video['icon'] }}"></i></span>
                        <span class="lb-tab-text">
                            <small>{{ $video['role'] }}</small>
                            <strong>{{ $video['label'] }}</strong>
                        </span>
                    </button>
                @endforeach
            </div>

            <div class="lb-stage">
                @foreach ($videos as $key => $video)
                    <div class="lb-panel" role="tabpanel" id="lb-panel-{{ $key }}" aria-labelledby="lb-tab-{{ $key }}" @if ($key !== $first) hidden @endif>
                        @include('frontend.partials.video-player', ['key' => $key, 'size' => 'lg'])
                        <p class="lb-benefit"><i class="bi bi-check-circle-fill"></i> {{ $video['benefit'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lb-actions">
            <a href="https://app.bizbos.id/auth/register" class="btn btn-primary btn-lg hero-btn">Coba Gratis 30 Hari <i class="bi bi-arrow-right"></i></a>
            <a href="{{ config('cuplikan.channel_url') }}" target="_blank" rel="noopener" class="lb-yt-link"><i class="bi bi-youtube"></i> Tonton semua di YouTube</a>
        </div>
    </div>
</section>

<script>
(function () {
    var root = document.getElementById('lihat-bizbos');
    if (!root) return;
    function select(key, focus) {
        var tab = root.querySelector('[data-lb-tab="' + key + '"]');
        if (!tab) return false;
        if (window.bizbosVideo) window.bizbosVideo.stopAll();
        root.querySelectorAll('[data-lb-tab]').forEach(function (t) {
            var on = t === tab;
            t.classList.toggle('is-active', on);
            t.setAttribute('aria-selected', on ? 'true' : 'false');
            t.tabIndex = on ? 0 : -1;
        });
        root.querySelectorAll('.lb-panel').forEach(function (p) { p.hidden = p.id !== 'lb-panel-' + key; });
        // di HP: geser tab aktif ke tengah
        var bar = root.querySelector('.lb-tabs');
        if (bar.scrollWidth > bar.clientWidth) {
            bar.scrollTo({ left: tab.offsetLeft - (bar.clientWidth - tab.offsetWidth) / 2, behavior: 'smooth' });
        }
        if (focus) tab.focus();
        return true;
    }
    root.addEventListener('click', function (e) {
        var tab = e.target.closest('[data-lb-tab]');
        if (tab) select(tab.getAttribute('data-lb-tab'));
    });
    root.querySelector('.lb-tabs').addEventListener('keydown', function (e) {
        if (['ArrowRight', 'ArrowLeft', 'ArrowDown', 'ArrowUp'].indexOf(e.key) < 0) return;
        e.preventDefault();
        var tabs = [].slice.call(root.querySelectorAll('[data-lb-tab]'));
        var i = tabs.indexOf(document.activeElement);
        var n = (e.key === 'ArrowRight' || e.key === 'ArrowDown') ? i + 1 : i - 1;
        n = (n + tabs.length) % tabs.length;
        select(tabs[n].getAttribute('data-lb-tab'), true);
    });
    // tombol dari bagian lain halaman, mis. kartu "rekap absensi"
    document.addEventListener('click', function (e) {
        var go = e.target.closest('[data-video-goto]');
        if (!go) return;
        if (select(go.getAttribute('data-video-goto'))) {
            e.preventDefault();
            root.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
})();
</script>
@endif
