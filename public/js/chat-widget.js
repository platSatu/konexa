// Live chat bizbos (frontend/partials/chat-widget.blade.php). Tanpa library, tanpa build.
// Langkah 1: pengunjung mengisi nama, email, WhatsApp (data-chat-start). Langkah 2: chat.
// Nama & percakapan disimpan di sessionStorage tab ini saja (hilang saat tab ditutup).
document.addEventListener('DOMContentLoaded', function () {
    var box = document.getElementById('bzChat');
    if (!box) return;

    var list = box.querySelector('[data-chat-messages]');
    var form = box.querySelector('[data-chat-form]');
    var input = form.querySelector('input');
    var chips = box.querySelector('[data-chat-chips]');
    var start = box.querySelector('[data-chat-start]');
    var wa = box.querySelector('[data-chat-wa]');
    var STORE = 'bz-chat-history';
    var STORE_LEAD = 'bz-chat-lead';
    var history = [];
    var lead = '';
    var busy = false;

    try {
        history = JSON.parse(sessionStorage.getItem(STORE) || '[]');
        lead = sessionStorage.getItem(STORE_LEAD) || '';
    } catch (e) { history = []; lead = ''; }

    function save() {
        try {
            sessionStorage.setItem(STORE, JSON.stringify(history.slice(-20)));
            sessionStorage.setItem(STORE_LEAD, lead);
        } catch (e) { /* mode privat: abaikan */ }
    }

    function firstName() {
        var n = (lead || '').trim().split(/\s+/)[0] || '';
        return n ? n.charAt(0).toUpperCase() + n.slice(1).toLowerCase() : '';
    }

    function greeting() {
        var text = box.dataset.greeting || '';
        return firstName() ? text.replace(/^Halo!?/i, 'Halo Kak ' + firstName() + '!') : text;
    }

    // Tombol WhatsApp ikut membawa nama, supaya tim langsung tahu siapa yang menghubungi.
    function syncWhatsapp() {
        if (!wa || !box.dataset.whatsapp) return;
        wa.href = box.dataset.whatsapp + (lead ? '?text=' + encodeURIComponent('Halo tim bizbos, saya ' + lead + '. Saya mau tanya: ') : '');
    }

    // Teks aman (bukan HTML) + link yang bisa diklik.
    function bubble(role, text) {
        var el = document.createElement('div');
        el.className = 'bz-chat__msg bz-chat__msg--' + (role === 'user' ? 'me' : 'bot');
        String(text).split(/(https?:\/\/[^\s)]+)/g).forEach(function (part, i) {
            if (i % 2) {
                var a = document.createElement('a');
                a.href = part; a.textContent = part; a.target = '_blank'; a.rel = 'noopener';
                el.appendChild(a);
            } else {
                el.appendChild(document.createTextNode(part));
            }
        });
        list.appendChild(el);
        list.scrollTop = list.scrollHeight;
        return el;
    }

    // Tampilkan langkah yang sesuai: form kenalan atau ruang chat.
    function showStep() {
        var started = !!lead;
        start.hidden = started;
        list.hidden = !started;
        form.hidden = !started;
        chips.hidden = !started || history.length > 0;
        syncWhatsapp();
        if (started) {
            list.innerHTML = '';
            bubble('assistant', greeting());
            history.forEach(function (m) { bubble(m.role, m.content); });
        }
    }

    function post(url, body) {
        return fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': box.dataset.token },
            body: JSON.stringify(body)
        });
    }

    function ask(text) {
        text = text.trim();
        if (!text || busy) return;
        busy = true;
        chips.hidden = true;
        bubble('user', text);
        var typing = bubble('assistant', '');
        typing.innerHTML = '<span class="bz-chat__typing"><span></span><span></span><span></span></span>';
        form.querySelector('button').disabled = true;

        post(box.dataset.url, { message: text, history: history.slice(-8) }).then(function (r) {
            if (r.status === 419) return { reply: 'Halaman sudah lama terbuka. Muat ulang halaman lalu kirim lagi ya.' };
            return r.json();
        }).then(function (data) {
            // Sesi di server habis: minta kenalan ulang (data tetap aman, hanya mengisi lagi).
            if (data && data.needs_start) { typing.remove(); lead = ''; save(); showStep(); return; }
            var reply = (data && (data.reply || data.message)) || 'Maaf, belum ada jawaban. Coba lagi ya.';
            // Tanda "mengetik" tetap tampil sebentar, sebanding panjang jawaban (maks 1,5 detik), supaya terasa natural.
            return new Promise(function (resolve) {
                setTimeout(function () {
                    typing.remove();
                    bubble('assistant', reply);
                    history.push({ role: 'user', content: text }, { role: 'assistant', content: reply });
                    save();
                    resolve();
                }, Math.min(1500, 300 + reply.length * 4));
            });
        }).catch(function () {
            typing.remove();
            bubble('assistant', 'Koneksi terputus. Periksa internet Anda lalu coba lagi.' + (box.dataset.whatsapp ? '\nAtau chat tim kami: ' + box.dataset.whatsapp : ''));
        }).finally(function () {
            busy = false;
            form.querySelector('button').disabled = false;
            input.focus();
        });
    }

    start.addEventListener('submit', function (e) {
        e.preventDefault();
        if (busy) return;
        var button = start.querySelector('button[type=submit]');
        start.querySelectorAll('[data-error]').forEach(function (el) { el.textContent = ''; });
        start.querySelectorAll('input').forEach(function (el) { el.classList.remove('is-invalid'); });

        var fields = start.elements;
        var body = { name: fields.name.value.trim(), email: fields.email.value.trim(), phone: fields.phone.value.trim() };
        var missing = Object.keys(body).filter(function (k) { return !body[k]; });
        if (missing.length) {
            missing.forEach(function (k) {
                fields[k].classList.add('is-invalid');
                start.querySelector('[data-error="' + k + '"]').textContent = 'Wajib diisi.';
            });
            fields[missing[0]].focus();
            return;
        }

        busy = true;
        button.disabled = true;
        button.textContent = 'Menghubungkan...';
        post(box.dataset.startUrl, body).then(function (r) {
            return r.json().then(function (data) { return { status: r.status, data: data }; });
        }).then(function (res) {
            if (res.status === 200) {
                lead = res.data.name || body.name;
                history = [];
                save();
                showStep();
                input.focus();
                return;
            }
            var errors = (res.data && res.data.errors) || {};
            Object.keys(errors).forEach(function (k) {
                if (!fields[k]) return;
                fields[k].classList.add('is-invalid');
                start.querySelector('[data-error="' + k + '"]').textContent = errors[k][0];
            });
            if (!Object.keys(errors).length) {
                start.querySelector('[data-error="phone"]').textContent = (res.data && res.data.message) || 'Gagal memulai chat. Coba lagi ya.';
            }
        }).catch(function () {
            start.querySelector('[data-error="phone"]').textContent = 'Koneksi terputus. Periksa internet Anda lalu coba lagi.';
        }).finally(function () {
            busy = false;
            button.disabled = false;
            button.textContent = 'Mulai Chat';
        });
    });

    var launcher = document.querySelector('[data-chat-toggle]');

    function syncLauncher() {
        if (!launcher) return;
        var isOpen = !box.hidden;
        launcher.classList.toggle('is-open', isOpen);
        launcher.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        launcher.setAttribute('aria-label', isOpen ? 'Tutup live chat' : 'Buka live chat');
        document.body.classList.toggle('bz-chat-open', isOpen);
    }

    function open() {
        box.hidden = false;
        showStep();
        syncLauncher();
        (lead ? input : start.elements.name).focus();
    }

    function close() {
        box.hidden = true;
        syncLauncher();
        if (launcher) launcher.focus();
    }

    if (launcher) {
        launcher.addEventListener('click', function () { box.hidden ? open() : close(); });
    }

    document.querySelectorAll('[data-chat-open]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Menu HP sedang terbuka: tutup dulu supaya chat terlihat penuh.
            var menu = document.getElementById('navbarMain');
            if (menu && menu.classList.contains('show') && window.bootstrap) bootstrap.Collapse.getOrCreateInstance(menu).hide();
            open();
        });
    });
    box.querySelector('[data-chat-close]').addEventListener('click', close);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !box.hidden) close(); });
    box.querySelectorAll('[data-chat-ask]').forEach(function (chip) { chip.addEventListener('click', function () { ask(chip.textContent); }); });
    form.addEventListener('submit', function (e) { e.preventDefault(); var text = input.value; input.value = ''; ask(text); });
});
