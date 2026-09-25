{{--
    Daftar package (pricing cards) — diambil dari API backend Teleios
    (App\Services\TeleiosApiService::getPackages(), sumber App\Models\
    Package + App\Http\Controllers\Api\Frontend\PackageController).
    Gaya kartu terinspirasi dari referensi harga VPS/KVM yang dikirim
    user: badge "TERPOPULER", harga besar, daftar spesifikasi dengan
    ikon, tombol beda gaya untuk paket unggulan.

    "Dinamis campur statis" (sesuai instruksi user):
    - DINAMIS (dari database lewat API, ikut berubah kalau diedit di
      Superadmin): nama paket, deskripsi, harga, durasi, daftar
      spesifikasi/limit (App\Models\PackageLimit + App\Models\
      LimitMetric — mis. jumlah broadcast/device/kontak, kalau sudah
      diisi di Superadmin > Package > kelola limit), DAN badge
      "TERPOPULER" (dari kolom `packages.is_featured`, dicentang manual
      per package di Superadmin > Package — lihat migration
      2026_08_31_120000_add_is_featured_to_packages_table.php di
      teleios). Sebelum 2026-08-31 badge ini heuristik posisi-tengah
      statis; sekarang superadmin yang menentukan langsung, boleh lebih
      dari satu package sekaligus.
    - STATIS (logika tampilan saja, tidak ada kolom database-nya):
      pemetaan ikon per jenis limit ($iconMap di bawah) dan gaya tombol
      (featured vs outline).

    Paket dikelompokkan per kombinasi layanan (category application):
    satu baris per kelompok, kolom urut Trial -> durasi pendek -> panjang.
    Pengelompokan & nama kolom dihitung di FrontendController::
    groupPackages() -- view ini hanya menampilkan $packageGroups.

    Kalau sebuah paket TIDAK punya baris PackageLimit sama sekali,
    daftar spesifikasi jatuh ke teks statis generik (fallback) supaya
    kartu tidak kosong.

    Tombol "Pilih Paket" mengarah ke WhatsApp ($webSetting['handphone'],
    lihat App\View\Composers\WebSettingComposer — partial ini baru
    didaftarkan di composer itu, lihat AppServiceProvider::boot())
    dengan pesan pre-filled menyebut nama paket. Kalau nomor WhatsApp
    belum diisi di Superadmin > Web > Pengaturan, fallback ke
    mailto: pakai $webSetting['email'] (kalau ada), lalu fallback
    terakhir ke "#" — section "Kontak" placeholder yang dulu jadi
    fallback-nya sudah dihapus dari frontend.index (lihat komentar di
    file itu), jadi TIDAK dipakai lagi sebagai anchor di sini.
--}}
@php
    // Bingkai dari Susunan Beranda (Teleios) -- judul, background, tombol.
    $section = ($section ?? []) + ['style' => '', 'is_dark' => false];
@endphp
<section id="packages" class="py-5 {{ $section['style'] === '' ? 'bg-light' : '' }} {{ $section['is_dark'] ? 'text-white' : '' }}" style="{{ $section['style'] }}">
    <div class="container">
        @include('frontend.partials.sections._heading', [
            'defaultTitle' => 'Paket Layanan',
            'defaultSubtitle' => 'Pilih paket sesuai layanan yang dibutuhkan bisnis Anda. Paket berlaku untuk setiap branch.',
        ])

        @if (empty($packageGroups))
            <p class="text-center text-muted mb-0">Paket belum tersedia saat ini.</p>
        @else
            @php
                // Pemetaan kata kunci pada LimitMetric.key -> ikon Bootstrap
                // Icons. Statis (belum ada kolom "icon" di limit_metrics).
                $iconMap = [
                    'device' => 'bi-phone',
                    'user' => 'bi-people-fill',
                    'contact' => 'bi-person-lines-fill',
                    'broadcast' => 'bi-megaphone-fill',
                    'message' => 'bi-chat-dots-fill',
                    'storage' => 'bi-hdd-fill',
                    'branch' => 'bi-diagram-3-fill',
                    'agent' => 'bi-headset',
                ];
                // Urutan spesifikasi tetap: Pengiriman -> Device -> Kontak.
                $limitDisplayOrder = ['broadcast', 'device', 'contact'];

                $waNumber = preg_replace('/\D/', '', (string) data_get($webSetting, 'handphone'));
                if ($waNumber !== '' && str_starts_with($waNumber, '0')) {
                    $waNumber = '62' . substr($waNumber, 1);
                }
                $contactEmail = data_get($webSetting, 'email');
            @endphp

            @foreach ($packageGroups as $group)
                <div class="package-group {{ $loop->last ? '' : 'mb-5' }}">
                    <div class="text-center mb-4">
                        <h3 class="h4 fw-bold mb-2">{{ $group['label'] }}</h3>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            @foreach ($group['services'] as $service)
                                <span class="badge bg-primary-subtle text-primary">{{ $service }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="row g-4 justify-content-center">
                        @foreach ($group['packages'] as $package)
                            @php
                                $isFeatured = (bool) ($package['is_featured'] ?? false);
                                $isTrial = (bool) ($package['is_trial'] ?? false);
                                $price = (float) ($package['price'] ?? 0);
                                $months = (int) ($package['months'] ?? 1);

                                $limits = [];
                                foreach ($limitDisplayOrder as $needle) {
                                    foreach ($package['limits'] ?? [] as $rawLimit) {
                                        if (str_contains(strtolower($rawLimit['limit_metric']['key'] ?? ''), $needle)) {
                                            $limits[] = $rawLimit;
                                            break;
                                        }
                                    }
                                }

                                $waHref = match (true) {
                                    $waNumber !== '' => 'https://wa.me/' . $waNumber . '?text=' . rawurlencode('Halo, saya tertarik dengan paket ' . ($package['name'] ?? '') . '. Bisa dibantu info lebih lanjut?'),
                                    ! empty($contactEmail) => 'mailto:' . $contactEmail . '?subject=' . rawurlencode('Tanya paket ' . ($package['name'] ?? '')),
                                    default => '#',
                                };
                            @endphp
                            <div class="col-12 col-md-6 col-lg-4 d-flex">
                                <div class="package-card w-100 h-100 d-flex flex-column p-4 {{ $isFeatured ? 'package-card--featured' : '' }}">
                                    @if ($isFeatured)
                                        <span class="package-badge">TERPOPULER</span>
                                    @endif

                                    <span class="badge {{ $isTrial ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} mb-2 align-self-start">
                                        {{ $package['column_label'] ?? '-' }}
                                    </span>

                                    <h5 class="package-name mb-1">{{ $package['name'] ?? '-' }}</h5>

                                    <div class="package-price mb-1">
                                        @if ($price <= 0)
                                            <span class="package-price-amount">Gratis</span>
                                        @else
                                            <span class="package-price-currency">Rp</span>
                                            <span class="package-price-amount">{{ number_format($price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <p class="package-duration text-muted small mb-4">
                                        Masa aktif {{ $package['duration'] ?? '-' }} hari
                                        @if (! $isTrial && $price > 0 && $months > 1)
                                            &middot; setara Rp {{ number_format($price / $months, 0, ',', '.') }}/bulan
                                        @endif
                                    </p>

                                    <a href="{{ $waHref }}" target="_blank" rel="noopener"
                                        class="btn {{ $isFeatured ? 'btn-package-featured' : 'btn-package-outline' }} w-100 mb-4">
                                        {{ $isTrial ? 'Coba Gratis' : 'Pilih Paket' }}
                                    </a>

                                    <ul class="package-feature-list list-unstyled mb-0 flex-grow-1">
                                        @foreach ($limits as $limit)
                                            @php
                                                $metric = $limit['limit_metric'] ?? [];
                                                $metricKey = strtolower($metric['key'] ?? '');
                                                $icon = 'bi-check-circle-fill';
                                                foreach ($iconMap as $needle => $mappedIcon) {
                                                    if (str_contains($metricKey, $needle)) {
                                                        $icon = $mappedIcon;
                                                        break;
                                                    }
                                                }
                                                // Kuota consumable (kiriman) berlaku per bulan.
                                                $perMonth = ($metric['metric_type'] ?? '') === 'consumable' && ! $isTrial;
                                            @endphp
                                            <li>
                                                <i class="bi {{ $icon }}"></i>
                                                <span>
                                                    {{ $metric['name'] ?? 'Limit' }}:
                                                    <strong>{{ number_format((float) ($limit['max_value'] ?? 0), 0, ',', '.') }}</strong>
                                                    @if (! empty($metric['unit'])) {{ $metric['unit'] }}@endif{{ $perMonth ? '/bulan' : '' }}
                                                </span>
                                            </li>
                                        @endforeach
                                        @foreach ($package['feature_lines'] ?? [] as $feature)
                                            <li>
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                        @if (empty($limits) && empty($package['feature_lines']))
                                            <li>
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span>Fitur lengkap sesuai kebutuhan bisnis Anda</span>
                                            </li>
                                            <li>
                                                <i class="bi bi-headset"></i>
                                                <span>Dukungan pelanggan responsif</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

        @include('frontend.partials.sections._buttons')
    </div>
</section>
