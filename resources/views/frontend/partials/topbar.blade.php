{{--
    Topbar = logo (header.blade.php) + menu (menu.blade.php) digabung
    jadi SATU baris <nav> Bootstrap (logo kiri, menu kanan, satu
    toggler/collapse yang sama untuk mobile).

    Khusus di homepage (ada hero background di baliknya): topbar TANPA
    background (transparan, teks putih/navbar-dark) selama posisinya
    masih di paling atas, supaya menyatu dengan hero. Begitu halaman
    di-scroll, public/js/frontend.js nambahin class "topbar-scrolled"
    yang bikin background jadi SOLID putih + teks gelap (navbar-light)
    supaya nama menu tetap kelihatan jelas — lihat .site-topbar--overlay
    di public/css/frontend.css.

    Di halaman selain homepage (tidak ada hero di baliknya) topbar tetap
    SOLID dari awal seperti sebelumnya, tidak ada efek transparan —
    karena tanpa hero di belakangnya, transparan cuma akan nabrak
    konten halaman itu sendiri. Halaman non-homepage pakai class
    Bootstrap "sticky-top" (position: sticky) seperti sebelumnya.

    Homepage SENGAJA TIDAK pakai "sticky-top" — dipakai position: fixed
    sendiri lewat .site-topbar--overlay supaya topbar benar-benar lepas
    dari document flow dan hero bisa full-bleed dari y=0 di BELAKANG
    topbar (baru transparansinya kelihatan menyatu dengan hero). Kalau
    tetap pakai sticky-top, hero akan mulai di BAWAH topbar (bukan di
    belakangnya), jadi area transparan topbar cuma akan nampilin
    background body/putih, bukan hero — makanya dua pendekatan ini
    sengaja dibedakan.
--}}
@php
    $isHomepage = request()->routeIs('frontend.index');
@endphp
<nav id="siteTopbar"
    class="navbar navbar-expand-lg site-topbar {{ $isHomepage ? 'site-topbar--overlay navbar-dark' : 'sticky-top navbar-light shadow-sm' }}">
    <div class="container d-flex align-items-center justify-content-between flex-wrap">
        @include('frontend.partials.header')
        @include('frontend.partials.menu')
    </div>
</nav>
