@extends('layouts.app')

@section('title', __('Order Confirmed'))

@section('content')
    <div class="container">
        <div class="surface-card p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="stat-icon"><i class="bi bi-check2-circle"></i></span>
                <div>
                    <h1 class="h3 mb-0">{{ __('Order received') }}</h1>
                    <div class="text-muted">{{ $order->order_number }}</div>
                </div>
            </div>
            <p>{{ __('Your order has been created with payment status') }} <x-status-badge :status="$order->payment_status" />.</p>
            <a class="btn btn-primary" href="{{ route('orders.show', $order) }}">{{ __('View order') }}</a>
            <a class="btn btn-outline-secondary" href="{{ route('books.index') }}">{{ __('Continue shopping') }}</a>
        </div>
    </div>
@endsection
