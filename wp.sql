-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2023 at 09:57 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wp`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `registration_token` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `registration_expires` datetime DEFAULT NULL,
  `active` smallint(1) NOT NULL DEFAULT 0,
  `forgotten_password_token` char(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forgotten_password_expires` datetime DEFAULT NULL,
  `is_banned` smallint(1) NOT NULL DEFAULT 0,
  `date_time` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `email`, `password`, `firstname`, `lastname`, `registration_token`, `registration_expires`, `active`, `forgotten_password_token`, `forgotten_password_expires`, `is_banned`, `date_time`) VALUES
(1, 'chole@vts.su.ac.rs', '$2y$10$T2rAKmfqlxBUe34mh.b9u.jNfB9vUjQSaf9I/4/o5K22xh5E4bFJS', 'Zlatko', 'Covic', '', '0000-00-00 00:00:00', 1, '', '0000-00-00 00:00:00', 0, '2023-04-23 20:59:15');

-- --------------------------------------------------------

--
-- Table structure for table `user_email_failures`
--

CREATE TABLE `user_email_failures` (
  `id_user_email_failure` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date_time_added` datetime NOT NULL,
  `date_time_tried` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  ADD PRIMARY KEY (`id_user_email_failure`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  MODIFY `id_user_email_failure` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  ADD CONSTRAINT `user_email_failures_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
