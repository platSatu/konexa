@extends('layouts.frontend')

{{-- Halaman dinamis /page/{slug} -- data dari Teleios GET /api/frontend/pages/{slug} (Superadmin > Web > Halaman). --}}
@section('title', $page['title'])
@section('meta_description', \Illuminate\Support\Str::limit((string) ($page['meta_description'] ?? $page['subtitle'] ?? ''), 160))
@section('meta_image', (string) ($page['meta_image_url'] ?? ''))

@section('content')
    @include('frontend.partials.topbar')

    <header class="page-hero py-5 {{ $heroStyle['is_dark'] ? 'text-white page-hero--image' : '' }}" style="{{ $heroStyle['style'] }}">
        <div class="container py-lg-4 text-center">
            <h1 class="fw-bold mb-2">{{ $page['title'] }}</h1>
            @if (! empty($page['subtitle']))
                <p class="lead mb-0 mx-auto {{ $heroStyle['is_dark'] ? 'text-white-50' : 'text-muted' }}" style="max-width: 720px;">{{ $page['subtitle'] }}</p>
            @endif
        </div>
    </header>

    @if (($page['type'] ?? null) === 'landing')
        @include('frontend.partials.sections._render')
    @else
        <section class="py-5">
            <div class="container">
                <div class="row g-5 justify-content-center">
                    @if (count($document['toc']) >= 2)
                        <aside class="col-lg-3 d-none d-lg-block">
                            <nav class="doc-toc" aria-label="Daftar isi">
                                <p class="fw-bold small text-uppercase text-muted mb-2">Daftar isi</p>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($document['toc'] as $heading)
                                        <li class="{{ $heading['level'] === 3 ? 'ps-3' : '' }}"><a href="#{{ $heading['id'] }}">{{ $heading['text'] }}</a></li>
                                    @endforeach
                                </ul>
                            </nav>
                        </aside>
                    @endif
                    <div class="col-lg-8">
                        @if (count($document['toc']) >= 2)
                            <details class="doc-toc-mobile d-lg-none mb-4">
                                <summary class="fw-semibold">Daftar isi</summary>
                                <ul class="list-unstyled mt-2 mb-0">
                                    @foreach ($document['toc'] as $heading)
                                        <li class="{{ $heading['level'] === 3 ? 'ps-3' : '' }}"><a href="#{{ $heading['id'] }}">{{ $heading['text'] }}</a></li>
                                    @endforeach
                                </ul>
                            </details>
                        @endif

                        <article class="doc-body">
                            {{-- HTML aman: dirender App\Support\MarkdownDocument (HTML mentah & link berbahaya dibuang). --}}
                            {!! $document['html'] !!}
                        </article>

                        @if (! empty($page['updated_at']))
                            <p class="text-muted small border-top pt-3 mt-5 mb-0">
                                Terakhir diperbarui {{ \Carbon\Carbon::parse($page['updated_at'])->translatedFormat('d F Y') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    @include('frontend.partials.footer')
@endsection
