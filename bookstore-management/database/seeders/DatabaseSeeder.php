<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Bookstore Admin',
            'email' => 'admin@bookstore.test',
            'password' => Hash::make('password'),
            'phone' => '+1 555 0100',
            'address' => '100 Admin Avenue, Demo City',
        ]);

        $customers = User::factory(10)->create();

        $categories = collect([
            'Fiction',
            'Business',
            'Technology',
            'Children',
            'History',
            'Science',
        ])->map(fn (string $name): Category => Category::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => "Curated {$name} books for everyday readers.",
            'status' => true,
        ]));

        $authors = collect([
            'Ava Bennett',
            'Milo Hart',
            'Nora Vale',
            'Theo Grant',
            'Iris Cole',
            'Julian West',
            'Maya Stone',
            'Elliot Finch',
            'Clara Moon',
            'Simon Brooks',
        ])->map(fn (string $name): Author => Author::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'biography' => fake()->paragraph(3),
            'status' => true,
        ]));

        $books = collect([
            'The Last Chapter House',
            'Building Better Habits',
            'Laravel From The Ground Up',
            'Moonlight at Maple Street',
            'The Quiet History of Maps',
            'Modern Store Operations',
            'Little Cloud Learns to Read',
            'Clean Code for Busy Teams',
            'The Amber Notebook',
            'Ocean Science for Curious Minds',
            'Practical Inventory Control',
            'The Midnight Library Bus',
            'Founders and First Drafts',
            'PHP Patterns in Practice',
            'Stories Before Sunrise',
            'The Illustrated Space Atlas',
            'Customer Service That Scales',
            'Tales From Willow Lane',
            'Everyday Economics',
            'The Bookshop Window',
            'Data Design Essentials',
            'Tiny Detectives Club',
            'Rivers Through Time',
            'Marketing for Local Shops',
            'The Science of Sleep',
            'Bootstrap Interfaces',
            'The Author Next Door',
            'Simple Project Management',
            'Garden Stories for Children',
            'Secure Web Applications',
        ])->map(function (string $title, int $index) use ($authors, $categories): Book {
            $isbn = '978'.str_pad((string) (1000000000 + $index), 10, '0', STR_PAD_LEFT);

            return Book::create([
                'category_id' => $categories[$index % $categories->count()]->id,
                'author_id' => $authors[$index % $authors->count()]->id,
                'title' => $title,
                'slug' => Str::slug($title) ?: Str::slug($isbn),
                'isbn' => $isbn,
                'description' => fake()->paragraphs(3, true),
                'price' => fake()->randomFloat(2, 9, 79),
                'stock' => fake()->numberBetween(12, 75),
                'publication_date' => now()->subMonths($index + 1)->toDateString(),
                'status' => true,
                'featured' => $index < 8,
            ]);
        });

        $customers->take(3)->each(function (User $customer, int $customerIndex) use ($books): void {
            $cart = Cart::create(['user_id' => $customer->id]);

            $books->slice($customerIndex * 3, 3)->each(function (Book $book) use ($cart): void {
                $cart->items()->create([
                    'book_id' => $book->id,
                    'quantity' => fake()->numberBetween(1, 2),
                    'unit_price' => $book->price,
                ]);
            });
        });

        $orderIndex = 1;

        $customers->take(6)->each(function (User $customer) use ($books, &$orderIndex): void {
            for ($i = 0; $i < 2; $i++) {
                $selectedBooks = $books->random(3);
                $orderedAt = now()->subMonths(fake()->numberBetween(0, 6))->subDays(fake()->numberBetween(0, 20));
                $paymentStatus = fake()->randomElement(['pending', 'paid', 'paid', 'paid', 'failed']);
                $orderStatus = $paymentStatus === 'paid'
                    ? fake()->randomElement(['processing', 'shipped', 'completed'])
                    : fake()->randomElement(['pending', 'cancelled']);

                $subtotal = 0;
                $items = [];

                foreach ($selectedBooks as $book) {
                    $quantity = fake()->numberBetween(1, 2);
                    $lineSubtotal = (float) $book->price * $quantity;
                    $subtotal += $lineSubtotal;

                    if ($book->stock >= $quantity && $orderStatus !== 'cancelled') {
                        $book->decrement('stock', $quantity);
                    }

                    $items[] = [
                        'book_id' => $book->id,
                        'book_title' => $book->title,
                        'book_isbn' => $book->isbn,
                        'quantity' => $quantity,
                        'unit_price' => $book->price,
                        'subtotal' => $lineSubtotal,
                    ];
                }

                $shipping = $subtotal >= 50 ? 0 : 4.99;
                $order = Order::create([
                    'user_id' => $customer->id,
                    'order_number' => 'ORD-'.$orderedAt->format('Ymd').'-'.str_pad((string) $orderIndex, 6, '0', STR_PAD_LEFT),
                    'customer_name' => $customer->name,
                    'customer_email' => $customer->email,
                    'customer_phone' => $customer->phone ?? '+1 555 0101',
                    'shipping_address' => $customer->address ?? fake()->address(),
                    'subtotal' => $subtotal,
                    'shipping_fee' => $shipping,
                    'total_amount' => $subtotal + $shipping,
                    'payment_method' => fake()->randomElement(['cash_on_delivery', 'bank_transfer']),
                    'payment_status' => $paymentStatus,
                    'order_status' => $orderStatus,
                    'notes' => fake()->optional()->sentence(),
                    'ordered_at' => $orderedAt,
                    'stock_returned_at' => $orderStatus === 'cancelled' ? $orderedAt->copy()->addDay() : null,
                ]);

                foreach ($items as $item) {
                    $order->items()->create($item);
                }

                $order->payment()->create([
                    'transaction_id' => $paymentStatus === 'paid' ? 'TXN-'.Str::upper(Str::random(10)) : null,
                    'amount' => $order->total_amount,
                    'method' => $order->payment_method,
                    'status' => $paymentStatus,
                    'paid_at' => $paymentStatus === 'paid' ? $orderedAt->copy()->addMinutes(5) : null,
                ]);

                $orderIndex++;
            }
        });

        $admin->cart()->create();
    }
}
