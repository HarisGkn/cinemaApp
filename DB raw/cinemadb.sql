-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2023 at 06:11 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinemadb`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `productid` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productid`, `name`, `description`, `price`, `type`) VALUES
(1, 'Lawrence of Arabia', 'Epic historical drama', 12.99, 'Drama'),
(2, 'Rambo - First Blood', 'Action thriller', 9.99, 'Action'),
(3, 'Beasts of No Nation', 'Drama about child soldiers', 8.99, 'Drama'),
(4, 'The Last Samurai', 'Historical action film', 11.99, 'Action'),
(5, '2 Fast 2 Furious edited', 'Action-packed car racing', 10.99, 'Action'),
(8, 'testMovie', 'TestMovie', 1.99, 'Test-movie app demo');

-- --------------------------------------------------------

--
-- Table structure for table `registration_requests`
--

CREATE TABLE `registration_requests` (
  `requestid` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration_requests`
--

INSERT INTO `registration_requests` (`requestid`, `firstname`, `lastname`, `country`, `city`, `address`, `email`, `username`, `password`, `status`) VALUES
(1, 'test4', 'test4', 'test4', 'test4', 'test4', 'test4@test.com', 'test4', '$2y$10$DWhGdK3x2HXI.al/p3yOGuXa.cdXkExBoS2716cwDfiO19LLC4Bn2', 'accepted'),
(2, 'test5', 'test5', 'test5', 'test5', 'test5', 'test5@test.com', 'test5', '$2y$10$PG5.Gd4rgBDn/4zmmnnCVOKrgJYhdTh7y3RHRl.wSowtZOf8JdEEy', 'accepted'),
(3, 'test6', 'test6', 'test6', 'test6', 'test6', 'test6@test.com', 'test6', '$2y$10$bUzYTgFlM19ZlftdGNGk6u5apW2/u5tyFRK/k4bvgHxljmfkUk0Ti', 'rejected'),
(4, 'test7', 'test7', 'test7', 'test7', 'test7', 'test7@test.com', 'test7', '$2y$10$ugtVeNFp0bjOmCPsg3BdEeK18Ap8sHW4b/A3DL53eK5WupY9GBhUq', 'rejected'),
(5, 'test8', 'test8', 'test8', 'test8', 'test8', 'test8@test.com', 'test8', '$2y$10$TwL2hq5Z2PbAkKD.2tGED.t.9SA74JjfAo27zbRB4A5mzXmr5DdpK', 'accepted'),
(6, 'countrytest', 'countrytest', 'GR', 'countrytest', 'countrytest', 'countrytest@test.com', 'countrytest', '$2y$10$.B.4YMhZ7GvqQGwS9TxXiOGO6lGLglWEKArkytyo6I8oEKiQBcV4.', 'pending'),
(7, 'countrytest2', 'countrytest2', 'Angola', 'countrytest2', 'countrytest2', 'countrytest2@test.com', 'countrytest2', '$2y$10$4G5bgBkzdISk7ngXh3ac7uXK9G6nGpdvvNnstGDEhArjS2E7dZYoW', 'pending'),
(8, 'citiestest', 'citiestest', 'Austria', 'Achensee', 'citiestest', 'citiestest@citiestest.com', 'citiestest', '$2y$10$JA7d/SZ7i7s6m72l9ZlPIenOBNvE0Thq/OQdFWLM12sG99RybaP3K', 'pending'),
(9, 'DemoTest', 'DemoTest', 'Austria', 'Abtenau', 'DemoTest 70', 'DemoTest@testemail.com', 'DemoTest', '$2y$10$RsJIPJqEBD8OSeuMdwEhnOGBjpMMH8qovaRFVRkm4IBYTvAlFM93S', 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservationid` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `productid` int(11) DEFAULT NULL,
  `reservationdate` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservationid`, `userid`, `productid`, `reservationdate`, `status`) VALUES
(4, 1, 1, '2023-08-10', 'Confirmed'),
(5, 2, 2, '2023-08-15', 'Cancelled'),
(6, 3, 3, '2023-08-20', 'Pending'),
(7, 3, 2, '2023-08-10', 'Pending'),
(9, 2, 1, '2023-08-09', 'Pending'),
(10, 2, 5, '2023-08-31', 'Pending'),
(11, 2, 2, '2024-03-03', 'Cancelled'),
(14, 2, 1, '2023-08-17', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `firstname`, `lastname`, `country`, `city`, `address`, `email`, `username`, `password`, `role`) VALUES
(1, 'test', 'test', 'test', 'test', 'test', 'test@test.com', 'test', '$2y$10$uTyQZpBeeBnVcuxxh9s/4ONZ3mPPt2DqdIb7SaZlgFyG1pD4.1h3C', 'admin'),
(2, 'test2', 'test2', 'test2', 'test2', 'test2', 'test2@test.com', 'test2', '$2y$10$1cWB0pgOFE.J.9dZeiu8juSi8ELsrsDDC8LnmjWGdfrkC2SxS3G42', 'user'),
(3, 'test3', 'test3', 'test3', 'test3', 'test3', 'test3@test.com', 'test3', '$2y$10$RUOuB64oSiR70/jLwyMLquSqoEtSkXQ3xXL4h2nL5c5QM5CvcMLfK', 'user'),
(4, 'test4', 'test4', 'test4', 'test4', 'test4', 'test4@test.com', 'test4', '$2y$10$DWhGdK3x2HXI.al/p3yOGuXa.cdXkExBoS2716cwDfiO19LLC4Bn2', 'user'),
(5, 'test5', 'test5', 'test5', 'test5', 'test5', 'test5@test.com', 'test5', '$2y$10$PG5.Gd4rgBDn/4zmmnnCVOKrgJYhdTh7y3RHRl.wSowtZOf8JdEEy', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productid`);

--
-- Indexes for table `registration_requests`
--
ALTER TABLE `registration_requests`
  ADD PRIMARY KEY (`requestid`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservationid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `fk_reservations_product` (`productid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `productid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `registration_requests`
--
ALTER TABLE `registration_requests`
  MODIFY `requestid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservationid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservations_product` FOREIGN KEY (`productid`) REFERENCES `products` (`productid`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `products` (`productid`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
