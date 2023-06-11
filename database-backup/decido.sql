-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2023 at 02:56 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alternatives`
--

INSERT INTO `alternatives` (`Alternative_ID`, `Alternative_Title`, `Decision_ID`) VALUES
(1, 'Alternative 1', 1),
(2, 'alternative 2', 1),
(3, 'Alternative3', 1),
(4, 'qwewqe', 1),
(5, '123', 2),
(6, 'alternative123', 3),
(7, 'A1', 11),
(8, 'A2', 11),
(9, 'A3', 11),
(10, 'A4', 11),
(11, 'anjung emang', 1),
(12, 'gold', 6),
(13, 'stock', 6),
(14, 'crypto', 6),
(15, 'Alt 1', 13),
(16, 'Alt 2', 13),
(17, 'Alt 3', 13),
(18, 'Alt 4', 13),
(19, 'haloo', 22),
(20, '123', 4),
(21, '123', 4),
(22, 'halo', 4),
(23, '123123', 4),
(24, 'halo bang', 4),
(27, 'Mantap bang dd', 18),
(32, 'AJGG', 18);

-- --------------------------------------------------------

--
-- Table structure for table `alternative_weight`
--

CREATE TABLE `alternative_weight` (
  `Weight_ID` int(11) NOT NULL,
  `Criteria_ID` int(11) NOT NULL,
  `Alternative_ID` int(11) NOT NULL,
  `Weight` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alternative_weight`
--

INSERT INTO `alternative_weight` (`Weight_ID`, `Criteria_ID`, `Alternative_ID`, `Weight`) VALUES
(1, 2, 1, '1'),
(2, 3, 1, '4'),
(3, 4, 1, '3'),
(4, 1, 5, '2'),
(5, 2, 2, '1'),
(6, 3, 2, '3'),
(7, 4, 2, '3'),
(8, 5, 2, '1'),
(9, 8, 2, '3'),
(10, 5, 1, '3'),
(11, 8, 1, '4'),
(12, 2, 3, '3'),
(13, 3, 3, '5'),
(14, 4, 3, '2'),
(15, 5, 3, '3'),
(16, 8, 3, '3'),
(17, 2, 4, '2'),
(18, 3, 4, '3'),
(19, 4, 4, '2'),
(20, 5, 4, '2'),
(21, 8, 4, '3'),
(22, 6, 6, '3'),
(23, 7, 6, '3'),
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
(43, 13, 10, '3'),
(44, 2, 11, '4'),
(45, 3, 11, '3'),
(46, 4, 11, '1'),
(47, 5, 11, '2'),
(48, 8, 11, '2'),
(49, 15, 12, '1'),
(50, 16, 12, '4'),
(51, 17, 12, '3'),
(52, 15, 13, '3'),
(53, 15, 14, '5'),
(54, 16, 14, '2'),
(55, 16, 13, '5'),
(56, 17, 13, '3'),
(57, 17, 14, '4'),
(58, 22, 15, '5'),
(59, 23, 15, '3'),
(60, 24, 15, '4'),
(61, 22, 16, '3'),
(62, 23, 16, '2'),
(63, 24, 16, '5'),
(64, 22, 17, '4'),
(65, 23, 17, '1'),
(66, 24, 17, '3'),
(67, 22, 18, '2'),
(68, 24, 18, '2'),
(69, 23, 18, '4'),
(70, 25, 19, '1'),
(71, 9, 25, '1'),
(72, 27, 5, '2'),
(73, 28, 26, '1'),
(74, 9, 33, '1');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `criterias`
--

INSERT INTO `criterias` (`criteria_ID`, `Criteria_Title`, `Linguistic_Term`, `Decision_ID`, `Criteria_Importance`, `Criteria_Type`) VALUES
(1, '123123', 1, 2, 3, 0),
(2, 'Eat shit broo', 8, 1, 5, 0),
(4, 'criteria 1', 1, 1, 2, 0),
(5, 'tesf', 1, 1, 3, 0),
(8, 'new criteria', 1, 1, 5, 0),
(9, 'Harga', 1, 11, 5, 0),
(10, 'Mutu', 1, 11, 4, 0),
(11, 'Waktu', 1, 11, 4, 0),
(12, 'Keuangan', 1, 11, 4, 0),
(13, 'P3L', 1, 11, 3, 0),
(14, 'hellow', 1, 8, 3, 0),
(15, 'fast', 3, 6, 4, 0),
(16, 'value', 4, 6, 5, 0),
(17, 'price', 3, 6, 5, 0),
(18, 'E1', 1, 12, 0, 0),
(19, 'E2', 1, 12, 0, 0),
(20, 'E3', 1, 12, 0, 0),
(21, 'E4', 1, 12, 0, 0),
(22, 'Weather', 1, 13, 5, 0),
(23, 'Cost', 1, 13, 2, 0),
(24, 'Activity', 1, 13, 5, 0),
(25, 'qwertfgh', 1, 22, 3, 0),
(26, 'haloo21341423423424', 1, 4, 4, 0),
(27, '123', 1, 2, 1, 0),
(28, 'New Criteria', 1, 18, 1, 0),
(30, 'taii', 1, 3, 3, 0),
(31, '123213', 1, 3, 2, 0),
(32, '123123', 1, 3, 1, 0),
(33, 'anak asd asdsaa dadlasda das', 1, 3, 3, 0),
(34, 'wkwkasadas', 1, 3, 0, 0),
(35, '23123', 1, 3, 0, 0),
(36, '23123', 1, 3, 0, 0),
(37, '23123', 1, 3, 0, 0),
(38, '23123', 1, 3, 0, 0),
(39, 'baru', 1, 3, 0, 0),
(40, '123', 1, 3, 0, 0),
(41, 'JUANSDASD', 1, 3, 0, 0),
(42, '1231', 1, 3, 0, 0),
(43, 'GACORR', 1, 3, 0, 1),
(44, 'GACOR BANG', 1, 3, 0, 0),
(45, '12321', 1, 3, 0, 0),
(46, 'uhuhj', 1, 3, 0, 0),
(47, '123', 1, 3, 0, 0),
(48, 'halo', 1, 3, 0, 0),
(49, '123', 1, 3, 0, 0),
(50, '123', 1, 3, 0, 0),
(51, 'JUAN', 1, 3, 0, 0),
(52, '', 1, 3, 0, 0),
(53, 'hello', 1, 3, 0, 0),
(54, '123', 1, 27, 0, 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `decisions`
--

INSERT INTO `decisions` (`decision_ID`, `decision_Title`, `decision_Status`, `decision_Date`, `UserID`) VALUES
(2, 'college', 0, '2023-04-25 01:05:13', 1),
(3, 'thesis topic', 0, '2023-04-25 01:05:42', 1),
(4, 'BARU BANG 323', 0, '2023-04-25 01:05:50', 1),
(5, 'juan2', 0, '2023-04-25 03:14:22', 1),
(6, 'call', 0, '2023-04-25 16:24:34', 1),
(7, 'asd', 0, '2023-04-25 16:45:16', 1),
(8, 'test', 0, '2023-04-25 20:46:11', 1),
(9, '123', 0, '2023-05-01 21:16:37', 0),
(10, '3213', 0, '2023-05-02 01:16:02', 0),
(11, 'bro semen', 0, '2023-05-15 21:21:45', 0),
(12, 'cek vikor2', 0, '2023-05-26 14:22:35', 0),
(13, 'Vacation', 0, '2023-06-02 10:48:35', 0),
(17, 'asdasd', 0, '2023-06-06 11:02:45', 0),
(18, 'asd', 0, '2023-06-06 11:02:48', 0),
(19, '21', 0, '2023-06-06 11:02:53', 0),
(20, '123', 0, '2023-06-06 11:03:51', 0),
(21, '123', 0, '2023-06-06 11:03:55', 0),
(22, '123', 0, '2023-06-06 11:04:08', 0),
(26, 'Decison 1', 0, '2023-06-06 22:22:27', 0),
(27, 'deasd1', 0, '2023-06-06 22:22:40', 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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









