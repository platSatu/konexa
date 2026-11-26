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
                href="{{ route('frontend.index') }}">Beranda</a>
        </li>
        {{-- Dropdown Layanan (gaya: frontend.css bagian "Menu Layanan"). Layanan baru cukup ditambah di $services. --}}
        @php
            // Urutan sengaja: solusi utuh (Kursus, Event) lebih dulu, baru alat bantunya
            // (WhatsApp, Invoice) -- mengikuti cara orang mencari. Ini bukan urutan alur
            // kerja; alur kerja ada di partials/hero-flow.blade.php, jangan disamakan.
            $services = [
                ['frontend.course', 'mortarboard', 'Aplikasi Kursus', 'Murid daftar online, jadwal, absensi & SPP', 'sun'],
                ['frontend.ticket', 'ticket-perforated', 'Jual Tiket Event', 'Tiket online, e-ticket & scan QR peserta', 'peach'],
                ['frontend.whatsapp', 'whatsapp', 'WhatsApp Bisnis', 'Balas chat bareng tim, auto reply & broadcast', 'mint'],
                ['frontend.invoice', 'receipt', 'Tagihan & Pembayaran', 'Kirim tagihan + link bayar, pengingat otomatis', 'sky'],
            ];
            $serviceActive = collect($services)->contains(fn (array $service) => request()->routeIs($service[0]));
        @endphp
        <li class="nav-item dropdown nav-services">
            <a class="nav-link dropdown-toggle {{ $serviceActive ? 'active' : '' }}" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">Solusi</a>
            <div class="dropdown-menu services-menu">
                <div class="services-menu-label">Aplikasi di bizbos</div>
                @foreach ($services as [$routeName, $icon, $label, $hint, $tone])
                    <a class="service-item {{ request()->routeIs($routeName) ? 'is-active' : '' }}"
                        aria-current="{{ request()->routeIs($routeName) ? 'page' : 'false' }}" href="{{ route($routeName) }}">
                        <span class="service-icon service-icon--{{ $tone }}"><i class="bi bi-{{ $icon }}"></i></span>
                        <span class="service-text"><strong>{{ $label }}</strong><small>{{ $hint }}</small></span>
                        <i class="bi bi-arrow-right service-arrow" aria-hidden="true"></i>
                    </a>
                @endforeach
            </div>
        </li>
        {{-- Halaman dinamis ber-flag "Menu atas" (App\View\Composers\PageLinksComposer). --}}
        @foreach ($navPages as $navPage)
            @php $isCurrentPage = request()->routeIs('frontend.page') && request()->route('slug') === $navPage['slug']; @endphp
            <li class="nav-item">
                <a class="nav-link {{ $isCurrentPage ? 'active' : '' }}" aria-current="{{ $isCurrentPage ? 'page' : 'false' }}"
                    href="{{ route('frontend.page', $navPage['slug']) }}">{{ $navPage['title'] }}</a>
            </li>
        @endforeach
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('frontend.contact') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('frontend.contact') ? 'page' : 'false' }}"
                href="{{ route('frontend.contact') }}">Kontak</a>
        </li>
        {{-- Live chat sekarang dibuka dari tombol bulat melayang di kanan bawah (frontend/partials/chat-widget.blade.php). --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('frontend.index') }}#packages">Harga</a>
        </li>
        <li class="nav-item ms-lg-2">
            <a class="nav-link fw-semibold" href="https://app.bizbos.id/auth/login">Masuk</a>
        </li>
        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
            <a class="btn btn-primary fw-semibold px-4" href="https://app.bizbos.id/auth/register">Coba Gratis</a>
        </li>
    </ul>
</div>
