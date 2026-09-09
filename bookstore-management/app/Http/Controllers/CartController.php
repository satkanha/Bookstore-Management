<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartItemRequest;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request, CartService $cartService): View
    {
        $this->ensureCustomer($request);

        return view('cart.index', [
            'cart' => $cartService->cartFor($request->user())->load('items.book.author'),
        ]);
    }

    public function store(CartItemRequest $request, CartService $cartService): RedirectResponse
    {
        $cartService->add(
            $request->user(),
            (int) $request->validated('book_id'),
            (int) $request->validated('quantity'),
        );

        return back()->with('success', __('Book added to cart.'));
    }

    public function update(Request $request, CartItem $cartItem, CartService $cartService): RedirectResponse
    {
        $this->ensureCustomer($request);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $cartService->update($request->user(), $cartItem, (int) $validated['quantity']);

        return back()->with('success', __('Cart updated.'));
    }

    public function destroy(Request $request, CartItem $cartItem, CartService $cartService): RedirectResponse
    {
        $this->ensureCustomer($request);
        $cartService->remove($request->user(), $cartItem);

        return back()->with('success', __('Item removed from cart.'));
    }

    public function clear(Request $request, CartService $cartService): RedirectResponse
    {
        $this->ensureCustomer($request);
        $cartService->clear($request->user());

        return back()->with('success', __('Cart cleared.'));
    }

    private function ensureCustomer(Request $request): void
    {
        abort_unless($request->user()?->isCustomer(), 403);
    }
}
