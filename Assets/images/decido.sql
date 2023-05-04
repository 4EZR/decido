-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2023 at 01:08 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `decido`
--

-- --------------------------------------------------------

--
-- Table structure for table `criterias`
--

CREATE TABLE `criterias` (
  `criteria_ID` int(11) NOT NULL,
  `criteria_Title` varchar(255) NOT NULL,
  `linguistic_term` int(11) NOT NULL,
  `Decision_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `decisions`
--

CREATE TABLE `decisions` (
  `decision_ID` int(11) NOT NULL,
  `decision_Title` varchar(255) NOT NULL,
  `decision_Status` tinyint(1) DEFAULT 0,
  `decision_Date` datetime NOT NULL DEFAULT current_timestamp(),
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `decisions`
--

INSERT INTO `decisions` (`decision_ID`, `decision_Title`, `decision_Status`, `decision_Date`, `UserID`) VALUES
(1, 'eat', 0, '2023-04-25 00:42:48', 1),
(2, 'college', 0, '2023-04-25 01:05:13', 1),
(3, 'thesis topic', 0, '2023-04-25 01:05:42', 1),
(4, 'asdasd', 0, '2023-04-25 01:05:50', 1),
(5, 'juan', 0, '2023-04-25 03:14:22', 1),
(6, 'call', 0, '2023-04-25 16:24:34', 1),
(7, 'asd', 0, '2023-04-25 16:45:16', 1),
(8, 'test', 0, '2023-04-25 20:46:11', 1),
(9, '123', 0, '2023-05-01 21:16:37', 0),
(10, '3213', 0, '2023-05-02 01:16:02', 0);

-- --------------------------------------------------------

--
-- Table structure for table `linguistic_terms`
--

CREATE TABLE `linguistic_terms` (
  `Term_ID` int(11) NOT NULL,
  `TermLevel_1` varchar(255) NOT NULL,
  `TermLevel_2` varchar(255) NOT NULL,
  `TermLevel_3` varchar(255) NOT NULL,
  `TermLevel_4` varchar(255) NOT NULL,
  `TermLevel_5` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `linguistic_terms`
--

INSERT INTO `linguistic_terms` (`Term_ID`, `TermLevel_1`, `TermLevel_2`, `TermLevel_3`, `TermLevel_4`, `TermLevel_5`) VALUES
(1, 'very short', 'short', 'moderate', 'long', 'very long'),
(2, 'rarely', 'occasionally', 'sometimes', 'often', 'frequently'),
(3, 'very poor', 'poor', 'moderate', 'good', 'very good'),
(4, 'very low', 'low', 'moderate', 'high', 'very high'),
(5, 'unacceptable', 'poor', 'fair', 'good', 'excellent'),
(6, 'very short', 'short', 'moderate', 'long', 'very long'),
(7, 'far', 'quite far', 'moderately far', 'quite near', 'near'),
(8, 'very cold', 'cold', 'moderate', 'warm', 'very warm'),
(9, 'very small', 'small', 'moderate', 'large', 'very large'),
(10, 'very weak', 'weak', 'moderate', 'strong', 'very strong');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `criterias`
--
ALTER TABLE `criterias`
  ADD PRIMARY KEY (`criteria_ID`);

--
-- Indexes for table `decisions`
--
ALTER TABLE `decisions`
  ADD PRIMARY KEY (`decision_ID`);

--
-- Indexes for table `linguistic_terms`
--
ALTER TABLE `linguistic_terms`
  ADD PRIMARY KEY (`Term_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `criterias`
--
ALTER TABLE `criterias`
  MODIFY `criteria_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `decisions`
--
ALTER TABLE `decisions`
  MODIFY `decision_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `linguistic_terms`
--
ALTER TABLE `linguistic_terms`
  MODIFY `Term_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
