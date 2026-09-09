<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 250);
        $shipping = fake()->randomElement([0, 4.99, 6.99]);
        $orderedAt = fake()->dateTimeBetween('-8 months', 'now');

        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-'.$orderedAt->format('Ymd').'-'.fake()->unique()->numerify('######'),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => fake()->phoneNumber(),
            'shipping_address' => fake()->address(),
            'subtotal' => $subtotal,
            'shipping_fee' => $shipping,
            'total_amount' => $subtotal + $shipping,
            'payment_method' => fake()->randomElement(['cash_on_delivery', 'bank_transfer']),
            'payment_status' => fake()->randomElement(Order::PAYMENT_STATUSES),
            'order_status' => fake()->randomElement(Order::ORDER_STATUSES),
            'notes' => fake()->optional()->sentence(),
            'ordered_at' => $orderedAt,
            'stock_returned_at' => null,
        ];
    }
}
