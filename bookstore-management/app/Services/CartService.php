<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function cartFor(User $user): Cart
    {
        return $user->cart()->firstOrCreate([]);
    }

    public function add(User $user, int $bookId, int $quantity): CartItem
    {
        return DB::transaction(function () use ($user, $bookId, $quantity): CartItem {
            $book = Book::active()->whereKey($bookId)->lockForUpdate()->firstOrFail();

            if ($book->stock < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => __('Only :count copies are available.', ['count' => $book->stock]),
                ]);
            }

            $cart = $this->cartFor($user);
            $item = $cart->items()->where('book_id', $book->id)->lockForUpdate()->first();
            $newQuantity = ($item?->quantity ?? 0) + $quantity;

            if ($newQuantity > $book->stock) {
                throw ValidationException::withMessages([
                    'quantity' => __('Your cart cannot exceed the available stock of :count.', ['count' => $book->stock]),
                ]);
            }

            return $cart->items()->updateOrCreate(
                ['book_id' => $book->id],
                ['quantity' => $newQuantity, 'unit_price' => $book->price],
            );
        });
    }

    public function update(User $user, CartItem $item, int $quantity): CartItem
    {
        return DB::transaction(function () use ($user, $item, $quantity): CartItem {
            $this->assertCartOwner($user, $item);

            $book = Book::active()->whereKey($item->book_id)->lockForUpdate()->firstOrFail();

            if ($quantity > $book->stock) {
                throw ValidationException::withMessages([
                    'quantity' => __('Only :count copies are available.', ['count' => $book->stock]),
                ]);
            }

            $item->update([
                'quantity' => $quantity,
                'unit_price' => $book->price,
            ]);

            return $item;
        });
    }

    public function remove(User $user, CartItem $item): void
    {
        $this->assertCartOwner($user, $item);
        $item->delete();
    }

    public function clear(User $user): void
    {
        $this->cartFor($user)->items()->delete();
    }

    private function assertCartOwner(User $user, CartItem $item): void
    {
        if ($item->cart->user_id !== $user->id) {
            abort(403);
        }
    }
}
