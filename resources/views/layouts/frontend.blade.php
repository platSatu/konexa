<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{--
        SEO: SATU sumber kebenaran untuk title & description tiap
        halaman, dipakai ulang di <title>, og:*, dan twitter:* di bawah
        (bukan tulis ulang manual per tag — biar tidak ada yang
        ketinggalan/beda pas ada yang lupa update salah satunya).

        - $__env->yieldContent(...) dipakai (bukan @yield biasa) karena
          @yield cuma boleh dipanggil SEKALI per section; di sini section
          yang sama ('title'/'meta_description') perlu dipakai berkali-
          kali (title tag, og:title, twitter:title, dst).
        - Prefix "Konexa : " di-hardcode DI SINI SAJA (satu tempat),
          bukan diulang di tiap page — sebelumnya tiap halaman pakai
          config('app.name', 'Konexa') buat suffix judul, tapi karena
          .env APP_NAME defaultnya "Laravel" (lihat config/app.php) dan
          fallback 'Konexa' di sisi Blade itu CUMA kepakai kalau
          config('app.name') null (yang hampir tidak pernah terjadi),
          title yang sungguhan tampil di production kemungkinan besar
          "Beranda - Laravel", BUKAN "Beranda - Konexa". Hardcode di
          sini menghilangkan ketergantungan ke APP_NAME sama sekali.
        - Tiap halaman cukup @section('title', 'Beranda') /
          @section('meta_description', '...') — lihat frontend/index.blade.php
          dkk untuk contohnya.
        - 'title_full' (OPSIONAL) — kalau diisi di sebuah halaman, INI
          yang dipakai apa adanya sebagai <title> (skip prefix "Konexa : "
          otomatis di atas). Dipakai khusus di Beranda supaya title-nya
          bisa jadi kalimat jualan penuh ("Konexa | Solusi Modern untuk
          WhatsApp Bisnis Anda") — bukan cuma "Konexa : Beranda" yang
          kurang menjual buat halaman paling penting secara SEO. Halaman
          lain (Artikel/Video/dst) TIDAK perlu set ini, biar tetap ikut
          pola "Konexa : {Nama Halaman}" yang konsisten.
    --}}
    @php
        $pageTitle = trim($__env->yieldContent('title', 'Beranda'));
        $customFullTitle = trim($__env->yieldContent('title_full', ''));
        $fullTitle = $customFullTitle !== '' ? $customFullTitle : 'Konexa : '.$pageTitle;
        $pageDescription = trim($__env->yieldContent('meta_description', (string) data_get($webSetting, 'meta_description', '')));
        $shareImage = data_get($webSetting, 'meta_images_url');
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
    <meta property="og:site_name" content="Konexa">
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
