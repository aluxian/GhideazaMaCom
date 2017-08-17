-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 15, 2013 at 05:07 PM
-- Server version: 5.1.68-cll
-- PHP Version: 5.3.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aluxian_et`
--

-- --------------------------------------------------------

--
-- Table structure for table `search`
--

CREATE TABLE IF NOT EXISTS `search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `query` text NOT NULL,
  `photo` text NOT NULL,
  `place` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `search`
--

INSERT INTO `search` (`id`, `query`, `photo`, `place`, `timestamp`) VALUES
(1, 'Tasmania', 'http://maps.googleapis.com/maps/api/staticmap?center=-41.3650,146.6284&zoom=5&size=220x140&sensor=false', 'http%3A//ghideaza-ma.com//place/info/Tasmania', '2013-05-09 13:56:35'),
(2, 'Belgrad', 'http://maps.googleapis.com/maps/api/staticmap?center=44.82055,20.46222&zoom=9&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Belgrad', '2013-05-09 13:57:44'),
(3, 'Olanda', 'http://maps.googleapis.com/maps/api/staticmap?center=52.13263,5.291265&zoom=6&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Olanda', '2013-05-09 13:57:49'),
(4, 'Paris', 'http://maps.googleapis.com/maps/api/staticmap?center=48.85661,2.352221&zoom=11&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Paris', '2013-05-09 14:23:54'),
(5, 'Londra', 'http://maps.googleapis.com/maps/api/staticmap?center=51.51121,-0.11982&zoom=9&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Londra', '2013-05-09 14:39:04'),
(6, 'Hawaii', 'http://maps.googleapis.com/maps/api/staticmap?center=19.89676,-155.582&zoom=6&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Hawaii', '2013-05-09 15:13:20'),
(7, 'Paris', 'http://maps.googleapis.com/maps/api/staticmap?center=48.85661,2.352221&zoom=11&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Paris', '2013-05-10 15:07:12'),
(8, 'Dubai', 'http://maps.googleapis.com/maps/api/staticmap?center=25.27113,55.30748&zoom=8&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Dubai', '2013-05-10 15:14:54'),
(9, 'New%20York', 'http://maps.googleapis.com/maps/api/staticmap?center=40.71435,-74.0059&zoom=9&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/New%20York', '2013-05-10 15:18:36'),
(10, 'Yosemite', 'https://lh6.googleusercontent.com/-o3WincuSbs8/T7SK19EWX7I/AAAAAAAAAQE/91dswJSO4WQ/w220-h140-s0/IMG_1958.JPG', 'http://ghideaza-ma.com/place/info/Yosemite', '2013-05-10 15:19:32'),
(11, 'Paris', 'http://maps.googleapis.com/maps/api/staticmap?center=48.85661,2.352221&zoom=11&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Paris', '2013-05-11 11:04:15'),
(12, 'Miami', 'http://maps.googleapis.com/maps/api/staticmap?center=25.78896,-80.2264&zoom=10&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Miami', '2013-05-11 12:34:36'),
(13, 'Paris', 'http://maps.googleapis.com/maps/api/staticmap?center=48.85661,2.352221&zoom=11&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Paris', '2013-05-11 12:37:23'),
(14, 'Miami', 'http://maps.googleapis.com/maps/api/staticmap?center=25.78896,-80.2264&zoom=10&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Miami', '2013-05-12 08:05:31'),
(15, 'Paris', 'http://maps.googleapis.com/maps/api/staticmap?center=48.85661,2.352221&zoom=11&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Paris', '2013-05-12 08:17:34'),
(16, 'Barbados', 'http://maps.googleapis.com/maps/api/staticmap?center=13.19388,-59.5431&zoom=10&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Barbados', '2013-05-12 08:54:26'),
(17, 'Belgrad', 'http://maps.googleapis.com/maps/api/staticmap?center=44.82055,20.46222&zoom=9&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Belgrad', '2013-05-12 08:54:34'),
(18, 'Pisa', 'http://maps.googleapis.com/maps/api/staticmap?center=43.72283,10.40168&zoom=11&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Pisa', '2013-05-12 08:54:42'),
(19, 'Paris', 'http://maps.googleapis.com/maps/api/staticmap?center=48.85661,2.352221&zoom=11&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Paris', '2013-05-12 09:23:20'),
(20, 'Salzburg', 'http://maps.googleapis.com/maps/api/staticmap?center=47.80949,13.05501&zoom=10&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Salzburg', '2013-05-14 15:36:40'),
(21, 'Barbados', 'http://maps.googleapis.com/maps/api/staticmap?center=13.19388,-59.5431&zoom=10&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Barbados', '2013-05-15 13:17:33'),
(22, 'Salzburg', 'http://maps.googleapis.com/maps/api/staticmap?center=47.80949,13.05501&zoom=10&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Salzburg', '2013-05-15 13:17:42'),
(23, 'Pisa', 'http://maps.googleapis.com/maps/api/staticmap?center=43.72283,10.40168&zoom=11&size=220x140&sensor=false', 'http://ghideaza-ma.com/place/info/Pisa', '2013-05-15 14:00:36');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
