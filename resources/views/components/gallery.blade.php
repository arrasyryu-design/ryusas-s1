@props(['attachments'])
@if ($attachments->isNotEmpty())
    <div class="sp-gallery">
        @foreach ($attachments as $file)
            <figure>
                <a href="{{ $file->url() }}" target="_blank" rel="noopener">
                    <img src="{{ $file->url() }}" alt="{{ $file->typeLabel() }} — {{ $file->original_name }}" loading="lazy">
                </a>
                <figcaption>{{ $file->typeLabel() }} · {{ $file->created_at->translatedFormat('d M Y') }}</figcaption>
            </figure>
        @endforeach
    </div>
@else
    <p class="sp-muted t-body-md mb-0">Belum ada foto.</p>
@endif
