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
        {{-- Dropdown Layanan: tambah halaman produk baru cukup di daftar $services ini. --}}
        @php
            $services = [
                ['frontend.ticket', 'ticket-perforated', 'Tiket Event', 'Jual tiket online & scan QR peserta'],
            ];
            $serviceActive = collect($services)->contains(fn (array $service) => request()->routeIs($service[0]));
        @endphp
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ $serviceActive ? 'active' : '' }}" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">Layanan</a>
            <ul class="dropdown-menu dropdown-menu-lg-end border-0 shadow p-2" style="min-width: 17rem; --bs-dropdown-link-active-bg: var(--bs-primary-bg-subtle); --bs-dropdown-link-active-color: var(--bs-body-color); --bs-dropdown-link-hover-bg: #fff7f2;">
                @foreach ($services as [$routeName, $icon, $label, $hint])
                    <li>
                        <a class="dropdown-item d-flex gap-3 align-items-start rounded-3 py-2 {{ request()->routeIs($routeName) ? 'active' : '' }}"
                            aria-current="{{ request()->routeIs($routeName) ? 'page' : 'false' }}" href="{{ route($routeName) }}">
                            <i class="bi bi-{{ $icon }} fs-5 text-primary"></i>
                            <span><span class="d-block fw-semibold">{{ $label }}</span><small class="text-muted text-wrap">{{ $hint }}</small></span>
                        </a>
                    </li>
                @endforeach
            </ul>
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
                href="{{ route('frontend.contact') }}">Contact</a>
        </li>
        {{-- Live chat sekarang dibuka dari tombol bulat melayang di kanan bawah (frontend/partials/chat-widget.blade.php). --}}
        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
            <a class="btn btn-primary fw-semibold px-4" href="https://app.bizbos.id/auth/login">Login</a>
        </li>
    </ul>
</div>
