@props(['updates', 'showAdmin' => false])
@if ($updates->isEmpty())
    <p class="sp-muted t-body-md mb-0">Belum ada catatan perkembangan.</p>
@else
    <ul class="sp-timeline">
        @foreach ($updates as $u)
            <li>
                <x-status-badge :status="$u->new_status" />
                <div class="when">
                    {{ $u->created_at->translatedFormat('d M Y · H:i') }} WIB
                    @if ($showAdmin && $u->user) · oleh {{ $u->user->name }} @endif
                    @if ($showAdmin && ! $u->is_public) · <span class="sp-badge pr-rendah" style="padding:1px 8px;">Internal</span> @endif
                </div>
                @if ($u->note)<p class="note">{{ $u->note }}</p>@endif
            </li>
        @endforeach
    </ul>
@endif
