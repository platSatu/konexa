@extends('layouts.frontend')

{{-- Layout menambahkan prefix "{APP_NAME} : " otomatis, lihat layouts/frontend.blade.php --}}
@section('title', 'Artikel')
@section('meta_description', 'Kumpulan artikel dan tips seputar WhatsApp Business, otomasi pelanggan, dan strategi digital marketing dari '.config('app.name').'.')

@section('content')

    @include('frontend.partials.topbar')

    @include('frontend.partials.articles')

    @include('frontend.partials.footer')

@endsection
