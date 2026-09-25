{{--
    Daftar artikel, diambil dari API backend Teleios (lihat
    App\Services\TeleiosApiService::getArticles() &
    App\Http\Controllers\FrontendController::articles()).
--}}
<section id="articles" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Artikel</h2>

        @if (empty($articles))
            <p class="text-center text-muted">Belum ada artikel saat ini.</p>
        @else
            <div class="row g-4">
                @foreach ($articles as $article)
                    <div class="col-md-6 col-lg-4 d-flex">
                        @include('frontend.partials.sections._article-card', ['article' => $article, 'excerpt' => $article['description'] ?? ''])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
