@extends('layouts.admin')

@section('title', __('Reports'))
@section('page-title', __('Reports'))

@section('content')
    <form class="admin-card p-3 mb-4" method="GET">
        <div class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label" for="from">{{ __('From') }}</label><input id="from" class="form-control" type="date" name="from" value="{{ request('from') }}"></div>
            <div class="col-md-4"><label class="form-label" for="to">{{ __('To') }}</label><input id="to" class="form-control" type="date" name="to" value="{{ request('to') }}"></div>
            <div class="col-md-4"><button class="btn btn-primary w-100">{{ __('Apply range') }}</button></div>
        </div>
    </form>
    <div class="row g-3 mb-4">
        <div class="col-md-6"><div class="admin-card p-4"><div class="text-muted">{{ __('Sales today') }}</div><div class="h3">${{ number_format($salesToday, 2) }}</div></div></div>
        <div class="col-md-6"><div class="admin-card p-4"><div class="text-muted">{{ __('Sales this month') }}</div><div class="h3">${{ number_format($salesThisMonth, 2) }}</div></div></div>
    </div>
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="admin-card p-4">
                <h2 class="h5">{{ __('Revenue by Month') }}</h2>
                <canvas id="revenueChart" height="130"></canvas>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="admin-card p-4">
                <h2 class="h5">{{ __('Orders by Status') }}</h2>
                @foreach($ordersByStatus as $status => $total)
                    <div class="d-flex justify-content-between border-bottom py-2"><span>{{ __("statuses.{$status}") }}</span><strong>{{ $total }}</strong></div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-7">
            <div class="admin-card p-4">
                <h2 class="h5">{{ __('Best-Selling Books') }}</h2>
                <table class="table"><thead><tr><th>{{ __('Book') }}</th><th>{{ __('Sold') }}</th><th>{{ __('Revenue') }}</th></tr></thead><tbody>@foreach($bestSellingBooks as $book)<tr><td>{{ $book->book_title }}</td><td>{{ $book->sold_count }}</td><td>${{ number_format((float) $book->revenue, 2) }}</td></tr>@endforeach</tbody></table>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="admin-card p-4">
                <h2 class="h5">{{ __('Low-Stock Books') }}</h2>
                @forelse($lowStockBooks as $book)
                    <div class="d-flex justify-content-between border-bottom py-2"><span>{{ $book->title }}</span><span class="badge text-bg-danger">{{ $book->stock }}</span></div>
                @empty
                    <p class="text-muted mb-0">{{ __('No low-stock books.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('revenueChart');
            if (!canvas || !window.Chart) return;
            new Chart(canvas, {
                type: 'bar',
                data: { labels: @json($revenueLabels), datasets: [{ label: @json(__('Revenue')), data: @json($revenueValues), backgroundColor: '#d89216' }] }
            });
        });
    </script>
@endsection
