<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookstoreWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_admin_pages(): void
    {
        $customer = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->actingAs($customer)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
    }

    public function test_admin_can_manage_books(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $author = Author::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.books.store'), [
            'category_id' => $category->id,
            'author_id' => $author->id,
            'title' => 'Testing Laravel Book',
            'isbn' => '9781234567890',
            'description' => 'A practical testing book.',
            'price' => 29.99,
            'stock' => 10,
            'status' => 1,
            'featured' => 1,
        ]);

        $book = Book::firstWhere('isbn', '9781234567890');
        $response->assertRedirect(route('admin.books.show', $book));
        $this->assertTrue($book->featured);

        $this->actingAs($admin)->put(route('admin.books.update', $book), [
            'category_id' => $category->id,
            'author_id' => $author->id,
            'title' => 'Testing Laravel Book Updated',
            'isbn' => '9781234567890',
            'description' => 'Updated.',
            'price' => 39.99,
            'stock' => 7,
            'status' => 1,
            'featured' => 0,
        ])->assertRedirect();

        $this->assertDatabaseHas('books', ['isbn' => '9781234567890', 'stock' => 7]);

        $this->actingAs($admin)->delete(route('admin.books.destroy', $book->fresh()))->assertRedirect(route('admin.books.index'));
        $this->assertSoftDeleted('books', ['id' => $book->id]);

        $this->actingAs($admin)->post(route('admin.books.restore', $book->id))->assertRedirect();
        $this->assertNotSoftDeleted('books', ['id' => $book->id]);
    }

    public function test_book_searching_and_filtering(): void
    {
        [$category, $otherCategory] = Category::factory(2)->create();
        $author = Author::factory()->create(['name' => 'Taylor Search']);
        $otherAuthor = Author::factory()->create();

        $match = Book::factory()->create([
            'category_id' => $category->id,
            'author_id' => $author->id,
            'title' => 'Laravel Search Patterns',
            'status' => true,
            'price' => 25,
        ]);
        Book::factory()->create([
            'category_id' => $otherCategory->id,
            'author_id' => $otherAuthor->id,
            'title' => 'Cooking Notes',
            'status' => true,
            'price' => 80,
        ]);

        $this->get(route('books.index', [
            'search' => 'Laravel',
            'category' => $category->id,
            'min_price' => 10,
            'max_price' => 30,
        ]))->assertOk()
            ->assertSee($match->title)
            ->assertDontSee('Cooking Notes');
    }

    public function test_cart_add_rejects_overstock_and_calculates_total(): void
    {
        $customer = User::factory()->create();
        $book = $this->book(['stock' => 3, 'price' => 12.50]);

        $this->actingAs($customer)->post(route('cart.store'), [
            'book_id' => $book->id,
            'quantity' => 2,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('cart_items', [
            'book_id' => $book->id,
            'quantity' => 2,
        ]);

        $this->actingAs($customer)->post(route('cart.store'), [
            'book_id' => $book->id,
            'quantity' => 2,
        ])->assertSessionHasErrors('quantity');

        $cart = $customer->cart()->with('items')->first();
        $this->assertSame(25.0, $cart->subtotal());
    }

    public function test_successful_checkout_reduces_stock_and_creates_order(): void
    {
        $customer = User::factory()->create();
        $book = $this->book(['stock' => 4, 'price' => 20]);
        $cart = Cart::create(['user_id' => $customer->id]);
        $cart->items()->create(['book_id' => $book->id, 'quantity' => 2, 'unit_price' => 5]);

        $this->actingAs($customer)->post(route('checkout.store'), $this->checkoutPayload())
            ->assertRedirect();

        $this->assertSame(2, $book->fresh()->stock);
        $this->assertDatabaseHas('orders', ['user_id' => $customer->id, 'subtotal' => 40]);
        $this->assertDatabaseHas('order_items', ['book_title' => $book->title, 'unit_price' => 20]);
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_customer_can_place_direct_book_order(): void
    {
        $customer = User::factory()->create([
            'email' => 'nita@example.test',
        ]);
        $book = $this->book(['stock' => 5, 'price' => 24.50]);

        $this->actingAs($customer)->post(route('books.order', $book), [
            'book_id' => $book->id,
            'quantity' => 2,
            'customer_name' => 'Nita Reader',
            'customer_phone' => '+855 12 345 678',
            'shipping_address' => 'Phnom Penh',
        ])->assertRedirect();

        $this->assertSame(3, $book->fresh()->stock);
        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'customer_name' => 'Nita Reader',
            'customer_email' => 'nita@example.test',
            'subtotal' => 49,
            'shipping_fee' => 4.99,
            'total_amount' => 53.99,
            'payment_method' => 'cash_on_delivery',
        ]);
        $this->assertDatabaseHas('order_items', [
            'book_id' => $book->id,
            'quantity' => 2,
            'unit_price' => 24.50,
            'subtotal' => 49,
        ]);
    }

    public function test_failed_checkout_rolls_back_changes(): void
    {
        $customer = User::factory()->create();
        $book = $this->book(['stock' => 1, 'price' => 20]);
        $cart = Cart::create(['user_id' => $customer->id]);
        $cart->items()->create(['book_id' => $book->id, 'quantity' => 2, 'unit_price' => 20]);

        $this->actingAs($customer)->post(route('checkout.store'), $this->checkoutPayload())
            ->assertSessionHasErrors('cart');

        $this->assertSame(1, $book->fresh()->stock);
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('cart_items', 1);
    }

    public function test_customer_cannot_view_another_customers_order(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)->get(route('orders.show', $order))->assertForbidden();
    }

    public function test_admin_can_update_status_and_cancel_returns_stock_only_once(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create();
        $book = $this->book(['stock' => 5, 'price' => 15]);
        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'order_status' => 'pending',
            'payment_status' => 'pending',
        ]);
        $order->items()->create([
            'book_id' => $book->id,
            'book_title' => $book->title,
            'book_isbn' => $book->isbn,
            'quantity' => 2,
            'unit_price' => 15,
            'subtotal' => 30,
        ]);
        $order->payment()->create(['amount' => 30, 'method' => 'cash_on_delivery', 'status' => 'pending']);

        $this->actingAs($admin)->patch(route('admin.orders.update', $order), [
            'order_status' => 'cancelled',
            'payment_status' => 'refunded',
        ])->assertRedirect();

        $this->assertSame(7, $book->fresh()->stock);

        $this->actingAs($admin)->patch(route('admin.orders.update', $order->fresh()), [
            'order_status' => 'cancelled',
        ])->assertRedirect();

        $this->assertSame(7, $book->fresh()->stock);
    }

    public function test_language_switcher_uses_khmer_locale(): void
    {
        $this->get(route('locale.switch', 'km'))
            ->assertRedirect()
            ->assertSessionHas('locale', 'km')
            ->assertSessionHas('status', 'បានប្ដូរភាសាហើយ។');

        $this->withSession(['locale' => 'km'])
            ->get(route('home'))
            ->assertOk()
            ->assertSee('ប្រព័ន្ធគ្រប់គ្រងហាងសៀវភៅ', false)
            ->assertSee('ស្វែងរកសៀវភៅ', false);
    }

    private function book(array $attributes = []): Book
    {
        return Book::factory()->create(array_merge([
            'category_id' => Category::factory()->create()->id,
            'author_id' => Author::factory()->create()->id,
            'status' => true,
        ], $attributes));
    }

    private function checkoutPayload(): array
    {
        return [
            'customer_name' => 'Casey Reader',
            'customer_email' => 'casey@example.test',
            'customer_phone' => '+1 555 1212',
            'shipping_address' => '12 Library Lane',
            'payment_method' => 'cash_on_delivery',
            'notes' => 'Leave at the desk.',
        ];
    }
}
