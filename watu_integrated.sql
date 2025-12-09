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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.chart_of_accounts: ~6 rows (approximately)
INSERT INTO `chart_of_accounts` (`id`, `code`, `name`, `type`, `created_at`, `updated_at`) VALUES
	(1, '1-101', 'Persediaan Bahan Baku', 'asset', '2025-12-08 07:55:51', '2025-12-08 07:55:51'),
	(2, '1-102', 'Kas Besar', 'asset', '2025-12-08 07:55:51', '2025-12-08 07:55:51'),
	(3, '2-101', 'Utang Dagang', 'liability', '2025-12-08 07:55:51', '2025-12-08 07:55:51'),
	(4, '4-100', 'Penjualan Cafe', 'revenue', '2025-12-08 07:55:51', '2025-12-08 07:55:51'),
	(5, '4-101', 'Penjualan Roastery', 'revenue', '2025-12-08 07:55:51', '2025-12-08 07:55:51'),
	(6, '5-100', 'Beban Gaji', 'expense', '2025-12-08 07:55:51', '2025-12-08 07:55:51'),
	(7, '5-101', 'Beban Pokok Penjualan (HPP)', 'expense', '2025-12-09 08:53:30', '2025-12-09 08:53:30');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.ingredients: ~1 rows (approximately)
INSERT INTO `ingredients` (`id`, `name`, `unit`, `stock`, `cost_price`, `minimum_stock`, `created_at`, `updated_at`) VALUES
	(1, 'Greenfields Fresh Milk 950ml', 'pcs', 60, 0.00, 10, '2025-12-09 08:11:19', '2025-12-09 08:13:03'),
	(2, 'COGS Test Bean', 'kg', 10, 10000.00, 10, '2025-12-09 08:58:15', '2025-12-09 08:58:15');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.journals: ~8 rows (approximately)
INSERT INTO `journals` (`id`, `ref_number`, `transaction_date`, `description`, `total_debit`, `total_credit`, `created_at`, `updated_at`) VALUES
	(1, 'WEB-1765264763', '2025-12-09', 'Penerimaan Web: Mamin (08912787371)', 315000.00, 315000.00, '2025-12-09 02:04:23', '2025-12-09 02:04:23'),
	(2, 'INV-20251209-9597', '2025-12-09', 'Penjualan POS: INV-20251209-9597', 220000.00, 220000.00, '2025-12-09 07:48:53', '2025-12-09 07:48:53'),
	(3, 'INV-20251209-4297', '2025-12-09', 'Penjualan POS: INV-20251209-4297', 220000.00, 220000.00, '2025-12-09 07:48:55', '2025-12-09 07:48:55'),
	(4, 'INV-20251209-1066', '2025-12-09', 'Penjualan POS: INV-20251209-1066', 65000.00, 65000.00, '2025-12-09 07:55:59', '2025-12-09 07:55:59'),
	(5, 'INV-20251209-2764', '2025-12-09', 'Penjualan POS: INV-20251209-2764', 53000.00, 53000.00, '2025-12-09 07:58:16', '2025-12-09 07:58:16'),
	(6, 'WEB-1765292584', '2025-12-09', 'Sales Order WEB-1765292584 (asd (4568002124))', 24000.00, 24000.00, '2025-12-09 08:03:04', '2025-12-09 08:03:04'),
	(7, 'WEB-1765292584', '2025-12-09', 'Penerimaan Web: asd (4568002124)', 24000.00, 24000.00, '2025-12-09 08:03:34', '2025-12-09 08:03:34'),
	(8, 'PUR-1765293183', '2025-12-09', 'Pembelian Stok: PUR-1765293183', 3600000.00, 3600000.00, '2025-12-09 08:13:04', '2025-12-09 08:13:04'),
	(9, 'WEB-1765293666', '2025-12-09', 'Sales Order WEB-1765293666 (Bambang (0912763612))', 49000.00, 49000.00, '2025-12-09 08:21:06', '2025-12-09 08:21:06'),
	(10, 'WEB-1765294143', '2025-12-09', 'Sales Order WEB-1765294143 (kakal (087123798129))', 50000.00, 50000.00, '2025-12-09 08:29:03', '2025-12-09 08:29:03'),
	(11, 'WEB-1765293666', '2025-12-09', 'Penerimaan Web: Bambang (0912763612)', 49000.00, 49000.00, '2025-12-09 08:29:15', '2025-12-09 08:29:15'),
	(12, 'WEB-1765294143', '2025-12-09', 'Penerimaan Web: kakal (087123798129)', 50000.00, 50000.00, '2025-12-09 08:30:34', '2025-12-09 08:30:34'),
	(13, 'WEB-1765294365', '2025-12-09', 'Sales Order WEB-1765294365 (okk (08271287129))', 78000.00, 78000.00, '2025-12-09 08:32:45', '2025-12-09 08:32:45'),
	(14, 'WEB-1765294365', '2025-12-09', 'Penerimaan Web: okk (08271287129)', 78000.00, 78000.00, '2025-12-09 08:36:03', '2025-12-09 08:36:03'),
	(15, 'WEB-1765294589', '2025-12-09', 'Sales Order WEB-1765294589 (Maman (086123658129))', 41000.00, 41000.00, '2025-12-09 08:36:29', '2025-12-09 08:36:29'),
	(16, 'WEB-1765294589', '2025-12-09', 'Penerimaan Web: Maman (086123658129)', 41000.00, 41000.00, '2025-12-09 08:44:15', '2025-12-09 08:44:15'),
	(17, 'PUR-1765295953', '2025-12-09', 'Pembelian Stok: PUR-1765295953', 200000.00, 200000.00, '2025-12-09 08:59:13', '2025-12-09 08:59:13'),
	(18, 'INV-20251209-9962', '2025-12-09', 'Penjualan POS: INV-20251209-9962', 50000.00, 50000.00, '2025-12-09 08:59:13', '2025-12-09 08:59:13'),
	(19, 'PUR-1765296002', '2025-12-09', 'Pembelian Stok: PUR-1765296002', 200000.00, 200000.00, '2025-12-09 09:00:02', '2025-12-09 09:00:02'),
	(20, 'INV-20251209-5038', '2025-12-09', 'Penjualan POS: INV-20251209-5038', 50000.00, 50000.00, '2025-12-09 09:00:02', '2025-12-09 09:00:02');

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.journal_details: ~16 rows (approximately)
INSERT INTO `journal_details` (`id`, `journal_id`, `account_id`, `debit`, `credit`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 315000.00, 0.00, '2025-12-09 02:04:23', '2025-12-09 02:04:23'),
	(2, 1, 5, 0.00, 315000.00, '2025-12-09 02:04:23', '2025-12-09 02:04:23'),
	(3, 2, 2, 220000.00, 0.00, '2025-12-09 07:48:53', '2025-12-09 07:48:53'),
	(4, 2, 5, 0.00, 220000.00, '2025-12-09 07:48:53', '2025-12-09 07:48:53'),
	(5, 3, 2, 220000.00, 0.00, '2025-12-09 07:48:55', '2025-12-09 07:48:55'),
	(6, 3, 5, 0.00, 220000.00, '2025-12-09 07:48:55', '2025-12-09 07:48:55'),
	(7, 4, 2, 65000.00, 0.00, '2025-12-09 07:55:59', '2025-12-09 07:55:59'),
	(8, 4, 5, 0.00, 65000.00, '2025-12-09 07:55:59', '2025-12-09 07:55:59'),
	(9, 5, 2, 53000.00, 0.00, '2025-12-09 07:58:16', '2025-12-09 07:58:16'),
	(10, 5, 4, 0.00, 53000.00, '2025-12-09 07:58:16', '2025-12-09 07:58:16'),
	(11, 6, 2, 24000.00, 0.00, '2025-12-09 08:03:04', '2025-12-09 08:03:04'),
	(12, 6, 4, 0.00, 24000.00, '2025-12-09 08:03:04', '2025-12-09 08:03:04'),
	(13, 7, 2, 24000.00, 0.00, '2025-12-09 08:03:34', '2025-12-09 08:03:34'),
	(14, 7, 4, 0.00, 24000.00, '2025-12-09 08:03:34', '2025-12-09 08:03:34'),
	(15, 8, 1, 3600000.00, 0.00, '2025-12-09 08:13:04', '2025-12-09 08:13:04'),
	(16, 8, 3, 0.00, 3600000.00, '2025-12-09 08:13:04', '2025-12-09 08:13:04'),
	(17, 9, 2, 49000.00, 0.00, '2025-12-09 08:21:06', '2025-12-09 08:21:06'),
	(18, 9, 4, 0.00, 49000.00, '2025-12-09 08:21:06', '2025-12-09 08:21:06'),
	(19, 10, 2, 50000.00, 0.00, '2025-12-09 08:29:03', '2025-12-09 08:29:03'),
	(20, 10, 4, 0.00, 50000.00, '2025-12-09 08:29:03', '2025-12-09 08:29:03'),
	(21, 11, 2, 49000.00, 0.00, '2025-12-09 08:29:15', '2025-12-09 08:29:15'),
	(22, 11, 4, 0.00, 49000.00, '2025-12-09 08:29:15', '2025-12-09 08:29:15'),
	(23, 12, 2, 50000.00, 0.00, '2025-12-09 08:30:34', '2025-12-09 08:30:34'),
	(24, 12, 4, 0.00, 50000.00, '2025-12-09 08:30:34', '2025-12-09 08:30:34'),
	(25, 13, 2, 78000.00, 0.00, '2025-12-09 08:32:45', '2025-12-09 08:32:45'),
	(26, 13, 4, 0.00, 78000.00, '2025-12-09 08:32:45', '2025-12-09 08:32:45'),
	(27, 14, 2, 78000.00, 0.00, '2025-12-09 08:36:03', '2025-12-09 08:36:03'),
	(28, 14, 4, 0.00, 78000.00, '2025-12-09 08:36:03', '2025-12-09 08:36:03'),
	(29, 15, 2, 41000.00, 0.00, '2025-12-09 08:36:29', '2025-12-09 08:36:29'),
	(30, 15, 4, 0.00, 41000.00, '2025-12-09 08:36:29', '2025-12-09 08:36:29'),
	(31, 16, 2, 41000.00, 0.00, '2025-12-09 08:44:15', '2025-12-09 08:44:15'),
	(32, 16, 4, 0.00, 41000.00, '2025-12-09 08:44:15', '2025-12-09 08:44:15'),
	(33, 17, 1, 200000.00, 0.00, '2025-12-09 08:59:13', '2025-12-09 08:59:13'),
	(34, 17, 2, 0.00, 200000.00, '2025-12-09 08:59:13', '2025-12-09 08:59:13'),
	(35, 18, 2, 50000.00, 0.00, '2025-12-09 08:59:13', '2025-12-09 08:59:13'),
	(36, 18, 4, 0.00, 50000.00, '2025-12-09 08:59:13', '2025-12-09 08:59:13'),
	(37, 18, 7, 15000.00, 0.00, '2025-12-09 08:59:13', '2025-12-09 08:59:13'),
	(38, 18, 1, 0.00, 15000.00, '2025-12-09 08:59:13', '2025-12-09 08:59:13'),
	(39, 19, 1, 200000.00, 0.00, '2025-12-09 09:00:02', '2025-12-09 09:00:02'),
	(40, 19, 2, 0.00, 200000.00, '2025-12-09 09:00:02', '2025-12-09 09:00:02'),
	(41, 20, 2, 50000.00, 0.00, '2025-12-09 09:00:02', '2025-12-09 09:00:02'),
	(42, 20, 4, 0.00, 50000.00, '2025-12-09 09:00:02', '2025-12-09 09:00:02'),
	(43, 20, 7, 15000.00, 0.00, '2025-12-09 09:00:02', '2025-12-09 09:00:02'),
	(44, 20, 1, 0.00, 15000.00, '2025-12-09 09:00:02', '2025-12-09 09:00:02');

-- Dumping structure for table watu_integrated.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.migrations: ~15 rows (approximately)
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
	(12, '2025_11_23_151119_add_role_to_users_table', 1),
	(13, '2025_12_08_133336_add_payment_terms_to_purchases_table', 1),
	(14, '2025_12_09_040253_add_uuid_to_transactions_table', 2),
	(15, '2025_12_09_040925_backfill_uuid_on_transactions', 3),
	(16, '2025_12_09_155055_add_cogs_columns_to_tables', 4);

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
INSERT INTO `products` (`id`, `code`, `name`, `category`, `varietal`, `process`, `price`, `cost_price`, `unit`, `description`, `stock`, `is_available`, `image`, `created_at`, `updated_at`) VALUES
	(1, 'BN-PNG-200', 'Arabica Java Pangalengan', 'roast_bean', 'Sigararutang', 'Natural', 95000.00, 0.00, '200gr', 'Taste Notes: Berry, Floral, Sweet Aftertaste. Medium Roast.', 20, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(2, 'BN-KIN-200', 'Arabica Bali Kintamani', 'roast_bean', 'Kopyol / Kartika', 'Koji Fermented', 151000.00, 0.00, '200gr', 'Taste Notes: Green Apple, Molasses, Lime. Unique fermentation process.', 20, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(3, 'BN-BJW-200', 'Arabica Flores Bajawa', 'roast_bean', 'S795 (Jember)', 'Full Wash', 98000.00, 0.00, '200gr', 'Taste Notes: Nutty, Caramel, Medium Body.', 20, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(4, 'BN-TBN-200', 'Robusta Tabanan Bali', 'roast_bean', 'Klon BP-42', 'Natural', 65000.00, 0.00, '200gr', 'Taste Notes: Bold, Dark Chocolate, Earthy.', 19, 1, NULL, '2025-12-08 08:45:08', '2025-12-09 07:55:59'),
	(5, 'BN-HSE-1KG', 'Watu House Blend', 'roast_bean', 'Mix Arabica & Robusta', 'Semi Wash', 220000.00, 0.00, '1kg', 'Campuran khusus untuk mesin Espresso.', 18, 1, NULL, '2025-12-08 08:45:08', '2025-12-09 07:48:55'),
	(6, NULL, 'Es Kopi Susu Watu', 'coffee', NULL, NULL, 24000.00, 0.00, 'cup', 'Es Kopi Susu dengan Creamer/Gula Aren khas Watu', 100, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(7, NULL, 'Es Kopi Hitam', 'coffee', NULL, NULL, 23000.00, 0.00, 'cup', 'Double Shot Espresso + Ice + Water', 100, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(8, NULL, 'Manual Brew V60', 'coffee', NULL, NULL, 28000.00, 0.00, 'cup', 'Pilihan Beans: Arabica Gayo/Bali/Java', 100, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(9, NULL, 'Japanese Iced Coffee', 'coffee', NULL, NULL, 30000.00, 0.00, 'cup', 'Manual Brew dingin menyegarkan', 100, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(10, NULL, 'Cappuccino Hot', 'coffee', NULL, NULL, 25000.00, 0.00, 'cup', 'Espresso + Steamed Milk + Foam tebal', 100, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(11, NULL, 'Caffe Latte', 'coffee', NULL, NULL, 25000.00, 0.00, 'cup', 'Espresso + Steamed Milk (Light Foam)', 99, 1, NULL, '2025-12-08 08:45:08', '2025-12-09 07:58:16'),
	(12, NULL, 'Kopi Tubruk Watu', 'coffee', NULL, NULL, 18000.00, 0.00, 'cup', 'Kopi hitam tradisional ampas', 100, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(13, NULL, 'Es Teh Susu', 'non_coffee', NULL, NULL, 23000.00, 0.00, 'cup', 'Teh Saring + Susu Kental Manis/Creamer', 100, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(14, NULL, 'Es Milo Malaysia', 'non_coffee', NULL, NULL, 22000.00, 0.00, 'cup', 'Milo kental tabur bubuk', 100, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(15, NULL, 'Watu Honey Lemon', 'non_coffee', NULL, NULL, 28000.00, 0.00, 'cup', 'Madu Watu (Talasi) + Lemon segar', 99, 1, NULL, '2025-12-08 08:45:08', '2025-12-09 07:58:16'),
	(16, NULL, 'Es Lychee Tea', 'non_coffee', NULL, NULL, 25000.00, 0.00, 'cup', 'Teh rasa leci dengan buah asli', 100, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(17, NULL, 'Chocolate Signature', 'non_coffee', NULL, NULL, 26000.00, 0.00, 'cup', 'Coklat pekat panas/dingin', 100, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(18, NULL, 'Singkong Goreng Watu', 'food', NULL, NULL, 20000.00, 0.00, 'porsi', 'Singkong goreng mekar, gurih, empuk', 50, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(19, NULL, 'Pisang Goreng Srikaya', 'food', NULL, NULL, 25000.00, 0.00, 'porsi', 'Pisang goreng dengan selai srikaya', 50, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(20, NULL, 'Cireng Rujak', 'food', NULL, NULL, 18000.00, 0.00, 'porsi', 'Cireng garing dengan bumbu rujak pedas manis', 50, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(21, NULL, 'Kentang Goreng', 'food', NULL, NULL, 21000.00, 0.00, 'porsi', 'Shoestring french fries', 50, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(22, NULL, 'Nasi Goreng Jawa', 'food', NULL, NULL, 28000.00, 0.00, 'porsi', 'Nasi goreng bumbu rempah jawa + Telur + Kerupuk', 50, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(23, NULL, 'Roti Bakar Keju Susu', 'food', NULL, NULL, 22000.00, 0.00, 'porsi', 'Roti bakar tebal topping melimpah', 50, 1, NULL, '2025-12-08 08:45:08', '2025-12-08 08:45:08'),
	(24, NULL, 'COGS Test Coffee', 'coffee', NULL, NULL, 50000.00, 0.00, 'porsi', NULL, 0, 1, NULL, '2025-12-09 08:58:15', '2025-12-09 08:58:15'),
	(25, NULL, 'COGS Test Coffee', 'coffee', NULL, NULL, 50000.00, 0.00, 'porsi', NULL, 0, 1, NULL, '2025-12-09 08:59:13', '2025-12-09 08:59:13');

-- Dumping structure for table watu_integrated.purchases
CREATE TABLE IF NOT EXISTS `purchases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `transaction_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchases_invoice_number_unique` (`invoice_number`),
  KEY `purchases_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.purchases: ~1 rows (approximately)
INSERT INTO `purchases` (`id`, `invoice_number`, `supplier_id`, `transaction_date`, `due_date`, `total_amount`, `payment_status`, `payment_method`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 'PUR-1765293183', 2, '2025-12-09', '2025-12-31', 3600000.00, 'unpaid', 'credit', NULL, '2025-12-09 08:13:03', '2025-12-09 08:13:03'),
	(3, 'PUR-1765295953', 1, '2025-12-09', '2025-12-09', 200000.00, 'paid', 'cash', NULL, '2025-12-09 08:59:13', '2025-12-09 08:59:13'),
	(4, 'PUR-1765296002', 1, '2025-12-09', '2025-12-09', 200000.00, 'paid', 'cash', NULL, '2025-12-09 09:00:02', '2025-12-09 09:00:02');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.purchase_items: ~1 rows (approximately)
INSERT INTO `purchase_items` (`id`, `purchase_id`, `ingredient_id`, `item_name`, `quantity`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'Greenfields Fresh Milk 950ml', 12, 300000.00, 3600000.00, '2025-12-09 08:13:03', '2025-12-09 08:13:03'),
	(2, 3, NULL, 'COGS Test Bean', 10, 20000.00, 200000.00, '2025-12-09 08:59:13', '2025-12-09 08:59:13'),
	(3, 4, NULL, 'COGS Test Bean', 10, 20000.00, 200000.00, '2025-12-09 09:00:02', '2025-12-09 09:00:02');

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
INSERT INTO `recipes` (`id`, `product_id`, `ingredient_id`, `amount_needed`, `created_at`, `updated_at`) VALUES
	(1, 24, 2, 1, '2025-12-09 08:58:15', '2025-12-09 08:58:15');

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
	('RjET2FZ42Pe4xthB7tOp3vZ3SnT1rYIgujoaATfh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieXd1c1VaZVpIQnk4bGxuRjdUWXQ3MEFmaGN6c01rVjhlc0FVQzlOdyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9fQ==', 1765296634);

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
	(1, 'PT. Kopi Nusantara', '08123456789', '2025-12-08 07:55:51', '2025-12-08 07:55:51'),
	(2, 'UD. Susu Segar', '08987654321', '2025-12-08 07:55:51', '2025-12-08 07:55:51');

-- Dumping structure for table watu_integrated.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unpaid',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_invoice_number_unique` (`invoice_number`),
  UNIQUE KEY `transactions_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.transactions: ~6 rows (approximately)
INSERT INTO `transactions` (`id`, `uuid`, `invoice_number`, `customer_name`, `type`, `total_amount`, `payment_status`, `payment_method`, `created_at`, `updated_at`) VALUES
	(1, '019b01fa-ab0c-70fb-973e-134d2fffed7b', 'WEB-1765264763', 'Mamin (08912787371)', 'Web-Order', 315000.00, 'Paid', 'Transfer/QRIS', '2025-12-09 00:19:23', '2025-12-09 02:04:23'),
	(2, '019b0396-2b43-73d1-9dc2-35cb2ba66572', 'INV-20251209-9597', 'Walk-in Customer', 'Dine-in', 220000.00, 'Paid', 'Cash', '2025-12-09 07:48:51', '2025-12-09 07:48:51'),
	(3, '019b0396-3a22-71d4-bba3-1f2a665cb779', 'INV-20251209-4297', 'Walk-in Customer', 'Dine-in', 220000.00, 'Paid', 'Cash', '2025-12-09 07:48:55', '2025-12-09 07:48:55'),
	(4, '019b039c-b0ab-7051-b09f-2833cf232005', 'INV-20251209-1066', 'Walk-in Customer', 'Dine-in', 65000.00, 'Paid', 'QRIS', '2025-12-09 07:55:59', '2025-12-09 07:55:59'),
	(5, '019b039e-c9b5-7240-8090-c99516c94ee2', 'INV-20251209-2764', 'Walk-in Customer', 'Dine-in', 53000.00, 'Paid', 'QRIS', '2025-12-09 07:58:16', '2025-12-09 07:58:16'),
	(6, '019b03a3-2dd8-715e-97a1-d4094b92d527', 'WEB-1765292584', 'asd (4568002124)', 'Web-Order', 24000.00, 'Paid', 'Transfer/QRIS', '2025-12-09 08:03:04', '2025-12-09 08:03:34'),
	(7, '019b03b3-b09d-7073-8fe6-955f6140956c', 'WEB-1765293666', 'Bambang (0912763612)', 'Web-Order', 49000.00, 'Paid', 'Transfer/QRIS', '2025-12-09 08:21:06', '2025-12-09 08:29:15'),
	(8, '019b03ba-f6f0-710f-87ac-c0379f66b3e4', 'WEB-1765294143', 'kakal (087123798129)', 'Web-Order', 50000.00, 'Paid', 'Transfer/QRIS', '2025-12-09 08:29:03', '2025-12-09 08:30:34'),
	(9, '019b03be-5ca4-7133-ad19-8ed9d2b7eb6a', 'WEB-1765294365', 'okk (08271287129)', 'Web-Order', 78000.00, 'Paid', 'Transfer/QRIS', '2025-12-09 08:32:45', '2025-12-09 08:36:03'),
	(10, '019b03c1-c63c-7258-b0fc-41be1d37dd0b', 'WEB-1765294589', 'Maman (086123658129)', 'Web-Order', 41000.00, 'Paid', 'Transfer/QRIS', '2025-12-09 08:36:29', '2025-12-09 08:44:15'),
	(11, '019b03d6-9614-7175-90c7-1f515c859eb2', 'INV-20251209-9962', 'Guest', 'Dine-in', 50000.00, 'Paid', 'Cash', '2025-12-09 08:59:13', '2025-12-09 08:59:13');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.transaction_items: ~8 rows (approximately)
INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `quantity`, `price`, `cost_price`, `subtotal`) VALUES
	(1, 1, 1, 1, 95000.00, 0.00, 95000.00),
	(2, 1, 5, 1, 220000.00, 0.00, 220000.00),
	(3, 2, 5, 1, 220000.00, 0.00, 220000.00),
	(4, 3, 5, 1, 220000.00, 0.00, 220000.00),
	(5, 4, 4, 1, 65000.00, 0.00, 65000.00),
	(6, 5, 15, 1, 28000.00, 0.00, 28000.00),
	(7, 5, 11, 1, 25000.00, 0.00, 25000.00),
	(8, 6, 6, 1, 24000.00, 0.00, 24000.00),
	(9, 7, 7, 1, 23000.00, 0.00, 23000.00),
	(10, 7, 17, 1, 26000.00, 0.00, 26000.00),
	(11, 8, 10, 1, 25000.00, 0.00, 25000.00),
	(12, 8, 11, 1, 25000.00, 0.00, 25000.00),
	(13, 9, 7, 1, 23000.00, 0.00, 23000.00),
	(14, 9, 9, 1, 30000.00, 0.00, 30000.00),
	(15, 9, 19, 1, 25000.00, 0.00, 25000.00),
	(16, 10, 12, 1, 18000.00, 0.00, 18000.00),
	(17, 10, 13, 1, 23000.00, 0.00, 23000.00),
	(18, 11, 25, 1, 50000.00, 15000.00, 50000.00);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table watu_integrated.users: ~4 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin Watu', 'admin@watu.com', 'admin', NULL, '$2y$12$hAm66jY8MgswTkzcmco4p.EGsw4KD6lS6OCG/.uUsIItJtJpIdsdm', NULL, '2025-12-08 07:55:50', '2025-12-08 07:55:50'),
	(2, 'Manajer/Owner Watu', 'manager@watu.com', 'manager', NULL, '$2y$12$Fhj4XkWYy.lTrGWZGuQNXehkqV8BWnGBXjMyxXjlbVurzP.GjRHY2', NULL, '2025-12-08 07:55:50', '2025-12-08 07:55:50'),
	(3, 'Barista Watu', 'barista@watu.com', 'barista', NULL, '$2y$12$LzuG0CUX9ncVf7BHe5qelOfvHt9dzxNdoPS2qLhPjCeNxQn.tKHcq', NULL, '2025-12-08 07:55:51', '2025-12-08 07:55:51'),
	(4, 'Roaster Watu', 'roaster@watu.com', 'roaster', NULL, '$2y$12$l35CLoJLF4S9hGPJRbQWOeg.ZccnMholnrTqqcyVZJ6XwRf3xNJZC', NULL, '2025-12-08 07:55:51', '2025-12-08 07:55:51');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
