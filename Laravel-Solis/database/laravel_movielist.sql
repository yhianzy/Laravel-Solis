-- ============================================================
-- MovieList Laravel App - Full SQL Setup (v2 with all fields)
-- Paste this entire file into phpMyAdmin > SQL tab
-- ============================================================

CREATE DATABASE IF NOT EXISTS `laravel_movielist`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `laravel_movielist`;

-- -------------------------------------------------------
-- Users table
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `address` VARCHAR(500) NULL DEFAULT NULL,
  `gender` VARCHAR(50) NULL DEFAULT NULL,
  `profile_picture` VARCHAR(255) NULL DEFAULT NULL,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Password reset tokens
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Sessions table
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` TEXT NULL DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sessions_user_id_index` (`user_id`),
  INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Cache table
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Jobs tables
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL DEFAULT NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL DEFAULT NULL,
  `cancelled_at` INT NULL DEFAULT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL UNIQUE,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Migrations tracking
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2024_01_01_000003_create_movies_table', 1),
('2024_01_01_000004_add_details_to_movies_table', 1);

-- -------------------------------------------------------
-- Movies table (with all new fields)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `movies` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `genre` VARCHAR(100) NOT NULL,
  `year` INT NOT NULL,
  `rating` DECIMAL(3,1) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `poster` VARCHAR(500) NULL DEFAULT NULL,
  `director` VARCHAR(255) NULL DEFAULT NULL,
  `cast` TEXT NULL DEFAULT NULL,
  `duration` INT NULL DEFAULT NULL,
  `language` VARCHAR(100) NOT NULL DEFAULT 'English',
  `status` ENUM('Watched','Unwatched','Watchlist') NOT NULL DEFAULT 'Unwatched',
  `is_favorite` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `movies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Sample data
-- Password for all users = "password123"
-- -------------------------------------------------------
INSERT INTO `users` (`name`, `email`, `password`, `created_at`, `updated_at`) VALUES
('Admin User', 'admin@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
('John Doe',   'john@example.com',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

INSERT INTO `movies` (`user_id`,`title`,`genre`,`year`,`rating`,`description`,`director`,`cast`,`duration`,`language`,`status`,`is_favorite`,`created_at`,`updated_at`) VALUES
(1,'The Dark Knight','Action',2008,9.0,'Batman faces the Joker in Gotham City.','Christopher Nolan','Christian Bale, Heath Ledger, Aaron Eckhart',152,'English','Watched',1,NOW(),NOW()),
(1,'Inception','Sci-Fi',2010,8.8,'A thief who steals corporate secrets through dreams.','Christopher Nolan','Leonardo DiCaprio, Joseph Gordon-Levitt',148,'English','Watched',1,NOW(),NOW()),
(1,'Interstellar','Sci-Fi',2014,8.6,'A team of explorers travel through a wormhole.','Christopher Nolan','Matthew McConaughey, Anne Hathaway',169,'English','Watched',0,NOW(),NOW()),
(1,'The Godfather','Drama',1972,9.2,'The aging patriarch of an organized crime dynasty.','Francis Ford Coppola','Marlon Brando, Al Pacino',175,'English','Watched',1,NOW(),NOW()),
(1,'Parasite','Drama',2019,8.5,'A poor family schemes to become employed by a wealthy family.','Bong Joon-ho','Song Kang-ho, Lee Sun-kyun',132,'Korean','Watchlist',0,NOW(),NOW()),
(2,'Avengers: Endgame','Action',2019,8.4,'The Avengers assemble once more.','Anthony Russo','Robert Downey Jr., Chris Evans',181,'English','Watched',1,NOW(),NOW()),
(2,'Your Name','Animation',2016,8.4,'Two strangers find themselves linked in a bizarre way.','Makoto Shinkai','Ryunosuke Kamiki, Mone Kamishiraishi',106,'Japanese','Watched',1,NOW(),NOW()),
(2,'The Matrix','Sci-Fi',1999,8.7,'A computer hacker learns about the true nature of reality.','The Wachowskis','Keanu Reeves, Laurence Fishburne',136,'English','Unwatched',0,NOW(),NOW());
