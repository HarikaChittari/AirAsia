-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 01, 2024 at 07:34 PM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rewards`
--

-- --------------------------------------------------------

--
-- Table structure for table `Account`
--

CREATE TABLE `Account` (
  `accountId` int NOT NULL,
  `userId` int NOT NULL,
  `accountType` varchar(100) NOT NULL,
  `points` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Account`
--

INSERT INTO `Account` (`accountId`, `userId`, `accountType`, `points`) VALUES
(1, 3, 'Customer', 1000),
(2, 6, 'Customer', 200),
(3, 1, 'Admin', 2500);

-- --------------------------------------------------------

--
-- Table structure for table `giftcard`
--

CREATE TABLE `giftcard` (
  `giftcard_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `points_required` int DEFAULT NULL,
  `type` enum('Wallet cash','Discount','Voucher') DEFAULT NULL,
  `imagepath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `giftcard`
--

INSERT INTO `giftcard` (`giftcard_id`, `name`, `value`, `points_required`, `type`, `imagepath`) VALUES
(1, 'Fly High, Pay Low', 10.00, 1000, 'Voucher', './img/gift-card-img-1.jpeg'),
(2, 'Home for the Holidays', 20.00, 800, 'Discount', './img/gift-card-img-2.jpeg'),
(3, 'Best Price Guaranteed', 50.00, 2500, 'Wallet cash', './img/gift-card-img-3.png'),
(4, 'Home for the Holidays', 50.00, 800, 'Voucher', './img/gift-card-img-4.jpeg'),
(5, 'Best Price Guaranteed', 25.00, 2500, 'Discount', './img/gift-card-img-5.jpeg'),
(6, 'Say Hello to AirAsia Gifts!', 5.00, 500, 'Wallet cash', './img/gift-card-img-6.webp'),
(7, 'Adventure Awaits', 20.00, 1200, 'Voucher', './img/gift-card-img-7.jpg'),
(8, 'Discount Bonanza', 15.00, 1000, 'Discount', './img/gift-card-img-8.png'),
(9, 'Celebrate with Miles', 100.00, 2000, 'Wallet cash', './img/gift-card-img-9.jpeg'),
(10, 'Explore the Skies', 30.00, 1500, 'Voucher', './img/gift-card-img-10.jpeg'),
(11, 'Weekend Getaway', 40.00, 1100, 'Discount', './img/gift-card-img-11.jpeg'),
(12, 'Student Saver Pass', 12.00, 600, 'Voucher', './img/gift-card-img-12.jpeg'),
(13, 'Business Traveler Special', 50.00, 1600, 'Wallet cash', './img/gift-card-img-13.jpeg'),
(14, 'Holiday Season Voucher', 75.00, 900, 'Discount', './img/gift-card-img-14.jpeg'),
(15, 'Super Saver Points', 20.00, 850, 'Voucher', './img/gift-card-img-15.png'),
(16, 'Shopping Spree Card', 60.00, 1250, 'Wallet cash', './img/gift-card-img-16.jpeg'),
(17, 'Relax & Unwind', 35.00, 1400, 'Discount', './img/gift-card-img-17.jpeg'),
(18, 'Family Fun Pass', 25.00, 1000, 'Voucher', './img/gift-card-img-18.jpeg'),
(19, 'VIP Lounge Access', 120.00, 3000, 'Wallet cash', './img/gift-card-img-19.jpeg'),
(20, 'Exclusive Travel Credit', 85.00, 2100, 'Discount', './img/gift-card-img-20.jpeg'),
(21, 'HARIKA CHITTARI', 1234.00, 123, 'Discount', './img/uploads/Salad-image.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `Redemption`
--

CREATE TABLE `Redemption` (
  `redeemId` int NOT NULL,
  `date` varchar(100) NOT NULL,
  `accountId` int NOT NULL,
  `cardId` int NOT NULL,
  `pointsRedeemed` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Redemption`
--

INSERT INTO `Redemption` (`redeemId`, `date`, `accountId`, `cardId`, `pointsRedeemed`) VALUES
(1, '2024-12-01 18:29:37', 2, 1, 1000),
(2, '2024-12-01 19:01:52', 3, 3, 2500),
(3, '2024-12-01 19:25:43', 2, 2, 800);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int NOT NULL,
  `userName` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `userName`, `password`, `firstName`, `lastName`, `role`) VALUES
(1, 'bsmith', '$2y$10$cQqgK8Cqa6Dd2HiLOqiYLOqWPi.TC6a3JaU.2vrgnqb9MJ3MPf1Mm', 'Smith', 'Barney', 'Admin'),
(3, 'pjones', '$2y$10$BR4ncLXbJN6oljw6X6o5BeSIT3pbCOmqKnsSSjqSLQRif4jW2h7J2', 'Jones', 'Patrick', 'Customer'),
(6, 'hchittari', '$2y$10$1fC56vmYw/O52R.PvwmJZ.q.4PzVH3OJgtJ3g4Xiw7EBHm37un02.', 'Harika', 'Chittari', 'Customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Account`
--
ALTER TABLE `Account`
  ADD PRIMARY KEY (`accountId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `giftcard`
--
ALTER TABLE `giftcard`
  ADD PRIMARY KEY (`giftcard_id`);

--
-- Indexes for table `Redemption`
--
ALTER TABLE `Redemption`
  ADD PRIMARY KEY (`redeemId`),
  ADD KEY `accountId` (`accountId`),
  ADD KEY `cardId` (`cardId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Account`
--
ALTER TABLE `Account`
  MODIFY `accountId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `giftcard`
--
ALTER TABLE `giftcard`
  MODIFY `giftcard_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `Redemption`
--
ALTER TABLE `Redemption`
  MODIFY `redeemId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Account`
--
ALTER TABLE `Account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `Redemption`
--
ALTER TABLE `Redemption`
  ADD CONSTRAINT `redemption_ibfk_1` FOREIGN KEY (`accountId`) REFERENCES `Account` (`accountId`) ON DELETE CASCADE,
  ADD CONSTRAINT `redemption_ibfk_2` FOREIGN KEY (`cardId`) REFERENCES `GiftCard` (`giftcard_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
