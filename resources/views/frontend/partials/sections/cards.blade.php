<div class="row g-4 justify-content-center">
    @foreach ($section['items'] as $item)
        <div class="col-12 col-md-6 col-lg-4 d-flex">
            <div class="card home-card w-100 h-100 border-0 shadow-sm text-body">
                @if (! empty($item['image_url']))
                    <img src="{{ $item['image_url'] }}" class="card-img-top home-card-img" alt="{{ $item['title'] }}" loading="lazy">
                @endif
                <div class="card-body d-flex flex-column p-4">
                    <h5 class="fw-bold mb-1">{{ $item['title'] }}</h5>
                    @if (! empty($item['value']))
                        <div class="fw-semibold text-primary mb-2">{{ $item['value'] }}</div>
                    @endif
                    @if (! empty($item['description']))
                        <p class="text-muted small flex-grow-1 mb-3">{!! nl2br(e($item['description'])) !!}</p>
                    @endif
                    @if (! empty($item['link_url']))
                        <a href="{{ $item['link_url'] }}" class="btn btn-outline-primary mt-auto align-self-start"
                            @if (str_starts_with($item['link_url'], 'http')) target="_blank" rel="noopener" @endif>{{ $item['link_text'] ?: 'Selengkapnya' }}</a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
