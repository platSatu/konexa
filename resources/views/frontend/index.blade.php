@extends('layouts.frontend')

{{--
    Beranda pakai 'title_full' (kalimat jualan penuh). Meta description
    sengaja TIDAK diisi di sini supaya memakai isian Teleios (Superadmin >
    Web > Pengaturan Web > Meta Description). Nama dari APP_NAME.
--}}
@section('title_full', config('app.name').' | Solusi Modern untuk WhatsApp Bisnis Anda')

@section('content')

    @include('frontend.partials.topbar')

    {{--
        Beranda disusun dari section yang diatur di Teleios (Superadmin >
        Web > Susunan Beranda) -- lihat FrontendController::index(). Section
        bawaan memakai partial lamanya; section tambahan lewat
        partials.sections.frame (bingkai + isi per tipe).
    --}}
    @include('frontend.partials.sections._render')

    @include('frontend.partials.footer')

@endsection
