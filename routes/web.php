<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// 'log.visitor' (App\Http\Middleware\LogVisitorMiddleware) cuma di 5
// halaman publik ini, bukan middleware global -- supaya /up (health
// check) dan route lain yang mungkin nyusul nanti tidak ikut kecatat
// sebagai kunjungan.
Route::middleware('log.visitor')->group(function () {
    Route::get('/', [FrontendController::class, 'index'])->name('frontend.index');
    Route::get('/artikel', [FrontendController::class, 'articles'])->name('frontend.articles');
    Route::get('/artikel/{slug}', [FrontendController::class, 'article'])->where('slug', '[a-z0-9-]+')->name('frontend.articles.show');
    Route::get('/syarat-dan-ketentuan', [FrontendController::class, 'terms'])->name('frontend.terms');
    Route::get('/video', [FrontendController::class, 'videos'])->name('frontend.videos');
    Route::get('/kontak', [FrontendController::class, 'contact'])->name('frontend.contact');
    Route::get('/tiket', [FrontendController::class, 'ticket'])->name('frontend.ticket');
    Route::view('/kursus', 'frontend.produk.kursus')->name('frontend.course');
    Route::view('/invoice', 'frontend.produk.invoice')->name('frontend.invoice');
    Route::view('/whatsapp', 'frontend.produk.whatsapp')->name('frontend.whatsapp');
    Route::get('/page/{slug}', [FrontendController::class, 'page'])->where('slug', '[a-z0-9-]+')->name('frontend.page');
});

// Kirim form Kontak -- di luar grup log.visitor (bukan kunjungan halaman).
// throttle: maks 5 kiriman / 10 menit per IP, di atas batas yang sama di Teleios.
Route::post('/kontak', [FrontendController::class, 'sendContact'])
    ->middleware('throttle:5,10')
    ->name('frontend.contact.send');

// Live chat (tombol bulat kanan bawah): diteruskan ke app.bizbos lewat server, API key tidak sampai ke browser.
// /chat/start: pengunjung mengisi nama, email, WhatsApp dulu (jadi Customer "Website" di app.bizbos).
Route::post('/chat/start', [App\Http\Controllers\ChatController::class, 'start'])
    ->middleware('throttle:10,1')
    ->name('frontend.chat.start');
Route::post('/chat', App\Http\Controllers\ChatController::class)
    ->middleware('throttle:20,1')
    ->name('frontend.chat');

// Sitemap untuk Google Search Console (di luar log.visitor: yang datang crawler).
Route::get('/sitemap.xml', SitemapController::class)->name('frontend.sitemap');
