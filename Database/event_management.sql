-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2025 at 06:39 PM
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
-- Database: `event_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `generated_by` int(11) NOT NULL,
  `certificate_url` varchar(255) NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `event_id`, `user_id`, `generated_by`, `certificate_url`, `issued_at`) VALUES
(1, 1, 1, 2, 'certificates/coding_hackathon_rahul.pdf', '2025-02-19 05:30:08'),
(2, 2, 1, 3, 'certificates/ai_workshop_rahul.pdf', '2025-02-19 05:30:08'),
(3, 2, 3, 3, 'certificates/ai_workshop_amit.pdf', '2025-02-19 05:30:08');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `event_type` enum('university-specific','open-for-all') NOT NULL,
  `university_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `mentor_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `timeline_description` text DEFAULT NULL,
  `rules` text DEFAULT NULL,
  `rewards` text DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_type`, `university_id`, `created_by`, `status`, `mentor_notes`, `created_at`, `updated_at`, `start_date`, `end_date`, `timeline_description`, `rules`, `rewards`, `poster`) VALUES
(1, 'Coding Hackathon', 'A competitive coding event.', 'university-specific', 1, 2, 'pending', NULL, '2025-02-19 05:22:56', '2025-02-19 05:22:56', '2025-03-01 10:00:00', '2025-03-01 18:00:00', '10:00 AM - Registration, 11:00 AM - Round 1', 'No cheating, Individual participation only.', 'Cash Prize: ₹10,000', 'hackathon_poster.jpg'),
(2, 'AI Workshop', 'Learn AI from industry experts.', 'open-for-all', NULL, 3, 'approved', NULL, '2025-02-19 05:22:56', '2025-02-19 05:22:56', '2025-03-10 09:00:00', '2025-03-10 17:00:00', '9:00 AM - Introduction, 10:00 AM - Hands-on session', 'Basic programming knowledge required.', 'Certificate + Swags', 'ai_workshop_poster.jpg'),
(3, 'Tech Talk on Quantum Computing', 'An expert session on Quantum Computing.', 'university-specific', 2, 2, 'rejected', 'Please include more details in the description.', '2025-02-19 05:22:56', '2025-02-19 05:22:56', '2025-04-05 14:00:00', '2025-04-05 16:00:00', '2:00 PM - Talk, 3:30 PM - Q&A Session', 'No recordings allowed.', 'Participation Certificate', 'quantum_talk_poster.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `event_approvals`
--

CREATE TABLE `event_approvals` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  `status` enum('approved','rejected') NOT NULL,
  `notes` text DEFAULT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_approvals`
--

INSERT INTO `event_approvals` (`id`, `event_id`, `mentor_id`, `status`, `notes`, `approved_at`) VALUES
(1, 1, 3, '', NULL, '2025-02-19 05:24:14'),
(2, 2, 3, 'approved', NULL, '2025-02-19 05:24:14'),
(3, 3, 3, 'rejected', 'Please include more details in the description.', '2025-02-19 05:24:14');

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_registrations`
--

INSERT INTO `event_registrations` (`id`, `event_id`, `user_id`, `registered_at`) VALUES
(1, 1, 1, '2025-02-19 05:24:00'),
(2, 2, 1, '2025-02-19 05:24:00'),
(3, 2, 3, '2025-02-19 05:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `event_statistics`
--

CREATE TABLE `event_statistics` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `total_registrations` int(11) DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_statistics`
--

INSERT INTO `event_statistics` (`id`, `event_id`, `total_registrations`, `last_updated`) VALUES
(1, 1, 1, '2025-02-19 05:24:30'),
(2, 2, 2, '2025-02-19 05:24:30'),
(3, 3, 0, '2025-02-19 05:24:30');

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`id`, `name`) VALUES
(4, 'BITS Pilani'),
(2, 'IIT Bombay'),
(1, 'Marwadi University'),
(3, 'NIT Surat');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('member','coordinator','mentor') DEFAULT 'member',
  `university_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `mobile_no`, `email`, `linkedin`, `github`, `password`, `role`, `university_id`, `created_at`) VALUES
(1, 'Rahul', 'Sharma', '9876543210', 'rahul.sharma@example.com', 'https://linkedin.com/in/rahulsharma', 'https://github.com/rahulsharma', 'hashed_password_1', 'member', 1, '2025-02-19 05:22:45'),
(2, 'Ananya', 'Mehta', '9876501234', 'ananya.mehta@example.com', 'https://linkedin.com/in/ananyamehta', 'https://github.com/ananyamehta', 'hashed_password_2', 'coordinator', 2, '2025-02-19 05:22:45'),
(3, 'Amit', 'Singh', '7890123456', 'amit.singh@example.com', 'https://linkedin.com/in/amitsingh', 'https://github.com/amitsingh', 'hashed_password_3', 'mentor', 1, '2025-02-19 05:22:45'),
(4, 'Priya', 'Verma', '8901234567', 'priya.verma@example.com', 'https://linkedin.com/in/priyaverma', 'https://github.com/priyaverma', 'hashed_password_4', 'mentor', 3, '2025-02-19 05:22:45'),
(9, 'Abhay', 'Nathwani', '', 'a@g.com', NULL, NULL, '$2y$10$XSZ2OPPdqzVKgXrJoY6jE.vTSM3PfQVbHOTY/Wor4VuwqY/tpbUEq', 'member', 1, '2025-02-19 16:07:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `generated_by` (`generated_by`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `university_id` (`university_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `event_approvals`
--
ALTER TABLE `event_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `mentor_id` (`mentor_id`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `event_statistics`
--
ALTER TABLE `event_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile_no` (`mobile_no`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `university_id` (`university_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event_approvals`
--
ALTER TABLE `event_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event_statistics`
--
ALTER TABLE `event_statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificates_ibfk_3` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_approvals`
--
ALTER TABLE `event_approvals`
  ADD CONSTRAINT `event_approvals_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_approvals_ibfk_2` FOREIGN KEY (`mentor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD CONSTRAINT `event_registrations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_registrations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_statistics`
--
ALTER TABLE `event_statistics`
  ADD CONSTRAINT `event_statistics_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
