@props(['book', 'showPurchaseControls' => false])

<div class="book-card h-100 p-3">
    <a href="{{ route('books.show', $book) }}">
        <img class="book-cover mb-3" src="{{ $book->cover_url }}" alt="{{ __(':title cover', ['title' => $book->title]) }}">
    </a>
    <div class="d-flex flex-column h-100">
        <h3 class="h6 book-title">
            <a class="text-decoration-none text-dark" href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
        </h3>
        <div class="text-muted small">{{ $book->author->name }}</div>
        <div class="small text-muted mb-2">{{ __($book->category->name) }}</div>
        @if($showPurchaseControls)
            <div class="d-flex justify-content-between align-items-center mt-auto">
                <strong>${{ number_format((float) $book->price, 2) }}</strong>
                <span class="badge {{ $book->stock > 0 ? 'text-bg-success' : 'text-bg-secondary' }}">
                    {{ $book->stock > 0 ? __(':count in stock', ['count' => $book->stock]) : __('Out of stock') }}
                </span>
            </div>
            <div class="book-actions mt-3 d-grid gap-2">
                @if(! auth()->check() || auth()->user()->isCustomer())
                    <button
                        type="button"
                        class="btn btn-sm btn-warning js-order-trigger"
                        data-bs-toggle="modal"
                        data-bs-target="#bookOrderModal"
                        data-order-url="{{ auth()->check() ? route('books.order', $book) : '' }}"
                        data-book-id="{{ $book->id }}"
                        data-book-title="{{ $book->title }}"
                        data-book-author="{{ $book->author->name }}"
                        data-book-category="{{ __($book->category->name) }}"
                        data-book-price="{{ $book->price }}"
                        data-book-stock="{{ $book->stock }}"
                        data-book-cover="{{ $book->cover_url }}"
                        data-book-cover-alt="{{ __(':title cover', ['title' => $book->title]) }}"
                        data-book-stock-label="{{ $book->stock > 0 ? __(':count in stock', ['count' => $book->stock]) : __('Out of stock') }}"
                        @disabled($book->stock < 1)
                    >
                        <i class="bi bi-bag-check"></i> {{ __('Order') }}
                    </button>
                @endif

                @auth
                    @if(auth()->user()->isAdmin())
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger js-delete-trigger"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteConfirmModal"
                            data-delete-url="{{ route('admin.books.destroy', $book) }}"
                            data-delete-title="{{ __('Delete :item?', ['item' => $book->title]) }}"
                            data-delete-message="{{ __('This book will be moved to trash and can be restored later.') }}"
                        >
                            <i class="bi bi-trash"></i> {{ __('Delete') }}
                        </button>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</div>
