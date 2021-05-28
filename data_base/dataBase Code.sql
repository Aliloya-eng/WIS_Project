-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2019 at 12:18 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wis_hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `case_id` int(10) NOT NULL,
  `patient_id` int(10) NOT NULL,
  `doctor_id` int(10) NOT NULL,
  `case_entry_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `in_out` set('in','out') NOT NULL DEFAULT 'out',
  `room` int(5) DEFAULT '0',
  `urgent` set('yes','no') NOT NULL DEFAULT 'no',
  `discharged` set('yes','no') NOT NULL DEFAULT 'no',
  `department` varchar(30) DEFAULT NULL,
  `assessment_progress` varchar(1000) NOT NULL,
  `diagnosis` varchar(100) NOT NULL,
  `state` varchar(20) NOT NULL,
  `plan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`case_id`, `patient_id`, `doctor_id`, `case_entry_date`, `in_out`, `room`, `urgent`, `discharged`, `department`, `assessment_progress`, `diagnosis`, `state`, `plan`) VALUES
(1, 1, 1, '2019-12-27 19:09:25', 'in', 152, 'no', 'no', 'cardiology', 'abc', 'bcd', 'cde', 'def'),
(2, 1, 1, '2019-12-27 19:14:06', 'out', 0, 'yes', 'yes', 'emergency', 'high pressure', 'heart malfunction', 'emergency', 'surgery'),
(3, 1, 1, '2019-12-28 00:47:04', 'out', 0, 'yes', 'no', 'aaaaa', 'bbbbbbbbb', 'qqqqqq', 'zzzzzzz', 'mmmmmmmm'),
(4, 1, 1, '2019-12-28 00:49:04', 'out', 0, 'yes', 'no', 'aaaaa', 'bbbbbbbbb', 'qqqqqq', 'zzzzzzz', 'mmmmmmmm'),
(5, 1, 1, '2019-12-28 00:49:38', 'out', 0, 'yes', 'no', 'aaaaa', 'bbbbbbbbb', 'qqqqqq', 'zzzzzzz', 'mmmmmmmm');

-- --------------------------------------------------------

--
-- Table structure for table `cbc_test_result`
--

CREATE TABLE `cbc_test_result` (
  `result_id` int(10) NOT NULL,
  `test_id` int(10) NOT NULL,
  `case_id` int(10) NOT NULL,
  `patient_id` int(10) NOT NULL,
  `result_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `WBC` double NOT NULL,
  `LYM` double NOT NULL,
  `GRAN` double NOT NULL,
  `MID` double NOT NULL,
  `LYM_pc` double NOT NULL,
  `GRA_pc` double NOT NULL,
  `MID_pc` double NOT NULL,
  `RBC` double NOT NULL,
  `HGB` double NOT NULL,
  `HCT` double NOT NULL,
  `MCV` double NOT NULL,
  `MCH` double NOT NULL,
  `MCHC` double NOT NULL,
  `RDW_pc` double NOT NULL,
  `RDWa` double NOT NULL,
  `PLT` double NOT NULL,
  `MPV` double NOT NULL,
  `PDW` double NOT NULL,
  `PCT` double NOT NULL,
  `LPCR` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(10) NOT NULL,
  `national_number` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(50) NOT NULL,
  `title` varchar(10) NOT NULL,
  `major` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `national_number`, `password`, `name`, `title`, `major`) VALUES
(1, '321685498765', 'bcdc27f8799b37dd0ffd80f38abfe1eb', 'Waseem Hassan', 'professor', 'cardiology');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(10) NOT NULL,
  `national_number` varchar(20) DEFAULT NULL,
  `password` varchar(32) NOT NULL,
  `full_name` varchar(80) NOT NULL,
  `mother_name` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` set('male','female','other') NOT NULL,
  `more_information` text,
  `phone_number1` int(10) NOT NULL,
  `phone_number2` int(10) DEFAULT NULL,
  `date_of_registration` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `national_number`, `password`, `full_name`, `mother_name`, `date_of_birth`, `gender`, `more_information`, `phone_number1`, `phone_number2`, `date_of_registration`) VALUES
(1, NULL, '1932b800a9678bc1c1dd8bf8bc73d958', 'ali waseem hassan', 'widaad', '2017-05-15', 'male', NULL, 999675094, NULL, '2019-12-27 18:48:07');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(10) NOT NULL,
  `case_id` int(10) NOT NULL,
  `patient_id` int(10) NOT NULL,
  `doctor_id` int(10) NOT NULL,
  `report_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `assessment_progress` varchar(1000) NOT NULL,
  `diagnosis` varchar(100) NOT NULL,
  `state` varchar(20) NOT NULL,
  `plan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `case_id`, `patient_id`, `doctor_id`, `report_date`, `assessment_progress`, `diagnosis`, `state`, `plan`) VALUES
(1, 3, 1, 1, '2019-12-28 01:15:53', 'good', 'cardiology', 'California', 'OH NO');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `test_id` int(10) NOT NULL,
  `case_id` int(10) NOT NULL,
  `patient_id` int(10) NOT NULL,
  `doctor_id` int(10) NOT NULL,
  `test_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `test_type` varchar(50) NOT NULL,
  `urgent` set('yes','no') DEFAULT 'no',
  `done` set('yes','no') DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`test_id`, `case_id`, `patient_id`, `doctor_id`, `test_date`, `test_type`, `urgent`, `done`) VALUES
(1, 1, 1, 1, '2019-12-28 00:43:52', 'cbc', 'no', 'no'),
(2, 1, 1, 1, '2019-12-28 00:44:00', 'cbc', 'no', 'no'),
(3, 2, 1, 1, '2019-12-28 01:12:42', 'gastric fluid analysis', 'no', 'no');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`case_id`),
  ADD KEY `patient_id` (`patient_id`,`doctor_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `cbc_test_result`
--
ALTER TABLE `cbc_test_result`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `test_id` (`test_id`,`case_id`,`patient_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `case_id` (`case_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD UNIQUE KEY `national_number` (`national_number`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `national_number` (`national_number`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `case_id` (`case_id`,`patient_id`,`doctor_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`test_id`),
  ADD KEY `case_id` (`case_id`,`patient_id`,`doctor_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `case_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cbc_test_result`
--
ALTER TABLE `cbc_test_result`
  MODIFY `result_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `test_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `cases_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`);

--
-- Constraints for table `cbc_test_result`
--
ALTER TABLE `cbc_test_result`
  ADD CONSTRAINT `cbc_test_result_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `cbc_test_result_ibfk_2` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`),
  ADD CONSTRAINT `cbc_test_result_ibfk_3` FOREIGN KEY (`test_id`) REFERENCES `tests` (`test_id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`),
  ADD CONSTRAINT `reports_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`);

--
-- Constraints for table `tests`
--
ALTER TABLE `tests`
  ADD CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `tests_ibfk_2` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`),
  ADD CONSTRAINT `tests_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
