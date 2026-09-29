{{-- Render daftar section (beranda & halaman landing). $sections dari FrontendController::sectionViewData(). --}}
@foreach ($sections as $section)
    @switch($section['type'])
        @case('hero')
            @include('frontend.partials.hero')
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
        @default
            @include('frontend.partials.sections.frame', ['section' => $section])
    @endswitch
@endforeach
