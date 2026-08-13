{{--
    Fitur unggulan, diambil dari API backend Teleios (lihat
    App\Services\TeleiosApiService::getFeatures()).
--}}
<section id="features" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Fitur Unggulan</h2>

        @if (empty($features))
            <p class="text-center text-muted">Belum ada fitur saat ini.</p>
        @else
            <div class="row g-4">
                @foreach ($features as $feature)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            @if (! empty($feature['images_url']))
                                <img src="{{ $feature['images_url'] }}" class="card-img-top"
                                    alt="{{ $feature['name'] }}" style="height: 180px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $feature['name'] }}</h5>
                                <p class="card-text text-muted">{{ $feature['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
