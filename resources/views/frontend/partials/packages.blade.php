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
<section id="packages" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2">Paket Layanan</h2>
            <p class="text-muted mx-auto mb-0" style="max-width: 560px;">
                Pilih paket yang sesuai dengan kebutuhan bisnis Anda — semua paket bisa di-upgrade kapan saja.
            </p>
        </div>

        @if (empty($packages))
            <p class="text-center text-muted mb-0">Paket belum tersedia saat ini.</p>
        @else
            @php
                // Pemetaan kata kunci pada LimitMetric.key -> ikon Bootstrap
                // Icons. Statis (belum ada kolom "icon" di limit_metrics),
                // cocokkan/​tambah sendiri kalau ada key metric baru.
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

                $waNumber = preg_replace('/\D/', '', (string) data_get($webSetting, 'handphone'));
                if ($waNumber !== '' && str_starts_with($waNumber, '0')) {
                    $waNumber = '62' . substr($waNumber, 1);
                }
                $contactEmail = data_get($webSetting, 'email');
            @endphp

            <div class="row g-4 justify-content-center">
                @foreach ($packages as $index => $package)
                    @php
                        $isFeatured = (bool) ($package['is_featured'] ?? false);

                        // Urutan tampil di frontend selalu tetap: Pengiriman
                        // Broadcast -> Device -> Kontak (permintaan user,
                        // 2026-08-31). "Company" (branch_count) sengaja
                        // TIDAK ditampilkan di sini meskipun datanya tetap
                        // ada & benar di backend/Superadmin.
                        $rawLimits = $package['limits'] ?? [];
                        $limitDisplayOrder = ['broadcast', 'device', 'contact'];
                        $limits = [];
                        foreach ($limitDisplayOrder as $needle) {
                            foreach ($rawLimits as $rawLimit) {
                                $key = strtolower($rawLimit['limit_metric']['key'] ?? '');
                                if (str_contains($key, $needle)) {
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
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3 d-flex">
                        <div class="package-card w-100 h-100 d-flex flex-column p-4 {{ $isFeatured ? 'package-card--featured' : '' }}">
                            @if ($isFeatured)
                                <span class="package-badge">TERPOPULER</span>
                            @endif

                            @if (! empty($package['category_application']['name']))
                                <span class="badge bg-primary-subtle text-primary mb-2 align-self-start">
                                    {{ $package['category_application']['name'] }}
                                </span>
                            @endif

                            <h5 class="package-name mb-1">{{ $package['name'] ?? '-' }}</h5>

                            <div class="package-price mb-1">
                                <span class="package-price-currency">Rp</span>
                                <span class="package-price-amount">{{ number_format((float) ($package['price'] ?? 0), 0, ',', '.') }}</span>
                            </div>
                            <p class="package-duration text-muted small mb-4">
                                per {{ $package['duration'] ?? '-' }} hari &middot; perpanjangan harga sama
                            </p>

                            <a href="{{ $waHref }}" target="_blank" rel="noopener"
                                class="btn {{ $isFeatured ? 'btn-package-featured' : 'btn-package-outline' }} w-100 mb-4">
                                Pilih Paket
                            </a>

                            <ul class="package-feature-list list-unstyled mb-0 flex-grow-1">
                                @forelse ($limits as $limit)
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
                                    @endphp
                                    <li>
                                        <i class="bi {{ $icon }}"></i>
                                        <span>
                                            {{ $metric['name'] ?? 'Limit' }}:
                                            <strong>{{ number_format((float) ($limit['max_value'] ?? 0), 0, ',', '.') }}</strong>
                                            @if (! empty($metric['unit'])) {{ $metric['unit'] }} @endif
                                        </span>
                                    </li>
                                @empty
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>Fitur lengkap sesuai kebutuhan bisnis Anda</span>
                                    </li>
                                    <li>
                                        <i class="bi bi-headset"></i>
                                        <span>Dukungan pelanggan responsif</span>
                                    </li>
                                @endforelse
                            </ul>

                            @if (! empty($package['description']))
                                <p class="package-description text-muted small mt-3 mb-0">{{ $package['description'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
