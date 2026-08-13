<footer class="site-footer">
    <div class="container py-5">
        <div class="row gy-4">
            {{--
                Kolom-kolom menu — datanya App\Models\WebFooter dari
                Superadmin > Web > Footer, dikelompokkan per group_name
                lewat App\View\Composers\FooterComposer ($footerGroups).
                Isi kolom (link-link di dalamnya) sepenuhnya diatur dari
                situ, tidak ada yang di-hardcode di sini.
            --}}
            @foreach ($footerGroups as $group)
                <div class="col-6 {{ $group['column_width'] }} footer-col">
                    <h6 class="footer-col-title">{{ $group['name'] }}</h6>
                    <ul class="list-unstyled footer-link-list">
                        @foreach ($group['items'] as $item)
                            <li>
                                <a href="{{ $item['link'] }}" @if ($item['target_blank']) target="_blank" rel="noopener" @endif>
                                    {{ $item['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            {{-- Link tanpa group_name (berdiri sendiri, bukan bagian kolom manapun) --}}
            @if ($footerUngrouped->isNotEmpty())
                <div class="col-6 col-md-3 footer-col">
                    <ul class="list-unstyled footer-link-list">
                        @foreach ($footerUngrouped as $item)
                            <li>
                                <a href="{{ $item['link'] }}" @if ($item['target_blank']) target="_blank" rel="noopener" @endif>
                                    {{ $item['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{--
                Newsletter — TAMPILAN SAJA. Belum terhubung ke penyimpanan/
                endpoint apapun (belum ada tabel subscriber di backend),
                jadi submit form ini saat ini tidak melakukan apa-apa.
                Kasih tahu kalau mau ini benar-benar berfungsi.
            --}}
            <div class="col-12 col-lg-4 ms-lg-auto footer-newsletter">
                <p class="mb-2">Explore with us! Sign up to receive exclusive access to product drops, company news, and more.</p>
                <form class="d-flex gap-2 flex-wrap" onsubmit="return false;">
                    <input type="email" class="form-control footer-newsletter-input" placeholder="Email" style="max-width: 280px;">
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Submit</button>
                </form>

                {{-- $webSetting datang dari App\View\Composers\WebSettingComposer --}}
                @if (data_get($webSetting, 'address') || data_get($webSetting, 'handphone') || data_get($webSetting, 'email'))
                    <div class="small mt-3 footer-contact">
                        @if (data_get($webSetting, 'address'))
                            <p class="mb-1">{{ $webSetting['address'] }}</p>
                        @endif

                        <p class="mb-0">
                            @if (data_get($webSetting, 'handphone'))
                                <a href="tel:{{ $webSetting['handphone'] }}">{{ $webSetting['handphone'] }}</a>
                            @endif

                            @if (data_get($webSetting, 'handphone') && data_get($webSetting, 'email'))
                                &nbsp;&middot;&nbsp;
                            @endif

                            @if (data_get($webSetting, 'email'))
                                <a href="mailto:{{ $webSetting['email'] }}">{{ $webSetting['email'] }}</a>
                            @endif
                        </p>
                    </div>
                @endif

                @if (data_get($webSetting, 'gmaps'))
                    <div class="ratio ratio-21x9 mt-3 footer-gmaps">
                        <iframe src="{{ $webSetting['gmaps'] }}" style="border: 0;" allowfullscreen loading="lazy"></iframe>
                    </div>
                @endif
            </div>
        </div>

        <hr class="footer-divider">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <img src="{{ data_get($webSetting, 'logo_url') ?: asset('images/Logo.png') }}"
                    alt="Konexa" height="28">
                {{-- Di-hardcode "Konexa" (bukan config('app.name')) — sama seperti <title> di layouts/frontend.blade.php.
                     Sebelumnya teks ini ikut config('app.name', 'Konexa'), tapi fallback itu nyaris tidak pernah
                     kepakai (config('app.name') hampir selalu resolve ke string asli dari .env, bukan null), jadi
                     kalau APP_NAME di .env bukan "Konexa" (mis. masih default "Laravel" atau kepencet jadi
                     "teleios"), teks ini ikut salah tampil. Cek juga APP_NAME di file .env fe-konexa kalau masih
                     salah setelah ini. --}}
                <span class="small footer-copyright">&copy; {{ date('Y') }} Konexa. All rights reserved.</span>
            </div>

            {{--
                Tiap platform: link cuma muncul kalau *_url diisi di
                Superadmin > Web > Pengaturan > Media Sosial. Ikon pakai
                gambar custom (icon_*_url, upload) kalau diisi, fallback
                ke ikon font Bootstrap Icons kalau tidak — supaya tetap
                aman tampil meski admin belum sempat upload ikon
                custom-nya. Twitter/X belum punya kolom ikon custom
                (belum diminta), jadi selalu pakai ikon font.
            --}}
            <div class="d-flex align-items-center gap-3 flex-wrap">
                @if (data_get($webSetting, 'instagram_url'))
                    <a href="{{ $webSetting['instagram_url'] }}" target="_blank" rel="noopener" class="footer-social-icon" aria-label="Instagram">
                        @if (data_get($webSetting, 'icon_instagram_url'))
                            <img src="{{ $webSetting['icon_instagram_url'] }}" alt="Instagram" class="footer-social-icon-img">
                        @else
                            <i class="bi bi-instagram"></i>
                        @endif
                    </a>
                @endif

                @if (data_get($webSetting, 'facebook_url'))
                    <a href="{{ $webSetting['facebook_url'] }}" target="_blank" rel="noopener" class="footer-social-icon" aria-label="Facebook">
                        @if (data_get($webSetting, 'icon_facebook_url'))
                            <img src="{{ $webSetting['icon_facebook_url'] }}" alt="Facebook" class="footer-social-icon-img">
                        @else
                            <i class="bi bi-facebook"></i>
                        @endif
                    </a>
                @endif

                @if (data_get($webSetting, 'youtube_url'))
                    <a href="{{ $webSetting['youtube_url'] }}" target="_blank" rel="noopener" class="footer-social-icon" aria-label="YouTube">
                        @if (data_get($webSetting, 'icon_youtube_url'))
                            <img src="{{ $webSetting['icon_youtube_url'] }}" alt="YouTube" class="footer-social-icon-img">
                        @else
                            <i class="bi bi-youtube"></i>
                        @endif
                    </a>
                @endif

                @if (data_get($webSetting, 'tiktok_url'))
                    <a href="{{ $webSetting['tiktok_url'] }}" target="_blank" rel="noopener" class="footer-social-icon" aria-label="TikTok">
                        @if (data_get($webSetting, 'icon_tiktok_url'))
                            <img src="{{ $webSetting['icon_tiktok_url'] }}" alt="TikTok" class="footer-social-icon-img">
                        @else
                            <i class="bi bi-tiktok"></i>
                        @endif
                    </a>
                @endif

                @if (data_get($webSetting, 'twitter_url'))
                    <a href="{{ $webSetting['twitter_url'] }}" target="_blank" rel="noopener" class="footer-social-icon" aria-label="Twitter / X">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                @endif

                <span class="small footer-legal-links">
                    <a href="{{ route('frontend.terms') }}">Syarat dan Ketentuan</a>
                </span>
            </div>
        </div>
    </div>
</footer>
