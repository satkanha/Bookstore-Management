<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function create(Request $request, CartService $cartService): View|RedirectResponse
    {
        abort_unless($request->user()?->isCustomer(), 403);

        $cart = $cartService->cartFor($request->user())->load('items.book.author');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('warning', __('Add a book before checkout.'));
        }

        return view('checkout.create', [
            'cart' => $cart,
            'subtotal' => $cart->subtotal(),
            'shippingFee' => $cart->subtotal() >= 50 ? 0 : 4.99,
        ]);
    }

    public function store(CheckoutRequest $request, CheckoutService $checkoutService): RedirectResponse
    {
        $order = $checkoutService->placeOrder($request->user(), $request->validated());

        return redirect()->route('checkout.confirmation', $order)->with('success', __('Order placed successfully.'));
    }

    public function confirmation(Request $request, Order $order): View
    {
        $this->authorize('view', $order);

        return view('checkout.confirmation', [
            'order' => $order->load(['items', 'payment']),
        ]);
    }
}
