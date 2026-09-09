<?php

namespace App\Http\Controllers;

use App\Http\Requests\DirectOrderRequest;
use App\Models\Book;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;

class DirectOrderController extends Controller
{
    public function store(DirectOrderRequest $request, Book $book, CheckoutService $checkoutService): RedirectResponse
    {
        abort_unless((int) $request->validated('book_id') === $book->id, 404);

        $order = $checkoutService->placeBookOrder(
            $request->user(),
            $book,
            (int) $request->validated('quantity'),
            [
                'customer_name' => $request->validated('customer_name'),
                'customer_email' => $request->user()->email,
                'customer_phone' => $request->validated('customer_phone'),
                'shipping_address' => $request->validated('shipping_address'),
                'payment_method' => 'cash_on_delivery',
                'notes' => __('Direct order from book popup.'),
            ],
        );

        return redirect()
            ->route('checkout.confirmation', $order)
            ->with('success', __('Order placed successfully.'));
    }
}
