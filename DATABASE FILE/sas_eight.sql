-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:5222
-- Generation Time: Nov 02, 2024 at 08:23 PM
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
-- Database: `sas_eight`
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
(2, 'Admin', 'SAS Eight', 'admin@eight', 'admin');

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
(12, '8');

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
(10, 'Class 8', 'Teacher', 'class@eight', 'pass123', '01913789012', '12', '', '2024-11-02');

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
(20, 'Faisal', 'Chowdhury', 'Jamil', '20242001', '12345', '12', '', '2024-11-02'),
(21, 'Sadia', 'Akter', 'Nabila', '20242002', '12345', '12', '', '2024-11-02'),
(22, 'Arif', 'Rahman', 'Omar', '20242003', '12345', '12', '', '2024-11-02'),
(23, 'Jasmine', 'Islam', 'Sultana', '20242004', '12345', '12', '', '2024-11-02'),
(24, 'Imran', 'Hossain', 'Rafiq', '20242005', '12345', '12', '', '2024-11-02'),
(25, 'Maya', 'Begum', 'Ria', '20242006', '12345', '12', '', '2024-11-02'),
(26, 'Samin', 'Mia', 'Razzak', '20242007', '12345', '12', '', '2024-11-02'),
(27, 'Zakir', 'Uddin', 'Fahim', '20242008', '12345', '12', '', '2024-11-02'),
(28, 'Nusrat', 'Khan', 'Sadia', '20242009', '12345', '12', '', '2024-11-02'),
(29, 'Rifat', 'Chowdhury', 'Tariq', '20242010', '12345', '12', '', '2024-11-02'),
(30, 'Samira', 'Jahan', 'Tamanna', '20242011', '12345', '12', '', '2024-11-02'),
(31, 'Farhan', 'Islam', 'Tariq', '20242012', '12345', '12', '', '2024-11-02'),
(32, 'Hina', 'Sarker', 'Shabnam', '20242013', '12345', '12', '', '2024-11-02'),
(33, 'Tania', 'Akter', 'Nusrat', '20242014', '12345', '12', '', '2024-11-02'),
(34, 'Rahim', 'Hossain', 'Rifat', '20242015', '12345', '12', '', '2024-11-02'),
(35, 'Fahim', 'Rahman', 'Omar', '20242016', '12345', '12', '', '2024-11-02'),
(36, 'Anika', 'Begum', 'Nabila', '20242017', '12345', '12', '', '2024-11-02'),
(37, 'Razi', 'Mia', 'Rafiq', '20242018', '12345', '12', '', '2024-11-02'),
(38, 'Fouzia', 'Chowdhury', 'Samira', '20242019', '12345', '12', '', '2024-11-02'),
(39, 'Kamruzzaman', 'Sarker', 'Nabin', '20242020', '12345', '12', '', '2024-11-02'),
(40, 'Sabina', 'Akter', 'Nafisa', '20242021', '12345', '12', '', '2024-11-02'),
(41, 'Yasmin', 'Khan', 'Farha', '20242022', '12345', '12', '', '2024-11-02'),
(42, 'Rafiq', 'Alam', 'Salim', '20242023', '12345', '12', '', '2024-11-02'),
(43, 'Sahar', 'Begum', 'Sadia', '20242024', '12345', '12', '', '2024-11-02'),
(44, 'Munir', 'Uddin', 'Hasan', '20242025', '12345', '12', '', '2024-11-02'),
(45, 'Riya', 'Jahan', 'Shamima', '20242026', '12345', '12', '', '2024-11-02'),
(46, 'Naima', 'Islam', 'Tina', '20242027', '12345', '12', '', '2024-11-02'),
(47, 'Rashid', 'Chowdhury', 'Shams', '20242028', '12345', '12', '', '2024-11-02'),
(48, 'Mansoor', 'Rahman', 'Omar', '20242029', '12345', '12', '', '2024-11-02'),
(49, 'Nadia', 'Mia', 'Rani', '20242030', '12345', '12', '', '2024-11-02'),
(50, 'Shamim', 'Uddin', 'Fazal', '20242031', '12345', '12', '', '2024-11-02');

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
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT for table `tblclass`
--
ALTER TABLE `tblclass`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tblterm`
--
ALTER TABLE `tblterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
