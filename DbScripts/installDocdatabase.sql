-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 04, 2014 at 04:58 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `docdatabase`
--

DELIMITER $$
--
-- Functions
--
DROP FUNCTION IF EXISTS `GetCourseName`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `GetCourseName`(`cID` INT) RETURNS varchar(100) CHARSET latin1
    DETERMINISTIC
BEGIN
DECLARE cName VARCHAR(100);
SELECT courseName
INTO cName
FROM courses
WHERE courseID = cID;
RETURN cName;
END$$

DROP FUNCTION IF EXISTS `GetDeptName`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `GetDeptName`(`dID` INT) RETURNS varchar(100) CHARSET latin1
    DETERMINISTIC
BEGIN
DECLARE dName VARCHAR(100);
SELECT deptName
INTO dName
FROM departments
WHERE deptID = dID;
RETURN dName;
END$$

DROP FUNCTION IF EXISTS `GetUserName`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `GetUserName`(`uID` INT) RETURNS text CHARSET latin1
    DETERMINISTIC
BEGIN
DECLARE uName TEXT;
SELECT fName
INTO uName
FROM users
WHERE userID = uID;
RETURN uName;
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseID`, `courseName`, `createDate`, `updateDate`) VALUES
(1, 'Biology 101', '2014-04-04 01:50:16', '0000-00-00 00:00:00');

--
-- Triggers `courses`
--
DROP TRIGGER IF EXISTS `courses_creation_timestamp`;
DELIMITER //
CREATE TRIGGER `courses_creation_timestamp` BEFORE INSERT ON `courses`
 FOR EACH ROW SET NEW.createDate = NOW()
//
DELIMITER ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`deptID`, `deptName`, `createDate`, `updateDate`) VALUES
(2, 'Science', '2014-04-04 01:37:14', '0000-00-00 00:00:00'),
(3, 'Math', '2014-04-04 01:45:30', '0000-00-00 00:00:00'),
(4, 'History', '2014-04-04 01:56:44', '0000-00-00 00:00:00');

--
-- Triggers `departments`
--
DROP TRIGGER IF EXISTS `departments_creation_timestamp`;
DELIMITER //
CREATE TRIGGER `departments_creation_timestamp` BEFORE INSERT ON `departments`
 FOR EACH ROW SET NEW.createDate = NOW()
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
CREATE TABLE IF NOT EXISTS `submissions` (
  `subID` int(10) NOT NULL AUTO_INCREMENT,
  `emailAddress` text,
  `deptName` varchar(100) NOT NULL,
  `courseName` varchar(100) NOT NULL,
  `docName` varchar(100) NOT NULL,
  `comments` varchar(5) DEFAULT NULL,
  `instructorInstruction` varchar(5) DEFAULT NULL,
  `studentInstruction` varchar(5) DEFAULT NULL,
  `rubricFileName` varchar(5) DEFAULT NULL,
  `willYouGrade` tinyint(1) DEFAULT NULL,
  `createDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`subID`),
  KEY `deptID` (`deptName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(10) NOT NULL AUTO_INCREMENT,
  `userType` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `emailAddress` text NOT NULL,
  `emailOptIn` text,
  `isValidated` text NOT NULL,
  `tempPassKey` varchar(128) DEFAULT NULL,
  `createDate` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`),
  KEY `userTypeID` (`userType`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userType`, `password`, `fname`, `lname`, `emailAddress`, `emailOptIn`, `isValidated`, `tempPassKey`, `createDate`, `updateDate`) VALUES
(1, 'ADMIN', '0d09e70c09a34be3114f40716eafd6b690195830d19b3aba4adb8f0cddf3634350bfe48d617d7003bd07d9b5c2039e553c5545bafc825f65d402e4caa5fbc2e3', 'Brian', 'Dunavent', 'dunavebc@mail.uc.edu', 'YES', 'YES', NULL, '2014-04-04 04:48:15', '2014-04-04 04:13:35'),
(2, 'ADMIN', 'b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86', 'Test', 'User', 'test@test.com', 'YES', 'YES', NULL, '2014-04-04 01:33:52', '2014-04-04 04:13:46');

--
-- Triggers `users`
--
DROP TRIGGER IF EXISTS `users_creation_timestamp`;
DELIMITER //
CREATE TRIGGER `users_creation_timestamp` BEFORE INSERT ON `users`
 FOR EACH ROW SET NEW.createDate = NOW()
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `usertypes`
--

DROP TABLE IF EXISTS `usertypes`;
CREATE TABLE IF NOT EXISTS `usertypes` (
  `userTypeID` int(10) NOT NULL AUTO_INCREMENT,
  `userTypeName` varchar(50) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userTypeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `usertypes`
--

INSERT INTO `usertypes` (`userTypeID`, `userTypeName`, `createDate`, `updateDate`) VALUES
(1, 'STANDARD', '2014-03-24 01:29:04', '2014-03-24 01:29:04'),
(2, 'ADMIN', '2014-03-24 01:29:16', '2014-03-24 01:29:16');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
