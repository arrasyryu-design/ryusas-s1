@props(['status'])
<span class="sp-badge {{ $status->cssClass() }}">{{ $status->label() }}</span>
