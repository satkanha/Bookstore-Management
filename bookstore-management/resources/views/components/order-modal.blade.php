@php
    $canOrder = auth()->check() && auth()->user()->isCustomer();
    $user = auth()->user();
@endphp

<div class="modal fade bookstore-modal order-modal" id="bookOrderModal" tabindex="-1" aria-labelledby="bookOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <p class="text-amber fw-semibold mb-1">{{ __('Quick order') }}</p>
                    <h2 class="modal-title h4" id="bookOrderModalLabel">{{ __('Order this book') }}</h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="order-book-preview p-3 h-100">
                            <img class="book-cover w-100 mb-3" data-order-cover src="{{ asset('images/book-placeholder.svg') }}" alt="{{ __('Book cover') }}">
                            <h3 class="h5 mb-1" data-order-title>{{ __('Book') }}</h3>
                            <p class="text-muted small mb-2" data-order-meta></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="h5 mb-0 text-amber" data-order-price>$0.00</strong>
                                <span class="badge text-bg-success" data-order-stock></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        @if($canOrder)
                            <form id="bookOrderForm" method="POST" action="" data-loading-form>
                                @csrf
                                <input type="hidden" name="book_id" data-order-book-id>
                                <div class="row g-3">
                                    <div class="col-sm-5">
                                        <label class="form-label" for="order_quantity">{{ __('Quantity') }}</label>
                                        <input id="order_quantity" class="form-control" type="number" name="quantity" value="1" min="1" required data-order-quantity>
                                    </div>
                                    <div class="col-sm-7">
                                        <label class="form-label" for="order_customer_name">{{ __('Customer name') }}</label>
                                        <input id="order_customer_name" class="form-control" name="customer_name" value="{{ old('customer_name', $user?->name) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="order_customer_phone">{{ __('Phone') }}</label>
                                        <input id="order_customer_phone" class="form-control" name="customer_phone" value="{{ old('customer_phone', $user?->phone) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="order_shipping_address">{{ __('Address') }}</label>
                                        <textarea id="order_shipping_address" class="form-control" name="shipping_address" rows="3" required>{{ old('shipping_address', $user?->address) }}</textarea>
                                    </div>
                                </div>

                                <div class="order-total-bar mt-4">
                                    <div>
                                        <div class="text-muted small">{{ __('Shipping') }}</div>
                                        <strong data-order-shipping>$0.00</strong>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-muted small">{{ __('Total') }}</div>
                                        <strong class="h4 mb-0" data-order-total>$0.00</strong>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                    <button type="submit" class="btn btn-primary" data-loading-button data-loading-text="{{ __('Confirming...') }}">
                                        <i class="bi bi-check2-circle"></i> {{ __('Confirm') }}
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="order-login-panel h-100 p-4 d-flex flex-column justify-content-center">
                                <h3 class="h5">{{ __('Login to order') }}</h3>
                                <p class="text-muted">{{ __('Please login or create an account before confirming your order.') }}</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a class="btn btn-primary" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    <a class="btn btn-outline-secondary" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
