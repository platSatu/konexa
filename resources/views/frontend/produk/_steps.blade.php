{{-- Langkah mulai (Action). Param: $title, $steps = [[judul, keterangan], ...]. --}}
<section class="pd-section" data-reveal>
    <div class="container">
        <h2 class="pd-title text-center">{{ $title }}</h2>
        <ol class="pd-steps list-unstyled row g-4 mt-2">
            @foreach ($steps as [$stepTitle, $stepText])
                <li class="col-md-6 col-lg-3">
                    <div class="pd-step">
                        <span class="pd-step-num">{{ $loop->iteration }}</span>
                        <h3>{{ $stepTitle }}</h3>
                        <p>{{ $stepText }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>
