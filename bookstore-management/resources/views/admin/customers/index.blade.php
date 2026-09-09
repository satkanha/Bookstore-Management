@extends('layouts.admin')

@section('title', __('Customers'))
@section('page-title', __('Customers'))

@section('content')
    <form class="admin-card p-3 mb-3" method="GET">
        <div class="row g-2">
            <div class="col-md-10"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="{{ __('Search name, email, or phone') }}"></div>
            <div class="col-md-2"><button class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> {{ __('Search') }}</button></div>
        </div>
    </form>
    <div class="admin-card p-3 table-responsive">
        <table class="table">
            <thead><tr><th>{{ __('Name') }}</th><th>{{ __('Email') }}</th><th>{{ __('Phone') }}</th><th>{{ __('Orders') }}</th><th>{{ __('Status') }}</th><th></th></tr></thead>
            <tbody>
                @foreach($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->orders_count }}</td>
                        <td><x-status-badge :status="$customer->is_active" /></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.customers.show', $customer) }}">{{ __('View') }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $customers->links() }}
    </div>
@endsection
