@extends('layouts.app')

@section('title', __('My Orders'))

@section('content')
    <div class="container">
        <h1 class="h3 mb-4">{{ __('My Orders') }}</h1>
        @if($orders->count())
            <div class="surface-card p-3 table-responsive">
                <table class="table">
                    <thead><tr><th>{{ __('Order') }}</th><th>{{ __('Date') }}</th><th>{{ __('Items') }}</th><th>{{ __('Total') }}</th><th>{{ __('Status') }}</th><th></th></tr></thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->ordered_at->format('M d, Y') }}</td>
                                <td>{{ $order->items_count }}</td>
                                <td>${{ number_format((float) $order->total_amount, 2) }}</td>
                                <td><x-status-badge :status="$order->order_status" /></td>
                                <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('orders.show', $order) }}">{{ __('Details') }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}
            </div>
        @else
            <x-empty-state :title="__('No orders yet')" :message="__('Your completed checkouts will appear here.')" />
        @endif
    </div>
@endsection
