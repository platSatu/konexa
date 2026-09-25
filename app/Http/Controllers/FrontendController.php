<?php

namespace App\Http\Controllers;

use App\Services\TeleiosApiService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function __construct(private readonly TeleiosApiService $teleiosApi)
    {
    }

    /**
     * Section beranda yang dikenal view ini. Section bawaan punya partial
     * sendiri; section tambahan dirender lewat frontend.partials.sections.frame.
     */
    private const BUILTIN_SECTIONS = ['hero', 'running_text', 'packages', 'features', 'faq'];

    private const EXTRA_SECTIONS = ['articles', 'icon_grid', 'cards', 'logos', 'stats', 'testimonials', 'text_media', 'banner'];

    /**
     * Beranda, disusun dari section yang diatur di Teleios (Superadmin >
     * Web > Susunan Beranda). Kalau Teleios tidak bisa dihubungi atau
     * belum ada section, pakai susunan default (sama seperti sebelumnya).
     * Data section bawaan hanya diambil kalau section-nya memang tampil.
     */
    public function index(): View
    {
        $sections = collect($this->teleiosApi->getHomeSections())
            ->filter(fn (array $section) => in_array($section['type'] ?? null, [...self::BUILTIN_SECTIONS, ...self::EXTRA_SECTIONS], true));

        if ($sections->isEmpty()) {
            $sections = collect(self::BUILTIN_SECTIONS)->map(fn (string $type) => ['type' => $type]);
        }

        $sections = $sections->map(fn (array $section) => $section + $this->sectionStyle($section))->values();
        $types = $sections->pluck('type')->all();
        $has = fn (string $type) => in_array($type, $types, true);

        return view('frontend.index', [
            'sections' => $sections,
            'headers' => $has('hero') ? $this->teleiosApi->getHeaders() : [],
            'packageGroups' => $has('packages') ? $this->groupPackages($this->teleiosApi->getPackages()) : [],
            'features' => $has('features') ? $this->teleiosApi->getFeatures() : [],
            'faqs' => $has('faq') ? $this->teleiosApi->getFaqs() : [],
        ]);
    }

    /**
     * Detail artikel (/artikel/{slug}).
     */
    public function article(string $slug): View
    {
        $article = $this->teleiosApi->getArticle($slug);
        abort_if(empty($article), 404);

        return view('frontend.artikel-detail', compact('article'));
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
     * Pilihan topik form Kontak -- harus sama dengan
     * App\Models\WebContactMessage::TOPICS di Teleios.
     */
    public const CONTACT_TOPICS = ['Chatbot AI', 'Broadcast WhatsApp', 'CRM & Sales Pipeline', 'Paket & Harga', 'Tagihan Online', 'Lainnya'];

    /** Form yang dikirim lebih cepat dari ini (detik) hampir pasti bot. */
    private const CONTACT_MIN_SECONDS = 3;

    /**
     * Halaman Kontak. $webSetting (alamat, no. HP, email) datang dari
     * App\View\Composers\WebSettingComposer. form_token = waktu form
     * dibuka (terenkripsi) untuk cek jeda di sendContact().
     */
    public function contact(): View
    {
        return view('frontend.kontak', [
            'topics' => self::CONTACT_TOPICS,
            'formToken' => encrypt(now()->timestamp),
        ]);
    }

    /**
     * Kirim form Kontak ke Teleios (disimpan + email ke Pengaturan Web).
     * Anti-spam di sisi ini: CSRF (grup web), throttle route, honeypot
     * "website" (harus kosong), jeda minimal sejak form dibuka, validasi.
     * Bot yang kena honeypot/jeda diberi respon "berhasil" palsu supaya
     * tidak belajar cara lolos.
     */
    public function sendContact(Request $request): RedirectResponse
    {
        $success = redirect()->route('frontend.contact')
            ->with('contact_success', 'Terima kasih! Pesan Anda sudah kami terima. Tim kami akan segera menghubungi Anda.');

        if (filled($request->input('website')) || ! $this->contactTokenIsHuman((string) $request->input('form_token'))) {
            return $success;
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:150'],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9][0-9\s\-]{7,19}$/'],
            'topic' => ['required', Rule::in(self::CONTACT_TOPICS)],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'phone.regex' => 'Nomor HP hanya boleh angka, spasi, atau tanda -, minimal 8 digit.',
            'message.min' => 'Pesan minimal 10 karakter.',
        ]);

        $response = $this->teleiosApi->sendContactMessage($data + [
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
        ]);

        if ($response?->successful()) {
            return $success;
        }

        $error = match ($response?->status()) {
            422, 429 => (string) $response->json('message', 'Pesan tidak dapat dikirim.'),
            default => 'Maaf, pesan belum bisa dikirim saat ini. Silakan coba lagi atau hubungi kami lewat WhatsApp.',
        };

        throw ValidationException::withMessages(['contact' => $error]);
    }

    private function contactTokenIsHuman(string $token): bool
    {
        try {
            $openedAt = (int) decrypt($token);
        } catch (DecryptException) {
            return false;
        }

        $elapsed = now()->timestamp - $openedAt;

        return $elapsed >= self::CONTACT_MIN_SECONDS && $elapsed <= 60 * 60 * 6;
    }

    /**
     * Inline style background section + penanda "gelap" (teks putih) untuk
     * background gambar/video. Nilai dari API dicek ulang di sini (warna
     * harus hex, URL harus http/https) supaya tidak bisa menyisipkan CSS.
     *
     * @return array{style: string, is_dark: bool}
     */
    private function sectionStyle(array $section): array
    {
        $background = $section['background'] ?? [];
        $type = $background['type'] ?? 'none';
        $color = (string) ($background['color'] ?? '');
        $image = (string) ($background['image_url'] ?? '');

        if ($type === 'color' && preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            return ['style' => "background-color: {$color};", 'is_dark' => false];
        }

        if ($type === 'image' && preg_match('#^https?://[^\s\'"()]+$#', $image)) {
            return [
                'style' => "background-image: linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)), url('{$image}'); background-size: cover; background-position: center;",
                'is_dark' => true,
            ];
        }

        return ['style' => '', 'is_dark' => $type === 'video' && ! empty($background['video_url'])];
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
