-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2023 at 03:27 AM
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
-- Table structure for table `alternatives`
--

CREATE TABLE `alternatives` (
  `Alternative_ID` int(11) NOT NULL,
  `Alternative_Title` varchar(255) NOT NULL,
  `Decision_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `alternatives`
--

INSERT INTO `alternatives` (`Alternative_ID`, `Alternative_Title`, `Decision_ID`) VALUES
(7, 'A1', 11),
(8, 'A2', 11),
(9, 'A3', 11),
(10, 'A4', 11);

-- --------------------------------------------------------

--
-- Table structure for table `alternative_weight`
--

CREATE TABLE `alternative_weight` (
  `Weight_ID` int(11) NOT NULL,
  `Criteria_ID` int(11) NOT NULL,
  `Alternative_ID` int(11) NOT NULL,
  `Weight` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `alternative_weight`
--

INSERT INTO `alternative_weight` (`Weight_ID`, `Criteria_ID`, `Alternative_ID`, `Weight`) VALUES
(24, 9, 7, '5'),
(25, 10, 7, '5'),
(26, 11, 7, '5'),
(27, 12, 7, '3'),
(28, 13, 7, '3'),
(29, 9, 8, '2'),
(30, 10, 8, '4'),
(31, 11, 8, '3'),
(32, 12, 8, '3'),
(33, 13, 8, '3'),
(34, 9, 9, '3'),
(35, 10, 9, '5'),
(36, 11, 9, '5'),
(37, 12, 9, '3'),
(38, 13, 9, '5'),
(39, 9, 10, '4'),
(40, 10, 10, '4'),
(41, 11, 10, '3'),
(42, 12, 10, '3'),
(43, 13, 10, '3');

-- --------------------------------------------------------

--
-- Table structure for table `criterias`
--

CREATE TABLE `criterias` (
  `criteria_ID` int(11) NOT NULL,
  `Criteria_Title` varchar(255) NOT NULL,
  `Linguistic_Term` int(11) NOT NULL,
  `Decision_ID` int(11) NOT NULL,
  `Criteria_Importance` int(11) NOT NULL,
  `Criteria_Type` tinyint(4) NOT NULL COMMENT '1 =  benefit , 0 = cost'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `criterias`
--

INSERT INTO `criterias` (`criteria_ID`, `Criteria_Title`, `Linguistic_Term`, `Decision_ID`, `Criteria_Importance`, `Criteria_Type`) VALUES
(9, 'Harga', 1, 11, 5, 0),
(10, 'Mutu', 1, 11, 4, 0),
(11, 'Waktu', 1, 11, 4, 0),
(12, 'Keuangan', 1, 11, 4, 0),
(13, 'P3L', 1, 11, 3, 0);

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
(11, 'bro semen', 0, '2023-05-15 21:21:45', 0);

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
(1, 'Very Low', 'Low', 'Good', 'High', 'Very High'),
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
-- Indexes for table `alternatives`
--
ALTER TABLE `alternatives`
  ADD PRIMARY KEY (`Alternative_ID`);

--
-- Indexes for table `alternative_weight`
--
ALTER TABLE `alternative_weight`
  ADD PRIMARY KEY (`Weight_ID`);

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
-- AUTO_INCREMENT for table `alternatives`
--
ALTER TABLE `alternatives`
  MODIFY `Alternative_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `alternative_weight`
--
ALTER TABLE `alternative_weight`
  MODIFY `Weight_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `criterias`
--
ALTER TABLE `criterias`
  MODIFY `criteria_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `decisions`
--
ALTER TABLE `decisions`
  MODIFY `decision_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `linguistic_terms`
--
ALTER TABLE `linguistic_terms`
  MODIFY `Term_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
