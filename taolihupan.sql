-- phpMyAdmin SQL Dump
-- version 3.5.6
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 18, 2013 at 10:54 AM
-- Server version: 5.5.28
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `houhongru_taolihupan`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(160) NOT NULL DEFAULT '',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `address` varchar(160) NOT NULL DEFAULT '',
  `content` text,
  `stu_num` varchar(8) NOT NULL DEFAULT '',
  `pass` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_activities` (`stu_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activities_tag`
--

CREATE TABLE IF NOT EXISTS `activities_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activities_id` int(10) NOT NULL DEFAULT '0',
  `content` char(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE IF NOT EXISTS `book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `author` varchar(50) NOT NULL DEFAULT '',
  `publish_company` varchar(50) NOT NULL DEFAULT '',
  `search_number` varchar(30) NOT NULL DEFAULT '',
  `cover` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `book_comment`
--

CREATE TABLE IF NOT EXISTS `book_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stu_num` varchar(8) NOT NULL DEFAULT '',
  `book_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `title` varchar(60) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `FK_book_review` (`book_id`),
  KEY `FK_book_review1` (`stu_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `book_tag`
--

CREATE TABLE IF NOT EXISTS `book_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `BookIndex` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `community`
--

CREATE TABLE IF NOT EXISTS `community` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `leader` varchar(20) NOT NULL DEFAULT '',
  `tel` varchar(15) NOT NULL DEFAULT '',
  `photo` varchar(200) DEFAULT NULL,
  `about` text NOT NULL,
  `adder` varchar(8) NOT NULL DEFAULT '',
  `pass` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `concern`
--

CREATE TABLE IF NOT EXISTS `concern` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idol` varchar(8) NOT NULL DEFAULT '',
  `fan` varchar(8) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idol` (`idol`,`fan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE IF NOT EXISTS `experience` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stu_num` varchar(8) NOT NULL DEFAULT '',
  `grade` varchar(10) NOT NULL DEFAULT '',
  `title` varchar(60) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `stu_num` (`stu_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `play`
--

CREATE TABLE IF NOT EXISTS `play` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(160) NOT NULL DEFAULT '',
  `address` varchar(160) NOT NULL DEFAULT '',
  `content` text,
  `via` varchar(8) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `play_tag`
--

CREATE TABLE IF NOT EXISTS `play_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `play_id` int(11) NOT NULL DEFAULT '0',
  `content` varchar(22) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `stu_num` varchar(8) NOT NULL DEFAULT '',
  `email` varchar(40) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `active` varchar(32) DEFAULT NULL,
  `registration_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `stop` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stu_num`),
  UNIQUE KEY `email` (`email`),
  KEY `stu_num` (`stu_num`,`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `students_page`
--

CREATE TABLE IF NOT EXISTS `students_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_num` varchar(8) NOT NULL DEFAULT '',
  `img` varchar(200) DEFAULT NULL,
  `music_title` varchar(30) DEFAULT NULL,
  `music_addr` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stu_num` (`stu_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `students_page_comment`
--

CREATE TABLE IF NOT EXISTS `students_page_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host` varchar(8) NOT NULL DEFAULT '',
  `via` varchar(8) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `to` (`host`,`via`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `students_profile`
--

CREATE TABLE IF NOT EXISTS `students_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_num` varchar(8) NOT NULL DEFAULT '',
  `name` varchar(16) NOT NULL DEFAULT '',
  `academy` varchar(22) NOT NULL DEFAULT '',
  `sex` varchar(4) NOT NULL DEFAULT '',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `hometown` varchar(6) NOT NULL DEFAULT '',
  `phone` varchar(11) DEFAULT NULL,
  `qq` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stu_num` (`stu_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yaonan`
--

CREATE TABLE IF NOT EXISTS `yaonan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `via` varchar(8) NOT NULL DEFAULT '',
  `topic` varchar(60) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `via` (`via`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yaonan_reply`
--

CREATE TABLE IF NOT EXISTS `yaonan_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `belong` int(11) NOT NULL DEFAULT '0',
  `via` varchar(8) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `belong` (`belong`,`via`),
  KEY `via` (`via`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `FK_activities` FOREIGN KEY (`stu_num`) REFERENCES `students` (`stu_num`);

--
-- Constraints for table `book_comment`
--
ALTER TABLE `book_comment`
  ADD CONSTRAINT `FK_book_review` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`),
  ADD CONSTRAINT `FK_book_review1` FOREIGN KEY (`stu_num`) REFERENCES `students` (`stu_num`);

--
-- Constraints for table `book_tag`
--
ALTER TABLE `book_tag`
  ADD CONSTRAINT `FK_book_tag` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`);

--
-- Constraints for table `experience`
--
ALTER TABLE `experience`
  ADD CONSTRAINT `experience_ibfk_1` FOREIGN KEY (`stu_num`) REFERENCES `students` (`stu_num`);

--
-- Constraints for table `students_profile`
--
ALTER TABLE `students_profile`
  ADD CONSTRAINT `students_profile_ibfk_1` FOREIGN KEY (`stu_num`) REFERENCES `students` (`stu_num`);

--
-- Constraints for table `yaonan`
--
ALTER TABLE `yaonan`
  ADD CONSTRAINT `yaonan_ibfk_1` FOREIGN KEY (`via`) REFERENCES `students` (`stu_num`);

--
-- Constraints for table `yaonan_reply`
--
ALTER TABLE `yaonan_reply`
  ADD CONSTRAINT `yaonan_reply_ibfk_1` FOREIGN KEY (`belong`) REFERENCES `yaonan` (`id`),
  ADD CONSTRAINT `yaonan_reply_ibfk_2` FOREIGN KEY (`via`) REFERENCES `students` (`stu_num`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
