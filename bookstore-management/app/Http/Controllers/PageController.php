<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return $this->show(
            'About Us',
            'About Bookstore',
            'We make it easy to discover, manage, and purchase books through a clean bookstore management experience.',
            [
                ['icon' => 'bi-book-half', 'title' => 'Curated catalog', 'body' => 'Explore featured books, authors, categories, and the latest bookstore arrivals.'],
                ['icon' => 'bi-bag-check', 'title' => 'Simple ordering', 'body' => 'Place book orders with customer details, quantity, address, and clear totals.'],
                ['icon' => 'bi-kanban', 'title' => 'Admin friendly', 'body' => 'Manage books, categories, authors, customers, orders, and reports from one dashboard.'],
            ],
        );
    }

    public function contact(): View
    {
        return $this->show(
            'Contact Us',
            'Contact',
            'Need help with books or orders? Our bookstore team is ready to assist.',
            [
                ['icon' => 'bi-telephone-fill', 'title' => '+855 12 345 678', 'body' => 'Call our bookstore support team during business hours.'],
                ['icon' => 'bi-envelope-fill', 'title' => 'bookstore@example.com', 'body' => 'Send questions about orders, accounts, or catalog updates.'],
                ['icon' => 'bi-geo-alt-fill', 'title' => 'Phnom Penh, Cambodia', 'body' => 'Visit us in Phnom Penh, Cambodia.'],
            ],
        );
    }

    public function faq(): View
    {
        return $this->show(
            'FAQ',
            'Support',
            'Find quick answers about ordering, checkout, accounts, and bookstore support.',
            [
                ['icon' => 'bi-question-circle', 'title' => 'How do I place an order?', 'body' => 'Choose a book, open the order popup, enter your details, and confirm.'],
                ['icon' => 'bi-cart-check', 'title' => 'Can I manage my cart?', 'body' => 'Yes. Login to add books, update quantities, and checkout securely.'],
                ['icon' => 'bi-shield-check', 'title' => 'How do admins manage books?', 'body' => 'Admin users can create, edit, restore, and delete catalog records from the admin panel.'],
            ],
        );
    }

    public function privacy(): View
    {
        return $this->show(
            'Privacy Policy',
            'Privacy',
            'We protect your bookstore account data and only use it to support catalog, cart, checkout, and order workflows.',
            [
                ['icon' => 'bi-person-lock', 'title' => 'Account data', 'body' => 'We store account details needed to identify customers and process bookstore orders.'],
                ['icon' => 'bi-receipt', 'title' => 'Order information', 'body' => 'Shipping and contact details are used only for order fulfillment and support.'],
                ['icon' => 'bi-lock-fill', 'title' => 'Security', 'body' => 'Access to admin workflows is restricted to authorized admin users.'],
            ],
        );
    }

    public function terms(): View
    {
        return $this->show(
            'Terms of Use',
            'Terms',
            'Use this bookstore system responsibly for browsing books, managing carts, and demo checkout workflows.',
            [
                ['icon' => 'bi-person-check', 'title' => 'Account responsibility', 'body' => 'Keep your login details secure and update your profile information when it changes.'],
                ['icon' => 'bi-journal-text', 'title' => 'Catalog usage', 'body' => 'Book information is provided for browsing and bookstore management workflows.'],
                ['icon' => 'bi-credit-card', 'title' => 'Demo checkout', 'body' => 'Orders in this project are for bookstore management demonstration and testing.'],
            ],
        );
    }

    private function show(string $title, string $eyebrow, string $intro, array $items): View
    {
        return view('pages.show', compact('title', 'eyebrow', 'intro', 'items'));
    }
}
