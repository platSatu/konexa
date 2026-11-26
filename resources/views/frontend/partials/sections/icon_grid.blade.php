{{--
    Isi section tipe icon_grid (Superadmin > Web > Susunan Beranda).
    - Mode biasa: kartu berwarna lembut bergantian, ikon besar, teks lebih tegas.
    - Mode alur ($isSteps dari frame.blade.php, judul item diawali nomor):
      stepper bersambung -- nomor dalam lingkaran, garis penghubung dengan titik
      yang bergerak, semua langkah satu baris di layar lebar.
    Gaya: public/css/frontend.css bagian "Section Superadmin: icon_grid".
--}}
@php
    $isSteps = $isSteps ?? false;
    $tones = ['peach', 'sun', 'mint', 'sky', 'lilac'];
@endphp
@if ($isSteps)
    <ol class="home-steps list-unstyled mb-0" style="--steps: {{ count($section['items']) }}">
        @foreach ($section['items'] as $item)
            @php
                preg_match('/^\s*(\d+)[.)]\s+(.*)$/s', (string) $item['title'], $m);
                $stepNumber = $m[1] ?? $loop->iteration;
                $stepTitle = $m[2] ?? $item['title'];
            @endphp
            <li class="home-step home-tone--{{ $tones[$loop->index % count($tones)] }}" style="--i: {{ $loop->index }}">
                <div class="home-step-marker">
                    <span class="home-step-icon">
                        @if (! empty($item['image_url']))
                            <img src="{{ $item['image_url'] }}" alt="" loading="lazy">
                        @else
                            <i class="bi bi-{{ $item['icon'] }}"></i>
                        @endif
                    </span>
                    <span class="home-step-num">{{ $stepNumber }}</span>
                </div>
                <h3 class="home-step-title">{{ $stepTitle }}</h3>
                @if (! empty($item['description']))
                    <p class="home-step-desc">{{ $item['description'] }}</p>
                @endif
            </li>
        @endforeach
    </ol>
@else
    <div class="home-icon-grid">
        @foreach ($section['items'] as $item)
            <div class="home-icon-item home-tone--{{ $tones[$loop->index % count($tones)] }}" style="--i: {{ $loop->index }}">
                <div class="home-icon-badge mb-3">
                    @if (! empty($item['image_url']))
                        <img src="{{ $item['image_url'] }}" alt="{{ $item['title'] }}" loading="lazy">
                    @else
                        <i class="bi bi-{{ $item['icon'] }}"></i>
                    @endif
                </div>
                <h3 class="home-icon-title">{{ $item['title'] }}</h3>
                @if (! empty($item['description']))
                    <p class="home-icon-desc mb-0">{{ $item['description'] }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endif
