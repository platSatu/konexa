<div class="d-flex flex-wrap justify-content-center align-items-center gap-4 gap-lg-5">
    @foreach ($section['items'] as $item)
        @php $logo = '<img src="'.e($item['image_url']).'" alt="'.e($item['title'] ?? '').'" class="home-logo" loading="lazy">'; @endphp
        @if (! empty($item['link_url']))
            <a href="{{ $item['link_url'] }}" target="_blank" rel="noopener" title="{{ $item['title'] }}">{!! $logo !!}</a>
        @else
            {!! $logo !!}
        @endif
    @endforeach
</div>
