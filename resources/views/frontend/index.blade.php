@extends('layouts.frontend')

@section('title', 'Beranda - ' . config('app.name', 'Konexa'))

@section('content')

    @include('frontend.partials.topbar')

    @include('frontend.partials.hero')

    @include('frontend.partials.packages')

    @include('frontend.partials.features')

    {{-- Tentang --}}
    <section id="about" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Tentang Kami</h2>
            <p class="text-center text-muted mx-auto" style="max-width: 640px;">
                Tulis konten tentang perusahaan/produk Anda di sini.
            </p>
        </div>
    </section>

    {{-- Layanan --}}
    <section id="services" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Layanan</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-lightning-charge fs-1 text-primary"></i>
                            <h5 class="card-title mt-3">Layanan 1</h5>
                            <p class="card-text text-muted">Deskripsi layanan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-check fs-1 text-primary"></i>
                            <h5 class="card-title mt-3">Layanan 2</h5>
                            <p class="card-text text-muted">Deskripsi layanan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-people fs-1 text-primary"></i>
                            <h5 class="card-title mt-3">Layanan 3</h5>
                            <p class="card-text text-muted">Deskripsi layanan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Kontak --}}
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Kontak</h2>
            <p class="text-center text-muted">Info kontak / form kontak di sini.</p>
        </div>
    </section>

    @include('frontend.partials.faq')

    @include('frontend.partials.footer')

@endsection
