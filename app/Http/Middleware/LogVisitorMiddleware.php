<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Mencatat tiap kunjungan ke halaman publik ini (beranda/artikel/
 * syarat-dan-ketentuan/video/kontak — lihat routes/web.php, dipasang
 * per-route lewat alias 'log.visitor', bukan middleware global) ke
 * backend Teleios lewat POST /api/frontend/visitor-log, pakai secret
 * X-API-KEY yang sama dengan App\Services\TeleiosApiService (yang
 * dipakai untuk ARAH SEBALIKNYA — baca katalog dari Teleios). Teleios
 * sendiri yang mem-parse browser/OS/device dari user_agent mentah yang
 * dikirim di sini (pakai jenssegers/agent) — lihat App\Http\
 * Controllers\Api\Frontend\VisitorLogController di sana.
 *
 * Cookie 'visitor_id' HARUS diset di handle() (sebelum response
 * dikirim ke browser) — terminate() baru jalan SETELAH response
 * terkirim (lewat fastcgi_finish_request), jadi Cookie::queue() yang
 * dipanggil di sana tidak akan pernah sampai ke browser. Sebaliknya,
 * panggilan HTTP ke Teleios (bagian yang boleh telat sedikit dan boleh
 * diam-diam gagal tanpa bikin halaman error) memang harus di
 * terminate(), bukan handle() — supaya pengunjung tidak menunggu
 * request keluar ini sebelum halamannya sendiri selesai dimuat. Sama
 * seperti TeleiosApiService: gagal lapor cuma di-log, tidak pernah
 * melempar exception yang bisa menjatuhkan halaman.
 *
 * PENTING: data dari handle() dititipkan lewat $request->attributes,
 * BUKAN property instance ($this->...). Laravel me-resolve middleware
 * route lewat container SECARA TERPISAH untuk fase terminate() (lihat
 * Router::gatherRouteMiddleware(), dipanggil dari
 * Kernel::terminateMiddleware()) — jadi $this saat terminate()
 * dipanggil BUKAN objek yang sama dengan $this saat handle() dipanggil,
 * dan property instance yang diisi di handle() akan selalu kosong lagi
 * di terminate(). $request sendiri, sebaliknya, adalah objek yang sama
 * persis di kedua fase, jadi aman dipakai buat nitip data antar fase.
 */
class LogVisitorMiddleware
{
    private const VISITOR_COOKIE = 'visitor_id';

    private const VISITOR_COOKIE_DAYS = 365;

    private const PAYLOAD_ATTRIBUTE = 'visitor_log_payload';

    public function handle(Request $request, Closure $next): Response
    {
        $visitorId = $request->cookie(self::VISITOR_COOKIE);

        if (! $visitorId) {
            $visitorId = (string) Str::uuid();

            Cookie::queue(self::VISITOR_COOKIE, $visitorId, self::VISITOR_COOKIE_DAYS * 24 * 60);
        }

        // Dititipkan lewat $request->attributes (bukan property $this)
        // -- lihat docblock class di atas kenapa.
        $request->attributes->set(self::PAYLOAD_ATTRIBUTE, [
            'visitor_id' => $visitorId,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'path' => '/'.ltrim($request->path(), '/'),
            'referrer' => $request->headers->get('referer'),
            'visited_at' => now()->toIso8601String(),
        ]);

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $payload = $request->attributes->get(self::PAYLOAD_ATTRIBUTE);

        if (! $payload) {
            return;
        }

        $baseUrl = config('services.teleios.url');
        $key = config('services.teleios.key');

        if (! $baseUrl || ! $key) {
            Log::warning('LogVisitorMiddleware: missing base URL or API key, skipping report.');

            return;
        }

        try {
            $response = Http::withHeaders(['X-API-KEY' => $key])
                ->timeout(3)
                ->post(rtrim($baseUrl, '/').'/api/frontend/visitor-log', $payload);

            if ($response->failed()) {
                Log::warning('LogVisitorMiddleware: report failed.', [
                    'status' => $response->status(),
                ]);
            }
        } catch (Throwable $e) {
            Log::warning('LogVisitorMiddleware: report threw an exception.', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}
