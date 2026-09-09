@extends('layouts.app')

@section('title', $order->order_number)

@section('content')
    <div class="container">
        <div class="surface-card p-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="h3">{{ $order->order_number }}</h1>
                    <div class="text-muted">{{ $order->ordered_at->format('M d, Y g:i A') }}</div>
                </div>
                <div class="text-end">
                    <x-status-badge :status="$order->order_status" />
                    <x-status-badge :status="$order->payment_status" />
                </div>
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
                <div class="col-md-4">
                    <div class="d-flex justify-content-between"><span>{{ __('Subtotal') }}</span><strong>${{ number_format((float) $order->subtotal, 2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>{{ __('Shipping') }}</span><strong>${{ number_format((float) $order->shipping_fee, 2) }}</strong></div>
                    <div class="d-flex justify-content-between h5 mt-2"><span>{{ __('Total') }}</span><span>${{ number_format((float) $order->total_amount, 2) }}</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection
