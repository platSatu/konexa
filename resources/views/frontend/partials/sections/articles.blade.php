@if (empty($section['articles']))
    <p class="text-center {{ $section['is_dark'] ? 'text-white-50' : 'text-muted' }} mb-0">Belum ada artikel.</p>
@else
    <div class="row g-4">
        @foreach ($section['articles'] as $article)
            <div class="col-12 col-md-6 col-lg-4 d-flex">
                @include('frontend.partials.sections._article-card', ['article' => $article, 'excerpt' => $article['excerpt'] ?? ''])
            </div>
        @endforeach
    </div>
@endif
