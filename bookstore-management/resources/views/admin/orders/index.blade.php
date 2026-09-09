@extends('layouts.admin')

@section('title', __('Orders'))
@section('page-title', __('Orders'))

@section('content')
    <form class="admin-card p-3 mb-3" method="GET">
        <div class="row g-2">
            <div class="col-md-3"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="{{ __('Order, customer, email') }}"></div>
            <div class="col-md-2"><select class="form-select" name="order_status"><option value="">{{ __('Order status') }}</option>@foreach($orderStatuses as $status)<option value="{{ $status }}" @selected(request('order_status') === $status)>{{ __("statuses.{$status}") }}</option>@endforeach</select></div>
            <div class="col-md-2"><select class="form-select" name="payment_status"><option value="">{{ __('Payment status') }}</option>@foreach($paymentStatuses as $status)<option value="{{ $status }}" @selected(request('payment_status') === $status)>{{ __("statuses.{$status}") }}</option>@endforeach</select></div>
            <div class="col-md-2"><input class="form-control" type="date" name="from" value="{{ request('from') }}"></div>
            <div class="col-md-2"><input class="form-control" type="date" name="to" value="{{ request('to') }}"></div>
            <div class="col-md-1"><button class="btn btn-outline-primary w-100"><i class="bi bi-search"></i></button></div>
        </div>
    </form>
    <div class="admin-card p-3 table-responsive">
        <table class="table">
            <thead><tr><th>{{ __('Order') }}</th><th>{{ __('Customer') }}</th><th>{{ __('Date') }}</th><th>{{ __('Total') }}</th><th>{{ __('Order') }}</th><th>{{ __('Payment') }}</th><th></th></tr></thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}<div class="text-muted small">{{ $order->customer_email }}</div></td>
                        <td>{{ $order->ordered_at->format('M d, Y') }}</td>
                        <td>${{ number_format((float) $order->total_amount, 2) }}</td>
                        <td><x-status-badge :status="$order->order_status" /></td>
                        <td><x-status-badge :status="$order->payment_status" /></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.show', $order) }}">{{ __('Open') }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
@endsection
