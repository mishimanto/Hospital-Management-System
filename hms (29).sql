-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2025 at 03:29 AM
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
  `appointment_number` varchar(100) DEFAULT NULL,
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
  `payment_status` enum('Pending','Paid','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `appointment_number`, `doctorSpecialization`, `doctorId`, `userId`, `userEmail`, `consultancyFees`, `appointmentDate`, `appointmentTime`, `postingDate`, `userStatus`, `doctorStatus`, `serialNumber`, `updationDate`, `payment_method`, `payment_amount`, `payment_status`) VALUES
(81, NULL, 'Orthopedics', 5, 8, 'moynulislamshimanto29@gmail.com', 1200, '2025-06-24', '17:10:00', '2025-06-23 17:55:19', 1, 1, '2', NULL, 'Wallet', 1200.00, 'Paid'),
(82, NULL, 'ENT', 1, 8, 'moynulislamshimanto29@gmail.com', 500, '2025-06-24', '17:00:00', '2025-06-23 17:57:02', 1, 1, '1', NULL, 'Wallet', 500.00, 'Paid'),
(83, 'APT-83', 'ENT', 1, 3, 'moynulislamshimanto24@gmail.com', 500, '2025-06-24', '17:10:00', '2025-06-23 17:58:53', 1, 1, '2', '2025-06-25 17:45:25', 'Wallet', 500.00, 'Paid'),
(84, NULL, 'Pediatrics', 4, 3, 'moynulislamshimanto24@gmail.com', 700, '2025-06-24', '17:10:00', '2025-06-23 18:00:02', 1, 1, '2', NULL, 'Wallet', 700.00, 'Paid'),
(85, NULL, 'Orthopedics', 5, 3, 'moynulislamshimanto24@gmail.com', 1200, '2025-06-25', '17:00:00', '2025-06-23 18:01:05', 1, 1, '1', NULL, 'Wallet', 1200.00, 'Paid'),
(86, NULL, 'Internal Medicine', 6, 3, 'moynulislamshimanto24@gmail.com', 1500, '2025-06-25', '17:00:00', '2025-06-23 18:02:02', 1, 1, '1', NULL, 'Wallet', 1500.00, 'Paid'),
(87, NULL, 'Obstetrics and Gynecology', 7, 3, 'moynulislamshimanto24@gmail.com', 800, '2025-06-25', '17:00:00', '2025-06-23 18:03:04', 1, 1, '1', NULL, 'Wallet', 800.00, 'Paid'),
(89, NULL, 'Pediatrics', 4, 3, 'moynulislamshimanto24@gmail.com', 700, '2025-06-25', '17:00:00', '2025-06-23 18:06:54', 1, 1, '1', NULL, 'Wallet', 700.00, 'Paid'),
(90, NULL, 'ENT', 1, 3, 'moynulislamshimanto24@gmail.com', 500, '2025-06-25', '17:00:00', '2025-06-23 18:08:24', 1, 1, '1', NULL, 'Wallet', 500.00, 'Paid'),
(91, NULL, 'Encologist', 8, 3, 'moynulislamshimanto24@gmail.com', 1000, '2025-06-25', '17:00:00', '2025-06-24 16:58:10', 1, 1, '1', NULL, 'Wallet', 1000.00, 'Paid'),
(94, NULL, 'Endocrinologists', 2, 3, 'moynulislamshimanto24@gmail.com', 800, '2025-06-25', '17:00:00', '2025-06-24 17:09:20', 1, 1, '1', NULL, 'Wallet', 800.00, 'Paid'),
(100, 'APT-100', 'Orthopedics', 5, 3, 'moynulislamshimanto24@gmail.com', 1200, '2025-06-26', '17:00:00', '2025-06-25 14:39:57', 1, 1, '1', '2025-06-25 17:16:57', 'Wallet', 1200.00, 'Paid'),
(101, 'APT-101', 'Obstetrics and Gynecology', 7, 3, 'moynulislamshimanto24@gmail.com', 800, '2025-06-26', '17:00:00', '2025-06-25 14:52:56', 1, 1, '1', '2025-06-25 14:52:56', 'Wallet', 800.00, 'Paid'),
(102, 'APT-102', 'Internal Medicine', 6, 3, 'moynulislamshimanto24@gmail.com', 1500, '2025-06-26', '17:00:00', '2025-06-25 15:11:57', 0, 1, '1', '2025-06-25 20:09:37', 'Wallet', 1500.00, 'Paid'),
(103, 'APT-103', 'Internal Medicine', 6, 8, 'moynulislamshimanto29@gmail.com', 1500, '2025-06-26', '17:10:00', '2025-06-25 17:39:23', 1, 1, '2', '2025-06-25 17:39:23', 'Wallet', 1500.00, 'Paid'),
(104, 'APT-104', 'Orthopedics', 5, 3, 'moynulislamshimanto24@gmail.com', 1200, '2025-06-27', '17:00:00', '2025-06-25 20:10:31', 0, 1, '1', '2025-06-25 20:10:47', 'Wallet', 1200.00, 'Paid'),
(105, 'APT-105', 'Orthopedics', 5, 4, 'moynulislamshimanto25@gmail.com', 1200, '2025-06-27', '17:10:00', '2025-06-25 20:11:34', 1, 1, '2', '2025-06-25 20:11:34', 'Wallet', 1200.00, 'Paid'),
(106, 'APT-106', 'ENT', 1, 3, 'moynulislamshimanto24@gmail.com', 500, '2025-06-27', '17:00:00', '2025-06-26 17:33:58', 1, 1, '1', '2025-06-26 17:33:58', 'Wallet', 500.00, 'Paid'),
(107, 'APT-107', 'Pediatrics', 4, 3, 'moynulislamshimanto24@gmail.com', 700, '2025-06-27', '17:00:00', '2025-06-26 17:34:32', 1, 1, '1', '2025-06-26 17:34:32', 'Wallet', 700.00, 'Paid'),
(108, 'APT-108', 'Internal Medicine', 6, 4, 'moynulislamshimanto25@gmail.com', 1500, '2025-06-27', '17:00:00', '2025-06-26 17:35:11', 1, 1, '1', '2025-06-26 17:35:11', 'Wallet', 1500.00, 'Paid'),
(109, 'APT-109', 'Pediatrics', 4, 4, 'moynulislamshimanto25@gmail.com', 700, '2025-06-27', '17:10:00', '2025-06-26 17:35:29', 1, 1, '2', '2025-06-26 17:35:29', 'Wallet', 700.00, 'Paid'),
(110, 'APT-110', 'ENT', 1, 4, 'moynulislamshimanto25@gmail.com', 500, '2025-06-27', '17:10:00', '2025-06-26 17:35:48', 1, 1, '2', '2025-06-26 17:35:48', 'Wallet', 500.00, 'Paid'),
(111, 'APT-111', 'Orthopedics', 5, 3, 'moynulislamshimanto24@gmail.com', 1200, '2025-07-08', '17:00:00', '2025-07-07 19:25:32', 1, 1, '1', '2025-07-07 19:26:05', 'Wallet', 1200.00, 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `beds`
--

CREATE TABLE `beds` (
  `bed_id` int(11) NOT NULL,
  `ward_id` int(11) NOT NULL,
  `bed_number` varchar(20) NOT NULL,
  `bed_type` varchar(200) NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('Available','Occupied','Maintenance','Reserved') DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beds`
--

INSERT INTO `beds` (`bed_id`, `ward_id`, `bed_number`, `bed_type`, `price_per_day`, `status`, `created_at`) VALUES
(1, 1, 'GA-101', 'General', 500.00, 'Available', '2025-07-02 16:53:36'),
(4, 3, 'PED-01', 'General', 500.00, 'Available', '2025-07-02 16:53:36'),
(5, 7, 'ICU-501', 'ICU', 20000.00, 'Available', '2025-07-03 04:18:57'),
(6, 1, 'GA-102', 'General', 500.00, 'Available', '2025-07-04 17:51:14'),
(7, 9, 'NI-601', 'NICU', 15000.00, 'Available', '2025-07-06 03:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `bed_assignments`
--

CREATE TABLE `bed_assignments` (
  `id` int(11) NOT NULL,
  `bed_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admission_date` datetime NOT NULL,
  `discharge_date` datetime DEFAULT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `total_charge` decimal(10,2) DEFAULT 0.00,
  `payment_status` enum('Pending','Paid','Partially Paid') DEFAULT 'Pending',
  `status` enum('Admitted','Discharged','Transferred') DEFAULT 'Admitted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bed_assignments`
--

INSERT INTO `bed_assignments` (`id`, `bed_id`, `patient_id`, `user_id`, `admission_date`, `discharge_date`, `price_per_day`, `total_charge`, `payment_status`, `status`, `created_at`) VALUES
(35, 1, 34, 3, '2025-07-05 13:25:01', '2025-07-05 13:25:23', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-05 07:25:01'),
(36, 6, 35, 3, '2025-07-05 13:28:26', '2025-07-05 13:28:56', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-05 07:28:26'),
(37, 1, 36, 4, '2025-07-05 13:48:27', '2025-07-05 13:50:40', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-05 07:48:27'),
(38, 6, 37, 4, '2025-07-05 13:48:38', '2025-07-05 13:49:55', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-05 07:48:38'),
(39, 4, 38, 4, '2025-07-05 13:48:49', '2025-07-05 13:54:37', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-05 07:48:49'),
(40, 1, 39, 3, '2025-07-05 23:33:41', '2025-07-05 23:56:08', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-05 17:33:41'),
(41, 1, 40, 3, '2025-07-06 14:46:20', '2025-07-06 14:46:35', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-06 08:46:20'),
(42, 1, 41, 3, '2025-07-06 15:08:11', '2025-07-06 15:08:20', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-06 09:08:11'),
(43, 1, 42, 3, '2025-07-06 00:50:56', '2025-07-07 00:51:41', 500.00, 1000.00, 'Paid', 'Discharged', '2025-07-06 18:50:56'),
(44, 1, 43, 3, '2025-07-07 01:07:14', '2025-07-07 01:07:33', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-06 19:07:14'),
(45, 1, 44, 3, '2025-07-07 22:37:34', '2025-07-08 00:12:19', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-07 16:37:34'),
(46, 6, 45, 3, '2025-07-07 22:44:33', '2025-07-07 23:15:08', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-07 16:44:33'),
(47, 1, 46, 3, '2025-07-08 00:32:18', '2025-07-08 00:34:16', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-07 18:32:18'),
(48, 4, 47, 3, '2025-07-08 00:46:47', '2025-07-08 00:58:07', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-07 18:46:47'),
(49, 1, 48, 3, '2025-07-08 00:57:09', '2025-07-08 01:19:18', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-07 18:57:09'),
(50, 1, 49, 3, '2025-07-08 01:28:19', '2025-07-08 01:28:31', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-07 19:28:19'),
(51, 6, 50, 3, '2025-07-08 01:29:09', '2025-07-08 01:29:19', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-07 19:29:09'),
(52, 1, 51, 3, '2025-07-08 01:36:00', '2025-07-08 01:36:21', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-07 19:36:00'),
(53, 4, 52, 3, '2025-07-08 20:10:18', '2025-07-08 20:37:59', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-08 14:10:18'),
(55, 6, 54, 3, '2025-07-08 21:20:45', '2025-07-08 22:05:03', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-08 15:20:45'),
(56, 6, 55, 3, '2025-07-08 22:28:07', '2025-07-08 22:29:26', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-08 16:28:07'),
(57, 4, 56, 3, '2025-07-08 22:48:06', '2025-07-08 22:48:59', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-08 16:48:06'),
(58, 1, 57, 3, '2025-07-08 23:14:32', '2025-07-08 23:52:27', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-08 17:14:32'),
(59, 6, 58, 3, '2025-07-08 23:52:04', '2025-07-09 00:07:20', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-08 17:52:04'),
(60, 1, 59, 3, '2025-07-09 00:08:59', '2025-07-09 00:53:17', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-08 18:08:59'),
(61, 6, 60, 3, '2025-07-09 00:44:57', '2025-07-09 00:51:44', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-08 18:44:57'),
(62, 4, 61, 3, '2025-07-09 00:54:11', '2025-07-09 00:58:48', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-08 18:54:11'),
(63, 1, 62, 3, '2025-07-09 01:04:39', '2025-07-09 01:06:41', 500.00, 500.00, 'Paid', 'Discharged', '2025-07-08 19:04:39');

-- --------------------------------------------------------

--
-- Table structure for table `diagnostic_tests`
--

CREATE TABLE `diagnostic_tests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `preparation` text DEFAULT NULL,
  `normal_range` varchar(100) DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diagnostic_tests`
--

INSERT INTO `diagnostic_tests` (`id`, `name`, `category_id`, `description`, `price`, `preparation`, `normal_range`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(1, 'Complete Blood Count (CBC)', 1, 'Measures various components of blood', 500.00, 'Fasting not required', 'Varies by component', '09:00:00', '22:00:00', '2025-06-30 13:58:12', '2025-06-30 19:06:51'),
(2, 'Hemoglobin (Hb)', 1, 'Measures hemoglobin level in blood', 200.00, 'Fasting not required', 'Male: 13.5-17.5 g/dL, Female: 12.0-15.5 g/dL', '00:00:00', '00:00:00', '2025-06-30 13:58:12', NULL),
(3, 'Blood Glucose (Fasting)', 2, 'Measures blood sugar level after fasting', 300.00, '8-12 hours fasting required', '70-100 mg/dL', '00:00:00', '00:00:00', '2025-06-30 13:58:12', NULL),
(4, 'Lipid Profile', 2, 'Measures cholesterol and triglycerides', 800.00, '12-14 hours fasting required', 'Total Cholesterol: <200 mg/dL, LDL: <100 mg/dL, HDL: >40 mg/dL', '00:00:00', '00:00:00', '2025-06-30 13:58:12', NULL),
(5, 'Urine Routine Examination', 3, 'General urine analysis', 250.00, 'Mid-stream clean catch sample', 'Color: Pale yellow, pH: 4.5-8.0', '00:00:00', '00:00:00', '2025-06-30 13:58:12', NULL),
(6, 'X-ray Chest (PA View)', 5, 'Chest X-ray examination', 600.00, 'No special preparation', 'N/A', '00:00:00', '00:00:00', '2025-06-30 13:58:12', NULL),
(7, 'Ultrasound Whole Abdomen', 5, 'Imaging of abdominal organs', 1200.00, '6 hours fasting required', 'N/A', '00:00:00', '00:00:00', '2025-06-30 13:58:12', NULL),
(8, 'ECG', 5, 'Electrocardiogram', 400.00, 'No special preparation', 'Normal sinus rhythm', '00:00:00', '00:00:00', '2025-06-30 13:58:12', NULL),
(9, 'Thyroid Profile (T3, T4, TSH)', 2, 'Thyroid function tests', 900.00, 'Fasting not required', 'TSH: 0.4-4.0 mIU/L, T4: 5-12 μg/dL', '00:00:00', '00:00:00', '2025-06-30 13:58:12', NULL),
(10, 'Liver Function Test', 2, 'Measures liver enzymes and proteins', 700.00, 'Fasting not required', 'ALT: 7-55 U/L, AST: 8-48 U/L', '00:00:00', '00:00:00', '2025-06-30 13:58:12', '2025-06-30 18:48:30');

-- --------------------------------------------------------

--
-- Table structure for table `discharge_requests`
--

CREATE TABLE `discharge_requests` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `request_date` datetime NOT NULL DEFAULT current_timestamp(),
  `requested_by` varchar(200) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `processed_by` int(11) DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `payment_requested` tinyint(1) DEFAULT 0,
  `payment_done` tinyint(1) DEFAULT 0,
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discharge_requests`
--

INSERT INTO `discharge_requests` (`id`, `assignment_id`, `request_date`, `requested_by`, `status`, `processed_by`, `processed_at`, `payment_requested`, `payment_done`, `rejection_reason`) VALUES
(22, 35, '2025-07-05 13:25:09', '', 'Approved', 1, '2025-07-05 13:25:23', 0, 0, NULL),
(23, 36, '2025-07-05 13:28:35', '', 'Approved', 1, '2025-07-05 13:28:56', 0, 0, NULL),
(24, 38, '2025-07-05 13:49:17', '', 'Approved', 1, '2025-07-05 13:49:55', 0, 0, NULL),
(25, 37, '2025-07-05 13:50:33', '', 'Approved', 1, '2025-07-05 13:50:40', 0, 0, NULL),
(26, 39, '2025-07-05 13:54:30', '', 'Approved', 1, '2025-07-05 13:54:37', 0, 0, NULL),
(27, 40, '2025-07-05 23:55:33', '', 'Approved', 1, '2025-07-05 23:56:08', 0, 0, NULL),
(28, 41, '2025-07-06 14:46:26', '', 'Approved', 1, '2025-07-06 14:46:35', 0, 0, NULL),
(29, 42, '2025-07-06 15:08:14', '', 'Approved', 1, '2025-07-06 15:08:20', 0, 0, NULL),
(30, 43, '2025-07-07 00:51:01', '', 'Approved', 1, '2025-07-07 00:51:41', 0, 0, NULL),
(31, 44, '2025-07-07 01:07:22', '', 'Approved', 1, '2025-07-07 01:07:33', 0, 0, NULL),
(32, 46, '2025-07-07 23:11:11', '', 'Approved', 1, '2025-07-07 23:15:08', 0, 0, NULL),
(34, 45, '2025-07-08 00:12:05', '', 'Approved', 1, '2025-07-08 00:12:19', 0, 0, NULL),
(35, 47, '2025-07-08 00:32:26', '', 'Approved', 1, '2025-07-08 00:34:16', 0, 0, NULL),
(36, 48, '2025-07-08 00:50:34', 'Dr. Majid', 'Approved', 1, '2025-07-08 00:58:07', 0, 0, NULL),
(37, 49, '2025-07-08 01:19:11', 'Dr. Majid', 'Approved', 1, '2025-07-08 01:19:18', 0, 0, NULL),
(38, 50, '2025-07-08 01:28:25', 'Dr. Majid', 'Approved', 1, '2025-07-08 01:28:31', 0, 0, NULL),
(39, 51, '2025-07-08 01:29:14', 'Dr. Majid', 'Approved', 1, '2025-07-08 01:29:19', 0, 0, NULL),
(40, 52, '2025-07-08 01:36:06', 'Dr. Majid', 'Approved', 1, '2025-07-08 01:36:21', 0, 0, NULL),
(41, 53, '2025-07-08 20:10:34', 'Dr. Majid', 'Approved', 1, '2025-07-08 20:37:59', 0, 0, NULL),
(43, 55, '2025-07-08 21:21:11', 'Dr. Majid', 'Approved', 1, '2025-07-08 22:05:03', 1, 1, NULL),
(44, 56, '2025-07-08 22:28:25', 'Dr. Majid', 'Approved', 1, '2025-07-08 22:29:26', 1, 1, NULL),
(45, 57, '2025-07-08 22:48:17', 'Dr. Majid', 'Approved', 1, '2025-07-08 22:48:59', 1, 1, NULL),
(46, 58, '2025-07-08 23:43:41', 'Dr. Majid', 'Approved', 1, '2025-07-08 23:52:27', 1, 1, NULL),
(47, 59, '2025-07-08 23:52:36', 'Dr. Majid', 'Approved', 1, '2025-07-09 00:07:20', 1, 1, NULL),
(48, 60, '2025-07-09 00:09:06', 'Dr. Majid', 'Rejected', 1, '2025-07-09 00:44:15', 1, 1, 'ok'),
(49, 61, '2025-07-09 00:45:13', 'Dr. Majid', 'Rejected', 1, '2025-07-09 00:46:39', 0, 0, 'No more'),
(50, 61, '2025-07-09 00:49:27', 'Dr. Majid', 'Approved', 1, '2025-07-09 00:51:44', 1, 1, NULL),
(51, 60, '2025-07-09 00:52:26', 'Dr. Majid', 'Approved', 1, '2025-07-09 00:53:17', 1, 1, NULL),
(52, 62, '2025-07-09 00:54:18', 'Dr. Majid', 'Approved', 1, '2025-07-09 00:58:48', 1, 1, NULL),
(53, 63, '2025-07-09 01:04:46', 'Dr. Majid', 'Rejected', 1, '2025-07-09 01:04:58', 0, 0, 'Not ok'),
(54, 63, '2025-07-09 01:05:38', 'Dr. Majid', 'Approved', 1, '2025-07-09 01:06:41', 1, 1, NULL);

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
(1, 'ENT', 'Anowar Hossain', 'DMC', '500', '01911111111', 'anowar@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2025-06-10 18:16:52', '2025-07-07 16:08:29', '17:00:00', '20:00:00'),
(2, 'Endocrinologists', 'Monir Hossain', 'DMC', '800', '01911111111', 'monir@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2025-06-11 01:06:41', '2025-06-28 19:06:44', '17:00:00', '21:00:00'),
(4, 'Pediatrics', 'Priyanka Chowdhury', 'DMC', '700', '01911111111', 'priyanka2@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2025-06-16 09:12:23', '2025-06-28 19:06:48', '17:00:00', '21:00:00'),
(5, 'Orthopedics', 'Vipin Tamal', 'DMC', '1200', '01911111111', 'vpint123@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2025-06-16 09:13:11', '2025-06-28 19:06:53', '17:00:00', '21:00:00'),
(6, 'Internal Medicine', 'Romil Khan', 'ShMC', '1500', '01911111111', 'drromil12@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2025-06-16 09:14:11', '2025-06-28 19:07:10', '17:00:00', '21:00:00'),
(7, 'Obstetrics and Gynecology', 'Shohag Mia', 'DMC', '800', '01911111111', 'shohag@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2025-06-16 09:15:18', '2025-06-28 19:07:18', '17:00:00', '21:00:00'),
(8, 'Encologist', 'Mosharof Khan', 'ShMC', '1000', '01911111111', 'moynulislamshimanto24@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2025-06-17 15:32:29', '2025-07-07 16:09:15', '17:00:00', '21:00:00'),
(9, 'Pathology', 'Najnin Akter', 'DMC', '800', '01911111111', 'najnin@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2025-06-28 06:26:08', '2025-07-07 16:10:21', '17:00:00', '21:00:00');

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
(14, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-06-12 14:06:16', NULL, 1),
(15, NULL, 'najnin@gmail.com', 0x3132372e302e302e3100000000000000, '2025-06-25 13:51:58', NULL, 0),
(16, NULL, 'najnin@gmail.com', 0x3132372e302e302e3100000000000000, '2025-06-25 13:52:19', NULL, 0),
(17, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-06-25 13:52:54', '26-06-2025 12:16:51 AM', 1),
(18, NULL, 'vpint123@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-25 18:47:14', NULL, 0),
(19, 5, 'vpint123@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-25 18:47:25', '26-06-2025 12:29:05 AM', 1),
(20, 6, 'drromil12@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-25 18:59:25', NULL, 1),
(21, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-06-26 17:33:09', '26-06-2025 11:06:06 PM', 1),
(22, 5, 'vpint123@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-26 17:36:57', NULL, 1),
(23, 5, 'vpint123@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-27 04:21:32', '27-06-2025 11:06:35 AM', 1),
(24, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-06-27 05:36:51', '27-06-2025 11:21:31 AM', 1),
(25, 4, 'priyanka2@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-27 05:51:46', NULL, 1),
(26, NULL, 'anowar@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-28 06:04:25', NULL, 0),
(27, NULL, 'najnin@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-28 06:04:42', NULL, 0),
(28, NULL, 'najnin@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-28 06:04:54', NULL, 0),
(29, NULL, 'najnin@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-28 06:05:07', NULL, 0),
(30, 1, 'anujk123@test.com', 0x3a3a3100000000000000000000000000, '2025-06-28 06:05:52', NULL, 1),
(31, 9, 'najnin@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-28 06:26:32', NULL, 1),
(32, 9, 'najnin@gmail.com', 0x3132372e302e302e3100000000000000, '2025-07-01 04:22:09', NULL, 1),
(33, NULL, 'vpint123@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-01 13:59:27', NULL, 0),
(34, 5, 'vpint123@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-01 13:59:36', NULL, 1),
(35, 5, 'vpint123@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-01 15:30:00', NULL, 1),
(36, 5, 'vpint123@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-01 18:58:48', NULL, 1),
(37, 5, 'vpint123@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 14:17:00', '07-07-2025 08:01:41 PM', 1),
(38, 1, 'majid@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 14:32:12', NULL, 1),
(39, 1, 'majid@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 15:05:21', NULL, 1),
(40, 1, 'majid@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 15:05:46', NULL, 1),
(41, 1, 'majid@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 15:13:03', '07-07-2025 08:43:06 PM', 1),
(42, 1, 'majid@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 15:14:03', '07-07-2025 08:45:59 PM', 1),
(43, 1, 'anowar@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 15:16:24', '07-07-2025 08:46:28 PM', 1),
(44, 1, 'majid@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 15:16:42', '07-07-2025 08:49:14 PM', 1),
(45, 1, 'majid@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 15:21:00', '07-07-2025 09:14:03 PM', 1),
(46, 1, 'majid@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 16:14:38', '07-07-2025 10:03:23 PM', 1),
(47, 1, 'majid@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 16:33:35', '08-07-2025 12:52:42 AM', 1),
(48, 5, 'vpint123@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 19:23:07', '08-07-2025 12:57:45 AM', 1),
(49, 1, 'majid@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 19:27:55', NULL, 1),
(50, NULL, 'majid@gmail.ocm', 0x3132372e302e302e3100000000000000, '2025-07-08 14:05:49', NULL, 0),
(51, NULL, 'moynulislamshimanto24@gmail.com', 0x3132372e302e302e3100000000000000, '2025-07-08 14:05:59', NULL, 0),
(52, NULL, 'mdskhnfdk@fdhf', 0x3132372e302e302e3100000000000000, '2025-07-08 14:07:36', NULL, 0),
(53, NULL, 'majid@gmail.com', 0x3132372e302e302e3100000000000000, '2025-07-08 14:07:52', NULL, 0),
(54, 1, 'majid@gmail.com', 0x3132372e302e302e3100000000000000, '2025-07-08 14:08:07', NULL, 1);

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
(19, 'Medicine', '2025-06-13 07:10:35', NULL),
(20, 'Encologist', '2025-06-27 17:39:59', NULL);

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
(1, 'Dr. Majid', '015XXXXXXXX', 'majid@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', 'Medicine', 'MBBS(DU)', '6AM - 6PM', 1, '2025-06-13 15:47:14', '2025-07-07 16:11:44'),
(3, 'Dr. Hamid khan', '017XXXXXXXX', 'hamid@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', 'Medicine', 'MBBS(DMC), BCS(Health).', '6 PM - 6 AM ', 1, '2025-06-13 15:47:14', '2025-06-28 18:57:08'),
(4, 'Dr. Jahir khan', '016XXXXXXXX', 'jahir@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', 'Medicine', 'MBBS(DMC)', '6 PM - 6 AM ', 1, '2025-06-13 15:48:14', '2025-06-28 18:56:59'),
(5, 'Samim Hossain', '016XXXXXXXX', 'samim@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', 'Medicine', 'MBBS', '6 AM - 6 PM', 1, '2025-06-23 14:47:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

CREATE TABLE `labs` (
  `id` int(11) NOT NULL,
  `lab_number` varchar(15) DEFAULT NULL,
  `count` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labs`
--

INSERT INTO `labs` (`id`, `lab_number`, `count`, `created_at`) VALUES
(1, '101', 1, '2025-06-20 19:05:11'),
(2, '102', 1, '2025-06-20 19:05:12');

-- --------------------------------------------------------

--
-- Table structure for table `lab_technicians`
--

CREATE TABLE `lab_technicians` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `lab_id` int(11) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_technicians`
--

INSERT INTO `lab_technicians` (`id`, `name`, `email`, `password`, `address`, `phone`, `lab_id`, `qualification`, `creationDate`, `updationDate`) VALUES
(1, 'Omar Faruk', 'moynulislamshimanto11@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', 'Narayanganj', '015XXXXXXXX', 1, 'DMF', '2025-07-01 04:03:22', '2025-07-01 04:05:55');

-- --------------------------------------------------------

--
-- Table structure for table `ordered_tests`
--

CREATE TABLE `ordered_tests` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Sample Collected','Processing','Completed','Cancelled') DEFAULT 'Pending',
  `result` text DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordered_tests`
--

INSERT INTO `ordered_tests` (`id`, `order_id`, `test_id`, `price`, `status`, `result`, `completed_at`) VALUES
(1, 1, 9, 900.00, 'Pending', NULL, NULL),
(2, 2, 9, 900.00, 'Completed', 'TH: 00', '2025-07-01 18:54:05'),
(3, 3, 9, 900.00, 'Completed', 'Th', '2025-07-01 19:11:09'),
(5, 5, 1, 500.00, 'Pending', NULL, NULL),
(6, 5, 2, 200.00, 'Pending', NULL, NULL),
(7, 6, 3, 300.00, 'Pending', NULL, NULL),
(8, 7, 6, 600.00, 'Completed', 'No Problem', '2025-07-01 19:19:16'),
(9, 8, 2, 200.00, 'Completed', 'HB: 0.5', '2025-07-01 18:51:22'),
(10, 9, 1, 500.00, 'Completed', 'Veery Good', '2025-07-01 18:41:04'),
(11, 10, 2, 200.00, 'Completed', 'All ok', '2025-07-01 18:28:00'),
(12, 11, 1, 500.00, 'Completed', 'Ok Go', '2025-07-01 17:21:20'),
(19, 16, 5, 250.00, 'Completed', 'Not good at all', '2025-07-01 16:41:00'),
(20, 17, 1, 500.00, 'Completed', 'Ok', '2025-07-01 16:05:17'),
(21, 18, 1, 500.00, 'Completed', 'everything ok', '2025-07-01 15:44:15'),
(22, 19, 3, 300.00, 'Completed', 'too much', '2025-07-01 16:28:19'),
(23, 19, 4, 800.00, 'Completed', 'no problem', '2025-07-01 16:28:52');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `PatientName` varchar(100) NOT NULL,
  `PatientContno` varchar(20) NOT NULL,
  `PatientGender` varchar(10) NOT NULL,
  `PatientAdd` varchar(255) NOT NULL,
  `reg_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `user_id`, `PatientName`, `PatientContno`, `PatientGender`, `PatientAdd`, `reg_date`) VALUES
(17, 4, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 00:36:58'),
(18, 4, 'Shimanto', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 00:38:51'),
(19, 4, 'Shimanto', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 00:42:03'),
(20, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 01:07:13'),
(21, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 01:07:29'),
(22, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 01:07:51'),
(23, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 01:10:48'),
(24, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 01:27:43'),
(26, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 10:12:39'),
(27, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 10:18:10'),
(28, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 10:25:25'),
(29, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 11:31:39'),
(30, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 11:52:22'),
(31, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 12:11:53'),
(32, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 12:49:05'),
(33, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 13:12:55'),
(34, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.', '2025-07-05 13:25:01'),
(35, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 13:28:26'),
(36, 4, 'Shimanto', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 13:48:27'),
(37, 4, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 13:48:38'),
(38, 4, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-05 13:48:49'),
(39, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.', '2025-07-05 23:33:41'),
(40, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah', '2025-07-06 14:46:20'),
(41, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-06 15:08:11'),
(42, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-07 00:50:56'),
(43, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-07 01:07:14'),
(44, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah', '2025-07-07 22:37:34'),
(45, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-07 22:44:33'),
(46, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-08 00:32:18'),
(47, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-08 00:46:47'),
(48, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\n', '2025-07-08 00:57:09'),
(49, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-08 01:28:19'),
(50, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-08 01:29:09'),
(51, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.', '2025-07-08 01:36:00'),
(52, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.', '2025-07-08 20:10:18'),
(53, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-08 20:52:46'),
(54, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-08 21:20:45'),
(55, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-08 22:28:07'),
(56, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.', '2025-07-08 22:48:06'),
(57, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-08 23:14:32'),
(58, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-08 23:52:04'),
(59, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.', '2025-07-09 00:08:59'),
(60, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-09 00:44:57'),
(61, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-09 00:54:11'),
(62, 3, 'Moynul Islam ', '01949854504', 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', '2025-07-09 01:04:39');

-- --------------------------------------------------------

--
-- Table structure for table `tblcontactus`
--

CREATE TABLE `tblcontactus` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contactno` varchar(12) DEFAULT NULL,
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
(2, 'Anik Dewan', 'anik@gmail.com', '01563457192', 'This is for testing', '2025-05-13 13:13:41', 'Contact the patient', '2025-06-28 19:04:17', 1),
(3, 'Shimanto', 'moynulislamshimanto24@gmail.com', '01949854504', 'Nice and Attractive', '2025-05-20 14:20:22', NULL, '2025-06-28 19:03:30', NULL);

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
  `appointment_number` varchar(100) DEFAULT NULL,
  `Docid` int(10) DEFAULT NULL,
  `PatientName` varchar(200) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `PatientContno` varchar(11) DEFAULT NULL,
  `age` varchar(11) DEFAULT NULL,
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

INSERT INTO `tblpatient` (`ID`, `appointment_number`, `Docid`, `PatientName`, `user_id`, `PatientContno`, `age`, `PatientEmail`, `PatientGender`, `PatientAdd`, `PatientAge`, `PatientMedhis`, `Prescription`, `Tests`, `CreationDate`, `UpdationDate`) VALUES
(1, NULL, 1, 'Rahul Kabir', NULL, '01745758392', NULL, 'rahul12@gmail.com', 'Male', 'NA', 32, 'Fever, Cold', '', '', '2025-05-16 05:23:35', '2025-05-30 16:13:07'),
(2, NULL, 1, 'Amit Hasan', NULL, '01845758543', NULL, 'amitk@gmail.com', 'Male', 'NA', 45, 'Fever', '', '', '2025-05-16 09:01:26', '2025-05-30 16:13:11'),
(3, NULL, 1, 'Moynul Islam Shimanto', NULL, '01949854504', NULL, 'moynulislamshimanto24@gmail.com', 'Male', 'Fatullah, Narayanganj.\r\n', 18, 'Asthma', '', '', '2025-05-30 16:12:44', '2025-06-12 08:09:11'),
(4, NULL, 1, 'Shimanto', NULL, '01949854504', NULL, 'moynulislamshimanto25@gmail.com', 'Male', 'Narayanganj', 27, 'Fever', '', '', '2025-06-10 16:20:41', '2025-06-12 08:09:17'),
(5, NULL, 1, 'Ripon', NULL, '01955215474', NULL, 'riponhossainmd744@gmail.com', 'Male', 'Jorpul', 27, 'Diahorrea', '', '', '2025-06-10 16:28:53', '2025-06-12 08:09:37'),
(6, NULL, 1, 'Shimanto', NULL, '0194985450', NULL, 'moynulislamshimanto25@gmail.com', 'Male', 'Narayanganj', 27, 'Fever', 'Napa 500mg   1+1+1', 'CVC', '2025-06-12 05:56:15', '2025-06-12 08:09:44'),
(8, NULL, 1, 'Moynul Islam Shimanto', 3, '01949854504', NULL, 'moynulislamshimanto24@gmail.com', 'Male', 'Narayanganj', 27, 'Fever', 'Napa 500mg   1+1+1', 'CVC', '2025-06-12 15:14:58', NULL),
(9, NULL, 1, 'Shimanto', 4, '01949854504', NULL, 'moynulislamshimanto24@gmail.com', 'Male', 'Narayanganj', 27, 'Pain', 'Fast 500mg   1+1+1', 'MRI', '2025-06-12 15:28:02', NULL),
(10, NULL, 1, 'Monira Akter', 4, '01949854504', NULL, 'moynulislamshimanto25@gmail.com', 'Male', 'Narayanganj', 27, NULL, '', '', '2025-06-12 15:50:16', NULL),
(11, NULL, 1, 'Monira Akter', 4, '01949854504', NULL, 'moynulislamshimanto25@gmail.com', 'Male', 'Narayanganj', 27, NULL, '', '', '2025-06-12 15:52:47', NULL),
(12, 'APT-83', 1, 'Moynul Islam', 3, '', NULL, 'moynulislamshimanto24@gmail.com', 'Male', 'Fatullah', 0, 'Pain', 'Flexi 1+1+1', 'ECG', '2025-06-25 17:57:49', '2025-06-25 18:11:10'),
(13, 'APT-102', 6, 'Moynul Islam', 3, NULL, NULL, 'moynulislamshimanto24@gmail.com', 'Male', 'Fatullah', 0, '', '', '', '2025-06-25 19:10:18', NULL),
(16, 'APT-105', 5, 'Shimanto', 4, NULL, '23', 'moynulislamshimanto25@gmail.com', 'Male', 'Dapa Idrakpur, Fatullah.', NULL, '11', '11', '11', '2025-06-26 18:10:10', NULL),
(19, 'APT-104', 5, 'Moynul Islam', 3, NULL, '32', 'moynulislamshimanto24@gmail.com', 'Male', 'Fatullah', NULL, 'Cough', 'Monas', 'ECO', '2025-06-27 05:34:38', NULL),
(38, 'APT-107', 4, 'Moynul Islam', 3, NULL, '56', 'moynulislamshimanto24@gmail.com', 'Male', 'Fatullah', NULL, 'Pain\r\nFever\r\nDistrophy', 'Napa\r\nMonas', 'CVC\r\nRBC', '2025-06-27 08:16:48', NULL),
(42, 'APT-109', 4, 'Shimanto', 4, NULL, '45', 'moynulislamshimanto25@gmail.com', 'Male', 'Dapa Idrakpur, Fatullah.', NULL, 'Fever', 'fast 500mg', 'CVC', '2025-06-27 08:34:53', NULL),
(43, NULL, NULL, 'Moynul Islam ', 3, '01949854504', NULL, NULL, 'Male', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.\\r\\nFatullah', NULL, NULL, '', '', '2025-07-04 19:13:59', NULL),
(44, 'APT-111', 5, 'Moynul Islam', 3, NULL, '32', 'moynulislamshimanto24@gmail.com', 'Male', 'Fatullah', NULL, 'Fever', 'Bold', 'CVC', '2025-07-07 19:26:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `test_categories`
--

CREATE TABLE `test_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_categories`
--

INSERT INTO `test_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Hematology', 'Blood related tests', '2025-06-30 13:57:52'),
(2, 'Biochemistry', 'Chemical analysis of bodily fluids', '2025-06-30 13:57:52'),
(3, 'Microbiology', 'Tests for microorganisms', '2025-06-30 13:57:52'),
(4, 'Pathology', 'Tissue and cell analysis', '2025-06-30 13:57:52'),
(5, 'Radiology', 'Imaging tests', '2025-06-30 13:57:52');

-- --------------------------------------------------------

--
-- Table structure for table `test_orders`
--

CREATE TABLE `test_orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `test_date` varchar(100) DEFAULT NULL,
  `test_time` varchar(100) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('Pending','Paid','Failed') DEFAULT 'Pending',
  `status` enum('Pending','Sample Collected','Processing','Completed','Cancelled') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_orders`
--

INSERT INTO `test_orders` (`id`, `order_number`, `user_id`, `test_date`, `test_time`, `total_amount`, `payment_method`, `payment_status`, `status`, `created_at`, `updated_at`) VALUES
(1, 'TST-20250630-6862A6D', 3, '', '', 900.00, 'Wallet', 'Paid', 'Pending', '2025-06-30 15:01:46', NULL),
(2, 'TST-20250630-6862A6F', 3, '', '', 900.00, 'Wallet', 'Paid', 'Completed', '2025-06-30 15:02:10', '2025-07-01 18:54:05'),
(3, 'TST-20250630-6862A7D', 3, '', '', 900.00, 'Wallet', 'Paid', 'Completed', '2025-06-30 15:06:01', '2025-07-01 19:11:09'),
(5, 'TST-20250630-572E02', 3, '', '', 700.00, 'Wallet', 'Paid', 'Pending', '2025-06-30 17:39:31', NULL),
(6, 'TST-20250630-C45F6F', 3, '2025-07-01', '9:00 AM', 300.00, 'Wallet', 'Paid', 'Pending', '2025-06-30 17:53:53', NULL),
(7, 'TST-20250701-0D4CE8', 3, '2025-07-01', '9:20 AM', 600.00, 'Wallet', 'Paid', 'Completed', '2025-06-30 18:02:46', '2025-07-01 19:19:16'),
(8, 'TST-20250701-B8CDE4', 3, '2025-07-14', '9:00 AM', 200.00, 'Wallet', 'Paid', 'Completed', '2025-06-30 18:09:48', '2025-07-01 18:51:22'),
(9, 'TST-20250701-62323A', 3, '2025-07-01', '9:00 AM', 500.00, 'Wallet', 'Paid', 'Completed', '2025-06-30 18:16:13', '2025-07-01 18:41:04'),
(10, 'TST-20250701-015250', 3, '2025-07-01', '9:00 AM', 200.00, 'Wallet', 'Paid', 'Completed', '2025-06-30 18:19:08', '2025-07-01 18:28:00'),
(11, 'TST-20250701-D668A8', 3, '2025-07-01', '9:00 AM', 500.00, 'Wallet', 'Paid', 'Completed', '2025-06-30 18:21:35', '2025-07-01 17:21:20'),
(16, 'TST-20250701-E4E674', 3, '2025-07-02', '9:00 AM', 250.00, 'Wallet', 'Paid', 'Pending', '2025-07-01 03:28:22', NULL),
(17, 'TST-20250701-BA0993', 3, '2025-07-02', '9:20 AM', 500.00, 'Wallet', 'Paid', 'Pending', '2025-07-01 03:30:52', NULL),
(18, 'TST-20250701-323D2E', 3, '2025-07-02', '9:20 AM', 500.00, 'Wallet', 'Paid', 'Pending', '2025-07-01 03:34:29', NULL),
(19, 'TST-20250701-8B9979', 3, '2025-07-02', '9:00 AM', 1100.00, 'Wallet', 'Paid', 'Pending', '2025-07-01 16:25:10', NULL);

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
(246, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 18:30:39', NULL, 1),
(247, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 19:13:48', NULL, 1),
(248, 3, 'moynulislamshimanto24@gmail.com', 0x3132372e302e302e3100000000000000, '2025-06-24 19:28:20', NULL, 1),
(249, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 19:44:35', NULL, 1),
(250, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-24 19:45:18', NULL, 1),
(251, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-25 14:18:54', NULL, 1),
(252, 8, 'moynulislamshimanto29@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-25 17:39:12', NULL, 1),
(253, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-25 18:44:55', NULL, 1),
(254, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-25 20:10:23', NULL, 1),
(255, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-25 20:11:21', NULL, 1),
(256, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-26 17:33:44', NULL, 1),
(257, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-26 17:34:54', NULL, 1),
(258, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-26 17:55:28', NULL, 1),
(259, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-26 18:10:44', NULL, 1),
(260, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-27 04:20:20', NULL, 1),
(261, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-27 05:38:37', NULL, 1),
(262, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-27 08:28:22', NULL, 1),
(263, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-30 14:06:06', NULL, 1),
(264, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-30 15:15:39', NULL, 1),
(265, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-30 17:25:59', NULL, 0),
(266, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-06-30 17:26:06', NULL, 1),
(267, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-01 03:01:52', NULL, 1),
(268, 3, 'moynulislamshimanto24@gmail.com', 0x3132372e302e302e3100000000000000, '2025-07-01 16:24:30', NULL, 1),
(269, 3, 'moynulislamshimanto24@gmail.com', 0x3132372e302e302e3100000000000000, '2025-07-02 17:34:03', NULL, 1),
(270, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-03 03:08:40', NULL, 1),
(271, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-03 17:37:32', NULL, 1),
(272, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-04 16:38:39', NULL, 1),
(273, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-04 17:55:01', NULL, 1),
(274, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-04 19:06:59', NULL, 1),
(275, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-05 03:47:32', NULL, 1),
(276, 4, 'moynulislamshimanto25@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-05 07:36:45', NULL, 1),
(277, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-05 16:42:25', NULL, 1),
(278, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-06 04:51:12', NULL, 1),
(279, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-06 08:44:42', NULL, 1),
(280, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-06 15:19:22', NULL, 1),
(281, NULL, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-06 18:50:37', NULL, 0),
(282, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-06 18:50:44', NULL, 1),
(283, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 16:36:17', NULL, 1),
(284, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-07 17:14:14', NULL, 1),
(285, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-08 14:08:27', NULL, 1),
(286, 3, 'moynulislamshimanto24@gmail.com', 0x3a3a3100000000000000000000000000, '2025-07-09 18:15:21', NULL, 1);

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
(2, 'Amit Hasan', 'Rampura', 'Dhaka', 'Male', 'amitk@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2025-06-21 13:15:32', '2025-06-28 18:59:41', 0.00),
(3, 'Moynul Islam', 'Fatullah', 'Narayanganj', 'Male', 'moynulislamshimanto24@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', '2025-05-09 07:03:04', '2025-07-08 19:06:00', 35700.00),
(4, 'Shimanto', 'Dapa Idrakpur, Fatullah.', 'Narayanganj Sadar', 'Male', 'moynulislamshimanto25@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', '2025-06-09 04:13:17', '2025-07-05 07:54:46', 5800.00),
(7, 'Pollab', 'C/118, Bayejid Bostami Road, Dapa Idrakpur, Fatullah.', 'Narayanganj Sadar', 'Male', 'moynulislamshimanto27@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', '2025-06-21 18:24:44', '2025-06-25 18:03:35', 5200.00),
(8, 'Monira Akter', 'Dhaka', 'Dhaka', 'Female', 'moynulislamshimanto29@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', '2025-06-21 19:05:00', '2025-06-25 18:03:51', 300.00),
(9, 'Samim', 'Bayejid Bostami Road, Dapa Idrakpur, Fatullah.', 'Narayanganj Sadar', 'Male', 'moynulislamshimanto11@gmail.com', '577f2852d267e9283f9d87a8e4fdbf84', '2025-06-23 16:38:28', '2025-06-25 18:03:39', 9200.00);

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
(53, 9, 'Samim', 10000.00, 'bKash', '01949854504', 11111, 'TXN1750696801114', 'Approved', '2025-06-23 16:40:01'),
(54, 3, 'Moynul Islam', 20000.00, 'bKash', '01949854504', 12345, 'TXN1750862486860', 'Approved', '2025-06-25 14:41:26'),
(55, 3, 'Moynul Islam', 20000.00, 'bKash', '01949854504', 12345, 'TXN1750862632799', 'Requested', '2025-06-25 14:43:52'),
(56, 3, 'Moynul Islam', 20000.00, 'bKash', '01949854504', 12345, 'TXN1750862735149', 'Requested', '2025-06-25 14:45:35'),
(57, 3, 'Moynul Islam', 1002.00, 'bKash', '01949854504', 12345, 'TXN1751647294636', 'Approved', '2025-07-04 16:41:34'),
(58, 3, 'Moynul Islam', 50000.00, 'bKash', '01949854504', 12345, 'TXN1751733758416', 'Approved', '2025-07-05 16:42:38');

-- --------------------------------------------------------

--
-- Table structure for table `wards`
--

CREATE TABLE `wards` (
  `ward_id` int(11) NOT NULL,
  `ward_name` varchar(50) NOT NULL,
  `ward_type` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wards`
--

INSERT INTO `wards` (`ward_id`, `ward_name`, `ward_type`, `description`, `created_at`) VALUES
(1, 'General Ward A', 'General', 'General ward for patients', '2025-07-02 16:48:21'),
(3, 'Pediatric Ward', 'Pediatric', 'For children under 12 years', '2025-07-02 16:48:21'),
(7, 'ICU', 'ICU', 'Special Service', '2025-07-03 04:18:22'),
(9, 'NICU', 'NICU', 'For neonatal ', '2025-07-06 03:47:19');

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
  ADD PRIMARY KEY (`bed_id`),
  ADD KEY `ward_id` (`ward_id`);

--
-- Indexes for table `bed_assignments`
--
ALTER TABLE `bed_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bed_id` (`bed_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `diagnostic_tests`
--
ALTER TABLE `diagnostic_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `discharge_requests`
--
ALTER TABLE `discharge_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignment_id` (`assignment_id`);

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
-- Indexes for table `lab_technicians`
--
ALTER TABLE `lab_technicians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ordered_tests`
--
ALTER TABLE `ordered_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
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
-- Indexes for table `test_categories`
--
ALTER TABLE `test_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_orders`
--
ALTER TABLE `test_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`ward_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `bed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bed_assignments`
--
ALTER TABLE `bed_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `diagnostic_tests`
--
ALTER TABLE `diagnostic_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `discharge_requests`
--
ALTER TABLE `discharge_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `doctorslog`
--
ALTER TABLE `doctorslog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `doctorspecilization`
--
ALTER TABLE `doctorspecilization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
-- AUTO_INCREMENT for table `lab_technicians`
--
ALTER TABLE `lab_technicians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ordered_tests`
--
ALTER TABLE `ordered_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

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
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `test_categories`
--
ALTER TABLE `test_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `test_orders`
--
ALTER TABLE `test_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=287;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `wards`
--
ALTER TABLE `wards`
  MODIFY `ward_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `beds`
--
ALTER TABLE `beds`
  ADD CONSTRAINT `beds_ibfk_1` FOREIGN KEY (`ward_id`) REFERENCES `wards` (`ward_id`);

--
-- Constraints for table `diagnostic_tests`
--
ALTER TABLE `diagnostic_tests`
  ADD CONSTRAINT `diagnostic_tests_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `test_categories` (`id`);

--
-- Constraints for table `discharge_requests`
--
ALTER TABLE `discharge_requests`
  ADD CONSTRAINT `discharge_requests_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `bed_assignments` (`id`);

--
-- Constraints for table `ordered_tests`
--
ALTER TABLE `ordered_tests`
  ADD CONSTRAINT `ordered_tests_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `test_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ordered_tests_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `diagnostic_tests` (`id`);

--
-- Constraints for table `tblpatient`
--
ALTER TABLE `tblpatient`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `test_orders`
--
ALTER TABLE `test_orders`
  ADD CONSTRAINT `test_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
