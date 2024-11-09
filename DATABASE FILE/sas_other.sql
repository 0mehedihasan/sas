-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:5222
-- Generation Time: Nov 09, 2024 at 09:25 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sas_other`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`) VALUES
(1, 'Super', 'Admin', 'super@admin', 'admin'),
(2, 'Admin', 'SAS Other', 'admin@other', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `Id` int(10) NOT NULL,
  `admissionNo` varchar(255) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL,
  `sessionTermId` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  `dateTimeTaken` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`Id`, `admissionNo`, `classId`, `classArmId`, `sessionTermId`, `status`, `dateTimeTaken`) VALUES
(256, '20243003', '13', '', '', '1', '2024-11-08'),
(255, '20243002', '13', '', '', '1', '2024-11-08'),
(254, '20243001', '13', '', '', '1', '2024-11-08'),
(253, '20243031', '13', '', '', '1', '2024-11-07'),
(252, '20243030', '13', '', '', '1', '2024-11-07'),
(251, '20243029', '13', '', '', '1', '2024-11-07'),
(250, '20243028', '13', '', '', '1', '2024-11-07'),
(249, '20243027', '13', '', '', '0', '2024-11-07'),
(248, '20243026', '13', '', '', '1', '2024-11-07'),
(247, '20243005', '13', '', '', '1', '2024-11-07'),
(246, '20243004', '13', '', '', '1', '2024-11-07'),
(245, '20243003', '13', '', '', '1', '2024-11-07'),
(244, '20243002', '13', '', '', '1', '2024-11-07'),
(243, '20243001', '13', '', '', '1', '2024-11-07'),
(242, '20243031', '12', '', '', '1', '2024-11-07'),
(241, '20243030', '12', '', '', '1', '2024-11-07'),
(240, '20243029', '12', '', '', '1', '2024-11-07'),
(239, '20243028', '12', '', '', '1', '2024-11-07'),
(238, '20243027', '12', '', '', '0', '2024-11-07'),
(237, '20243026', '12', '', '', '1', '2024-11-07'),
(236, '20243025', '12', '', '', '0', '2024-11-07'),
(235, '20243024', '12', '', '', '0', '2024-11-07'),
(234, '20243023', '12', '', '', '0', '2024-11-07'),
(233, '20243022', '12', '', '', '0', '2024-11-07'),
(232, '20243021', '12', '', '', '0', '2024-11-07'),
(231, '20243020', '12', '', '', '0', '2024-11-07'),
(230, '20243019', '12', '', '', '0', '2024-11-07'),
(229, '20243018', '12', '', '', '0', '2024-11-07'),
(228, '20243017', '12', '', '', '0', '2024-11-07'),
(227, '20243016', '12', '', '', '0', '2024-11-07'),
(226, '20243015', '12', '', '', '0', '2024-11-07'),
(225, '20243014', '12', '', '', '0', '2024-11-07'),
(224, '20243013', '12', '', '', '0', '2024-11-07'),
(223, '20243012', '12', '', '', '0', '2024-11-07'),
(222, '20243011', '12', '', '', '0', '2024-11-07'),
(221, '20243010', '12', '', '', '0', '2024-11-07'),
(220, '20243009', '12', '', '', '0', '2024-11-07'),
(219, '20243008', '12', '', '', '0', '2024-11-07'),
(218, '20243007', '12', '', '', '0', '2024-11-07'),
(217, '20243006', '12', '', '', '1', '2024-11-07'),
(216, '20243005', '12', '', '', '1', '2024-11-07'),
(215, '20243004', '12', '', '', '1', '2024-11-07'),
(214, '20243003', '12', '', '', '1', '2024-11-07'),
(213, '20243002', '12', '', '', '1', '2024-11-07'),
(212, '20243001', '12', '', '', '1', '2024-11-07'),
(257, '20243004', '13', '', '', '1', '2024-11-08'),
(258, '20243005', '13', '', '', '1', '2024-11-08'),
(259, '20243006', '13', '', '', '1', '2024-11-08'),
(260, '20243026', '13', '', '', '1', '2024-11-08'),
(261, '20243027', '13', '', '', '0', '2024-11-08'),
(262, '20243028', '13', '', '', '1', '2024-11-08'),
(263, '20243029', '13', '', '', '1', '2024-11-08'),
(264, '20243030', '13', '', '', '1', '2024-11-08'),
(265, '20243031', '13', '', '', '1', '2024-11-08');

-- --------------------------------------------------------

--
-- Table structure for table `tblclass`
--

CREATE TABLE `tblclass` (
  `Id` int(10) NOT NULL,
  `className` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclass`
--

INSERT INTO `tblclass` (`Id`, `className`) VALUES
(13, '9'),
(14, '10');

-- --------------------------------------------------------

--
-- Table structure for table `tblclassarms`
--

CREATE TABLE `tblclassarms` (
  `Id` int(10) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmName` varchar(255) NOT NULL,
  `isAssigned` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblclassteacher`
--

CREATE TABLE `tblclassteacher` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNo` varchar(50) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclassteacher`
--

INSERT INTO `tblclassteacher` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`, `phoneNo`, `classId`, `classArmId`, `dateCreated`) VALUES
(10, 'Class 9', 'Teacher', 'class@nine', 'pass123', '01307456789', '13', '', '2024-11-02');

-- --------------------------------------------------------

--
-- Table structure for table `tblsessionterm`
--

CREATE TABLE `tblsessionterm` (
  `Id` int(10) NOT NULL,
  `sessionName` varchar(50) NOT NULL,
  `termId` varchar(50) NOT NULL,
  `isActive` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `otherName` varchar(255) NOT NULL,
  `admissionNumber` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`Id`, `firstName`, `lastName`, `otherName`, `admissionNumber`, `password`, `classId`, `classArmId`, `dateCreated`) VALUES
(20, 'Mansoor', 'Rahman', 'Omar', '20243001', '12345', '13', '', '2024-11-02'),
(21, 'Ayesha', 'Begum', 'Nabila', '20243002', '12345', '13', '', '2024-11-02'),
(22, 'Hasan', 'Mia', 'Rafiq', '20243003', '12345', '13', '', '2024-11-02'),
(23, 'Zara', 'Khan', 'Sadia', '20243004', '12345', '13', '', '2024-11-02'),
(24, 'Irfan', 'Chowdhury', 'Salim', '20243005', '12345', '13', '', '2024-11-02'),
(51, 'Md.', 'Hasan', 'awd', '20243006', '12345', '13', '', '2024-11-07'),
(45, 'Sakina', 'Khan', 'Farah', '20243026', '12345', '13', '', '2024-11-02'),
(46, 'Yasir', 'Chowdhury', 'Faisal', '20243027', '12345', '13', '', '2024-11-02'),
(47, 'Rahi', 'Uddin', 'Nafisa', '20243028', '12345', '13', '', '2024-11-02'),
(48, 'Roshni', 'Begum', 'Salma', '20243029', '12345', '13', '', '2024-11-02'),
(49, 'Shayan', 'Rahman', 'Rizwan', '20243030', '12345', '13', '', '2024-11-02'),
(50, 'Zara', 'Islam', 'Tania', '20243031', '12345', '13', '', '2024-11-02');

-- --------------------------------------------------------

--
-- Table structure for table `tblterm`
--

CREATE TABLE `tblterm` (
  `Id` int(10) NOT NULL,
  `termName` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclass`
--
ALTER TABLE `tblclass`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclassteacher`
--
ALTER TABLE `tblclassteacher`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblterm`
--
ALTER TABLE `tblterm`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=266;

--
-- AUTO_INCREMENT for table `tblclass`
--
ALTER TABLE `tblclass`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tblclassteacher`
--
ALTER TABLE `tblclassteacher`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `tblterm`
--
ALTER TABLE `tblterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
