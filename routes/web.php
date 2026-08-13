<?php

use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'index'])->name('frontend.index');
Route::get('/artikel', [FrontendController::class, 'articles'])->name('frontend.articles');
Route::get('/syarat-dan-ketentuan', [FrontendController::class, 'terms'])->name('frontend.terms');
Route::get('/video', [FrontendController::class, 'videos'])->name('frontend.videos');
Route::get('/kontak', [FrontendController::class, 'contact'])->name('frontend.contact');
