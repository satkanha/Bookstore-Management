@extends('layouts.admin')

@section('title', __('Invoice').' '.$order->order_number)
@section('page-title', __('Invoice'))

@section('content')
    <div class="admin-card p-4">
        <div class="d-flex justify-content-between mb-4">
            <div>
                <h1 class="h3">{{ __('Invoice') }}</h1>
                <div>{{ $order->order_number }}</div>
            </div>
            <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> {{ __('Print') }}</button>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <strong>{{ __('Bill To') }}</strong>
                <div>{{ $order->customer_name }}</div>
                <div>{{ $order->customer_email }}</div>
                <div>{{ $order->shipping_address }}</div>
            </div>
            <div class="col-md-6 text-md-end">
                <strong>{{ __('Date') }}</strong>
                <div>{{ $order->ordered_at->format('M d, Y') }}</div>
                <div>{{ __('Payment') }}: {{ __("statuses.{$order->payment_status}") }}</div>
            </div>
        </div>
        <table class="table">
            <thead><tr><th>{{ __('Book') }}</th><th>{{ __('Qty') }}</th><th>{{ __('Unit') }}</th><th>{{ __('Subtotal') }}</th></tr></thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr><td>{{ $item->book_title }}</td><td>{{ $item->quantity }}</td><td>${{ number_format((float) $item->unit_price, 2) }}</td><td>${{ number_format((float) $item->subtotal, 2) }}</td></tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-end h5">{{ __('Total') }}: ${{ number_format((float) $order->total_amount, 2) }}</div>
    </div>
@endsection
