<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from')?->startOfDay();
        $to = $request->date('to')?->endOfDay();

        $paidOrders = Order::where('payment_status', 'paid')
            ->when($from, fn ($query) => $query->where('ordered_at', '>=', $from))
            ->when($to, fn ($query) => $query->where('ordered_at', '<=', $to));

        $revenueByMonth = (clone $paidOrders)
            ->get()
            ->groupBy(fn (Order $order): string => $order->ordered_at->format('M Y'))
            ->map(fn ($orders): float => (float) $orders->sum('total_amount'));

        return view('admin.reports.index', [
            'salesToday' => (float) Order::where('payment_status', 'paid')->whereDate('ordered_at', today())->sum('total_amount'),
            'salesThisMonth' => (float) Order::where('payment_status', 'paid')->whereBetween('ordered_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount'),
            'ordersByStatus' => Order::select('order_status', DB::raw('COUNT(*) as total'))->groupBy('order_status')->pluck('total', 'order_status'),
            'revenueLabels' => $revenueByMonth->keys()->values(),
            'revenueValues' => $revenueByMonth->values(),
            'bestSellingBooks' => OrderItem::select('book_title', DB::raw('SUM(quantity) as sold_count'), DB::raw('SUM(subtotal) as revenue'))
                ->groupBy('book_title')
                ->orderByDesc('sold_count')
                ->take(10)
                ->get(),
            'lowStockBooks' => Book::with(['author', 'category'])->where('stock', '<=', 5)->orderBy('stock')->get(),
        ]);
    }
}
