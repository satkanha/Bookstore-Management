@props(['status'])

@php
    $type = match ($status) {
        'paid', 'completed', true, 1, 'active' => 'success',
        'pending', 'processing', 'shipped' => 'warning',
        'failed', 'cancelled', false, 0, 'inactive' => 'danger',
        'refunded' => 'secondary',
        default => 'secondary',
    };
@endphp

@php
    $label = is_bool($status)
        ? __($status ? 'Active' : 'Inactive')
        : __('statuses.'.(string) str_replace(' ', '_', strtolower((string) $status)));
@endphp

<span class="badge text-bg-{{ $type }}">{{ $label }}</span>
