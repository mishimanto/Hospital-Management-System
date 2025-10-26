-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 08:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `updationDate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `updationDate`) VALUES
(1, 'admin', 'Test@12345', '04-03-2024 11:42:05 AM');

-- --------------------------------------------------------

--
-- Table structure for table `ambulances`
--

CREATE TABLE `ambulances` (
  `id` int(11) NOT NULL,
  `ambulance_number` varchar(20) DEFAULT NULL,
  `fee` varchar(20) DEFAULT NULL,
  `status` enum('available','booked') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ambulances`
--

INSERT INTO `ambulances` (`id`, `ambulance_number`, `fee`, `status`) VALUES
(1, '019XXXXXXX', '1000', 'available'),
(2, '01933333333', '1000', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `ambulance_bookings`
--

CREATE TABLE `ambulance_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ambulance_number` varchar(15) NOT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `booking_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Active','Paid','Cancelled') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ambulance_bookings`
--

INSERT INTO `ambulance_bookings` (`id`, `user_id`, `ambulance_number`, `pickup_location`, `destination`, `cost`, `booking_time`, `status`) VALUES
(2, 3, '01933333333', 'Narayanganj', '', 1000.00, '2025-06-13 18:03:08', 'Paid'),
(3, 3, '01933333333', 'Narayanganj', '', 1000.00, '2025-06-13 18:10:05', 'Paid'),
(4, 3, '019XXXXXXX', 'Narayanganj', '', 1000.00, '2025-06-13 18:15:27', 'Paid'),
(5, 4, '019XXXXXXX', 'Narayanganj', '', 1000.00, '2025-06-13 18:17:40', 'Paid'),
(6, 4, '01933333333', 'fatullah', '', 1000.00, '2025-06-13 18:18:15', 'Paid'),
(7, 4, '019XXXXXXX', 'Narayanganj', '', 1000.00, '2025-06-13 18:22:39', 'Paid'),
(8, 3, '019XXXXXXX', 'Narayanganj', '', 1000.00, '2025-06-13 18:25:05', 'Paid'),
(9, 4, '01933333333', 'Fatullah', '', 1000.00, '2025-06-13 18:25:45', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `doctorSpecialization` varchar(255) DEFAULT NULL,
  `doctorId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `userEmail` varchar(100) NOT NULL,
  `consultancyFees` int(11) DEFAULT NULL,
  `appointmentDate` varchar(255) DEFAULT NULL,
  `appointmentTime` varchar(100) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp(),
  `userStatus` int(11) DEFAULT NULL,
  `doctorStatus` int(11) DEFAULT NULL,
  `serialNumber` varchar(20) DEFAULT NULL,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_amount` decimal(10,2) DEFAULT 0.00,
  `payment_status` enum('Pending','Paid','Cancelled') DEFAULT 'Pending',
  `PatientMedhis` text DEFAULT NULL,
  `Prescription` text DEFAULT NULL,
  `Tests` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `doctorSpecialization`, `doctorId`, `userId`, `userEmail`, `consultancyFees`, `appointmentDate`, `appointmentTime`, `postingDate`, `userStatus`, `doctorStatus`, `serialNumber`, `updationDate`, `payment_method`, `payment_amount`, `payment_status`, `PatientMedhis`, `Prescription`, `Tests`) VALUES
(81, 'Orthopedics', 5, 8, 'moynulislamshimanto29@gmail.com', 1200, '2025-06-24', '17:10:00', '2025-06-23 17:55:19', 1, 1, '2', NULL, 'Wallet', 1200.00, 'Paid', NULL, NULL, NULL),
(82, 'ENT', 1, 8, 'moynulislamshimanto29@gmail.com', 500, '2025-06-24', '17:00:00', '2025-06-23 17:57:02', 1, 1, '1', NULL, 'Wallet', 500.00, 'Paid', NULL, NULL, NULL),
(83, 'ENT', 1, 3, 'moynulislamshimanto24@gmail.com', 500, '2025-06-24', '17:10:00', '2025-06-23 17:58:53', 1, 1, '2', NULL, 'Wallet', 500.00, 'Paid', NULL, NULL, NULL),
(84, 'Pediatrics', 4, 3, 'moynulislamshimanto24@gmail.com', 700, '2025-06-24', '17:10:00', '2025-06-23 18:00:02', 1, 1, '2', NULL, 'Wallet', 700.00, 'Paid', NULL, NULL, NULL),
(85, 'Orthopedics', 5, 3, 'moynulislamshimanto24@gmail.com', 1200, '2025-06-25', '17:00:00', '2025-06-23 18:01:05', 1, 1, '1', NULL, 'Wallet', 1200.00, 'Paid', NULL, NULL, NULL),
(86, 'Internal Medicine', 6, 3, 'moynulislamshimanto24@gmail.com', 1500, '2025-06-25', '17:00:00', '2025-06-23 18:02:02', 1, 1, '1', NULL, 'Wallet', 1500.00, 'Paid', NULL, NULL, NULL),
(87, 'Obstetrics and Gynecology', 7, 3, 'moynulislamshimanto24@gmail.com', 800, '2025-06-25', '17:00:00', '2025-06-23 18:03:04', 1, 1, '1', NULL, 'Wallet', 800.00, 'Paid', NULL, NULL, NULL),
(89, 'Pediatrics', 4, 3, 'moynulislamshimanto24@gmail.com', 700, '2025-06-25', '17:00:00', '2025-06-23 18:06:54', 1, 1, '1', NULL, 'Wallet', 700.00, 'Paid', NULL, NULL, NULL),
(90, 'ENT', 1, 3, 'moynulislamshimanto24@gmail.com', 500, '2025-06-25', '17:00:00', '2025-06-23 18:08:24', 1, 1, '1', NULL, 'Wallet', 500.00, 'Paid', NULL, NULL, NULL),
(91, 'Encologist', 8, 3, 'moynulislamshimanto24@gmail.com', 1000, '2025-06-25', '17:00:00', '2025-06-24 16:58:10', 1, 1, '1', NULL, 'Wallet', 1000.00, 'Paid', NULL, NULL, NULL),
(94, 'Endocrinologists', 2, 3, 'moynulislamshimanto24@gmail.com', 800, '2025-06-25', '17:00:00', '2025-06-24 17:09:20', 1, 1, '1', NULL, 'Wallet', 800.00, 'Paid', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `beds`
--

CREATE TABLE `beds` (
  `id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beds`
--

INSERT INTO `beds` (`id`, `count`, `created_at`) VALUES
(9, 40, '2025-06-20 19:34:34');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `specilization` varchar(255) DEFAULT NULL,
  `doctorName` varchar(255) DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `docFees` varchar(255) DEFAULT NULL,
  `contactno` varchar(11) DEFAULT NULL,
  `docEmail` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `visiting_start_time` time DEFAULT '09:00:00',
  `visiting_end_time` time DEFAULT '17:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `specilization`, `doctorName`, `address`, `docFees`, `contactno`, `docEmail`, `password`, `creationDate`, `updationDate`, `visiting_start_time`, `visiting_end_time`) VALUES
(1, 'ENT', 'Anuj kumar', 'A 123 XYZ Apartment Raj Nagar Ext Ghaziabad', '500', '01911111111', 'anujk123@test.com', 'f925916e2754e5e03f75dd58a5733251', '2024-04-10 18:16:52', '2025-06-10 16:47:54', '17:00:00', '21:00:00'),
(2, 'Endocrinologists', 'Monir Hossain', 'Dhaka', '800', '01911111111', 'monir@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2024-04-11 01:06:41', '2025-06-17 18:11:41', '17:00:00', '21:00:00'),
(4, 'Pediatrics', 'Priyanka Chowdhury', 'Dhaka', '700', '01911111111', 'priyanka2@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2024-05-16 09:12:23', '2025-06-17 18:11:50', '17:00:00', '21:00:00'),
(5, 'Orthopedics', 'Vipin Tayagi', 'Yasho Hospital New Delhi', '1200', '01911111111', 'vpint123@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2024-05-16 09:13:11', '2025-06-17 18:11:56', '17:00:00', '21:00:00'),
(6, 'Internal Medicine', 'Romil Khan', 'Dhaka', '1500', '01911111111', 'drromil12@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2024-05-16 09:14:11', '2025-06-23 17:27:13', '17:00:00', '21:00:00'),
(7, 'Obstetrics and Gynecology', 'Shohag Mia', 'Dhaka', '800', '01911111111', 'shohag@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2024-05-16 09:15:18', '2025-06-17 18:12:08', '17:00:00', '21:00:00'),
(8, 'Encologist', 'Mosharof Khan', 'Narayanganj', '1000', '01949854504', 'moynulislamshimanto24@gmail.com', '0538081431123f49c786933cf8ae27fc', '2025-05-30 15:32:29', '2025-06-17 18:12:13', '17:00:00', '21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `doctorslog`
--

CREATE TABLE `doctorslog` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NULL DEFAULT current_timestamp(),
  `logout` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctorslog`
--

INSERT INTO `doctorslog` (`id`, `uid`, `username`, `userip`, `loginTime`, `logout`, `status`) VALUES
(1, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2024-05-16 05:19:33', NULL, 1),
(2, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2024-05-16 09:01:03', '16-05-2024 02:37:32 PM', 1),
(3, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:13:35', '29-05-2025 10:44:17 PM', 1),
(4, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-05-30 16:07:56', NULL, 1),
(5, 8, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 16:35:09', NULL, 1),
(6, 8, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 16:36:22', NULL, 1),
(7, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-06-08 06:03:49', '08-06-2025 11:36:20 AM', 1),
(8, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-06-08 06:32:37', NULL, 1),
(9, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-06-08 06:48:44', NULL, 1),
(10, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:02:35', NULL, 0),
(11, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-06-10 16:19:01', NULL, 1),
(12, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-06-11 16:18:29', NULL, 1),
(13, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-06-12 07:34:52', NULL, 1),
(14, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-06-12 14:06:16', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctorspecilization`
--

CREATE TABLE `doctorspecilization` (
  `id` int(11) NOT NULL,
  `specilization` varchar(255) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctorspecilization`
--

INSERT INTO `doctorspecilization` (`id`, `specilization`, `creationDate`, `updationDate`) VALUES
(1, 'Orthopedics', '2024-04-09 18:09:46', '2024-05-14 09:26:47'),
(2, 'Internal Medicine', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(3, 'Obstetrics and Gynecology', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(4, 'Dermatology', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(5, 'Pediatrics', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(6, 'Radiology', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(7, 'General Surgery', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(8, 'Ophthalmology', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(9, 'Anesthesia', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(10, 'Pathology', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(11, 'ENT', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(12, 'Dental Care', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(13, 'Dermatologists', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(14, 'Endocrinologists', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(15, 'Neurologists', '2024-04-09 18:09:46', '2024-05-14 09:26:56'),
(18, 'Encologist', '2025-05-30 15:24:04', NULL),
(19, 'Medicine', '2025-06-13 07:10:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `em_doctors`
--

CREATE TABLE `em_doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `shift` varchar(100) DEFAULT NULL,
  `available` tinyint(1) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `em_doctors`
--

INSERT INTO `em_doctors` (`id`, `name`, `contact`, `email`, `password`, `specialization`, `qualification`, `shift`, `available`, `creationDate`, `updationDate`) VALUES
(1, 'Dr. Majid', NULL, NULL, NULL, 'Medicine', 'MBBS(DU)', '6AM - 6PM', 1, '2025-06-13 15:47:14', NULL),
(3, 'Dr. Hamid khan', '01922222222', 'hamid@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', 'Medicine', 'MBBS(DMC), BCS(Health).', '6 PM - 6 AM ', 1, '2025-06-13 15:47:14', '2025-06-13 16:22:29'),
(4, 'Dr. Jahir khan', '01922222555', 'jahir@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', 'Medicine', 'MBBS(DMC)', '6 PM - 6 AM ', 1, '2025-06-13 15:48:14', '2025-06-23 14:54:03'),
(5, 'Samim Hossain', '016XXXXXXXX', 'samim@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', 'Medicine', 'MBBS', '6 AM - 6 PM', 1, '2025-06-23 14:47:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

CREATE TABLE `labs` (
  `id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labs`
--

INSERT INTO `labs` (`id`, `count`, `created_at`) VALUES
(1, 1, '2025-06-20 19:05:11'),
(2, 1, '2025-06-20 19:05:12');

-- --------------------------------------------------------

--
-- Table structure for table `tblcontactus`
--

CREATE TABLE `tblcontactus` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contactno` bigint(12) DEFAULT NULL,
  `message` mediumtext DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `AdminRemark` mediumtext DEFAULT NULL,
  `LastupdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `IsRead` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblcontactus`
--

INSERT INTO `tblcontactus` (`id`, `fullname`, `email`, `contactno`, `message`, `PostingDate`, `AdminRemark`, `LastupdationDate`, `IsRead`) VALUES
(1, 'Anuj kumar', 'anujk30@test.com', 1425362514, 'This is for testing purposes.   This is for testing purposes.This is for testing purposes.This is for testing purposes.This is for testing purposes.This is for testing purposes.This is for testing purposes.This is for testing purposes.This is for testing purposes.', '2024-04-20 16:52:03', NULL, '2024-05-14 09:27:15', NULL),
(2, 'Anuj kumar', 'ak@gmail.com', 1111122233, 'This is for testing', '2024-04-23 13:13:41', 'Contact the patient', '2024-04-27 13:13:57', 1),
(3, 'Shimanto', 'moynulislamshimanto@gmail.com', 1949854504, '', '2025-05-20 14:20:22', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblmedicalhistory`
--

CREATE TABLE `tblmedicalhistory` (
  `ID` int(10) NOT NULL,
  `PatientID` int(10) DEFAULT NULL,
  `BloodPressure` varchar(200) DEFAULT NULL,
  `BloodSugar` varchar(200) NOT NULL,
  `Weight` varchar(100) DEFAULT NULL,
  `Temperature` varchar(200) DEFAULT NULL,
  `MedicalPres` mediumtext DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblmedicalhistory`
--

INSERT INTO `tblmedicalhistory` (`ID`, `PatientID`, `BloodPressure`, `BloodSugar`, `Weight`, `Temperature`, `MedicalPres`, `CreationDate`) VALUES
(1, 2, '80/120', '110', '85', '97', 'Dolo,\r\nLevocit 5mg', '2024-05-16 09:07:16');

-- --------------------------------------------------------

--
-- Table structure for table `tblpage`
--

CREATE TABLE `tblpage` (
  `ID` int(10) NOT NULL,
  `PageType` varchar(200) DEFAULT NULL,
  `PageTitle` varchar(200) DEFAULT NULL,
  `PageDescription` mediumtext DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `MobileNumber` varchar(11) DEFAULT NULL,
  `UpdationDate` timestamp NULL DEFAULT current_timestamp(),
  `OpenningTime` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpage`
--

INSERT INTO `tblpage` (`ID`, `PageType`, `PageTitle`, `PageDescription`, `Email`, `MobileNumber`, `UpdationDate`, `OpenningTime`) VALUES
(1, 'aboutus', 'About Us', 'MEDIZEN is designed for Any Hospital to replace their existing manual, paper based system. The new system is to control the following information; patient information, room availability, staff and operating room schedules, and patient invoices. These services are to be provided in an efficient, cost effective manner, with the goal of reducing the time and resources currently required for such tasks. A significant part of the operation of any hospital involves the acquisition, management and timely retrieval of great volumes of information. This information typically involves; patient personal information and medical history, staff information, room and ward scheduling, staff scheduling, operating theater scheduling and various facilities waiting lists. All of this information must be managed in an efficient and cost wise fashion so that an institution\'s resources may be effectively utilized MEDIZEN will automate the management of the hospital making it more efficient and error free. It aims at standardizing data, consolidating data ensuring data integrity and reducing inconsistencies.', NULL, NULL, '2020-05-20 07:21:52', NULL),
(2, 'contactus', 'Contact Details', 'Chasara, Narayaganj.', 'medizen@gmail.com', '01949854504', '2020-05-20 07:24:07', '9 AM To 10 PM');

-- --------------------------------------------------------

--
-- Table structure for table `tblpatient`
--

CREATE TABLE `tblpatient` (
  `ID` int(10) NOT NULL,
  `Docid` int(10) DEFAULT NULL,
  `PatientName` varchar(200) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `PatientContno` varchar(11) DEFAULT NULL,
  `PatientEmail` varchar(200) DEFAULT NULL,
  `PatientGender` varchar(50) DEFAULT NULL,
  `PatientAdd` mediumtext DEFAULT NULL,
  `PatientAge` int(10) DEFAULT NULL,
  `PatientMedhis` mediumtext DEFAULT NULL,
  `Prescription` text NOT NULL,
  `Tests` text NOT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblpatient`
--

INSERT INTO `tblpatient` (`ID`, `Docid`, `PatientName`, `user_id`, `PatientContno`, `PatientEmail`, `PatientGender`, `PatientAdd`, `PatientAge`, `PatientMedhis`, `Prescription`, `Tests`, `CreationDate`, `UpdationDate`) VALUES
(1, 1, 'Rahul Kabir', NULL, '01745758392', 'rahul12@gmail.com', 'Male', 'NA', 32, 'Fever, Cold', '', '', '2025-05-16 05:23:35', '2025-05-30 16:13:07'),
(2, 1, 'Amit Hasan', NULL, '01845758543', 'amitk@gmail.com', 'Male', 'NA', 45, 'Fever', '', '', '2025-05-16 09:01:26', '2025-05-30 16:13:11'),
(3, 1, 'Moynul Islam Shimanto', NULL, '01949854504', 'moynulislamshimanto24@gmail.com', 'Male', 'Fatullah, Narayanganj.\r\n', 18, 'Asthma', '', '', '2025-05-30 16:12:44', '2025-06-12 08:09:11'),
(4, 1, 'Shimanto', NULL, '01949854504', 'moynulislamshimanto25@gmail.com', 'Male', 'Narayanganj', 27, 'Fever', '', '', '2025-06-10 16:20:41', '2025-06-12 08:09:17'),
(5, 1, 'Ripon', NULL, '01955215474', 'riponhossainmd744@gmail.com', 'Male', 'Jorpul', 27, 'Diahorrea', '', '', '2025-06-10 16:28:53', '2025-06-12 08:09:37'),
(6, 1, 'Shimanto', NULL, '0194985450', 'moynulislamshimanto25@gmail.com', 'Male', 'Narayanganj', 27, 'Fever', 'Napa 500mg   1+1+1', 'CVC', '2025-06-12 05:56:15', '2025-06-12 08:09:44'),
(8, 1, 'Moynul Islam Shimanto', 3, '01949854504', 'moynulislamshimanto24@gmail.com', 'Male', 'Narayanganj', 27, 'Fever', 'Napa 500mg   1+1+1', 'CVC', '2025-06-12 15:14:58', NULL),
(9, 1, 'Shimanto', 4, '01949854504', 'moynulislamshimanto24@gmail.com', 'Male', 'Narayanganj', 27, 'Pain', 'Fast 500mg   1+1+1', 'MRI', '2025-06-12 15:28:02', NULL),
(10, 1, 'Monira Akter', 4, '01949854504', 'moynulislamshimanto25@gmail.com', 'Male', 'Narayanganj', 27, NULL, '', '', '2025-06-12 15:50:16', NULL),
(11, 1, 'Monira Akter', 4, '01949854504', 'moynulislamshimanto25@gmail.com', 'Male', 'Narayanganj', 27, NULL, '', '', '2025-06-12 15:52:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NULL DEFAULT current_timestamp(),
  `logout` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`id`, `uid`, `username`, `userip`, `loginTime`, `logout`, `status`) VALUES
(1, 1, 'johndoe12@test.com', 0x3a3a3100000000000000000000000000, '2024-05-15 03:41:48', NULL, 1),
(2, 2, 'amitk@gmail.com', 0x3a3a3100000000000000000000000000, '2024-05-16 09:08:06', '16-05-2024 02:41:06 PM', 1),
(3, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-09 07:03:21', '09-05-2025 12:33:51 PM', 1),
(4, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-16 17:25:38', NULL, 0),
(5, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-16 17:26:02', NULL, 0),
(6, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-16 17:26:14', '16-05-2025 10:57:26 PM', 1),
(7, NULL, 'johndoe12@test.com', 0x3a3a3100000000000000000000000000, '2025-05-23 07:10:02', NULL, 0),
(8, 1, 'johndoe12@test.com', 0x3a3a3100000000000000000000000000, '2025-05-23 07:10:17', NULL, 1),
(9, 1, 'johndoe12@test.com', 0x3a3a3100000000000000000000000000, '2025-05-23 08:02:57', NULL, 1),
(10, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-26 14:16:44', NULL, 1),
(11, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-26 14:27:18', NULL, 1),
(12, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-26 15:43:08', NULL, 0),
(13, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-26 15:43:18', NULL, 0),
(14, 3, 'moynulislamshimanto@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-26 15:43:57', NULL, 1),
(15, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 17:53:41', NULL, 1),
(16, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 17:57:53', '27-05-2025 11:28:10 PM', 1),
(17, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:01:15', NULL, 1),
(18, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:05:07', '28-05-2025 12:05:16 AM', 1),
(19, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:11:47', '28-05-2025 12:11:53 AM', 1),
(20, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:12:59', '28-05-2025 12:13:02 AM', 1),
(21, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:14:32', '28-05-2025 12:14:34 AM', 1),
(22, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:16:05', '28-05-2025 12:16:09 AM', 1),
(23, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:16:41', '28-05-2025 12:16:45 AM', 1),
(24, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:17:21', '28-05-2025 12:17:24 AM', 1),
(25, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:23:05', '28-05-2025 12:23:08 AM', 1),
(26, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:26:21', '28-05-2025 12:26:25 AM', 1),
(27, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:27:15', '28-05-2025 12:27:19 AM', 1),
(28, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:28:39', '28-05-2025 12:30:01 AM', 1),
(29, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:30:40', NULL, 1),
(30, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:31:53', NULL, 1),
(31, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:32:39', NULL, 1),
(32, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:33:13', NULL, 0),
(33, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:33:25', NULL, 0),
(34, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:33:34', NULL, 0),
(35, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:33:46', NULL, 1),
(36, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-27 18:34:25', NULL, 1),
(37, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-28 13:20:55', NULL, 0),
(38, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-28 13:21:05', NULL, 1),
(39, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-28 13:30:15', NULL, 1),
(40, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-28 13:32:20', NULL, 1),
(41, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-28 17:39:49', NULL, 1),
(42, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 14:23:09', NULL, 1),
(43, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 14:23:33', NULL, 1),
(44, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 15:27:58', NULL, 1),
(45, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 16:29:20', NULL, 1),
(46, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 16:38:15', NULL, 1),
(47, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 16:39:59', NULL, 1),
(48, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 16:42:21', NULL, 1),
(49, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:04:41', NULL, 1),
(50, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:06:23', NULL, 1),
(51, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:09:14', NULL, 1),
(52, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:09:46', NULL, 1),
(53, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:10:26', NULL, 1),
(54, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:11:23', NULL, 1),
(55, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:11:44', NULL, 1),
(56, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:12:08', NULL, 1),
(57, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:23:48', NULL, 0),
(58, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:23:56', NULL, 1),
(59, 1, 'johndoe12@test.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:27:28', NULL, 1),
(60, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-29 17:29:02', NULL, 1),
(61, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 04:08:06', NULL, 1),
(62, 1, 'johndoe12@test.com', 0x3a3a3100000000000000000000000000, '2025-05-30 04:25:54', NULL, 1),
(63, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 05:02:10', NULL, 1),
(64, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 05:05:19', NULL, 1),
(65, 1, 'johndoe12@test.com', 0x3a3a3100000000000000000000000000, '2025-05-30 05:53:10', NULL, 1),
(66, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 13:43:14', NULL, 1),
(67, 1, 'johndoe12@test.com', 0x3a3a3100000000000000000000000000, '2025-05-30 13:45:25', NULL, 1),
(68, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 15:02:59', NULL, 1),
(69, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 17:06:31', NULL, 1),
(70, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 17:06:39', NULL, 1),
(71, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 17:06:49', NULL, 0),
(72, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 17:07:09', NULL, 1),
(73, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 18:11:42', NULL, 1),
(74, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-30 19:18:20', NULL, 1),
(75, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-05-31 08:46:01', NULL, 1),
(76, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-05 14:55:48', NULL, 1),
(77, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-06 03:19:12', NULL, 1),
(78, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-06 04:24:26', NULL, 1),
(79, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-06 06:47:31', NULL, 1),
(80, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-06 07:13:54', NULL, 1),
(81, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-06 08:18:11', NULL, 1),
(82, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-06 13:09:24', NULL, 1),
(83, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-06 16:24:49', NULL, 1),
(84, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-06 17:51:57', NULL, 1),
(85, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-06 18:04:10', NULL, 1),
(86, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 03:15:38', NULL, 1),
(87, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 03:27:19', NULL, 1),
(88, NULL, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 04:14:13', NULL, 0),
(89, NULL, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 04:14:31', NULL, 0),
(90, NULL, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 04:15:01', NULL, 0),
(91, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 04:15:17', NULL, 1),
(92, NULL, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 04:16:51', NULL, 0),
(93, NULL, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 04:17:06', NULL, 0),
(94, NULL, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 04:17:41', NULL, 0),
(95, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 04:18:39', NULL, 1),
(96, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 04:20:18', NULL, 1),
(97, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 08:41:13', NULL, 0),
(98, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 08:41:21', NULL, 1),
(99, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 08:46:04', NULL, 1),
(100, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 15:00:00', NULL, 1),
(101, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 15:35:32', NULL, 1),
(102, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 15:59:19', NULL, 1),
(103, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 17:56:00', NULL, 0),
(104, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:05:18', NULL, 0),
(105, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:05:24', NULL, 0),
(106, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:05:32', NULL, 0),
(107, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:05:38', NULL, 0),
(108, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:05:42', NULL, 0),
(109, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:05:47', NULL, 0),
(110, NULL, 'moynulislamshimanto24@gmail.comdsfdf', 0x3a3a3100000000000000000000000000, '2025-06-09 18:05:53', NULL, 0),
(111, NULL, 'moynulislamshimanto24@gmail.comdsfdf', 0x3a3a3100000000000000000000000000, '2025-06-09 18:08:32', NULL, 0),
(112, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:08:50', NULL, 0),
(113, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:14:09', NULL, 0),
(114, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:15:09', NULL, 0),
(115, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:15:29', NULL, 0),
(116, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:17:07', NULL, 0),
(117, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:17:31', NULL, 0),
(118, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:18:57', NULL, 0),
(119, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:19:02', NULL, 0),
(120, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:19:13', NULL, 0),
(121, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:19:19', NULL, 1),
(122, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:19:44', NULL, 0),
(123, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:27:25', NULL, 0),
(124, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:27:33', NULL, 1),
(125, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:27:40', NULL, 0),
(126, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:28:39', NULL, 0),
(127, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:28:49', NULL, 0),
(128, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:29:00', NULL, 0),
(129, NULL, 'moynulislamshimanto24@gmail.comsss', 0x3a3a3100000000000000000000000000, '2025-06-09 18:29:09', NULL, 0),
(130, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:29:32', NULL, 0),
(131, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:29:53', NULL, 0),
(132, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:30:26', NULL, 0),
(133, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:31:48', NULL, 0),
(134, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:41:33', NULL, 0),
(135, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:41:43', NULL, 0),
(136, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 18:55:52', NULL, 0),
(137, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 19:07:48', NULL, 0),
(138, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-09 19:07:52', NULL, 0),
(139, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:31:10', NULL, 0),
(140, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:39:07', NULL, 1),
(141, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:39:49', NULL, 0),
(142, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:41:04', NULL, 0),
(143, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:43:51', NULL, 0),
(144, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:43:55', NULL, 0),
(145, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:44:31', NULL, 0),
(146, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:44:45', NULL, 1),
(147, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:48:32', NULL, 0),
(148, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:49:18', NULL, 0),
(149, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:49:32', NULL, 0),
(150, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:52:10', NULL, 0),
(151, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 03:58:33', NULL, 0),
(152, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:01:09', NULL, 0),
(153, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:03:38', NULL, 0),
(154, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:04:16', NULL, 0),
(155, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:04:38', NULL, 0),
(156, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:05:34', NULL, 0),
(157, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:05:46', NULL, 0),
(158, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:05:51', NULL, 0),
(159, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:05:59', NULL, 0),
(160, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:06:17', NULL, 0),
(161, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:07:01', NULL, 0),
(162, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:07:07', NULL, 0),
(163, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:09:11', NULL, 0),
(164, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:09:35', NULL, 0),
(165, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:10:05', NULL, 0),
(166, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:12:16', NULL, 0),
(167, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 04:15:56', NULL, 1),
(168, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 05:07:25', NULL, 1),
(169, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 16:16:24', NULL, 0),
(170, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 16:30:09', NULL, 1),
(171, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 16:33:33', NULL, 1),
(172, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-10 17:59:16', NULL, 1),
(173, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-11 16:08:45', NULL, 1),
(174, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-11 17:30:22', NULL, 1),
(175, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-11 18:58:44', NULL, 1),
(176, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-11 19:01:53', NULL, 1),
(177, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-12 04:01:16', NULL, 1),
(178, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-12 05:54:35', NULL, 1),
(179, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-12 06:02:00', NULL, 1),
(180, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-12 14:03:01', NULL, 1),
(181, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-12 14:16:54', NULL, 1),
(182, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-13 05:15:01', NULL, 1),
(183, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-13 14:38:49', NULL, 1),
(184, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-13 16:45:17', NULL, 1),
(185, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-13 16:45:25', NULL, 1),
(186, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-13 17:38:37', NULL, 1),
(187, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-13 18:17:24', NULL, 1),
(188, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-13 18:21:12', NULL, 1),
(189, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-13 18:22:16', NULL, 1),
(190, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-13 18:22:27', NULL, 1),
(191, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-13 18:24:01', NULL, 1),
(192, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-13 18:24:43', NULL, 1),
(193, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-17 03:39:34', NULL, 1),
(194, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-17 17:59:27', NULL, 1),
(195, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-17 18:03:06', NULL, 1),
(196, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-20 17:01:32', NULL, 1),
(197, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-20 18:02:11', NULL, 1),
(198, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-21 15:34:31', NULL, 1),
(199, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-21 17:21:36', NULL, 1),
(200, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-21 17:33:37', NULL, 1),
(201, NULL, 'pallab@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-21 18:14:40', NULL, 0),
(202, NULL, 'pallab@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-21 18:15:49', NULL, 0),
(203, 6, 'pallab@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-21 18:17:51', NULL, 1),
(204, 7, 'pallab@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-21 18:24:56', NULL, 1),
(205, 8, 'monira@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-21 19:05:13', NULL, 1),
(206, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 14:50:36', NULL, 1),
(207, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 16:12:08', NULL, 1),
(208, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 16:15:42', NULL, 1),
(209, 7, 'pallab@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 16:15:57', NULL, 1),
(210, 7, 'pallab@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 16:17:05', NULL, 1),
(211, 8, 'moynulislamshimanto29@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 16:32:49', NULL, 1),
(212, NULL, 'moynulislamshimanto11@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 16:38:42', NULL, 0),
(213, NULL, 'moynulislamshimanto11@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 16:38:53', NULL, 0),
(214, 9, 'moynulislamshimanto11@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 16:39:39', NULL, 1),
(215, 7, 'moynulislamshimanto27@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 17:12:22', NULL, 1),
(216, 7, 'moynulislamshimanto27@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 17:33:14', NULL, 1),
(217, NULL, 'moynulislamshimanto29@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 17:33:39', NULL, 0),
(218, 8, 'moynulislamshimanto29@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 17:33:49', NULL, 1),
(219, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 17:58:29', NULL, 1),
(220, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 19:01:13', NULL, 1),
(221, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-23 19:03:20', NULL, 1),
(222, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 14:45:21', NULL, 1),
(223, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 16:00:26', NULL, 1),
(224, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 16:12:31', NULL, 1),
(225, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 16:15:56', NULL, 1),
(226, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 16:27:43', NULL, 1),
(227, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 16:27:52', NULL, 1),
(228, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 16:29:54', NULL, 1),
(229, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 16:39:13', NULL, 1),
(230, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 16:40:44', NULL, 1),
(231, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 16:43:00', NULL, 1),
(232, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 16:46:50', NULL, 1),
(233, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 17:24:31', NULL, 1),
(234, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 17:27:10', NULL, 1),
(235, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 17:40:56', NULL, 1),
(236, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 17:46:50', NULL, 1),
(237, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 17:47:10', NULL, 1),
(238, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 17:52:37', NULL, 1),
(239, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 17:57:44', NULL, 1),
(240, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 17:59:53', NULL, 1),
(241, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 18:03:36', NULL, 1),
(242, 3, 'moynulislamshimanto24@gmail.com', 0x3132372e302e302e3100000000000000, '2025-06-24 18:08:55', NULL, 1),
(243, 3, 'moynulislamshimanto24@gmail.com', 0x3132372e302e302e3100000000000000, '2025-06-24 18:11:11', NULL, 1),
(244, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 18:26:42', NULL, 1),
(245, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 18:30:33', NULL, 0),
(246, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 18:30:39', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `regDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `wallet_balance` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullName`, `address`, `city`, `gender`, `email`, `password`, `regDate`, `updationDate`, `wallet_balance`) VALUES
(1, 'John Doe', 'A 123 ABC Apartment GZB 201017', 'Ghaziabad', 'male', 'johndoe12@test.com', 'f925916e2754e5e03f75dd58a5733251', '2024-04-20 12:13:56', '2024-05-14 09:28:15', 0.00),
(2, 'Amit kumar', 'new Delhi india', 'New Delhi', 'male', 'amitk@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2024-04-21 13:15:32', '2024-05-14 09:28:23', 0.00),
(3, 'Moynul Islam', 'Fatullah', 'Narayanganj', 'male', 'moynulislamshimanto24@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', '2025-05-09 07:03:04', '2025-06-24 17:09:20', 7200.00),
(4, 'Shimanto', 'Dapa Idrakpur, Fatullah.', 'Narayanganj Sadar', 'male', 'moynulislamshimanto25@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', '2025-06-09 04:13:17', '2025-06-23 16:12:26', 11700.00),
(7, 'Pollab', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.', 'Narayanganj Sadar', 'male', 'moynulislamshimanto27@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', '2025-06-21 18:24:44', '2025-06-23 17:30:04', 5200.00),
(8, 'Monira Akter', 'Dhaka', 'Dhaka', 'female', 'moynulislamshimanto29@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', '2025-06-21 19:05:00', '2025-06-23 17:57:02', 1800.00),
(9, 'Samim', 'Bayejid Bostami Road, Dapa Idrakpur, Fatullah.', 'Narayanganj Sadar', 'male', 'moynulislamshimanto11@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', '2025-06-23 16:38:28', '2025-06-23 16:41:50', 9200.00);

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_requests`
--

CREATE TABLE `wallet_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(40) NOT NULL,
  `contact_no` varchar(14) NOT NULL,
  `pin` int(11) NOT NULL,
  `tnx_id` varchar(50) NOT NULL,
  `status` enum('Pending','Approved','Rejected','Requested') DEFAULT 'Pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallet_requests`
--

INSERT INTO `wallet_requests` (`id`, `user_id`, `userName`, `amount`, `payment_method`, `contact_no`, `pin`, `tnx_id`, `status`, `requested_at`) VALUES
(31, 3, 'Moynul Islam', 20000.00, 'bKash', '01949854504', 1234, 'TXN1749202650210', 'Approved', '2025-06-06 09:37:30'),
(32, 4, 'Shimanto', 5000.00, 'Nagad', '01576547409', 1234, 'TXN1749442749949', 'Pending', '2025-06-09 04:19:09'),
(33, 4, 'Shimanto', 0.00, '', '', 0, 'TXN1749442753151', 'Approved', '2025-06-09 04:19:13'),
(34, 4, 'Shimanto', 5000.00, 'bKash', '01576547409', 1234, 'TXN1749442779691', 'Pending', '2025-06-09 04:19:39'),
(35, 4, 'Shimanto', 0.00, '', '', 0, 'TXN1749442782903', 'Approved', '2025-06-09 04:19:42'),
(36, 4, 'Shimanto', 5000.00, 'bKash', '01949854504', 1234, 'TXN1749442838889', 'Approved', '2025-06-09 04:20:38'),
(37, 3, 'Moynul Islam', 2300.00, 'bKash', '01949854504', 1234, 'TXN1749483352550', 'Approved', '2025-06-09 15:35:52'),
(38, 3, 'Moynul Islam', 2000.00, 'bKash', '01949854504', 2145, 'TXN1749528974936', 'Approved', '2025-06-10 04:16:14'),
(39, 3, 'Moynul Islam', 3000.00, 'bKash', '01949854504', 1234, 'TXN1749529036196', 'Approved', '2025-06-10 04:17:16'),
(40, 3, 'Moynul Islam', 5000.00, 'bKash', '01949854504', 1234, 'TXN1749530393900', 'Pending', '2025-06-10 04:39:53'),
(41, 3, 'Moynul Islam', 2000.00, 'bKash', '01949854504', 4556, 'TXN1749530481279', 'Approved', '2025-06-10 04:41:21'),
(42, 3, 'Moynul Islam', 3000.00, 'bKash', '01949854504', 1234, 'TXN1749530947117', 'Pending', '2025-06-10 04:49:07'),
(43, 3, 'Moynul Islam', 3000.00, 'bKash', '01949854504', 1234, 'TXN1749531468625', 'Pending', '2025-06-10 04:57:48'),
(44, 3, 'Moynul Islam', 3000.00, 'bKash', '01949854504', 1234, 'TXN1749531662298', 'Pending', '2025-06-10 05:01:02'),
(45, 3, 'Moynul Islam', 3000.00, 'bKash', '01949854504', 1234, 'TXN1749531780720', 'Approved', '2025-06-10 05:03:00'),
(46, 4, 'Shimanto', 1900.00, 'Nagad', '01949854504', 1234, 'TXN1749532087570', 'Approved', '2025-06-10 05:08:07'),
(47, 4, 'Shimanto', 20000.00, 'bKash', '01949854504', 1234, 'TXN1749665790334', 'Approved', '2025-06-11 18:16:30'),
(48, 3, 'Moynul Islam', 1000.00, 'bKash', '01949854504', 12345, 'TXN1750520093822', 'Rejected', '2025-06-21 15:34:53'),
(49, 3, 'Moynul Islam', 1200.00, 'bKash', '01949854504', 12345, 'TXN1750520912654', 'Approved', '2025-06-21 15:48:32'),
(50, 7, 'Pollab', 10000.00, 'bKash', '01576547409', 12345, 'TXN1750695378449', 'Requested', '2025-06-23 16:16:18'),
(51, 7, 'Pollab', 10000.00, 'bKash', '01576547409', 12345, 'TXN1750695475320', 'Approved', '2025-06-23 16:17:55'),
(52, 8, 'Monira Akter', 10000.00, 'bKash', '01718792704', 12345, 'TXN1750696391466', 'Approved', '2025-06-23 16:33:11'),
(53, 9, 'Samim', 10000.00, 'bKash', '01949854504', 11111, 'TXN1750696801114', 'Approved', '2025-06-23 16:40:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ambulances`
--
ALTER TABLE `ambulances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ambulance_bookings`
--
ALTER TABLE `ambulance_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ambulance_id` (`ambulance_number`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_date` (`doctorId`,`appointmentDate`);

--
-- Indexes for table `beds`
--
ALTER TABLE `beds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctorslog`
--
ALTER TABLE `doctorslog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctorspecilization`
--
ALTER TABLE `doctorspecilization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `em_doctors`
--
ALTER TABLE `em_doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labs`
--
ALTER TABLE `labs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcontactus`
--
ALTER TABLE `tblcontactus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblmedicalhistory`
--
ALTER TABLE `tblmedicalhistory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpage`
--
ALTER TABLE `tblpage`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpatient`
--
ALTER TABLE `tblpatient`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet_requests`
--
ALTER TABLE `wallet_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ambulances`
--
ALTER TABLE `ambulances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ambulance_bookings`
--
ALTER TABLE `ambulance_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `doctorslog`
--
ALTER TABLE `doctorslog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `doctorspecilization`
--
ALTER TABLE `doctorspecilization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `em_doctors`
--
ALTER TABLE `em_doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `labs`
--
ALTER TABLE `labs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblcontactus`
--
ALTER TABLE `tblcontactus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblmedicalhistory`
--
ALTER TABLE `tblmedicalhistory`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblpage`
--
ALTER TABLE `tblpage`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblpatient`
--
ALTER TABLE `tblpatient`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wallet_requests`
--
ALTER TABLE `wallet_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblpatient`
--
ALTER TABLE `tblpatient`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
