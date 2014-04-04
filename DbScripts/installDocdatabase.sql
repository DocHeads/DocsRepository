-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2014 at 02:57 AM
-- Server version: 5.5.34
-- PHP Version: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `docdatabase`
--
CREATE DATABASE IF NOT EXISTS `docdatabase` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `docdatabase`;

DELIMITER $$
--
-- Functions
--
DROP FUNCTION IF EXISTS `GetCourseName`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `GetCourseName`(cID INT) RETURNS varchar(100) CHARSET latin1
BEGIN
  DECLARE cName VARCHAR(100);
  SELECT courseName 
  INTO cName
  FROM courses 
  WHERE courseID = cID;
  RETURN cName;
END$$

DROP FUNCTION IF EXISTS `GetDeptName`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `GetDeptName`(deptId INT) RETURNS varchar(100) CHARSET latin1
BEGIN
  DECLARE dName VARCHAR(100);
  SELECT deptName 
  INTO dName
  FROM departments 
  WHERE deptID = deptId;
  RETURN dName;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `courseID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `courseName` varchar(100) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `deptID` int(10) NOT NULL AUTO_INCREMENT,
  `deptName` varchar(100) NOT NULL,
  `createDate` timestamp NULL DEFAULT NULL,
  `updateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`deptID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
CREATE TABLE IF NOT EXISTS `submissions` (
  `subID` int(10) NOT NULL AUTO_INCREMENT,
  `userID` int(10) DEFAULT NULL,
  `deptID` int(10) NOT NULL,
  `courseID` int(10) NOT NULL,
  `docName` varchar(100) NOT NULL,
  `comments` varchar(5) DEFAULT NULL,
  `instructorInstruction` varchar(5) DEFAULT NULL,
  `studentInstruction` varchar(5) DEFAULT NULL,
  `rubricFileName` varchar(5) DEFAULT NULL,
  `willYouGrade` tinyint(1) DEFAULT NULL,
  `createDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`subID`),
  KEY `deptID` (`deptID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(10) NOT NULL AUTO_INCREMENT,
  `userTypeID` int(10) NOT NULL,
  `password` varchar(128) NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `emailAddress` text NOT NULL,
  `emailOptIn` tinyint(1) DEFAULT NULL,
  `isValidated` tinyint(1) NOT NULL,
  `tempPassKey` varchar(128) DEFAULT NULL,
  `createDate` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`),
  KEY `userTypeID` (`userTypeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userTypeID`, `password`, `fname`, `lname`, `emailAddress`, `emailOptIn`, `isValidated`, `tempPassKey`, `createDate`, `updateDate`) VALUES
(1, 1, '0d09e70c09a34be3114f40716eafd6b690195830d19b3aba4adb8f0cddf3634350bfe48d617d7003bd07d9b5c2039e553c5545bafc825f65d402e4caa5fbc2e3', 'Brian', 'Dunavent', 'dunavebc@mail.uc.edu', 1, 1, NULL, '2014-04-04 00:48:15', '2014-04-04 00:50:08');

-- --------------------------------------------------------

--
-- Table structure for table `usertypes`
--

DROP TABLE IF EXISTS `usertypes`;
CREATE TABLE IF NOT EXISTS `usertypes` (
  `userTypeID` int(10) NOT NULL AUTO_INCREMENT,
  `userTypeName` varchar(100) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userTypeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `usertypes`
--

INSERT INTO `usertypes` (`userTypeID`, `userTypeName`, `createDate`, `updateDate`) VALUES
(1, 'STANDARD', '2014-03-23 21:29:04', '2014-03-23 21:29:04'),
(2, 'ADMIN', '2014-03-23 21:29:16', '2014-03-23 21:29:16');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `fk_Depts_2_Submissions` FOREIGN KEY (`deptID`) REFERENCES `departments` (`deptID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_UserTypes_2_Users` FOREIGN KEY (`userTypeID`) REFERENCES `usertypes` (`userTypeID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
