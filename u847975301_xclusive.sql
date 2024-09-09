-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 15, 2024 at 01:43 PM
-- Server version: 10.6.14-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u847975301_xclusive`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `car_type` varchar(191) DEFAULT NULL,
  `service` varchar(191) DEFAULT NULL,
  `slot` tinyint(6) DEFAULT NULL,
  `employee_assigned` varchar(191) NOT NULL,
  `reserve_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Pending','Confirmed','Cancelled','Completed','Declined','Waitlisted','Refund') DEFAULT 'Pending',
  `note` varchar(191) DEFAULT NULL,
  `reserve_time` time(6) DEFAULT NULL,
  `price` int(60) DEFAULT NULL,
  `payment_method` varchar(191) DEFAULT NULL,
  `reservation_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `reservation_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_status` int(11) DEFAULT 0,
  `service_type` varchar(255) DEFAULT NULL,
  `duration` decimal(5,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings_waitlist`
--

CREATE TABLE `bookings_waitlist` (
  `WaitlistID` int(11) NOT NULL,
  `Booking_ID` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cancelled_bookings`
--

CREATE TABLE `cancelled_bookings` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_services`
--

CREATE TABLE `car_services` (
  `id` int(11) NOT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `service_classification` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_services`
--

INSERT INTO `car_services` (`id`, `service_type`, `service_classification`) VALUES
(1, 'Carwash', 'Standard-Wash'),
(2, 'Engine Wash', 'Standard-Wash'),
(3, 'Acid Rain Removal', 'Standard-Wash'),
(4, 'Asphalt Removal', 'Standard-Wash'),
(5, 'Dashboard & Sidings Polishing', 'Standard-Wash'),
(6, 'Back 2 Zero / Sterilize', 'Standard-Wash'),
(7, 'Quickie Detail Wax', 'Waxing'),
(8, 'Handjob Wax', 'Waxing'),
(9, 'Machine Job Wax', 'Waxing'),
(10, 'Glass Detailing', 'Premium-Detailing'),
(11, 'Interior Detailing', 'Premium-Detailing'),
(12, 'Exterior Detailing', 'Premium-Detailing'),
(13, 'Headlight Restoration', 'Premium-Detailing');

-- --------------------------------------------------------

--
-- Table structure for table `car_types`
--

CREATE TABLE `car_types` (
  `id` int(11) NOT NULL,
  `car_type` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_types`
--

INSERT INTO `car_types` (`id`, `car_type`) VALUES
(1, 'Motorcycle'),
(2, 'Compact Cars'),
(3, 'Sedans'),
(4, 'SUVs/Crossovers'),
(5, 'Trucks'),
(6, 'Vans/Minivans');

-- --------------------------------------------------------

--
-- Table structure for table `declined_bookings`
--

CREATE TABLE `declined_bookings` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `reason_for_decline` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `Specialty` varchar(50) NOT NULL,
  `picture_path` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `contact`, `Specialty`, `picture_path`, `is_available`) VALUES
(15, 'Remiere Laguna', 'd1@gmail.com', '09991416851', 'Auto Detailing', '../../img/uploads/Remiere Laguna656cbcded05e83.28176897.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nextpay`
--

CREATE TABLE `nextpay` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `referenceId` varchar(255) DEFAULT NULL,
  `paymentlinkId` varchar(255) DEFAULT NULL,
  `paymentId` varchar(255) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `payer_id` varchar(255) NOT NULL,
  `payer_email` varchar(255) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `booking_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sentiment_analysis`
--

CREATE TABLE `sentiment_analysis` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `neg` float DEFAULT NULL,
  `neu` float DEFAULT NULL,
  `pos` float DEFAULT NULL,
  `comp` float DEFAULT NULL,
  `sentiment` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_prices`
--

CREATE TABLE `service_prices` (
  `id` int(11) NOT NULL,
  `car_service_id` int(11) DEFAULT NULL,
  `car_type_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `duration_hours` decimal(5,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_prices`
--

INSERT INTO `service_prices` (`id`, `car_service_id`, `car_type_id`, `price`, `duration_hours`) VALUES
(1, 1, 1, 120.00, 1.0),
(2, 1, 2, 130.00, 1.0),
(3, 1, 3, 160.00, 1.5),
(4, 1, 4, 160.00, 1.5),
(5, 1, 5, 200.00, 2.0),
(6, 1, 6, 220.00, 2.0),
(7, 2, 2, 270.00, 2.0),
(8, 2, 3, 340.00, 2.5),
(9, 2, 4, 340.00, 2.5),
(10, 2, 5, 400.00, 3.0),
(11, 2, 6, 430.00, 3.0),
(12, 3, 2, 520.00, 3.5),
(13, 3, 3, 690.00, 4.0),
(14, 3, 4, 890.00, 4.5),
(15, 3, 5, 1050.00, 5.0),
(16, 3, 6, 1230.00, 5.5),
(17, 4, 2, 90.00, 1.0),
(18, 4, 3, 90.00, 1.0),
(19, 4, 4, 110.00, 1.5),
(20, 4, 5, 90.00, 1.0),
(21, 4, 6, 100.00, 1.5),
(22, 5, 2, 110.00, 1.5),
(23, 5, 3, 110.00, 1.5),
(24, 5, 4, 130.00, 2.0),
(25, 5, 5, 110.00, 1.5),
(26, 5, 6, 90.00, 1.0),
(27, 6, 2, 210.00, 2.0),
(28, 6, 3, 210.00, 2.0),
(29, 6, 4, 240.00, 2.5),
(30, 6, 5, 230.00, 2.5),
(31, 6, 6, 250.00, 2.5),
(32, 7, 1, 120.00, 1.0),
(33, 7, 2, 250.00, 2.5),
(34, 7, 3, 400.00, 3.5),
(35, 7, 4, 450.00, 4.0),
(36, 7, 5, 500.00, 4.5),
(37, 7, 6, 550.00, 5.0),
(38, 8, 1, 400.00, 3.5),
(39, 8, 2, 550.00, 4.0),
(40, 8, 3, 600.00, 4.5),
(41, 8, 4, 650.00, 5.0),
(42, 8, 5, 700.00, 5.5),
(43, 8, 6, 750.00, 6.0),
(44, 9, 1, 400.00, 3.5),
(45, 9, 2, 700.00, 5.0),
(46, 9, 3, 750.00, 5.5),
(47, 9, 4, 800.00, 6.0),
(48, 9, 5, 850.00, 6.5),
(49, 9, 6, 900.00, 7.0),
(50, 10, 2, 2500.00, 10.0),
(51, 10, 3, 3000.00, 12.0),
(52, 10, 4, 3300.00, 13.0),
(53, 10, 5, 3500.00, 14.0),
(54, 10, 6, 4000.00, 16.0),
(55, 11, 2, 3500.00, 14.0),
(56, 11, 3, 4000.00, 16.0),
(57, 11, 4, 4500.00, 18.0),
(58, 11, 5, 5000.00, 20.0),
(59, 11, 6, 6000.00, 24.0),
(60, 12, 2, 3500.00, 14.0),
(61, 12, 3, 4000.00, 16.0),
(62, 12, 4, 4500.00, 18.0),
(63, 12, 5, 5000.00, 20.0),
(64, 12, 6, 6000.00, 24.0),
(65, 13, 1, 500.00, 2.0),
(66, 13, 2, 500.00, 2.0),
(67, 13, 3, 500.00, 2.0),
(68, 13, 4, 500.00, 2.0),
(69, 13, 5, 500.00, 2.0),
(70, 13, 6, 500.00, 2.0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'default.png',
  `role_as` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `verify_token` varchar(191) DEFAULT NULL,
  `verify_status` tinyint(1) DEFAULT NULL,
  `authentication_source` enum('local','google') NOT NULL DEFAULT 'local',
  `google_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bookings_waitlist`
--
ALTER TABLE `bookings_waitlist`
  ADD PRIMARY KEY (`WaitlistID`),
  ADD KEY `Booking_ID` (`Booking_ID`);

--
-- Indexes for table `cancelled_bookings`
--
ALTER TABLE `cancelled_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `car_services`
--
ALTER TABLE `car_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car_types`
--
ALTER TABLE `car_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `declined_bookings`
--
ALTER TABLE `declined_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `nextpay`
--
ALTER TABLE `nextpay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_booking_id` (`booking_id`);

--
-- Indexes for table `sentiment_analysis`
--
ALTER TABLE `sentiment_analysis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_prices`
--
ALTER TABLE `service_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_service_id` (`car_service_id`),
  ADD KEY `car_type_id` (`car_type_id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT for table `bookings_waitlist`
--
ALTER TABLE `bookings_waitlist`
  MODIFY `WaitlistID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cancelled_bookings`
--
ALTER TABLE `cancelled_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `car_services`
--
ALTER TABLE `car_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `car_types`
--
ALTER TABLE `car_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `declined_bookings`
--
ALTER TABLE `declined_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `nextpay`
--
ALTER TABLE `nextpay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `sentiment_analysis`
--
ALTER TABLE `sentiment_analysis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2047144946;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings_waitlist`
--
ALTER TABLE `bookings_waitlist`
  ADD CONSTRAINT `bookings_waitlist_ibfk_1` FOREIGN KEY (`Booking_ID`) REFERENCES `bookings` (`booking_id`);

--
-- Constraints for table `cancelled_bookings`
--
ALTER TABLE `cancelled_bookings`
  ADD CONSTRAINT `cancelled_bookings_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);

--
-- Constraints for table `declined_bookings`
--
ALTER TABLE `declined_bookings`
  ADD CONSTRAINT `declined_bookings_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);

--
-- Constraints for table `nextpay`
--
ALTER TABLE `nextpay`
  ADD CONSTRAINT `nextpay_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_booking_id` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `service_prices`
--
ALTER TABLE `service_prices`
  ADD CONSTRAINT `service_prices_ibfk_1` FOREIGN KEY (`car_service_id`) REFERENCES `car_services` (`id`),
  ADD CONSTRAINT `service_prices_ibfk_2` FOREIGN KEY (`car_type_id`) REFERENCES `car_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
