<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 3);
        $price = fake()->randomFloat(2, 8, 95);

        return [
            'order_id' => Order::factory(),
            'book_id' => Book::factory(),
            'book_title' => fake()->sentence(3),
            'book_isbn' => fake()->numerify('978##########'),
            'quantity' => $quantity,
            'unit_price' => $price,
            'subtotal' => $quantity * $price,
        ];
    }
}
