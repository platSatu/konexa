@extends('layouts.frontend')

{{-- Detail artikel (/artikel/{slug}) -- data dari Teleios GET /api/frontend/articles/{slug}. --}}
@section('title', $article['title'])
@section('meta_description', \Illuminate\Support\Str::limit(trim(strip_tags((string) ($article['meta_description'] ?? ''))), 160))
@section('meta_image', (string) ($article['meta_images_url'] ?? ''))

@section('content')
    @include('frontend.partials.topbar')

    @php
        $paragraphs = preg_split('/\R\s*\R/', trim((string) ($article['description'] ?? ''))) ?: [];
        $shareText = rawurlencode($article['title'].' — '.url()->current());
    @endphp

    <article class="py-5">
        <div class="container" style="max-width: 820px;">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.index') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('frontend.articles') }}">Artikel</a></li>
                    <li class="breadcrumb-item active text-truncate" aria-current="page">{{ $article['title'] }}</li>
                </ol>
            </nav>

            @if (! empty($article['category']))
                <span class="badge bg-primary-subtle text-primary mb-2">{{ $article['category'] }}</span>
            @endif
            <h1 class="fw-bold mb-2">{{ $article['title'] }}</h1>
            @if (! empty($article['date_publish']))
                <p class="text-muted small mb-4">{{ \Carbon\Carbon::parse($article['date_publish'])->translatedFormat('d F Y') }}</p>
            @endif

            @if (! empty($article['images_url']))
                <img src="{{ $article['images_url'] }}" alt="{{ $article['title'] }}" class="img-fluid rounded-4 w-100 mb-4">
            @endif

            <div class="article-body">
                @foreach ($paragraphs as $paragraph)
                    @if (trim($paragraph) !== '')
                        <p>{!! nl2br(e(trim($paragraph))) !!}</p>
                    @endif
                @endforeach
            </div>

            @if (! empty($article['tags']))
                <div class="d-flex flex-wrap gap-2 mt-4">
                    @foreach ($article['tags'] as $tag)
                        <span class="badge rounded-pill bg-light text-dark border">#{{ $tag }}</span>
                    @endforeach
                </div>
            @endif

            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center border-top pt-4 mt-4">
                <a href="{{ route('frontend.articles') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Semua Artikel</a>
                <a href="https://wa.me/?text={{ $shareText }}" target="_blank" rel="noopener" class="btn btn-success"><i class="bi bi-whatsapp"></i> Bagikan</a>
            </div>
        </div>
    </article>

    @if (! empty($article['related']))
        <section class="py-5 bg-light">
            <div class="container">
                <h2 class="h4 fw-bold mb-4">Artikel Terkait</h2>
                <div class="row g-4">
                    @foreach ($article['related'] as $related)
                        <div class="col-12 col-md-6 col-lg-4 d-flex">
                            @include('frontend.partials.sections._article-card', ['article' => $related + ['category' => $article['category'] ?? null], 'excerpt' => $related['description'] ?? ''])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('frontend.partials.footer')
@endsection
