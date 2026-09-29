<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Render isi halaman Dokumen (Markdown sederhana dari Teleios Superadmin >
 * Web > Halaman) jadi HTML aman + daftar isi.
 *
 * Aman: html_input=strip (HTML/script di isian dibuang) dan
 * allow_unsafe_links=false (javascript:, data: dll. tidak jadi link).
 * Judul bab (h2/h3) diberi id unik untuk daftar isi yang bisa diklik.
 */
final class MarkdownDocument
{
    /**
     * @return array{html: string, toc: array<int, array{id: string, text: string, level: int}>}
     */
    public static function render(?string $markdown): array
    {
        $html = (string) Str::markdown((string) $markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 10,
        ]);

        $toc = [];
        $used = [];

        $html = preg_replace_callback('#<h([23])>(.*?)</h\1>#s', function (array $match) use (&$toc, &$used) {
            $text = trim(html_entity_decode(strip_tags($match[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $base = Str::slug($text) ?: 'bagian';
            $id = $base;

            for ($i = 2; isset($used[$id]); $i++) {
                $id = $base.'-'.$i;
            }

            $used[$id] = true;
            $toc[] = ['id' => $id, 'text' => $text, 'level' => (int) $match[1]];

            return '<h'.$match[1].' id="'.$id.'">'.$match[2].'</h'.$match[1].'>';
        }, $html) ?? $html;

        // Link keluar dibuka di tab baru & tanpa akses ke window asal.
        $html = preg_replace('#<a href="(https?://[^"]+)">#', '<a href="$1" target="_blank" rel="noopener nofollow">', $html) ?? $html;

        return ['html' => $html, 'toc' => $toc];
    }
}
