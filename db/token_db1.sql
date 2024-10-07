-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2024 at 02:44 AM
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
-- Database: `token_db1`
--

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

CREATE TABLE `classrooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `building` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classrooms`
--

INSERT INTO `classrooms` (`id`, `room_number`, `capacity`, `building`) VALUES
(13, 'A101', 30, 'Education Building'),
(14, 'A102', 30, 'Education Building'),
(15, 'B101', 25, 'Language Arts Building'),
(16, 'B102', 25, 'Language Arts Building'),
(17, 'C101', 30, 'Math Building'),
(18, 'C102', 30, 'Math Building'),
(19, 'D101', 20, 'Values Education Center'),
(20, 'D102', 20, 'Values Education Center'),
(21, 'A201', 30, 'Education Building'),
(22, 'A202', 30, 'Education Building'),
(23, 'B201', 25, 'Language Arts Building'),
(24, 'B202', 25, 'Language Arts Building'),
(25, 'C201', 30, 'Math Building'),
(26, 'C202', 30, 'Math Building'),
(27, 'D201', 20, 'Values Education Center'),
(28, 'D202', 20, 'Values Education Center'),
(29, 'A301', 30, 'Education Building'),
(30, 'A302', 30, 'Education Building'),
(31, 'B301', 25, 'Language Arts Building'),
(32, 'B302', 25, 'Language Arts Building'),
(33, 'C301', 30, 'Math Building'),
(34, 'C302', 30, 'Math Building'),
(35, 'D301', 20, 'Values Education Center'),
(36, 'D302', 20, 'Values Education Center'),
(37, 'A401', 30, 'Education Building'),
(38, 'A402', 30, 'Education Building'),
(39, 'B401', 25, 'Language Arts Building'),
(40, 'B402', 25, 'Language Arts Building'),
(41, 'C401', 30, 'Math Building'),
(42, 'C402', 30, 'Math Building'),
(43, 'D401', 20, 'Values Education Center'),
(44, 'D402', 20, 'Values Education Center');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `department_id`) VALUES
(47, 'Bachelor of Secondary Education Major in Content Courses', 72),
(48, 'Bachelor of Secondary Education Major in Filipino', 72),
(49, 'Bachelor of Secondary Education Major in Mathematics', 72),
(50, 'Bachelor of Secondary Education Major in Values Education', 72),
(51, 'Certificate Program in Education for non Education Degrees', 72),
(52, 'Associate in Computer Secretarial 2 year course', 73),
(53, 'Bachelor of Science in Office Administration', 73),
(55, 'Bachelor of Science in Office Administration Major in Marketing Management', 73),
(56, 'Bachelor of Science in Office Administration Major in Financial Management', 73),
(57, 'Associate in Computer Technology 2 year course', 74),
(58, 'Bachelor of Science in Computer Engineering', 75);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `established` year(4) NOT NULL,
  `dean` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `faculty_count` int(11) NOT NULL,
  `student_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `established`, `dean`, `email`, `phone`, `location`, `faculty_count`, `student_count`) VALUES
(72, 'College of Education', '2024', 'Juan', 'bcccollegeofeducation@gmail.com', '09123456789', 'College of Education Building', 0, 0),
(73, 'College of Business', '2024', 'Juan', 'bcccollegeofbusiness@gmail.com', '09123456789', 'College of Business Building', 0, 0),
(74, 'College of Technology', '2024', 'Juan', 'bcccollegeoftechnology@gmail.com', '09123456789', 'College of Technology Building', 0, 0),
(75, 'College of Engineering', '2024', 'Juan', 'bcccollegeofengineering@gmail.com', '09123456789', 'College of Engineering Building', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `status` enum('pending','confirmed','rejected','New Student','Old Student','Regular','Irregular','Transferee') DEFAULT 'New Student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `school_year` varchar(10) DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `statusofenrollment` enum('pending','verifying','enrolled','rejected','incomplete') DEFAULT 'pending',
  `student_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`id`, `student_id`, `firstname`, `middlename`, `lastname`, `suffix`, `status`, `created_at`, `updated_at`, `school_year`, `semester`, `sex`, `dob`, `address`, `email`, `contact_no`, `statusofenrollment`, `student_number`) VALUES
(11, 43, 'eeeeeee', 'eeeeeeeeee', 'pedrajeta', 'N/A', 'Irregular', '2024-09-16 17:49:14', '2024-09-17 16:17:25', '1st Year', '1st Semester', 'Female', '2024-09-11', 'eeeeeeeeeeeeeeeeeee', 'pedrajetajr21@gmail.com', '09101110410', 'pending', 'SN-000043'),
(12, 44, 'eeeee', 'eeeeee', 'eeeeeee', 'Jr', 'Irregular', '2024-09-16 17:53:32', '2024-09-16 18:46:37', '1st Year', '1st Semester', 'Female', '2024-09-01', 'eeeeeeeeee', 'pedrajetajr22@gmail.com', '99999999999', 'pending', 'SN-000044');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_number` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `student_type` enum('regular','new student','irregular','summer') NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_number`, `firstname`, `middlename`, `lastname`, `suffix`, `student_type`, `sex`, `dob`, `email`, `contact_no`, `created_at`, `updated_at`, `address`, `status`) VALUES
(98, 'STU20240002', 'Jane', 'Roe', 'Doe', 'Jr.', 'new student', 'female', '2001-02-20', 'jane.roe@yahoo.com', '09176543210', '2024-10-05 15:15:25', '2024-10-05 15:15:25', '456 Elm St, Manila', 'Old'),
(99, 'STU20240003', 'Jim', 'Beam', 'Brown', NULL, 'irregular', 'male', '2000-03-25', 'jim.beam@gmail.com', '09181234567', '2024-10-05 15:15:25', '2024-10-05 15:15:25', '789 Pine St, Cebu', 'Regular'),
(100, 'STU20240004', 'Anna', 'Belle', 'Green', 'Sr.', 'summer', 'female', '1999-04-30', 'anna.belle@yahoo.com', '09182345678', '2024-10-05 15:15:25', '2024-10-05 15:15:25', '321 Oak St, Davao', 'Transferee'),
(101, 'STU20240005', 'Sam', 'Owen', 'White', NULL, 'regular', 'male', '1998-05-15', 'sam.owen@gmail.com', '09183456789', '2024-10-05 15:15:25', '2024-10-05 15:15:25', '654 Maple St, Iloilo', 'New Student'),
(102, 'STU20240006', 'Mike', 'Tyson', 'Brown', NULL, 'new student', 'male', '2000-06-20', 'mike.tyson@yahoo.com', '09184567890', '2024-10-05 15:15:25', '2024-10-05 15:15:25', '987 Cedar St, Baguio', 'Old'),
(103, 'STU20240007', 'Ella', 'Fitzgerald', 'Jones', NULL, 'irregular', 'female', '1999-07-10', 'ella.fitzgerald@gmail.com', '09185678901', '2024-10-05 15:15:25', '2024-10-05 15:15:25', '135 Walnut St, Cagayan de Oro', 'Regular'),
(104, 'STU20240008', 'Tom', 'Hanks', 'Taylor', NULL, 'summer', 'male', '1998-08-30', 'tom.hanks@yahoo.com', '09186789012', '2024-10-05 15:15:25', '2024-10-05 15:15:25', '246 Chestnut St, Makati', 'Transferee'),
(105, 'STU20240009', 'Sophia', 'Loren', 'García', 'Jr.', 'regular', 'female', '2000-09-15', 'sophia.loren@gmail.com', '09187890123', '2024-10-05 15:15:25', '2024-10-05 15:15:25', '357 Spruce St, Pasig', 'New Student'),
(106, 'STU20240010', 'Chris', 'Evans', 'Smith', 'Sr.', 'new student', 'male', '1999-10-22', 'chris.evans@yahoo.com', '09188901234', '2024-10-05 15:15:25', '2024-10-05 15:15:25', '468 Fir St, Quezon City', 'Old'),
(107, 'STU20240011', 'pedrajeta', 'Leopardas', 'Enrico', 'Jr', 'regular', '', '2024-10-07', 'pedrajetajr21@gmail.com', '09101110410', '2024-10-06 02:38:00', '2024-10-06 04:18:41', 'Philec  Taytay, Rizal', 'Regular');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment_payments`
--

CREATE TABLE `enrollment_payments` (
  `id` int(11) NOT NULL,
  `units_price` decimal(10,2) NOT NULL,
  `miscellaneous_fee` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `months_of_payments` int(11) DEFAULT NULL,
  `student_number` varchar(20) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment_payments`
--

INSERT INTO `enrollment_payments` (`id`, `units_price`, `miscellaneous_fee`, `created_at`, `updated_at`, `months_of_payments`, `student_number`, `transaction_id`) VALUES
(22, 156.00, 5665.00, '2024-10-05 07:12:11', '2024-10-05 07:12:11', 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_number` varchar(50) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `grade` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `term` varchar(50) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `prelim` decimal(5,2) DEFAULT NULL,
  `midterm` decimal(5,2) DEFAULT NULL,
  `finals` decimal(5,2) DEFAULT NULL,
  `total_grade` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `student_number`, `subject_id`, `grade`, `created_at`, `term`, `student_id`, `prelim`, `midterm`, `finals`, `total_grade`) VALUES
(89, 'STU20240011', 48, 0, '2024-10-06 02:43:23', NULL, NULL, 3.00, 3.00, 3.00, 3),
(90, 'STU20240011', 49, 0, '2024-10-06 03:25:10', NULL, NULL, 3.00, 3.00, 3.00, 3);

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `course_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `first_name`, `middle_name`, `suffix`, `last_name`, `email`, `department_id`, `created_at`, `updated_at`, `course_id`, `section_id`) VALUES
(70, 'Lorena', 'Leopardas', '', 'Pedrajeta', 'lorenapedrajeta@gmail.com', 72, '2024-10-06 02:34:14', '2024-10-06 02:34:14', 47, 45),
(71, 'Enrico', 'Leopardas', '', 'Pedrajeta', 'markanthonypedrajeta24@gmail.com', 72, '2024-10-06 02:35:12', '2024-10-06 02:35:12', 47, 45);

-- --------------------------------------------------------

--
-- Table structure for table `instructor_subjects`
--

CREATE TABLE `instructor_subjects` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_subjects`
--

INSERT INTO `instructor_subjects` (`id`, `instructor_id`, `subject_id`, `semester_id`) VALUES
(55, 70, 48, 1),
(56, 70, 49, 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`, `expires_at`) VALUES
(26, '', 'e2769c410e600b4e8a55edb0990556dfbd1001b9', '2024-08-21 08:23:54', '2024-08-21 09:23:54'),
(34, 'Pedrajetajr21@gmail.com', '4c2b9eb6f4a50ba1881e905e3bf24d93b3a4459d', '2024-08-21 08:25:16', '2024-08-21 09:25:16'),
(35, 'Pedrajetajr21@gmail.com', '42040e3563cc297bb6667113a08bf0d68f9e06d0', '2024-08-21 08:26:15', '2024-08-21 09:26:15'),
(37, 'Pedrajetajr21@gmail.com', 'e18cdb3a271d31d454f577a5858638965005703d', '2024-08-21 08:26:21', '2024-08-21 09:26:21'),
(38, 'Pedrajetajr21@gmail.com', 'f7377264c5c15527e28d90e6bd3cf230de261168', '2024-08-21 08:27:06', '2024-08-21 09:27:06'),
(39, 'Pedrajetajr21@gmail.com', '501cddfbe693981a498b214b09b0f6f5f7560828', '2024-08-21 08:27:07', '2024-08-21 09:27:07'),
(43, 'Pedrajetajr21@gmail.com', '651e1b8a05fd466fc221bcae5adc68d329688bde', '2024-08-21 08:28:28', '2024-08-21 09:28:28'),
(44, 'Pedrajetajr21@gmail.com', 'f9f657bbcef6960f32845308a482d2246005e9b9', '2024-08-21 08:29:48', '2024-08-21 09:29:48'),
(45, '', '7c2669dad8109a79967244074708916445921995', '2024-08-21 08:30:00', '2024-08-21 09:30:00'),
(46, 'Pedrajetajr21@gmail.com', '5e94166abf825bac48e278a44833fd6789a4019f', '2024-08-21 08:30:04', '2024-08-21 09:30:04'),
(47, '', 'c1fe8d870dd2f2a72bfe9aef20457e2ad183a8f7', '2024-08-21 08:32:22', '2024-08-21 09:32:22'),
(48, 'Pedrajetajr21@gmail.com', 'b0909952376df423f40aaa56481af86f87ee40ff', '2024-08-21 08:32:25', '2024-08-21 09:32:25'),
(49, 'Pedrajetajr21@gmail.com', '5d12bd64bd0386de790330ad38837b14542cc9ca', '2024-08-21 08:34:02', '2024-08-21 09:34:02'),
(50, 'Pedrajetajr21@gmail.com', '990f82d94583af1507c099fc92d6281c93d05b10', '2024-08-21 19:31:56', '2024-08-21 20:31:56'),
(51, 'Pedrajetajr21@gmail.com', '43f0c4179e430fd22f9328b03ec28848f7008c2c', '2024-08-21 20:24:51', '2024-08-21 21:24:51'),
(54, 'Pedrajetajr21@gmail.com', 'b7da8234c590579dd84d38616cc7c867be22074f', '2024-08-21 21:15:59', '2024-08-21 22:15:59'),
(55, '', 'adadfda1e21cf72f68c9a9d5f830d49a4d6f3c3a', '2024-08-23 14:48:45', '2024-08-23 15:48:45'),
(56, 'Pedrajetajr21@gmail.com', '28bba930a4ef3c62ae35b313aa84f8cafbf772c2', '2024-08-23 16:07:32', '2024-08-23 17:07:32'),
(57, 'Pedrajetajr21@gmail.com', 'a52756de928958129ceda3c1be9cc5559d40c9e2', '2024-08-23 16:17:04', '2024-08-23 17:17:04'),
(58, 'Pedrajetajr21@gmail.com', '42baf6db9344f8bef238f01c75f86160c8ca5028', '2024-10-03 20:10:51', '2024-10-03 21:10:51'),
(59, 'Pedrajetajr21@gmail.com', 'dd3089a1f3b90e79e6e8535dd36626a5a413fce4', '2024-10-03 20:12:19', '2024-10-03 21:12:19'),
(60, 'markanthonypedrajeta24@gmail.com', '136b0f51db8d747e32948a8e2341358e507c30cc', '2024-10-03 20:33:10', '2024-10-03 21:33:10'),
(61, 'markanthonypedrajeta24@gmail.com', '8c0765887fe7119adf262cb076c34bc2c3d64eca', '2024-10-03 20:33:38', '2024-10-03 21:33:38'),
(62, 'markanthonypedrajeta24@gmail.com', 'c625bf6d21705a75131a43bd174b9b7428627a90', '2024-10-03 20:34:57', '2024-10-03 21:34:57'),
(63, 'Pedrajetajr21@gmail.com', '14e342e1b58c84754d9d4fc9737615bb4a877eea', '2024-10-03 20:35:42', '2024-10-03 21:35:42'),
(65, 'pedrajeta.enrico.stem202@gmail.com', '4d71fb599724aea7c36d5fe84ebe5a446990870c', '2024-10-07 08:30:49', '2024-10-07 09:30:49'),
(66, 'pedrajetajr21@gmail.com', '7e4db110ab4b0c1a5c7ccfd90ea0a19d671c6741', '2024-10-07 08:31:10', '2024-10-07 09:31:10');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `student_number` varchar(50) NOT NULL,
  `number_of_units` int(11) NOT NULL,
  `amount_per_unit` decimal(10,2) NOT NULL,
  `miscellaneous_fee` decimal(10,2) NOT NULL,
  `total_payment` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `remaining_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','installment') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transaction_id` varchar(255) DEFAULT NULL,
  `research_fee` int(11) DEFAULT NULL,
  `transfer_fee` int(11) DEFAULT NULL,
  `overload_fee` int(11) DEFAULT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `number_of_months_payment` int(11) DEFAULT NULL,
  `monthly_payment` decimal(10,2) DEFAULT NULL,
  `next_payment_due_date` date DEFAULT NULL,
  `installment_down_payment` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `student_number`, `number_of_units`, `amount_per_unit`, `miscellaneous_fee`, `total_payment`, `paid_amount`, `remaining_balance`, `payment_method`, `created_at`, `updated_at`, `transaction_id`, `research_fee`, `transfer_fee`, `overload_fee`, `payment_status`, `number_of_months_payment`, `monthly_payment`, `next_payment_due_date`, `installment_down_payment`) VALUES
(94, 'STU20240011', 0, 0.00, 0.00, 0.00, 0.00, 0.00, 'cash', '2024-10-07 00:12:35', '2024-10-07 00:12:35', '33J44946LX647573U', NULL, NULL, NULL, 'pending', 6, 1413.80, '2024-12-07', 0.00),
(99, 'STU20240011', 0, 0.00, 0.00, 0.00, 0.00, 0.00, 'cash', '2024-10-07 00:20:05', '2024-10-07 00:20:05', '5H657328D0198250T', NULL, NULL, NULL, 'pending', 6, 1000.00, '2025-01-07', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `day_of_week` varchar(10) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `room` varchar(50) NOT NULL,
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `subject_id`, `day_of_week`, `start_time`, `end_time`, `room`, `section_id`) VALUES
(32, 48, 'Monday', '22:45:00', '14:42:00', '13', 45),
(33, 48, 'Monday', '13:43:00', '13:43:00', '14', 45),
(34, 48, 'Monday', '08:00:00', '09:30:00', '13', 45),
(35, 49, 'Tuesday', '09:30:00', '11:00:00', '14', 46),
(36, 50, 'Wednesday', '10:00:00', '11:30:00', '15', 47),
(37, 51, 'Thursday', '11:00:00', '12:30:00', '16', 48),
(38, 52, 'Friday', '13:00:00', '14:30:00', '17', 49),
(39, 53, 'Saturday', '14:30:00', '16:00:00', '18', 50),
(40, 54, 'Sunday', '08:00:00', '09:30:00', '19', 51),
(41, 55, 'Monday', '09:00:00', '10:30:00', '20', 52),
(42, 56, 'Tuesday', '10:30:00', '12:00:00', '21', 53),
(43, 57, 'Wednesday', '13:00:00', '14:30:00', '22', 54),
(44, 58, 'Thursday', '14:30:00', '16:00:00', '23', 55),
(45, 59, 'Friday', '08:00:00', '09:30:00', '24', 56),
(46, 60, 'Saturday', '10:00:00', '11:30:00', '25', 57),
(47, 61, 'Sunday', '12:00:00', '13:30:00', '26', 58),
(48, 62, 'Monday', '14:00:00', '15:30:00', '27', 59),
(49, 63, 'Tuesday', '08:00:00', '09:30:00', '28', 60),
(50, 64, 'Wednesday', '09:30:00', '11:00:00', '29', 61),
(51, 65, 'Thursday', '11:00:00', '12:30:00', '30', 62),
(52, 66, 'Friday', '13:00:00', '14:30:00', '31', 63),
(53, 67, 'Saturday', '14:30:00', '16:00:00', '32', 64),
(54, 68, 'Sunday', '08:00:00', '09:30:00', '33', 65),
(55, 69, 'Monday', '09:00:00', '10:30:00', '34', 66),
(56, 70, 'Tuesday', '10:30:00', '12:00:00', '35', 67),
(57, 71, 'Wednesday', '13:00:00', '14:30:00', '36', 68),
(58, 72, 'Thursday', '14:30:00', '16:00:00', '37', 69),
(59, 73, 'Friday', '08:00:00', '09:30:00', '38', 70),
(60, 74, 'Saturday', '10:00:00', '11:30:00', '39', 71),
(61, 75, 'Sunday', '12:00:00', '13:30:00', '40', 72),
(62, 76, 'Monday', '14:00:00', '15:30:00', '41', 73),
(63, 77, 'Tuesday', '08:00:00', '09:30:00', '42', 74),
(64, 78, 'Wednesday', '09:30:00', '11:00:00', '43', 75),
(65, 79, 'Thursday', '11:00:00', '12:30:00', '44', 76),
(66, 80, 'Friday', '13:00:00', '14:30:00', '13', 77),
(67, 81, 'Saturday', '14:30:00', '16:00:00', '14', 78),
(68, 82, 'Sunday', '08:00:00', '09:30:00', '15', 79),
(69, 83, 'Monday', '09:00:00', '10:30:00', '16', 80),
(70, 84, 'Tuesday', '10:30:00', '12:00:00', '17', 81),
(71, 85, 'Wednesday', '13:00:00', '14:30:00', '18', 82),
(72, 86, 'Thursday', '14:30:00', '16:00:00', '19', 83),
(73, 87, 'Friday', '08:00:00', '09:30:00', '20', 84),
(74, 88, 'Saturday', '10:00:00', '11:30:00', '21', 85),
(75, 89, 'Sunday', '12:00:00', '13:30:00', '22', 86),
(76, 90, 'Monday', '14:00:00', '15:30:00', '23', 87),
(77, 91, 'Tuesday', '08:00:00', '09:30:00', '24', 88),
(78, 92, 'Wednesday', '09:30:00', '11:00:00', '25', 89),
(79, 93, 'Thursday', '11:00:00', '12:30:00', '26', 90),
(80, 94, 'Friday', '13:00:00', '14:30:00', '27', 91),
(81, 95, 'Saturday', '14:30:00', '16:00:00', '28', 92),
(82, 96, 'Sunday', '08:00:00', '09:30:00', '29', 93),
(83, 97, 'Monday', '09:00:00', '10:30:00', '30', 94),
(84, 98, 'Tuesday', '10:30:00', '12:00:00', '31', 95),
(85, 99, 'Wednesday', '13:00:00', '14:30:00', '32', 96),
(86, 100, 'Thursday', '14:30:00', '16:00:00', '33', 97),
(87, 101, 'Friday', '08:00:00', '09:30:00', '34', 98),
(88, 102, 'Saturday', '10:00:00', '11:30:00', '35', 99),
(89, 103, 'Sunday', '12:00:00', '13:30:00', '36', 100),
(90, 104, 'Monday', '14:00:00', '15:30:00', '37', 101),
(91, 105, 'Tuesday', '08:00:00', '09:30:00', '38', 102),
(92, 106, 'Wednesday', '09:30:00', '11:00:00', '39', 103),
(93, 107, 'Thursday', '11:00:00', '12:30:00', '40', 104),
(94, 108, 'Friday', '13:00:00', '14:30:00', '41', 105),
(95, 109, 'Saturday', '14:30:00', '16:00:00', '42', 106),
(96, 110, 'Sunday', '08:00:00', '09:30:00', '43', 107),
(97, 111, 'Monday', '09:00:00', '10:30:00', '44', 108);

-- --------------------------------------------------------

--
-- Table structure for table `school_years`
--

CREATE TABLE `school_years` (
  `id` int(11) NOT NULL,
  `year` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_years`
--

INSERT INTO `school_years` (`id`, `year`) VALUES
(1, '2023-2024'),
(2, '2024-2025');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `course_id`) VALUES
(45, '1BSEMCC1', 47),
(46, '1BSEMCC2', 47),
(47, '1BSEMF1', 48),
(48, '1BSEMF2', 48),
(49, '1BSEMM1', 49),
(50, '1BSEMM2', 49),
(51, '1BSEMV1', 50),
(52, '1BSEMV2', 50),
(53, '2BSEMCC1', 47),
(54, '2BSEMCC2', 47),
(55, '2BSEMF1', 48),
(56, '2BSEMF2', 48),
(57, '2BSEMM1', 49),
(58, '2BSEMM2', 49),
(59, '2BSEMV1', 50),
(60, '2BSEMV2', 50),
(61, '3BSEMCC1', 47),
(62, '3BSEMCC2', 47),
(64, '3BSEMF1', 48),
(65, '3BSEMF2', 48),
(66, '3BSEMM1', 49),
(67, '3BSEMM2', 49),
(68, '3BSEMV1', 50),
(69, '3BSEMV2', 50),
(70, '4BSEMCC1', 47),
(71, '4BSEMCC2', 47),
(72, '4BSEMF1', 48),
(73, '4BSEMF2', 48),
(74, '4BSEMM1', 49),
(75, '4BSEMM2', 49),
(76, '4BSEMV1', 50),
(77, '4BSEMV2', 50),
(78, '1BSEMCN1', 51),
(79, '1BSEMCN2', 51),
(80, '1ACSC1', 52),
(81, '1ACSC2', 52),
(82, '2BSOA1', 53),
(83, '2BSOA2', 53),
(84, '3BSOAMM1', 55),
(85, '3BSOAMM2', 55),
(86, '3BSOAFM1', 56),
(87, '3BSOAFM2', 56),
(88, '1ACTC1', 57),
(89, '1ACTC2', 57),
(90, '4BSCE1', 58),
(91, '4BSCE2', 58),
(92, '1BSCE1', 58),
(93, '1BSCE2', 58),
(94, '2BSCE1', 58),
(95, '2BSCE2', 58),
(96, '3BSCE1', 58),
(97, '3BSCE2', 58),
(98, '2ACTC1', 57),
(99, '2ACTC2', 57),
(100, '1BSOAFM1', 56),
(101, '1BSOAFM2', 56),
(102, '2BSOAFM1', 56),
(103, '2BSOAFM2', 56),
(104, '4BSOAFM1', 56),
(105, '4BSOAFM2', 56),
(106, '1BSOAMM1', 55),
(107, '1BSOAMM2', 55),
(109, '2BSOAMM1', 55),
(110, '2BSOAMM2', 55),
(112, '4BSOAMM1', 55),
(113, '4BSOAMM2', 55),
(114, '1BSOA1', 53),
(115, '1BSOA2', 53),
(116, '3BSOA1', 53),
(117, '3BSOA2', 53),
(118, '4BSOA1', 53),
(119, '4BSOA2', 53),
(120, '2ACSC1', 52),
(121, '2ACSC2', 52);

-- --------------------------------------------------------

--
-- Table structure for table `selected_courses`
--

CREATE TABLE `selected_courses` (
  `id` int(11) NOT NULL,
  `section_name` varchar(255) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` int(11) NOT NULL,
  `semester_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `semester_name`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, '1st Semester', '2024-09-13', '2025-09-13', '2024-09-17 23:50:33', '2024-09-17 23:50:33'),
(2, '2nd Semester', '2024-09-19', '2024-09-12', '2024-09-18 07:16:33', '2024-09-18 07:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `sex_options`
--

CREATE TABLE `sex_options` (
  `id` int(11) NOT NULL,
  `sex_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sex_options`
--

INSERT INTO `sex_options` (`id`, `sex_name`) VALUES
(2, 'Femaleeee'),
(1, 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `status_options`
--

CREATE TABLE `status_options` (
  `id` int(11) NOT NULL,
  `status_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_options`
--

INSERT INTO `status_options` (`id`, `status_name`) VALUES
(1, 'New Student'),
(3, 'Old'),
(5, 'Regular'),
(4, 'Transferee');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_number` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `student_type` enum('regular','new student','irregular','summer') NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `number_of_units` int(11) DEFAULT NULL,
  `amount_per_unit` decimal(10,2) DEFAULT NULL,
  `miscellaneous_fee` decimal(10,2) DEFAULT NULL,
  `total_payment` decimal(10,2) DEFAULT NULL,
  `payment_method` enum('cash','installment') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `section_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `semester` int(11) NOT NULL,
  `school_year` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_number`, `firstname`, `middlename`, `lastname`, `suffix`, `student_type`, `sex`, `dob`, `email`, `contact_no`, `address`, `status`, `number_of_units`, `amount_per_unit`, `miscellaneous_fee`, `total_payment`, `payment_method`, `created_at`, `updated_at`, `section_id`, `department_id`, `course_id`, `subject_id`, `schedule_id`, `semester`, `school_year`) VALUES
(12, 'STU20240001', 'John', 'Doe', 'Smith', '', 'regular', '', '2000-01-15', 'pedrajetajr21@gmail.com', '09171234567', '123 Main St, Quezon City', 'New Student', 6, 156.00, 5665.00, 6601.00, 'cash', '2024-10-05 17:19:28', '2024-10-05 17:19:28', 45, 72, 47, 48, 32, 1, '2024-2025');

-- --------------------------------------------------------

--
-- Table structure for table `student_details`
--

CREATE TABLE `student_details` (
  `id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `semester` int(11) NOT NULL,
  `school_year` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_details`
--

INSERT INTO `student_details` (`id`, `section_id`, `department_id`, `course_id`, `subject_id`, `schedule_id`, `semester`, `school_year`) VALUES
(1, 45, 72, 47, 48, 32, 1, '2024-2025'),
(2, 45, 72, 47, 48, 32, 1, '2024-2025');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `units` int(11) DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `school_year_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `code`, `title`, `section_id`, `units`, `semester_id`, `school_year_id`) VALUES
(48, 'BSE101', 'Introduction to Education', 45, 3, 1, 2),
(49, 'BSE102', 'Child Development', 45, 3, 1, 2),
(50, 'BSE101', 'Introduction to Education', 46, 3, 1, 1),
(51, 'BSE102', 'Child Development', 46, 3, 1, 2),
(52, 'FIL101', 'Filipino Language and Literature', 47, 3, 1, 2),
(53, 'FIL102', 'Teaching Strategies in Filipino', 47, 3, 1, 2),
(54, 'FIL101', 'Filipino Language and Literature', 48, 3, 1, 2),
(55, 'FIL102', 'Teaching Strategies in Filipino', 48, 3, 1, 2),
(56, 'MAT101', 'Fundamentals of Mathematics', 49, 3, 1, 2),
(57, 'MAT102', 'Mathematics Teaching Methodologies', 50, 3, 1, 2),
(58, 'MAT101', 'Fundamentals of Mathematics', 50, 3, 1, 2),
(59, 'MAT102', 'Mathematics Teaching Methodologies', 50, 3, 1, 2),
(60, 'VAL101', 'Foundations of Values Education', 51, 3, 1, 2),
(61, 'VAL102', 'Ethics and Moral Development', 51, 3, 1, 2),
(62, 'VAL101', 'Foundations of Values Education', 52, 3, 1, 2),
(63, 'VAL102', 'Ethics and Moral Development', 52, 3, 1, 2),
(64, 'BSE201', 'Educational Psychology', 45, 3, 2, 2),
(65, 'BSE202', 'Classroom Management', 45, 3, 2, 2),
(66, 'BSE201', 'Educational Psychology', 46, 3, 2, 2),
(67, 'BSE202', 'Classroom Management', 46, 3, 2, 1),
(68, 'FIL201', 'Introduction to Philippine Literature', 47, 3, 2, 1),
(69, 'FIL202', 'Methods of Teaching Filipino', 47, 3, 2, 2),
(70, 'FIL201', 'Introduction to Philippine Literature', 48, 3, 2, 2),
(71, 'FIL202', 'Methods of Teaching Filipino', 48, 3, 2, 2),
(72, 'MAT201', 'Algebra I', 49, 3, 2, 2),
(73, 'MAT202', 'Geometry', 49, 3, 2, 2),
(74, 'MAT201', 'Algebra I', 50, 3, 2, 2),
(75, 'MAT202', 'Geometry', 50, 3, 2, 2),
(76, 'VAL201', 'Social Foundations of Education', 51, 3, 2, 2),
(77, 'VAL202', 'Values in the Curriculum', 51, 3, 2, 2),
(78, 'VAL201', 'Social Foundations of Education', 52, 3, 2, 2),
(79, 'VAL202', 'Values in the Curriculum', 52, 3, 2, 2),
(80, 'BSE301', 'Curriculum Development', 53, 3, 1, 2),
(81, 'BSE302', 'Assessment and Evaluation in Education', 53, 3, 1, 2),
(82, 'BSE301', 'Curriculum Development', 54, 3, 1, 2),
(83, 'BSE302', 'Assessment and Evaluation in Education', 54, 3, 1, 2),
(84, 'FIL301', 'Advanced Filipino Language Studies', 55, 3, 1, 2),
(85, 'FIL302', 'Filipino Literature and Culture', 55, 3, 1, 2),
(86, 'FIL301', 'Advanced Filipino Language Studies', 56, 3, 1, 2),
(87, 'FIL302', 'Filipino Literature and Culture', 56, 3, 1, 2),
(88, 'MAT301', 'Algebra II', 57, 3, 1, 2),
(89, 'MAT302', 'Trigonometry', 57, 3, 1, 2),
(90, 'MAT301', 'Algebra II', 58, 3, 1, 2),
(91, 'MAT302', 'Trigonometry', 58, 3, 1, 2),
(92, 'VAL301', 'Social Justice Education', 59, 3, 1, 2),
(93, 'VAL302', 'Character Education', 59, 3, 1, 2),
(94, 'VAL301', 'Social Justice Education', 60, 3, 1, 2),
(95, 'VAL302', 'Character Education', 60, 3, 1, 2),
(96, 'BSE401', 'Educational Technology', 53, 3, 2, 2),
(97, 'BSE402', 'Research in Education', 45, 3, 2, 2),
(98, 'BSE401', 'Educational Technology', 54, 3, 2, 2),
(99, 'BSE402', 'Research in Education', 54, 3, 2, 2),
(100, 'FIL401', 'Modern Methods of Teaching Filipino', 55, 3, 2, 2),
(101, 'FIL402', 'Philippine Cultural Heritage', 55, 3, 2, 2),
(102, 'FIL401', 'Modern Methods of Teaching Filipino', 56, 3, 2, 2),
(103, 'FIL402', 'Philippine Cultural Heritage', 56, 3, 2, 2),
(104, 'MAT401', 'Statistics', 57, 3, 2, 2),
(105, 'MAT402', 'Probability', 57, 3, 2, 2),
(106, 'MAT401', 'Statistics', 58, 3, 2, 2),
(107, 'MAT402', 'Probability', 58, 3, 2, 2),
(108, 'VAL401', 'Values and Ethics in Education', 59, 3, 2, 2),
(109, 'VAL402', 'Peace Education', 59, 3, 2, 2),
(110, 'VAL401', 'Values and Ethics in Education', 60, 3, 2, 2),
(111, 'VAL402', 'Peace Education', 60, 3, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `subject_enrollments`
--

CREATE TABLE `subject_enrollments` (
  `id` int(11) NOT NULL,
  `student_number` varchar(15) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `semester` int(11) NOT NULL,
  `school_year` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_enrollments`
--

INSERT INTO `subject_enrollments` (`id`, `student_number`, `section_id`, `department_id`, `course_id`, `subject_id`, `schedule_id`, `semester`, `school_year`) VALUES
(265, 'STU20240011', 45, 72, 47, 48, 32, 1, '2024-2025'),
(266, 'STU20240011', 45, 72, 47, 49, 35, 1, '2024-2025'),
(267, 'STU20240011', 46, 72, 47, 51, 37, 1, '2024-2025');

-- --------------------------------------------------------

--
-- Table structure for table `subject_ojt_fees`
--

CREATE TABLE `subject_ojt_fees` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `ojt_fee` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_research_fees`
--

CREATE TABLE `subject_research_fees` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `research_fee` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suffixes`
--

CREATE TABLE `suffixes` (
  `id` int(11) NOT NULL,
  `suffix_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suffixes`
--

INSERT INTO `suffixes` (`id`, `suffix_name`) VALUES
(13, 'enrico'),
(1, 'Jr'),
(8, 'Sr');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'inactive',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `failed_attempts` int(11) DEFAULT 0,
  `account_locked` tinyint(1) DEFAULT 0,
  `lock_time` datetime DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`, `email_confirmed`, `failed_attempts`, `account_locked`, `lock_time`, `profile_photo`) VALUES
(43, 'pedrajetajr21@gmail.com', '$2y$10$LA3cicwE99t5Cj247jtPKu000osoAdK0ZsYGJ0y0Z4/kyU2RsIujK', 'student', 'active', '2024-09-13 20:34:24', '2024-10-06 10:36:43', 1, 0, 0, NULL, 'pedrajetajr21_1727802002_91QnZwnER3L.png'),
(44, 'pedrajetajr22@gmail.com', '$2y$10$E7SBHI9hSDmGDwObPuJfGurF42bwtEjS2mmIYbnqefJ0pqu/KI1Uu', 'student', 'active', '2024-09-16 00:46:34', '2024-10-01 17:37:17', 1, 0, 0, NULL, 'pedrajetajr22_1727706055_12.jpg'),
(45, 'lorenapedrajeta@gmail.com', '$2y$10$fTdZE6mlKKXqpw0JSuYOYOGQHEtxxorDo.RhMQvqcD2bq8prc3oSa', 'college_department', 'active', '2024-10-01 22:27:22', '2024-10-06 01:30:53', 1, 0, 0, NULL, NULL),
(46, 'markanthonypedrajeta24@gmail.com', '$2y$10$F9ABXUEOP0wfc4OzWZXvleFoVbArtWQN5MsAkPK4DkZ.ZfRiqzlBq', 'college_department', 'active', '2024-10-02 00:00:21', '2024-10-02 00:00:42', 1, 0, 0, NULL, NULL),
(52, 'pedrajeta.enrico.stem202@gmail.com', '$2y$10$WPZUGMdlr1ax4lGe39zQN.YkXOMcSuZNOypdohCwE23XE/1DRtpJm', 'college_department', 'active', '2024-10-07 08:27:55', '2024-10-07 08:34:56', 1, 0, 0, NULL, NULL),
(53, 'pedrajeta.enrico.stem2021@gmail.com', '$2y$10$9nYvZ27RcBFZ3AdDFQihweogrPq61Z3OXtXHzwQbOse3WOdcmWXHa', 'college_department', 'inactive', '2024-10-07 08:35:41', '2024-10-07 08:35:41', 0, 0, 0, NULL, NULL),
(54, 'pedrajeta.enrico.stem20211@gmail.com', '$2y$10$oACS.AvEoXa2HnxsFnjKsuWwXzON1/wIlNoTvmuNF5MScXFmxruES', 'college_department', 'inactive', '2024-10-07 08:36:13', '2024-10-07 08:36:13', 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users1`
--

CREATE TABLE `users1` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users1`
--

INSERT INTO `users1` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'enricothe3rd', 'markanthonypedrajeta24@gmail.com', '$2y$10$uBoE1Iu0U8O20vpWUAtiC.oWgFjZ1WhbnSn7d3FlIeW3a9NqVTlZm', '2024-09-24 16:19:54');

-- --------------------------------------------------------

--
-- Table structure for table `user_registration`
--

CREATE TABLE `user_registration` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_registration`
--

INSERT INTO `user_registration` (`id`, `user_id`, `token`, `type`, `created_at`) VALUES
(53, 53, '511757d033cb8d677d5a84b77fa57eaca3b1927b', 'registration', '2024-10-07 00:35:41'),
(54, 54, 'c5062a5dda8fb0e40fb4117ef0e8d598bf561ca3', 'registration', '2024-10-07 00:36:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_department` (`department_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_number` (`student_number`),
  ADD KEY `fk_student_id` (`student_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_number` (`student_number`);

--
-- Indexes for table `enrollment_payments`
--
ALTER TABLE `enrollment_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `instructor_subjects`
--
ALTER TABLE `instructor_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `school_years`
--
ALTER TABLE `school_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `year` (`year`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_section_course` (`course_id`);

--
-- Indexes for table `selected_courses`
--
ALTER TABLE `selected_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sex_options`
--
ALTER TABLE `sex_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sex_name` (`sex_name`);

--
-- Indexes for table `status_options`
--
ALTER TABLE `status_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `status_name` (`status_name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_number` (`student_number`);

--
-- Indexes for table `student_details`
--
ALTER TABLE `student_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_enrollments`
--
ALTER TABLE `subject_enrollments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_ojt_fees`
--
ALTER TABLE `subject_ojt_fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subject_research_fees`
--
ALTER TABLE `subject_research_fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `suffixes`
--
ALTER TABLE `suffixes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suffix_name` (`suffix_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users1`
--
ALTER TABLE `users1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_registration`
--
ALTER TABLE `user_registration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `enrollment_payments`
--
ALTER TABLE `enrollment_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `instructor_subjects`
--
ALTER TABLE `instructor_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `school_years`
--
ALTER TABLE `school_years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `selected_courses`
--
ALTER TABLE `selected_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sex_options`
--
ALTER TABLE `sex_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `status_options`
--
ALTER TABLE `status_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `student_details`
--
ALTER TABLE `student_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `subject_enrollments`
--
ALTER TABLE `subject_enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT for table `subject_ojt_fees`
--
ALTER TABLE `subject_ojt_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subject_research_fees`
--
ALTER TABLE `subject_research_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `suffixes`
--
ALTER TABLE `suffixes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users1`
--
ALTER TABLE `users1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_registration`
--
ALTER TABLE `user_registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `fk_student_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `instructor_subjects`
--
ALTER TABLE `instructor_subjects`
  ADD CONSTRAINT `instructor_subjects_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`),
  ADD CONSTRAINT `instructor_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `fk_section_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `selected_courses`
--
ALTER TABLE `selected_courses`
  ADD CONSTRAINT `selected_courses_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_details`
--
ALTER TABLE `student_details`
  ADD CONSTRAINT `student_details_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  ADD CONSTRAINT `student_details_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `student_details_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `student_details_ibfk_4` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `student_details_ibfk_5` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`);

--
-- Constraints for table `subject_ojt_fees`
--
ALTER TABLE `subject_ojt_fees`
  ADD CONSTRAINT `subject_ojt_fees_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `subject_research_fees`
--
ALTER TABLE `subject_research_fees`
  ADD CONSTRAINT `subject_research_fees_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `user_registration`
--
ALTER TABLE `user_registration`
  ADD CONSTRAINT `user_registration_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
