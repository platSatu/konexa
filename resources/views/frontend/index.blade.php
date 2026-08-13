@extends('layouts.frontend')

{{--
    Beranda pakai 'title_full' (bukan 'title' biasa) supaya title-nya
    jadi kalimat jualan penuh, bukan ikut pola "Konexa : Beranda" —
    lihat komentar 'title_full' di layouts/frontend.blade.php.
--}}
@section('title_full', 'Konexa | Solusi Modern untuk WhatsApp Bisnis Anda')
@section('meta_description', 'Konexa adalah platform WhatsApp Business All-in-One — chatbot AI, broadcast anti-banned, CRM, dan otomasi pelanggan dalam satu dashboard.')

@section('content')

    @include('frontend.partials.topbar')

    @include('frontend.partials.hero')

    @include('frontend.partials.running-text')

    @include('frontend.partials.packages')

    @include('frontend.partials.features')

    {{--
        Section placeholder "Tentang Kami" / "Layanan" / "Kontak" (dummy
        scaffolding dari awal proyek, isinya cuma teks generik "Tulis
        konten... di sini") sudah DIHAPUS atas permintaan user — bukan
        konten asli, dan tumpang tindih dengan section Fitur Unggulan di
        atas (yang datanya asli dari App\Models\WebFeature). Kalau nanti
        mau ada section "Tentang Kami" versi asli, tinggal tambahkan lagi
        di sini dengan konten sungguhan (bukan placeholder).
    --}}

    @include('frontend.partials.faq')

    @include('frontend.partials.footer')

@endsection
