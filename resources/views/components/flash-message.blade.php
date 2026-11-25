@foreach (['success' => 'success', 'status' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $type)
    @if (session($key))
        <div class="alert alert-{{ $type }} d-flex align-items-start gap-2" role="alert">
            <i class="bi {{ $type === 'success' ? 'bi-check-circle' : ($type === 'danger' ? 'bi-exclamation-octagon' : 'bi-info-circle') }} mt-1" aria-hidden="true"></i>
            <div>{{ session($key) }}</div>
        </div>
    @endif
@endforeach
