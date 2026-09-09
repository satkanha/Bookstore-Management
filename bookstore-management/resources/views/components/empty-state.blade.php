@props(['title' => __('Nothing here yet'), 'message' => __('Try changing filters or creating a new record.')])

<div class="surface-card text-center p-5">
    <div class="display-6 text-muted"><i class="bi bi-inbox"></i></div>
    <h2 class="h5 mt-3">{{ $title }}</h2>
    @if($message)
        <p class="text-muted mb-0">{{ $message }}</p>
    @endif
</div>
