<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->isCustomer(), 403);

        return view('orders.index', [
            'orders' => $request->user()
                ->orders()
                ->withCount('items')
                ->latest('ordered_at')
                ->paginate(10),
        ]);
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        return view('orders.show', [
            'order' => $order->load(['items', 'payment']),
        ]);
    }
}
