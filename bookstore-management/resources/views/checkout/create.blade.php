@extends('layouts.app')

@section('title', __('Checkout'))

@section('content')
    <div class="container">
        <h1 class="h3 mb-4">{{ __('Checkout') }}</h1>
        <x-validation-errors />
        <div class="row g-4">
            <div class="col-lg-7">
                <form class="surface-card p-4" method="POST" action="{{ route('checkout.store') }}" data-loading-form>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="customer_name">{{ __('Name') }}</label>
                            <input id="customer_name" class="form-control" name="customer_name" value="{{ old('customer_name', auth()->user()->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="customer_email">{{ __('Email') }}</label>
                            <input id="customer_email" class="form-control" type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="customer_phone">{{ __('Phone') }}</label>
                            <input id="customer_phone" class="form-control" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="payment_method">{{ __('Payment method') }}</label>
                            <select id="payment_method" class="form-select" name="payment_method" required>
                                <option value="cash_on_delivery" @selected(old('payment_method') === 'cash_on_delivery')>{{ __('Cash on Delivery') }}</option>
                                <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>{{ __('Bank Transfer') }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="shipping_address">{{ __('Shipping address') }}</label>
                            <textarea id="shipping_address" class="form-control" name="shipping_address" rows="3" required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="notes">{{ __('Notes') }}</label>
                            <textarea id="notes" class="form-control" name="notes" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-4" type="submit" data-loading-button data-loading-text="{{ __('Placing order...') }}"><i class="bi bi-check2-circle"></i> {{ __('Place order') }}</button>
                </form>
            </div>
            <div class="col-lg-5">
                <div class="surface-card p-4">
                    <h2 class="h5">{{ __('Order Summary') }}</h2>
                    @foreach($cart->items as $item)
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <div>{{ $item->book->title }} <span class="text-muted">x{{ $item->quantity }}</span></div>
                            <div>${{ number_format($item->subtotal(), 2) }}</div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between mt-3"><span>{{ __('Subtotal') }}</span><strong>${{ number_format($subtotal, 2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>{{ __('Shipping') }}</span><strong>${{ number_format($shippingFee, 2) }}</strong></div>
                    <div class="d-flex justify-content-between h5 mt-3"><span>{{ __('Total') }}</span><span>${{ number_format($subtotal + $shippingFee, 2) }}</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection
