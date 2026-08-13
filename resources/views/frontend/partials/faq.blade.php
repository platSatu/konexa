{{--
    FAQ, diambil dari API backend Teleios (lihat
    App\Services\TeleiosApiService::getFaqs()). Ditampilkan sebagai
    Bootstrap 5 accordion (collapse) — hanya satu jawaban terbuka
    dalam satu waktu (data-bs-parent).
--}}
<section id="faq" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Pertanyaan yang Sering Diajukan</h2>

        @if (empty($faqs))
            <p class="text-center text-muted">Belum ada FAQ saat ini.</p>
        @else
            <div class="accordion mx-auto" id="faqAccordion" style="max-width: 800px;">
                @foreach ($faqs as $index => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading{{ $index }}">
                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="faqCollapse{{ $index }}">
                                {{ $faq['name'] }}
                            </button>
                        </h2>
                        <div id="faqCollapse{{ $index }}"
                            class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                            aria-labelledby="faqHeading{{ $index }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                {{ $faq['descriptions'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
