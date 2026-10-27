@extends('layouts.frontend')

{{-- Layout menambahkan prefix "{APP_NAME} : " otomatis, lihat layouts/frontend.blade.php --}}
@section('title', 'Kontak')
@section('meta_description', 'Hubungi tim '.config('app.name').' — tanya apa saja soal chatbot AI, broadcast WhatsApp, CRM, atau paket harga. Kami siap membantu.')

@section('content')

    @include('frontend.partials.topbar')

    @php
        // $webSetting dari App\View\Composers\WebSettingComposer (Superadmin > Web > Pengaturan).
        $waNumber = preg_replace('/\D/', '', (string) data_get($webSetting, 'handphone'));
        if ($waNumber !== '' && str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        }
        $contactEmail = data_get($webSetting, 'email');
        $contactAddress = data_get($webSetting, 'address');
    @endphp

    {{-- Tema terang seperti beranda & halaman produk (frontend.css bagian "Hero terang" + "Kontak modern"). --}}
    <section class="hero-light contact-page">
        <span class="hero-burst hero-burst--left" aria-hidden="true"></span>
        <span class="hero-burst hero-burst--right" aria-hidden="true"></span>
        <div class="container">
            <div class="text-center mb-5">
                <span class="hero-eyebrow"><i class="bi bi-chat-heart"></i> Kontak</span>
                <h1 class="contact-title mb-3">Ada yang bisa kami <span class="contact-highlight">bantu?</span></h1>
                <p class="text-muted mx-auto contact-subtitle mb-0">
                    Tanya soal paket, fitur, atau cara memakai {{ config('app.name') }}. Tim kami siap membantu, tanpa jawaban robot yang berbelit.
                </p>
            </div>

            <div class="row g-4 justify-content-center align-items-stretch">
                {{-- Kanal kontak: selalu ada live chat; WhatsApp/Email/Alamat tampil kalau diisi di Pengaturan Web. --}}
                <div class="col-12 col-lg-5 col-xl-4">
                    <aside class="contact-panel h-100">
                        <h2 class="h4 fw-bold mb-2">Ngobrol langsung</h2>
                        <p class="contact-panel-lead">Pilih cara yang paling nyaman. Pesan dari form di samping juga langsung masuk ke tim kami.</p>

                        <div class="d-flex flex-column gap-2">
                            <button type="button" class="contact-channel" data-chat-open>
                                <span class="contact-channel-icon contact-channel-icon--chat"><i class="bi bi-chat-dots"></i></span>
                                <span class="text-start">
                                    <span class="d-block fw-bold">Live chat</span>
                                    <span class="d-block small contact-channel-hint">Langsung dijawab di halaman ini</span>
                                </span>
                                <i class="bi bi-arrow-right ms-auto"></i>
                            </button>

                            @if ($waNumber !== '')
                                <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="contact-channel">
                                    <span class="contact-channel-icon contact-channel-icon--wa"><i class="bi bi-whatsapp"></i></span>
                                    <span>
                                        <span class="d-block fw-bold">WhatsApp</span>
                                        <span class="d-block small contact-channel-hint">Balasan paling cepat</span>
                                    </span>
                                    <i class="bi bi-arrow-right ms-auto"></i>
                                </a>
                            @endif

                            @if ($contactEmail)
                                <a href="mailto:{{ $contactEmail }}" class="contact-channel">
                                    <span class="contact-channel-icon"><i class="bi bi-envelope"></i></span>
                                    <span class="text-break">
                                        <span class="d-block fw-bold">Email</span>
                                        <span class="d-block small contact-channel-hint">{{ $contactEmail }}</span>
                                    </span>
                                </a>
                            @endif

                            @if ($contactAddress)
                                <div class="contact-channel">
                                    <span class="contact-channel-icon"><i class="bi bi-geo-alt"></i></span>
                                    <span>
                                        <span class="d-block fw-bold">Alamat</span>
                                        <span class="d-block small contact-channel-hint">{{ $contactAddress }}</span>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="contact-links">
                            <span class="small fw-bold d-block mb-2">Mungkin yang Anda cari</span>
                            <a href="{{ route('frontend.index') }}#packages"><i class="bi bi-tags"></i> Paket & harga</a>
                            <a href="{{ route('frontend.index') }}#faq"><i class="bi bi-question-circle"></i> Pertanyaan umum (FAQ)</a>
                            <a href="{{ route('frontend.terms') }}"><i class="bi bi-file-earmark-text"></i> Syarat & Ketentuan</a>
                        </div>

                        {{-- Ilustrasi percakapan (hiasan, bukan data asli). --}}
                        <div class="contact-art" aria-hidden="true">
                            <p class="contact-bubble">Halo, mau tanya paket yang cocok untuk kursus 👋</p>
                            <p class="contact-bubble contact-bubble--out">Boleh! Kami bantu pilihkan ya 😊</p>
                        </div>
                    </aside>
                </div>

                {{-- Form: dikirim ke Teleios (Superadmin > Web > Pesan Masuk + email Pengaturan Web) --}}
                <div class="col-12 col-lg-7">
                    <div class="contact-form-card h-100">
                        <h2 class="h4 fw-bold mb-1">Kirim pesan</h2>
                        <p class="text-muted small mb-4">Kami balas ke email atau WhatsApp Anda secepatnya di jam kerja.</p>

                        @if (session('contact_success'))
                            <div class="alert alert-success d-flex gap-2 align-items-start" role="alert">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>{{ session('contact_success') }}</span>
                            </div>
                        @endif

                        @error('contact')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror

                        <form action="{{ route('frontend.contact.send') }}" method="POST" class="contact-form" id="contactForm" novalidate>
                            @csrf
                            <input type="hidden" name="form_token" value="{{ $formToken }}">

                            {{-- Honeypot: tidak terlihat manusia, biasanya diisi bot. --}}
                            <div class="contact-hp" aria-hidden="true">
                                <label for="website">Website</label>
                                <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <span class="form-label small fw-semibold d-block">Topik</span>
                                    <div class="contact-topics @error('topic') is-invalid @enderror" role="radiogroup" aria-label="Topik">
                                        @foreach ($topics as $topic)
                                            <input type="radio" class="btn-check" name="topic" id="topic-{{ $loop->index }}" value="{{ $topic }}" @checked(old('topic') === $topic) required>
                                            <label class="contact-topic" for="topic-{{ $loop->index }}">{{ $topic }}</label>
                                        @endforeach
                                    </div>
                                    @error('topic')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                @foreach ([
                                    ['name', 'Nama', 'text', 'person', 'Nama Anda', 100, 'name'],
                                    ['email', 'Email', 'email', 'envelope', 'nama@email.com', 150, 'email'],
                                    ['phone', 'No. HP / WhatsApp', 'tel', 'phone', '0812 3456 7890', 20, 'tel'],
                                ] as [$field, $label, $type, $icon, $placeholder, $max, $autocomplete])
                                    <div class="col-12 {{ $field === 'phone' ? '' : 'col-md-6' }}">
                                        <label for="{{ $field }}" class="form-label small fw-semibold">{{ $label }}</label>
                                        <div class="contact-field">
                                            <i class="bi bi-{{ $icon }}" aria-hidden="true"></i>
                                            <input type="{{ $type }}" name="{{ $field }}" id="{{ $field }}" maxlength="{{ $max }}" value="{{ old($field) }}" autocomplete="{{ $autocomplete }}"
                                                class="form-control contact-input @error($field) is-invalid @enderror" placeholder="{{ $placeholder }}" required>
                                        </div>
                                        @error($field)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                @endforeach
                                <div class="col-12">
                                    <label for="message" class="form-label small fw-semibold">Pesan</label>
                                    <textarea name="message" id="message" rows="5" maxlength="3000"
                                        class="form-control contact-input contact-textarea @error('message') is-invalid @enderror"
                                        placeholder="Ceritakan kebutuhan atau kendala Anda..." required>{{ old('message') }}</textarea>
                                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <p class="small text-muted mb-0">
                                        Dengan mengirim, Anda menyetujui <a href="{{ route('frontend.terms') }}">Syarat dan Ketentuan</a> kami.
                                    </p>
                                    <button type="submit" class="btn contact-submit-btn" id="contactSubmit">
                                        <i class="bi bi-send"></i> Kirim Pesan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.partials.footer')

@endsection

@push('scripts')
    @if (session('contact_success'))
        {{-- Pesan berhasil terkirim -> event Lead untuk pixel iklan (lihat partials/tracking). --}}
        <script>window.bizbosTrack && bizbosTrack('lead_form', { content_name: 'Form Kontak' });</script>
    @endif
    <script>
        // Cegah klik ganda (pesan terkirim dua kali).
        document.getElementById('contactForm')?.addEventListener('submit', function () {
            var button = document.getElementById('contactSubmit');
            button.disabled = true;
            button.innerHTML = 'Mengirim...';
        });
    </script>
@endpush
