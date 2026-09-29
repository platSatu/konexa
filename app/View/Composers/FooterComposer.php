<?php

namespace App\View\Composers;

use App\Services\TeleiosApiService;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Groups App\Services\TeleiosApiService::getFooters()'s flat row list
 * into columns for frontend.partials.footer — rows sharing the same
 * `group_name` (from App\Models\WebFooter on the Teleios side) become one
 * column, in the order their group first appears (already sort_order-
 * sorted server-side, so this preserves that order). Rows with no
 * group_name are collected separately as loose/ungrouped links, rendered
 * without a column header.
 *
 * A column's Bootstrap width class is taken from its FIRST row's
 * `column_width` ('col-md-3'/'col-md-4') — the superadmin form asks
 * admins to keep that value consistent across every row in one group,
 * see the group_name field's help text in Teleios' footers/_form.
 *
 * Registered only on frontend.partials.footer (see
 * App\Providers\AppServiceProvider::boot()) — unlike WebSettingComposer,
 * this data is only ever used there, so there's no need to fire it on
 * the layout/header views too.
 */
class FooterComposer
{
    public function __construct(private readonly TeleiosApiService $teleiosApi)
    {
    }

    public function compose(View $view): void
    {
        // Halaman dinamis ber-flag "Footer" (Superadmin > Web > Halaman) ikut
        // jadi link: nama kolom sama dengan grup Web > Footer = digabung,
        // selain itu jadi kolom baru. Urutan: link Footer dulu, lalu halaman.
        $pageLinks = collect($this->teleiosApi->getPages())
            ->filter(fn (array $page) => ! empty($page['show_in_footer']) && filled($page['footer_group'] ?? null))
            ->sortBy([['footer_order', 'asc'], ['title', 'asc']])
            ->map(fn (array $page) => [
                'name' => $page['title'],
                'group_name' => $page['footer_group'],
                'link' => route('frontend.page', $page['slug']),
                'target_blank' => false,
            ]);

        $footers = collect($this->teleiosApi->getFooters())->concat($pageLinks);

        $groups = $footers
            ->filter(fn (array $row) => filled($row['group_name'] ?? null))
            ->groupBy('group_name')
            ->map(fn (Collection $rows, string $groupName) => [
                'name' => $groupName,
                'column_width' => $rows->first()['column_width'] ?? 'col-md-3',
                'items' => $rows->values(),
            ])
            ->values();

        $ungrouped = $footers
            ->reject(fn (array $row) => filled($row['group_name'] ?? null))
            ->values();

        $view->with('footerGroups', $groups);
        $view->with('footerUngrouped', $ungrouped);
    }
}
