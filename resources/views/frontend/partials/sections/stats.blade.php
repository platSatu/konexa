<div class="row g-4 justify-content-center text-center">
    @foreach ($section['items'] as $item)
        <div class="col-6 col-md-3">
            @if (! empty($item['icon']))
                <i class="bi bi-{{ $item['icon'] }} fs-2 {{ $section['is_dark'] ? 'text-white' : 'text-primary' }}"></i>
            @endif
            <div class="home-stat-value">{{ $item['value'] }}</div>
            <div class="{{ $section['is_dark'] ? 'text-white-50' : 'text-muted' }}">{{ $item['title'] }}</div>
        </div>
    @endforeach
</div>
