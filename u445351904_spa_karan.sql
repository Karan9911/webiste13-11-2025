-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 12, 2025 at 09:36 AM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u445351904_spa_karan`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@spa.com', '$2y$10$Y6lkHWkr9MLCmXAGQJxy0OhFEAcIOkfffrIlWjeHMRlcskdUcljVG', '2025-06-19 11:34:23'),
(2, 'hammam', ' info@hammammensspa.com', '$2y$10$flk.HQhwZ.GZzAiKa0rs8O4yz7iu32GiEYyPjkgbowfNHQlTt6qS6', '2025-07-05 10:29:27'),
(3, 'karan', 'karan1@gmail.com', '$2y$10$i79BoFn223eaxgYp.dGNX.aPU6BEKgp4SXczNdCnyje4Z1rGfpPMm', '2025-07-11 16:44:00');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `therapist_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `total_amount` decimal(10,2) DEFAULT NULL,
  `region` enum('ncr','other') NOT NULL DEFAULT 'other',
  `is_night` tinyint(1) DEFAULT 0,
  `night_charge` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `therapist_id`, `full_name`, `email`, `phone`, `address`, `booking_date`, `booking_time`, `message`, `status`, `payment_status`, `total_amount`, `region`, `is_night`, `night_charge`, `created_at`) VALUES
(1, 1, 'test', 'test@gmail.com', '7692921393', NULL, '2025-06-21', '11:00:00', 'etsti n afj sajfhs', 'completed', 'pending', 2500.00, 'other', 0, 0.00, '2025-06-20 05:05:17'),
(2, 2, 'karan', 'karan@gmail.com', '8178413777', NULL, '2025-06-27', '11:00:00', '', 'completed', 'pending', 3000.00, 'other', 0, 0.00, '2025-06-24 05:09:42'),
(3, 1, 'Sumit', 'karan@gmail.com', '8178413777', NULL, '2025-06-30', '18:00:00', 'I want some time with you and your family is a new job', 'completed', 'pending', 2500.00, 'other', 0, 0.00, '2025-06-26 19:07:02'),
(4, 1, 'Rohit', 'Rohit@gmail.com', '8178413777', NULL, '2025-06-30', '10:00:00', 'Hi this is a trst booking', 'completed', 'pending', 2500.00, 'other', 0, 0.00, '2025-06-28 20:26:19'),
(5, 3, 'karan', 'karan@gmail.com', '8178413777', NULL, '2025-07-22', '14:00:00', 'sdfaf', 'pending', 'pending', 12200.00, 'other', 0, 0.00, '2025-07-01 17:15:20'),
(6, 3, 'karan', 'karan@gmail.com', '8178413777', NULL, '2025-07-24', '14:00:00', 'i want spa at my location', 'confirmed', 'pending', 2800.00, 'other', 0, 0.00, '2025-07-02 16:46:24'),
(7, 2, 'narender', 'karan@gmail.com', '8178413777', NULL, '2025-07-03', '11:00:00', 'test', 'confirmed', 'pending', 3000.00, 'other', 0, 0.00, '2025-07-02 17:36:42'),
(8, 3, 'rohit', 'karan@gmail.com', '8178413777', NULL, '2025-07-10', '10:00:00', 'easfdfas', 'confirmed', 'pending', 2800.00, 'other', 0, 0.00, '2025-07-02 17:45:05'),
(9, 1, 'karan', 'karan@gmail.com', '8178413777', NULL, '2025-07-18', '15:00:00', 'Ichha Z jh dhdkjbd', 'completed', 'pending', 2500.00, 'other', 0, 0.00, '2025-07-03 08:35:21'),
(10, 1, 'akash', 'akash6878@gmail.com', '9875645453', NULL, '2025-07-24', '11:00:00', 'efasdsdfs', 'completed', 'pending', 2500.00, 'other', 0, 0.00, '2025-07-05 05:13:26'),
(11, 2, 'karan', 'karan@gmail.com', '8178413777', NULL, '2025-07-10', '12:00:00', 'test', 'cancelled', 'pending', 3000.00, 'other', 0, 0.00, '2025-07-05 05:16:59'),
(12, 1, 'akash', 'akash@gmail.com', '9876543456', NULL, '2025-07-15', '10:00:00', 'u8yuiyeuryfguisdryufgs', 'completed', 'pending', 2500.00, 'other', 0, 0.00, '2025-07-05 06:39:50'),
(13, 3, 'dev', 'dev2312@gmail.com', '9876554543', NULL, '2025-07-09', '14:00:00', '', 'completed', 'pending', 2800.00, 'other', 0, 0.00, '2025-07-05 09:52:12'),
(14, 2, 'raj', 'raj123@gmail.com', '9711125731', NULL, '2025-07-06', '15:00:00', '', 'pending', 'pending', 3000.00, 'other', 0, 0.00, '2025-07-05 10:08:54'),
(15, 1, 'karan', 'karan@gmail.com', '8178413777', NULL, '2025-07-24', '01:00:00', '', 'confirmed', 'pending', 13500.00, 'other', 1, 1500.00, '2025-07-08 17:05:12'),
(16, 1, 'rohit', 'rohit@gmail.com', '2345678765', NULL, '2025-07-18', '10:00:00', 'poiuytgfd', 'pending', 'pending', 12000.00, 'other', 0, 0.00, '2025-07-08 17:34:10'),
(17, 1, 'karan', 'karan@gmail.com', '8178413777', NULL, '2025-07-10', '00:00:00', '', 'confirmed', 'pending', 13500.00, 'other', 1, 1500.00, '2025-07-09 16:39:41'),
(18, 1, 'karan', 'karan@gmail.com', '8178413777', NULL, '2025-07-20', '00:00:00', 'Civil', 'cancelled', 'pending', 4000.00, 'other', 1, 1500.00, '2025-07-09 18:29:30'),
(19, 1, 'karan', 'karan@gmail.com', '8178413777', NULL, '2025-07-17', '08:00:00', '', 'cancelled', 'pending', 12000.00, 'other', 0, 0.00, '2025-07-10 06:29:20'),
(20, 1, 'karan', 'karan@gmail.com', '8178413777', NULL, '2025-07-31', '12:00:00', 'HI test booking', 'pending', 'pending', 12000.00, 'other', 0, 0.00, '2025-07-11 16:51:41'),
(21, 1, 'karan', 'karan@gmail.com', '8178413777', 'wz-t1\r\n3rd floor blk-2', '2025-07-31', '00:00:00', 'lkjghsd kaskaj kj kad', 'confirmed', 'pending', 13500.00, 'other', 1, 1500.00, '2025-07-11 18:09:46'),
(22, 2, 'karan', 'karan@gmail.com', '8178413777', 'G-65 deepak vihar vk colony', '2025-07-31', '00:00:00', 'Test', 'pending', 'pending', 13500.00, 'other', 1, 1500.00, '2025-07-11 19:43:08'),
(23, 2, 'karan', '', '8178413777', 'wz-t1\r\n3rd floor blk-2', '2025-07-15', '15:00:00', ';oiuytr', 'confirmed', 'pending', 12000.00, 'other', 0, 0.00, '2025-07-12 04:48:58'),
(24, 5, 'nexuscraft solutions', 'nexuscraftsolutions@gmail.com', '4348413777', 'wz-t1\r\n3rd floor blk-2', '2025-07-24', '16:00:00', 'sdfsfd', 'confirmed', 'pending', 12600.00, 'other', 0, 0.00, '2025-07-12 06:28:06'),
(25, 5, 'nexuscraft solutions', 'nexuscraftsolutions@gmail.com', '4348413777', 'wz-t1\r\n3rd floor blk-2', '2025-07-17', '16:00:00', 'sfsfadas', 'confirmed', 'pending', 4500.00, 'ncr', 0, 0.00, '2025-07-12 06:29:25'),
(26, 5, 'nexuscraft solutions', 'karan@gmail.com', '8178413777', 'saharanpur', '2025-07-26', '00:00:00', '', 'confirmed', 'pending', 14100.00, 'other', 1, 1500.00, '2025-07-12 09:07:18');

-- --------------------------------------------------------

--
-- Table structure for table `contact_inquiries`
--

CREATE TABLE `contact_inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','replied','closed') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_inquiries`
--

INSERT INTO `contact_inquiries` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'Narendra Patel', 'narender@gmail.com', '2564856549', 'General Inquiry', 'I want to book a Therapists for treatment', 'new', '2025-06-28 05:35:26'),
(2, 'Test', 'test@gmail.com', '2564856549', 'Service Information', 'Test', 'new', '2025-07-04 18:11:11'),
(3, 'nexuscraft solutions', 'karan@gmail.com', '8178413777', 'Booking Question', 'sdfsfs', 'new', '2025-07-11 17:00:03');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `type` enum('inquiry','booking','whatsapp','contact') NOT NULL,
  `therapist_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('new','follow_up','converted','closed') DEFAULT 'new',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `type`, `therapist_id`, `full_name`, `email`, `phone`, `message`, `status`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 'booking', 2, 'karan', 'karan@gmail.com', '8178413777', '', 'new', NULL, '2025-06-24 05:09:42', '2025-06-24 05:09:42'),
(2, 'inquiry', 2, 'Nukul', 'k1@gmail.com', '9876565456', 'test bookings\n\nPreferred Date: 2025-07-03', 'new', NULL, '2025-06-24 18:06:38', '2025-06-24 18:06:38'),
(3, 'booking', 1, 'Sumit', 'karan@gmail.com', '8178413777', 'I want some time with you and your family is a new job', 'new', NULL, '2025-06-26 19:07:02', '2025-06-26 19:07:02'),
(5, 'inquiry', 1, 'rawat ji', 'dev456rawat@gmail.com', '9389014941', 'testing\n\nPreferred Date: 2025-06-30', 'new', NULL, '2025-06-30 08:16:43', '2025-06-30 08:16:43'),
(6, 'inquiry', 2, 'nexuscraft solutions', 'admin@gmail.com', '9876565667', 'fgdfgh\n\nPreferred Date: 2025-07-09', 'new', NULL, '2025-07-01 17:02:57', '2025-07-01 17:02:57'),
(7, 'booking', 3, 'karan', 'karan@gmail.com', '8178413777', 'sdfaf', 'new', NULL, '2025-07-01 17:15:20', '2025-07-01 17:15:20'),
(8, 'booking', 3, 'karan', 'karan@gmail.com', '8178413777', 'i want spa at my location', 'new', NULL, '2025-07-02 16:46:24', '2025-07-02 16:46:24'),
(9, 'booking', 2, 'narender', 'karan@gmail.com', '8178413777', 'test', 'new', NULL, '2025-07-02 17:36:42', '2025-07-02 17:36:42'),
(10, 'booking', 3, 'rohit', 'karan@gmail.com', '8178413777', 'easfdfas', 'new', NULL, '2025-07-02 17:45:05', '2025-07-02 17:45:05'),
(11, 'booking', 1, 'karan', 'karan@gmail.com', '8178413777', 'Ichha Z jh dhdkjbd', 'new', NULL, '2025-07-03 08:35:21', '2025-07-03 08:35:21'),
(12, 'booking', 1, 'akash', 'akash6878@gmail.com', '9875645453', 'efasdsdfs', 'new', NULL, '2025-07-05 05:13:26', '2025-07-05 05:13:26'),
(13, 'booking', 2, 'karan', 'karan@gmail.com', '8178413777', 'test', 'new', NULL, '2025-07-05 05:16:59', '2025-07-05 05:16:59'),
(14, 'booking', 1, 'akash', 'akash@gmail.com', '9876543456', 'u8yuiyeuryfguisdryufgs', 'new', NULL, '2025-07-05 06:39:50', '2025-07-05 06:39:50'),
(15, 'booking', 3, 'dev', 'dev2312@gmail.com', '9876554543', '', 'new', NULL, '2025-07-05 09:52:12', '2025-07-05 09:52:12'),
(16, 'booking', 2, 'raj', 'raj123@gmail.com', '9711125731', '', 'new', NULL, '2025-07-05 10:08:54', '2025-07-05 10:08:54'),
(17, 'inquiry', 2, 'test', 'test@gmila.com', '9999999999', 'yest\n\nPreferred Date: 2025-07-16', 'new', NULL, '2025-07-06 05:04:31', '2025-07-06 05:04:31'),
(18, 'booking', 1, 'karan', 'karan@gmail.com', '8178413777', '', 'new', NULL, '2025-07-08 17:05:12', '2025-07-08 17:05:12'),
(19, 'booking', 1, 'rohit', 'rohit@gmail.com', '2345678765', 'poiuytgfd', 'new', NULL, '2025-07-08 17:34:10', '2025-07-08 17:34:10'),
(20, 'booking', 1, 'karan', 'karan@gmail.com', '8178413777', '', 'new', NULL, '2025-07-09 16:39:41', '2025-07-09 16:39:41'),
(21, 'booking', 1, 'karan', 'karan@gmail.com', '8178413777', 'Civil', 'new', NULL, '2025-07-09 18:29:30', '2025-07-09 18:29:30'),
(22, 'booking', 1, 'karan', 'karan@gmail.com', '8178413777', '', 'new', NULL, '2025-07-10 06:29:20', '2025-07-10 06:29:20'),
(23, 'booking', 1, 'karan', 'karan@gmail.com', '8178413777', 'HI test booking', 'new', NULL, '2025-07-11 16:51:41', '2025-07-11 16:51:41'),
(24, 'booking', 1, 'karan', 'karan@gmail.com', '8178413777', 'lkjghsd kaskaj kj kad', 'new', NULL, '2025-07-11 18:09:46', '2025-07-11 18:09:46'),
(25, 'booking', 2, 'karan', 'karan@gmail.com', '8178413777', 'Test', 'new', NULL, '2025-07-11 19:43:08', '2025-07-11 19:43:08'),
(26, 'inquiry', 2, 'nexuscraft solutions', 'k1@gmail.com', '2345678765', '.,kjhgfd\n\nPreferred Date: 2025-08-01', 'new', NULL, '2025-07-12 04:41:09', '2025-07-12 04:41:09'),
(27, 'booking', 2, 'karan', '', '8178413777', ';oiuytr', 'new', NULL, '2025-07-12 04:48:58', '2025-07-12 04:48:58'),
(28, 'inquiry', 2, 'nexuscraft solutions', '', '7649539361', ';oiuytds4\n\nPreferred Date: 2025-07-22', 'new', NULL, '2025-07-12 04:51:35', '2025-07-12 04:51:35'),
(29, 'booking', 5, 'nexuscraft solutions', 'nexuscraftsolutions@gmail.com', '4348413777', 'sdfsfd', 'new', NULL, '2025-07-12 06:28:06', '2025-07-12 06:28:06'),
(30, 'booking', 5, 'nexuscraft solutions', 'nexuscraftsolutions@gmail.com', '4348413777', 'sfsfadas', 'new', NULL, '2025-07-12 06:29:25', '2025-07-12 06:29:25'),
(31, 'booking', 5, 'nexuscraft solutions', 'karan@gmail.com', '8178413777', '', 'new', NULL, '2025-07-12 09:07:18', '2025-07-12 09:07:18');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `points` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `icon_type` varchar(255) DEFAULT NULL,
  `icon_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `points`, `created_at`, `icon_type`, `icon_value`) VALUES
(1, 'Swedish Massage', 'Relaxing full body massage with smooth, long strokes', 'Professional certified therapists|Premium quality products|Relaxing ambiance|Customized treatment', '2025-06-19 11:34:23', 'bootstrap', 'bi-fire'),
(2, 'Deep Tissue Massage', 'Therapeutic massage targeting deeper muscle layers', 'Deep muscle tension relief|Sports injury recovery|Chronic pain management|Improved circulation', '2025-06-19 11:34:23', 'bootstrap', 'bi-moon'),
(3, 'Hot Stone Therapy', 'Massage using heated stones to relax muscles', 'Heated volcanic stones|Deep muscle relaxation|Improved blood flow|Stress reduction', '2025-06-19 11:34:23', 'bootstrap', 'bi-gem'),
(4, 'Aromatherapy Massage', 'Massage with essential oils for relaxation', 'Aromatherapy  tension relief | Sports injury recovery | Chronic pain management | Improved circulation', '2025-06-19 11:34:23', 'bootstrap', 'bi-heart-pulse'),
(5, 'Reflexology', 'Pressure point massage focusing on feet and hands', 'Pressure point massage|Energy balance restoration|Organ function improvement|Overall wellness boost', '2025-06-19 11:34:23', 'bootstrap', 'bi-sun'),
(6, 'Thai Massage', 'Traditional stretching and pressure point massage', 'Traditional stretching techniques|Improved flexibility|Energy flow enhancement|Joint mobility improvement', '2025-06-19 11:34:23', 'bootstrap', 'bi-flower1');

-- --------------------------------------------------------

--
-- Table structure for table `therapists`
--

CREATE TABLE `therapists` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price_ncr` decimal(10,2) NOT NULL DEFAULT 0.00,
  `price_other` decimal(10,2) NOT NULL DEFAULT 0.00,
  `height` varchar(20) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `availability_slots` text DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `therapists`
--

INSERT INTO `therapists` (`id`, `name`, `price_ncr`, `price_other`, `height`, `weight`, `description`, `availability_slots`, `main_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Sarah Johnson', 4000.00, 12000.00, '5.8', '55kg', 'Certified massage therapist with 8+ years of experience in Swedish and deep tissue massage.', 'Mon-Fri: 9 AM - 6 PM, Sat: 10 AM - 4 PM', '685c3de3b0486_1750875619.webp', 'active', '2025-06-19 11:34:23', '2025-07-12 05:35:48'),
(2, 'Maya Patel', 4100.00, 12000.00, '5.4', '52kg', 'Specialist in aromatherapy and hot stone therapy. Trained in traditional healing techniques.', 'Tue-Sat: 10 AM - 7 PM', '685c3df7699a4_1750875639.webp', 'active', '2025-06-19 11:34:23', '2025-07-12 05:35:59'),
(3, 'Lisa Chen', 4200.00, 12200.00, '5', '50kg', 'Expert in Thai massage and reflexology. Focuses on holistic wellness and stress relief.', 'Mon-Wed-Fri: 9 AM - 5 PM, Sat-Sun: 11 AM - 3 PM', '685c3e091eb68_1750875657.webp', 'active', '2025-06-19 11:34:23', '2025-07-08 17:00:22'),
(5, 'Akash Singh', 4500.00, 12600.00, '5.4', '72', 'Certified massage therapist with 8+ years of experience', 'Mon-Fri: 9 AM - 6 PM, Sat: 10 AM - 4 PM', '6871f3f2d08cf_1752298482.jpg', 'active', '2025-07-12 05:31:36', '2025-07-12 06:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `therapist_images`
--

CREATE TABLE `therapist_images` (
  `id` int(11) NOT NULL,
  `therapist_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_main` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `therapist_images`
--

INSERT INTO `therapist_images` (`id`, `therapist_id`, `image_path`, `is_main`, `created_at`) VALUES
(87, 1, 'therapists/685c3de3b0486_1750875619.webp', 1, '2025-06-25 18:20:19'),
(88, 1, 'therapists/685c3de3b0b09_1750875619.webp', 0, '2025-06-25 18:20:19'),
(89, 1, 'therapists/685c3de3b0d38_1750875619.webp', 0, '2025-06-25 18:20:19'),
(90, 2, 'therapists/685c3df7699a4_1750875639.webp', 1, '2025-06-25 18:20:39'),
(91, 2, 'therapists/685c3df769dab_1750875639.webp', 0, '2025-06-25 18:20:39'),
(92, 2, 'therapists/685c3df769f5a_1750875639.webp', 0, '2025-06-25 18:20:39'),
(93, 3, 'therapists/685c3e091eb68_1750875657.webp', 1, '2025-06-25 18:20:57'),
(94, 3, 'therapists/685c3e091f017_1750875657.webp', 0, '2025-06-25 18:20:57'),
(95, 3, 'therapists/685c3e091f257_1750875657.webp', 0, '2025-06-25 18:20:57'),
(97, 5, 'therapists/6871f3f2d08cf_1752298482.jpg', 1, '2025-07-12 05:34:42');

-- --------------------------------------------------------

--
-- Table structure for table `therapist_services`
--

CREATE TABLE `therapist_services` (
  `id` int(11) NOT NULL,
  `therapist_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `therapist_services`
--

INSERT INTO `therapist_services` (`id`, `therapist_id`, `service_id`, `created_at`) VALUES
(52, 1, 2, '2025-07-12 05:35:48'),
(53, 1, 1, '2025-07-12 05:35:48'),
(54, 2, 4, '2025-07-12 05:35:59'),
(55, 2, 3, '2025-07-12 05:35:59'),
(56, 3, 3, '2025-07-12 05:36:05'),
(57, 3, 5, '2025-07-12 05:36:05'),
(60, 5, 2, '2025-07-12 06:36:04'),
(61, 5, 1, '2025-07-12 06:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `city`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'karan', 'karan@gmail.com', '8178413777', 'Delhi', '$2y$10$FyMfdtLXF8WYVlkqbtQiROgmYUUbAomlJDMOZEsVXUrTP5aDpRkdi', 'user', 'active', '2025-06-23 05:15:08'),
(4, 'akash', 'akash@gmail.com', '9876543456', 'Delhi', '$2y$10$vkj27Twbvocvh6IMMLdjLO8/lJF6VwrZ3KUXaOh7TBxgNnM6dOEya', 'user', 'active', '2025-07-05 06:39:01'),
(5, 'dev', 'dev2312@gmail.com', '9876554543', 'delhi', '$2y$10$Q7GMxom2s3Fjad2LNO4FpOPPwSXDpqPn/jF98Bq7qA4/xy.N6p0CC', 'user', 'active', '2025-07-05 09:49:57'),
(6, 'raj', 'raj123@gmail.com', '9711125731', 'New Delhi', '$2y$10$mvI1UQFXkN8F1asDJsnaZecCaHtIYrvCmHx341uBxcDzMIh9oCp9i', 'user', 'active', '2025-07-05 10:07:05'),
(7, 'rohit', 'rohit@gmail.com', '2345678765', 'New Delhi', '$2y$10$2c2eBDhQArCDOnoPvszHNOH962ZT4tlzpgBjv3QK3VUuTqvfoOA/.', 'user', 'active', '2025-07-08 17:03:14'),
(8, 'nexuscraft solutions', 'nexuscraftsolutions@gmail.com', '4348413777', 'Delhi', '$2y$10$UKNHoLdV6ZDkN3GY.k7z/OBkKUmsQEJvE4oSAk1HnP0VjITwH9sUa', 'user', 'active', '2025-07-12 06:27:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `therapist_id` (`therapist_id`);

--
-- Indexes for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `therapist_id` (`therapist_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `therapists`
--
ALTER TABLE `therapists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `therapist_images`
--
ALTER TABLE `therapist_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `therapist_id` (`therapist_id`);

--
-- Indexes for table `therapist_services`
--
ALTER TABLE `therapist_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_therapist_service` (`therapist_id`,`service_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `therapists`
--
ALTER TABLE `therapists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `therapist_images`
--
ALTER TABLE `therapist_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `therapist_services`
--
ALTER TABLE `therapist_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`therapist_id`) REFERENCES `therapists` (`id`);

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_ibfk_1` FOREIGN KEY (`therapist_id`) REFERENCES `therapists` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `therapist_images`
--
ALTER TABLE `therapist_images`
  ADD CONSTRAINT `therapist_images_ibfk_1` FOREIGN KEY (`therapist_id`) REFERENCES `therapists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `therapist_services`
--
ALTER TABLE `therapist_services`
  ADD CONSTRAINT `therapist_services_ibfk_1` FOREIGN KEY (`therapist_id`) REFERENCES `therapists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `therapist_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
