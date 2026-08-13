<?php

namespace App\View\Composers;

use App\Services\TeleiosApiService;
use Illuminate\View\View;

/**
 * Injects $webSetting (favicon, logo, meta tags, contact info, GTM/GA
 * IDs, Maps embed — see App\Services\TeleiosApiService::getWebSetting())
 * into layouts/frontend.blade.php and the header/footer partials — see
 * the View::composer() registration in App\Providers\AppServiceProvider
 * ::boot() for the exact view list.
 *
 * Registered on three views rather than just the layout because
 * @include'd partials (header/footer) render as part of the CHILD
 * view's content, which is already fully rendered into a string
 * BEFORE @extends hands control to the layout — so a composer on the
 * layout alone never reaches them. TeleiosApiService is bound as a
 * singleton specifically so firing this composer three times per page
 * still only makes one actual HTTP call (see that method's docblock).
 */
class WebSettingComposer
{
    public function __construct(private readonly TeleiosApiService $teleiosApi)
    {
    }

    public function compose(View $view): void
    {
        $view->with('webSetting', $this->teleiosApi->getWebSetting());
    }
}
