<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderStatusService
{
    private const TRANSITIONS = [
        'pending' => ['pending', 'processing', 'cancelled'],
        'processing' => ['processing', 'shipped', 'completed', 'cancelled'],
        'shipped' => ['shipped', 'completed', 'cancelled'],
        'completed' => ['completed'],
        'cancelled' => ['cancelled'],
    ];

    public function update(Order $order, ?string $orderStatus, ?string $paymentStatus): Order
    {
        return DB::transaction(function () use ($order, $orderStatus, $paymentStatus): Order {
            $order = Order::with('items')->lockForUpdate()->findOrFail($order->id);

            if ($orderStatus !== null) {
                $this->validateTransition($order->order_status, $orderStatus);
                $order->order_status = $orderStatus;
            }

            if ($paymentStatus !== null) {
                $order->payment_status = $paymentStatus;
            }

            if ($order->order_status === 'cancelled' && $order->stock_returned_at === null) {
                foreach ($order->items as $item) {
                    if ($item->book_id !== null) {
                        Book::whereKey($item->book_id)->increment('stock', $item->quantity);
                    }
                }

                $order->stock_returned_at = now();
            }

            $order->save();

            if ($paymentStatus !== null && $order->payment) {
                $order->payment->update([
                    'status' => $paymentStatus,
                    'paid_at' => $paymentStatus === 'paid' ? now() : $order->payment->paid_at,
                ]);
            }

            return $order->fresh(['items', 'payment', 'user']);
        });
    }

    private function validateTransition(string $current, string $next): void
    {
        if (! in_array($next, self::TRANSITIONS[$current] ?? [], true)) {
            throw ValidationException::withMessages([
                'order_status' => __('Cannot change order status from :current to :next.', [
                    'current' => __("statuses.{$current}"),
                    'next' => __("statuses.{$next}"),
                ]),
            ]);
        }
    }
}
