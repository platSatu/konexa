<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{--
        SEO: satu sumber untuk title & description tiap halaman, dipakai
        ulang di <title>, og:* dan twitter:*.
        - Nama brand diambil dari APP_NAME (.env) lewat $appName, jadi
          tidak ada nama yang diketik manual di view.
        - Halaman cukup @section('title', 'Artikel'); hasilnya "{APP_NAME} : Artikel".
        - 'title_full' (opsional) dipakai apa adanya sebagai <title> (Beranda).
        - meta_description: dari halaman, kalau kosong pakai Pengaturan Web
          Teleios (meta_description).
    --}}
    @php
        $appName = (string) config('app.name');
        $pageTitle = trim($__env->yieldContent('title', 'Beranda'));
        $customFullTitle = trim($__env->yieldContent('title_full', ''));
        $fullTitle = $customFullTitle !== '' ? $customFullTitle : $appName.' : '.$pageTitle;
        $pageDescription = trim($__env->yieldContent('meta_description', (string) data_get($webSetting, 'meta_description', '')));
        // Halaman boleh mengganti gambar share lewat @section('meta_image', ...) (mis. detail artikel).
        $shareImage = trim($__env->yieldContent('meta_image', '')) ?: data_get($webSetting, 'meta_images_url');
    @endphp

    <title>{{ $fullTitle }}</title>
    <link rel="canonical" href="{{ url()->current() }}">

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

    @if ($pageDescription !== '')
        <meta name="description" content="{{ $pageDescription }}">
    @endif

    @if (data_get($webSetting, 'meta_keywords'))
        <meta name="keywords" content="{{ $webSetting['meta_keywords'] }}">
    @endif

    {{--
        Open Graph + Twitter Card — inilah yang dibaca WhatsApp/
        Facebook/Telegram/dll pas link di-share, BUKAN <title> biasa.
        Tanpa ini, judul/deskripsi/gambar yang muncul di preview share
        bisa asal-asalan (atau kosong) meskipun <title> halaman sudah
        benar. og:title & og:description sengaja pakai $fullTitle/
        $pageDescription yang sama seperti <title>/meta description di
        atas supaya konsisten di mana pun link-nya di-share.
    --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $appName }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $fullTitle }}">
    @if ($pageDescription !== '')
        <meta property="og:description" content="{{ $pageDescription }}">
    @endif
    @if ($shareImage)
        <meta property="og:image" content="{{ $shareImage }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $fullTitle }}">
    @if ($pageDescription !== '')
        <meta name="twitter:description" content="{{ $pageDescription }}">
    @endif
    @if ($shareImage)
        <meta name="twitter:image" content="{{ $shareImage }}">
    @endif

    {{-- Google Tag Manager --}}
    @if (data_get($webSetting, 'google_tag'))
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',{{ \Illuminate\Support\Js::from((string) $webSetting['google_tag']) }});</script>
    @endif

    {{-- GA4, Google Ads, Meta Pixel, TikTok Pixel + event retargeting --}}
    @include('frontend.partials.tracking')

    <!-- Google Fonts: Comfortaa (dipakai untuk seluruh font-family, lihat public/css/frontend.css) -->
    {{--
        Performa (PageSpeed): koneksi ke CDN & server gambar Teleios dibuka
        lebih awal (preconnect). Font & ikon dimuat tanpa menahan tampilan
        halaman (preload -> stylesheet), fallback <noscript>. Bootstrap CSS
        tetap blocking karena layout bergantung padanya.
    --}}
    @php $imageHost = parse_url((string) config('services.teleios.url'), PHP_URL_HOST); @endphp
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    @if ($imageHost)
        <link rel="preconnect" href="https://{{ $imageHost }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap"></noscript>

    <!-- Bootstrap 5 (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"></noscript>

    <!--
        CSS custom di bawah ini di-load LANGSUNG dari folder public (bukan lewat Vite/build).
        Jadi kalau file public/css/frontend.css diedit lalu browser di-reload,
        perubahan langsung terlihat tanpa perlu npm run build/dev.

        Query string ?v=<filemtime> di bawah ini cache-buster: begitu file
        frontend.css disave (mtime-nya berubah), URL-nya ikut berubah, jadi
        browser pengunjung otomatis ambil versi terbaru alih-alih terus
        pakai copy lama dari cache -- tanpa ini, perubahan warna/style
        sering "tidak muncul" di browser sampai user hard-refresh manual
        (Ctrl+Shift+R), padahal filenya di server sudah benar.
    -->
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}?v={{ filemtime(public_path('css/frontend.css')) }}">

    @stack('styles')
</head>
<body>

    {{-- Google Tag Manager (noscript, wajib persis setelah <body>) --}}
    @if (data_get($webSetting, 'google_tag'))
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ urlencode($webSetting['google_tag']) }}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    @yield('content')

    <!-- Bootstrap 5 JS Bundle (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

    <!-- JS custom, sama seperti CSS di atas: edit file lalu reload, tanpa build -->
    <script src="{{ asset('js/frontend.js') }}?v={{ filemtime(public_path('js/frontend.js')) }}" defer></script>

    @stack('scripts')
</body>
</html>
