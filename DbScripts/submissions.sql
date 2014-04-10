-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2014 at 07:40 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE IF NOT EXISTS `submissions` (
  `subID` int(10) NOT NULL AUTO_INCREMENT,
  `emailAddress` text NOT NULL,
  `deptName` varchar(100) DEFAULT NULL,
  `courseName` varchar(100) DEFAULT NULL,
  `docName` varchar(100) DEFAULT NULL,
  `comments` longtext,
  `submissionFile` varchar(500) DEFAULT NULL,
  `instructorInstruction` varchar(500) DEFAULT NULL,
  `studentInstruction` varchar(500) DEFAULT NULL,
  `rubricFileName` varchar(500) DEFAULT NULL,
  `willYouGrade` varchar(3) DEFAULT NULL,
  `createDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edit` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`subID`),
  KEY `deptID` (`deptName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`subID`, `emailAddress`, `deptName`, `courseName`, `docName`, `comments`, `submissionFile`, `instructorInstruction`, `studentInstruction`, `rubricFileName`, `willYouGrade`, `createDate`, `updateDate`, `edit`) VALUES
(80, 'b.dunavent@gmail.com', 'Computer Apps', 'Accounting', 'I''ve changed', 'changed text again', '<a href="../uploads/Computer Apps/1391645505_question-balloon_basic_blue.png">1391645505_question-balloon_basic_blue.png</a>', '', 'qcIcon.ico', '<a href="../uploads/Computer Apps/1HD1CGP126K459240.pdf">1HD1CGP126K459240.pdf</a>', 'Yes', '2014-04-09 02:36:26', '2014-04-09 05:23:44', '<a href="../Submission/submissionProfile.php?subID=80"><img width="13px" src="../Images/edit.png"></a>'),
(82, 'amy@test.com', 'Computer Apps', 'Accounting', 'Amy''s upload', 'Amy test upload', '<a href="../uploads/Computer Apps/A NOTE FROM THE NURSE.docx">A NOTE FROM THE NURSE.docx</a>', '<a href="../uploads/Computer Apps/UseCase.dot">UseCase.dot</a>', '', '<a href="../uploads/Computer Apps/Application_Integration_Fact_Sheet.pdf">Application_Integration_Fact_Sheet.pdf</a>', 'Yes', '2014-04-09 18:38:55', '2014-04-09 19:01:27', '<a href="../Submission/submissionProfile.php?subID=82"><img width="13px" src="../Images/edit.png"></a>');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
