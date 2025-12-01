-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for watu_integrated
CREATE DATABASE IF NOT EXISTS `watu_integrated` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `watu_integrated`;

-- Dumping structure for table watu_integrated.audit_logs
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.audit_logs: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.cache: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.cache_locks: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.chart_of_accounts
CREATE TABLE IF NOT EXISTS `chart_of_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('asset','liability','equity','revenue','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chart_of_accounts_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.chart_of_accounts: ~5 rows (approximately)
INSERT INTO `chart_of_accounts` (`id`, `code`, `name`, `type`, `created_at`, `updated_at`) VALUES
	(1, '1-101', 'Persediaan Bahan Baku', 'asset', '2025-11-23 10:40:57', '2025-11-23 10:40:57'),
	(2, '1-102', 'Kas Besar', 'asset', '2025-11-23 10:40:57', '2025-11-23 10:40:57'),
	(3, '2-101', 'Utang Dagang', 'liability', '2025-11-23 10:40:57', '2025-11-23 10:40:57'),
	(4, '4-100', 'Penjualan Kopi', 'revenue', '2025-11-23 10:40:57', '2025-11-23 10:40:57'),
	(5, '5-100', 'Beban Gaji', 'expense', '2025-11-23 10:40:57', '2025-11-23 10:40:57');

-- Dumping structure for table watu_integrated.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
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

-- Dumping data for table watu_integrated.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.ingredients
CREATE TABLE IF NOT EXISTS `ingredients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `minimum_stock` int NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.ingredients: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
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

-- Dumping data for table watu_integrated.jobs: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
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

-- Dumping data for table watu_integrated.job_batches: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.journals
CREATE TABLE IF NOT EXISTS `journals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ref_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_debit` decimal(15,2) NOT NULL,
  `total_credit` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.journals: ~3 rows (approximately)
INSERT INTO `journals` (`id`, `ref_number`, `transaction_date`, `description`, `total_debit`, `total_credit`, `created_at`, `updated_at`) VALUES
	(1, 'INV-20251123-8182', '2025-11-23', 'Penjualan POS: INV-20251123-8182', 425000.00, 425000.00, '2025-11-23 10:57:57', '2025-11-23 10:57:57'),
	(2, 'INV-20251123-2367', '2025-11-23', 'Penjualan POS: INV-20251123-2367', 46000.00, 46000.00, '2025-11-23 10:59:10', '2025-11-23 10:59:10'),
	(3, 'INV-20251123-7318', '2025-11-23', 'Penjualan POS: INV-20251123-7318', 69000.00, 69000.00, '2025-11-23 11:00:28', '2025-11-23 11:00:28'),
	(4, 'INV-20251123-6030', '2025-11-23', 'Penjualan POS: INV-20251123-6030', 48000.00, 48000.00, '2025-11-23 13:54:01', '2025-11-23 13:54:01');

-- Dumping structure for table watu_integrated.journal_details
CREATE TABLE IF NOT EXISTS `journal_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `journal_id` bigint unsigned NOT NULL,
  `account_id` bigint unsigned NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `journal_details_journal_id_foreign` (`journal_id`),
  KEY `journal_details_account_id_foreign` (`account_id`),
  CONSTRAINT `journal_details_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`),
  CONSTRAINT `journal_details_journal_id_foreign` FOREIGN KEY (`journal_id`) REFERENCES `journals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.journal_details: ~6 rows (approximately)
INSERT INTO `journal_details` (`id`, `journal_id`, `account_id`, `debit`, `credit`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 425000.00, 0.00, '2025-11-23 10:57:57', '2025-11-23 10:57:57'),
	(2, 1, 4, 0.00, 425000.00, '2025-11-23 10:57:57', '2025-11-23 10:57:57'),
	(3, 2, 2, 46000.00, 0.00, '2025-11-23 10:59:10', '2025-11-23 10:59:10'),
	(4, 2, 4, 0.00, 46000.00, '2025-11-23 10:59:10', '2025-11-23 10:59:10'),
	(5, 3, 2, 69000.00, 0.00, '2025-11-23 11:00:28', '2025-11-23 11:00:28'),
	(6, 3, 4, 0.00, 69000.00, '2025-11-23 11:00:28', '2025-11-23 11:00:28'),
	(7, 4, 2, 48000.00, 0.00, '2025-11-23 13:54:01', '2025-11-23 13:54:01'),
	(8, 4, 4, 0.00, 48000.00, '2025-11-23 13:54:01', '2025-11-23 13:54:01');

-- Dumping structure for table watu_integrated.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.migrations: ~0 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_11_20_170059_create_products_table', 1),
	(5, '2025_11_20_170255_create_transactions_table', 1),
	(6, '2025_11_20_204301_create_reservations_table', 1),
	(7, '2025_11_21_133045_create_procurement_and_accounting_tables', 1),
	(8, '2025_11_21_153542_create_orders_table', 1),
	(9, '2025_11_22_032844_create_ingredients_recipes_audit_tables', 1),
	(10, '2025_11_23_063351_add_ingredient_id_to_purchase_items', 1),
	(11, '2025_11_23_145151_add_details_to_products_table', 1),
	(12, '2025_11_23_151119_add_role_to_users_table', 1);

-- Dumping structure for table watu_integrated.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `pay_amount` decimal(15,2) NOT NULL,
  `change_amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_invoice_number_unique` (`invoice_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.orders: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.order_items: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `varietal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'porsi',
  `description` text COLLATE utf8mb4_unicode_ci,
  `stock` int NOT NULL DEFAULT '0',
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.products: ~23 rows (approximately)
INSERT INTO `products` (`id`, `code`, `name`, `category`, `varietal`, `process`, `price`, `unit`, `description`, `stock`, `is_available`, `image`, `created_at`, `updated_at`) VALUES
	(1, 'BN-PNG-200', 'Arabica Java Pangalengan', 'roast_bean', 'Sigararutang', 'Natural', 95000.00, '200gr', 'Taste Notes: Berry, Floral, Sweet Aftertaste. Medium Roast.', 19, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:57:57'),
	(2, 'BN-KIN-200', 'Arabica Bali Kintamani', 'roast_bean', 'Kopyol / Kartika', 'Koji Fermented', 151000.00, '200gr', 'Taste Notes: Green Apple, Molasses, Lime. Unique fermentation process.', 18, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:57:57'),
	(3, 'BN-BJW-200', 'Arabica Flores Bajawa', 'roast_bean', 'S795 (Jember)', 'Full Wash', 98000.00, '200gr', 'Taste Notes: Nutty, Caramel, Medium Body.', 20, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(4, 'BN-TBN-200', 'Robusta Tabanan Bali', 'roast_bean', 'Klon BP-42', 'Natural', 65000.00, '200gr', 'Taste Notes: Bold, Dark Chocolate, Earthy.', 20, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(5, 'BN-HSE-1KG', 'Watu House Blend', 'roast_bean', 'Mix Arabica & Robusta', 'Semi Wash', 220000.00, '1kg', 'Campuran khusus untuk mesin Espresso.', 20, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(6, NULL, 'Es Kopi Susu Watu', 'coffee', NULL, NULL, 24000.00, 'cup', 'Es Kopi Susu dengan Creamer/Gula Aren khas Watu', 97, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 13:54:01'),
	(7, NULL, 'Es Kopi Hitam', 'coffee', NULL, NULL, 23000.00, 'cup', 'Double Shot Espresso + Ice + Water', 100, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(8, NULL, 'Manual Brew V60', 'coffee', NULL, NULL, 28000.00, 'cup', 'Pilihan Beans: Arabica Gayo/Bali/Java', 99, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:57:57'),
	(9, NULL, 'Japanese Iced Coffee', 'coffee', NULL, NULL, 30000.00, 'cup', 'Manual Brew dingin menyegarkan', 100, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(10, NULL, 'Cappuccino Hot', 'coffee', NULL, NULL, 25000.00, 'cup', 'Espresso + Steamed Milk + Foam tebal', 99, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:59:10'),
	(11, NULL, 'Caffe Latte', 'coffee', NULL, NULL, 25000.00, 'cup', 'Espresso + Steamed Milk (Light Foam)', 100, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(12, NULL, 'Kopi Tubruk Watu', 'coffee', NULL, NULL, 18000.00, 'cup', 'Kopi hitam tradisional ampas', 100, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(13, NULL, 'Es Teh Susu', 'non_coffee', NULL, NULL, 23000.00, 'cup', 'Teh Saring + Susu Kental Manis/Creamer', 100, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(14, NULL, 'Es Milo Malaysia', 'non_coffee', NULL, NULL, 22000.00, 'cup', 'Milo kental tabur bubuk', 100, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(15, NULL, 'Watu Honey Lemon', 'non_coffee', NULL, NULL, 28000.00, 'cup', 'Madu Watu (Talasi) + Lemon segar', 100, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(16, NULL, 'Es Lychee Tea', 'non_coffee', NULL, NULL, 25000.00, 'cup', 'Teh rasa leci dengan buah asli', 100, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(17, NULL, 'Chocolate Signature', 'non_coffee', NULL, NULL, 26000.00, 'cup', 'Coklat pekat panas/dingin', 100, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(18, NULL, 'Singkong Goreng Watu', 'food', NULL, NULL, 20000.00, 'porsi', 'Singkong goreng mekar, gurih, empuk', 49, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 11:00:28'),
	(19, NULL, 'Pisang Goreng Srikaya', 'food', NULL, NULL, 25000.00, 'porsi', 'Pisang goreng dengan selai srikaya', 49, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 11:00:28'),
	(20, NULL, 'Cireng Rujak', 'food', NULL, NULL, 18000.00, 'porsi', 'Cireng garing dengan bumbu rujak pedas manis', 50, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(21, NULL, 'Kentang Goreng', 'food', NULL, NULL, 21000.00, 'porsi', 'Shoestring french fries', 49, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:59:10'),
	(22, NULL, 'Nasi Goreng Jawa', 'food', NULL, NULL, 28000.00, 'porsi', 'Nasi goreng bumbu rempah jawa + Telur + Kerupuk', 50, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09'),
	(23, NULL, 'Roti Bakar Keju Susu', 'food', NULL, NULL, 22000.00, 'porsi', 'Roti bakar tebal topping melimpah', 50, 1, NULL, '2025-11-23 10:56:09', '2025-11-23 10:56:09');

-- Dumping structure for table watu_integrated.purchases
CREATE TABLE IF NOT EXISTS `purchases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `transaction_date` date NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchases_invoice_number_unique` (`invoice_number`),
  KEY `purchases_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.purchases: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.purchase_items
CREATE TABLE IF NOT EXISTS `purchase_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint unsigned NOT NULL,
  `ingredient_id` bigint unsigned DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  KEY `purchase_items_ingredient_id_foreign` (`ingredient_id`),
  CONSTRAINT `purchase_items_ingredient_id_foreign` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.purchase_items: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.recipes
CREATE TABLE IF NOT EXISTS `recipes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `ingredient_id` bigint unsigned NOT NULL,
  `amount_needed` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipes_product_id_foreign` (`product_id`),
  KEY `recipes_ingredient_id_foreign` (`ingredient_id`),
  CONSTRAINT `recipes_ingredient_id_foreign` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `recipes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.recipes: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.reservations
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `pax` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.reservations: ~0 rows (approximately)

-- Dumping structure for table watu_integrated.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
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

-- Dumping data for table watu_integrated.sessions: ~1 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('dq9EF9hHjeLJw8AHJ1ZTwGy4lMnLvPb2iHogjOth', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieWN2cHVhVmlHVWc3MU9HQ1VkZ1VlUlUzVHdzRTByQkhIbTRQaDEyMyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wb3MvcHJpbnQvNCI7czo1OiJyb3V0ZSI7czo5OiJwb3MucHJpbnQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1763931242);

-- Dumping structure for table watu_integrated.suppliers
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.suppliers: ~2 rows (approximately)
INSERT INTO `suppliers` (`id`, `name`, `phone`, `created_at`, `updated_at`) VALUES
	(1, 'PT. Kopi Nusantara', '08123456789', '2025-11-23 10:40:57', '2025-11-23 10:40:57'),
	(2, 'UD. Susu Segar', '08987654321', '2025-11-23 10:40:57', '2025-11-23 10:40:57');

-- Dumping structure for table watu_integrated.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unpaid',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_invoice_number_unique` (`invoice_number`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.transactions: ~3 rows (approximately)
INSERT INTO `transactions` (`id`, `invoice_number`, `customer_name`, `type`, `total_amount`, `payment_status`, `payment_method`, `created_at`, `updated_at`) VALUES
	(1, 'INV-20251123-8182', 'Walk-in Customer', 'Dine-in', 425000.00, 'Paid', 'Cash', '2025-11-23 10:57:56', '2025-11-23 10:57:56'),
	(2, 'INV-20251123-2367', 'Walk-in Customer', 'Dine-in', 46000.00, 'Paid', 'Cash', '2025-11-23 10:59:10', '2025-11-23 10:59:10'),
	(3, 'INV-20251123-7318', 'Walk-in Customer', 'Dine-in', 69000.00, 'Paid', 'Cash', '2025-11-23 11:00:28', '2025-11-23 11:00:28'),
	(4, 'INV-20251123-6030', 'Walk-in Customer', 'Dine-in', 48000.00, 'Paid', 'Cash', '2025-11-23 13:54:01', '2025-11-23 13:54:01');

-- Dumping structure for table watu_integrated.transaction_items
CREATE TABLE IF NOT EXISTS `transaction_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  KEY `transaction_items_product_id_foreign` (`product_id`),
  CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.transaction_items: ~8 rows (approximately)
INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
	(1, 1, 1, 1, 95000.00, 95000.00),
	(2, 1, 2, 2, 151000.00, 302000.00),
	(3, 1, 8, 1, 28000.00, 28000.00),
	(4, 2, 10, 1, 25000.00, 25000.00),
	(5, 2, 21, 1, 21000.00, 21000.00),
	(6, 3, 19, 1, 25000.00, 25000.00),
	(7, 3, 18, 1, 20000.00, 20000.00),
	(8, 3, 6, 1, 24000.00, 24000.00),
	(9, 4, 6, 2, 24000.00, 48000.00);

-- Dumping structure for table watu_integrated.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','manager','owner','barista','roaster') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'barista',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.users: ~4 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin Watu', 'admin@watu.com', 'admin', NULL, '$2y$12$gRFF.dsmjYlXgNt.kDPFrubyqdVv2be5snaUryXHbq9Y3xJdqghzq', NULL, '2025-11-23 10:40:56', '2025-11-23 10:40:56'),
	(2, 'Manajer/Owner Watu', 'manager@watu.com', 'manager', NULL, '$2y$12$N7BPKETEcN5ZQMtGH9Yqcu58rhatOXgO/VZCmSS37dA7IEET.XGhG', NULL, '2025-11-23 10:40:57', '2025-11-23 10:40:57'),
	(3, 'Barista Watu', 'barista@watu.com', 'barista', NULL, '$2y$12$WkeWEN69/4iobCEUY0h4N.GkTEGAK8cIdyyrI5mJuNPx0cSyk0LZC', NULL, '2025-11-23 10:40:57', '2025-11-23 10:40:57'),
	(4, 'Roaster Watu', 'roaster@watu.com', 'roaster', NULL, '$2y$12$zoxPh55nJF17MPeYEEcNzOdlyzrDscdTrH2SMT0pB2gq/4mUpyk7C', NULL, '2025-11-23 10:40:57', '2025-11-23 10:40:57');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
