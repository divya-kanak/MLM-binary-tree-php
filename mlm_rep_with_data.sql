-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2020 at 05:09 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mlmbinary`
--

-- --------------------------------------------------------

--
-- Table structure for table `mlm_rep`
--

CREATE TABLE `mlm_rep` (
  `recordID` int(8) NOT NULL,
  `parentID` int(11) NOT NULL,
  `sponsorID` int(8) NOT NULL DEFAULT 0,
  `leg` int(1) NOT NULL DEFAULT 0,
  `repID` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mlm_rep`
--

INSERT INTO `mlm_rep` (`recordID`, `parentID`, `sponsorID`, `leg`, `repID`, `name`) VALUES
(1, 0, 0, 0, 'A', 'A'),
(2, 1, 0, 0, 'B', 'B'),
(3, 1, 0, 1, 'C', 'C'),
(4, 2, 1, 0, 'D', 'D'),
(5, 4, 0, 0, 'H', 'H'),
(6, 4, 0, 1, 'I', 'I'),
(7, 2, 0, 1, 'E', 'E'),
(8, 3, 0, 0, 'F', 'F'),
(9, 3, 0, 1, 'G', 'G'),
(10, 9, 0, 0, 'J', 'J'),
(11, 9, 1, 1, 'K', 'K');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mlm_rep`
--
ALTER TABLE `mlm_rep`
  ADD PRIMARY KEY (`recordID`),
  ADD UNIQUE KEY `repID` (`repID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mlm_rep`
--
ALTER TABLE `mlm_rep`
  MODIFY `recordID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
