@extends('layouts.frontend')

{{-- Layout menambahkan prefix "Bizbos : " otomatis, lihat layouts/frontend.blade.php --}}
@section('title', 'Kontak')
@section('meta_description', 'Hubungi tim Bizbos — tanya apa saja soal chatbot AI, broadcast WhatsApp, CRM, atau paket harga. Kami siap membantu.')

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

    <section class="py-5 contact-hero">
        <div class="container">
            <div class="text-center mb-5">
                <p class="text-muted small mb-2">Bizbos &bull; Kontak</p>
                <h1 class="contact-title mb-3">Hubungi Kami</h1>
                <p class="text-muted mx-auto contact-subtitle mb-0">
                    Ada pertanyaan soal chatbot AI, broadcast WhatsApp, CRM, atau paket harga? Tim kami siap membantu menjawab semuanya.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                {{-- Kanal kontak langsung --}}
                <div class="col-12 col-lg-4">
                    <div class="d-flex flex-column gap-3 h-100">
                        @if ($waNumber !== '')
                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="contact-channel">
                                <span class="contact-channel-icon contact-channel-icon--wa"><i class="bi bi-whatsapp"></i></span>
                                <span>
                                    <span class="d-block fw-bold">WhatsApp</span>
                                    <span class="d-block text-muted small">Balasan paling cepat</span>
                                </span>
                            </a>
                        @endif

                        @if ($contactEmail)
                            <a href="mailto:{{ $contactEmail }}" class="contact-channel">
                                <span class="contact-channel-icon"><i class="bi bi-envelope"></i></span>
                                <span class="text-break">
                                    <span class="d-block fw-bold">Email</span>
                                    <span class="d-block text-muted small">{{ $contactEmail }}</span>
                                </span>
                            </a>
                        @endif

                        @if ($contactAddress)
                            <div class="contact-channel">
                                <span class="contact-channel-icon"><i class="bi bi-geo-alt"></i></span>
                                <span>
                                    <span class="d-block fw-bold">Alamat</span>
                                    <span class="d-block text-muted small">{{ $contactAddress }}</span>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Form: dikirim ke Teleios (Superadmin > Web > Pesan Masuk + email Pengaturan Web) --}}
                <div class="col-12 col-lg-7">
                    <div class="contact-form-card">
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
                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label small fw-semibold">Nama</label>
                                    <input type="text" name="name" id="name" maxlength="100" value="{{ old('name') }}"
                                        class="form-control contact-input @error('name') is-invalid @enderror" placeholder="Nama Anda" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label small fw-semibold">Email</label>
                                    <input type="email" name="email" id="email" maxlength="150" value="{{ old('email') }}"
                                        class="form-control contact-input @error('email') is-invalid @enderror" placeholder="nama@email.com" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="phone" class="form-label small fw-semibold">No. HP / WhatsApp</label>
                                    <input type="tel" name="phone" id="phone" maxlength="20" value="{{ old('phone') }}"
                                        class="form-control contact-input @error('phone') is-invalid @enderror" placeholder="0812 3456 7890" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="topic" class="form-label small fw-semibold">Topik</label>
                                    <select name="topic" id="topic" class="form-select contact-input @error('topic') is-invalid @enderror" required>
                                        <option value="" disabled @selected(! old('topic'))>Pilih topik</option>
                                        @foreach ($topics as $topic)
                                            <option value="{{ $topic }}" @selected(old('topic') === $topic)>{{ $topic }}</option>
                                        @endforeach
                                    </select>
                                    @error('topic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label small fw-semibold">Pesan</label>
                                    <textarea name="message" id="message" rows="5" maxlength="3000"
                                        class="form-control contact-input contact-textarea @error('message') is-invalid @enderror"
                                        placeholder="Ada yang bisa kami bantu?" required>{{ old('message') }}</textarea>
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
    <script>
        // Cegah klik ganda (pesan terkirim dua kali).
        document.getElementById('contactForm')?.addEventListener('submit', function () {
            var button = document.getElementById('contactSubmit');
            button.disabled = true;
            button.innerHTML = 'Mengirim...';
        });
    </script>
@endpush
