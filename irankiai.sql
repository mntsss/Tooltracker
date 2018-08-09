-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 2018 m. Bal 03 d. 04:15
-- Server version: 5.7.19
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `irankiai`
--

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `ClientID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ClientName` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `ClientStatus` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `ClientPhone` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `ClientEmail` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `ClientContactName` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `ClientIdentificationHash` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `ClientPayedTill` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ClientID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `clients`
--

INSERT INTO `clients` (`ClientID`, `ClientName`, `ClientStatus`, `ClientPhone`, `ClientEmail`, `ClientContactName`, `ClientIdentificationHash`, `ClientPayedTill`, `created_at`, `updated_at`) VALUES
(1, 'Carpro LT', 'active', '', '', '', 'sf46g5s4df65g4sd65fg46sd5f4g6sdf4g', '2018-04-30', NULL, NULL);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `ItemID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ItemName` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `ItemCode` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `ItemQuantity` int(11) NOT NULL DEFAULT '1',
  `ItemImage` varchar(191) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `ItemGroupID` int(11) NOT NULL,
  `ClientID` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `item_groups`
--

DROP TABLE IF EXISTS `item_groups`;
CREATE TABLE IF NOT EXISTS `item_groups` (
  `ItemGroupID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ItemGroupName` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `ItemGroupImage` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `ClientID` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ItemGroupID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `item_groups`
--

INSERT INTO `item_groups` (`ItemGroupID`, `ItemGroupName`, `ItemGroupImage`, `ClientID`, `created_at`, `updated_at`) VALUES
(1, 'Pjūklai', '1522417973.jpeg', 1, '2018-03-30 13:52:53', '2018-03-30 13:52:53'),
(2, 'Kirviai', '1522419221.jpg', 1, '2018-03-30 14:13:41', '2018-03-30 14:13:41'),
(3, 'Plaktukai tikrinu kaip su', '1522419328.jpg', 1, '2018-03-30 14:15:28', '2018-03-30 14:15:28'),
(4, 'Kastuvai', '1522419374.jpg', 1, '2018-03-30 14:16:14', '2018-03-30 14:16:14'),
(5, 'Betono maišyklė', '1522420353.jpg', 1, '2018-03-30 14:32:33', '2018-03-30 14:32:33'),
(6, 'Drėlės', '1522725123.jpg', 1, '2018-04-03 03:12:03', '2018-04-03 03:12:03');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `item_withdrawals`
--

DROP TABLE IF EXISTS `item_withdrawals`;
CREATE TABLE IF NOT EXISTS `item_withdrawals` (
  `ItemWithdrawalID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ItemWithdrawalQuentity` int(11) NOT NULL DEFAULT '1',
  `ItemWithdrawalReturned` tinyint(1) NOT NULL DEFAULT '0',
  `ItemWithdrawalReturnedQuantity` int(11) DEFAULT NULL,
  `WorkerID` int(11) DEFAULT NULL,
  `ObjectID` int(11) DEFAULT NULL,
  `ClientID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ItemWithdrawalID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2018_03_28_124441_create_items_table', 1),
(5, '2018_03_28_124511_create_item_groups_table', 1),
(6, '2018_03_28_124518_create_workers_table', 1),
(7, '2018_03_28_124530_create_item_withdrawals_table', 1),
(8, '2018_03_28_124540_create_objects_table', 1),
(9, '2014_10_12_000000_create_users_table', 2),
(10, '2017_10_31_131405_create_clients_table', 2);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `objects`
--

DROP TABLE IF EXISTS `objects`;
CREATE TABLE IF NOT EXISTS `objects` (
  `ObjectID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ObjectName` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `ObjectFinished` tinyint(1) NOT NULL DEFAULT '0',
  `ClientID` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ObjectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `token` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `Username` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `UserPhone` varchar(191) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `UserLastSeen` varchar(191) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `ClientID` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `users`
--

INSERT INTO `users` (`UserID`, `email`, `Username`, `UserPhone`, `password`, `UserLastSeen`, `ClientID`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'mntsss@carpro.lt', 'Mantas Muliarcikas', NULL, '$2y$10$N2giIg4IpU..mvgTw4vUAuTvbj175e2hWKBk9MUTQ8/ihOnOwiAQu', NULL, '1', 'OiBZXThxhpAnPDwKtqx4zBid02ddOfQqzlwfPcfjs14b3iClE8SVhc0E7UIy', '2018-03-29 14:33:29', '2018-03-29 14:33:29');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `workers`
--

DROP TABLE IF EXISTS `workers`;
CREATE TABLE IF NOT EXISTS `workers` (
  `WorkerID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `WorkerName` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `WorkerCode` varchar(191) COLLATE utf8_lithuanian_ci NOT NULL,
  `ClientID` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`WorkerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
