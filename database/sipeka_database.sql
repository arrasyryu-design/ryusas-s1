-- SIPEKA — skema database + data awal (impor lewat phpMyAdmin)
-- Gunakan pada database BARU yang kosong (mis. bernama "sipeka"), collation utf8mb4_unicode_ci.
-- Jangan dikombinasikan dengan "php artisan migrate" pada database yang sama.
-- Login admin awal: admin@sman1nusantara.sch.id / Admin12345  (SEGERA ganti setelah login)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_code` varchar(30) NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `reporter_name` varchar(100) DEFAULT NULL,
  `reporter_class` varchar(50) DEFAULT NULL,
  `reporter_contact` varchar(100) DEFAULT NULL,
  `location` varchar(200) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `priority` varchar(20) NOT NULL DEFAULT 'sedang',
  `status` varchar(30) NOT NULL DEFAULT 'menunggu',
  `verified_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reports_report_code_unique` (`report_code`),
  KEY `reports_status_index` (`status`),
  KEY `reports_verified_at_index` (`verified_at`),
  CONSTRAINT `reports_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `report_updates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `old_status` varchar(30) DEFAULT NULL,
  `new_status` varchar(30) NOT NULL,
  `note` text,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `report_updates_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  CONSTRAINT `report_updates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `report_attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(200) NOT NULL,
  `attachment_type` varchar(20) NOT NULL DEFAULT 'before',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `report_attachments_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  CONSTRAINT `report_attachments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `subject_type` varchar(100) DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `identity_number` varchar(30) NOT NULL,
  `class_or_position` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_identity_number_unique` (`identity_number`),
  UNIQUE KEY `members_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------- DATA AWAL ----------
INSERT INTO `categories` (`name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
('Elektronik', 'elektronik', 'Lampu, stop kontak, kipas angin, proyektor, komputer, dan perangkat elektronik lain', 1, NOW(), NOW()),
('Furnitur', 'furnitur', 'Meja, kursi, papan tulis, lemari, dan perabot kelas', 1, NOW(), NOW()),
('Bangunan', 'bangunan', 'Dinding, atap, plafon, pintu, jendela, dan lantai', 1, NOW(), NOW()),
('Sanitasi', 'sanitasi', 'Toilet, keran air, wastafel, dan saluran pembuangan', 1, NOW(), NOW()),
('Infrastruktur', 'infrastruktur', 'Lapangan, selasar, pagar, jalan, dan fasilitas umum sekolah', 1, NOW(), NOW()),
('Lainnya', 'lainnya', 'Kerusakan fasilitas yang tidak termasuk kategori lain', 1, NOW(), NOW());

INSERT INTO `users` (`name`, `email`, `password`, `is_active`, `created_at`, `updated_at`) VALUES
('Admin Sarpras', 'admin@sman1nusantara.sch.id', '$2y$12$Qa6k8JzyvVEl7V2NH.ZIFOcg9O8sCImZl1V.ADT0WTwOOqR7BT5F2', 1, NOW(), NOW());
