-- Bookstore Management System
-- MySQL/Navicat import file generated from the existing Laravel migrations, models, and DatabaseSeeder.
-- Safe import note: this file does not DROP, TRUNCATE, or DELETE existing data. Import into an empty local database.

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS `bookstore_management`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `bookstore_management`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `authors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biography` text COLLATE utf8mb4_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `authors_slug_unique` (`slug`),
  KEY `authors_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `books` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isbn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int unsigned NOT NULL DEFAULT '0',
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publication_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `books_slug_unique` (`slug`),
  UNIQUE KEY `books_isbn_unique` (`isbn`),
  KEY `books_status_index` (`status`),
  KEY `books_featured_index` (`featured`),
  KEY `books_category_id_author_id_status_index` (`category_id`, `author_id`, `status`),
  KEY `books_price_stock_index` (`price`, `stock`),
  KEY `books_author_id_foreign` (`author_id`),
  CONSTRAINT `books_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `books_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carts_user_id_unique` (`user_id`),
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `book_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_items_cart_id_book_id_unique` (`cart_id`, `book_id`),
  KEY `cart_items_book_id_foreign` (`book_id`),
  CONSTRAINT `cart_items_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `order_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `ordered_at` timestamp NOT NULL,
  `stock_returned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_payment_status_index` (`payment_status`),
  KEY `orders_order_status_index` (`order_status`),
  KEY `orders_ordered_at_index` (`ordered_at`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `book_id` bigint unsigned DEFAULT NULL,
  `book_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_isbn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int unsigned NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_book_id_foreign` (`book_id`),
  CONSTRAINT `order_items_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_order_id_unique` (`order_id`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  KEY `payments_status_index` (`status`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_18_050000_create_categories_table', 1),
(5, '2026_08_18_050010_create_authors_table', 1),
(6, '2026_08_18_050020_create_books_table', 1),
(7, '2026_08_18_050030_create_carts_table', 1),
(8, '2026_08_18_050040_create_cart_items_table', 1),
(9, '2026_08_18_050050_create_orders_table', 1),
(10, '2026_08_18_050060_create_order_items_table', 1),
(11, '2026_08_18_050070_create_payments_table', 1),
(12, '2026_08_20_000000_backfill_blank_book_slugs', 1);

-- Data for `users`
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `address`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Bookstore Admin', 'admin@bookstore.test', '2026-09-16 06:41:51', '$2y$12$JPCaaIyZjob5Q6bLPYvE3e0q1ncViSyycQa3v532vQBSk94ox.lmG', 'admin', '+1 555 0100', '100 Admin Avenue, Demo City', 1, 'gIo3AdHUZS', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(2, 'Prof. Santina Pouros Jr.', 'josiah.weissnat@example.org', '2026-09-16 06:41:51', '$2y$12$DPHLqNE3taMKyvZEWMkeX.bdykEGuDXGDAXiUDXWRzTqoMg6fsiey', 'customer', '+1-848-629-3803', '570 Pacocha Flats
Schulistmouth, UT 64452-3936', 1, 'iAQKNR1kfp', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(3, 'Erin Beatty V', 'gmertz@example.com', '2026-09-16 06:41:51', '$2y$12$DPHLqNE3taMKyvZEWMkeX.bdykEGuDXGDAXiUDXWRzTqoMg6fsiey', 'customer', '325.518.2225', '711 Will Estate Suite 971
Aaronmouth, SD 78499-3182', 1, 'UcseEbSvL1', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(4, 'Josue Kunze', 'layne.hills@example.net', '2026-09-16 06:41:51', '$2y$12$DPHLqNE3taMKyvZEWMkeX.bdykEGuDXGDAXiUDXWRzTqoMg6fsiey', 'customer', '+1 (484) 772-9163', '1717 Gideon Ridge
West Claudfurt, NH 95871', 1, 'UoJHfSyHRV', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(5, 'Margarita Mohr', 'bethany11@example.org', '2026-09-16 06:41:51', '$2y$12$DPHLqNE3taMKyvZEWMkeX.bdykEGuDXGDAXiUDXWRzTqoMg6fsiey', 'customer', '1-704-433-1800', '6396 Abbott Grove
Wisokyview, DC 43122-5361', 1, 'ZDjCzGvCfD', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(6, 'Vivienne Rempel', 'gail.koch@example.com', '2026-09-16 06:41:51', '$2y$12$DPHLqNE3taMKyvZEWMkeX.bdykEGuDXGDAXiUDXWRzTqoMg6fsiey', 'customer', '+1-928-253-1886', '48094 Franecki Turnpike
North Jo, CA 09671', 1, 'OnnJv2Dhml', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(7, 'Prof. Paige Ward MD', 'rodriguez.florian@example.net', '2026-09-16 06:41:51', '$2y$12$DPHLqNE3taMKyvZEWMkeX.bdykEGuDXGDAXiUDXWRzTqoMg6fsiey', 'customer', '+1-734-352-5030', '6037 Tillman Village
New Felicia, UT 25529', 1, 'PUDJsbLtLM', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(8, 'Mac Bradtke', 'martine12@example.org', '2026-09-16 06:41:51', '$2y$12$DPHLqNE3taMKyvZEWMkeX.bdykEGuDXGDAXiUDXWRzTqoMg6fsiey', 'customer', '740.467.6745', '19208 Dare Run Suite 854
Boehmhaven, MN 19619-4911', 1, 'oPRWYXjLEW', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(9, 'Mrs. Alysson Lowe DDS', 'effertz.agnes@example.org', '2026-09-16 06:41:51', '$2y$12$DPHLqNE3taMKyvZEWMkeX.bdykEGuDXGDAXiUDXWRzTqoMg6fsiey', 'customer', '+1-240-217-0853', '505 Botsford Mount
Moenland, NY 98866-8881', 1, '33UTP16IAw', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(10, 'Felipe D''Amore', 'amy.labadie@example.net', '2026-09-16 06:41:51', '$2y$12$DPHLqNE3taMKyvZEWMkeX.bdykEGuDXGDAXiUDXWRzTqoMg6fsiey', 'customer', '605.896.6159', '96902 Ashleigh Roads
New Trace, NC 60192-2543', 1, 'ZyDJgJCiGJ', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(11, 'Ludwig Nikolaus I', 'ddach@example.org', '2026-09-16 06:41:51', '$2y$12$DPHLqNE3taMKyvZEWMkeX.bdykEGuDXGDAXiUDXWRzTqoMg6fsiey', 'customer', '303.999.3535', '276 Eichmann Springs
Monahanchester, MN 66861-3437', 1, 'w35tQMYA5G', '2026-09-16 06:41:51', '2026-09-16 06:41:51');

-- Data for `categories`
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Fiction', 'fiction', 'Curated Fiction books for everyday readers.', 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(2, 'Business', 'business', 'Curated Business books for everyday readers.', 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(3, 'Technology', 'technology', 'Curated Technology books for everyday readers.', 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(4, 'Children', 'children', 'Curated Children books for everyday readers.', 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(5, 'History', 'history', 'Curated History books for everyday readers.', 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(6, 'Science', 'science', 'Curated Science books for everyday readers.', 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL);

-- Data for `authors`
INSERT INTO `authors` (`id`, `name`, `slug`, `biography`, `photo`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Ava Bennett', 'ava-bennett', 'Rerum omnis atque repellat sed incidunt est. Optio quam consequatur et magni.', NULL, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(2, 'Milo Hart', 'milo-hart', 'Nobis delectus veritatis illum dolorem quaerat non laboriosam. Perferendis modi quasi voluptatem qui voluptatem architecto eius. Voluptas voluptas praesentium ea repudiandae.', NULL, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(3, 'Nora Vale', 'nora-vale', 'Quo voluptatem iste qui aperiam. Culpa reprehenderit eius optio ad fugit sit repellat. Voluptatem sit enim praesentium est ipsa perferendis vero eius.', NULL, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(4, 'Theo Grant', 'theo-grant', 'Dolorem ut quisquam dicta qui quisquam dicta. Eum qui accusamus excepturi tempora ut non. Sit officiis accusantium temporibus harum architecto id sit aut.', NULL, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(5, 'Iris Cole', 'iris-cole', 'Veniam necessitatibus provident quam aperiam error hic doloribus. Voluptatum reiciendis veniam animi omnis ducimus molestias. Facere facilis nobis aut est accusamus. Culpa et culpa ut est nam accusamus iusto.', NULL, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(6, 'Julian West', 'julian-west', 'Modi facilis dolorum voluptas omnis qui natus. Dolores pariatur et veniam id sint consequatur consequatur sed. Voluptatem dolor qui omnis ea deleniti assumenda. Quia iure aut dolore harum consequatur possimus.', NULL, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(7, 'Maya Stone', 'maya-stone', 'Facere sunt sit illum qui illum. Sit quod in et iusto suscipit sequi. Nihil qui reiciendis quod iste suscipit odio dolorem.', NULL, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(8, 'Elliot Finch', 'elliot-finch', 'Voluptas eum provident est eos dolores. Magni et ipsum rerum maxime voluptatem. Labore in dolores quasi quisquam dolore. Blanditiis iusto iure ipsum aut eligendi nihil mollitia quisquam.', NULL, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(9, 'Clara Moon', 'clara-moon', 'Voluptas maxime nesciunt ea omnis ducimus quibusdam cupiditate. Optio laudantium quae impedit est. Dolorum qui nemo distinctio dignissimos minus et.', NULL, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(10, 'Simon Brooks', 'simon-brooks', 'Explicabo aperiam doloribus deserunt praesentium et id saepe enim. Similique qui placeat et sapiente voluptas distinctio alias eos. Eligendi expedita vitae sed quis vel. Et unde libero eaque harum.', NULL, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL);

-- Data for `books`
INSERT INTO `books` (`id`, `category_id`, `author_id`, `title`, `slug`, `isbn`, `description`, `price`, `stock`, `cover_image`, `publication_date`, `status`, `featured`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'The Last Chapter House', 'the-last-chapter-house', '9781000000000', 'Magni pariatur repellendus possimus mollitia. Et doloribus dicta ipsam amet.

Nesciunt doloremque eos itaque id repudiandae asperiores. Porro dicta autem harum dicta totam earum. Vitae atque reiciendis unde sequi laboriosam. Corporis aut ipsam est tempore.

Ea voluptas officiis perspiciatis et sequi. Sint numquam sit quos facere placeat autem id. Velit excepturi qui quas suscipit ad expedita occaecati. Rerum eum esse mollitia et sit.', 75.94, 33, NULL, '2026-08-16 00:00:00', 1, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(2, 2, 2, 'Building Better Habits', 'building-better-habits', '9781000000001', 'Quo dolore consequatur consectetur omnis molestiae nam sed. Atque placeat in officia fugit ea qui.

Qui dolorem vel quo dolorum dolor. Autem saepe voluptas omnis dolor dolore unde. Et repellat vero quia id facere. Nesciunt et voluptatibus quis labore odit qui vel consequatur.

Deleniti eaque veniam neque omnis quibusdam. Molestiae est sed quos deleniti molestiae est. Nesciunt impedit quis et debitis a et. Minima sit debitis assumenda consectetur enim voluptates possimus repellendus. Rerum aut ullam porro voluptatem.', 9.77, 24, NULL, '2026-07-16 00:00:00', 1, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(3, 3, 3, 'Laravel From The Ground Up', 'laravel-from-the-ground-up', '9781000000002', 'Nostrum aut deleniti voluptatum consequatur tempora rem autem. Adipisci minima rerum temporibus alias. Eos assumenda magnam tempore voluptas cumque et.

Laudantium explicabo officiis eligendi quo eveniet officia qui. Qui deleniti est consequatur placeat rerum autem voluptatum. Distinctio consectetur esse doloribus cupiditate quia voluptates voluptatum.

Exercitationem nostrum sit ea maxime. Et quam fugiat et nobis. Quia sit inventore ut enim ex placeat.', 31.54, 72, NULL, '2026-06-16 00:00:00', 1, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(4, 4, 4, 'Moonlight at Maple Street', 'moonlight-at-maple-street', '9781000000003', 'Facilis perspiciatis nobis maiores nesciunt est. Sed ut id est et quo. Modi molestias quod soluta molestiae adipisci eum. Quidem ut in sint nesciunt assumenda consequatur eum voluptas. Odio enim molestias dolor eius est recusandae sed.

Nemo ut modi dolorem rerum. Quasi soluta vel voluptatem iusto optio repudiandae facere. Eveniet vitae minus perferendis quis dicta qui.

Explicabo sequi aut quisquam ut inventore. Et nostrum magnam eius qui consequatur similique rerum. Doloremque est aut voluptatem consectetur iste.', 62.73, 15, NULL, '2026-05-16 00:00:00', 1, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(5, 5, 5, 'The Quiet History of Maps', 'the-quiet-history-of-maps', '9781000000004', 'Delectus impedit enim ab excepturi est. Deleniti architecto laboriosam qui quis qui eos dicta. Sit sed qui tempore sapiente nulla omnis. Necessitatibus ab adipisci asperiores odio nihil ut.

Nostrum atque saepe voluptas ipsam. Vel pariatur vel rerum. Molestiae itaque voluptate sit reprehenderit.

Dolorum qui quibusdam rem quam illo quis. Fugit fugit est officiis fugit voluptatem. Ab molestiae nihil autem voluptas quis. Non error quo voluptas earum ut accusamus quasi eum.', 62.39, 19, NULL, '2026-04-16 00:00:00', 1, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(6, 6, 6, 'Modern Store Operations', 'modern-store-operations', '9781000000005', 'Ut voluptatum vitae officiis voluptas voluptatem aut. Labore qui est qui eaque. Incidunt fugit sed voluptates deserunt. Laboriosam culpa quae illo est minus odit est.

Vero quis voluptatem ratione ad. Nihil pariatur qui atque sint consequatur. Quae laborum nihil in. Odio et amet occaecati accusantium.

Sunt est aut minima voluptatum. Nisi qui ipsam voluptas quaerat sint dicta qui. Similique consequuntur rerum similique quia facilis et.', 19.15, 71, NULL, '2026-03-16 00:00:00', 1, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(7, 1, 7, 'Little Cloud Learns to Read', 'little-cloud-learns-to-read', '9781000000006', 'Ad aperiam sit eius consequuntur. Sit provident at officia. Ab natus architecto illum totam eos atque harum ducimus.

Qui tempora saepe laudantium excepturi tempore delectus. Quaerat quibusdam vitae velit dolores. Repudiandae cum porro doloremque voluptates omnis illo ad doloremque.

Exercitationem et distinctio sit vero. Aut ut consequatur blanditiis animi vero. Ea occaecati ut dolor quisquam illo rerum officia. Consequatur numquam doloribus laudantium nihil.', 65.54, 58, NULL, '2026-02-16 00:00:00', 1, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(8, 2, 8, 'Clean Code for Busy Teams', 'clean-code-for-busy-teams', '9781000000007', 'Esse ut ipsa maiores nihil. Sit officia accusamus harum molestias occaecati nihil omnis. Aliquid nihil harum consequatur minus ut veritatis. Sunt fugiat consequatur totam tempora est vel.

Ipsum quia illo exercitationem rem quo. Officiis officiis voluptatum voluptate ut labore saepe aut nesciunt. Iste iste voluptatem illum nihil. Excepturi dignissimos officia ipsum distinctio non.

Est temporibus sed labore cupiditate et et maiores. Sequi tempora hic corrupti eos. Laborum dolorem odit qui neque sit. Hic rerum ipsa similique rerum.', 11.76, 54, NULL, '2026-01-16 00:00:00', 1, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(9, 3, 9, 'The Amber Notebook', 'the-amber-notebook', '9781000000008', 'Voluptatem cum vel quo explicabo quis sit. Dolorem ab consectetur officiis qui voluptas minus ipsum.

Aut ipsam est architecto aut voluptate sed id. Id voluptatum officiis magnam error maxime. Sequi qui quia est saepe totam hic. Id placeat est veniam odio natus consequatur.

Ratione voluptatem aliquam ratione dolor quas veritatis. Assumenda et ipsa repellendus nisi distinctio nesciunt nobis. Est et atque similique nesciunt.', 66.87, 29, NULL, '2025-12-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(10, 4, 10, 'Ocean Science for Curious Minds', 'ocean-science-for-curious-minds', '9781000000009', 'Eveniet tempora odit minima quis. Aut hic similique aspernatur quo et reiciendis iusto. Aut perspiciatis facilis ratione harum.

Et saepe est voluptas minus odio voluptatem qui autem. Molestias odio rem exercitationem impedit vel. Laboriosam reprehenderit temporibus nesciunt aspernatur quis. Iste dolorem exercitationem et tenetur ut facilis. Et similique maxime ipsa.

Quia voluptate nihil in velit magni quia. Explicabo quidem culpa consequatur in alias et. Aut dolorum sit eum facilis labore. Reprehenderit reprehenderit molestias ut.', 69.13, 49, NULL, '2025-11-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(11, 5, 1, 'Practical Inventory Control', 'practical-inventory-control', '9781000000010', 'Id qui ex animi dolor tenetur aliquam. Doloremque sed illo provident nobis repudiandae laboriosam. Mollitia vel sed voluptatem aliquid expedita.

Aut rerum tempora ad ea laborum. Veniam autem quasi quia eligendi qui aperiam. Ducimus esse iusto ipsam laudantium.

Laboriosam ipsam illo consequatur magni eum. Sed distinctio et cupiditate non reprehenderit eaque iusto.', 75.81, 29, NULL, '2025-10-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(12, 6, 2, 'The Midnight Library Bus', 'the-midnight-library-bus', '9781000000011', 'Aut et exercitationem consectetur culpa praesentium. Aliquam explicabo dolor qui.

Commodi odio delectus in maiores non. Aut repudiandae magni laudantium enim. Quo consequatur ad porro reprehenderit magnam aut at. Delectus ad voluptates fuga nihil nam dolore. Repellat est est ullam ipsam.

Dignissimos blanditiis accusantium amet ab. Unde soluta dolores sint et. Sit et molestias officiis minima dolor vero eaque. Ex facilis velit quam et.', 54.38, 49, NULL, '2025-09-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(13, 1, 3, 'Founders and First Drafts', 'founders-and-first-drafts', '9781000000012', 'Non vitae porro reprehenderit autem mollitia aut vel magnam. Cumque itaque saepe perspiciatis aliquam. Adipisci nihil soluta voluptatibus exercitationem quis.

Et dolores quo sed laboriosam quos. Officia totam culpa culpa dolores accusantium eligendi tempora. Dolores molestiae natus earum earum aliquid aut.

Deserunt deleniti odio laborum possimus. Iure earum vel aut ab quae pariatur eum. Blanditiis porro consequatur architecto voluptas consequatur nemo. Inventore ipsa quis velit hic expedita nihil illum.', 78.56, 25, NULL, '2025-08-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(14, 2, 4, 'PHP Patterns in Practice', 'php-patterns-in-practice', '9781000000013', 'Error hic veritatis quidem qui. Asperiores et libero sed laudantium consequatur praesentium. Non accusamus in perferendis nam suscipit est alias. Dolores eius provident ratione autem.

Quidem quisquam laborum qui sunt minus nihil. Deleniti libero magni ducimus vero. Molestiae sunt fugit incidunt et ut. Repudiandae mollitia quia ut omnis.

Ut necessitatibus fugit ea quia beatae facilis. Sit officia a omnis ex numquam non quod. Commodi dolores maiores voluptatem error ea. Itaque quo maiores libero quaerat optio eligendi ut corrupti.', 73.9, 31, NULL, '2025-07-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(15, 3, 5, 'Stories Before Sunrise', 'stories-before-sunrise', '9781000000014', 'Neque sed quas sit soluta neque quia et. Sint eius consequatur iste accusantium est voluptatem animi. Adipisci dolorem voluptatibus laborum dolorem porro itaque.

Eveniet ea cum libero consectetur. Eos est voluptas ipsam sapiente autem. Porro soluta fugit perferendis sequi sit.

Eligendi delectus atque est omnis sapiente aut. Non a nulla rerum et ab.', 21.68, 72, NULL, '2025-06-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(16, 4, 6, 'The Illustrated Space Atlas', 'the-illustrated-space-atlas', '9781000000015', 'Perspiciatis deleniti voluptatum reprehenderit consequatur. Autem distinctio magnam aut. Eum enim ea temporibus ab est aspernatur. Necessitatibus quia sed et.

Repellat eum voluptatem non totam ratione voluptatem. Ea accusantium non perspiciatis. Accusantium iusto nam dolores similique tenetur eveniet pariatur.

Libero laudantium voluptas sequi tempora. Consequatur et dicta laborum soluta repudiandae eos. Reprehenderit consequatur perferendis rerum magni dolor ad eum.', 56.37, 13, NULL, '2025-05-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(17, 5, 7, 'Customer Service That Scales', 'customer-service-that-scales', '9781000000016', 'Et et quam dignissimos vel quas sunt nostrum. Sapiente dolores a possimus eos voluptatibus ut. Assumenda natus accusantium culpa nam vel dolorem.

Possimus similique voluptas mollitia. Alias rerum quibusdam enim qui repellat eligendi ad sequi.

Consequatur asperiores quasi aperiam veritatis mollitia ut exercitationem. Unde itaque consequatur ullam aut veritatis omnis non et. Accusamus ea voluptatem veritatis repellat suscipit. Ut minus dolor vitae fugit facilis repellendus.', 24.08, 32, NULL, '2025-04-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(18, 6, 8, 'Tales From Willow Lane', 'tales-from-willow-lane', '9781000000017', 'Ex voluptatibus et quibusdam est et maxime. Quas impedit dolorem iure ab. Voluptates rerum quis adipisci unde dolor atque aut accusantium.

Non assumenda dicta numquam vero molestiae. Eveniet dolores suscipit nulla illo. Libero earum debitis porro eius aut eum. Itaque dolores occaecati ex sapiente nesciunt enim hic et.

Mollitia quia sit dolore non nostrum molestiae atque sed. Quis laboriosam facere quibusdam qui unde expedita rerum. Vel fugiat ut est blanditiis odit. Autem non dolores accusantium. Quasi qui et qui dicta impedit ullam.', 43.6, 38, NULL, '2025-03-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(19, 1, 9, 'Everyday Economics', 'everyday-economics', '9781000000018', 'Sit veniam sint molestias soluta. Id dignissimos occaecati et aliquam velit. Illum eos voluptates dolor voluptatem eum.

Omnis et hic vero facilis. Ducimus eius facere reprehenderit quo culpa. Et ratione amet quidem.

Quas necessitatibus exercitationem eaque quo. Autem rerum consequuntur veritatis et impedit. Est et corrupti unde id illum.', 9.34, 11, NULL, '2025-02-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(20, 2, 10, 'The Bookshop Window', 'the-bookshop-window', '9781000000019', 'Aut ad molestiae error quidem error. Minus aut eligendi sequi officia quaerat nisi odit non. Nobis suscipit sunt debitis facilis est et. Deserunt sit non aut dicta nisi.

Fuga reiciendis fugit rerum dolorem et dolore. Vitae sit eum assumenda ipsum magni laboriosam. Ab sed unde dolores voluptatem doloribus.

Incidunt et enim veniam. Dolor quas et blanditiis saepe corrupti. Minus voluptas temporibus voluptas et est beatae. Pariatur eum eos molestiae et voluptas numquam.', 17.66, 14, NULL, '2025-01-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(21, 3, 1, 'Data Design Essentials', 'data-design-essentials', '9781000000020', 'Modi beatae ex illum eaque dolores. Delectus voluptatum quia quos aperiam velit mollitia. Consequatur consequuntur est aliquid aut ut harum quod.

Quia voluptate earum odio tempore. Omnis quos laboriosam aut. Voluptatem non labore non magnam vel.

Quia molestiae dolorem animi consequatur amet quo. Sit rerum error iure eos. Tempore harum non quia doloremque quis accusamus. Et ut accusantium beatae neque. Quia unde debitis ut ut.', 50.37, 56, NULL, '2024-12-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(22, 4, 2, 'Tiny Detectives Club', 'tiny-detectives-club', '9781000000021', 'Iure reprehenderit est totam mollitia. Quae illum sed odit doloribus. Iusto nihil a est.

Deserunt aliquam voluptas dolorum voluptatum doloribus suscipit ipsa rerum. Minus quo architecto suscipit molestiae qui. Dolor velit consequuntur quis debitis saepe iusto explicabo. Voluptas voluptatem ducimus iste et rerum labore corrupti.

Voluptatem officia quia occaecati non at eum. Magnam officia accusantium eos et eligendi quis est. Distinctio doloribus rem distinctio consequatur tenetur reiciendis nulla natus.', 24.54, 53, NULL, '2024-11-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(23, 5, 3, 'Rivers Through Time', 'rivers-through-time', '9781000000022', 'Consequuntur hic qui repellat ipsum soluta quia molestias libero. Veritatis et nihil illo qui nihil.

Tenetur debitis unde excepturi rerum ex quasi veritatis. Sed quasi ea itaque non ipsum vitae blanditiis. Error culpa dolor enim ut quo nihil et. Eligendi ipsum harum rerum praesentium dolore alias sed.

Est placeat voluptatem et aut. Et est voluptas in fugiat dicta labore. Asperiores dolorem quaerat nesciunt fugiat ut.', 26.15, 53, NULL, '2024-10-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(24, 6, 4, 'Marketing for Local Shops', 'marketing-for-local-shops', '9781000000023', 'Tenetur dolore earum excepturi. Corporis animi aut corrupti facere nobis numquam voluptatem ducimus. Quasi et autem est aut. Aut fugiat doloribus iste non in.

Accusantium dolores error velit fuga molestiae qui. Non minima corporis possimus optio dolores sint rerum. Veritatis aperiam voluptatibus nesciunt voluptatem non esse.

Voluptate aut voluptatibus vel commodi mollitia. Quia ut quidem non sint consequatur. Et ullam provident at quasi assumenda quibusdam qui. Pariatur ut ratione atque aut rerum nihil voluptatem.', 47.89, 52, NULL, '2024-09-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(25, 1, 5, 'The Science of Sleep', 'the-science-of-sleep', '9781000000024', 'Aspernatur eos modi autem enim impedit deserunt. Dolorem perspiciatis sed magnam consequatur alias. Et porro beatae consequatur molestiae occaecati sunt et suscipit.

Sit assumenda sed animi dicta. Amet similique vel ut non. Reiciendis enim debitis dolorum expedita recusandae non esse. Reprehenderit eligendi doloremque aperiam maiores ab omnis qui.

Rerum eum inventore suscipit et. Qui enim vero praesentium. Repellat quaerat temporibus sunt laborum possimus dignissimos tenetur. Ratione earum nobis aut quos sint.', 37.84, 12, NULL, '2024-08-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(26, 2, 6, 'Bootstrap Interfaces', 'bootstrap-interfaces', '9781000000025', 'Sint reprehenderit quo aut enim incidunt velit odio. Aperiam voluptatem voluptas sit. Recusandae magnam atque in at. Dolores aut harum laborum.

Id non consequatur ea et autem saepe repellendus. Nisi perspiciatis culpa est reiciendis laboriosam. Rem qui sit laboriosam et occaecati. Minima sed harum non aut voluptatum et. Deserunt ex molestiae nobis.

Quas rerum sed ut consequuntur veritatis consequuntur. Iste numquam aspernatur reiciendis accusantium dolores voluptatem. Voluptatem voluptas tempora nobis sit similique.', 75.1, 63, NULL, '2024-07-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(27, 3, 7, 'The Author Next Door', 'the-author-next-door', '9781000000026', 'Ipsa dolorem aut libero consectetur aut. Aliquam nam autem tempora. Quaerat officiis sed ut unde sunt est. Sequi iusto impedit est mollitia est quasi.

Nulla aut reprehenderit non error non alias. Explicabo impedit est ut consequuntur dolores suscipit asperiores. Accusamus itaque dolores ipsa explicabo asperiores libero.

Minus maxime cum quod accusamus. Fugit consequatur voluptates veniam id. Optio dolores eius est quibusdam at cumque omnis. Nemo quidem corporis ipsum.', 46.09, 15, NULL, '2024-06-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(28, 4, 8, 'Simple Project Management', 'simple-project-management', '9781000000027', 'Itaque corporis eum odit eum qui aut in. Quis sint ut deserunt ipsum. Voluptatem nemo consequuntur reiciendis dolores odit nobis doloremque. Deleniti nobis praesentium nobis praesentium mollitia.

Unde adipisci corporis est voluptatem voluptatem sit ipsa. Omnis illum voluptate ea est magni aperiam ducimus earum.

Accusantium doloremque consequatur vel dolorem. Reprehenderit itaque odit quisquam tempora. Et inventore doloribus molestias ullam qui perferendis quos omnis.', 17.74, 37, NULL, '2024-05-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(29, 5, 9, 'Garden Stories for Children', 'garden-stories-for-children', '9781000000028', 'Nostrum laborum deleniti natus molestiae quidem dicta non. Dolorem placeat unde ipsam commodi quisquam nihil.

Exercitationem at explicabo tempora est minima ipsa. Quis ut sint aut in. Consequuntur numquam quod qui culpa. Corporis dignissimos sapiente optio ut ut voluptas totam.

Ratione et voluptatem doloremque non quis sit inventore. Ea fugiat qui ipsam quia odit fuga sed. Accusamus exercitationem nam et.', 32, 71, NULL, '2024-04-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL),
(30, 6, 10, 'Secure Web Applications', 'secure-web-applications', '9781000000029', 'Ut veniam quia molestias maxime. Aspernatur perferendis et ad est qui.

Alias reprehenderit in rerum beatae. Velit voluptatum id est velit et minus. Temporibus est voluptatibus recusandae animi ea natus. Qui qui esse et voluptates ut.

Neque illum ad facere modi quaerat modi. Eum qui non aut laudantium fugit fuga. Commodi dolor itaque cum nesciunt harum est ullam.', 61.46, 12, NULL, '2024-03-16 00:00:00', 1, 0, '2026-09-16 06:41:51', '2026-09-16 06:41:51', NULL);

-- Data for `carts`
INSERT INTO `carts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(2, 3, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(3, 4, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(4, 1, '2026-09-16 06:41:51', '2026-09-16 06:41:51');

-- Data for `cart_items`
INSERT INTO `cart_items` (`id`, `cart_id`, `book_id`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 75.94, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(2, 1, 2, 2, 9.77, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(3, 1, 3, 2, 31.54, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(4, 2, 4, 2, 62.73, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(5, 2, 5, 1, 62.39, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(6, 2, 6, 2, 19.15, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(7, 3, 7, 1, 65.54, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(8, 3, 8, 1, 11.76, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(9, 3, 9, 1, 66.87, '2026-09-16 06:41:51', '2026-09-16 06:41:51');

-- Data for `orders`
INSERT INTO `orders` (`id`, `user_id`, `order_number`, `customer_name`, `customer_email`, `customer_phone`, `shipping_address`, `subtotal`, `shipping_fee`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `notes`, `ordered_at`, `stock_returned_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'ORD-20260507-000001', 'Prof. Santina Pouros Jr.', 'josiah.weissnat@example.org', '+1-848-629-3803', '570 Pacocha Flats
Schulistmouth, UT 64452-3936', 292.14, 0, 292.14, 'bank_transfer', 'failed', 'cancelled', 'Natus enim et dolores mollitia dolore officia.', '2026-05-07 06:41:51', '2026-05-08 06:41:51', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(2, 2, 'ORD-20260708-000002', 'Prof. Santina Pouros Jr.', 'josiah.weissnat@example.org', '+1-848-629-3803', '570 Pacocha Flats
Schulistmouth, UT 64452-3936', 166.81, 0, 166.81, 'cash_on_delivery', 'pending', 'pending', NULL, '2026-07-08 06:41:51', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(3, 3, 'ORD-20260910-000003', 'Erin Beatty V', 'gmertz@example.com', '325.518.2225', '711 Will Estate Suite 971
Aaronmouth, SD 78499-3182', 171.96, 0, 171.96, 'cash_on_delivery', 'pending', 'pending', 'Voluptatem ratione ex quaerat deleniti rerum iure.', '2026-09-10 06:41:51', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(4, 3, 'ORD-20260607-000004', 'Erin Beatty V', 'gmertz@example.com', '325.518.2225', '711 Will Estate Suite 971
Aaronmouth, SD 78499-3182', 191.99, 0, 191.99, 'cash_on_delivery', 'failed', 'cancelled', NULL, '2026-06-07 06:41:51', '2026-06-08 06:41:51', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(5, 4, 'ORD-20260630-000005', 'Josue Kunze', 'layne.hills@example.net', '+1 (484) 772-9163', '1717 Gideon Ridge
West Claudfurt, NH 95871', 318.16, 0, 318.16, 'cash_on_delivery', 'paid', 'completed', NULL, '2026-06-30 06:41:51', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(6, 4, 'ORD-20260407-000006', 'Josue Kunze', 'layne.hills@example.net', '+1 (484) 772-9163', '1717 Gideon Ridge
West Claudfurt, NH 95871', 268.86, 0, 268.86, 'cash_on_delivery', 'failed', 'pending', 'Officiis atque est quaerat accusantium.', '2026-04-07 06:41:51', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(7, 5, 'ORD-20260630-000007', 'Margarita Mohr', 'bethany11@example.org', '1-704-433-1800', '6396 Abbott Grove
Wisokyview, DC 43122-5361', 184.85, 0, 184.85, 'bank_transfer', 'pending', 'pending', 'Numquam asperiores debitis aliquid ea ratione delectus.', '2026-06-30 06:41:51', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(8, 5, 'ORD-20260713-000008', 'Margarita Mohr', 'bethany11@example.org', '1-704-433-1800', '6396 Abbott Grove
Wisokyview, DC 43122-5361', 143.91, 0, 143.91, 'bank_transfer', 'failed', 'cancelled', 'Ipsam laboriosam consequuntur distinctio aspernatur eveniet reiciendis delectus.', '2026-07-13 06:41:51', '2026-07-14 06:41:51', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(9, 6, 'ORD-20260904-000009', 'Vivienne Rempel', 'gail.koch@example.com', '+1-928-253-1886', '48094 Franecki Turnpike
North Jo, CA 09671', 251.09, 0, 251.09, 'cash_on_delivery', 'paid', 'processing', NULL, '2026-09-04 06:41:51', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(10, 6, 'ORD-20260515-000010', 'Vivienne Rempel', 'gail.koch@example.com', '+1-928-253-1886', '48094 Franecki Turnpike
North Jo, CA 09671', 167.25, 0, 167.25, 'cash_on_delivery', 'paid', 'shipped', NULL, '2026-05-15 06:41:51', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(11, 7, 'ORD-20260705-000011', 'Prof. Paige Ward MD', 'rodriguez.florian@example.net', '+1-734-352-5030', '6037 Tillman Village
New Felicia, UT 25529', 172.14, 0, 172.14, 'bank_transfer', 'paid', 'processing', 'Praesentium quae magni quis neque debitis velit qui.', '2026-07-05 06:41:51', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(12, 7, 'ORD-20260414-000012', 'Prof. Paige Ward MD', 'rodriguez.florian@example.net', '+1-734-352-5030', '6037 Tillman Village
New Felicia, UT 25529', 245.36, 0, 245.36, 'cash_on_delivery', 'paid', 'processing', 'Est aut atque est voluptate debitis ut recusandae et.', '2026-04-14 06:41:51', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51');

-- Data for `order_items`
INSERT INTO `order_items` (`id`, `order_id`, `book_id`, `book_title`, `book_isbn`, `quantity`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 'Little Cloud Learns to Read', '9781000000006', 2, 65.54, 131.08, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(2, 1, 12, 'The Midnight Library Bus', '9781000000011', 2, 54.38, 108.76, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(3, 1, 23, 'Rivers Through Time', '9781000000022', 2, 26.15, 52.3, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(4, 2, 23, 'Rivers Through Time', '9781000000022', 1, 26.15, 26.15, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(5, 2, 28, 'Simple Project Management', '9781000000027', 1, 17.74, 17.74, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(6, 2, 30, 'Secure Web Applications', '9781000000029', 2, 61.46, 122.92, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(7, 3, 3, 'Laravel From The Ground Up', '9781000000002', 1, 31.54, 31.54, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(8, 3, 7, 'Little Cloud Learns to Read', '9781000000006', 2, 65.54, 131.08, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(9, 3, 19, 'Everyday Economics', '9781000000018', 1, 9.34, 9.34, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(10, 4, 15, 'Stories Before Sunrise', '9781000000014', 2, 21.68, 43.36, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(11, 4, 21, 'Data Design Essentials', '9781000000020', 2, 50.37, 100.74, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(12, 4, 24, 'Marketing for Local Shops', '9781000000023', 1, 47.89, 47.89, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(13, 5, 1, 'The Last Chapter House', '9781000000000', 2, 75.94, 151.88, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(14, 5, 7, 'Little Cloud Learns to Read', '9781000000006', 1, 65.54, 65.54, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(15, 5, 21, 'Data Design Essentials', '9781000000020', 2, 50.37, 100.74, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(16, 6, 13, 'Founders and First Drafts', '9781000000012', 2, 78.56, 157.12, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(17, 6, 18, 'Tales From Willow Lane', '9781000000017', 2, 43.6, 87.2, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(18, 6, 22, 'Tiny Detectives Club', '9781000000021', 1, 24.54, 24.54, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(19, 7, 3, 'Laravel From The Ground Up', '9781000000002', 2, 31.54, 63.08, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(20, 7, 25, 'The Science of Sleep', '9781000000024', 2, 37.84, 75.68, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(21, 7, 27, 'The Author Next Door', '9781000000026', 1, 46.09, 46.09, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(22, 8, 6, 'Modern Store Operations', '9781000000005', 1, 19.15, 19.15, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(23, 8, 22, 'Tiny Detectives Club', '9781000000021', 2, 24.54, 49.08, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(24, 8, 25, 'The Science of Sleep', '9781000000024', 2, 37.84, 75.68, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(25, 9, 1, 'The Last Chapter House', '9781000000000', 1, 75.94, 75.94, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(26, 9, 5, 'The Quiet History of Maps', '9781000000004', 2, 62.39, 124.78, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(27, 9, 21, 'Data Design Essentials', '9781000000020', 1, 50.37, 50.37, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(28, 10, 5, 'The Quiet History of Maps', '9781000000004', 1, 62.39, 62.39, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(29, 10, 18, 'Tales From Willow Lane', '9781000000017', 2, 43.6, 87.2, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(30, 10, 20, 'The Bookshop Window', '9781000000019', 1, 17.66, 17.66, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(31, 11, 10, 'Ocean Science for Curious Minds', '9781000000009', 2, 69.13, 138.26, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(32, 11, 19, 'Everyday Economics', '9781000000018', 1, 9.34, 9.34, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(33, 11, 22, 'Tiny Detectives Club', '9781000000021', 1, 24.54, 24.54, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(34, 12, 14, 'PHP Patterns in Practice', '9781000000013', 1, 73.9, 73.9, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(35, 12, 24, 'Marketing for Local Shops', '9781000000023', 2, 47.89, 95.78, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(36, 12, 25, 'The Science of Sleep', '9781000000024', 2, 37.84, 75.68, '2026-09-16 06:41:51', '2026-09-16 06:41:51');

-- Data for `payments`
INSERT INTO `payments` (`id`, `order_id`, `transaction_id`, `amount`, `method`, `status`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 292.14, 'bank_transfer', 'failed', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(2, 2, NULL, 166.81, 'cash_on_delivery', 'pending', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(3, 3, NULL, 171.96, 'cash_on_delivery', 'pending', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(4, 4, NULL, 191.99, 'cash_on_delivery', 'failed', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(5, 5, 'TXN-IJ855PAIAF', 318.16, 'cash_on_delivery', 'paid', '2026-06-30 06:46:51', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(6, 6, NULL, 268.86, 'cash_on_delivery', 'failed', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(7, 7, NULL, 184.85, 'bank_transfer', 'pending', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(8, 8, NULL, 143.91, 'bank_transfer', 'failed', NULL, '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(9, 9, 'TXN-UKLFXWJW7J', 251.09, 'cash_on_delivery', 'paid', '2026-09-04 06:46:51', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(10, 10, 'TXN-6PHJ4XSGQP', 167.25, 'cash_on_delivery', 'paid', '2026-05-15 06:46:51', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(11, 11, 'TXN-2G2YQ1EBPO', 172.14, 'bank_transfer', 'paid', '2026-07-05 06:46:51', '2026-09-16 06:41:51', '2026-09-16 06:41:51'),
(12, 12, 'TXN-LQMBPDGBA8', 245.36, 'cash_on_delivery', 'paid', '2026-04-14 06:46:51', '2026-09-16 06:41:51', '2026-09-16 06:41:51');

-- Empty Laravel runtime tables intentionally included by schema:
-- password_reset_tokens, sessions, cache, cache_locks, jobs, job_batches, failed_jobs.
