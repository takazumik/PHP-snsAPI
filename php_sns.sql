-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 16, 2020 at 10:45 AM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_sns`
--
CREATE DATABASE IF NOT EXISTS `php_sns` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `php_sns`;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `message`, `date`, `user_id`, `modified`) VALUES
(13, 'テスト3\n', '2020-03-12 14:40:08', 0, '2020-03-16 07:02:38'),
(14, 'テスト２', '2020-03-12 14:41:05', 0, '2020-03-16 07:02:38'),
(15, 'うんち', '2020-03-12 15:05:58', 0, '2020-03-16 07:02:38'),
(16, 'うんちもらせ\n', '2020-03-12 15:06:03', 0, '2020-03-16 07:02:38'),
(17, 'うんちもらせ\n', '2020-03-12 15:08:11', 0, '2020-03-16 07:02:38'),
(18, 'うんちもらせ\n', '2020-03-12 15:08:12', 0, '2020-03-16 07:02:38'),
(19, 'うんちもらせ\n', '2020-03-12 15:08:12', 0, '2020-03-16 07:02:38'),
(20, 'うんちもらせ\n', '2020-03-12 15:08:12', 49, '2020-03-16 10:11:13'),
(21, 'うんちもらせ\n', '2020-03-12 15:08:13', 0, '2020-03-16 07:02:38'),
(22, 'うんちもらせ\n', '2020-03-12 15:08:13', 0, '2020-03-16 07:02:38'),
(23, 'うんちもらせ\n', '2020-03-12 15:08:13', 0, '2020-03-16 07:02:38'),
(24, 'うんちもらせ\n', '2020-03-12 15:12:53', 0, '2020-03-16 07:02:38'),
(25, 'うんちもらせ\n', '2020-03-12 15:14:26', 0, '2020-03-16 07:02:38'),
(26, 'うんちもらした\n', '2020-03-12 15:16:53', 0, '2020-03-16 07:02:38'),
(27, '', '2020-03-12 17:34:48', 0, '2020-03-16 07:02:38'),
(28, 'test', '2020-03-12 18:41:19', 0, '2020-03-16 07:02:38'),
(29, '最新の投稿', '2020-03-12 18:44:19', 0, '2020-03-16 07:02:38'),
(30, '最新の投稿2', '2020-03-12 18:44:57', 0, '2020-03-16 07:02:38'),
(31, 'しっこー', '2020-03-12 18:54:39', 12335, '2020-03-16 07:02:38'),
(32, 'ぽっっぽ', '2020-03-12 18:59:19', 0, '2020-03-16 07:02:38'),
(33, 'いっけーー', '2020-03-12 19:04:07', 0, '2020-03-16 07:02:38'),
(34, 'なぜだー＝', '2020-03-12 19:05:13', 0, '2020-03-16 07:02:38'),
(35, 'おちん', '2020-03-12 19:08:46', 33, '2020-03-16 07:02:38'),
(36, 'それそれー', '2020-03-12 19:12:14', 33, '2020-03-16 07:02:38'),
(37, 'できたぜーーーー', '2020-03-12 19:12:25', 33, '2020-03-16 07:02:38'),
(38, 'めっこ', '2020-03-12 19:12:36', 33, '2020-03-16 07:02:38'),
(39, 'パスタ食べてるなう', '2020-03-16 13:51:14', 33, '2020-03-16 07:02:38'),
(40, '0', '2020-03-16 14:32:58', 33, '2020-03-16 08:50:58'),
(41, '変えた！！！！！！', '2020-03-16 15:43:32', 33, '2020-03-16 08:51:43'),
(42, 'aaaa\n', '2020-03-16 17:26:35', 33, '2020-03-16 09:15:45'),
(43, 'MISERI', '2020-03-16 18:35:56', 33, '2020-03-16 09:35:56'),
(44, 'MISERI', '2020-03-16 18:39:58', 33, '2020-03-16 09:39:58'),
(45, 'MISERI', '2020-03-16 18:39:59', 33, '2020-03-16 09:39:59'),
(46, 'MISERI', '2020-03-16 18:40:07', 33, '2020-03-16 09:40:07'),
(47, 'MISERI', '2020-03-16 18:40:07', 33, '2020-03-16 09:40:07'),
(48, 'MISERI', '2020-03-16 18:40:07', 33, '2020-03-16 09:40:07'),
(49, 'MISERI', '2020-03-16 18:51:26', 49, '2020-03-16 09:51:26'),
(50, 'MISERIMISERIMISERIMISERIMISERI', '2020-03-16 18:52:41', 49, '2020-03-16 09:52:41'),
(51, 'MISERIMISERIMISERIMISERIMISERI', '2020-03-16 18:54:31', 49, '2020-03-16 09:54:31'),
(52, 'MISERIMISERIMISERIMISERIMISERI', '2020-03-16 18:55:50', 49, '2020-03-16 09:55:50'),
(53, 'MISERIMISERIMISERIMISERIMISERI', '2020-03-16 18:55:51', 49, '2020-03-16 09:55:51'),
(54, 'MISERIMISERIMISERIMISERIMISERI', '2020-03-16 18:55:51', 49, '2020-03-16 09:55:51'),
(55, 'MISERIMISERIMISERIMISERIMISERI', '2020-03-16 18:55:51', 49, '2020-03-16 09:55:51'),
(56, 'MISERIMISERIMISERIMISERIMISERI', '2020-03-16 18:55:51', 49, '2020-03-16 09:55:51'),
(57, 'MISERIMISERIMISERIMISERIMISERI', '2020-03-16 18:55:51', 49, '2020-03-16 09:55:51'),
(58, 'MISERIMISERIMISERIMISERIMISERI', '2020-03-16 18:55:51', 49, '2020-03-16 09:55:51'),
(59, 'yuzunoha', '2020-03-16 18:56:34', 36, '2020-03-16 09:56:34'),
(60, 'じｄｊふぁさ', '2020-03-16 19:00:18', 36, '2020-03-16 10:00:18'),
(61, 'じｄｊふぁさ', '2020-03-16 19:04:13', 49, '2020-03-16 10:04:13'),
(62, 'なんでだよ', '2020-03-16 19:17:27', 49, '2020-03-16 10:17:27'),
(63, 'なんでだよ', '2020-03-16 19:20:32', 49, '2020-03-16 10:20:32'),
(64, 'なんでだよ', '2020-03-16 19:20:33', 49, '2020-03-16 10:20:33'),
(65, '', '2020-03-16 19:24:53', 49, '2020-03-16 10:24:53'),
(66, 'テストです', '2020-03-16 19:26:45', 48, '2020-03-16 10:26:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `bio` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `bio`, `email`, `password`, `created_at`, `updated_at`, `token`) VALUES
(28, 'テスト4', 'bio4', 'email4', 'password4', '2020-03-11 15:09:40', '2020-03-11 15:09:40', ''),
(29, 'テスト5', '5', '5', '6', '2020-03-11 15:15:50', '2020-03-11 15:15:50', ''),
(31, 'テスト6', '6', '7', '7', '2020-03-11 15:17:28', '2020-03-11 15:17:28', ''),
(32, 'テスト7', '8', '8', '8', '2020-03-11 15:20:39', '2020-03-11 15:20:39', ''),
(33, '今変えました', '変えました', 'takazumi', 'takazumi', '2020-03-12 15:33:17', '2020-03-12 15:33:17', '493360907edae44f43d97bf3cd2b1adf7f403f5f52b5b7d04401ab3110a95d99'),
(35, 'yuzunoha02', 'yuzunoha02', 'yuzunoha02', 'yuzunoha02', '2020-03-13 16:44:54', '2020-03-13 16:44:54', 'cfeb7a795c46bacb73ae6567f844b8d65220b43cbc069c8343b5ad724f098d0f'),
(37, 'yuzunoha01', 'yuzunoha01', 'yuzunoha01', 'yuzunoha01', '2020-03-13 16:45:27', '2020-03-13 16:45:27', 'ddc4b18d5a71616460c19efe4f72fb3695c618146e74bb6ed4558e3562bd777f'),
(38, 'amazarashi01', 'amazarashi01', 'amazarashi01', 'amazarashi01', '2020-03-13 16:45:44', '2020-03-13 16:45:44', 'e20628f74e73ff2d9df0c21404f864457e097d35b2b64ff2fd1ea30b19c78572'),
(39, 'amazarashi02', 'amazarashi02', 'amazarashi02', 'amazarashi02', '2020-03-13 16:45:51', '2020-03-13 16:45:51', '26ac2a3f2405f106140028d39a1a624b1b2408cd42a72b2053c750c21d8dc497'),
(40, 'amazarashi03', 'amazarashi03', 'amazarashi03', 'amazarashi03', '2020-03-13 16:46:00', '2020-03-13 16:46:00', 'f46ab504ff0cfb52443fde621f67862d73d08d86c2bc9b3e04875766f8306cc7'),
(41, 'amazarashi04', 'amazarashi04', 'amazarashi04', 'amazarashi04', '2020-03-13 16:46:15', '2020-03-13 16:46:15', '634280da3e324191b75e945b349c3848254741d1000c7e8bb123bd3e05f9e95c'),
(44, 'tesuto01', 'tesuto01', 'tesuto01', 'tesuto01tesuto01', '2020-03-13 17:53:43', '2020-03-13 17:53:43', '8aaaf49cfc0d13a60b28d7053984c9048e7f27ab8a028c47a9f3f4aefd659c2f'),
(45, '', '', '', '', '2020-03-16 18:26:59', '2020-03-16 18:26:59', 'a830a06b35a53ca028198f4dede00b62a73fea7b87d3df46da220f0b46b1762e'),
(48, 'ちんちん', 'ちんこぽ', 'DAIKI', 'DAIKI', '2020-03-16 18:27:12', '2020-03-16 18:27:12', 'fe7e320dd2751c965175a02255a47f4eb249f84bbba346e2a107273ee9959188'),
(49, '', 'うんち', 'MISERI', 'MISERI', '2020-03-16 18:35:46', '2020-03-16 18:35:46', '9b72713ac34a1b5a0ffed41f038a8c2f2d2810c4a6dff86fdf0784b49deefa5e');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
