-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2024 at 08:34 AM
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
-- Database: `edb`
--

-- --------------------------------------------------------

--
-- Table structure for table `carddetails`
--

CREATE TABLE `carddetails` (
  `cardNumber` varchar(30) NOT NULL,
  `cvv` int(11) NOT NULL,
  `expiry` int(11) NOT NULL,
  `balance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carddetails`
--

INSERT INTO `carddetails` (`cardNumber`, `cvv`, `expiry`, `balance`) VALUES
('4581560038717195', 1234, 2025, 983),
('3530111333300000', 1357, 2022, 750),
('4581560038717195', 1894, 2023, 983),
('5105105105105100', 5678, 2024, 501),
('6011000990139424', 7890, 2025, 3000);

-- --------------------------------------------------------

--
-- Table structure for table `consumer`
--

CREATE TABLE `consumer` (
  `id` int(11) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `units_consumed` int(10) NOT NULL,
  `bill` int(10) NOT NULL,
  `selectedMonth` varchar(30) NOT NULL,
  `payment_status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `service` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `customer_id` varchar(255) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `units_consumed` int(10) NOT NULL,
  `bill` int(10) NOT NULL,
  `selectedMonth` varchar(30) NOT NULL,
  `payment_status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registered`
--

CREATE TABLE `registered` (
  `customer_id` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `password` varchar(30) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registered`
--

INSERT INTO `registered` (`customer_id`, `name`, `email`, `user_type`, `password`, `file_path`) VALUES
('4887700823674', 'Kaushik R', 'kaushik@gmail.com', 'admin', '1234', 'uploads/CD_Module 3 (1).pdf'),
('6690048209831', 'Abdul Rahman', 'rahmanckm018@gmail.com', 'super', '1234', 'uploads/e10.pdf'),
('9982527533955', 'MAF', 'maf@gmail.com', 'user', '1234', 'uploads/WhatsApp Image 2024-02');

-- --------------------------------------------------------

--
-- Table structure for table `temp_reg`
--

CREATE TABLE `temp_reg` (
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `file_path` varchar(30) NOT NULL,
  `user_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `temp_reg`
--

INSERT INTO `temp_reg` (`name`, `email`, `password`, `file_path`, `user_type`) VALUES
('Shashank', 'shashank@gmail.com', '1234', 'uploads/http request.pdf', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `upidetails`
--

CREATE TABLE `upidetails` (
  `upiNumber` varchar(30) NOT NULL,
  `balance` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `upidetails`
--

INSERT INTO `upidetails` (`upiNumber`, `balance`) VALUES
('8754440987@ybl', 1500),
('8660509040@abl', 501),
('9512083543@sbi', 1201),
('9882233769@hdfc', 3000),
('9440608050@icici', 750);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carddetails`
--
ALTER TABLE `carddetails`
  ADD PRIMARY KEY (`cvv`);

--
-- Indexes for table `consumer`
--
ALTER TABLE `consumer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_consumer_customer` (`customer_id`),
  ADD KEY `fk_consumer_registered` (`email`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD KEY `fk_contact_registered` (`email`);

--
-- Indexes for table `registered`
--
ALTER TABLE `registered`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `idx_customer_id` (`customer_id`),
  ADD KEY `idx_registered_email` (`email`);

--
-- Indexes for table `temp_reg`
--
ALTER TABLE `temp_reg`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `consumer`
--
ALTER TABLE `consumer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `consumer`
--
ALTER TABLE `consumer`
  ADD CONSTRAINT `fk_consumer_registered` FOREIGN KEY (`email`) REFERENCES `registered` (`email`);

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `fk_contact_registered` FOREIGN KEY (`email`) REFERENCES `registered` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
