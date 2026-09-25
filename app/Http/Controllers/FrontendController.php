<?php

namespace App\Http\Controllers;

use App\Services\TeleiosApiService;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function __construct(private readonly TeleiosApiService $teleiosApi)
    {
    }

    /**
     * Tampilkan halaman frontend (beranda), termasuk katalog kategori
     * aplikasi & packages yang diambil dari backend Teleios lewat API.
     */
    public function index(): View
    {
        $categoryApplications = $this->teleiosApi->getCategoryApplications();
        $packageGroups = $this->groupPackages($this->teleiosApi->getPackages());
        $faqs = $this->teleiosApi->getFaqs();
        $features = $this->teleiosApi->getFeatures();
        $headers = $this->teleiosApi->getHeaders();

        return view('frontend.index', compact('categoryApplications', 'packageGroups', 'faqs', 'features', 'headers'));
    }

    /**
     * Tampilkan halaman artikel, diambil dari backend Teleios lewat API.
     */
    public function articles(): View
    {
        $articles = $this->teleiosApi->getArticles();

        return view('frontend.artikel', compact('articles'));
    }

    /**
     * Tampilkan halaman Syarat dan Ketentuan, diambil dari backend
     * Teleios lewat API (versi "current" — status active, terbaru).
     */
    public function terms(): View
    {
        $termCondition = $this->teleiosApi->getTermCondition();

        return view('frontend.syarat-dan-ketentuan', compact('termCondition'));
    }

    /**
     * Tampilkan halaman Video, dikelompokkan per kategori, diambil dari
     * backend Teleios lewat API.
     */
    public function videos(): View
    {
        $categoryVideos = $this->teleiosApi->getCategoryVideos();
        $videos = $this->teleiosApi->getVideos();

        return view('frontend.video', compact('categoryVideos', 'videos'));
    }

    /**
     * Tampilkan halaman Kontak (form + info alamat/peta). $webSetting
     * (alamat, no. HP, email, embed Maps) di-supply lewat
     * App\View\Composers\WebSettingComposer — lihat pendaftarannya di
     * App\Providers\AppServiceProvider::boot() untuk view
     * 'frontend.kontak'. Form-nya sendiri UI saja untuk sekarang, belum
     * terhubung ke backend mana pun (belum ada endpoint buat nerima
     * submission-nya) — lihat catatan di resources/views/frontend/kontak.blade.php.
     */
    public function contact(): View
    {
        return view('frontend.kontak');
    }

    /**
     * Kelompokkan paket per kombinasi layanan (category application) --
     * satu baris per kelompok (mis. "Paket Lengkap", "Paket Whatsapp
     * Blast"), kolomnya urut Trial -> durasi terpendek -> terpanjang.
     * Kelompok dengan layanan terbanyak tampil paling atas.
     *
     * @param  array<int, array<string, mixed>>  $packages
     * @return array<int, array{label: string, services: array<int, string>, packages: array<int, array<string, mixed>>}>
     */
    private function groupPackages(array $packages): array
    {
        $packages = collect($packages)->map(fn (array $package) => $package + [
            'services' => $this->serviceNames($package),
            'column_label' => $this->columnLabel($package),
            'months' => $this->months($package),
            'feature_lines' => $this->featureLines($package),
        ]);

        $maxServices = $packages->max(fn (array $package) => count($package['services'])) ?? 0;

        return $packages
            ->groupBy(fn (array $package) => implode('|', $package['services']))
            ->map(function ($items) use ($maxServices) {
                $services = $items->first()['services'];

                return [
                    'label' => count($services) > 1 && count($services) === $maxServices
                        ? 'Paket Lengkap'
                        : 'Paket '.(implode(' + ', $services) ?: 'Lainnya'),
                    'services' => $services,
                    'packages' => $items
                        ->sortBy(fn (array $package) => sprintf('%d-%06d', empty($package['is_trial']) ? 1 : 0, (int) ($package['duration'] ?? 0)))
                        ->values()
                        ->all(),
                ];
            })
            ->sortByDesc(fn (array $group) => count($group['services']))
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function serviceNames(array $package): array
    {
        $names = collect($package['category_applications'] ?? [])->pluck('name');

        if ($names->isEmpty()) {
            $names->push(data_get($package, 'category_application.name'));
        }

        return $names->filter()->unique()->sort()->values()->all();
    }

    /**
     * Deskripsi paket sebagai daftar fitur: satu baris = satu fitur
     * (sama dengan Package::featureLines() di teleios).
     *
     * @return array<int, string>
     */
    private function featureLines(array $package): array
    {
        return collect(preg_split('/\R/', (string) ($package['description'] ?? '')))
            ->map(fn (string $line) => trim((string) preg_replace('/^[\s\-*•]+/u', '', $line)))
            ->filter()
            ->values()
            ->all();
    }

    private function months(array $package): int
    {
        return max(1, (int) round(((int) ($package['duration'] ?? 0)) / 30.4));
    }

    /**
     * Nama kolom dari durasi: 3 hari trial -> "Trial 3 Hari", 180 -> "6 Bulan",
     * 365 -> "12 Bulan", di bawah 28 hari -> "N Hari".
     */
    private function columnLabel(array $package): string
    {
        $days = (int) ($package['duration'] ?? 0);

        if (! empty($package['is_trial'])) {
            return "Trial {$days} Hari";
        }

        return $days < 28 ? "{$days} Hari" : $this->months($package).' Bulan';
    }
}
