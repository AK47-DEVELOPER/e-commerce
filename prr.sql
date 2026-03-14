-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2025 at 04:50 PM
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
-- Database: `prr`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_carts`
--

CREATE TABLE `tb_carts` (
  `cart_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `prod_qty` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_orders`
--

CREATE TABLE `tb_orders` (
  `orde_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_fname` text NOT NULL,
  `user_lname` text NOT NULL,
  `user_email` text NOT NULL,
  `user_tel` text NOT NULL,
  `user_address` text NOT NULL,
  `orde_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `orde_del` datetime DEFAULT NULL,
  `orde_slip` text NOT NULL,
  `orde_bank` text NOT NULL,
  `orde_bankdate` text NOT NULL,
  `orde_time` time NOT NULL,
  `orde_amount` text NOT NULL,
  `orde_slip2` text NOT NULL,
  `orde_deposit` enum('0','1') NOT NULL DEFAULT '0',
  `orde_status` enum('0','1','2','3','4','5') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_order_lists`
--

CREATE TABLE `tb_order_lists` (
  `orli_id` int(11) NOT NULL,
  `orde_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `prod_price` int(11) NOT NULL,
  `prod_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_products`
--

CREATE TABLE `tb_products` (
  `prod_id` int(11) NOT NULL,
  `prod_img` text NOT NULL,
  `prod_name` text NOT NULL,
  `prod_details` text NOT NULL,
  `prod_price` int(11) NOT NULL,
  `prod_max` int(11) NOT NULL,
  `prod_total` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

CREATE TABLE `tb_users` (
  `user_id` int(11) NOT NULL,
  `user_username` varchar(24) NOT NULL,
  `user_password` varchar(24) NOT NULL,
  `user_fname` text NOT NULL,
  `user_lname` text NOT NULL,
  `user_email` text NOT NULL,
  `user_tel` varchar(10) NOT NULL,
  `user_address` text NOT NULL,
  `user_rules` enum('member','factory','admin') NOT NULL DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_users`
--

INSERT INTO `tb_users` (`user_id`, `user_username`, `user_password`, `user_fname`, `user_lname`, `user_email`, `user_tel`, `user_address`, `user_rules`) VALUES
(1, 'ad', '123456', 'Admin', 'Admin', 'admin@test.com', '0123456789', '0', 'admin'),
(2, 'fa', '123456', 'Factory', 'Factory', 'factory@test.com', '0123456789', '0', 'factory'),
(3, 'me', '123456', 'Member', 'Member', 'member@test.com', '0123456789', '0', 'member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_carts`
--
ALTER TABLE `tb_carts`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `tb_orders`
--
ALTER TABLE `tb_orders`
  ADD PRIMARY KEY (`orde_id`);

--
-- Indexes for table `tb_order_lists`
--
ALTER TABLE `tb_order_lists`
  ADD PRIMARY KEY (`orli_id`);

--
-- Indexes for table `tb_products`
--
ALTER TABLE `tb_products`
  ADD PRIMARY KEY (`prod_id`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_username` (`user_username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_carts`
--
ALTER TABLE `tb_carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_orders`
--
ALTER TABLE `tb_orders`
  MODIFY `orde_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_order_lists`
--
ALTER TABLE `tb_order_lists`
  MODIFY `orli_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_products`
--
ALTER TABLE `tb_products`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;