<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Thin client for the Teleios backend's public /api/frontend/* catalog
 * (category-applications, packages, articles, faqs, term-condition,
 * category-videos, videos — see Teleios' App\Http\Controllers\Api\
 * Frontend namespace). The two apps run as separate `php artisan
 * serve` processes on localhost during development, on different ports
 * (see this app's SERVER_PORT vs Teleios' SERVER_PORT in each .env),
 * and trust each other via a shared X-API-KEY secret
 * (services.teleios.key / TELEIOS_API_KEY here, must match
 * FRONTEND_API_KEY on the Teleios side) rather than a session or
 * Sanctum token — there's no logged-in user involved, just one
 * server calling another.
 *
 * Every call degrades to an empty result (empty array, or null for the
 * single-object term-condition endpoint) with a logged warning rather
 * than throwing, so the frontend page still renders if Teleios' server
 * isn't running or the key is misconfigured — this is "nice to have"
 * catalog content, not something that should 500 the whole page.
 */
class TeleiosApiService
{
    /** Lama cache isi website dari app.bizbos (detik). */
    private const CACHE_TTL = 300;

    /**
     * @var array<string, mixed>|null
     */
    private ?array $webSetting = null;

    private bool $webSettingFetched = false;

    /**
     * @var array<int, array<string, mixed>>|null
     */
    private ?array $pages = null;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getCategoryApplications(): array
    {
        return $this->data('/api/frontend/category-applications');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPackages(): array
    {
        return $this->data('/api/frontend/packages');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getArticles(): array
    {
        return $this->data('/api/frontend/articles');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getFaqs(): array
    {
        return $this->data('/api/frontend/faqs');
    }

    /**
     * Single row (or null) — unlike the other endpoints above, Teleios
     * only ever has one "current" (most recently updated, active) terms
     * version, see WebTermCondition::current() on that side.
     *
     * @return array<string, mixed>|null
     */
    public function getTermCondition(): ?array
    {
        return $this->data('/api/frontend/term-condition', null);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getCategoryVideos(): array
    {
        return $this->data('/api/frontend/category-videos');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getVideos(): array
    {
        return $this->data('/api/frontend/videos');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getFeatures(): array
    {
        return $this->data('/api/frontend/features');
    }

    /**
     * Susunan section beranda (Teleios: Superadmin > Web > Susunan
     * Beranda, App\Models\WebHomeSection). Kosong kalau Teleios tidak
     * bisa dihubungi -- FrontendController lalu memakai susunan default.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getHomeSections(): array
    {
        return $this->data('/api/frontend/home-sections');
    }

    /**
     * Link halaman dinamis untuk navbar & footer (Teleios: Superadmin > Web
     * > Halaman). Di-memo per request (class ini singleton) karena menu dan
     * footer sama-sama membutuhkannya.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getPages(): array
    {
        return $this->pages ??= $this->data('/api/frontend/pages');
    }

    /**
     * Isi satu halaman dinamis (/page/{slug}), atau null kalau tidak ada.
     *
     * @return array<string, mixed>|null
     */
    public function getPage(string $slug): ?array
    {
        return $this->data('/api/frontend/pages/'.rawurlencode($slug), null);
    }

    /**
     * Detail satu artikel + artikel terkait, atau null kalau tidak ada.
     *
     * @return array<string, mixed>|null
     */
    public function getArticle(string $slug): ?array
    {
        return $this->data('/api/frontend/articles/'.rawurlencode($slug), null);
    }

    /**
     * Homepage hero/header slides — see App\Models\WebHeader on the
     * Teleios side (superadmin-managed, Superadmin > Web > Headers).
     * Only active slides, already ordered by sort_order. Each row
     * carries background_type ('image'|'video') plus the matching
     * *_url accessor (videos_url / background_images_url /
     * thumbnail_images_url) to actually render.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getHeaders(): array
    {
        return $this->data('/api/frontend/headers');
    }

    /**
     * Footer link/block rows — see App\Models\WebFooter on the Teleios
     * side (superadmin-managed, Superadmin > Web > Footer). Rows sharing
     * the same `group_name` become one footer column; see
     * App\View\Composers\FooterComposer for how these are grouped before
     * they reach frontend.partials.footer.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getFooters(): array
    {
        return $this->data('/api/frontend/footers');
    }

    /**
     * Singleton site-wide settings row (favicon, logo, meta tags,
     * contact info, GTM/GA IDs, Maps embed) — see WebSetting::current()
     * on the Teleios side. Memoized per-instance (not just per-call)
     * since App\View\Composers\WebSettingComposer is registered on
     * several views (layout + header + footer partials) that all
     * render within the same request; without this, a single page load
     * would fire this HTTP call once per matching view instead of once
     * total. Safe because this class is bound as a singleton in
     * App\Providers\AppServiceProvider, so "per-instance" already means
     * "per-request" here.
     *
     * @return array<string, mixed>|null
     */
    public function getWebSetting(): ?array
    {
        if (! $this->webSettingFetched) {
            $this->webSetting = $this->data('/api/frontend/web-setting', null);
            $this->webSettingFetched = true;
        }

        return $this->webSetting;
    }

    /**
     * Kirim pesan form Kontak ke Teleios (POST /api/frontend/contact-messages).
     * Mengembalikan Response apa adanya (201 / 422 / 429) supaya pemanggil
     * bisa menampilkan pesan yang sesuai, atau null kalau Teleios tidak
     * bisa dihubungi / konfigurasi kosong.
     */
    public function sendContactMessage(array $payload): ?Response
    {
        $baseUrl = config('services.teleios.url');
        $key = config('services.teleios.key');

        if (! $baseUrl || ! $key) {
            Log::warning('TeleiosApiService: missing base URL or API key, contact message not sent.');

            return null;
        }

        try {
            return Http::withHeaders(['X-API-KEY' => $key])
                ->acceptJson()
                ->timeout(8)
                ->post(rtrim($baseUrl, '/').'/api/frontend/contact-messages', $payload);
        } catch (Throwable $e) {
            Log::warning('TeleiosApiService: contact message request threw an exception.', ['message' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Live chat (POST /api/frontend/chat di app.bizbos). Response apa adanya (200 / 422 / 429),
     * atau null kalau backend tidak bisa dihubungi.
     */
    public function chat(array $payload, string $path = '/api/frontend/chat'): ?Response
    {
        $baseUrl = config('services.teleios.url');
        $key = config('services.teleios.key');

        if (! $baseUrl || ! $key) {
            return null;
        }

        try {
            return Http::withHeaders(['X-API-KEY' => $key])
                ->acceptJson()
                ->timeout(40)
                ->post(rtrim($baseUrl, '/').$path, $payload);
        } catch (Throwable $e) {
            Log::warning('TeleiosApiService: chat request threw an exception.', ['message' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Isi `data` dari endpoint GET, disimpan di cache supaya halaman tidak menunggu app.bizbos
     * di setiap kunjungan. Perubahan dari superadmin tampil paling lambat CACHE_TTL detik
     * (atau langsung setelah `php artisan cache:clear`). Kalau app.bizbos sedang tidak bisa
     * dihubungi, dipakai salinan terakhir yang berhasil supaya website tetap tampil lengkap.
     * Respons gagal (404, error) tidak disimpan.
     */
    private function data(string $path, mixed $default = []): mixed
    {
        $key = 'teleios:'.$path;

        if (is_array($cached = Cache::get($key))) {
            return $cached['data'] ?? $default;
        }

        $response = $this->request($path);

        if (! $response) {
            return Cache::get($key.':last')['data'] ?? $default;
        }

        $payload = ['data' => $response->json('data')];
        Cache::put($key, $payload, self::CACHE_TTL);
        Cache::put($key.':last', $payload, now()->addDays(7));

        return $payload['data'] ?? $default;
    }

    /**
     * Shared GET + auth-header + error-handling for every endpoint
     * above. Returns null (rather than throwing or returning an empty
     * Response) on any failure — missing config, non-2xx status, or a
     * connection exception — so every public getX() method above can
     * fall back to its own empty value with a single `?? []`/`?? null`.
     */
    private function request(string $path): ?Response
    {
        $baseUrl = config('services.teleios.url');
        $key = config('services.teleios.key');

        if (! $baseUrl || ! $key) {
            Log::warning('TeleiosApiService: missing base URL or API key, skipping request.', [
                'path' => $path,
            ]);

            return null;
        }

        try {
            $response = Http::withHeaders(['X-API-KEY' => $key])
                ->timeout(5)
                ->get(rtrim($baseUrl, '/').$path);

            if ($response->failed()) {
                Log::warning('TeleiosApiService: request failed.', [
                    'path' => $path,
                    'status' => $response->status(),
                ]);

                return null;
            }

            return $response;
        } catch (Throwable $e) {
            Log::warning('TeleiosApiService: request threw an exception.', [
                'path' => $path,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
