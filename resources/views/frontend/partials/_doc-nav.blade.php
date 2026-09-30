{{--
    Sidebar halaman Dokumen: daftar dokumen satu grup ($nav dari
    FrontendController::documentNav) dan sub-bagian dokumen yang sedang
    dibuka ($toc dari MarkdownDocument). Dipakai di desktop & mobile.
--}}
<ul class="list-unstyled doc-nav mb-0">
    @foreach ($nav as $item)
        <li class="doc-nav-item {{ $item['active'] ? 'is-active' : '' }}">
            <a href="{{ $item['url'] }}" @if ($item['active']) aria-current="page" @endif>{{ $item['title'] }}</a>

            @if ($item['active'] && count($toc) > 0)
                <ul class="list-unstyled doc-nav-sections">
                    @foreach ($toc as $heading)
                        <li class="{{ $heading['level'] === 3 ? 'ps-3' : '' }}">
                            <a href="#{{ $heading['id'] }}" data-doc-section="{{ $heading['id'] }}">{{ $heading['text'] }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
