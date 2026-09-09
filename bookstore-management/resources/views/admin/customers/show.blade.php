@extends('layouts.admin')

@section('title', $customer->name)
@section('page-title', __('Customer Details'))

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="admin-card p-4">
                <h1 class="h4">{{ $customer->name }}</h1>
                <p class="text-muted mb-1">{{ $customer->email }}</p>
                <p class="text-muted">{{ $customer->phone }}</p>
                <p>{{ $customer->address }}</p>
                <form method="POST" action="{{ route('admin.customers.status', $customer) }}" data-loading-form>
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="is_active" value="{{ $customer->is_active ? 0 : 1 }}">
                    <button class="btn btn-outline-{{ $customer->is_active ? 'danger' : 'success' }}" data-loading-button data-loading-text="{{ __('Updating...') }}">
                        {{ $customer->is_active ? __('Deactivate account') : __('Activate account') }}
                    </button>
                </form>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="admin-card p-4">
                <h2 class="h5">{{ __('Recent Orders') }}</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>{{ __('Order') }}</th><th>{{ __('Date') }}</th><th>{{ __('Total') }}</th><th>{{ __('Status') }}</th><th></th></tr></thead>
                        <tbody>
                            @foreach($customer->orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->ordered_at->format('M d, Y') }}</td>
                                    <td>${{ number_format((float) $order->total_amount, 2) }}</td>
                                    <td><x-status-badge :status="$order->order_status" /></td>
                                    <td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.show', $order) }}">{{ __('Open') }}</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
