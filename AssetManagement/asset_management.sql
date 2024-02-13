-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2023 at 04:52 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asset_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `tid` int(11) NOT NULL,
  `qr_and_bar_code_number` varchar(255) NOT NULL,
  `rfid_or_id` varchar(255) NOT NULL,
  `asset` varchar(255) NOT NULL,
  `subnumber` varchar(255) NOT NULL,
  `fa` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `asset_class` varchar(255) NOT NULL,
  `asset_status_1` varchar(255) NOT NULL,
  `profit_center` varchar(255) NOT NULL,
  `as_per_sap` varchar(255) NOT NULL,
  `outlet_type` varchar(255) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `opening_date` varchar(255) NOT NULL,
  `asset_status_2` varchar(255) NOT NULL,
  `asset_block` varchar(255) NOT NULL,
  `asset_brand` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `location_id` int(11) NOT NULL DEFAULT 0,
  `asset_description` varchar(255) NOT NULL,
  `asset_name` varchar(255) NOT NULL,
  `type_of_Assets` varchar(255) NOT NULL,
  `capitalized_on` varchar(255) NOT NULL,
  `life` varchar(255) NOT NULL,
  `balance_life` varchar(255) NOT NULL,
  `days` varchar(255) NOT NULL,
  `opening` varchar(255) NOT NULL,
  `cwip_capitalized` varchar(255) NOT NULL,
  `add_asset` varchar(255) NOT NULL,
  `transfer` varchar(255) NOT NULL,
  `del` varchar(255) NOT NULL,
  `w_off` varchar(255) NOT NULL,
  `net_block` varchar(255) NOT NULL,
  `dep_fy_start` varchar(255) NOT NULL,
  `dep_transfer` varchar(255) NOT NULL,
  `dep_for_the_year` varchar(255) NOT NULL,
  `dep_retir` varchar(255) NOT NULL,
  `accumul_dep` varchar(255) NOT NULL,
  `opening_impairment` varchar(255) NOT NULL,
  `impairment_transfer` varchar(255) NOT NULL,
  `impairment_charges` varchar(255) NOT NULL,
  `impairment_charges2` varchar(255) NOT NULL,
  `imapirment_reversal` varchar(255) NOT NULL,
  `accumul_impairment` varchar(255) NOT NULL,
  `Curr_bk_val` varchar(255) NOT NULL,
  `wdv_after_impairment` varchar(255) NOT NULL,
  `opening_wdv` varchar(255) NOT NULL,
  `opening_wdv_after_impairment` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `deactivation_on` varchar(255) NOT NULL,
  `life_used` varchar(255) NOT NULL,
  `dep` varchar(255) NOT NULL,
  `historical_wdv` varchar(255) NOT NULL,
  `data_exist` tinyint(4) NOT NULL,
  `rfid_read_status` tinyint(4) NOT NULL,
  `qr_read_status` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_dt` datetime NOT NULL,
  `rfid_read_by` int(11) DEFAULT NULL,
  `rfid_read_dt` datetime DEFAULT NULL,
  `qr_read_by` int(11) DEFAULT NULL,
  `qr_read_dt` datetime DEFAULT NULL,
  `moved_status` tinytext NOT NULL DEFAULT '0' COMMENT '0 = not moved \r\n1 = moved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `audit_management`
--

CREATE TABLE `audit_management` (
  `aid` int(11) NOT NULL,
  `audit_name` varchar(50) NOT NULL,
  `location_id` int(11) NOT NULL,
  `end_date` datetime NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_dt` datetime NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_dt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `company_management`
--

CREATE TABLE `company_management` (
  `cid` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_dt` datetime NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_dt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `device_management`
--

CREATE TABLE `device_management` (
  `id` int(11) NOT NULL,
  `device_name` varchar(150) NOT NULL,
  `device_id` varchar(150) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_dt` datetime NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_dt` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `location_management`
--

CREATE TABLE `location_management` (
  `pid` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `location_name` varchar(150) NOT NULL,
  `status` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_dt` datetime NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_dt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loginactivity`
--

CREATE TABLE `loginactivity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_login` datetime NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `login_agent` varchar(255) NOT NULL,
  `platform` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `log_table`
--

CREATE TABLE `log_table` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `rfid_or_id` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = not exist\r\n1 = exist'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tag_limit`
--

CREATE TABLE `tag_limit` (
  `id` int(11) NOT NULL,
  `total_limit` int(11) NOT NULL,
  `totel_scanned` int(11) NOT NULL DEFAULT 0,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tag_limit`
--

INSERT INTO `tag_limit` (`id`, `total_limit`, `totel_scanned`, `modified_by`, `modified_at`) VALUES
(1, 150, 0, 1, '2022-09-28 10:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `user_management`
--

CREATE TABLE `user_management` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` char(2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `location_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_dt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_management`
--

INSERT INTO `user_management` (`user_id`, `first_name`, `last_name`, `phone_number`, `password`, `user_type`, `status`, `last_login`, `location_id`, `created_by`, `created_dt`, `modified_by`, `modified_dt`) VALUES
(1, 'Admin', 'Singh', '1234567890', '21232f297a57a5a743894a0e4a801fc3', 's', 1, '2023-03-01 10:41:11', 1, 1, '2019-09-13 21:56:49', NULL, NULL),
(18, 'Deepu', 'Bhasin', '9915099247', 'e10adc3949ba59abbe56e057f20f883e', 'e', 1, '2022-10-01 10:13:37', 2, 1, '2022-08-27 22:50:36', 1, '2023-02-07 22:04:48'),
(19, 'Sarbdeep', 'Singh', '1122334455', 'e10adc3949ba59abbe56e057f20f883e', 'e', 1, '2022-09-29 11:40:10', 1, 1, '2022-09-06 10:39:29', NULL, NULL),
(20, 'Deepu', 'Bhasin', '554411', 'e10adc3949ba59abbe56e057f20f883e', 'e', 1, NULL, 3, 1, '2023-02-07 21:39:05', 1, '2023-02-07 22:07:47'),
(21, 'Deepinder', 'Singh', '123', 'e10adc3949ba59abbe56e057f20f883e', 'a', 1, NULL, 1, 1, '2023-02-28 10:21:27', NULL, NULL),
(22, 'pankaj', 'singh', '11', 'e10adc3949ba59abbe56e057f20f883e', 'a', 1, NULL, 2, 1, '2023-02-28 10:21:46', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `rfid_or_id_index` (`rfid_or_id`),
  ADD KEY `qr_and_bar_code_number_index` (`qr_and_bar_code_number`);

--
-- Indexes for table `audit_management`
--
ALTER TABLE `audit_management`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `company_management`
--
ALTER TABLE `company_management`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `device_management`
--
ALTER TABLE `device_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_management`
--
ALTER TABLE `location_management`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `loginactivity`
--
ALTER TABLE `loginactivity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_table`
--
ALTER TABLE `log_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tag_limit`
--
ALTER TABLE `tag_limit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_management`
--
ALTER TABLE `user_management`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_management`
--
ALTER TABLE `audit_management`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_management`
--
ALTER TABLE `company_management`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `device_management`
--
ALTER TABLE `device_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_management`
--
ALTER TABLE `location_management`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loginactivity`
--
ALTER TABLE `loginactivity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_table`
--
ALTER TABLE `log_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tag_limit`
--
ALTER TABLE `tag_limit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_management`
--
ALTER TABLE `user_management`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
