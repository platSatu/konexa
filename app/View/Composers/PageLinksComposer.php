<?php

namespace App\View\Composers;

use App\Services\TeleiosApiService;
use Illuminate\View\View;

/**
 * $navPages untuk navbar: halaman dinamis (Teleios Superadmin > Web >
 * Halaman) yang dicentang "Menu atas", urut navbar_order lalu judul.
 */
class PageLinksComposer
{
    public function __construct(private readonly TeleiosApiService $teleiosApi)
    {
    }

    public function compose(View $view): void
    {
        $view->with('navPages', collect($this->teleiosApi->getPages())
            ->filter(fn (array $page) => ! empty($page['show_in_navbar']))
            ->sortBy([['navbar_order', 'asc'], ['title', 'asc']])
            ->values());
    }
}
