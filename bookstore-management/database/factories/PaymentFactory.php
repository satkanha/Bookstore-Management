<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(Order::PAYMENT_STATUSES);

        return [
            'order_id' => Order::factory(),
            'transaction_id' => fake()->optional(0.6)->bothify('TXN-########'),
            'amount' => fake()->randomFloat(2, 20, 250),
            'method' => fake()->randomElement(['cash_on_delivery', 'bank_transfer']),
            'status' => $status,
            'paid_at' => $status === 'paid' ? now()->subDays(fake()->numberBetween(0, 30)) : null,
        ];
    }
}
