-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 10:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vacaystar`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `card_fee` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `vat` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `adults` int(11) DEFAULT NULL,
  `children` int(11) DEFAULT NULL,
  `pets` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `hotel_id`, `customer_name`, `phone`, `checkin_date`, `checkout_date`, `days`, `subtotal`, `card_fee`, `discount`, `vat`, `total`, `adults`, `children`, `pets`, `created_at`) VALUES
(1, 10, 'Product Name example', 'df', '2025-05-22', '2025-05-24', 2, 11516.00, 88.00, 0.00, 18080.12, 29684.12, 1, 2, 'yes', '2025-05-21 20:41:58');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `host_profiles`
--

CREATE TABLE `host_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `host_name` varchar(100) DEFAULT NULL,
  `experience_years` int(11) DEFAULT 0,
  `highlights` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `host_profiles`
--

INSERT INTO `host_profiles` (`id`, `user_id`, `host_name`, `experience_years`, `highlights`, `profile_image`, `created_at`) VALUES
(1, 1, 'fgf', 3, 'fgdfg', 'uploads/1747859390_dddddddddddddddddd.png', '2025-05-21 20:29:50');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `price_per_day` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `card_fee` decimal(10,2) DEFAULT 0.00,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `vat_percentage` decimal(5,2) DEFAULT 0.00,
  `image_path` varchar(255) DEFAULT NULL,
  `max_guests` int(11) DEFAULT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `house_rules` text DEFAULT NULL,
  `safety` text DEFAULT NULL,
  `cancellation_policy` text DEFAULT NULL,
  `checkin_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `title`, `user_id`, `name`, `description`, `location`, `price_per_day`, `price`, `card_fee`, `discount_percentage`, `vat_percentage`, `image_path`, `max_guests`, `check_in`, `check_out`, `image_url`, `created_at`, `house_rules`, `safety`, `cancellation_policy`, `checkin_date`) VALUES
(1, 'sd', 1, '', 'fdf', NULL, 44.00, NULL, 55.00, 555.00, 15.00, '', 55, NULL, NULL, NULL, '2025-05-21 20:32:54', NULL, NULL, NULL, NULL),
(2, 'sd', 1, '', 'fdf', NULL, 44.00, NULL, 55.00, 555.00, 15.00, '', 55, NULL, NULL, NULL, '2025-05-21 20:33:27', NULL, NULL, NULL, NULL),
(3, 'sd', 1, '', 'fdf', NULL, 44.00, NULL, 55.00, 555.00, 15.00, '', 55, NULL, NULL, NULL, '2025-05-21 20:34:13', NULL, NULL, NULL, NULL),
(4, 'sd', 1, '', 'fdf', NULL, 44.00, NULL, 55.00, 555.00, 15.00, '', 55, NULL, NULL, NULL, '2025-05-21 20:34:24', NULL, NULL, NULL, NULL),
(5, 'sd', 1, '', 'fdf', NULL, 44.00, NULL, 55.00, 555.00, 15.00, '', 55, NULL, NULL, NULL, '2025-05-21 20:34:53', NULL, NULL, NULL, NULL),
(6, 'sd', 1, '', 'fdf', NULL, 44.00, NULL, 55.00, 555.00, 15.00, '', 55, NULL, NULL, NULL, '2025-05-21 20:35:15', 'sdfs', 'sfs', 'sdfsdf', NULL),
(7, 'sd', 1, '', 'fdf', NULL, 44.00, NULL, 55.00, 555.00, 15.00, '', 55, NULL, NULL, NULL, '2025-05-21 20:35:40', NULL, NULL, NULL, NULL),
(8, 'sd', 1, '', 'fdf', NULL, 44.00, NULL, 55.00, 555.00, 15.00, '', 55, NULL, NULL, NULL, '2025-05-21 20:36:13', NULL, NULL, NULL, NULL),
(9, 'erte', 1, '', 'uthrth', NULL, 74.00, NULL, 585.00, 585.00, 999.99, '', 57, NULL, NULL, NULL, '2025-05-21 20:37:55', 'zcxdsv', 'sdvsd', 'sdvsdc', NULL),
(10, 'fsfg', 1, '', 'sgs', NULL, 5758.00, NULL, 88.00, 88.00, 157.00, '', 88, NULL, NULL, NULL, '2025-05-21 20:40:16', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_facilities`
--

CREATE TABLE `hotel_facilities` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `facility_name` varchar(100) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_facilities`
--

INSERT INTO `hotel_facilities` (`id`, `hotel_id`, `facility_name`, `icon`, `created_at`) VALUES
(1, 8, 'Double bed', '🛏️', '2025-05-21 20:36:13'),
(2, 8, 'Wi-Fi', '📶', '2025-05-21 20:36:13'),
(3, 8, 'Smart TV', '📺', '2025-05-21 20:36:13'),
(4, 8, 'Coffee Maker', '☕', '2025-05-21 20:36:13'),
(5, 9, 'Double bed', '🛏️', '2025-05-21 20:37:55'),
(6, 9, 'Wi-Fi', '📶', '2025-05-21 20:37:55'),
(7, 9, 'Private Bathroom', '🚿', '2025-05-21 20:37:55'),
(8, 9, 'Smart TV', '📺', '2025-05-21 20:37:55'),
(9, 9, 'Coffee Maker', '☕', '2025-05-21 20:37:55'),
(10, 9, 'Free Parking', '🅿️', '2025-05-21 20:37:55'),
(11, 9, 'Air Conditioning', '❄️', '2025-05-21 20:37:55'),
(12, 9, 'Pet Friendly', '🐶', '2025-05-21 20:37:55'),
(13, 10, 'Double bed', '🛏️', '2025-05-21 20:40:16'),
(14, 10, 'Smart TV', '📺', '2025-05-21 20:40:16'),
(15, 10, 'Air Conditioning', '❄️', '2025-05-21 20:40:16');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_images`
--

CREATE TABLE `hotel_images` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_images`
--

INSERT INTO `hotel_images` (`id`, `hotel_id`, `image_path`, `uploaded_at`) VALUES
(1, 2, 'uploads/1747859607_dddddddddddddddddd.png', '2025-05-21 20:33:27'),
(2, 3, 'uploads/1747859653_dddddddddddddddddd.png', '2025-05-21 20:34:13'),
(3, 4, 'uploads/1747859664_dddddddddddddddddd.png', '2025-05-21 20:34:24'),
(4, 5, 'uploads/1747859693_dddddddddddddddddd.png', '2025-05-21 20:34:53'),
(5, 6, 'uploads/1747859715_dddddddddddddddddd.png', '2025-05-21 20:35:15'),
(6, 7, 'uploads/1747859740_dddddddddddddddddd.png', '2025-05-21 20:35:40'),
(7, 8, 'uploads/1747859773_dddddddddddddddddd.png', '2025-05-21 20:36:13'),
(8, 9, 'uploads/1747859875_dddddddddddddddddd.png', '2025-05-21 20:37:55'),
(9, 10, 'uploads/1747860016_gsap-smoooth-poster_randomizer.png', '2025-05-21 20:40:16'),
(10, 10, 'uploads/1747860016_Image.png', '2025-05-21 20:40:16'),
(11, 10, 'uploads/1747860016_BlogImg-3.png', '2025-05-21 20:40:16'),
(12, 10, 'uploads/1747860016_BlogImg-2.png', '2025-05-21 20:40:16'),
(13, 10, 'uploads/1747860016_BlogImg-1.png', '2025-05-21 20:40:16');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `password_hash` varchar(255) NOT NULL,
  `role` enum('host','guest','admin') DEFAULT 'host'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `password_hash`, `role`) VALUES
(1, 'Arcadii', '', '', '2025-05-21 20:21:18', '$2y$10$EAZlhBS8GP1sazbsaxjiiOElOgYf80v2yx/8xc1nhBgf2CDPVrx2.', 'host');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `host_profiles`
--
ALTER TABLE `host_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `host_profiles`
--
ALTER TABLE `host_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hotel_images`
--
ALTER TABLE `hotel_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `host_profiles`
--
ALTER TABLE `host_profiles`
  ADD CONSTRAINT `host_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `hotels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  ADD CONSTRAINT `hotel_facilities_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD CONSTRAINT `hotel_images_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
