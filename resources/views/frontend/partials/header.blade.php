{{--
    Logo/brand + tombol hamburger SAJA — di-include oleh
    partials/topbar.blade.php di DALAM satu <nav> yang sama dengan
    menu.blade.php (bukan <nav> sendiri lagi), supaya logo & menu jadi
    satu section/baris yang menyatu, bukan dua baris terpisah.
--}}
<a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
    {{-- $webSetting datang dari App\View\Composers\WebSettingComposer — fallback ke logo statis kalau belum diisi di backend --}}
    <img src="{{ data_get($webSetting, 'logo_url') ?: asset('images/Logo.png') }}"
        alt="{{ config('app.name', 'Konexa') }}" height="40">
</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
    aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
