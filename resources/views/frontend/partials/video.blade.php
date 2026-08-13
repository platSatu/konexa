{{--
    Video, dikelompokkan per kategori — diambil dari API backend Teleios
    (lihat App\Services\TeleiosApiService::getCategoryVideos()/getVideos()
    & App\Http\Controllers\FrontendController::videos()).
--}}
<section id="videos" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Video</h2>

        @if (empty($categoryVideos))
            <p class="text-center text-muted">Belum ada video saat ini.</p>
        @else
            @foreach ($categoryVideos as $category)
                @php
                    $categoryVideoItems = collect($videos)->filter(
                        fn ($video) => ($video['category']['id'] ?? null) === $category['id']
                    );
                @endphp

                @if ($categoryVideoItems->isNotEmpty())
                    <div class="mb-5">
                        <h4 class="mb-1">{{ $category['name'] }}</h4>
                        @if (! empty($category['description']))
                            <p class="text-muted mb-3">{{ $category['description'] }}</p>
                        @endif

                        <div class="row g-4">
                            @foreach ($categoryVideoItems as $video)
                                <div class="col-md-4">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="ratio ratio-16x9 bg-dark">
                                            @if (! empty($video['youtube_embed_url']))
                                                <iframe src="{{ $video['youtube_embed_url'] }}"
                                                    title="{{ $video['title'] }}" allowfullscreen></iframe>
                                            @elseif (! empty($video['videos_url']))
                                                <video controls
                                                    poster="{{ $video['thumbnail_url'] ?? '' }}">
                                                    <source src="{{ $video['videos_url'] }}">
                                                </video>
                                            @elseif (! empty($video['thumbnail_url']))
                                                <img src="{{ $video['thumbnail_url'] }}" class="w-100 h-100"
                                                    style="object-fit: cover;" alt="{{ $video['title'] }}">
                                            @endif
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $video['title'] }}</h5>
                                            <p class="card-text text-muted flex-grow-1">
                                                {{ \Illuminate\Support\Str::limit($video['description'] ?? '', 100) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</section>
