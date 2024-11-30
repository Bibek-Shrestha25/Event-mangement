-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 07:57 AM
-- Server version: 5.6.25
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventdb`
--
CREATE DATABASE IF NOT EXISTS `eventdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `eventdb`;

-- --------------------------------------------------------

--
-- Table structure for table `audience`
--

DROP TABLE IF EXISTS `audience`;
CREATE TABLE IF NOT EXISTS `audience` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `contact` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `event_id` int(30) NOT NULL,
  `payment_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0= pending, 1 =Paid',
  `attendance_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1= present',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = for verification,  1 = confirmed,2= declined',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `audience`
--

INSERT INTO `audience` (`id`, `name`, `contact`, `email`, `address`, `event_id`, `payment_status`, `attendance_status`, `status`, `date_created`) VALUES
(7, 'raamt', '9807641892', 'raamt@gmail.com', 'Lalitpur', 2, 1, 0, 1, '2023-12-09 22:21:22'),
(15, 'ram', '9870674127', 'ram@gmail.com', 'balkumari', 5, 0, 0, 0, '2024-02-14 12:37:34'),
(17, 'amar', '9801530901', 'amar1@gmail.com', 'Lalitpur', 2, 1, 0, 1, '2024-02-15 18:31:11');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(30) NOT NULL,
  `venue_id` int(30) NOT NULL,
  `event` text NOT NULL,
  `description` text NOT NULL,
  `schedule` datetime NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Public, 2-Private',
  `audience_capacity` int(30) NOT NULL,
  `payment_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Free,payable',
  `amount` double NOT NULL DEFAULT '0',
  `banner` text NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `venue_id`, `event`, `description`, `schedule`, `type`, `audience_capacity`, `payment_type`, `amount`, `banner`, `date_created`) VALUES
(2, 2, 'Holi Celebration', 'Lets have a meetup at Basantapur, Kathmandu on 14th of March (Falgun 29) and lets celebrate holi together with the color of happiness..', '2025-03-14 14:00:00', 1, 1000, 1, 0, '1702134360_Holi.jpg', '0000-00-00 00:00:00'),
(5, 4, 'Concert at Lagankhel', 'Welcome all of you in the biggest concert ever held in nepal with the presence of famous celebrities (Singer, Actor) of nepal. Singer such as Sushant KC, Raju Lama, Trishna Gurung, Pramod Kharel etc. will be performing at the concert. ', '2025-04-20 21:55:00', 1, 10000, 1, 0, '1702140300_concert.jpg', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `cover_img`, `about_content`) VALUES
(1, 'Event Management System', 'EventMSnp@gmail.com', '+977 01-1853167', '1702096740_people-2608316_1920.jpg', 'Your big day is here and you are worried about guests, food, logistics, decorations, sounds and so many big and small details that will eventually make your day perfect. We after experiencing dozens of weddings, celebrations and events have realized that the host barely gets enough time to enjoy their own event being very busy in managing all the details. That is why we started this prestigious company to plan and assist our customers for their weddings and events.&lt;br&gt;&lt;br&gt;\r\nWe will keep your worries at bay by managing everything that goes into your event and lets you do what is more important - be with your friends and family on your big day. Hire us as your wedding and event planner and outsource your worries to us because we care about your events.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1=Admin,2=Staff'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`) VALUES
(1, 'Administrator', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1);

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

DROP TABLE IF EXISTS `venue`;
CREATE TABLE IF NOT EXISTS `venue` (
  `id` int(30) NOT NULL,
  `venue` text NOT NULL,
  `address` text NOT NULL,
  `description` text NOT NULL,
  `rate` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`id`, `venue`, `address`, `description`, `rate`) VALUES
(1, 'Wedding Hall', 'Imadol, Lalitpur', 'A peaceful area specially for a wedding that can make your wedding more better, joyful and beautiful. ', 10000),
(2, 'Celebration Hall', 'Satdobato, Lalitpur', '(Wedding, Birthday) Plan with us and make your day more more beautiful and exciting.', 11000),
(3, 'Conference Hall', 'Thimi, Bhaktapur', 'A venue specially for conference meeting and other events like wedding, parties.', 12000),
(4, 'Grand Events', 'Basantapur, Kathmandu', 'A venue for a Grand events such as concerts, music events, festival celebration, parties etc. ', 15000);

-- --------------------------------------------------------

--
-- Table structure for table `venue_booking`
--

DROP TABLE IF EXISTS `venue_booking`;
CREATE TABLE IF NOT EXISTS `venue_booking` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `venue_id` int(30) NOT NULL,
  `duration` varchar(100) NOT NULL,
  `datetime` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-for verification,1=confirmed,2=canceled'
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `venue_booking`
--

INSERT INTO `venue_booking` (`id`, `name`, `address`, `email`, `contact`, `venue_id`, `duration`, `datetime`, `status`) VALUES
(4, 'Raaman Shrestha', 'Lalitpur', 'raamt@gmail.com', '9813822930', 4, '45 min', '2025-01-10 13:57:00', 1),
(8, 'Raj ', 'lalitpur', 'raj@gmail.com', '9870731267', 2, '4hour', '2025-12-15 07:12:00', 1),
(13, '', '', '', '', 0, '', '0000-00-00 00:00:00', 0),
(15, 'a', 'a', 'a@gmail.com', '1111111111', 3, '1a', '2024-11-30 10:00:00', 2),
(16, 'taria', 'nepal', 'tinea@gmail.com', '9870674123', 2, '12hour', '2025-02-22 12:46:00', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audience`
--
ALTER TABLE `audience`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `venue_booking`
--
ALTER TABLE `venue_booking`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audience`
--
ALTER TABLE `audience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `venue_booking`
--
ALTER TABLE `venue_booking`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
