{{-- Render daftar section (beranda & halaman landing). $sections dari FrontendController::sectionViewData(). --}}
@php
    // Section video "Lihat bizbos bekerja" (beranda) tampil tepat setelah section "cara kerja"
    // (stepper, lihat frame.blade.php). Kalau stepper tidak ada, tampil setelah kenal-situasi.
    $isHome = request()->routeIs('frontend.index');
    $hasSteps = collect($sections)->contains(fn ($s) => ($s['type'] ?? '') === 'icon_grid'
        && count($s['items'] ?? []) > 1
        && collect($s['items'])->every(fn ($item) => preg_match('/^\s*\d+[.)]\s+/', (string) ($item['title'] ?? ''))));
@endphp
@foreach ($sections as $section)
    @switch($section['type'])
        @case('hero')
            @include('frontend.partials.hero')
            {{-- Section keluhan sebelum-sesudah, tetap tampil tepat di bawah hero beranda. --}}
            @if (request()->routeIs('frontend.index'))
                @include('frontend.partials.kenal-situasi')
                @unless ($hasSteps)
                    @include('frontend.partials.lihat-bizbos')
                @endunless
            @endif
            @break
        @case('running_text')
            @include('frontend.partials.running-text')
            @break
        @case('packages')
            @include('frontend.partials.packages', ['section' => $section])
            @break
        @case('features')
            @include('frontend.partials.features', ['section' => $section])
            @break
        @case('faq')
            @include('frontend.partials.faq', ['section' => $section])
            @break
        @case('banner')
            @include('frontend.partials.sections.cta', ['section' => $section])
            @break
        @default
            @include('frontend.partials.sections.frame', ['section' => $section, 'showVideoAfterSteps' => $isHome])
    @endswitch
@endforeach
