{{--
    FAQ, diambil dari API backend Teleios (lihat
    App\Services\TeleiosApiService::getFaqs()). Ditampilkan sebagai
    accordion "card list" sesuai referensi desain terbaru dari user:
    judul besar "FAQs" di kiri disambung garis dekoratif (bulatan di
    kedua ujung, lihat .faq-heading-line) ke deskripsi singkat di
    kanan, tiap FAQ jadi card bulat (rounded, background krem) terpisah
    satu sama lain — bukan garis tipis nyambung antar item kayak versi
    sebelumnya — dengan bullet ikon hexagon kecil di depan pertanyaan
    dan ikon plus/minus custom (bukan chevron, lihat .faq-item-icon) di
    kanan yang berubah jadi minus pas dibuka. Hanya satu jawaban
    terbuka dalam satu waktu (data-bs-parent), sama seperti sebelumnya.

    Accordion Bootstrap JS-nya (data-bs-toggle="collapse", dkk) tetap
    dipakai apa adanya — class .accordion/.accordion-item/
    .accordion-button bawaan Bootstrap tetap TIDAK dipakai (diganti
    .faq-item-* custom) supaya tidak kebawa style card/rounded/shadow
    default Bootstrap. Class "collapse" pada div jawaban WAJIB tetap ada
    (itu yang jadi hook buat JS collapse-nya, bukan class "accordion").
--}}
<section id="faq" class="py-5 faq-section">
    {{--
        container-fluid + .faq-container (bukan .container Bootstrap biasa)
        supaya lebar section ini bisa jauh lebih lebar dari container
        Bootstrap standar (yang mentok di ~1320px) — hampir full width
        layar sesuai referensi, dengan padding kiri/kanan yang tetap
        menyesuaikan (mengecil) di layar sempit lewat clamp() di CSS,
        biar tetap responsive dan tidak mepet banget di mobile.
    --}}
    <div class="container-fluid faq-container">
        <div class="faq-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between gap-3 gap-lg-4 mb-4 mb-lg-5">
            <div class="d-flex align-items-center gap-3 flex-grow-1">
                <h2 class="faq-heading mb-0">FAQs</h2>
                <span class="faq-heading-line d-none d-lg-block" aria-hidden="true"></span>
            </div>
            <p class="faq-header-desc text-muted mb-0">
                Temukan jawaban atas pertanyaan umum seputar chatbot AI, broadcast WhatsApp, CRM, dan paket harga Konexa.
            </p>
        </div>

        @if (empty($faqs))
            <p class="text-center text-muted mb-0">Belum ada FAQ saat ini.</p>
        @else
            <div class="faq-list mx-auto" id="faqAccordion">
                @foreach ($faqs as $index => $faq)
                    <div class="faq-item">
                        <h3 class="faq-item-header" id="faqHeading{{ $index }}">
                            <button class="faq-item-button {{ $index === 0 ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="faqCollapse{{ $index }}">
                                <span class="faq-item-question-wrap">
                                    <i class="bi bi-hexagon faq-item-bullet" aria-hidden="true"></i>
                                    <span class="faq-item-question">{{ $faq['name'] }}</span>
                                </span>
                                <span class="faq-item-icon" aria-hidden="true"></span>
                            </button>
                        </h3>
                        <div id="faqCollapse{{ $index }}"
                            class="collapse {{ $index === 0 ? 'show' : '' }}"
                            aria-labelledby="faqHeading{{ $index }}" data-bs-parent="#faqAccordion">
                            <div class="faq-item-body text-muted">
                                {{ $faq['descriptions'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
