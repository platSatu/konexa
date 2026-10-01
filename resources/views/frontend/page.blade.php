@extends('layouts.frontend')

{{-- Halaman dinamis /page/{slug} -- data dari Teleios GET /api/frontend/pages/{slug} (Superadmin > Web > Halaman). --}}
@section('title', $page['title'])
@section('meta_description', \Illuminate\Support\Str::limit((string) ($page['meta_description'] ?? $page['subtitle'] ?? ''), 160))
@section('meta_image', (string) ($page['meta_image_url'] ?? ''))

@section('content')
    @include('frontend.partials.topbar')

    {{-- Tanpa gambar hero: latar merah bizbos (sama dengan section CTA). --}}
    <header class="page-hero text-white {{ $heroStyle['is_dark'] ? 'page-hero--image' : 'page-hero--brand' }}" style="{{ $heroStyle['style'] }}">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb page-hero-breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.index') }}">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page['title'] }}</li>
                </ol>
            </nav>
            <h1 class="page-hero-title mb-2">{{ $page['title'] }}</h1>
            @if (! empty($page['subtitle']))
                <p class="lead page-hero-subtitle mb-0">{{ $page['subtitle'] }}</p>
            @endif
            @if (($page['type'] ?? null) !== 'landing' && ! empty($page['updated_at']))
                <p class="page-hero-meta small mb-0">Terakhir diperbarui {{ \Carbon\Carbon::parse($page['updated_at'])->translatedFormat('d F Y') }}</p>
            @endif
        </div>
    </header>

    @if (($page['type'] ?? null) === 'landing')
        @include('frontend.partials.sections._render')
    @else
        <section class="py-5">
            <div class="container">
                <div class="row g-5 justify-content-center">
                    @php $showNav = count($nav) > 1 || count($document['toc']) >= 2; @endphp
                    @if ($showNav)
                        <aside class="col-lg-3 d-none d-lg-block">
                            <nav class="doc-toc" aria-label="Daftar dokumen">
                                @include('frontend.partials._doc-nav', ['nav' => $nav, 'toc' => $document['toc']])
                            </nav>
                        </aside>
                    @endif
                    <div class="col-lg-8">
                        @if ($showNav)
                            <details class="doc-toc-mobile d-lg-none mb-4">
                                <summary class="fw-semibold">Daftar isi</summary>
                                <div class="mt-2">
                                    @include('frontend.partials._doc-nav', ['nav' => $nav, 'toc' => $document['toc']])
                                </div>
                            </details>
                        @endif

                        <article class="doc-body">
                            {{-- HTML aman: dirender App\Support\MarkdownDocument (HTML mentah & link berbahaya dibuang). --}}
                            {!! $document['html'] !!}
                        </article>

                    </div>
                </div>
            </div>
        </section>
    @endif

    @include('frontend.partials.footer')
@endsection

@push('scripts')
    {{-- Sorot sub-bagian yang sedang dibaca di sidebar (kedua sidebar: desktop & mobile). --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var headings = document.querySelectorAll('.doc-body h2[id], .doc-body h3[id]');
            if (!headings.length || !('IntersectionObserver' in window)) return;

            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    document.querySelectorAll('[data-doc-section]').forEach(function (link) {
                        link.classList.toggle('is-current', link.dataset.docSection === entry.target.id);
                    });
                });
            }, { rootMargin: '-100px 0px -65% 0px' });

            headings.forEach(function (heading) { observer.observe(heading); });
        });
    </script>
@endpush
