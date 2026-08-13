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
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            @if (! empty($article['images_url']))
                                <img src="{{ $article['images_url'] }}" class="card-img-top"
                                    alt="{{ $article['title'] }}" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body d-flex flex-column">
                                @if (! empty($article['category']['name']))
                                    <span class="badge bg-primary-subtle text-primary mb-2 align-self-start">
                                        {{ $article['category']['name'] }}
                                    </span>
                                @endif

                                <h5 class="card-title">{{ $article['title'] }}</h5>
                                <p class="card-text text-muted flex-grow-1">
                                    {{ \Illuminate\Support\Str::limit($article['description'] ?? '', 120) }}
                                </p>

                                @if (! empty($article['date_publish']))
                                    <p class="text-muted small mb-0">
                                        {{ \Carbon\Carbon::parse($article['date_publish'])->translatedFormat('d M Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
