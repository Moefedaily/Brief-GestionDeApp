
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2024 at 02:40 PM
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
-- Database: `gda`
--

-- --------------------------------------------------------

--
-- Table structure for table `gda_attendance`
--

DROP TABLE IF EXISTS `gda_attendance`;
CREATE TABLE IF NOT EXISTS `gda_attendance` (
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `presence` tinyint(1) DEFAULT NULL,
  `delay` tinyint(1) DEFAULT NULL,
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gda_attendance`
--

INSERT INTO `gda_attendance` (`user_id`, `course_id`, `presence`, `delay`) VALUES
(1, 1, 1, 0),
(1, 2, 0, 1),
(2, 1, 1, 0),
(3, 4, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gda_classes`
--

DROP TABLE IF EXISTS `gda_classes`;
CREATE TABLE IF NOT EXISTS `gda_classes` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(255) NOT NULL,
  `class_start_date` date NOT NULL,
  `class_end_date` date NOT NULL,
  `places_available` int(11) NOT NULL,
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `class_name` (`class_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gda_classes`
--

INSERT INTO `gda_classes` (`class_id`, `class_name`, `class_start_date`, `class_end_date`, `available_places`) VALUES
(1, 'DWWM2', '2024-03-01', '2024-06-30', 20),
(2, 'DWWM3', '2024-04-15', '2024-09-30', 15),
(3, 'CDA', '2024-05-01', '2024-10-31', 12);

-- --------------------------------------------------------

--
-- Table structure for table `gda_courses`
--

DROP TABLE IF EXISTS `gda_courses`;
CREATE TABLE IF NOT EXISTS `gda_courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `course_date` date NOT NULL,
  `course_start_time` time NOT NULL,
  `course_end_time` time NOT NULL,
  `course_randomCode` int(5) NOT NULL,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `course_randomCode` (`course_randomCode`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gda_courses`
--

INSERT INTO `gda_courses` (`course_id`, `class_id`, `course_date`, `course_start_time`, `course_end_time`, `course_randomCode`) VALUES
(1, 1, '2024-03-15', '09:00:00', '12:00:00', 12345),
(2, 1, '2024-03-22', '13:00:00', '16:00:00', 67890),
(3, 2, '2024-04-20', '10:00:00', '13:00:00', 24680),
(4, 3, '2024-05-10', '14:00:00', '17:00:00', 13579);

-- --------------------------------------------------------

--
-- Table structure for table `gda_roles`
--

DROP TABLE IF EXISTS `gda_roles`;
CREATE TABLE IF NOT EXISTS `gda_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gda_roles`
--

INSERT INTO `gda_roles` (`role_id`, `role_name`) VALUES
('Apprenant'),
('Formateur'),
('Administrateur');

-- --------------------------------------------------------

--
-- Table structure for table `gda_user_class`
--

DROP TABLE IF EXISTS `gda_user_class`;
CREATE TABLE IF NOT EXISTS `gda_user_class` (
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`class_id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gda_user_class`
--

INSERT INTO `gda_user_class` (`user_id`, `class_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `gda_users`
--

DROP TABLE IF EXISTS `gda_users`;
CREATE TABLE IF NOT EXISTS `gda_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `activation` tinyint(1) NOT NULL DEFAULT '0',
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gda_users`
--

INSERT INTO `gda_users` (`user_id`, `first_name`, `last_name`, `password`, `email`, `activation`, `role_id`) VALUES
(1, 'Jean', 'Dupont', '$2y$10$Uv7GJpmaix3lm9hE5urk4.ww0vwumq6qqOV32WwN/nBorqUtHzzla', 'dada@gmail.com', 1, 3),
(2, 'Marie', 'Durand', '$2y$10$Uv7GJpmaix3lm9hE5urk4.ww0vwumq6qqOV32WwN/nBorqUtHzzla', 'tutu@gmail.com', 1, 2),
(3, 'Pierre', 'Martin', '$2y$10$Uv7GJpmaix3lm9hE5urk4.ww0vwumq6qqOV32WwN/nBorqUtHzzla', 'dodo@gmail.com', 1, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gda_attendance`
--
ALTER TABLE `gda_attendance`
  ADD CONSTRAINT `gda_attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `gda_users` (`user_id`),
  ADD CONSTRAINT `gda_attendance_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `gda_courses` (`course_id`);

--
-- Constraints for table `gda_courses`
--
ALTER TABLE `gda_courses`
  ADD CONSTRAINT `gda_courses_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `gda_classes` (`class_id`);

--
-- Constraints for table `gda_user_class`
--
ALTER TABLE `gda_user_class`
  ADD CONSTRAINT `gda_user_class_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `gda_users` (`user_id`),
  ADD CONSTRAINT `gda_user_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `gda_classes` (`class_id`);

--
-- Constraints for table `gda_users`
--
ALTER TABLE `gda_users`
  ADD CONSTRAINT `gda_users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `gda_roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;