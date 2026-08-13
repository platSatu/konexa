@extends('layouts.frontend')

@section('title', 'Artikel - ' . config('app.name', 'Konexa'))

@section('content')

    @include('frontend.partials.topbar')

    @include('frontend.partials.articles')

    @include('frontend.partials.footer')

@endsection
