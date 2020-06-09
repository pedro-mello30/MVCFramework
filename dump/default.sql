-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Oct 02, 2019 at 02:30 PM
-- Server version: 8.0.16
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `default`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id_admin_users` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id_admin_users`, `name`, `email`, `username`, `password`) VALUES
(1, 'Admin', 'admin@70e7.com', 'admin', 'ed5d95a5d2067f1293117ce7c5e2e6260e71b5b15f2e92be86f4066b9c86c2b22747e500dcee81c4c1773c0fce929c2d5fd88f2ee2be3545e8c7c5e3813b9b21');

-- --------------------------------------------------------

--
-- Table structure for table `contato`
--

CREATE TABLE `contato` (
  `id_contato` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `mensagem` text,
  `data` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id_admin_users`);

--
-- Indexes for table `contato`
--
ALTER TABLE `contato`
  ADD PRIMARY KEY (`id_contato`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id_admin_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contato`
--
ALTER TABLE `contato`
  MODIFY `id_contato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
