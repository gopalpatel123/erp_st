-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2017 at 02:22 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wwwarmsl_sunilerp`
--

-- --------------------------------------------------------

--
-- Table structure for table `purchase_invoices`
--

CREATE TABLE `purchase_invoices` (
  `id` int(11) NOT NULL,
  `voucher_no` int(10) NOT NULL,
  `company_id` int(10) NOT NULL,
  `transaction_date` date NOT NULL,
  `supplier_ledger_id` int(10) NOT NULL,
  `purchase_ledger_id` int(10) NOT NULL,
  `grn_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_invoices`
--

INSERT INTO `purchase_invoices` (`id`, `voucher_no`, `company_id`, `transaction_date`, `supplier_ledger_id`, `purchase_ledger_id`, `grn_id`) VALUES
(1, 1, 1, '2017-10-09', 108, 33, 0),
(2, 1, 1, '2017-10-09', 108, 33, 0),
(3, 1, 1, '2017-10-09', 108, 33, 0),
(4, 1, 1, '2017-10-09', 108, 33, 0),
(5, 1, 1, '2017-10-09', 108, 33, 0),
(6, 1, 1, '2017-10-09', 108, 33, 0),
(7, 1, 1, '2017-10-09', 108, 33, 0),
(8, 2, 1, '2017-10-09', 107, 33, 0),
(9, 3, 1, '2017-10-09', 108, 33, 0),
(10, 4, 1, '2017-10-09', 108, 33, 0),
(11, 5, 1, '2017-10-09', 108, 33, 0),
(12, 6, 1, '2017-10-09', 107, 33, 0),
(13, 7, 1, '2017-10-09', 107, 33, 0),
(14, 8, 1, '2017-10-09', 107, 33, 0),
(15, 9, 1, '2017-10-09', 108, 33, 3);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_invoice_rows`
--

CREATE TABLE `purchase_invoice_rows` (
  `id` int(11) NOT NULL,
  `purchase_invoice_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `discount_percentage` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) NOT NULL,
  `pnf_percentage` varchar(10) NOT NULL,
  `pnf_amount` decimal(15,2) NOT NULL,
  `taxable_value` decimal(15,2) NOT NULL,
  `net_amount` decimal(10,2) NOT NULL,
  `item_gst_figure_id` int(10) NOT NULL,
  `gst_value` decimal(10,2) NOT NULL,
  `round_off` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_invoice_rows`
--

INSERT INTO `purchase_invoice_rows` (`id`, `purchase_invoice_id`, `item_id`, `quantity`, `rate`, `discount_percentage`, `discount_amount`, `pnf_percentage`, `pnf_amount`, `taxable_value`, `net_amount`, `item_gst_figure_id`, `gst_value`, `round_off`) VALUES
(1, 1, 47, '20.00', '848.00', '1.00', '169.60', '3', '503.71', '17294.11', '18159.00', 5, '864.71', '0.18'),
(2, 1, 48, '20.00', '568.00', '2.00', '227.20', '4', '445.31', '11578.11', '12157.00', 5, '578.91', '-0.02'),
(3, 2, 47, '20.00', '848.00', '1.00', '169.60', '3', '503.71', '17294.11', '18159.00', 5, '864.71', '0.18'),
(4, 2, 48, '20.00', '568.00', '2.00', '227.20', '4', '445.31', '11578.11', '12157.00', 5, '578.91', '-0.02'),
(5, 3, 47, '20.00', '848.00', '1.00', '169.60', '3', '503.71', '17294.11', '18159.00', 5, '864.71', '0.18'),
(6, 3, 48, '20.00', '568.00', '2.00', '227.20', '4', '445.31', '11578.11', '12157.00', 5, '578.91', '-0.02'),
(7, 4, 47, '20.00', '848.00', '1.00', '169.60', '3', '503.71', '17294.11', '18159.00', 5, '864.71', '0.18'),
(8, 4, 48, '20.00', '568.00', '2.00', '227.20', '4', '445.31', '11578.11', '12157.00', 5, '578.91', '-0.02'),
(9, 5, 47, '20.00', '848.00', '1.00', '169.60', '3.00', '503.71', '17294.11', '18159.00', 5, '864.71', '0.18'),
(10, 5, 48, '20.00', '568.00', '2.00', '227.20', '4.00', '445.31', '11578.11', '12157.00', 5, '578.91', '-0.02'),
(11, 6, 47, '20.00', '848.00', '1.00', '169.60', '3', '503.71', '17294.11', '18159.00', 5, '864.71', '0.18'),
(12, 6, 48, '20.00', '568.00', '2.00', '227.20', '4', '445.31', '11578.11', '12157.00', 5, '578.91', '-0.02'),
(13, 7, 47, '20.00', '848.00', '1.00', '169.60', '3', '503.71', '17294.11', '18159.00', 5, '864.72', '0.17'),
(14, 7, 48, '20.00', '568.00', '2.00', '227.20', '4', '445.31', '11578.11', '12157.00', 5, '578.90', '-0.01'),
(15, 8, 290, '15.00', '446.00', '1.00', '66.90', '4', '264.92', '6888.02', '7232.00', 12, '344.40', '-0.43'),
(16, 8, 291, '15.00', '389.00', '3.00', '175.05', '5', '283.00', '5942.95', '6240.00', 12, '297.15', '-0.09'),
(17, 9, 290, '15.00', '446.00', '1.00', '66.90', '3', '198.69', '6821.79', '7163.00', 12, '341.09', '0.12'),
(18, 9, 291, '15.00', '389.00', '2.00', '116.70', '4', '228.73', '5947.03', '6244.00', 12, '297.35', '-0.38'),
(19, 10, 290, '15.00', '446.00', '1.00', '66.90', '3', '198.69', '6821.79', '7163.00', 12, '341.09', '0.12'),
(20, 10, 291, '15.00', '389.00', '2.00', '116.70', '4', '228.73', '5947.03', '6244.00', 12, '297.35', '-0.38'),
(21, 11, 290, '15.00', '446.00', '1.00', '66.90', '3', '198.69', '6821.79', '7163.00', 12, '341.09', '0.12'),
(22, 11, 291, '15.00', '389.00', '2.00', '116.70', '4', '228.73', '5947.03', '6244.00', 12, '297.35', '-0.38'),
(23, 12, 290, '15.00', '446.00', '1.00', '66.90', '1', '66.23', '6689.33', '7024.00', 12, '334.47', '0.20'),
(24, 12, 291, '15.00', '389.00', '2.00', '116.70', '3', '171.55', '5889.85', '6184.00', 12, '294.49', '-0.34'),
(25, 13, 290, '15.00', '446.00', '1.00', '66.90', '1', '66.23', '6689.33', '7024.00', 12, '334.47', '0.20'),
(26, 13, 291, '15.00', '389.00', '2.00', '116.70', '3', '171.55', '5889.85', '6184.00', 12, '294.49', '-0.34'),
(27, 14, 290, '15.00', '446.00', '1.00', '66.90', '1', '66.23', '6689.33', '7024.00', 12, '334.47', '0.20'),
(28, 14, 291, '15.00', '389.00', '2.00', '116.70', '3', '171.55', '5889.85', '6184.00', 12, '294.49', '-0.34'),
(29, 15, 36, '6.00', '5500.00', '1.01', '333.30', '2.00', '653.33', '33320.03', '34986.00', 2, '1666.00', '-0.03'),
(30, 15, 37, '12.00', '1500.00', '2.00', '360.00', '3.00', '529.20', '18169.20', '19078.00', 2, '908.46', '0.34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `purchase_invoices`
--
ALTER TABLE `purchase_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_invoice_rows`
--
ALTER TABLE `purchase_invoice_rows`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `purchase_invoices`
--
ALTER TABLE `purchase_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `purchase_invoice_rows`
--
ALTER TABLE `purchase_invoice_rows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
