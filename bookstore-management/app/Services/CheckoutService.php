<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function placeOrder(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data): Order {
            $cart = $user->cart()->with('items.book')->first();

            if (! $cart || $cart->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => __('Your cart is empty.'),
                ]);
            }

            $subtotal = 0;
            $lineItems = [];

            foreach ($cart->items as $cartItem) {
                $book = Book::whereKey($cartItem->book_id)->lockForUpdate()->firstOrFail();

                if (! $book->status) {
                    throw ValidationException::withMessages([
                        'cart' => __(':book is no longer available.', ['book' => $book->title]),
                    ]);
                }

                if ($cartItem->quantity > $book->stock) {
                    throw ValidationException::withMessages([
                        'cart' => __(':book only has :count copies available.', [
                            'book' => $book->title,
                            'count' => $book->stock,
                        ]),
                    ]);
                }

                $lineSubtotal = (float) $book->price * $cartItem->quantity;
                $subtotal += $lineSubtotal;

                $lineItems[] = [
                    'book' => $book,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $book->price,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $shippingFee = $this->shippingFeeFor($subtotal);
            $order = $user->orders()->create([
                'order_number' => $this->generateOrderNumber(),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => $data['shipping_address'],
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total_amount' => $subtotal + $shippingFee,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => $data['notes'] ?? null,
                'ordered_at' => now(),
            ]);

            foreach ($lineItems as $lineItem) {
                /** @var Book $book */
                $book = $lineItem['book'];
                $quantity = $lineItem['quantity'];

                $updated = Book::whereKey($book->id)
                    ->where('stock', '>=', $quantity)
                    ->decrement('stock', $quantity);

                if ($updated !== 1) {
                    throw ValidationException::withMessages([
                        'cart' => __(':book went out of stock before checkout completed.', ['book' => $book->title]),
                    ]);
                }

                $order->items()->create([
                    'book_id' => $book->id,
                    'book_title' => $book->title,
                    'book_isbn' => $book->isbn,
                    'quantity' => $quantity,
                    'unit_price' => $lineItem['unit_price'],
                    'subtotal' => $lineItem['subtotal'],
                ]);
            }

            $order->payment()->create([
                'amount' => $order->total_amount,
                'method' => $order->payment_method,
                'status' => 'pending',
            ]);

            $cart->items()->delete();

            return $order->load('items', 'payment');
        });
    }

    public function placeBookOrder(User $user, Book $book, int $quantity, array $data): Order
    {
        return DB::transaction(function () use ($user, $book, $quantity, $data): Order {
            $book = Book::active()->whereKey($book->id)->lockForUpdate()->firstOrFail();

            if ($quantity > $book->stock) {
                throw ValidationException::withMessages([
                    'quantity' => __('Only :count copies are available.', ['count' => $book->stock]),
                ]);
            }

            $subtotal = (float) $book->price * $quantity;
            $shippingFee = $this->shippingFeeFor($subtotal);

            $order = $user->orders()->create([
                'order_number' => $this->generateOrderNumber(),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => $data['shipping_address'],
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total_amount' => $subtotal + $shippingFee,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => $data['notes'] ?? null,
                'ordered_at' => now(),
            ]);

            $updated = Book::whereKey($book->id)
                ->where('stock', '>=', $quantity)
                ->decrement('stock', $quantity);

            if ($updated !== 1) {
                throw ValidationException::withMessages([
                    'quantity' => __(':book went out of stock before checkout completed.', ['book' => $book->title]),
                ]);
            }

            $order->items()->create([
                'book_id' => $book->id,
                'book_title' => $book->title,
                'book_isbn' => $book->isbn,
                'quantity' => $quantity,
                'unit_price' => $book->price,
                'subtotal' => $subtotal,
            ]);

            $order->payment()->create([
                'amount' => $order->total_amount,
                'method' => $order->payment_method,
                'status' => 'pending',
            ]);

            return $order->load('items', 'payment');
        });
    }

    public function cartSubtotal(User $user): float
    {
        $cart = $user->cart()->with('items')->first();

        return $cart ? $cart->subtotal() : 0;
    }

    private function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $next = (Order::whereDate('ordered_at', now()->toDateString())->lockForUpdate()->count()) + 1;

        do {
            $orderNumber = 'ORD-'.$date.'-'.str_pad((string) $next, 6, '0', STR_PAD_LEFT);
            $next++;
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    private function shippingFeeFor(float $subtotal): float
    {
        return $subtotal >= 50 ? 0 : 4.99;
    }
}
