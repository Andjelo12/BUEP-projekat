-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2025 at 09:01 PM
-- Server version: 8.0.42-0ubuntu0.20.04.1
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rsharp`
--

-- --------------------------------------------------------

--
-- Table structure for table `detects`
--

CREATE TABLE `detects` (
  `id_log` int NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `device_type` enum('phone','tablet','computer') COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('success','banned','wrong data','register','register with same email') COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `proxy` tinyint(1) NOT NULL,
  `google_login` enum('1','0') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detects`
--

INSERT INTO `detects` (`id_log`, `user_agent`, `ip_address`, `country`, `date_time`, `device_type`, `status`, `user_id`, `proxy`, `google_login`) VALUES
(9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', 'Serbia', '2024-01-04 13:22:54', 'computer', 'banned', 8, 0, '0'),
(10, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', 'Serbia', '2024-01-04 13:23:38', 'computer', 'success', 9, 0, '0'),
(16, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', 'Serbia', '2024-01-04 17:10:40', 'computer', 'success', 16, 0, '0'),
(17, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', 'Serbia', '2024-01-04 17:13:49', 'computer', 'success', 17, 0, '0'),
(18, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-05 17:41:29', 'computer', 'wrong data', 18, 0, '0'),
(19, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-05 17:41:44', 'computer', 'success', 19, 0, '0'),
(20, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-10 08:05:15', 'computer', 'wrong data', 20, 0, '0'),
(21, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-10 08:05:21', 'computer', 'wrong data', 21, 0, '0'),
(22, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-10 08:05:28', 'computer', 'success', 22, 0, '0'),
(23, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-10 08:16:02', 'computer', 'success', 23, 0, '0'),
(24, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-10 08:17:13', 'computer', 'success', 24, 0, '0'),
(25, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-10 08:19:29', 'computer', 'success', 25, 0, '0'),
(26, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-18 18:21:33', 'computer', 'wrong data', 26, 0, '0'),
(27, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-18 18:21:38', 'computer', 'wrong data', 27, 0, '0'),
(28, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-18 18:22:59', 'computer', 'wrong data', 28, 0, '0'),
(29, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-18 18:23:05', 'computer', 'success', 29, 0, '0'),
(30, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-19 00:53:05', 'computer', 'success', 30, 0, '0'),
(38, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', '192.168.1.8', '', '2024-01-19 11:44:31', 'phone', 'wrong data', 38, 0, '0'),
(39, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', '192.168.1.8', '', '2024-01-19 11:44:49', 'phone', 'success', 39, 0, '0'),
(40, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', '192.168.1.8', '', '2024-01-19 12:14:05', 'phone', 'success', 40, 0, '0'),
(41, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-19 12:27:01', 'computer', 'wrong data', 41, 0, '0'),
(42, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-19 12:27:07', 'computer', 'success', 42, 0, '0'),
(43, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-19 12:27:24', 'computer', 'success', 43, 0, '0'),
(44, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', '192.168.1.8', '', '2024-01-19 12:27:57', 'phone', 'wrong data', 44, 0, '0'),
(45, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', '192.168.1.8', '', '2024-01-19 12:28:10', 'phone', 'success', 45, 0, '0'),
(46, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', '192.168.1.8', '', '2024-01-19 12:28:41', 'phone', 'success', 46, 0, '0'),
(47, '', '192.168.1.8', '', '2024-01-19 13:05:56', 'phone', 'wrong data', 47, 0, '0'),
(48, '', '192.168.1.8', '', '2024-01-19 13:06:08', 'phone', 'success', 48, 0, '0'),
(49, '', '192.168.1.8', '', '2024-01-19 13:14:14', 'phone', 'success', 49, 0, '0'),
(50, '', '192.168.1.8', '', '2024-01-19 13:43:24', 'phone', 'success', 50, 0, '0'),
(51, '', '192.168.1.8', '', '2024-01-19 13:43:59', 'phone', 'success', 51, 0, '0'),
(52, '', '192.168.1.8', '', '2024-01-19 14:41:58', 'phone', 'success', 52, 0, '0'),
(53, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', '192.168.1.8', '', '2024-01-19 16:05:17', 'phone', 'wrong data', 53, 0, '0'),
(54, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', '192.168.1.8', '', '2024-01-19 16:05:29', 'phone', 'success', 54, 0, '0'),
(55, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-19 16:11:37', 'computer', 'wrong data', 55, 0, '0'),
(56, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-19 16:11:44', 'computer', 'success', 56, 0, '0'),
(59, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-19 17:41:38', 'computer', 'wrong data', 59, 0, '0'),
(60, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-19 17:41:43', 'computer', 'success', 60, 0, '0'),
(61, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-19 18:23:01', 'computer', 'success', 61, 0, '0'),
(64, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-19 22:33:38', 'computer', 'success', 64, 0, '0'),
(65, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 09:11:34', 'computer', 'success', 65, 0, '0'),
(66, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', '192.168.1.8', '', '2024-01-20 11:32:54', 'phone', 'success', 66, 0, '0'),
(67, '', '127.0.0.1', '', '2024-01-20 11:41:58', 'computer', 'success', 67, 0, '0'),
(68, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 12:01:26', 'computer', 'success', 68, 0, '0'),
(73, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 12:11:51', 'computer', 'success', 73, 0, '0'),
(74, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-20 12:39:03', 'computer', 'success', 74, 0, '0'),
(75, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 12:59:53', 'computer', 'wrong data', 75, 0, '0'),
(76, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 12:59:58', 'computer', 'success', 76, 0, '0'),
(77, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 13:00:12', 'computer', 'success', 77, 0, '0'),
(80, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-20 16:46:15', 'computer', 'success', 80, 0, '0'),
(82, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 17:07:45', 'computer', 'success', 82, 0, '0'),
(83, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 17:22:48', 'computer', 'success', 83, 0, '0'),
(84, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 18:14:54', 'computer', 'success', 84, 0, '0'),
(85, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 19:34:55', 'computer', 'success', 85, 0, '0'),
(86, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 21:03:48', 'computer', 'success', 86, 0, '0'),
(87, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 21:06:15', 'computer', 'success', 87, 0, '0'),
(88, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 21:13:26', 'computer', 'success', 88, 0, '0'),
(89, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 22:10:05', 'computer', 'success', 89, 0, '0'),
(90, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 22:28:28', 'computer', 'success', 90, 0, '0'),
(91, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 22:35:05', 'computer', 'success', 91, 0, '0'),
(92, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-20 23:02:16', 'computer', 'register with same email', 92, 0, '0'),
(93, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-21 09:18:28', 'computer', 'success', 93, 0, '0'),
(94, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-21 11:22:03', 'computer', 'success', 94, 0, '0'),
(95, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-21 11:22:30', 'computer', 'success', 95, 0, '0'),
(96, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-21 11:28:44', 'computer', 'wrong data', 96, 0, '0'),
(97, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-21 11:28:51', 'computer', 'success', 97, 0, '0'),
(98, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-21 11:29:10', 'computer', 'wrong data', 98, 0, '0'),
(99, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-21 11:29:17', 'computer', 'success', 99, 0, '0'),
(100, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-21 11:29:34', 'computer', 'success', 100, 0, '0'),
(101, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-21 11:29:47', 'computer', 'success', 101, 0, '0'),
(102, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-21 11:29:50', 'computer', 'success', 102, 0, '0'),
(103, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-21 11:32:04', 'computer', 'success', 103, 0, '0'),
(104, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-21 11:32:30', 'computer', 'success', 104, 0, '0'),
(105, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-21 11:36:49', 'computer', 'success', 105, 0, '0'),
(106, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-21 12:35:24', 'computer', 'success', 106, 0, '0'),
(114, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-21 13:23:00', 'computer', 'success', 114, 0, '0'),
(117, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-21 17:36:34', 'computer', 'success', 117, 0, '0'),
(118, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '178.221.140.167', 'Serbia', '2024-01-21 17:39:05', 'computer', 'wrong data', 118, 0, '0'),
(119, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '178.221.140.167', 'Serbia', '2024-01-21 17:39:17', 'computer', 'success', 119, 0, '0'),
(120, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', '178.221.140.167', 'Serbia', '2024-01-21 17:45:34', 'phone', 'wrong data', 120, 0, '0'),
(125, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-21 21:13:51', 'computer', 'success', 125, 0, '0'),
(126, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-21 21:14:04', 'computer', 'success', 126, 0, '0'),
(130, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-22 08:36:34', 'computer', 'success', 130, 0, '0'),
(131, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-22 09:35:57', 'computer', 'success', 131, 0, '0'),
(133, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-22 13:22:16', 'computer', 'success', 133, 0, '0'),
(134, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-22 13:22:34', 'computer', 'success', 134, 0, '0'),
(136, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '::1', '', '2024-01-22 15:40:04', 'computer', 'success', 136, 0, '0'),
(137, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 17:42:51', 'computer', 'success', 137, 0, '0'),
(138, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 19:04:07', 'computer', 'success', 138, 0, '0'),
(139, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 20:59:25', 'computer', 'success', 139, 0, '0'),
(140, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 20:59:40', 'computer', 'success', 140, 0, '0'),
(141, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 21:00:10', 'computer', 'success', 141, 0, '0'),
(142, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 21:00:28', 'computer', 'success', 142, 0, '0'),
(143, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 21:00:58', 'computer', 'success', 143, 0, '0'),
(144, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 21:01:32', 'computer', 'success', 144, 0, '0'),
(145, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 21:01:56', 'computer', 'success', 145, 0, '0'),
(147, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 21:37:52', 'computer', 'success', 147, 0, '0'),
(150, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 22:50:13', 'computer', 'success', 151, 0, '0'),
(151, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 22:54:33', 'computer', 'wrong data', 152, 0, '0'),
(152, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 22:54:44', 'computer', 'wrong data', 153, 0, '0'),
(153, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 22:54:52', 'computer', 'wrong data', 154, 0, '0'),
(154, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', '127.0.0.1', '', '2024-01-22 22:55:02', 'computer', 'success', 155, 0, '0'),
(155, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '178.222.131.61', 'Serbia', '2025-06-17 11:28:43', 'computer', 'success', 156, 0, '0'),
(156, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', '178.222.131.61', 'Serbia', '2025-06-17 11:37:58', 'computer', 'success', 157, 0, '0'),
(157, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '147.91.199.142', 'Serbia', '2025-06-17 12:20:29', 'computer', 'success', 158, 0, '0'),
(158, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '178.222.131.61', 'Serbia', '2025-06-17 14:39:57', 'computer', 'success', 159, 0, '0'),
(159, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:05:27', 'computer', 'wrong data', 160, 1, '0'),
(160, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:05:28', 'computer', 'wrong data', 161, 1, '0'),
(161, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:05:30', 'computer', 'wrong data', 162, 1, '0'),
(162, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:05:32', 'computer', 'wrong data', 163, 1, '0'),
(163, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:05:41', 'computer', 'wrong data', 164, 1, '0'),
(164, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:06:26', 'computer', 'wrong data', 165, 1, '0'),
(165, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:06:27', 'computer', 'wrong data', 166, 1, '0'),
(166, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:06:29', 'computer', 'wrong data', 167, 1, '0'),
(167, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:06:30', 'computer', 'wrong data', 168, 1, '0'),
(168, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:06:40', 'computer', 'wrong data', 169, 1, '0'),
(169, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '87.89.48.69', 'France', '2025-06-18 02:07:21', 'computer', 'wrong data', 170, 0, '0'),
(170, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '87.89.48.69', 'France', '2025-06-18 02:07:23', 'computer', 'wrong data', 171, 0, '0'),
(171, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '87.89.48.69', 'France', '2025-06-18 02:07:24', 'computer', 'wrong data', 172, 0, '0'),
(172, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '87.89.48.69', 'France', '2025-06-18 02:07:25', 'computer', 'wrong data', 173, 0, '0'),
(173, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '87.89.48.69', 'France', '2025-06-18 02:07:35', 'computer', 'wrong data', 174, 0, '0'),
(174, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:38:39', 'computer', 'wrong data', 175, 1, '0'),
(175, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:38:41', 'computer', 'wrong data', 176, 1, '0'),
(176, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:38:43', 'computer', 'wrong data', 177, 1, '0'),
(177, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:38:44', 'computer', 'wrong data', 178, 1, '0'),
(178, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:38:54', 'computer', 'wrong data', 179, 1, '0'),
(179, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:40:28', 'computer', 'wrong data', 180, 1, '0'),
(180, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:40:30', 'computer', 'wrong data', 181, 1, '0'),
(181, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:40:32', 'computer', 'wrong data', 182, 1, '0'),
(182, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:40:34', 'computer', 'wrong data', 183, 1, '0'),
(183, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '146.70.221.29', 'Serbia', '2025-06-18 02:40:44', 'computer', 'wrong data', 184, 1, '0'),
(184, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '139.99.170.109', 'Australia', '2025-06-18 02:42:32', 'computer', 'wrong data', 185, 1, '0'),
(185, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '139.99.170.109', 'Australia', '2025-06-18 02:42:35', 'computer', 'wrong data', 186, 1, '0'),
(186, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '139.99.170.109', 'Australia', '2025-06-18 02:42:37', 'computer', 'wrong data', 187, 1, '0'),
(187, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '139.99.170.109', 'Australia', '2025-06-18 02:42:39', 'computer', 'wrong data', 188, 1, '0'),
(188, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', '139.99.170.109', 'Australia', '2025-06-18 02:42:51', 'computer', 'wrong data', 189, 1, '0'),
(189, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:139.0) Gecko/20100101 Firefox/139.0', '77.46.232.231', 'Serbia', '2025-06-30 16:55:43', 'computer', 'success', 190, 0, '0'),
(190, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '77.46.232.231', 'Serbia', '2025-06-30 17:04:36', 'computer', 'success', 191, 0, '0'),
(191, '', '109.92.68.154', 'Serbia', '2025-07-01 11:51:58', 'phone', 'success', 192, 0, '0'),
(192, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '178.223.85.96', 'Serbia', '2025-07-02 11:27:58', 'computer', 'success', 193, 0, '0'),
(193, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '109.93.68.115', 'Serbia', '2025-07-03 11:45:47', 'computer', 'success', 194, 0, '0'),
(194, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '109.93.68.115', 'Serbia', '2025-07-03 13:05:27', 'computer', 'success', 195, 0, '0'),
(196, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-04 18:03:00', 'computer', 'success', 197, 0, '0'),
(197, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-04 20:23:15', 'computer', 'success', 198, 0, '0'),
(198, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-04 20:24:43', 'computer', 'success', 199, 0, '0'),
(199, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-04 21:54:13', 'computer', 'success', 200, 0, '1'),
(200, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-04 22:30:37', 'computer', 'success', 201, 0, '1'),
(201, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-04 22:35:51', 'computer', 'success', 203, 0, '0'),
(202, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-04 22:37:03', 'computer', 'success', 204, 0, '1'),
(203, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-04 22:37:32', 'computer', 'success', 205, 0, '0'),
(204, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-04 23:27:42', 'computer', 'success', 206, 0, '0'),
(205, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 12:08:24', 'computer', 'success', 207, 0, '1'),
(206, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 12:08:42', 'computer', 'success', 208, 0, '1'),
(207, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 12:10:14', 'computer', 'success', 209, 0, '0'),
(208, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 12:24:59', 'computer', 'success', 210, 0, '1'),
(209, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 18:41:09', 'computer', 'success', 211, 0, '0'),
(210, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 18:43:12', 'computer', 'success', 212, 0, '0'),
(211, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 18:45:43', 'computer', 'success', 213, 0, '0'),
(212, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 18:47:13', 'computer', 'success', 214, 0, '0'),
(213, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 18:49:03', 'computer', 'success', 215, 0, '0'),
(214, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 18:51:28', 'computer', 'success', 216, 0, '0'),
(215, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '178.221.141.146', 'Serbia', '2025-07-05 18:53:41', 'computer', 'wrong data', 217, 0, '0'),
(216, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '178.221.141.146', 'Serbia', '2025-07-05 18:54:07', 'computer', 'success', 218, 0, '0'),
(217, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 19:05:05', 'computer', 'success', 219, 0, '1'),
(218, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 19:05:14', 'computer', 'success', 220, 0, '1'),
(219, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 19:13:05', 'computer', 'success', 221, 0, '1'),
(220, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 19:13:31', 'computer', 'success', 222, 0, '0'),
(221, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 19:36:27', 'computer', 'success', 223, 0, '0'),
(222, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '178.221.141.146', 'Serbia', '2025-07-05 20:23:07', 'computer', 'success', 224, 0, '0'),
(223, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 20:35:58', 'computer', 'success', 225, 0, '0'),
(224, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-05 22:26:24', 'computer', 'success', 226, 0, '0'),
(225, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '95.86.51.96', 'Serbia', '2025-07-06 10:48:56', 'computer', 'wrong data', 227, 0, '0'),
(226, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '95.86.51.96', 'Serbia', '2025-07-06 10:48:59', 'computer', 'wrong data', 228, 0, '0'),
(227, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '95.86.51.96', 'Serbia', '2025-07-06 10:49:17', 'computer', 'success', 229, 0, '0'),
(228, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '95.86.51.96', 'Serbia', '2025-07-06 10:50:27', 'computer', 'wrong data', 230, 0, '0'),
(229, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '95.86.51.96', 'Serbia', '2025-07-06 10:51:47', 'computer', 'success', 231, 0, '0'),
(230, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-06 13:54:26', 'computer', 'wrong data', 232, 0, '0'),
(231, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-06 13:54:39', 'computer', 'success', 233, 0, '0'),
(232, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-06 14:09:42', 'computer', 'success', 234, 0, '1'),
(233, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-06 15:23:55', 'computer', 'success', 235, 0, '1'),
(234, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-06 17:10:29', 'computer', 'success', 236, 0, '0'),
(235, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-06 17:32:08', 'computer', 'success', 237, 0, '1'),
(236, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '178.221.141.146', 'Serbia', '2025-07-06 18:37:35', 'computer', 'success', 238, 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `date` datetime NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `is_blocked` set('blocked','free') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'free',
  `archived` enum('yes','no') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `name`, `description`, `date`, `foto`, `location`, `created_by`, `is_blocked`, `archived`) VALUES
(12, 'Godišnjica braka', 'Pozivam Vas na 5 godišnjicu moga braka.', '2028-01-19 20:00:00', '7009-ezgif-8a6c5e98af970b.jpg', 'Srbija, Beograd', '26121057@vts.su.ac.rs', 'free', 'no'),
(15, 'Imendan', 'Poziva Vas na moj imendan.', '2030-01-24 23:30:00', '7011-SmartSize.jpg', 'Pačir, Srbija', '26121057@vts.su.ac.rs', 'free', 'no'),
(23, '28 rođendan', 'Poziv na proslavu mog rođendana.', '2026-05-10 09:00:00', '3640-d3f5d3a540e5b359459e5dcfe43a9a25.jpg', 'Subotica, Srbija', '26121057@vts.su.ac.rs', 'free', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `invites`
--

CREATE TABLE `invites` (
  `id` int NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `are_coming` set('Yes','No','Maybe','Didn''t decided') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Didn''t decided',
  `invite_code` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `event_id` int NOT NULL,
  `wish_list` enum('yes','no') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invites`
--

INSERT INTO `invites` (`id`, `email`, `name`, `are_coming`, `invite_code`, `event_id`, `wish_list`) VALUES
(39, 'andjell009@gmail.com', 'Anđelo', 'Maybe', '59a244cdfff9b45079ac', 15, 'yes'),
(40, '26121057@vts.su.ac.rs', 'Anđelko', 'Yes', '8deb62a199b46554fe76', 15, 'no'),
(58, 'andjell009@gmail.com', 'Anđelo', 'No', 'a8a9f1094c2577c20a25', 23, 'no'),
(59, '26121057@vts.su.ac.rs', 'Anđelko', 'Didn\'t decided', '337d28030876f214e329', 23, 'no'),
(80, 'andjelo12@live.com', 'Name', 'Didn\'t decided', 'b17f3da85317e4a36db3', 23, 'yes'),
(111, 'mzmqdlqxjvcggyfaya@cwmxc.com', 'Pismo', 'Didn\'t decided', '1cf3016af8bace0f2a71', 15, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id_message` int NOT NULL,
  `invite_name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `id_event` int NOT NULL,
  `sender_email` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('replied','unreplied') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unreplied',
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id_message`, `invite_name`, `message`, `id_event`, `sender_email`, `status`, `date_time`) VALUES
(1, 'Pera', 'Želeo bih da dođem na događaj. Da li bi ste mogli da me dodate u listu zvanica?', 23, 'lhf85968@zbock.com', 'replied', '2024-01-19 20:18:21'),
(2, 'Mika', 'Zeleo bih da dodjem na dogadjaj', 23, 'andjell009@gmail.com', 'replied', '2024-01-20 09:09:44'),
(6, 'Veljko', 'Želeo bih da dođem na događaj.', 23, 'hqi25647@nezid.com', 'replied', '2024-01-20 17:07:27'),
(7, 'Name', 'I am interested in event', 23, 'andjelo12@live.com', 'replied', '2024-01-20 18:14:39'),
(19, 'Proba', 'Probamo dal radi', 23, 'ajeioiketvzukhffii@cazlv.com', 'unreplied', '2024-01-22 17:23:24'),
(20, 'Pismo', 'Probno pismo', 15, 'mzmqdlqxjvcggyfaya@cwmxc.com', 'replied', '2024-01-22 17:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id_token` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `calls_no` int NOT NULL DEFAULT '0',
  `tokens_no` int NOT NULL DEFAULT '50',
  `account_type` enum('bronze','silver','gold','platinum') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'bronze',
  `email` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_call` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_reset` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `blocked` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id_token`, `token`, `calls_no`, `tokens_no`, `account_type`, `email`, `last_call`, `last_reset`, `blocked`) VALUES
(5, 'Bearer 04bcddf34274219310b9', 10, 50, 'bronze', 'larisa.djuric@matkovukovic.edu.rs', '2025-07-04 20:27:29', '2025-07-04 20:27:29', 0),
(11, 'Bearer c5242c9835cb061204aa', 0, 50, 'bronze', '26121057@vts.su.ac.rs', '2025-07-06 18:08:20', '2025-07-06 18:08:20', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users2`
--

CREATE TABLE `users2` (
  `id_user` int NOT NULL,
  `email` varchar(40) COLLATE utf8mb3_unicode_ci NOT NULL,
  `google_id` varchar(21) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `firstname` varchar(30) COLLATE utf8mb3_unicode_ci NOT NULL,
  `lastname` varchar(30) COLLATE utf8mb3_unicode_ci NOT NULL,
  `registration_token` char(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `registration_expires` datetime DEFAULT NULL,
  `active` smallint NOT NULL DEFAULT '0',
  `forgotten_password_token` char(40) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `forgotten_password_expires` datetime DEFAULT NULL,
  `change_password_token` char(40) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `change_password_expires` datetime DEFAULT NULL,
  `is_banned` smallint NOT NULL DEFAULT '0',
  `is_admin` enum('Yes','No') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'No',
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `users2`
--

INSERT INTO `users2` (`id_user`, `email`, `google_id`, `password`, `firstname`, `lastname`, `registration_token`, `registration_expires`, `active`, `forgotten_password_token`, `forgotten_password_expires`, `change_password_token`, `change_password_expires`, `is_banned`, `is_admin`, `date_time`) VALUES
(1, 'andjell009@gmail.com', '', '$2y$10$JeAxuLYbhmhA3fWzUAz88.74p4nOhwYpTOGq3EwRtC.ts9uq7fFEa', 'Jevanđel', 'Admin', '', '0000-00-00 00:00:00', 1, NULL, NULL, '', NULL, 0, 'Yes', '2025-07-05 20:17:41'),
(5, '26121057@vts.su.ac.rs', '', '$2y$10$nYaSnIk5WWjF0rAZdHxnMul.4ZI98HOanHD2V1x7pDgW6vPdBw/Em', 'Anđelo', 'Đurić', '', '0000-00-00 00:00:00', 1, '8dc7224ddf5f9be64d8094010a3d509d9ef50a02', '2025-07-07 01:03:37', '', NULL, 0, 'No', '2025-07-07 11:08:37'),
(16, 'larisa.djuric@matkovukovic.edu.rs', '118213597653707711002', NULL, 'Лариса', 'Ђурић', NULL, NULL, 1, NULL, NULL, NULL, NULL, 0, 'No', '2025-07-04 18:03:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_detects`
--

CREATE TABLE `user_detects` (
  `id_user_detects` int NOT NULL,
  `email` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `user_details` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_detects`
--

INSERT INTO `user_detects` (`id_user_detects`, `email`, `user_details`) VALUES
(8, '26121057@vts.su.ac.rs', 5),
(9, '26121057@vts.su.ac.rs', 5),
(16, '26121057@vts.su.ac.rs', 5),
(17, '26121057@vts.su.ac.rs', 5),
(18, 'chole@vts.su.ac.rs', 1),
(19, 'chole@vts.su.ac.rs', 1),
(20, '26121057@vts.su.ac.rs', 5),
(21, '26121057@vts.su.ac.rs', 5),
(22, '26121057@vts.su.ac.rs', 5),
(23, '26121057@vts.su.ac.rs', 5),
(24, 'chole@vts.su.ac.rs', 1),
(25, '26121057@vts.su.ac.rs', 5),
(26, '26121057@vts.su.ac.rs', 5),
(27, '26121057@vts.su.ac.rs', 5),
(28, '26121057@vts.su.ac.rs', 5),
(29, '26121057@vts.su.ac.rs', 5),
(30, 'chole@vts.su.ac.rs', 1),
(31, '26121057@vts.su.ac.rs', 5),
(33, 'chole@vts.su.ac.rs', 1),
(34, '26121057@vts.su.ac.rs', 5),
(35, '26121057@vts.su.ac.rs', 5),
(36, '26121057@vts.su.ac.rs', 5),
(37, '26121057@vts.su.ac.rs', 5),
(38, '26121057@vts.su.ac.rs', 5),
(39, '26121057@vts.su.ac.rs', 5),
(40, 'chole@vts.su.ac.rs', 1),
(41, '26121057@vts.su.ac.rs', 5),
(42, '26121057@vts.su.ac.rs', 5),
(43, 'chole@vts.su.ac.rs', 1),
(44, 'chole@vts.su.ac.rs', 1),
(45, '26121057@vts.su.ac.rs', 5),
(46, 'chole@vts.su.ac.rs', 1),
(47, '26121057@vts.su.ac.rs', 5),
(48, '26121057@vts.su.ac.rs', 5),
(49, '26121057@vts.su.ac.rs', 5),
(50, '26121057@vts.su.ac.rs', 5),
(51, '26121057@vts.su.ac.rs', 5),
(52, '26121057@vts.su.ac.rs', 5),
(53, '26121057@vts.su.ac.rs', 5),
(54, '26121057@vts.su.ac.rs', 5),
(55, '26121057@vts.su.ac.rs', 5),
(56, '26121057@vts.su.ac.rs', 5),
(59, '26121057@vts.su.ac.rs', 5),
(60, '26121057@vts.su.ac.rs', 5),
(61, '26121057@vts.su.ac.rs', 5),
(64, '26121057@vts.su.ac.rs', 5),
(65, '26121057@vts.su.ac.rs', 5),
(66, '26121057@vts.su.ac.rs', 5),
(67, 'chole@vts.su.ac.rs', 1),
(68, '26121057@vts.su.ac.rs', 5),
(73, '26121057@vts.su.ac.rs', 5),
(74, 'chole@vts.su.ac.rs', 1),
(75, '26121057@vts.su.ac.rs', 5),
(76, '26121057@vts.su.ac.rs', 5),
(77, '26121057@vts.su.ac.rs', 5),
(80, '26121057@vts.su.ac.rs', 5),
(82, '26121057@vts.su.ac.rs', 5),
(83, '26121057@vts.su.ac.rs', 5),
(84, '26121057@vts.su.ac.rs', 5),
(85, '26121057@vts.su.ac.rs', 5),
(86, '26121057@vts.su.ac.rs', 5),
(87, '26121057@vts.su.ac.rs', 5),
(88, 'chole@vts.su.ac.rs', 1),
(89, '26121057@vts.su.ac.rs', 5),
(90, 'chole@vts.su.ac.rs', 1),
(91, 'andjell009@gmail.com', 1),
(92, '26121057@vts.su.ac.rs', 5),
(93, '26121057@vts.su.ac.rs', 5),
(94, '26121057@vts.su.ac.rs', 5),
(95, 'andjell009@gmail.com', 1),
(96, '26121057@vts.su.ac.rs', 5),
(97, '26121057@vts.su.ac.rs', 5),
(98, 'andjell009@gmail.com', 1),
(99, 'andjell009@gmail.com', 1),
(100, '26121057@vts.su.ac.rs', 5),
(101, '26121057@vts.su.ac.rs', 5),
(102, '26121057@vts.su.ac.rs', 5),
(103, '26121057@vts.su.ac.rs', 5),
(104, 'andjell009@gmail.com', 1),
(105, 'andjell009@gmail.com', 1),
(106, '26121057@vts.su.ac.rs', 5),
(114, '26121057@vts.su.ac.rs', 5),
(117, 'andjell009@gmail.com', 1),
(118, 'andjell009@gmail.com', 1),
(119, 'andjell009@gmail.com', 1),
(120, 'andjell009@gmail.com', 1),
(125, 'andjell009@gmail.com', 1),
(126, '26121057@vts.su.ac.rs', 5),
(130, '26121057@vts.su.ac.rs', 5),
(131, 'andjell009@gmail.com', 1),
(133, '26121057@vts.su.ac.rs', 5),
(134, 'andjell009@gmail.com', 1),
(136, '26121057@vts.su.ac.rs', 5),
(137, '26121057@vts.su.ac.rs', 5),
(138, '26121057@vts.su.ac.rs', 5),
(139, '26121057@vts.su.ac.rs', 5),
(140, 'andjell009@gmail.com', 1),
(141, '26121057@vts.su.ac.rs', 5),
(142, '26121057@vts.su.ac.rs', 5),
(143, '26121057@vts.su.ac.rs', 5),
(144, '26121057@vts.su.ac.rs', 5),
(145, 'andjell009@gmail.com', 1),
(147, '26121057@vts.su.ac.rs', 5),
(151, 'andjell009@gmail.com', 1),
(152, 'abc@dfe.com', NULL),
(153, 'chole@vts.su.ac.rs', NULL),
(154, 'andjell009@vts.su.ac.rs', NULL),
(155, 'andjell009@gmail.com', 1),
(156, '26121057@vts.su.ac.rs', 5),
(157, '26121057@vts.su.ac.rs', 5),
(158, '26121057@vts.su.ac.rs', 5),
(159, 'andjell009@gmail.com', 1),
(160, 'orville97@ourtimesupport.com', NULL),
(161, 'orville97@ourtimesupport.com', NULL),
(162, 'parkercatherine622@gmail.com', NULL),
(163, 'isobel72@hotmail.com', NULL),
(164, 'isobel72@hotmail.com', NULL),
(165, 'alejandra.franecki35@ourtimesupport.com', NULL),
(166, 'alejandra.franecki35@ourtimesupport.com', NULL),
(167, 'parkercatherine622@gmail.com', NULL),
(168, 'isobel72@hotmail.com', NULL),
(169, 'isobel72@hotmail.com', NULL),
(170, 'nathan.baron5@ourtimesupport.com', NULL),
(171, 'parkercatherine622@gmail.com', NULL),
(172, 'nathan.baron5@ourtimesupport.com', NULL),
(173, 'isobel72@hotmail.com', NULL),
(174, 'isobel72@hotmail.com', NULL),
(175, 'arvel0@orimi.co', NULL),
(176, 'arvel0@orimi.co', NULL),
(177, 'isobel72@hotmail.com', NULL),
(178, 'ghowell00@yahoo.com', NULL),
(179, 'ghowell00@yahoo.com', NULL),
(180, 'walker59@orimi.co', NULL),
(181, 'isobel72@hotmail.com', NULL),
(182, 'walker59@orimi.co', NULL),
(183, 'ghowell00@yahoo.com', NULL),
(184, 'ghowell00@yahoo.com', NULL),
(185, 'taylor33@orimi.co', NULL),
(186, 'taylor33@orimi.co', NULL),
(187, 'isobel72@hotmail.com', NULL),
(188, 'ghowell00@yahoo.com', NULL),
(189, 'ghowell00@yahoo.com', NULL),
(190, '26121057@vts.su.ac.rs', 5),
(191, '26121057@vts.su.ac.rs', 5),
(192, '26121057@vts.su.ac.rs', 5),
(193, '26121057@vts.su.ac.rs', 5),
(194, '26121057@vts.su.ac.rs', 5),
(195, '26121057@vts.su.ac.rs', 5),
(197, 'Лариса', 16),
(198, 'Jevandjel', 1),
(199, 'Лариса', 16),
(200, 'Jevandjel', 1),
(201, 'Jevandjel', 1),
(202, '26121057@vts.su.ac.rs', 5),
(203, '26121057@vts.su.ac.rs', 5),
(204, 'Jevandjel', 1),
(205, '26121057@vts.su.ac.rs', 5),
(206, '26121057@vts.su.ac.rs', 5),
(207, 'Jevandjel', 1),
(208, 'Лариса', 16),
(209, '26121057@vts.su.ac.rs', 5),
(210, 'Jevandjel', 1),
(211, '26121057@vts.su.ac.rs', 5),
(212, '26121057@vts.su.ac.rs', 5),
(213, '26121057@vts.su.ac.rs', 5),
(214, '26121057@vts.su.ac.rs', 5),
(215, '26121057@vts.su.ac.rs', 5),
(216, '26121057@vts.su.ac.rs', 5),
(217, '26121057@', NULL),
(218, '26121057@vts.su.ac.rs', 5),
(219, 'Лариса', 16),
(220, 'Лариса', 16),
(221, 'Лариса', 16),
(222, '26121057@vts.su.ac.rs', 5),
(223, 'andjell009@gmail.com', 1),
(224, '26121057@vts.su.ac.rs', 5),
(225, '26121057@vts.su.ac.rs', 5),
(226, '26121057@vts.su.ac.rs', 5),
(227, '26121057', NULL),
(228, '26121057', NULL),
(229, '26121057@vts.su.ac.rs', 5),
(230, 'larisa.djuric@matkovukovic.edu.rs', 16),
(231, '26121057@vts.su.ac.rs', 5),
(232, '26121057@vts.su.ac.rs', 5),
(233, '26121057@vts.su.ac.rs', 5),
(234, 'Jevanđel', 1),
(235, 'Jevanđel', 1),
(236, '26121057@vts.su.ac.rs', 5),
(237, 'Лариса', 16),
(238, 'Jevanđel', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_email_failures`
--

CREATE TABLE `user_email_failures` (
  `id_user_email_failure` int NOT NULL,
  `id_user` int NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_time_added` datetime NOT NULL,
  `date_time_tried` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wish_list`
--

CREATE TABLE `wish_list` (
  `id` int NOT NULL,
  `item` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `link` text COLLATE utf8mb4_general_ci NOT NULL,
  `event_id` int NOT NULL,
  `user_buying_present` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `is_selected` enum('yes','no') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wish_list`
--

INSERT INTO `wish_list` (`id`, `item`, `link`, `event_id`, `user_buying_present`, `is_selected`) VALUES
(5, 'samsung telefon', 'https://www.samsung.com/hr/smartphones/galaxy-a/galaxy-a54-5g-awesome-black-128gb-sm-a546bzkceue/', 15, NULL, 'no'),
(7, 'Mis', 'https://www.ctshop.rs/logitech-misevi', 15, NULL, 'no'),
(21, 'buket', 'https://www.cvecarakazablanka.com/?gad_source=1&gad_campaignid=12696489331&gbraid=0AAAAADVMLTDEfV60L4WfgKM2r_nv3k8qE&gclid=EAIaIQobChMI4J-40peojgMVHluRBR36rRRcEAAYASAAEgKyk_D_BwE', 12, NULL, 'no');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detects`
--
ALTER TABLE `detects`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `fk_user_detects` (`user_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_created_by` (`created_by`);

--
-- Indexes for table `invites`
--
ALTER TABLE `invites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wish_list_item` (`event_id`),
  ADD KEY `name` (`name`),
  ADD KEY `email` (`email`) USING BTREE;

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `fk_id_event` (`id_event`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id_token`),
  ADD KEY `fk_email` (`email`);

--
-- Indexes for table `users2`
--
ALTER TABLE `users2`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `user_detects`
--
ALTER TABLE `user_detects`
  ADD PRIMARY KEY (`id_user_detects`),
  ADD KEY `fk_users` (`user_details`);

--
-- Indexes for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  ADD PRIMARY KEY (`id_user_email_failure`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `wish_list`
--
ALTER TABLE `wish_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_event_no` (`event_id`),
  ADD KEY `fk_buyer` (`user_buying_present`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detects`
--
ALTER TABLE `detects`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `invites`
--
ALTER TABLE `invites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id_message` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id_token` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users2`
--
ALTER TABLE `users2`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_detects`
--
ALTER TABLE `user_detects`
  MODIFY `id_user_detects` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;

--
-- AUTO_INCREMENT for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  MODIFY `id_user_email_failure` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wish_list`
--
ALTER TABLE `wish_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detects`
--
ALTER TABLE `detects`
  ADD CONSTRAINT `fk_user_detects` FOREIGN KEY (`user_id`) REFERENCES `user_detects` (`id_user_detects`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fk_created_by` FOREIGN KEY (`created_by`) REFERENCES `users2` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invites`
--
ALTER TABLE `invites`
  ADD CONSTRAINT `fk_wish_list_item` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_id_event` FOREIGN KEY (`id_event`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `fk_email` FOREIGN KEY (`email`) REFERENCES `users2` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_detects`
--
ALTER TABLE `user_detects`
  ADD CONSTRAINT `fk_users` FOREIGN KEY (`user_details`) REFERENCES `users2` (`id_user`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  ADD CONSTRAINT `user_email_failures_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users2` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wish_list`
--
ALTER TABLE `wish_list`
  ADD CONSTRAINT `fk_buyer` FOREIGN KEY (`user_buying_present`) REFERENCES `invites` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_event_no` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
