// Live chat bizbos (frontend/partials/chat-widget.blade.php). Tanpa library, tanpa build.
// Percakapan disimpan di sessionStorage tab ini saja (hilang saat tab ditutup).
document.addEventListener('DOMContentLoaded', function () {
    var box = document.getElementById('bzChat');
    if (!box) return;

    var list = box.querySelector('[data-chat-messages]');
    var form = box.querySelector('[data-chat-form]');
    var input = form.querySelector('input');
    var chips = box.querySelector('[data-chat-chips]');
    var STORE = 'bz-chat-history';
    var GREETING = box.dataset.greeting;
    var history = [];
    var busy = false;

    try { history = JSON.parse(sessionStorage.getItem(STORE) || '[]'); } catch (e) { history = []; }

    function save() {
        try { sessionStorage.setItem(STORE, JSON.stringify(history.slice(-20))); } catch (e) { /* mode privat: abaikan */ }
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

    function render() {
        list.innerHTML = '';
        bubble('assistant', GREETING);
        history.forEach(function (m) { bubble(m.role, m.content); });
        chips.hidden = history.length > 0;
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

        fetch(box.dataset.url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': box.dataset.token },
            body: JSON.stringify({ message: text, history: history.slice(-8) })
        }).then(function (r) {
            if (r.status === 419) return { reply: 'Halaman sudah lama terbuka. Muat ulang halaman lalu kirim lagi ya.' };
            return r.json();
        }).then(function (data) {
            var reply = (data && (data.reply || data.message)) || 'Maaf, belum ada jawaban. Coba lagi ya.';
            typing.remove();
            bubble('assistant', reply);
            history.push({ role: 'user', content: text }, { role: 'assistant', content: reply });
            save();
        }).catch(function () {
            typing.remove();
            bubble('assistant', 'Koneksi terputus. Periksa internet Anda lalu coba lagi.' + (box.dataset.whatsapp ? '\nAtau chat tim kami: ' + box.dataset.whatsapp : ''));
        }).finally(function () {
            busy = false;
            form.querySelector('button').disabled = false;
            input.focus();
        });
    }

    function open() {
        box.hidden = false;
        if (!list.childElementCount) render();
        input.focus();
    }

    document.querySelectorAll('[data-chat-open]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Menu HP sedang terbuka: tutup dulu supaya chat terlihat penuh.
            var menu = document.getElementById('navbarMain');
            if (menu && menu.classList.contains('show') && window.bootstrap) bootstrap.Collapse.getOrCreateInstance(menu).hide();
            open();
        });
    });
    box.querySelector('[data-chat-close]').addEventListener('click', function () { box.hidden = true; });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !box.hidden) box.hidden = true; });
    box.querySelectorAll('[data-chat-ask]').forEach(function (chip) { chip.addEventListener('click', function () { ask(chip.textContent); }); });
    form.addEventListener('submit', function (e) { e.preventDefault(); var text = input.value; input.value = ''; ask(text); });
});
