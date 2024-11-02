-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:5222
-- Generation Time: Nov 02, 2024 at 08:22 PM
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
-- Database: `sas_six`
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
(2, 'Admin', 'SAS Six', 'admin@six', 'admin');

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
(251, '20240029', '15', '', '', '0', '2024-11-02'),
(250, '20240028', '15', '', '', '0', '2024-11-02'),
(249, '20240027', '15', '', '', '0', '2024-11-02'),
(248, '20240026', '15', '', '', '0', '2024-11-02'),
(247, '20240025', '15', '', '', '0', '2024-11-02'),
(246, '20240024', '15', '', '', '0', '2024-11-02'),
(245, '20240023', '15', '', '', '0', '2024-11-02'),
(244, '20240022', '15', '', '', '0', '2024-11-02'),
(243, '20240021', '15', '', '', '0', '2024-11-02'),
(242, '20240020', '15', '', '', '0', '2024-11-02'),
(241, '20240019', '15', '', '', '0', '2024-11-02'),
(240, '20240018', '15', '', '', '0', '2024-11-02'),
(239, '20240017', '15', '', '', '0', '2024-11-02'),
(238, '20240016', '15', '', '', '0', '2024-11-02'),
(237, '20240015', '15', '', '', '0', '2024-11-02'),
(236, '20240014', '15', '', '', '0', '2024-11-02'),
(235, '20240013', '15', '', '', '0', '2024-11-02'),
(234, '20240012', '15', '', '', '0', '2024-11-02'),
(233, '20240011', '15', '', '', '0', '2024-11-02'),
(232, '20240010', '15', '', '', '0', '2024-11-02'),
(231, '20240009', '15', '', '', '0', '2024-11-02'),
(230, '20240008', '15', '', '', '0', '2024-11-02'),
(229, '20240007', '15', '', '', '0', '2024-11-02'),
(228, '20240006', '15', '', '', '0', '2024-11-02'),
(227, '20240005', '15', '', '', '0', '2024-11-02'),
(226, '20240004', '15', '', '', '0', '2024-11-02'),
(225, '20240003', '15', '', '', '0', '2024-11-02'),
(224, '20240001', '15', '', '', '0', '2024-11-02'),
(223, '20240002', '15', '', '', '0', '2024-11-02');

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
(15, '6');

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
(13, 'Class 6', 'Teacher', 'class@six', 'pass123', '01715123456', '15', '', '2024-11-02');

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
(23, 'Farhana', 'Islam', 'Sultana', '20240002', '12345', '15', '', '2024-11-02'),
(22, 'Arafat', 'Rahman', 'Kabir', '20240001', '12345', '15', '', '2024-11-02'),
(25, 'Ruhul', 'Kuddus', 'Sami', '20240003', '12345', '15', '', '2024-11-02'),
(20240004, 'Mithun', 'Sarker', 'Raihan', '20240004', '12345', '15', '', '2024-11-02'),
(20240005, 'Puja', 'Das', 'Aditi', '20240005', '12345', '15', '', '2024-11-02'),
(20240006, 'Shanto', 'Chowdhury', 'Tanvir', '20240006', '12345', '15', '', '2024-11-02'),
(20240007, 'Nabila', 'Ahmed', 'Mimi', '20240007', '12345', '15', '', '2024-11-02'),
(20240008, 'Sadiq', 'Hossain', 'Samiul', '20240008', '12345', '15', '', '2024-11-02'),
(20240009, 'Farhana', 'Begum', 'Nila', '20240009', '12345', '15', '', '2024-11-02'),
(20240010, 'Ratul', 'Khan', 'Bappi', '20240010', '12345', '15', '', '2024-11-02'),
(20240011, 'Khalid', 'Islam', 'Rashid', '20240011', '12345', '15', '', '2024-11-02'),
(20240012, 'Sabrina', 'Rahman', 'Nilu', '20240012', '12345', '15', '', '2024-11-02'),
(20240013, 'Jewel', 'Miah', 'Tuhin', '20240013', '12345', '15', '', '2024-11-02'),
(20240014, 'Sumaiya', 'Zaman', 'Lima', '20240014', '12345', '15', '', '2024-11-02'),
(20240015, 'Imran', 'Alam', 'Fahim', '20240015', '12345', '15', '', '2024-11-02'),
(20240016, 'Riya', 'Sultana', 'Rupa', '20240016', '12345', '15', '', '2024-11-02'),
(20240017, 'Shahriar', 'Chowdhury', 'Shaan', '20240017', '12345', '15', '', '2024-11-02'),
(20240018, 'Anika', 'Tariq', 'Samiha', '20240018', '12345', '15', '', '2024-11-02'),
(20240019, 'Feroz', 'Hoque', 'Rafi', '20240019', '12345', '15', '', '2024-11-02'),
(20240020, 'Tamanna', 'Khan', 'Maya', '20240020', '12345', '15', '', '2024-11-02'),
(20240021, 'Sakib', 'Uddin', 'Nabil', '20240021', '12345', '15', '', '2024-11-02'),
(20240022, 'Kashif', 'Hossain', 'Riyad', '20240022', '12345', '15', '', '2024-11-02'),
(20240023, 'Rumi', 'Rahman', 'Nipa', '20240023', '12345', '15', '', '2024-11-02'),
(20240024, 'Zahir', 'Islam', 'Bobby', '20240024', '12345', '15', '', '2024-11-02'),
(20240025, 'Nafisa', 'Akter', 'Rumi', '20240025', '12345', '15', '', '2024-11-02'),
(20240026, 'Roni', 'Chowdhury', 'Arif', '20240026', '12345', '15', '', '2024-11-02'),
(20240027, 'Lina', 'Begum', 'Piya', '20240027', '12345', '15', '', '2024-11-02'),
(20240028, 'Raihan', 'Shah', 'Samir', '20240028', '12345', '15', '', '2024-11-02'),
(20240029, 'Tisha', 'Mia', 'Soma', '20240029', '12345', '15', '', '2024-11-02');

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
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT for table `tblclass`
--
ALTER TABLE `tblclass`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `tblclassteacher`
--
ALTER TABLE `tblclassteacher`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20240030;

--
-- AUTO_INCREMENT for table `tblterm`
--
ALTER TABLE `tblterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
