{{--
    Link-link menu navigasi SAJA (isi collapse #navbarMain) — di-include
    oleh partials/topbar.blade.php di DALAM satu <nav> yang sama dengan
    header.blade.php (logo), jadi logo & menu tampil sebagai satu
    section/baris yang menyatu (bukan dua baris terpisah lagi).

    Menu berpindah halaman (bukan scroll/anchor di landing page).
    Home, Artikel & Video sudah pakai route yang ada. About Us,
    Product, Contact masih placeholder href="#" — ganti dengan
    route() begitu halamannya dibuat, misalnya:
    href="{{ route('frontend.about') }}"

    Jarak antar item menu diatur lewat padding .nav-link di
    public/css/frontend.css (class .site-topbar .navbar-nav .nav-link),
    bukan margin per <li>, supaya area klik-nya ikut lebar (lebih enak
    dipakai) bukan cuma teksnya.

    Tombol Login di paling ujung kanan SENGAJA bukan .nav-link biasa
    (dibuat .btn.btn-warning) — link-nya ke halaman login Teleios
    (backend), karena login akun cuma ada di sana, fe-konexa cuma
    tampilan publik/landing.
--}}
<div class="collapse navbar-collapse" id="navbarMain">
    <ul class="navbar-nav ms-auto align-items-lg-center mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('frontend.index') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('frontend.index') ? 'page' : 'false' }}"
                href="{{ route('frontend.index') }}">Home</a>
        </li>
        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Product</a></li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('frontend.articles') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('frontend.articles') ? 'page' : 'false' }}"
                href="{{ route('frontend.articles') }}">Artikel</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('frontend.videos') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('frontend.videos') ? 'page' : 'false' }}"
                href="{{ route('frontend.videos') }}">Video</a>
        </li>
        <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
            <a class="btn btn-warning fw-semibold px-3" href="{{ rtrim(config('services.teleios.url'), '/') }}/login">Login</a>
        </li>
    </ul>
</div>
