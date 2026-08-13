<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
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
    /**
     * @var array<string, mixed>|null
     */
    private ?array $webSetting = null;

    private bool $webSettingFetched = false;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getCategoryApplications(): array
    {
        return $this->request('/api/frontend/category-applications')?->json('data', []) ?? [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPackages(): array
    {
        return $this->request('/api/frontend/packages')?->json('data', []) ?? [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getArticles(): array
    {
        return $this->request('/api/frontend/articles')?->json('data', []) ?? [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getFaqs(): array
    {
        return $this->request('/api/frontend/faqs')?->json('data', []) ?? [];
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
        return $this->request('/api/frontend/term-condition')?->json('data');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getCategoryVideos(): array
    {
        return $this->request('/api/frontend/category-videos')?->json('data', []) ?? [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getVideos(): array
    {
        return $this->request('/api/frontend/videos')?->json('data', []) ?? [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getFeatures(): array
    {
        return $this->request('/api/frontend/features')?->json('data', []) ?? [];
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
        return $this->request('/api/frontend/headers')?->json('data', []) ?? [];
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
        return $this->request('/api/frontend/footers')?->json('data', []) ?? [];
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
            $this->webSetting = $this->request('/api/frontend/web-setting')?->json('data');
            $this->webSettingFetched = true;
        }

        return $this->webSetting;
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
