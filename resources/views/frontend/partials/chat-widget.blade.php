{{--
    Live chat bizbos: dibuka dari tombol bulat melayang di kanan bawah
    (.bz-chat-launcher, ukuran & gaya sama dengan tombol WhatsApp melayang).
    Elemen lain dengan atribut data-chat-open juga tetap bisa membukanya.
    Sebelum chat, pengunjung mengisi nama, email, WhatsApp (frontend.chat.start -> Customer "Website" di app.bizbos).
    Pesan dikirim ke route frontend.chat (server ini) lalu diteruskan ke app.bizbos.
    Logika di public/js/chat-widget.js, tampilan di public/css/chat-widget.css.
--}}
@php
    // Diatur di app.bizbos: Superadmin > Settings > Live Chat.
    $chatWhatsapp = preg_replace('/\D/', '', (string) data_get($webSetting ?? null, 'handphone'));
    $chatQuestions = array_filter(array_map('trim', preg_split('/\R/', (string) data_get($webSetting ?? null, 'chat_questions'))))
        ?: ['Apa itu bizbos?', 'Berapa harga paketnya?', 'Bisa coba gratis?', 'Bagaimana cara daftar?'];
@endphp
@if (data_get($webSetting ?? null, 'chat_enabled') !== false)
<div class="bz-chat" id="bzChat" hidden
    data-url="{{ route('frontend.chat') }}" data-start-url="{{ route('frontend.chat.start') }}" data-token="{{ csrf_token() }}"
    data-greeting="{{ data_get($webSetting ?? null, 'chat_greeting') ?: 'Halo! 👋 Terima kasih sudah mampir. Mau tanya soal fitur, harga paket, atau cara mulai? Tulis saja di sini ya.' }}"
    data-whatsapp="{{ $chatWhatsapp ? 'https://wa.me/'.$chatWhatsapp : '' }}"
    role="dialog" aria-modal="false" aria-labelledby="bzChatTitle">
    <div class="bz-chat__head">
        <span class="bz-chat__avatar"><i class="bi bi-headset"></i></span>
        <div class="flex-grow-1 min-w-0">
            <div class="fw-bold" id="bzChatTitle">Tim bizbos</div>
            <div class="bz-chat__status"><span class="bz-chat__dot"></span> Biasanya membalas dalam hitungan detik</div>
        </div>
        <button type="button" class="bz-chat__close" data-chat-close aria-label="Tutup live chat"><i class="bi bi-x-lg"></i></button>
    </div>

    {{-- Langkah 1: kenalan dulu. Data masuk ke Customer "Website (Live Chat)" di app.bizbos. --}}
    <form class="bz-chat__start" data-chat-start novalidate>
        <div class="bz-chat__start-intro">
            <div class="fw-bold mb-1">Halo! 👋 Kenalan dulu, yuk</div>
            <div>Isi data singkat di bawah supaya kami bisa menyapa Anda dan menghubungi kembali bila perlu.</div>
        </div>
        <label class="bz-chat__field">
            <span>Nama</span>
            <input type="text" name="name" maxlength="150" autocomplete="name" placeholder="Nama lengkap" required>
            <small data-error="name"></small>
        </label>
        <label class="bz-chat__field">
            <span>Email</span>
            <input type="email" name="email" maxlength="255" autocomplete="email" inputmode="email" placeholder="nama@email.com" required>
            <small data-error="email"></small>
        </label>
        <label class="bz-chat__field">
            <span>Nomor WhatsApp</span>
            <div class="bz-chat__phone">
                <span>+62</span>
                <input type="tel" name="phone" maxlength="16" autocomplete="tel-national" inputmode="numeric" placeholder="81234567890" required>
            </div>
            <small data-error="phone"></small>
        </label>
        <button type="submit" class="bz-chat__start-btn">Mulai Chat</button>
        <p class="bz-chat__privacy">Dengan memulai chat, Anda setuju data ini kami simpan untuk keperluan layanan sesuai <a href="{{ url('/page/kebijakan-privasi') }}" target="_blank" rel="noopener">Kebijakan Privasi</a>.</p>
    </form>

    <div class="bz-chat__body" data-chat-messages aria-live="polite" hidden></div>

    <div class="bz-chat__chips" data-chat-chips hidden>
        @foreach ($chatQuestions as $question)
            <button type="button" data-chat-ask>{{ $question }}</button>
        @endforeach
    </div>

    <form class="bz-chat__form" data-chat-form autocomplete="off" hidden>
        <input type="text" name="message" maxlength="500" placeholder="Tulis pesan Anda..." aria-label="Pesan" required>
        <button type="submit" aria-label="Kirim"><i class="bi bi-send-fill"></i></button>
    </form>

    @if ($chatWhatsapp)
        <a class="bz-chat__wa" data-chat-wa href="https://wa.me/{{ $chatWhatsapp }}" target="_blank" rel="noopener">
            <i class="bi bi-whatsapp"></i> Lebih nyaman ngobrol dengan tim kami? Chat WhatsApp
        </a>
    @endif
</div>

<button type="button" class="bz-chat-launcher" data-chat-toggle aria-controls="bzChat" aria-expanded="false"
    aria-label="Buka live chat" title="Live Chat">
    <i class="bi bi-chat-dots-fill bz-chat-launcher__icon-open" aria-hidden="true"></i>
    <i class="bi bi-x-lg bz-chat-launcher__icon-close" aria-hidden="true"></i>
</button>
@endif
