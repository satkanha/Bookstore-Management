<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\OrderStatusService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with('user')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($subQuery) use ($search): void {
                    $subQuery->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('order_status'), fn ($query) => $query->where('order_status', $request->get('order_status')))
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->get('payment_status')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('ordered_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('ordered_at', '<=', $request->date('to')))
            ->latest('ordered_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'orderStatuses' => Order::ORDER_STATUSES,
            'paymentStatuses' => Order::PAYMENT_STATUSES,
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load(['user', 'items', 'payment']),
            'orderStatuses' => Order::ORDER_STATUSES,
            'paymentStatuses' => Order::PAYMENT_STATUSES,
        ]);
    }

    public function update(UpdateOrderStatusRequest $request, Order $order, OrderStatusService $statusService): RedirectResponse
    {
        $validated = $request->validated();

        $statusService->update(
            $order,
            $validated['order_status'] ?? null,
            $validated['payment_status'] ?? null,
        );

        return back()->with('success', __('Order updated.'));
    }

    public function invoice(Order $order): View
    {
        return view('admin.orders.invoice', [
            'order' => $order->load(['items', 'payment']),
        ]);
    }
}
