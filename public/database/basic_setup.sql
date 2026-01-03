-- phpMyAdmin SQL Dump
-- version 4.9.10
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 01:37 PM
-- Server version: 8.0.41-0ubuntu0.20.04.1
-- PHP Version: 7.0.33-75+ubuntu20.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `basic_setup`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int NOT NULL,
  `client_unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `client_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contact_person` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pan_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `billing_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shifting_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gst_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_id` int DEFAULT NULL,
  `date` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `time` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int DEFAULT '0',
  `state` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `state_no` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bank_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pin` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `client_unit`, `client_name`, `contact_person`, `pan_no`, `billing_address`, `shifting_address`, `phone_no`, `gst_number`, `created_id`, `date`, `time`, `timestamp`, `deleted`, `state`, `state_no`, `bank_details`, `address1`, `location`, `pin`) VALUES
(1, 'Talegaon Unit', 'TEST TECHNOPLAST', 'MR. Suresh Kamat', 'BIZPB5715', 'S.no. 123/4, Near PCMC water tank, Whalekarwadi Road, Pimple Goan, Pune-411111  Email: xxxxaaa@yahoo.com   PH: 1234567890', 'Gat no.5648, House no 133, near Hotel, Pimple Road, Pune-411111', '1111111110', '11ABCDE2222FGHI', 3, '03-04-2024', '10:58:33', '2024-02-09 08:27:30', 0, 'MAHARASHTRA', '27', 'ICICI BANK - Ac.No. 1111', 'xxxxx, xxxxxxxxxxx, xxxxxxxxxxxxxxxxxxxxxxx', 'Chinchwad', '411111'),
(2, 'Akurdi Unit', 'TEST TECHNOPLAST', 'MR. Suresh Kamat', 'BIZPB5715', 'S.no. 123/4, Near PCMC water tank, Whalekarwadi Road, Pimple Goan, Pune-411111  Email: xxxxaaa@yahoo.com   PH: 1234567890', 'Gat no.5648, House no 133, near Hotel, Pimple Road, Pune-411111', '1111111110', '11ABCDE2222FGHI', 3, '21-04-2024', '06:53:03', '2024-02-09 10:29:41', 0, 'Maharashtra', '27', 'ICICI BANK 1111', 'xxxxx, xxxxxxxxxxx, xxxxxxxxxxxxxxxxxxxxxxx', 'Chinchwad', '411111');

-- --------------------------------------------------------

--
-- Table structure for table `config_setting`
--

CREATE TABLE `config_setting` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('check_box','input','date','file') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `company_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `config_setting`
--

INSERT INTO `config_setting` (`id`, `name`, `title`, `value`, `description`, `type`, `company_id`) VALUES
(9, 'company_logo', 'Company Logo', 'public/assets/images/data_collection_lgo.png', 'Company logo', 'file', 0),
(10, 'company_name', 'Company name', 'Data Collection', 'Company name', 'input', 0),
(11, 'company_fav_icon', 'Company fav icon', 'public/assets/img/favicon/favicon.png', 'Company fav icon', 'file', 0),
(12, 'login_attempt', 'Login attempt', '5', 'Login attempt', 'input', 0),
(13, 'menu_type', 'Menu Type', 'vertical', 'horizontal|vertical', 'input', 0),
(14, 'default_page_view_type', 'Default Page View Type for listing', '{\"User\":\"Grid\"}', 'Table/Grid', 'input', 0),
(15, 'smtp_user_name', 'SMTP User Name', 'mullaaarbaj10@gmail.com', 'SMTP User Name', 'input', 0),
(16, 'smtp_user_password', 'SMTP User Password', 'csoh fxfg hvfk egju', 'SMTP User Password', 'input', 0),
(17, 'company_email', 'Company Email', 'erp.system@gmail.com', 'Company Email', 'input', 0),
(18, 'password_link_expiry', 'Password Link Expiry', '10', 'Password Link Expiry In Minutes', 'input', 0),
(19, 'email_notification_enable', 'Email Notification Enable', 'Yes', 'Email Notification Enable', 'input', 0);

-- --------------------------------------------------------

--
-- Table structure for table `group_master`
--

CREATE TABLE `group_master` (
  `group_master_id` int NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `group_code` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group_master`
--

INSERT INTO `group_master` (`group_master_id`, `group_name`, `group_code`, `status`) VALUES
(1, 'Admin', 'Admin', 'Active'),
(2, 'AROM', 'AROM', 'Active'),
(5, 'Purchase', 'purchase', 'Active'),
(6, 'Sales', 'sales', 'Active'),
(7, 'Quality', 'quality', 'Active'),
(13, 'Super Admin', 'super_admin', 'Active'),
(14, 'Super Admin2', 'super_adminw', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `group_rights`
--

CREATE TABLE `group_rights` (
  `group_rights_id` int NOT NULL,
  `group_master_id` int NOT NULL,
  `menu_master_id` int NOT NULL,
  `list` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'No',
  `add` enum('Yes','No') NOT NULL DEFAULT 'No',
  `update` enum('Yes','No') NOT NULL DEFAULT 'No',
  `delete` enum('Yes','No') NOT NULL DEFAULT 'No',
  `export` enum('Yes','No') NOT NULL DEFAULT 'No',
  `import` enum('Yes','No') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group_rights`
--

INSERT INTO `group_rights` (`group_rights_id`, `group_master_id`, `menu_master_id`, `list`, `add`, `update`, `delete`, `export`, `import`) VALUES
(26, 1, 1, 'No', 'No', 'No', 'Yes', 'No', 'No'),
(27, 1, 2, 'Yes', 'No', 'Yes', 'No', 'No', 'No'),
(28, 1, 3, 'Yes', 'No', 'Yes', 'No', 'No', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `menu_category`
--

CREATE TABLE `menu_category` (
  `menu_category_id` int NOT NULL,
  `menu_category_code` varchar(255) NOT NULL,
  `menu_category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu_category`
--

INSERT INTO `menu_category` (`menu_category_id`, `menu_category_code`, `menu_category_name`) VALUES
(1, 'user_managemnet', 'User Management'),
(2, 'purchase', 'Purchase');

-- --------------------------------------------------------

--
-- Table structure for table `menu_master`
--

CREATE TABLE `menu_master` (
  `menu_master_id` int NOT NULL,
  `menu_category_id` int NOT NULL,
  `diaplay_name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu_master`
--

INSERT INTO `menu_master` (`menu_master_id`, `menu_category_id`, `diaplay_name`, `url`, `status`) VALUES
(1, 1, 'User', 'user_list', 'Inactive'),
(2, 1, 'Group Master', 'group_master', 'Inactive'),
(3, 2, 'Sitemap', 'sitemap', 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

CREATE TABLE `userinfo` (
  `id` int NOT NULL,
  `user_email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `user_role` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `added_date` datetime DEFAULT NULL,
  `added_by` int NOT NULL,
  `deleted` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `unit_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `groups` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `login_attempt` int NOT NULL DEFAULT '0',
  `status` enum('Active','Inactive','Block') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userinfo`
--

INSERT INTO `userinfo` (`id`, `user_email`, `user_role`, `user_name`, `user_password`, `added_date`, `added_by`, `deleted`, `unit_ids`, `groups`, `login_attempt`, `status`) VALUES
(1, 'mullaaarbaj10@gmail.com', 'Admin', 'Aarbaj Mulla', 'Test@123', '2024-11-19 12:41:29', 3, NULL, '1,2', '1', 3, 'Active'),
(2, 'mullajuned0@gmail.com', 'Admin', 'Juned Mulla', '123456', '2024-11-19 12:42:40', 3, '0', '1,2', '1', 0, 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config_setting`
--
ALTER TABLE `config_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_master`
--
ALTER TABLE `group_master`
  ADD PRIMARY KEY (`group_master_id`);

--
-- Indexes for table `group_rights`
--
ALTER TABLE `group_rights`
  ADD PRIMARY KEY (`group_rights_id`);

--
-- Indexes for table `menu_category`
--
ALTER TABLE `menu_category`
  ADD PRIMARY KEY (`menu_category_id`);

--
-- Indexes for table `menu_master`
--
ALTER TABLE `menu_master`
  ADD PRIMARY KEY (`menu_master_id`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `config_setting`
--
ALTER TABLE `config_setting`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `group_master`
--
ALTER TABLE `group_master`
  MODIFY `group_master_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `group_rights`
--
ALTER TABLE `group_rights`
  MODIFY `group_rights_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `menu_category`
--
ALTER TABLE `menu_category`
  MODIFY `menu_category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu_master`
--
ALTER TABLE `menu_master`
  MODIFY `menu_master_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `userinfo`
--
ALTER TABLE `userinfo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
