{{--
    Live chat bizbos: dibuka dari tombol "Live Chat" di menu (data-chat-open).
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
    data-url="{{ route('frontend.chat') }}" data-token="{{ csrf_token() }}"
    data-greeting="{{ data_get($webSetting ?? null, 'chat_greeting') ?: 'Halo! 👋 Saya asisten bizbos. Mau tanya soal fitur, harga paket, atau cara mulai? Silakan tulis pertanyaannya.' }}"
    data-whatsapp="{{ $chatWhatsapp ? 'https://wa.me/'.$chatWhatsapp : '' }}"
    role="dialog" aria-modal="false" aria-labelledby="bzChatTitle">
    <div class="bz-chat__head">
        <span class="bz-chat__avatar"><i class="bi bi-robot"></i></span>
        <div class="flex-grow-1 min-w-0">
            <div class="fw-bold" id="bzChatTitle">Asisten bizbos</div>
            <div class="bz-chat__status"><span class="bz-chat__dot"></span> Siap membantu 24 jam</div>
        </div>
        <button type="button" class="bz-chat__close" data-chat-close aria-label="Tutup live chat"><i class="bi bi-x-lg"></i></button>
    </div>

    <div class="bz-chat__body" data-chat-messages aria-live="polite"></div>

    <div class="bz-chat__chips" data-chat-chips>
        @foreach ($chatQuestions as $question)
            <button type="button" data-chat-ask>{{ $question }}</button>
        @endforeach
    </div>

    <form class="bz-chat__form" data-chat-form autocomplete="off">
        <input type="text" name="message" maxlength="500" placeholder="Tulis pertanyaan Anda..." aria-label="Pertanyaan" required>
        <button type="submit" aria-label="Kirim"><i class="bi bi-send-fill"></i></button>
    </form>

    @if ($chatWhatsapp)
        <a class="bz-chat__wa" href="https://wa.me/{{ $chatWhatsapp }}" target="_blank" rel="noopener">
            <i class="bi bi-whatsapp"></i> Lebih nyaman ngobrol dengan tim kami? Chat WhatsApp
        </a>
    @endif
</div>
@endif
