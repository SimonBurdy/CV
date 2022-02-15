/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `addressable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `addressable_id` int NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `commentable_id` int unsigned NOT NULL,
  `commentable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `experiences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `notes` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `invoice_rows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `article_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `article_id` int unsigned NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `unity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'day(s)',
  `vat_rate` decimal(8,2) NOT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `discount_euro` decimal(8,2) DEFAULT NULL,
  `discount_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sell_total` decimal(8,2) NOT NULL,
  `order` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_rows_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `invoice_rows_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned DEFAULT NULL,
  `address_id` bigint unsigned DEFAULT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creation_date` date NOT NULL,
  `validity_date` date NOT NULL,
  `notes` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_euro` decimal(8,2) DEFAULT NULL,
  `discount_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sell_total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sell_total_ttc` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_project_id_foreign` (`project_id`),
  KEY `invoices_address_id_foreign` (`address_id`),
  CONSTRAINT `invoices_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`),
  CONSTRAINT `invoices_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `project_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `quote_rows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quote_id` bigint unsigned NOT NULL,
  `article_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `article_id` int unsigned NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `unity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'day(s)',
  `vat_rate` decimal(8,2) NOT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `discount_euro` decimal(8,2) DEFAULT NULL,
  `discount_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sell_total` decimal(8,2) NOT NULL,
  `order` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quote_rows_quote_id_foreign` (`quote_id`),
  CONSTRAINT `quote_rows_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `quotes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned DEFAULT NULL,
  `address_id` bigint unsigned DEFAULT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creation_date` date NOT NULL,
  `validity_date` date NOT NULL,
  `notes` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_euro` decimal(8,2) DEFAULT NULL,
  `discount_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sell_total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sell_total_ttc` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotes_project_id_foreign` (`project_id`),
  KEY `quotes_address_id_foreign` (`address_id`),
  CONSTRAINT `quotes_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`),
  CONSTRAINT `quotes_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `supplies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_rate` decimal(8,2) NOT NULL,
  `total_supply` decimal(8,2) NOT NULL,
  `total_supply_net` decimal(8,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplies_project_id_foreign` (`project_id`),
  CONSTRAINT `supplies_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `addresses` (`id`, `addressable_type`, `addressable_id`, `is_main`, `name`, `address`, `created_at`, `updated_at`, `phone`) VALUES
(1, 'App\\Models\\Client', 1, 1, 'THALES', '13 Rue De Saint Omer\r\n59300\r\nLille', '2022-02-15 21:02:46', '2022-02-15 21:02:46', '0646515826');
INSERT INTO `addresses` (`id`, `addressable_type`, `addressable_id`, `is_main`, `name`, `address`, `created_at`, `updated_at`, `phone`) VALUES
(2, 'App\\Models\\Client', 1, 0, 'THALES ROUEN', '4 rue Michel Servet\r\n76000\r\nRouen', '2022-02-15 21:03:01', '2022-02-15 21:03:18', '0646515826');
INSERT INTO `addresses` (`id`, `addressable_type`, `addressable_id`, `is_main`, `name`, `address`, `created_at`, `updated_at`, `phone`) VALUES
(3, 'App\\Models\\Client', 2, 1, 'POLE EMPLOI', '13 Rue De Saint Omer\r\n85000\r\nReins', '2022-02-15 21:04:34', '2022-02-15 21:04:34', '0646515826');
INSERT INTO `addresses` (`id`, `addressable_type`, `addressable_id`, `is_main`, `name`, `address`, `created_at`, `updated_at`, `phone`) VALUES
(4, 'App\\Models\\Client', 2, 0, 'POLE EMPLOI', '4 rue Michel Servet\r\n75000\r\nMarseille', '2022-02-15 21:04:58', '2022-02-15 21:04:58', '0646515826'),
(5, 'App\\Models\\Client', 3, 1, 'AIR LIQUIDE', '13 Rue De Saint Omer\r\n75000\r\nLille', '2022-02-15 21:06:11', '2022-02-15 21:06:11', '0646515826'),
(6, 'App\\Models\\Client', 3, 0, 'AIR LIQUIDE', '13 Rue De Saint Omer\r\nqsdqsd', '2022-02-15 22:15:12', '2022-02-15 22:15:12', '0646515826');

INSERT INTO `clients` (`id`, `name`, `siret`, `logo`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'THALES', '123545785968944', 'ea192339403024de6ba8a4625fcfb631.jpg', NULL, '2022-02-15 21:01:55', '2022-02-15 21:01:55');
INSERT INTO `clients` (`id`, `name`, `siret`, `logo`, `notes`, `created_at`, `updated_at`) VALUES
(2, 'POLE EMPLOI', '15645121646456', '1b2ae45a4cf498e8c7921f91482bf892.jpg', NULL, '2022-02-15 21:03:50', '2022-02-15 21:03:50');
INSERT INTO `clients` (`id`, `name`, `siret`, `logo`, `notes`, `created_at`, `updated_at`) VALUES
(3, 'AIR LIQUIDE', '457452342345354', 'c8de2c1828b33b5eb07d754bebd78299.jpg', '<p>fsdfsdfdsf</p>', '2022-02-15 21:05:32', '2022-02-15 21:05:32');
INSERT INTO `clients` (`id`, `name`, `siret`, `logo`, `notes`, `created_at`, `updated_at`) VALUES
(4, 'Client 1', '454544556464', '51e06c2d4b30fdc2dde132cbaca8479f.jpg', NULL, '2022-02-15 22:04:59', '2022-02-15 22:04:59'),
(5, 'AIR lique 2', '45484648', 'db424aae86563b02867fefd6b5f3da0a.jpg', '<p><span style=\"color: rgb(94, 87, 55); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;\">Novitates autem si spem adferunt, ut tamquam in herbis non fallacibus fructus appareat, non sunt illae quidem repudiandae, vetustas tamen suo loco conservanda; maxima</span><br></p>', '2022-02-15 22:14:37', '2022-02-15 22:14:37');



INSERT INTO `experiences` (`id`, `location`, `name`, `from`, `to`, `notes`, `created_at`, `updated_at`, `logo`) VALUES
(1, 'Sante BTP', 'Stage en Creation  de site Web  et bases de donnees', '2022-03-10', '2022-02-24', 'Novitates autem si spem adferunt, ut tamquam in herbis non fallacibus fructus appareat, non sunt illae quidem repudiandae, vetustas tamen suo loco conservanda; maxima', '2022-02-15 21:38:36', '2022-02-15 21:38:36', 'daba1caf937362ca5af72593d4ba5bd0.jpg');
INSERT INTO `experiences` (`id`, `location`, `name`, `from`, `to`, `notes`, `created_at`, `updated_at`, `logo`) VALUES
(2, 'Weezea', 'Stage Devellopement', '2022-02-01', '2022-02-03', 'Novitates autem si spem adferunt, ut tamquam in herbis non fallacibus fructus appareat, non sunt illae quidem repudiandae, vetustas tamen suo loco conservanda; maxima', '2022-02-15 21:39:01', '2022-02-15 21:39:01', '211dba52008873b4ff7fa57ca5de4abf.jpg');
INSERT INTO `experiences` (`id`, `location`, `name`, `from`, `to`, `notes`, `created_at`, `updated_at`, `logo`) VALUES
(3, 'Weezea', 'CDD', '2022-02-06', '2022-02-14', 'Novitates autem si spem adferunt, ut tamquam in herbis non fallacibus fructus appareat, non sunt illae quidem repudiandae, vetustas tamen suo loco conservanda; maxima', '2022-02-15 21:39:28', '2022-02-15 21:39:28', 'ab4f00aa7aef091a0d3b6b6bec74bbda.jpg');
INSERT INTO `experiences` (`id`, `location`, `name`, `from`, `to`, `notes`, `created_at`, `updated_at`, `logo`) VALUES
(4, 'Expe', 'Licence Science Du Numerique test', '2022-02-27', '2022-02-28', 'Novitates autem si spem adferunt, ut tamquam in herbis non fallacibus fructus appareat, non sunt illae quidem repudiandae, vetustas tamen suo loco conservanda; maxima', '2022-02-15 22:06:16', '2022-02-15 22:06:45', '0367a38c1a1557527cb655f4f8e3ac4c.jpg');



INSERT INTO `invoice_rows` (`id`, `invoice_id`, `article_type`, `article_id`, `quantity`, `unity`, `vat_rate`, `unit_price`, `discount_euro`, `discount_unit`, `description`, `sell_total`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Service', 1, 5.00, 'day(s)', 20.00, 300.00, 0.00, 'euros', 'SRV1 Dévellopement', 1500.00, 0, '2022-02-15 21:24:23', '2022-02-15 21:24:23');
INSERT INTO `invoice_rows` (`id`, `invoice_id`, `article_type`, `article_id`, `quantity`, `unity`, `vat_rate`, `unit_price`, `discount_euro`, `discount_unit`, `description`, `sell_total`, `order`, `created_at`, `updated_at`) VALUES
(2, 1, 'App\\Models\\Service', 2, 1.00, 'year(s)', 20.00, 600.00, 10.00, 'euros', 'SRV2 Hébergement', 590.00, 1, '2022-02-15 21:24:23', '2022-02-15 21:24:23');
INSERT INTO `invoice_rows` (`id`, `invoice_id`, `article_type`, `article_id`, `quantity`, `unity`, `vat_rate`, `unit_price`, `discount_euro`, `discount_unit`, `description`, `sell_total`, `order`, `created_at`, `updated_at`) VALUES
(3, 1, 'App\\Models\\Service', 5, 5.00, 'hour(s)', 20.00, 40.00, 0.00, 'euros', 'SRV5 DevOps', 200.00, 2, '2022-02-15 21:24:23', '2022-02-15 21:24:23');
INSERT INTO `invoice_rows` (`id`, `invoice_id`, `article_type`, `article_id`, `quantity`, `unity`, `vat_rate`, `unit_price`, `discount_euro`, `discount_unit`, `description`, `sell_total`, `order`, `created_at`, `updated_at`) VALUES
(4, 2, 'App\\Models\\Service', 1, 5.00, 'day(s)', 20.00, 300.00, 0.00, 'euros', 'SRV1 Dévellopement', 1500.00, 0, '2022-02-15 21:26:24', '2022-02-15 21:26:24'),
(5, 2, 'App\\Models\\Service', 2, 1.00, 'year(s)', 20.00, 600.00, 10.00, 'euros', 'SRV2 Hébergement', 590.00, 1, '2022-02-15 21:26:24', '2022-02-15 21:26:24'),
(6, 2, 'App\\Models\\Service', 5, 5.00, 'hour(s)', 20.00, 40.00, 0.00, 'euros', 'SRV5 DevOps', 200.00, 2, '2022-02-15 21:26:24', '2022-02-15 21:26:24'),
(7, 3, 'App\\Models\\Service', 1, 5.00, 'day(s)', 20.00, 300.00, 0.00, 'euros', 'SRV1 Dévellopement', 1500.00, 0, '2022-02-15 21:26:43', '2022-02-15 21:26:43'),
(8, 3, 'App\\Models\\Service', 2, 1.00, 'year(s)', 20.00, 600.00, 10.00, 'euros', 'SRV2 Hébergement', 590.00, 1, '2022-02-15 21:26:43', '2022-02-15 21:26:43'),
(9, 3, 'App\\Models\\Service', 5, 5.00, 'hour(s)', 20.00, 40.00, 0.00, 'euros', 'SRV5 DevOps', 200.00, 2, '2022-02-15 21:26:43', '2022-02-15 21:26:43'),
(10, 4, 'App\\Models\\Service', 1, 5.00, 'day(s)', 20.00, 300.00, 0.00, 'euros', 'SRV1 Dévellopement', 1500.00, 0, '2022-02-15 21:27:44', '2022-02-15 21:27:44'),
(11, 4, 'App\\Models\\Service', 2, 1.00, 'year(s)', 20.00, 600.00, 10.00, 'euros', 'SRV2 Hébergement', 590.00, 1, '2022-02-15 21:27:44', '2022-02-15 21:27:44'),
(12, 4, 'App\\Models\\Service', 5, 5.00, 'hour(s)', 20.00, 40.00, 0.00, 'euros', 'SRV5 DevOps', 200.00, 2, '2022-02-15 21:27:44', '2022-02-15 21:27:44'),
(13, 5, 'App\\Models\\Service', 1, 5.00, 'day(s)', 20.00, 300.00, 0.00, 'euros', 'SRV1 Dévellopement', 1500.00, 0, '2022-02-15 21:27:45', '2022-02-15 21:27:45'),
(14, 5, 'App\\Models\\Service', 2, 1.00, 'year(s)', 20.00, 600.00, 10.00, 'euros', 'SRV2 Hébergement', 590.00, 1, '2022-02-15 21:27:45', '2022-02-15 21:27:45'),
(15, 5, 'App\\Models\\Service', 5, 5.00, 'hour(s)', 20.00, 40.00, 0.00, 'euros', 'SRV5 DevOps', 200.00, 2, '2022-02-15 21:27:45', '2022-02-15 21:27:45'),
(16, 6, 'App\\Models\\Service', 1, 5.00, 'day(s)', 20.00, 300.00, 0.00, 'euros', 'SRV1 Dévellopement', 1500.00, 0, '2022-02-15 21:28:00', '2022-02-15 21:28:00'),
(17, 6, 'App\\Models\\Service', 2, 1.00, 'year(s)', 20.00, 600.00, 10.00, 'euros', 'SRV2 Hébergement', 590.00, 1, '2022-02-15 21:28:00', '2022-02-15 21:28:00'),
(18, 6, 'App\\Models\\Service', 5, 5.00, 'hour(s)', 20.00, 40.00, 0.00, 'euros', 'SRV5 DevOps', 200.00, 2, '2022-02-15 21:28:00', '2022-02-15 21:28:00'),
(19, 7, 'App\\Models\\Service', 1, 5.00, 'day(s)', 20.00, 300.00, 0.00, 'euros', 'SRV1 Dévellopement', 1500.00, 0, '2022-02-15 21:28:20', '2022-02-15 21:28:20'),
(20, 7, 'App\\Models\\Service', 2, 1.00, 'year(s)', 20.00, 600.00, 10.00, 'euros', 'SRV2 Hébergement', 590.00, 1, '2022-02-15 21:28:20', '2022-02-15 21:28:20'),
(21, 7, 'App\\Models\\Service', 5, 5.00, 'hour(s)', 20.00, 40.00, 0.00, 'euros', 'SRV5 DevOps', 200.00, 2, '2022-02-15 21:28:20', '2022-02-15 21:28:20'),
(22, 8, 'App\\Models\\Service', 1, 5.00, 'day(s)', 20.00, 300.00, 0.00, 'euros', 'SRV1 Dévellopement', 1500.00, 0, '2022-02-15 21:28:23', '2022-02-15 21:28:23'),
(23, 8, 'App\\Models\\Service', 2, 1.00, 'year(s)', 20.00, 600.00, 10.00, 'euros', 'SRV2 Hébergement', 590.00, 1, '2022-02-15 21:28:23', '2022-02-15 21:28:23'),
(24, 8, 'App\\Models\\Service', 5, 5.00, 'hour(s)', 20.00, 40.00, 0.00, 'euros', 'SRV5 DevOps', 200.00, 2, '2022-02-15 21:28:23', '2022-02-15 21:28:23'),
(25, 9, 'App\\Models\\Service', 1, 5.00, 'day(s)', 20.00, 300.00, 0.00, 'euros', 'SRV1 Dévellopement', 1500.00, 0, '2022-02-15 21:28:24', '2022-02-15 21:28:24'),
(26, 9, 'App\\Models\\Service', 2, 1.00, 'year(s)', 20.00, 600.00, 10.00, 'euros', 'SRV2 Hébergement', 590.00, 1, '2022-02-15 21:28:24', '2022-02-15 21:28:24'),
(27, 9, 'App\\Models\\Service', 5, 5.00, 'hour(s)', 20.00, 40.00, 0.00, 'euros', 'SRV5 DevOps', 200.00, 2, '2022-02-15 21:28:25', '2022-02-15 21:28:25'),
(28, 10, 'App\\Models\\Service', 1, 3.00, 'day(s)', 20.00, 200.00, 60.00, 'pc', 'SRV1 Dévellopement', 540.00, 0, '2022-02-15 22:02:43', '2022-02-15 22:02:43'),
(29, 11, 'App\\Models\\Service', 1, 2.00, 'day(s)', 20.00, 200.00, 0.00, 'euros', 'SRV1 Dévellopement', 400.00, 0, '2022-02-15 22:12:26', '2022-02-15 22:12:26');

INSERT INTO `invoices` (`id`, `project_id`, `address_id`, `number`, `status`, `creation_date`, `validity_date`, `notes`, `discount_euro`, `discount_unit`, `sell_total`, `created_at`, `updated_at`, `sell_total_ttc`) VALUES
(1, 1, 2, NULL, 'draft', '2022-02-15', '2022-03-15', NULL, 0.00, 'euros', 2290.00, '2022-02-15 21:24:23', '2022-02-15 21:24:23', 2748.00);
INSERT INTO `invoices` (`id`, `project_id`, `address_id`, `number`, `status`, `creation_date`, `validity_date`, `notes`, `discount_euro`, `discount_unit`, `sell_total`, `created_at`, `updated_at`, `sell_total_ttc`) VALUES
(2, 1, 2, NULL, 'draft', '2022-02-15', '2022-03-15', NULL, 0.00, 'euros', 2290.00, '2022-02-15 21:26:24', '2022-02-15 21:26:24', 2748.00);
INSERT INTO `invoices` (`id`, `project_id`, `address_id`, `number`, `status`, `creation_date`, `validity_date`, `notes`, `discount_euro`, `discount_unit`, `sell_total`, `created_at`, `updated_at`, `sell_total_ttc`) VALUES
(3, 1, 2, '2022003', 'waiting for payment', '2022-02-15', '2022-03-15', NULL, 0.00, 'euros', 2290.00, '2022-02-15 21:26:43', '2022-02-15 21:30:45', 2748.00);
INSERT INTO `invoices` (`id`, `project_id`, `address_id`, `number`, `status`, `creation_date`, `validity_date`, `notes`, `discount_euro`, `discount_unit`, `sell_total`, `created_at`, `updated_at`, `sell_total_ttc`) VALUES
(4, 2, 3, '2022002', 'paid', '2022-02-15', '2022-03-15', NULL, 0.00, 'euros', 2290.00, '2022-02-15 21:27:44', '2022-02-15 21:29:07', 2748.00),
(5, 2, 3, NULL, 'draft', '2022-02-15', '2022-03-15', NULL, 0.00, 'euros', 2290.00, '2022-02-15 21:27:45', '2022-02-15 21:27:45', 2748.00),
(6, 2, 3, '2022005', 'unpaid invoice', '2022-02-15', '2022-03-15', NULL, 0.00, 'euros', 2290.00, '2022-02-15 21:28:00', '2022-02-15 22:17:00', 2748.00),
(7, 3, 5, NULL, 'draft', '2022-02-15', '2022-03-15', NULL, 0.00, 'euros', 2290.00, '2022-02-15 21:28:20', '2022-02-15 21:28:20', 2748.00),
(8, 3, 5, NULL, 'draft', '2022-02-15', '2022-03-15', NULL, 0.00, 'euros', 2290.00, '2022-02-15 21:28:23', '2022-02-15 21:28:23', 2748.00),
(9, 3, 5, '2022001', 'paid', '2022-02-15', '2022-03-15', NULL, 0.00, 'euros', 2290.00, '2022-02-15 21:28:24', '2022-02-15 21:28:53', 2748.00),
(10, 6, 5, '2022004', 'waiting for payment', '2022-02-15', '2022-03-15', NULL, 0.00, 'euros', 540.00, '2022-02-15 22:02:43', '2022-02-15 22:03:19', 648.00),
(11, 1, 1, NULL, 'draft', '2022-02-15', '2022-03-15', NULL, 0.00, 'euros', 400.00, '2022-02-15 22:12:26', '2022-02-15 22:12:26', 480.00);

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(4, '2022_02_10_085627_create_projects_table', 2),
(5, '2022_02_10_090807_create_clients_table', 3),
(7, '2022_02_10_091723_create_experiences_table', 3),
(8, '2022_02_10_161831_create_addresses_table', 4),
(9, '2022_02_10_161832_create_quotes_table', 5),
(10, '2022_02_10_161945_create_invoices_table', 5),
(11, '2022_02_10_215253_create_services_table', 6),
(12, '2022_02_10_223118_create_comments_table', 7),
(14, '2022_02_10_161946_create_invoice_rows_table', 8),
(15, '2022_02_10_223119_create_supplies_table', 9),
(16, '2022_02_10_161833_create_quote_rows_table', 10),
(17, '2022_02_10_091233_create_formations_table', 11);



INSERT INTO `projects` (`id`, `client_id`, `project_number`, `description`, `title`, `created_at`, `updated_at`, `state`) VALUES
(1, 1, '1', 'Novitates autem si spem adferunt, ut tamquam in herbis non fallacibus fructus appareat, non sunt illae quidem repudiandae, vetustas tamen suo loco conservanda; maxima est enim vis vetustatis et consuetudinis. Quin in ipso equo,', 'Projet Dev API', '2022-02-15 21:07:04', '2022-02-15 21:24:24', 'in progress');
INSERT INTO `projects` (`id`, `client_id`, `project_number`, `description`, `title`, `created_at`, `updated_at`, `state`) VALUES
(2, 2, '2', 'Novitates autem si spem adferunt, ut tamquam in herbis non fallacibus fructus appareat, non sunt illae quidem repudiandae, vetustas tamen suo loco conservanda; maxima est enim vis vetustatis et consuetudinis. Quin in ipso equo,', 'Projet Hébergement', '2022-02-15 21:07:04', '2022-02-15 21:30:14', 'paid');
INSERT INTO `projects` (`id`, `client_id`, `project_number`, `description`, `title`, `created_at`, `updated_at`, `state`) VALUES
(3, 3, '3', 'Novitates autem si spem adferunt, ut tamquam in herbis non fallacibus fructus appareat, non sunt illae quidem repudiandae, vetustas tamen suo loco conservanda; maxima est enim vis vetustatis et consuetudinis. Quin in ipso equo,', 'Projet Dev Mobile', '2022-02-15 21:07:04', '2022-02-15 21:29:40', 'paid');
INSERT INTO `projects` (`id`, `client_id`, `project_number`, `description`, `title`, `created_at`, `updated_at`, `state`) VALUES
(4, 1, '4', 'Novitates autem si spem adferunt, ut tamquam in herbis non fallacibus fructus appareat, non sunt illae quidem repudiandae, vetustas tamen suo loco conservanda; maxima est enim vis vetustatis et consuetudinis. Quin in ipso equo,', 'Projet  VueJs', '2022-02-15 21:07:04', '2022-02-15 21:07:04', 'in progress'),
(5, 2, '5', 'Novitates autem si spem adferunt, ut tamquam in herbis non fallacibus fructus appareat, non sunt illae quidem repudiandae, vetustas tamen suo loco conservanda; maxima est enim vis vetustatis et consuetudinis. Quin in ipso equo,', 'Projet  React', '2022-02-15 21:07:04', '2022-02-15 21:07:04', 'in progress'),
(6, 3, '2', 'dfg fdgdf gfdgdfgdf gfdgfd hgfuidhg dfuhgf', 'Projet Test', '2022-02-15 22:00:58', '2022-02-15 22:00:58', 'in progress');

INSERT INTO `quote_rows` (`id`, `quote_id`, `article_type`, `article_id`, `quantity`, `unity`, `vat_rate`, `unit_price`, `discount_euro`, `discount_unit`, `description`, `sell_total`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Service', 1, 5.00, 'day(s)', 20.00, 300.00, 0.00, 'euros', 'SRV1 Dévellopement', 1500.00, 0, '2022-02-15 21:17:50', '2022-02-15 21:17:50');
INSERT INTO `quote_rows` (`id`, `quote_id`, `article_type`, `article_id`, `quantity`, `unity`, `vat_rate`, `unit_price`, `discount_euro`, `discount_unit`, `description`, `sell_total`, `order`, `created_at`, `updated_at`) VALUES
(2, 1, 'App\\Models\\Service', 2, 1.00, 'year(s)', 20.00, 600.00, 10.00, 'euros', 'SRV2 Hébergement', 590.00, 1, '2022-02-15 21:17:50', '2022-02-15 21:17:50');
INSERT INTO `quote_rows` (`id`, `quote_id`, `article_type`, `article_id`, `quantity`, `unity`, `vat_rate`, `unit_price`, `discount_euro`, `discount_unit`, `description`, `sell_total`, `order`, `created_at`, `updated_at`) VALUES
(3, 1, 'App\\Models\\Service', 5, 5.00, 'hour(s)', 20.00, 40.00, 0.00, 'euros', 'SRV5 DevOps', 200.00, 2, '2022-02-15 21:17:50', '2022-02-15 21:17:50');
INSERT INTO `quote_rows` (`id`, `quote_id`, `article_type`, `article_id`, `quantity`, `unity`, `vat_rate`, `unit_price`, `discount_euro`, `discount_unit`, `description`, `sell_total`, `order`, `created_at`, `updated_at`) VALUES
(4, 2, 'App\\Models\\Service', 1, 5.00, 'day(s)', 20.00, 300.00, 0.00, 'euros', 'SRV1 Dévellopement', 1500.00, 0, '2022-02-15 21:17:50', '2022-02-15 21:17:50'),
(5, 2, 'App\\Models\\Service', 2, 1.00, 'year(s)', 20.00, 600.00, 10.00, 'euros', 'SRV2 Hébergement', 590.00, 1, '2022-02-15 21:17:50', '2022-02-15 21:17:50'),
(6, 2, 'App\\Models\\Service', 5, 5.00, 'hour(s)', 20.00, 40.00, 0.00, 'euros', 'SRV5 DevOps', 200.00, 2, '2022-02-15 21:17:50', '2022-02-15 21:17:50'),
(7, 3, 'App\\Models\\Service', 1, 5.00, 'day(s)', 20.00, 300.00, 0.00, 'euros', 'SRV1 Dévellopement', 1500.00, 0, '2022-02-15 21:17:50', '2022-02-15 21:17:50'),
(8, 3, 'App\\Models\\Service', 2, 1.00, 'year(s)', 20.00, 600.00, 10.00, 'euros', 'SRV2 Hébergement', 590.00, 1, '2022-02-15 21:17:50', '2022-02-15 21:17:50'),
(9, 3, 'App\\Models\\Service', 5, 5.00, 'hour(s)', 20.00, 40.00, 0.00, 'euros', 'SRV5 DevOps', 200.00, 2, '2022-02-15 21:17:50', '2022-02-15 21:17:50'),
(10, 4, 'App\\Models\\Service', 1, 3.00, 'day(s)', 20.00, 200.00, 60.00, 'pc', 'SRV1 Dévellopement', 540.00, 0, '2022-02-15 22:02:11', '2022-02-15 22:02:11'),
(11, 5, 'App\\Models\\Service', 1, 2.00, 'day(s)', 20.00, 200.00, 0.00, 'euros', 'SRV1 Dévellopement', 400.00, 0, '2022-02-15 22:11:57', '2022-02-15 22:11:57');

INSERT INTO `quotes` (`id`, `project_id`, `address_id`, `number`, `status`, `creation_date`, `validity_date`, `notes`, `discount_euro`, `discount_unit`, `sell_total`, `created_at`, `updated_at`, `sell_total_ttc`) VALUES
(1, 1, 2, 'D22-001', 'draft', '2022-02-15', '2022-04-15', NULL, 0.00, 'euros', 2290.00, '2022-02-15 21:17:50', '2022-02-15 21:17:50', 2748.00);
INSERT INTO `quotes` (`id`, `project_id`, `address_id`, `number`, `status`, `creation_date`, `validity_date`, `notes`, `discount_euro`, `discount_unit`, `sell_total`, `created_at`, `updated_at`, `sell_total_ttc`) VALUES
(2, 2, 3, 'D22-002', 'draft', '2022-02-15', '2022-04-15', NULL, 0.00, 'euros', 2290.00, '2022-02-15 21:17:50', '2022-02-15 21:17:50', 2748.00);
INSERT INTO `quotes` (`id`, `project_id`, `address_id`, `number`, `status`, `creation_date`, `validity_date`, `notes`, `discount_euro`, `discount_unit`, `sell_total`, `created_at`, `updated_at`, `sell_total_ttc`) VALUES
(3, 3, 5, 'D22-003', 'draft', '2022-02-15', '2022-04-15', NULL, 0.00, 'euros', 2290.00, '2022-02-15 21:17:50', '2022-02-15 21:17:50', 2748.00);
INSERT INTO `quotes` (`id`, `project_id`, `address_id`, `number`, `status`, `creation_date`, `validity_date`, `notes`, `discount_euro`, `discount_unit`, `sell_total`, `created_at`, `updated_at`, `sell_total_ttc`) VALUES
(4, 6, 5, 'D22-004', 'draft', '2022-02-15', '2022-04-15', NULL, 0.00, 'euros', 540.00, '2022-02-15 22:02:11', '2022-02-15 22:02:11', 648.00),
(5, 1, 1, 'D22-005', 'draft', '2022-02-15', '2022-04-15', NULL, 0.00, 'euros', 400.00, '2022-02-15 22:11:57', '2022-02-15 22:11:57', 480.00);

INSERT INTO `services` (`id`, `ref`, `desc`, `notes`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'SRV1', 'Dévellopement', NULL, NULL, '2022-02-15 21:14:01', '2022-02-15 21:14:01');
INSERT INTO `services` (`id`, `ref`, `desc`, `notes`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 'SRV2', 'Hébergement', NULL, NULL, '2022-02-15 21:14:01', '2022-02-15 21:14:01');
INSERT INTO `services` (`id`, `ref`, `desc`, `notes`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, 'SRV3', 'Entretien de conseil', NULL, NULL, '2022-02-15 21:14:01', '2022-02-15 21:14:01');
INSERT INTO `services` (`id`, `ref`, `desc`, `notes`, `deleted_at`, `created_at`, `updated_at`) VALUES
(4, 'SRV4', 'Audit de sécurité', NULL, NULL, '2022-02-15 21:14:01', '2022-02-15 21:14:01'),
(5, 'SRV5', 'DevOps', NULL, NULL, '2022-02-15 21:14:01', '2022-02-15 21:14:01');

INSERT INTO `supplies` (`id`, `project_id`, `supplier`, `number`, `vat_rate`, `total_supply`, `total_supply_net`, `status`, `path`, `created_at`, `updated_at`) VALUES
(1, 3, 'ORPEA', '25845575', 20.00, 300.00, 360.00, 'to be paid upon receipt', NULL, '2022-02-15 21:40:24', '2022-02-15 21:40:24');
INSERT INTO `supplies` (`id`, `project_id`, `supplier`, `number`, `vat_rate`, `total_supply`, `total_supply_net`, `status`, `path`, `created_at`, `updated_at`) VALUES
(2, 6, 'OVH', NULL, 20.00, 200.00, 240.00, 'to be paid when due', NULL, '2022-02-15 22:04:08', '2022-02-15 22:04:08');
INSERT INTO `supplies` (`id`, `project_id`, `supplier`, `number`, `vat_rate`, `total_supply`, `total_supply_net`, `status`, `path`, `created_at`, `updated_at`) VALUES
(3, 2, 'OVH', NULL, 20.00, 200.00, 240.00, 'to be paid when due', NULL, '2022-02-15 22:13:40', '2022-02-15 22:13:40');

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'BURDY', 'admin@lacatholille.fr', NULL, '$2y$10$Z30HJMIiJI6aVypbcro.7eDZxD8cVQwMOW/ZTeX8qQWXDTNDgO.Y2', NULL, '2022-02-15 20:58:49', '2022-02-15 20:58:49');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;