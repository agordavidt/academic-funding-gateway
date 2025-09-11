-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 10, 2025 at 12:18 AM
-- Server version: 8.4.3
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `afg`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@afc.com', NULL, '$2y$12$79tAKLUCkstveB3LIEFr8OcKCT7wKA4jQi3SocXbsxebn7o0IQzke', 'super_admin', 1, NULL, '2025-09-09 09:53:16', '2025-09-09 09:53:16'),
(2, 'Admin User', 'user@afc.com', NULL, '$2y$12$hoQeuMMT3RxDvB0brGFIDuaGcLWeXXHSXVRA2fsnB/MRPNerqoY22', 'admin', 1, NULL, '2025-09-09 09:53:16', '2025-09-09 09:53:16');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `need_assessment_text` text COLLATE utf8mb4_unicode_ci,
  `terms_agreed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `need_assessment_text`, `terms_agreed_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'I really need this money. I will make it big in THIS ABUJA. God is got me.', '2025-09-09 10:11:04', '2025-09-09 10:10:45', '2025-09-09 10:11:04');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_08_29_150751_create_training_institutions_table', 1),
(6, '2025_08_29_150804_create_applications_table', 1),
(7, '2025_08_29_150815_create_payments_table', 1),
(8, '2025_08_29_150831_create_notifications_table', 1),
(9, '2025_08_29_150842_create_jobs_table', 1),
(10, '2025_08_29_150900_create_sessions_table', 1),
(11, '2025_08_30_152741_create_admins_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` enum('email','sms') COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','sent','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','submitted','success','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `gateway_response` json DEFAULT NULL,
  `payment_evidence` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `transaction_id`, `amount`, `status`, `paid_at`, `gateway_response`, `payment_evidence`, `payment_note`, `created_at`, `updated_at`) VALUES
(1, 2, 'TXN_1757416246_2', 3000.00, 'success', '2025-09-09 10:13:54', '{\"file_type\": \"jpg\", \"approved_at\": \"2025-09-09T11:13:54.702433Z\", \"approved_by\": \"Super Admin\", \"uploaded_at\": \"2025-09-09T11:11:05.974311Z\", \"approval_note\": null, \"evidence_uploaded\": true}', 'payment_evidence/NGKGLHhflu6hH6iOV0jkU7TwmAzr9oS63rQEThTY.jpg', NULL, '2025-09-09 10:10:46', '2025-09-09 10:13:54');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_institutions`
--

CREATE TABLE `training_institutions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `training_institutions`
--

INSERT INTO `training_institutions` (`id`, `name`, `description`, `contact_email`, `created_at`, `updated_at`) VALUES
(1, 'Ottinic Business School', 'Leading technology training institute specializing in software development and digital skills.', 'admin@techhub.academy', '2025-09-09 09:53:16', '2025-09-09 09:53:16'),
(2, 'Ottinic Technology Hub', 'Professional development programs for business management and entrepreneurship.', 'info@businessleadership.edu.ng', '2025-09-09 09:53:16', '2025-09-09 09:53:16'),
(3, 'Digital Marketing Academy', 'Comprehensive digital marketing and e-commerce training programs.', 'contact@digitalmarketing.academy', '2025-09-09 09:53:16', '2025-09-09 09:53:16'),
(4, 'Healthcare Training Center', 'Specialized training programs for healthcare professionals and medical assistants.', 'training@healthcarecenter.ng', '2025-09-09 09:53:16', '2025-09-09 09:53:16'),
(5, 'Agricultural Innovation Hub', 'Modern agricultural techniques and agribusiness training programs.', 'info@agrihub.ng', '2025-09-09 09:53:16', '2025-09-09 09:53:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `school` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matriculation_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_stage` enum('imported','profile_completion','payment','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'imported',
  `payment_status` enum('pending','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `application_status` enum('pending','reviewing','accepted','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `phone_number`, `email`, `first_name`, `last_name`, `address`, `school`, `matriculation_number`, `registration_stage`, `payment_status`, `application_status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, '08033282698', 'davidagort@gmail.com', 'David', 'Agor', NULL, 'Ahmadu Bello University Zaria', 'UNILAG/2023/001', 'imported', 'pending', 'pending', NULL, '$2y$12$PjnReSfg9UC4W.vNlGx.qe0CTCrSBAJ8vQ313rMAILaWE0Klp/qJC', NULL, '2025-09-09 09:54:17', '2025-09-09 09:54:17'),
(2, '09018832244', 'christyarg@gmail.com', 'Christiana', 'Daniel', NULL, 'Nassara State University Lafia', 'UNN/2022/045', 'completed', 'paid', 'accepted', NULL, '$2y$12$Qv2EE2cwEClpSalV8OajHejbiHzZXeTef04GRZ5T0vG.7zHB/AjNG', NULL, '2025-09-09 09:54:17', '2025-09-09 10:16:11'),
(3, '08187654321', 'amina.bello@buk.edu.ng', 'Amina', 'Bello', NULL, 'Bayero University Kano', 'BUK/2021/212', 'imported', 'pending', 'pending', NULL, '$2y$12$OTjEK.XZXGz4MCIRCsoen.i4MLWPVBIwkldFLXn3u17ZfZdm75pj2', NULL, '2025-09-09 09:54:18', '2025-09-09 09:54:18'),
(4, '09012345678', 'tunde.balogun@ui.edu.ng', 'Tunde', 'Balogun', NULL, 'University of Ibadan', 'UI/2023/314', 'imported', 'pending', 'pending', NULL, '$2y$12$7C/jvHRHDm8YW67Blw.uoOai6Zf.tAMFywJepeE/ZP1YR.c/kuhZq', NULL, '2025-09-09 09:54:18', '2025-09-09 09:54:18'),
(5, '08023456789', 'chioma.eze@unn.edu.ng', 'Chioma', 'Eze', NULL, 'University of Nigeria Nsukka', 'UNN/2023/118', 'imported', 'pending', 'pending', NULL, '$2y$12$GFuht/IaVSXuctD7UstKPuXr.Xh3wMO88fKawNwzM8W14qmPa0ypW', NULL, '2025-09-09 09:54:19', '2025-09-09 09:54:19'),
(6, '09123456700', 'm.abdullahi@unimaid.edu.ng', 'Mohammed', 'Abdullahi', NULL, 'University of Maiduguri', 'UNIMAID/2024/099', 'imported', 'pending', 'pending', NULL, '$2y$12$wFD9V/CPPAFpBMjBYpt7/e25HiFGqiZUBt9IwCzZ54FmRuJh281.q', NULL, '2025-09-09 09:54:19', '2025-09-09 09:54:19'),
(7, '08011223344', 'folake.adeyemi@oauife.edu.ng', 'Folake', 'Adeyemi', NULL, 'Obafemi Awolowo University', 'OAU/2023/007', 'imported', 'pending', 'pending', NULL, '$2y$12$Od7hSUF9De/zsQsVrAPM.OR/PQkSPeBCG53Vl0uzIUuqNekMN/69O', NULL, '2025-09-09 09:54:20', '2025-09-09 09:54:20'),
(8, '08129876543', 'sani.usman@abu.edu.ng', 'Sani', 'Usman', NULL, 'Ahmadu Bello University', 'ABU/2022/056', 'imported', 'pending', 'pending', NULL, '$2y$12$EmqHp3PdarPeKuwLxCIttunuPUI5tKosbK/sJS9mobbmk6WNiSde2', NULL, '2025-09-09 09:54:21', '2025-09-09 09:54:21'),
(9, '09087654321', 'kelechi.nwosu@uniben.edu.ng', 'Kelechi', 'Nwosu', NULL, 'University of Benin', 'UNIBEN/2023/205', 'imported', 'pending', 'pending', NULL, '$2y$12$C4MLuw3N8mhpmueV1HQSDuZ.9B3wuZjnOb67Qf8kwLelczHp1cwt.', NULL, '2025-09-09 09:54:21', '2025-09-09 09:54:21'),
(10, '08099887766', 'blessing.umeh@lasu.edu.ng', 'Blessing', 'Umeh', NULL, 'Lagos State University', 'LASU/2023/032', 'imported', 'pending', 'pending', NULL, '$2y$12$7lA/r5JXq0YokorddDcSrugnLE50YapsGnkXz63md3wXrEtufKxY6', NULL, '2025-09-09 09:54:22', '2025-09-09 09:54:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applications_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training_institutions`
--
ALTER TABLE `training_institutions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_phone_number_unique` (`phone_number`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_institutions`
--
ALTER TABLE `training_institutions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
