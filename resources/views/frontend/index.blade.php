@extends('layouts.frontend')

{{--
    Beranda pakai 'title_full' (bukan 'title' biasa) supaya title-nya
    jadi kalimat jualan penuh, bukan ikut pola "Bizbos : Beranda" —
    lihat komentar 'title_full' di layouts/frontend.blade.php.
--}}
@section('title_full', 'Bizbos | Solusi Modern untuk WhatsApp Bisnis Anda')
@section('meta_description', 'Bizbos adalah platform WhatsApp Business All-in-One — chatbot AI, broadcast anti-banned, CRM, dan otomasi pelanggan dalam satu dashboard.')

@section('content')

    @include('frontend.partials.topbar')

    {{--
        Beranda disusun dari section yang diatur di Teleios (Superadmin >
        Web > Susunan Beranda) -- lihat FrontendController::index(). Section
        bawaan memakai partial lamanya; section tambahan lewat
        partials.sections.frame (bingkai + isi per tipe).
    --}}
    @foreach ($sections as $section)
        @switch($section['type'])
            @case('hero')
                @include('frontend.partials.hero')
                @break
            @case('running_text')
                @include('frontend.partials.running-text')
                @break
            @case('packages')
                @include('frontend.partials.packages', ['section' => $section])
                @break
            @case('features')
                @include('frontend.partials.features', ['section' => $section])
                @break
            @case('faq')
                @include('frontend.partials.faq', ['section' => $section])
                @break
            @default
                @include('frontend.partials.sections.frame', ['section' => $section])
        @endswitch
    @endforeach

    @include('frontend.partials.footer')

@endsection
