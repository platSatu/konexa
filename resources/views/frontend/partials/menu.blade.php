{{--
    Item menu "About Us", "Artikel", "Video" DIHAPUS dari sini atas
    permintaan user — halaman/route-nya (frontend.articles/
    frontend.videos) TIDAK dihapus, cuma link-nya dikeluarkan dari
    navbar. Kalau nanti mau dimunculkan lagi tinggal tambahkan balik
    <li>-nya seperti Home/Artikel/Video di bawah ini pola-nya.

    "Contact" sekarang mengarah ke halaman kontak sungguhan
    (frontend.contact, resources/views/frontend/kontak.blade.php) —
    sebelumnya cuma href="#" (dead link).
--}}
<div class="collapse navbar-collapse" id="navbarMain">
    <ul class="navbar-nav ms-auto align-items-lg-center mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('frontend.index') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('frontend.index') ? 'page' : 'false' }}"
                href="{{ route('frontend.index') }}">Home</a>
        </li>
        <li class="nav-item"><a class="nav-link" href="#">Product</a></li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('frontend.contact') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('frontend.contact') ? 'page' : 'false' }}"
                href="{{ route('frontend.contact') }}">Contact</a>
        </li>
        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
            <a class="btn btn-primary fw-semibold px-4" href="https://app.konexa.id/auth/login">Login</a>
        </li>
    </ul>
</div>
