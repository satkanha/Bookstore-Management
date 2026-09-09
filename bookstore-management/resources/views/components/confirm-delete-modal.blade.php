@auth
    @if(auth()->user()->isAdmin())
        <div class="modal fade bookstore-modal" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <p class="text-danger fw-semibold mb-1">{{ __('Delete confirmation') }}</p>
                            <h2 class="modal-title h4" id="deleteConfirmModalLabel" data-delete-title>{{ __('Are you sure?') }}</h2>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-4" data-delete-message>{{ __('This item will be moved to trash.') }}</p>
                        <form id="deleteConfirmForm" method="POST" action="" data-loading-form>
                            @csrf
                            @method('DELETE')
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                <button type="submit" class="btn btn-danger" data-loading-button data-loading-text="{{ __('Deleting...') }}">
                                    <i class="bi bi-trash"></i> {{ __('Delete') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endauth
