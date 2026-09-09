@php
    $icons = ['success' => 'bi-check2-circle', 'warning' => 'bi-exclamation-triangle', 'status' => 'bi-info-circle', 'error' => 'bi-x-circle'];
@endphp

<div class="toast-container position-fixed top-0 end-0 p-3">
    @foreach (['success' => 'success', 'warning' => 'warning', 'status' => 'info', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="toast bookstore-toast text-bg-{{ $type }} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4600">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi {{ $icons[$key] }} me-2"></i>{{ session($key) }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="{{ __('Close') }}"></button>
                </div>
            </div>
        @endif
    @endforeach
</div>
