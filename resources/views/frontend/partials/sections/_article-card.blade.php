{{-- Kartu artikel: gambar, kategori, judul, potongan 120 karakter, tombol ke /artikel/{slug}. --}}
<div class="card home-card w-100 h-100 border-0 shadow-sm text-body">
    @if (! empty($article['images_url']))
        <a href="{{ route('frontend.articles.show', $article['slug']) }}">
            <img src="{{ $article['images_url'] }}" class="card-img-top home-card-img" alt="{{ $article['title'] }}" loading="lazy">
        </a>
    @endif
    <div class="card-body d-flex flex-column p-4">
        @if (! empty($article['category']))
            <span class="badge bg-primary-subtle text-primary mb-2 align-self-start">{{ is_array($article['category']) ? ($article['category']['name'] ?? '') : $article['category'] }}</span>
        @endif
        <h5 class="fw-bold mb-2">
            <a href="{{ route('frontend.articles.show', $article['slug']) }}" class="text-reset text-decoration-none">{{ $article['title'] }}</a>
        </h5>
        <p class="text-muted small flex-grow-1 mb-3">{{ \Illuminate\Support\Str::limit(trim(strip_tags((string) $excerpt)), 120) }}</p>
        <div class="d-flex align-items-center justify-content-between gap-2 mt-auto">
            @if (! empty($article['date_publish']))
                <span class="text-muted small">{{ \Carbon\Carbon::parse($article['date_publish'])->translatedFormat('d M Y') }}</span>
            @endif
            <a href="{{ route('frontend.articles.show', $article['slug']) }}" class="fw-semibold text-primary text-decoration-none small ms-auto">
                Baca selengkapnya <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
