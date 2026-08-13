<?php

namespace App\Providers;

use App\Services\TeleiosApiService;
use App\View\Composers\FooterComposer;
use App\View\Composers\WebSettingComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Singleton so TeleiosApiService::getWebSetting()'s per-instance
        // memoization (see that method's docblock) is effectively
        // per-request — WebSettingComposer fires on several views
        // (layout + header + footer partials) per page load, and they
        // all need to share the same instance to avoid one HTTP call
        // per view instead of one per request.
        $this->app->singleton(TeleiosApiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(
            [
                'layouts.frontend',
                'frontend.partials.header',
                'frontend.partials.footer',
                // Packages CTA button links to WhatsApp using $webSetting['handphone']
                // when set — see frontend.partials.packages.
                'frontend.partials.packages',
                // Halaman Kontak butuh alamat/no. HP/email/embed Maps —
                // lihat resources/views/frontend/kontak.blade.php.
                'frontend.kontak',
                // Running text/marquee butuh WebSetting::running_text —
                // lihat frontend.partials.running-text.
                'frontend.partials.running-text',
            ],
            WebSettingComposer::class
        );

        // Footer link groups (Support/About/Sales/Explore-style columns)
        // — only frontend.partials.footer needs this, see FooterComposer.
        View::composer('frontend.partials.footer', FooterComposer::class);
    }
}
