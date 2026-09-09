# Bookstore Management System

A Laravel 12 bookstore application with a public storefront, customer accounts, cart and checkout, order history, and an admin dashboard for catalog, customers, orders, payments, inventory, and reports.

## Features

- Laravel Breeze Blade authentication
- Bootstrap 5, Bootstrap Icons, vanilla JavaScript, and Chart.js
- Customer browsing, searching, filtering, cart, checkout, and order history
- Admin dashboard with sales chart, recent orders, popular books, and low-stock alerts
- Admin CRUD for books, categories, and authors with soft delete and restore
- Customer management with activate/deactivate support
- Order management with payment/order statuses and printable invoice
- Transactional checkout with stock checks, price snapshots, payment records, and rollback protection
- PHPUnit feature tests for the main workflows

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- MySQL

This machine was verified with PHP 8.4.14, Composer 2.8.12, Node 26.3.0, npm 11.16.0, and MySQL 9.5.0.

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
php artisan storage:link
php artisan migrate --seed
php artisan serve
```

## Environment

The included `.env.example` is configured for local MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bookstore_management
DB_USERNAME=root
DB_PASSWORD=
```

Create the database:

```bash
mysql -uroot -e "CREATE DATABASE IF NOT EXISTS bookstore_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

## Demo Login

Admin account:

- Email: `admin@bookstore.test`
- Password: `password`

This password is only for local development.

## Main Structure

- `app/Models` - User, catalog, cart, order, and payment models
- `app/Services` - Cart, checkout, and order status business logic
- `app/Http/Controllers` - Storefront, customer, auth, and admin controllers
- `app/Http/Requests` - Form request validation
- `database/migrations` - MySQL-compatible schema
- `database/factories` and `database/seeders` - Demo data and test data
- `resources/views` - Bootstrap Blade UI
- `tests/Feature/BookstoreWorkflowTest.php` - Bookstore workflow coverage

## Useful Commands

```bash
php artisan migrate:status
php artisan route:list --except-vendor
php artisan test
./vendor/bin/pint
npm run build
php artisan serve
```

## Screenshots

Add screenshots of the storefront, cart, checkout, admin dashboard, and reports here after running the app locally.

## Notes

- The current payment methods are Cash on Delivery and Bank Transfer only.
- No real payment gateway is integrated yet.
- Uploaded covers and author photos use Laravel public storage through `php artisan storage:link`.
