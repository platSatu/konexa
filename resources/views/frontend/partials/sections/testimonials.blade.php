<div class="row g-4 justify-content-center">
    @foreach ($section['items'] as $item)
        <div class="col-12 col-md-6 col-lg-4 d-flex">
            <div class="card border-0 shadow-sm w-100 h-100 text-body">
                <div class="card-body p-4 d-flex flex-column">
                    <i class="bi bi-quote fs-1 text-primary lh-1"></i>
                    <p class="flex-grow-1">{!! nl2br(e($item['description'])) !!}</p>
                    <div class="d-flex align-items-center gap-3 mt-3">
                        @if (! empty($item['image_url']))
                            <img src="{{ $item['image_url'] }}" alt="{{ $item['title'] }}" class="rounded-circle" width="48" height="48" style="object-fit: cover;" loading="lazy">
                        @endif
                        <div>
                            <div class="fw-bold">{{ $item['title'] }}</div>
                            @if (! empty($item['value']))
                                <div class="small text-muted">{{ $item['value'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
