@extends('layouts.frontend')

{{-- Layout menambahkan prefix "Konexa : " otomatis, lihat layouts/frontend.blade.php --}}
@section('title', 'Kontak')
@section('meta_description', 'Hubungi tim Konexa — tanya apa saja soal chatbot AI, broadcast WhatsApp, CRM, atau paket harga. Kami siap membantu.')

@section('content')

    @include('frontend.partials.topbar')

    {{--
        Desain terinspirasi dari referensi (form Osome) yang dikirim
        user, TAPI tanpa ilustrasi dekoratif/background pattern seperti
        di referensi aslinya — sesuai permintaan "tanpa background",
        cuma layout & gaya elemen formnya yang mengikuti.
    --}}
    <section class="py-5 contact-hero">
        <div class="container">
            <p class="text-center text-muted small mb-2">Konexa &bull; Kontak</p>
            <h1 class="text-center contact-title mb-3">Hubungi Kami</h1>
            <p class="text-center text-muted mx-auto contact-subtitle">
                Ada pertanyaan soal chatbot AI, broadcast WhatsApp, CRM, atau paket harga? Tim kami siap membantu menjawab semuanya.
            </p>

            {{--
                Form UI SAJA untuk sekarang — belum terhubung ke backend
                mana pun (belum ada endpoint yang menerima submission-nya),
                sama seperti form newsletter di footer yang juga masih
                UI-only. onsubmit="return false;" sengaja dipasang supaya
                klik "Kirim Pesan" tidak reload halaman ke mana-mana
                selama belum ada endpoint sungguhan. Kasih tahu kalau mau
                ini benar-benar mengirim (lewat email, atau endpoint baru
                di backend Teleios) supaya bisa disambungkan.
            --}}
            <form class="contact-form mx-auto" onsubmit="return false;">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <input type="text" class="form-control contact-input" placeholder="Nama Anda" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="email" class="form-control contact-input" placeholder="Alamat email" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="tel" class="form-control contact-input" placeholder="+62 812 3456 7890" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <select class="form-select contact-input" required>
                            <option value="" selected disabled>Tertarik dengan</option>
                            <option>Chatbot AI</option>
                            <option>Broadcast WhatsApp</option>
                            <option>CRM &amp; Sales Pipeline</option>
                            <option>Paket &amp; Harga</option>
                            <option>Lainnya</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <textarea class="form-control contact-input contact-textarea" rows="5"
                            placeholder="Ada yang bisa kami bantu?" required></textarea>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn contact-submit-btn">Kirim Pesan</button>
                        <p class="small text-muted mt-3 mb-0">
                            Dengan mengklik kirim, Anda menyetujui
                            <a href="{{ route('frontend.terms') }}">Syarat dan Ketentuan</a> kami.
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </section>

    {{--
        Info kontak & peta — $webSetting datang dari
        App\View\Composers\WebSettingComposer (lihat pendaftarannya di
        App\Providers\AppServiceProvider::boot() untuk view
        'frontend.kontak'). Field yang dipakai (address/handphone/email/
        gmaps) SAMA dengan yang dipakai di partials/footer.blade.php —
        sengaja satu sumber data, bukan dua yang terpisah.
    --}}
    <section class="py-5 contact-info-section">
        <div class="container">
            <p class="text-center text-muted small mb-2">Kontak</p>
            <h2 class="text-center mb-5">Ngobrol dengan Tim Konexa</h2>

            <div class="row g-4 align-items-stretch justify-content-center">
                @if (data_get($webSetting, 'gmaps'))
                    <div class="col-12 col-lg-7">
                        <div class="ratio ratio-16x9 contact-map">
                            <iframe src="{{ $webSetting['gmaps'] }}" style="border: 0;" allowfullscreen loading="lazy"></iframe>
                        </div>
                    </div>
                @endif

                <div class="col-12 col-lg-5">
                    <div class="contact-info-card h-100">
                        @if (data_get($webSetting, 'address'))
                            <p class="text-muted small mb-1">Alamat</p>
                            <p class="fw-bold mb-4">{{ $webSetting['address'] }}</p>
                        @endif

                        @if (data_get($webSetting, 'handphone'))
                            <p class="text-muted small mb-1">Telepon / WhatsApp</p>
                            <p class="fw-bold mb-4">
                                <a href="tel:{{ $webSetting['handphone'] }}">{{ $webSetting['handphone'] }}</a>
                            </p>
                        @endif

                        @if (data_get($webSetting, 'email'))
                            <p class="text-muted small mb-1">Email</p>
                            <p class="fw-bold mb-0">
                                <a href="mailto:{{ $webSetting['email'] }}">{{ $webSetting['email'] }}</a>
                            </p>
                        @endif

                        @if (! data_get($webSetting, 'address') && ! data_get($webSetting, 'handphone') && ! data_get($webSetting, 'email'))
                            <p class="text-muted mb-0">Info kontak belum diisi di Superadmin &gt; Web &gt; Pengaturan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.partials.footer')

@endsection
