@extends('layouts.frontend')

@section('title', 'Video - ' . config('app.name', 'Konexa'))

@section('content')

    @include('frontend.partials.topbar')

    @include('frontend.partials.video')

    @include('frontend.partials.footer')

@endsection
