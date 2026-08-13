@extends('layouts.frontend')

{{-- Layout menambahkan prefix "Konexa : " otomatis, lihat layouts/frontend.blade.php --}}
@section('title', 'Video')
@section('meta_description', 'Tonton video tutorial dan demo fitur-fitur Konexa untuk mengelola WhatsApp Business Anda lebih efisien.')

@section('content')

    @include('frontend.partials.topbar')

    @include('frontend.partials.video')

    @include('frontend.partials.footer')

@endsection
