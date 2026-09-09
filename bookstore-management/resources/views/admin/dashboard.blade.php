@extends('layouts.admin')

@section('title', __('Dashboard'))
@section('page-title', __('Dashboard'))

@section('content')
    <div class="row g-3 mb-4">
        @foreach([
            [__('Books'), $stats['books'], 'bi-book-half'],
            [__('Customers'), $stats['customers'], 'bi-people'],
            [__('Orders'), $stats['orders'], 'bi-receipt'],
            [__('Revenue'), '$'.number_format($stats['revenue'], 2), 'bi-cash-stack'],
            [__('Low Stock'), $stats['low_stock'], 'bi-exclamation-triangle'],
            [__('Pending'), $stats['pending_orders'], 'bi-hourglass-split'],
        ] as [$label, $value, $icon])
            <div class="col-sm-6 col-xl-2">
                <div class="admin-card p-3 h-100">
                    <div class="stat-icon mb-3"><i class="bi {{ $icon }}"></i></div>
                    <div class="text-muted small">{{ $label }}</div>
                    <div class="h4 mb-0">{{ $value }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card p-4">
                <h2 class="h5">{{ __('Monthly Sales') }}</h2>
                <canvas id="salesChart" height="120"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="admin-card p-4 h-100">
                <h2 class="h5">{{ __('Popular Books') }}</h2>
                @forelse($popularBooks as $book)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ $book->book_title }}</span>
                        <strong>{{ $book->sold_count }}</strong>
                    </div>
                @empty
                    <p class="text-muted mb-0">{{ __('No sales yet.') }}</p>
                @endforelse
            </div>
        </div>
        <div class="col-lg-8">
            <div class="admin-card p-4">
                <h2 class="h5">{{ __('Recent Orders') }}</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>{{ __('Order') }}</th><th>{{ __('Customer') }}</th><th>{{ __('Total') }}</th><th>{{ __('Status') }}</th><th></th></tr></thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>${{ number_format((float) $order->total_amount, 2) }}</td>
                                    <td><x-status-badge :status="$order->order_status" /></td>
                                    <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">{{ __('Open') }}</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="admin-card p-4">
                <h2 class="h5">{{ __('Low Stock') }}</h2>
                @forelse($lowStockBooks as $book)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ $book->title }}</span>
                        <span class="badge text-bg-danger">{{ $book->stock }}</span>
                    </div>
                @empty
                    <p class="text-muted mb-0">{{ __('No low-stock books.') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('salesChart');
            if (!canvas || !window.Chart) return;
            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: @json($monthlyLabels),
                    datasets: [{
                        label: @json(__('Paid revenue')),
                        data: @json($monthlyValues),
                        borderColor: '#12304a',
                        backgroundColor: 'rgba(216, 146, 22, .18)',
                        fill: true,
                        tension: .35
                    }]
                }
            });
        });
    </script>
@endsection
