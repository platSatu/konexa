<div class="row g-4 justify-content-center">
    @foreach ($section['items'] as $item)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="home-icon-item text-center h-100">
                <div class="home-icon-badge mx-auto mb-3">
                    @if (! empty($item['image_url']))
                        <img src="{{ $item['image_url'] }}" alt="{{ $item['title'] }}">
                    @else
                        <i class="bi bi-{{ $item['icon'] }}"></i>
                    @endif
                </div>
                <h6 class="fw-bold mb-1">{{ $item['title'] }}</h6>
                @if (! empty($item['description']))
                    <p class="small mb-0 {{ $section['is_dark'] ? 'text-white-50' : 'text-muted' }}">{{ $item['description'] }}</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
