@extends('layouts.admin')

@section('title', $order->order_number)
@section('page-title', __('Order Details'))

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="h3">{{ $order->order_number }}</h1>
                        <p class="text-muted">{{ $order->ordered_at->format('M d, Y g:i A') }}</p>
                    </div>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.orders.invoice', $order) }}"><i class="bi bi-printer"></i> {{ __('Invoice') }}</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>{{ __('Book') }}</th><th>{{ __('ISBN') }}</th><th>{{ __('Qty') }}</th><th>{{ __('Unit') }}</th><th>{{ __('Subtotal') }}</th></tr></thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->book_title }}</td>
                                    <td>{{ $item->book_isbn }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format((float) $item->unit_price, 2) }}</td>
                                    <td>${{ number_format((float) $item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="d-flex justify-content-between"><span>{{ __('Subtotal') }}</span><strong>${{ number_format((float) $order->subtotal, 2) }}</strong></div>
                        <div class="d-flex justify-content-between"><span>{{ __('Shipping') }}</span><strong>${{ number_format((float) $order->shipping_fee, 2) }}</strong></div>
                        <div class="d-flex justify-content-between h5 mt-2"><span>{{ __('Total') }}</span><span>${{ number_format((float) $order->total_amount, 2) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="admin-card p-4 mb-4">
                <h2 class="h5">{{ __('Customer') }}</h2>
                <p class="mb-1">{{ $order->customer_name }}</p>
                <p class="mb-1">{{ $order->customer_email }}</p>
                <p>{{ $order->customer_phone }}</p>
                <p class="text-muted">{{ $order->shipping_address }}</p>
            </div>
            <form class="admin-card p-4" method="POST" action="{{ route('admin.orders.update', $order) }}" data-loading-form>
                @csrf
                @method('PATCH')
                <x-validation-errors />
                <div class="mb-3">
                    <label class="form-label" for="order_status">{{ __('Order status') }}</label>
                    <select id="order_status" class="form-select" name="order_status">
                        @foreach($orderStatuses as $status)
                            <option value="{{ $status }}" @selected($order->order_status === $status)>{{ __("statuses.{$status}") }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="payment_status">{{ __('Payment status') }}</label>
                    <select id="payment_status" class="form-select" name="payment_status">
                        @foreach($paymentStatuses as $status)
                            <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ __("statuses.{$status}") }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" data-loading-button data-loading-text="{{ __('Updating...') }}">{{ __('Update order') }}</button>
            </form>
        </div>
    </div>
@endsection
