@extends('layouts.frontend')

{{-- Layout menambahkan prefix "Konexa : " otomatis, lihat layouts/frontend.blade.php --}}
@section('title', 'Syarat dan Ketentuan')
@section('meta_description', 'Syarat dan ketentuan penggunaan layanan Konexa — baca sebelum menggunakan platform WhatsApp Business kami.')

@section('content')

    @include('frontend.partials.topbar')

    {{--
        Isi diambil dari API backend Teleios (lihat
        App\Services\TeleiosApiService::getTermCondition() &
        App\Http\Controllers\FrontendController::terms()).
    --}}
    <section class="py-5">
        <div class="container" style="max-width: 800px;">
            @if ($termCondition)
                <h1 class="mb-4">{{ $termCondition['name'] }}</h1>
                <div class="text-muted" style="white-space: pre-line;">{{ $termCondition['descriptions'] }}</div>
            @else
                <h1 class="mb-4">Syarat dan Ketentuan</h1>
                <p class="text-muted">Syarat dan ketentuan belum tersedia saat ini.</p>
            @endif
        </div>
    </section>

    @include('frontend.partials.footer')

@endsection
