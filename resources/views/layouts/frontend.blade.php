<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Konexa'))</title>

    {{--
        $webSetting datang dari App\View\Composers\WebSettingComposer
        (lihat App\Providers\AppServiceProvider::boot()), diambil dari
        backend Teleios lewat App\Services\TeleiosApiService::getWebSetting().
        Bisa null kalau backend Teleios mati / belum ada data — semua
        pemakaian di bawah pakai data_get() supaya aman.
    --}}
    @if (data_get($webSetting, 'favicon_url'))
        <link rel="icon" href="{{ $webSetting['favicon_url'] }}">
    @endif

    @if (data_get($webSetting, 'meta_description'))
        <meta name="description" content="{{ $webSetting['meta_description'] }}">
    @endif

    @if (data_get($webSetting, 'meta_keywords'))
        <meta name="keywords" content="{{ $webSetting['meta_keywords'] }}">
    @endif

    @if (data_get($webSetting, 'meta_images_url'))
        <meta property="og:image" content="{{ $webSetting['meta_images_url'] }}">
    @endif

    {{-- Google Tag Manager --}}
    @if (data_get($webSetting, 'google_tag'))
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ $webSetting['google_tag'] }}');</script>
    @endif

    {{-- Google Analytics (GA4) --}}
    @if (data_get($webSetting, 'google_analytics'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $webSetting['google_analytics'] }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $webSetting['google_analytics'] }}');
        </script>
    @endif

    <!-- Google Fonts: Comfortaa (dipakai untuk seluruh font-family, lihat public/css/frontend.css) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!--
        CSS custom di bawah ini di-load LANGSUNG dari folder public (bukan lewat Vite/build).
        Jadi kalau file public/css/frontend.css diedit lalu browser di-reload,
        perubahan langsung terlihat tanpa perlu npm run build/dev.
    -->
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">

    @stack('styles')
</head>
<body>

    {{-- Google Tag Manager (noscript, wajib persis setelah <body>) --}}
    @if (data_get($webSetting, 'google_tag'))
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $webSetting['google_tag'] }}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    @yield('content')

    <!-- Bootstrap 5 JS Bundle (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS custom, sama seperti CSS di atas: edit file lalu reload, tanpa build -->
    <script src="{{ asset('js/frontend.js') }}"></script>

    @stack('scripts')
</body>
</html>
