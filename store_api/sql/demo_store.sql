-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 17, 2017 at 11:16 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `demo_store`
--
CREATE DATABASE IF NOT EXISTS `demo_store` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `demo_store`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categories`
--

CREATE TABLE IF NOT EXISTS `tbl_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `tax` int(11) NOT NULL,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tbl_categories`
--

INSERT INTO `tbl_categories` (`id`, `name`, `description`, `tax`, `added_on`, `updated_on`) VALUES
(3, 'Women''s Clothing', 'Women''s Clothing', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Memory Cards', 'Made in India', 3, '2017-01-17 18:24:26', '0000-00-00 00:00:00'),
(5, 'Softwares', 'Made in India', 3, '2017-01-17 18:24:26', '0000-00-00 00:00:00'),
(10, 'Bags', 'Made in India', 2, '2017-01-18 04:07:53', '0000-00-00 00:00:00'),
(11, 'Shoes', 'Made in India', 5, '2017-01-18 04:07:53', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE IF NOT EXISTS `tbl_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `discount` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`id`, `name`, `description`, `price`, `discount`, `category_id`, `added_on`, `updated_on`) VALUES
(2, 'Solimo Spectra Stripe', 'Made in India', 856, 15, 4, '2017-01-18 03:27:38', '0000-00-00 00:00:00'),
(4, 'Kinley', 'Water Bottle', 22, 0, 5, '2017-01-18 00:00:00', '0000-00-00 00:00:00'),
(5, 'Samsung S5', 'Mobile', 22000, 6, 4, '2017-01-18 15:37:28', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sessions`
--

CREATE TABLE IF NOT EXISTS `tbl_sessions` (
  `id` varchar(32) NOT NULL,
  `access` int(10) unsigned DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sessions`
--

INSERT INTO `tbl_sessions` (`id`, `access`, `data`) VALUES
('9pfgojvu5nqiac719duri6rmr6', 1484663806, 'cart|a:1:{i:0;a:2:{s:2:"id";i:1;s:3:"qty";i:14;}}'),
('t3vjal8s3j7543qoiat6p0erj6', 1484637027, 'cart|a:0:{}');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD CONSTRAINT `tbl_products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tbl_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
