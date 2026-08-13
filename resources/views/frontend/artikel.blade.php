@extends('layouts.frontend')

{{-- Layout menambahkan prefix "Konexa : " otomatis, lihat layouts/frontend.blade.php --}}
@section('title', 'Artikel')
@section('meta_description', 'Kumpulan artikel dan tips seputar WhatsApp Business, otomasi pelanggan, dan strategi digital marketing dari Konexa.')

@section('content')

    @include('frontend.partials.topbar')

    @include('frontend.partials.articles')

    @include('frontend.partials.footer')

@endsection
