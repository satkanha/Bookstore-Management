<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $paidOrders = Order::query()->where('payment_status', 'paid');
        $monthlyRevenue = (clone $paidOrders)
            ->where('ordered_at', '>=', now()->subMonths(5)->startOfMonth())
            ->get()
            ->groupBy(fn (Order $order): string => $order->ordered_at->format('M Y'))
            ->map(fn ($orders): float => (float) $orders->sum('total_amount'));

        return view('admin.dashboard', [
            'stats' => [
                'books' => Book::count(),
                'customers' => User::where('role', 'customer')->count(),
                'orders' => Order::count(),
                'revenue' => (float) Order::where('payment_status', 'paid')->sum('total_amount'),
                'low_stock' => Book::where('stock', '<=', 5)->count(),
                'pending_orders' => Order::where('order_status', 'pending')->count(),
            ],
            'monthlyLabels' => $monthlyRevenue->keys()->values(),
            'monthlyValues' => $monthlyRevenue->values(),
            'recentOrders' => Order::with('user')->latest('ordered_at')->take(8)->get(),
            'popularBooks' => OrderItem::query()
                ->select('book_title', DB::raw('SUM(quantity) as sold_count'))
                ->groupBy('book_title')
                ->orderByDesc('sold_count')
                ->take(5)
                ->get(),
            'lowStockBooks' => Book::with(['author', 'category'])
                ->where('stock', '<=', 5)
                ->orderBy('stock')
                ->take(6)
                ->get(),
        ]);
    }
}
