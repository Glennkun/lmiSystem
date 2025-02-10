-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2025 at 01:57 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lmi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `form_data`
--

CREATE TABLE `form_data` (
  `id` int(5) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `mi` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `suffix` varchar(10) NOT NULL,
  `occupation` varchar(50) NOT NULL,
  `birthdate` date NOT NULL DEFAULT current_timestamp(),
  `age` int(11) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `civilStatus` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `educational` varchar(50) NOT NULL,
  `course` varchar(50) NOT NULL,
  `vocational` varchar(50) NOT NULL,
  `workExperience` int(5) NOT NULL,
  `employmentStatus` varchar(20) NOT NULL,
  `purok` varchar(50) NOT NULL,
  `sitio` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `municipality` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `contact` int(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `local_overseas` varchar(20) NOT NULL,
  `remarks` varchar(50) NOT NULL,
  `ojt_name` varchar(50) NOT NULL,
  `dateEncoded` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `form_data`
--

INSERT INTO `form_data` (`id`, `firstName`, `mi`, `lastName`, `suffix`, `occupation`, `birthdate`, `age`, `sex`, `civilStatus`, `religion`, `educational`, `course`, `vocational`, `workExperience`, `employmentStatus`, `purok`, `sitio`, `barangay`, `municipality`, `province`, `contact`, `email`, `local_overseas`, `remarks`, `ojt_name`, `dateEncoded`) VALUES
(5, 'NARCISA', 'I.', 'BALILING', 'N/A', 'N/A', '1991-06-13', 33, 'MALE', 'SINGLE', 'N/A', 'N/A', 'N/A', 'N/A', 2, 'EMPLOYED', 'N/A', '', 'N/A', 'CORELLA', 'BOHOL', 0, 'N/A', 'LOCAL', 'TUPADERS', 'RODEL GLENN JAWA', '2025-02-09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `form_data`
--
ALTER TABLE `form_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `form_data`
--
ALTER TABLE `form_data`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
