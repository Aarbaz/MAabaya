-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2023 at 07:04 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pma`
--

-- --------------------------------------------------------

--
-- Table structure for table `balance`
--

CREATE TABLE `balance` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `bill_type` char(12) NOT NULL,
  `bill_no` varchar(255) NOT NULL,
  `total_bill` decimal(10,2) DEFAULT NULL,
  `paid_bill` decimal(10,2) DEFAULT NULL,
  `balance_bill` decimal(10,2) DEFAULT NULL,
  `updated_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `balance`
--

INSERT INTO `balance` (`id`, `customer_id`, `bill_type`, `bill_no`, `total_bill`, `paid_bill`, `balance_bill`, `updated_on`) VALUES
(4, 1, 'challan', 'CH001', '210.00', '10.00', '90.00', '2019-10-17 07:23:02'),
(3, 1, 'challan', 'CH001', '210.00', '110.00', '100.00', '2019-10-16 08:13:52'),
(5, 1, 'challan', 'CH001', '210.00', '10.00', '90.00', '2019-10-19 08:19:52'),
(6, 1, 'challan', 'CH001', '210.00', '10.00', '90.00', '2019-10-19 08:20:36'),
(7, 1, 'challan', 'CH001', '210.00', '10.00', '90.00', '2019-10-19 08:22:51');

-- --------------------------------------------------------

--
-- Table structure for table `challan_bills`
--

CREATE TABLE `challan_bills` (
  `sr_no` int(11) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `challan_no` varchar(10) NOT NULL,
  `material` varchar(255) NOT NULL,
  `qnty` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `total` decimal(10,0) NOT NULL,
  `paid` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `total_in_words` text NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `challan_bills`
--

INSERT INTO `challan_bills` (`sr_no`, `customer_id`, `challan_no`, `material`, `qnty`, `rate`, `amount`, `total`, `paid`, `balance`, `total_in_words`, `created_on`) VALUES
(13, '1', 'CH001', 'ACID PAN,PAV FLAVOR', '1,2', '210.00,120.00', '210.00,240.00', '450', '0.00', '450.00', ' Four Hundred  Fifty', '2019-10-27 23:36:23'),
(14, '2', 'CH002', 'PAV FLAVOR,ACID PAN', '2,2', '120.00,210.00', '240.00,420.00', '660', '0.00', '660.00', ' Six Hundred  Sixty', '2019-10-27 23:49:08'),
(15, '2', 'CH003', 'ACID PAN,PAV FLAVOR', '1,2', '210.00,120.00', '210.00,240.00', '450', '0.00', '450.00', ' Four Hundred  Fifty', '2019-10-28 08:13:29'),
(16, '1', 'CH004', 'ACID PAN,PAV FLAVOR,PAV GHEE FLAVOR,MASKA BUTTER', '1,2,1,1', '210.00,120.00,70.00,90.00', '210.00,240.00,70.00,90.00', '610', '0.00', '610.00', ' Six Hundred Ten', '2019-10-28 08:27:03'),
(17, '2', 'CH005', 'ACID PAN,PAV GHEE FLAVOR', '1,2', '210.00,70.00', '210.00,140.00', '350', '0.00', '350.00', ' Three Hundred  Fifty', '2019-10-28 08:54:11'),
(18, '2', 'CH006', 'PAV GHEE FLAVOR', '2', '70.00', '140.00', '140', '0.00', '140.00', ' One Hundred  Forty', '2019-10-28 09:00:49'),
(19, '1', 'CH007', 'PAV FLAVOR', '1', '120.00', '120.00', '120', '0.00', '120.00', ' One Hundred  Twenty', '2019-10-28 09:05:52'),
(20, '1', 'CH008', 'PAV GHEE FLAVOR', '2', '70.00', '140.00', '140', '0.00', '140.00', ' One Hundred  Forty', '2019-10-28 09:06:42'),
(21, '1', 'CH009', 'ACID PAN', '2', '210.00', '420.00', '420', '0.00', '420.00', ' Four Hundred  Twenty', '2021-09-19 18:56:00');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `bakery_name` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `owner_phone` varchar(255) NOT NULL,
  `owner_email` varchar(255) NOT NULL,
  `bakery_gst` varchar(30) NOT NULL,
  `bakery_address` text NOT NULL,
  `bakery_area` varchar(255) NOT NULL,
  `bakery_city` varchar(255) NOT NULL,
  `last_amount` decimal(10,2) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_ledger_balance`
--

CREATE TABLE `customer_ledger_balance` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `hsn` varchar(100) NOT NULL,
  `batch_no` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `rate` varchar(150) NOT NULL,
  `invoice` varchar(20) DEFAULT NULL,
  `challan` varchar(20) DEFAULT NULL,
  `customer` int(11) NOT NULL,
  `last_amount` decimal(10,2) NOT NULL,
  `bill_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `new_amount` decimal(10,2) NOT NULL,
  `payment_mode` varchar(20) NOT NULL,
  `transaction_no` varchar(100) NOT NULL,
  `cheque_no` varchar(10) NOT NULL,
  `dated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer_ledger_balance`
--

INSERT INTO `customer_ledger_balance` (`id`, `product_name`, `hsn`, `batch_no`, `quantity`, `rate`, `invoice`, `challan`, `customer`, `last_amount`, `bill_amount`, `paid_amount`, `new_amount`, `payment_mode`, `transaction_no`, `cheque_no`, `dated`) VALUES
(2, 'ACID PAN,PAV FLAVOR', '1901,1901', 'BCA,ABC', '1,1', '50,120.00', NULL, 'ch12', 1, '700.00', '170.00', '200.00', '670.00', 'Cheque', '', '123456', '2019-10-19 22:07:20'),
(3, 'ACID PAN', '', '', '', '', NULL, NULL, 1, '670.00', '0.00', '270.00', '400.00', 'Cash', '', '', '2019-10-19 22:08:40'),
(4, '', '', '', '', '', NULL, NULL, 1, '400.00', '0.00', '100.00', '300.00', 'IMPS', 'sw3456', '', '2018-10-19 22:38:58'),
(5, 'PAV FLAVOR', '1901', 'ABC', '4', '50', 'AB123', NULL, 2, '620.00', '200.00', '100.00', '720.00', 'Cheque', '', '123456', '2019-10-19 22:54:51'),
(6, 'MASKA BUTTER', '', '', '', '', NULL, NULL, 1, '300.00', '200.00', '100.00', '400.00', 'Cash', '', '', '2021-09-26 23:40:04'),
(7, '', '', '', '', '', NULL, NULL, 1, '400.00', '0.00', '300.00', '100.00', 'Cash', '', '', '2021-09-26 23:41:07');

-- --------------------------------------------------------

--
-- Table structure for table `insider_bill`
--

CREATE TABLE `insider_bill` (
  `sr_no` int(11) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `customer_address` varchar(255) NOT NULL,
  `buyer_gst` varchar(32) NOT NULL,
  `invoice_no` varchar(10) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `hsn` text NOT NULL,
  `stk` varchar(32) NOT NULL,
  `qnty` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `transport_charges` decimal(8,0) DEFAULT NULL,
  `other_charge` decimal(8,0) DEFAULT NULL,
  `total_taxable_amount` decimal(10,0) NOT NULL,
  `igst_5_cent` decimal(10,0) DEFAULT NULL,
  `cgst_2_5_cent` decimal(8,0) DEFAULT NULL,
  `sgst_2_5_cent` decimal(8,0) DEFAULT NULL,
  `total` decimal(10,0) NOT NULL,
  `round_off_total` decimal(10,0) NOT NULL,
  `total_in_words` text NOT NULL,
  `date_of_supply` varchar(255) NOT NULL,
  `place_of_supply` varchar(255) NOT NULL,
  `other_notes` tinytext DEFAULT NULL,
  `paid` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `invoice_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `insider_bill`
--

INSERT INTO `insider_bill` (`sr_no`, `customer_id`, `customer_address`, `buyer_gst`, `invoice_no`, `product_name`, `hsn`, `stk`, `qnty`, `rate`, `amount`, `transport_charges`, `other_charge`, `total_taxable_amount`, `igst_5_cent`, `cgst_2_5_cent`, `sgst_2_5_cent`, `total`, `round_off_total`, `total_in_words`, `date_of_supply`, `place_of_supply`, `other_notes`, `paid`, `balance`, `invoice_date`) VALUES
(11, '1', '', '', 'INV001', 'ACID PAN', '', '1901', '1', '571.43', '571.43', '0', '0', '571', '0', '0', '0', '571', '571', ' Five Hundred and Seventy One', '', '', '', '0.00', '571.00', '2019-10-14 09:06:32'),
(12, '2', '', '', 'INV002', 'ACID PAN,PAV FLAVOR', '', '1901,1901', '1,2', '210.00,120.00', '210.00,240.00', '0', '0', '450', '0', '11', '11', '473', '473', ' Four Hundred and Seventy Three', '', '', '', '0.00', '473.00', '2019-10-19 09:44:42'),
(13, '1', '', '', 'INV003', 'PAV FLAVOR', '', '1901', '1', '190.48', '190.48', '0', '0', '190', '0', '5', '5', '200', '200', ' Two Hundred ', '', '', '', '0.00', '200.00', '2019-10-28 09:10:54'),
(14, '2', '', '', 'INV004', 'PAV GHEE FLAVOR', '', '1901', '1', '190.48', '190.48', '10', '20', '220', '0', '6', '6', '232', '232', ' Two Hundred and Thirty Two', '', '', '', '0.00', '232.00', '2019-10-28 18:51:47'),
(15, '2', '', '', 'INV005', 'ACID PAN,PAV GHEE FLAVOR', '', '1901,1901', '2,1', '210.00,70.00', '420.00,70.00', '10', '20', '520', '0', '13', '13', '546', '546', ' Five Hundred and Forty Six', '2019-10-27', 'Bhiwandi', 'Thaks for business', '0.00', '546.00', '2019-10-28 18:57:30'),
(16, '1', '', '', 'INV006', 'ACID PAN', '', '1901', '2', '210.00', '420.00', '50', '0', '470', '85', '0', '0', '470', '470', ' Four Hundred  Seventy', '2021-09-20', '', '', '0.00', '470.00', '2021-09-19 18:27:33'),
(17, '1', '', '', 'INV007', 'SAD', '', '', '2', '60.00', '120.00', '50', '0', '170', '0', '0', '0', '170', '170', ' One Hundred  Seventy', '2021-09-21', '', '', '0.00', '170.00', '2021-09-22 22:47:07'),
(18, '1', '', '', 'INV008', 'PAV GHEE FLAVOR', '', '', '', '', '', '0', '0', '0', '0', '0', '0', '0', '0', ' Two Hundred  Forty', '', '', '', '0.00', '0.00', '2021-09-26 23:34:29'),
(19, '1', '', '', 'INV009', '', '', '', '2', '50.00,50.00', '100.00', NULL, '0', '100', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '', '', '', '0.00', '0.00', '2021-10-02 19:51:58'),
(20, '1', '', '', 'INV010', '', '', '', '2', '50.00,50.00', '', NULL, '0', '0', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '', '', '', '0.00', '0.00', '2021-10-02 19:52:14'),
(21, '1', '', '', 'INV011', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '0', '2', '2', '124', '124', ' One Hundred and Twenty Four', '', '', NULL, '0.00', '124.00', '2021-10-02 20:09:16'),
(22, '1', '', '', 'INV011', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '0', '2', '2', '124', '124', ' One Hundred and Twenty Four', '', '', NULL, '0.00', '124.00', '2021-10-02 20:32:26'),
(23, '1', '', '', 'INV011', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '0', '2', '2', '124', '124', ' One Hundred and Twenty Four', '', '', NULL, '0.00', '124.00', '2021-10-02 20:33:21'),
(24, '1', '', '', 'INV012', 'COLD SYRUP', '', '', '2', '50.00,50.00', '', '10', '10', '0', '0', '0', '0', '0', '0', ' One Hundred and Twenty Four', '', '', NULL, '0.00', '0.00', '2021-10-02 20:46:40'),
(25, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:05:34'),
(26, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:07:17'),
(27, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:08:49'),
(28, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:10:16'),
(29, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:10:30'),
(30, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:32:49'),
(31, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:33:21'),
(32, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:33:30'),
(33, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:35:22'),
(34, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:35:44'),
(35, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:36:17'),
(36, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:36:31'),
(37, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:36:34'),
(38, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:36:50'),
(39, '1', '', '', 'INV013', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '10', '120', '1', '0', '0', '121', '121', ' One Hundred and Twenty One', '', '', NULL, '0.00', '121.00', '2021-10-02 21:37:17'),
(40, '1', '', '', 'INV014', '', '', '', '2', '50.00,50.00', '100.00', NULL, '0', '100', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '', '', '', '0.00', '0.00', '2021-10-02 21:44:27'),
(41, '1', '', '', 'INV015', 'COLD SYRUP', '', '', '2', '50.00,50.00', '100.00', '10', '0', '110', '2', '0', '0', '112', '112', ' One Hundred and Twelve', '', '', NULL, '0.00', '112.00', '2021-10-02 21:46:23'),
(42, '1', '', '', 'INV016', '', '', '', '', '50.00,50.00', '', NULL, '0', '0', NULL, '0', '0', '0', '0', 'fifty', '', '', '', '0.00', '0.00', '2023-01-18 19:31:28'),
(43, '3', '', '', 'INV017', '', '', '', '20', '50.00,50.00', '1000.00', NULL, '0', '0', NULL, '0', '0', '0', '0', 'twenty', '', '', '', '0.00', '0.00', '2023-01-18 19:38:23'),
(44, '1', '', '', 'INV018', '', '', '', '2,4', '50.00,50.00,50.00', '100.00,200.00', NULL, '0', '0', NULL, '0', '0', '0', '0', 'three hundred', '', '', '', '0.00', '0.00', '2023-01-18 19:41:22'),
(45, '3', '', '', 'INV019', '', '', '', '', '50.00,50.00,50.00,50.00', '', NULL, '0', '0', NULL, '0', '0', '0', '0', 'three hundred', '', '', '', '0.00', '0.00', '2023-01-18 19:44:10'),
(46, '1', '', '', 'INV020', '', '', '', '20', '50.00,50.00', '1000.00', NULL, '0', '1000', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '2023-01-19', '', '', '0.00', '0.00', '2023-01-19 01:04:01'),
(47, '3', '', '', 'INV021', '', '', '', '20', '50.00,50.00', '1000.00', NULL, '0', '990', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '2023-01-19', '', '', '0.00', '0.00', '2023-01-19 01:06:05'),
(48, '1', '', '', 'INV022', '', '', '', '20', '50.00,50.00', '1000.00', NULL, '0', '990', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '2023-01-19', '', '', '0.00', '0.00', '2023-01-19 01:39:17'),
(49, '3', '', '', 'INV023', '', '', '', '20', '50.00,50.00', '1000.00', NULL, '0', '1000', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '2023-01-19', '', '', '0.00', '0.00', '2023-01-19 01:42:48'),
(50, '3', '', '', 'INV024', '', '', '', '20', '50.00,50.00', '1000.00', NULL, '0', '1000', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '', '', '', '0.00', '0.00', '2023-01-19 01:44:18'),
(51, '3', '', '', 'INV025', '', '', '', '20', '50.00,50.00', '1000.00', NULL, '0', '1000', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '2023-01-19', '', '', '0.00', '0.00', '2023-01-19 01:45:39'),
(52, '1', '', '', 'INV026', '', '', '', '20', '50.00,50.00', '1000.00', NULL, '0', '1000', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '2023-02-05', '', '', '0.00', '0.00', '2023-01-19 01:49:20'),
(53, '1', '', '', 'INV027', '', '', '', '20', '50.00,50.00', '', NULL, '0', '0', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '2023-02-05', '', '', '0.00', '0.00', '2023-01-19 01:49:39'),
(54, '1', '', '', 'INV028', '', '', '', '20', '50.00,50.00', '', NULL, '0', '0', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '2023-02-05', '', '', '0.00', '0.00', '2023-01-19 01:51:25'),
(55, '1', '', '', 'INV029', '', '', '', '20', '50.00,50.00', '', NULL, '0', '0', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '2023-02-05', '', '', '0.00', '0.00', '2023-01-19 01:51:42'),
(56, '3', '', '', 'INV030', '', '', '', '20', '50.00,50.00', '1000.00', NULL, '0', '1000', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '2023-01-19', '', '', '0.00', '0.00', '2023-01-19 02:09:17'),
(57, '1', '', '', 'INV031', '', '', '', '2', '50.00,50.00', '100.00', NULL, '0', '100', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '', '', '', '0.00', '0.00', '2023-02-03 22:14:48'),
(58, '1', '', '', 'INV032', '', '', '', '5', '50.00,50.00', '250.00', NULL, '0', '250', NULL, '0', '0', '0', '0', 'undefined Hundred andundefinedundefined', '', '', '', '0.00', '0.00', '2023-02-03 22:27:14');

-- --------------------------------------------------------

--
-- Table structure for table `ledger_balance`
--

CREATE TABLE `ledger_balance` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product` varchar(255) NOT NULL,
  `quantity` tinyint(4) NOT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `paid` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `updated_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ledger_balance`
--

INSERT INTO `ledger_balance` (`id`, `customer_id`, `product`, `quantity`, `rate`, `total`, `paid`, `balance`, `updated_date`) VALUES
(9, 1, 'PAV FLAVOR', 1, '120.00', '120.00', '0.00', '120.00', '2019-10-17'),
(8, 1, 'ACID PAN', 4, '210.00', '840.00', '300.00', '540.00', '2019-10-15');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `material_name` varchar(255) DEFAULT NULL,
  `hsn` varchar(255) DEFAULT NULL,
  `batch_no` varchar(255) DEFAULT NULL,
  `quantity` varchar(100) DEFAULT NULL,
  `rate` varchar(150) DEFAULT NULL,
  `invoice` varchar(50) DEFAULT NULL,
  `challan` varchar(40) DEFAULT NULL,
  `vendor` varchar(100) NOT NULL,
  `last_amount` decimal(10,2) DEFAULT NULL,
  `bill_amount` decimal(10,2) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `new_amount` decimal(10,2) DEFAULT NULL,
  `pay_mode` varchar(20) NOT NULL,
  `transaction_no` varchar(100) DEFAULT NULL,
  `cheque_no` varchar(10) DEFAULT NULL,
  `buy_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `material_name`, `hsn`, `batch_no`, `quantity`, `rate`, `invoice`, `challan`, `vendor`, `last_amount`, `bill_amount`, `paid_amount`, `new_amount`, `pay_mode`, `transaction_no`, `cheque_no`, `buy_date`) VALUES
(11, 'APPLE,BOY', '1901,1901', 'ABC,BCA', '2,1', '120.00,210.00', 'AB123', 'ch12', '10', '1000.00', '330.00', '500.00', '830.00', 'Cash', '', '', '2019-10-15 02:40:59'),
(12, '', '', '', '', '', NULL, NULL, '10', '830.00', '0.00', '130.00', '700.00', 'Cheque', '', '123456', '2019-10-15 02:41:50'),
(13, 'BOY,CAT', '123,1901', 'BCA,BCA', '1,2', '100,150', 'AB4321', NULL, '9', '123.50', '400.00', '23.00', '500.50', 'Cash', '', '', '2019-10-19 03:50:25');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `purchaser_id` int(10) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `pcs` int(11) NOT NULL DEFAULT 0,
  `total_amount` decimal(10,3) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `design_number` varchar(255) NOT NULL,
  `prod_exp` date DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `purchaser_id`, `product_name`, `pcs`, `total_amount`, `stock`, `price`, `design_number`, `prod_exp`, `create_date`) VALUES
(9, 13, 'BLACK NIDA', 0, '50.000', 10, '5.00', 'BRN1', NULL, '2023-02-08 18:15:22'),
(10, 13, 'BLACK NIDA', 1, '250.000', 5, '50.00', 'BRN9', NULL, '2023-02-08 18:18:23'),
(11, 13, 'SHAHBAAZ', 0, '40.000', 5, '8.00', 'BRN9', NULL, '2023-02-08 18:57:14');

-- --------------------------------------------------------

--
-- Table structure for table `purchasers`
--

CREATE TABLE `purchasers` (
  `id` int(10) UNSIGNED NOT NULL,
  `bakery_name` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `owner_phone` varchar(255) NOT NULL,
  `owner_email` varchar(255) NOT NULL,
  `bakery_gst` varchar(30) NOT NULL,
  `bakery_address` text NOT NULL,
  `bakery_area` varchar(255) NOT NULL,
  `bakery_city` varchar(255) NOT NULL,
  `last_amount` decimal(10,2) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchasers`
--

INSERT INTO `purchasers` (`id`, `bakery_name`, `owner_name`, `owner_phone`, `owner_email`, `bakery_gst`, `bakery_address`, `bakery_area`, `bakery_city`, `last_amount`, `created_on`) VALUES
(13, 'CODECHAIN.IN', 'SHAHBAAZ', '09028295792', '', '', '', 'Yusuf Manzil  3rd Flr Flat No.2 Above Chahat(Barkati) Hotel', '', '0.00', '2023-02-08 18:09:41');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock_qty` int(11) NOT NULL,
  `purchase_rate` float NOT NULL,
  `p_design_number` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `product_id`, `stock_qty`, `purchase_rate`, `p_design_number`, `created_at`) VALUES
(10, 10, 50, 500, 'BRN2', '2023-02-08 14:22:29'),
(15, 15, 6, 6, 'BRN1', '2023-02-08 15:31:39'),
(16, 16, 6, 6, 'BRN11', '2023-02-08 15:31:55'),
(19, 3, 2, 10, 'BRN1', '2023-02-08 15:38:18'),
(20, 4, 50, 2, 'BRN1', '2023-02-08 15:39:16'),
(25, 9, 40, 85, 'BRN1', '2023-02-08 18:15:22'),
(26, 10, 5, 50, 'BRN9', '2023-02-08 18:18:23'),
(27, 11, 5, 8, 'BRN9', '2023-02-08 18:57:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(15) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `last_login`) VALUES
(1, 'labbaik', 'bakery123', '2018-04-11 09:39:58'),
(2, 'asad', '12345678', '2021-09-19 14:00:20'),
(3, 'Mateen', 'Mat@123', '2021-09-19 14:01:24');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `area` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `phone1` varchar(10) NOT NULL,
  `phone2` varchar(10) NOT NULL,
  `email1` varchar(150) NOT NULL,
  `email2` varchar(150) NOT NULL,
  `gst` varchar(20) NOT NULL,
  `pan` varchar(12) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `bank_branch` varchar(150) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `rtgs_ifsc` varchar(30) NOT NULL,
  `debit_balance` decimal(10,2) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vendor_id`, `vendor_name`, `contact_name`, `address`, `area`, `city`, `phone1`, `phone2`, `email1`, `email2`, `gst`, `pan`, `bank_name`, `bank_branch`, `account_number`, `rtgs_ifsc`, `debit_balance`, `date_added`) VALUES
(10, 'ANKIT GRAIN', 'pawan', '701 / 702 , Karishma Plaza Commercial Premises', '7th Floor, Above Shamrao Vithal Bank, Near Asha Gen. Hospital,', 'malad east mumbai', '9320546953', '', '', 'info@hkgroup.net', '123454321234567', 'AODPA8487Q', 'HDFC BANK', 'malad east mumbai', '0228467121', '', '700.00', '2019-10-09 07:59:08'),
(9, 'H. K. ENZYMES', 'Piyush Doshi', '701 / 702 , Karishma Plaza Commercial Premises', '7th Floor, Above Shamrao Vithal Bank, Near Asha Gen. Hospital,', 'malad east mumbai', '0222866765', '', '', 'info@hkgroup.net', '27AABCH5271J1ZG', 'AODPA8487Q', 'HDFC BANK', 'malad east mumbai', '0228467121', 'HDFC0123', '500.50', '2019-10-09 07:54:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balance`
--
ALTER TABLE `balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `challan_bills`
--
ALTER TABLE `challan_bills`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_ledger_balance`
--
ALTER TABLE `customer_ledger_balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `insider_bill`
--
ALTER TABLE `insider_bill`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `ledger_balance`
--
ALTER TABLE `ledger_balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchasers`
--
ALTER TABLE `purchasers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balance`
--
ALTER TABLE `balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `challan_bills`
--
ALTER TABLE `challan_bills`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_ledger_balance`
--
ALTER TABLE `customer_ledger_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `insider_bill`
--
ALTER TABLE `insider_bill`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `ledger_balance`
--
ALTER TABLE `ledger_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `purchasers`
--
ALTER TABLE `purchasers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
