-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2014 at 04:05 AM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=103 ;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`subID`, `emailAddress`, `deptName`, `courseName`, `docName`, `comments`, `submissionFile`, `instructorInstruction`, `studentInstruction`, `rubricFileName`, `willYouGrade`, `createDate`, `updateDate`, `edit`) VALUES
(80, 'b.dunavent@gmail.com', 'Computer Apps', 'Accounting', 'I''ve changed', 'changed text again', '<a href="../uploads/Computer Apps/1391645505_question-balloon_basic_blue.png">1391645505_question-balloon_basic_blue.png</a>', '', 'qcIcon.ico', '<a href="../uploads/Computer Apps/1HD1CGP126K459240.pdf">1HD1CGP126K459240.pdf</a>', 'Yes', '2014-04-09 02:36:26', '2014-04-09 05:23:44', '<a href="../Submission/submissionProfile.php?subID=80"><img width="13px" src="../Images/edit.png"></a>'),
(82, 'amy@test.com', 'Computer Apps', 'Accounting', 'Amy''s upload', 'Amy test upload', '<a href="../uploads/Computer Apps/A NOTE FROM THE NURSE.docx">A NOTE FROM THE NURSE.docx</a>', '<a href="../uploads/Computer Apps/UseCase.dot">UseCase.dot</a>', '', '<a href="../uploads/Computer Apps/Application_Integration_Fact_Sheet.pdf">Application_Integration_Fact_Sheet.pdf</a>', 'Yes', '2014-04-09 18:38:55', '2014-04-09 19:01:27', '<a href="../Submission/submissionProfile.php?subID=82"><img width="13px" src="../Images/edit.png"></a>'),
(83, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:05:23', NULL),
(84, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:20:16', NULL),
(85, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:25:25', NULL),
(86, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:25:50', NULL),
(87, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:27:15', NULL),
(88, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:28:24', NULL),
(89, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:31:28', NULL),
(90, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:32:21', NULL),
(91, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:38:00', NULL),
(92, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:39:47', NULL),
(93, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:42:05', NULL),
(94, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:45:21', NULL),
(95, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:51:16', NULL),
(96, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 22:55:50', NULL),
(97, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 23:04:39', NULL),
(98, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 23:07:40', NULL),
(99, 'b.dunavent@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2014-04-10 23:11:19', NULL),
(100, 'b.dunavent@gmail.com', 'Creative Dance', 'Accounting', 'Yet another subID test', 'sfdasfgasdgfa', '<a href="../uploads/100/BrianDunaventBackCheck.pdf">BrianDunaventBackCheck.pdf</a>', '<a href="../uploads/100/kiPasses.pdf">kiPasses.pdf</a>', '<a href="../uploads/100/invalidShipInfo (2).csv">invalidShipInfo (2).csv</a>', '<a href="../uploads/100/crissAngel.pdf">crissAngel.pdf</a>', 'No', '2014-04-10 23:12:47', '2014-04-10 23:12:47', '<a href="../Submission/submissionProfile.php?subID=100"><img width="13px" src="../Images/edit.png"></a>'),
(101, 'b.dunavent@gmail.com', 'Creative Dance', 'Business Admin', 'test', 'wesehg', '<a href="../uploads/101/xtremeMuscleProCon#.pdf">xtremeMuscleProCon#.pdf</a>', '', '<a href="../uploads/101/QC Requirements.docx">QC Requirements.docx</a>', '', 'No', '2014-04-11 01:58:14', '2014-04-11 01:58:14', '<a href="../Submission/submissionProfile.php?subID=101"><img width="13px" src="../Images/edit.png"></a>'),
(102, 'b.dunavent@gmail.com', 'Creative Dance', 'Business Admin', 'test', 'wesehg', '<a href="../uploads/102/xtremeMuscleProCon#.pdf">xtremeMuscleProCon#.pdf</a>', '', '<a href="../uploads/102/QC Requirements.docx">QC Requirements.docx</a>', '', 'No', '2014-04-11 02:04:06', '2014-04-11 02:04:06', '<a href="../Submission/submissionProfile.php?subID=102"><img width="13px" src="../Images/edit.png"></a>');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
