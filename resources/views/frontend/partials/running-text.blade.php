{{--
    Running text / marquee banner. Teksnya datang dari
    WebSetting::running_text (Superadmin > Web > Pengaturan > Running
    Text, kolom teks biasa — lihat App\Models\WebSetting), diulang
    berkali-kali dipisah ikon diamond kecil, lalu di-scroll otomatis
    tanpa henti pakai animasi CSS murni (bukan JS/library) dari kanan
    ke kiri.

    Section ini disembunyikan total kalau running_text kosong.

    Wrapper .running-text-wrap pakai trik "100vw breakout" yang SAMA
    persis dengan .features-slider-wrap di section Fitur Unggulan
    (lihat komentar lengkapnya di public/css/frontend.css) supaya
    nempel rata dari ujung layar ke ujung layar seperti diminta.

    Teknik infinite-scroll-nya: .running-text-track berisi DUA copy
    identik dari .running-text-group (satu asli buat ditampilkan, satu
    lagi duplikat/aria-hidden khusus buat nyambung visual), lalu
    di-geser (translateX) sejauh -50% (= lebar satu group) terus-
    menerus — begitu sampai -50% langsung "loncat" balik ke 0% tapi
    karena isinya identik, matanya tidak nangkep ada jeda/patahan sama
    sekali (ilusi scroll tanpa akhir).
--}}
@if (data_get($webSetting, 'running_text'))
    <section class="running-text-section" aria-label="{{ $webSetting['running_text'] }}">
        <div class="running-text-wrap">
            <div class="running-text-track">
                @for ($group = 0; $group < 2; $group++)
                    <div class="running-text-group" @if ($group === 1) aria-hidden="true" @endif>
                        @for ($i = 0; $i < 8; $i++)
                            <span class="running-text-item">{{ $webSetting['running_text'] }}</span>
                            <span class="running-text-dot" aria-hidden="true"></span>
                        @endfor
                    </div>
                @endfor
            </div>
        </div>
    </section>
@endif
