-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 24, 2024 at 12:29 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `record`
--

-- --------------------------------------------------------

--
-- Table structure for table `studentrecord`
--

DROP TABLE IF EXISTS `studentrecord`;
CREATE TABLE IF NOT EXISTS `studentrecord` (
  `id` int NOT NULL AUTO_INCREMENT,
  `f_name` varchar(55) NOT NULL,
  `l_name` varchar(55) NOT NULL,
  `age` int NOT NULL,
  `emailId` varchar(55) NOT NULL,
  `phone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gender` varchar(55) NOT NULL,
  `userimage` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `studentrecord`
--

INSERT INTO `studentrecord` (`id`, `f_name`, `l_name`, `age`, `emailId`, `phone`, `gender`, `userimage`) VALUES
(91, 'Aman', 'Kondal', 23, 'amandavid9956@gmail.com', '44848487', '', ''),
(92, 'Aman', 'Kondal', 23, 'amandavid9956@gmail.com', '44848487', 'male', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
