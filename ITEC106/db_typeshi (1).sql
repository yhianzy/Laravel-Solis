-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2026 at 03:05 PM
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
-- Database: `db_typeshi`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contact`
--

CREATE TABLE `tbl_contact` (
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` int(11) NOT NULL,
  `message` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_contact`
--

INSERT INTO `tbl_contact` (`fullname`, `email`, `contact`, `message`) VALUES
('yhianzy solis', 'yhianzypsolis@gmail.com', 2147483647, 'kamusta na kayo dyan nak'),
('kuya carl cymon', 'carlcymon29@gmail.com', 123123123, 'hoy kuya pajobo'),
('kuya carl cymon', 'yhianzy@gmail.com', 123123, 'yon dol ayos yan ah');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_register`
--

CREATE TABLE `tbl_register` (
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contactnumber` int(11) NOT NULL,
  `birth` date NOT NULL,
  `password` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip` int(11) NOT NULL,
  `status` enum('Active','Inactive') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Active',
  `role` enum('User','Admin') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'User',
  `profile_pic` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_register`
--

INSERT INTO `tbl_register` (`firstname`, `lastname`, `username`, `email`, `contactnumber`, `birth`, `password`, `city`, `zip`, `status`, `role`, `profile_pic`) VALUES
('carl', 'cymon', 'kuyamocarl', 'carlcymon29@gmail.com', 2147483647, '2026-03-09', '$2y$10$tYElMxVs/lhxy.UnRrqoMOJVRX5EmxJbhY0TPsc2cXIySC2n0Q77q', 'carmona', 4116, 'Active', 'User', ''),
('yhianzy', 'solis', 'yhianzy21', 'yhianzy@gmail.com', 123123123, '2026-03-09', '$2y$10$y2l5KE8Bfsb1rRekK95GSuWwSISYgiGCHEN1GKbbvHCguJFcnd7PK', 'carmona', 4116, 'Active', 'User', ''),
('boss', 'mo to', 'bossing', 'boss@gmail.com', 12321312, '2026-03-10', '$2y$10$wv2Fln7CLNIdUp0cPreYSeix9XRLrl1f3ycChyxjYoyi2UTGZJqnK', 'carmona', 4116, 'Active', 'User', ''),
('aasdasda', 'sadasdasd', 'boss', 'araymo@gmail.com', 123213, '2026-03-19', '$2y$10$fVFJyOATUg7htXLDB1jxkeO5rV7Y6jQUSw4cNlco3LP/3QjF9LVdm', 'carmona', 4116, 'Active', 'User', ''),
('admin', 'pogi', 'admin', 'admin@gmail.com', 2147483647, '2026-03-10', '$2y$10$wx/8NzkBWsSAjZDVPxRHWemU9WOwx2aqvn66R6OkRJS.aNbjsALsW', 'carmona', 4116, 'Active', 'User', '1774274666_1774271674_gang3.jpg'),
('admin', 'solis', 'admin', 'admin@gmail.com', 123213123, '2026-03-10', '$2y$10$ZIBtAoXSOxdbI3M1ZsTaie6QdQ9HDraynX4w6.cvZ52bdPuV0.djy', 'carmona', 4116, 'Active', 'User', '1774274666_1774271674_gang3.jpg'),
('admin', 'carl', 'admin', 'admin@gmail.com', 123213, '2026-03-10', '$2y$10$EMSAQ2ILUMcBbc6fDTUzTOM315Zj/j4E.KMHOdet3bg8zgZ2NAVhm', 'carmona', 4116, 'Active', 'User', '1774274666_1774271674_gang3.jpg'),
('admin', 'boy', 'admin', 'admin@gmail.com', 2147483647, '2026-03-10', '$2y$10$RXqk3NJRdGo/9PIE.gHhnubw2ibnwzDPWPV1EO3HKvCpCMKfgowHe', 'carmona', 4116, 'Active', 'User', '1774274666_1774271674_gang3.jpg'),
('borloloy', 'solis', 'borloloy', 'borloloy@gmail.com', 9123123, '0023-12-31', '$2y$10$All/kcsr9zZ6VLEbIUcNyuT1GBw50EGfFgk8PcZ1Ytgp2Yh8pB896', 'carmona', 4116, 'Active', 'User', '1774273918_gang2.jpg'),
('asdasd', 'asdasd', 'asdasd', 'asdasd@gmail.com', 9123123, '2332-12-31', '$2y$10$K1w9N7.EkZg7kvtmkWN78uXvIeMfrQCfha/xCk9u.pqMYMWcuqJYW', 'carmona', 123, 'Active', 'User', ''),
('qweqwe', 'qweqwe', 'qweqwe', 'qwe@gmail.com', 123123, '0000-00-00', '$2y$10$aNFOyEchrXo4rc43NbA8B.9H.CUfvxsG6ldHJPmuEuA8um3utDY6G', '123', 123, 'Active', 'User', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
