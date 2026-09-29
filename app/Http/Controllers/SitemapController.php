<?php

namespace App\Http\Controllers;

use App\Services\TeleiosApiService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

/**
 * /sitemap.xml untuk Google Search Console: halaman statis + semua artikel
 * & halaman dinamis dari Teleios. Otomatis ikut bertambah tiap ada artikel/
 * page baru; di-cache 1 jam supaya tidak memanggil Teleios tiap kali
 * crawler datang.
 */
class SitemapController extends Controller
{
    public function __invoke(TeleiosApiService $teleiosApi): Response
    {
        $urls = Cache::remember('frontend.sitemap', now()->addHour(), function () use ($teleiosApi) {
            $urls = collect([
                ['loc' => route('frontend.index'), 'priority' => '1.0'],
                ['loc' => route('frontend.articles'), 'priority' => '0.8'],
                ['loc' => route('frontend.videos'), 'priority' => '0.6'],
                ['loc' => route('frontend.contact'), 'priority' => '0.6'],
                ['loc' => route('frontend.terms'), 'priority' => '0.3'],
            ]);

            foreach ($teleiosApi->getArticles() as $article) {
                if (! empty($article['slug']) && preg_match('/^[a-z0-9-]+$/', $article['slug'])) {
                    $urls->push([
                        'loc' => route('frontend.articles.show', $article['slug']),
                        'lastmod' => ! empty($article['date_publish']) ? substr((string) $article['date_publish'], 0, 10) : null,
                        'priority' => '0.7',
                    ]);
                }
            }

            foreach ($teleiosApi->getPages() as $page) {
                if (! empty($page['slug']) && preg_match('/^[a-z0-9-]+$/', $page['slug'])) {
                    $urls->push(['loc' => route('frontend.page', $page['slug']), 'priority' => '0.7']);
                }
            }

            return $urls->all();
        });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= '  <url><loc>'.e($url['loc']).'</loc>'
                .(! empty($url['lastmod']) ? '<lastmod>'.e($url['lastmod']).'</lastmod>' : '')
                .'<priority>'.$url['priority'].'</priority></url>'."\n";
        }

        return response($xml.'</urlset>'."\n", 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
