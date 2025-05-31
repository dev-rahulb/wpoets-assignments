-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 31, 2025 at 11:40 AM
-- Server version: 9.1.0
-- PHP Version: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wpoets_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

DROP TABLE IF EXISTS `slides`;
CREATE TABLE IF NOT EXISTS `slides` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tab_id` int NOT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tab_id` (`tab_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`id`, `tab_id`, `tag`, `description`, `image`) VALUES
(1, 1, 'DIGITAL LEARNING INFRASTRUCTURE', 'Slide 1: Usability enhancement for Portal', 'images/DL-Learning-1.jpg'),
(2, 1, 'DIGITAL LEARNING INFRASTRUCTURE', 'Slide 2: Training module delivery', 'images/DL-Technology.jpg'),
(3, 2, 'TECHNOLOGY ENABLEMENT', 'Slide 1: Custom enterprise solutions', 'images/DL-Learning-1.jpg'),
(4, 2, 'TECHNOLOGY ENABLEMENT', 'Slide 2: Performance optimization', 'images/DL-Technology.jpg'),
(5, 3, 'EFFECTIVE COMMUNICATION', 'Slide 1: Scalable communication systems', 'images/DL-Learning-1.jpg'),
(6, 3, 'EFFECTIVE COMMUNICATION', 'Slide 2: Internal collaboration tools', 'images/DL-Technology.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tabs`
--

DROP TABLE IF EXISTS `tabs`;
CREATE TABLE IF NOT EXISTS `tabs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `logo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `tabs`
--

INSERT INTO `tabs` (`id`, `title`, `logo`) VALUES
(1, 'Learning', 'images/DL-learning.svg'),
(2, 'Technology', 'images/DL-technology.svg'),
(3, 'Communication', 'images/DL-communication.svg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
