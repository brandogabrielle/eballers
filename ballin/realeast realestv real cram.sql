-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 09:11 AM
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
-- Database: `oro_va_dental_records`
--

-- --------------------------------------------------------

--
-- Table structure for table `patient_registry`
--

CREATE TABLE `patient_registry` (
  `id` int(11) NOT NULL,
  `registry_date` date NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `dob` date NOT NULL,
  `age` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `appointment_date` date NOT NULL,
  `add_info` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_registry`
--

INSERT INTO `patient_registry` (`id`, `registry_date`, `last_name`, `first_name`, `middle_name`, `dob`, `age`, `email`, `address`, `mobile`, `appointment_date`, `add_info`, `created_at`) VALUES
(22, '2024-12-11', 'Paaño', 'Brando Gabrielle ', 'Jimenez', '2002-11-28', 22, 'paano.brando028@gmail.com', '401 Perla St. Tondo Manila ', '09774298123', '2024-12-28', 'High blood pressure', '2024-12-10 17:31:52');

-- --------------------------------------------------------

--
-- Table structure for table `patient_services`
--

CREATE TABLE `patient_services` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `option_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_services`
--

INSERT INTO `patient_services` (`id`, `patient_id`, `service_id`, `option_id`) VALUES
(56, 22, 1, 1),
(57, 22, 9, 37),
(58, 22, 10, 41);

-- --------------------------------------------------------

--
-- Table structure for table `searchable_patients`
--

CREATE TABLE `searchable_patients` (
  `patient_id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `services` text DEFAULT NULL,
  `search_term` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `searchable_patients`
--

INSERT INTO `searchable_patients` (`patient_id`, `full_name`, `address`, `services`, `search_term`, `created_at`) VALUES
(22, 'Brando Gabrielle  Jimenez Paaño', '401 Perla St. Tondo Manila ', 'Diagnosis, Partial Denture, Full Denture', 'brando gabrielle  jimenez paaño 401 perla st. tondo manila  paano.brando028@gmail.com', '2024-12-10 17:31:52');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`) VALUES
(1, 'Diagnosis'),
(2, 'Periodontics'),
(3, 'Oral Surgery'),
(4, 'Restorative'),
(5, 'Repair'),
(6, 'Prosthodontics'),
(7, 'Orthodontics'),
(8, 'Others'),
(9, 'Partial Denture'),
(10, 'Full Denture');

-- --------------------------------------------------------

--
-- Table structure for table `service_options`
--

CREATE TABLE `service_options` (
  `option_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `option_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_options`
--

INSERT INTO `service_options` (`option_id`, `service_id`, `option_name`) VALUES
(1, 1, 'Consultation'),
(2, 1, '+ Medical Certificate'),
(3, 2, 'Light-Moderate'),
(4, 2, 'Heavy'),
(5, 2, '+ Fluoride Treatment'),
(6, 3, 'Simple Extraction'),
(7, 3, 'Complicated Extraction'),
(8, 3, 'Odontectomy'),
(9, 4, 'Temporary'),
(10, 4, 'Composite'),
(11, 4, 'Additional Surface'),
(12, 4, 'Pit and Fissure Sealant'),
(13, 5, 'Crack'),
(14, 5, 'Broken with Impression'),
(15, 5, 'Plastic (Missing Pontic)'),
(16, 5, 'Porcelain (Missing Pontic)'),
(17, 6, 'Plastic (Jacket Crown per Unit)'),
(18, 6, 'Porcelain Simple Metal (Jacket Crown per Unit)'),
(19, 6, 'Porcelain Tilite (Jacket Crown per Unit)'),
(20, 6, 'E-Max (Jacket Crown per Unit)'),
(21, 6, 'Zirconia (Jacket Crown per Unit)'),
(22, 6, 'Re-Cementation'),
(23, 7, 'Conventional Metal Brackets'),
(24, 7, 'Ceramic Brackets'),
(25, 7, 'Self-Litigating Metal Brackets'),
(26, 7, 'Functional Retainer'),
(27, 7, 'Retainer with Design'),
(28, 7, 'Ortho Kit'),
(29, 7, 'Ortho Wax'),
(30, 8, 'Teeth Whitening'),
(31, 8, 'Rebase'),
(32, 8, 'Reline'),
(33, 9, 'Stayplate Plastic'),
(34, 9, 'Stayplate Porcelain'),
(35, 9, 'One-piece Plastic'),
(36, 9, 'One-piece Porcelain'),
(37, 9, 'Flexite'),
(38, 10, 'Stayplate Plastic'),
(39, 10, 'Stayplate Porcelain'),
(40, 10, 'Ivocap'),
(41, 10, 'Thermosen');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `patient_registry`
--
ALTER TABLE `patient_registry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_services`
--
ALTER TABLE `patient_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `searchable_patients`
--
ALTER TABLE `searchable_patients`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `service_options`
--
ALTER TABLE `service_options`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `service_id` (`service_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `patient_registry`
--
ALTER TABLE `patient_registry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `patient_services`
--
ALTER TABLE `patient_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `service_options`
--
ALTER TABLE `service_options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `patient_services`
--
ALTER TABLE `patient_services`
  ADD CONSTRAINT `patient_services_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient_registry` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_services_ibfk_3` FOREIGN KEY (`option_id`) REFERENCES `service_options` (`option_id`) ON DELETE CASCADE;

--
-- Constraints for table `searchable_patients`
--
ALTER TABLE `searchable_patients`
  ADD CONSTRAINT `searchable_patients_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient_registry` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_options`
--
ALTER TABLE `service_options`
  ADD CONSTRAINT `service_options_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
