<?php

namespace App\Http\Controllers;

use App\Services\TeleiosApiService;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function __construct(private readonly TeleiosApiService $teleiosApi)
    {
    }

    /**
     * Tampilkan halaman frontend (beranda), termasuk katalog kategori
     * aplikasi & packages yang diambil dari backend Teleios lewat API.
     */
    public function index(): View
    {
        $categoryApplications = $this->teleiosApi->getCategoryApplications();
        $packages = $this->teleiosApi->getPackages();
        $faqs = $this->teleiosApi->getFaqs();
        $features = $this->teleiosApi->getFeatures();
        $headers = $this->teleiosApi->getHeaders();

        return view('frontend.index', compact('categoryApplications', 'packages', 'faqs', 'features', 'headers'));
    }

    /**
     * Tampilkan halaman artikel, diambil dari backend Teleios lewat API.
     */
    public function articles(): View
    {
        $articles = $this->teleiosApi->getArticles();

        return view('frontend.artikel', compact('articles'));
    }

    /**
     * Tampilkan halaman Syarat dan Ketentuan, diambil dari backend
     * Teleios lewat API (versi "current" — status active, terbaru).
     */
    public function terms(): View
    {
        $termCondition = $this->teleiosApi->getTermCondition();

        return view('frontend.syarat-dan-ketentuan', compact('termCondition'));
    }

    /**
     * Tampilkan halaman Video, dikelompokkan per kategori, diambil dari
     * backend Teleios lewat API.
     */
    public function videos(): View
    {
        $categoryVideos = $this->teleiosApi->getCategoryVideos();
        $videos = $this->teleiosApi->getVideos();

        return view('frontend.video', compact('categoryVideos', 'videos'));
    }
}
