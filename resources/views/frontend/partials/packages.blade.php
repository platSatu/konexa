{{--
    Daftar package, diambil dari API backend Teleios (lihat
    App\Services\TeleiosApiService & App\Http\Controllers\FrontendController).
    Sementara diletakkan langsung di bawah header — posisi final menyusul.
--}}
<section id="packages" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Paket Layanan</h2>

        @if (empty($packages))
            <p class="text-center text-muted">Paket belum tersedia saat ini.</p>
        @else
            <div class="row g-4">
                @foreach ($packages as $package)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body d-flex flex-column">
                                @if (! empty($package['category_application']['name']))
                                    <span class="badge bg-primary-subtle text-primary mb-2 align-self-start">
                                        {{ $package['category_application']['name'] }}
                                    </span>
                                @endif

                                <h5 class="card-title">{{ $package['name'] }}</h5>
                                <p class="card-text text-muted flex-grow-1">{{ $package['description'] }}</p>

                                <p class="fw-bold fs-5 mb-0">
                                    Rp {{ number_format((float) ($package['price'] ?? 0), 0, ',', '.') }}
                                </p>
                                <p class="text-muted small mb-0">{{ $package['duration'] ?? '-' }} hari</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
