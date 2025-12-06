-- phpMyAdmin SQL Dump
-- Sistem Manajemen Gaji - Updated Schema
-- Version 2.0 with Authentication & Salary Slips

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Database: `payroll`
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `payroll` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `payroll`;

-- --------------------------------------------------------
-- Table structure for table `employee`
-- --------------------------------------------------------

CREATE TABLE `employee` (
  `employee_id` INT(5) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `gender` VARCHAR(10) NOT NULL,
  `birth_date` DATE DEFAULT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `province` VARCHAR(100) DEFAULT NULL,
  `postal_code` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `website` VARCHAR(100) DEFAULT NULL,
  `join_date` DATE DEFAULT NULL,
  `annual_basic_pay` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `monthly_pay` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `tax` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `tax_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Dumping data for table `employee`
-- --------------------------------------------------------

INSERT INTO `employee` (`employee_id`, `name`, `gender`, `birth_date`, `address`, `city`, `province`, `postal_code`, `email`, `website`, `join_date`, `annual_basic_pay`, `monthly_pay`, `tax`, `tax_amount`) VALUES
(101, 'Andi Pratama', 'Male', '1994-09-22', 'Jalan Senopati No. 2', 'Bandung', 'Jawa Barat', '40123', 'andi@gmail.com', 'www.linkedin.com/andi', '2017-10-26', 48000000, 3680000, 5, 200000),
(102, 'Maharani Putri', 'Female', '1994-04-04', 'Jalan Kebun Raya No. 15', 'Semarang', 'Jawa Tengah', '50234', 'maharani@gmail.com', 'www.linkedin.com/maharani', '2017-10-26', 60000000, 4600000, 5, 250000),
(103, 'Guntur Wibowo', 'Male', '1994-09-23', 'Jalan Mangga Dua No. 8', 'Jakarta', 'DKI Jakarta', '10730', 'guntur@gmail.com', 'www.linkedin.com/guntur', '2017-10-10', 42000000, 3220000, 5, 175000),
(104, 'Veri Santoso', 'Male', '1994-09-22', 'Jalan Pahlawan No. 22', 'Surabaya', 'Jawa Timur', '60175', 'veri@gmail.com', 'www.linkedin.com/veri', '2017-11-22', 54000000, 4140000, 5, 225000),
(105, 'Tejo Purnomo', 'Male', '1995-09-22', 'Jalan Waringin Barat No. 5', 'Medan', 'Sumatera Utara', '20112', 'tejo@gmail.com', 'www.linkedin.com/tejo', '2017-09-24', 48000000, 3680000, 5, 200000),
(106, 'Sumarsih Dewi', 'Female', '1994-10-09', 'Jalan Bulu Ayam No. 3', 'Denpasar', 'Bali', '80235', 'sumarsih@gmail.com', 'www.linkedin.com/sumarsih', '2017-10-31', 52000000, 3990000, 5, 216667),
(107, 'Yuri Kartika', 'Female', '1994-09-08', 'Jalan Gajah Mada No. 10', 'Sleman', 'Yogyakarta', '55281', 'yuri@gmail.com', 'www.linkedin.com/yuri', '2018-01-15', 56000000, 4293333, 5, 233333),
(108, 'Jaya Sentosa', 'Male', '1997-06-17', 'Jalan Muja Muju No. 7', 'Bantul', 'Yogyakarta', '55711', 'jaya@gmail.com', 'www.linkedin.com/jaya', '2017-10-20', 45000000, 3450000, 5, 187500);

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `user_id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'employee') NOT NULL DEFAULT 'employee',
  `employee_id` INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`employee_id`) REFERENCES `employee`(`employee_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Dumping data for table `users`
-- Default passwords: admin123 for admin, employee123 for employees
-- Passwords are hashed using PHP password_hash()
-- --------------------------------------------------------

INSERT INTO `users` (`username`, `password`, `role`, `employee_id`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL),
('101', '$2y$10$xLxRZq.NtK1h.6ZRYV3OUeF8K.XEcJzxH9r0LXwZgNBWQE2XL8WKy', 'employee', 101),
('102', '$2y$10$xLxRZq.NtK1h.6ZRYV3OUeF8K.XEcJzxH9r0LXwZgNBWQE2XL8WKy', 'employee', 102),
('103', '$2y$10$xLxRZq.NtK1h.6ZRYV3OUeF8K.XEcJzxH9r0LXwZgNBWQE2XL8WKy', 'employee', 103),
('104', '$2y$10$xLxRZq.NtK1h.6ZRYV3OUeF8K.XEcJzxH9r0LXwZgNBWQE2XL8WKy', 'employee', 104),
('105', '$2y$10$xLxRZq.NtK1h.6ZRYV3OUeF8K.XEcJzxH9r0LXwZgNBWQE2XL8WKy', 'employee', 105),
('106', '$2y$10$xLxRZq.NtK1h.6ZRYV3OUeF8K.XEcJzxH9r0LXwZgNBWQE2XL8WKy', 'employee', 106),
('107', '$2y$10$xLxRZq.NtK1h.6ZRYV3OUeF8K.XEcJzxH9r0LXwZgNBWQE2XL8WKy', 'employee', 107),
('108', '$2y$10$xLxRZq.NtK1h.6ZRYV3OUeF8K.XEcJzxH9r0LXwZgNBWQE2XL8WKy', 'employee', 108);

-- --------------------------------------------------------
-- Table structure for table `salary_slips`
-- --------------------------------------------------------

CREATE TABLE `salary_slips` (
  `slip_id` INT AUTO_INCREMENT PRIMARY KEY,
  `employee_id` INT NOT NULL,
  `month` INT NOT NULL,
  `year` INT NOT NULL,
  `basic_pay` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `allowance` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `deduction` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `tax_percent` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `tax_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `net_pay` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `status` ENUM('draft', 'published') DEFAULT 'draft',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`employee_id`) REFERENCES `employee`(`employee_id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_slip` (`employee_id`, `month`, `year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Sample salary slips for December 2025
-- --------------------------------------------------------

INSERT INTO `salary_slips` (`employee_id`, `month`, `year`, `basic_pay`, `allowance`, `deduction`, `tax_percent`, `tax_amount`, `net_pay`, `status`) VALUES
(101, 12, 2025, 4000000, 500000, 100000, 5, 200000, 4200000, 'published'),
(102, 12, 2025, 5000000, 600000, 100000, 5, 250000, 5250000, 'published'),
(103, 12, 2025, 3500000, 400000, 100000, 5, 175000, 3625000, 'published'),
(104, 12, 2025, 4500000, 550000, 100000, 5, 225000, 4725000, 'published'),
(105, 12, 2025, 4000000, 500000, 100000, 5, 200000, 4200000, 'published'),
(106, 12, 2025, 4333333, 520000, 100000, 5, 216667, 4536666, 'published'),
(107, 12, 2025, 4666667, 560000, 100000, 5, 233333, 4893334, 'published'),
(108, 12, 2025, 3750000, 450000, 100000, 5, 187500, 3912500, 'published');

-- --------------------------------------------------------
-- Sample salary slips for November 2025
-- --------------------------------------------------------

INSERT INTO `salary_slips` (`employee_id`, `month`, `year`, `basic_pay`, `allowance`, `deduction`, `tax_percent`, `tax_amount`, `net_pay`, `status`) VALUES
(101, 11, 2025, 4000000, 500000, 100000, 5, 200000, 4200000, 'published'),
(102, 11, 2025, 5000000, 600000, 100000, 5, 250000, 5250000, 'published'),
(103, 11, 2025, 3500000, 400000, 100000, 5, 175000, 3625000, 'published'),
(104, 11, 2025, 4500000, 550000, 100000, 5, 225000, 4725000, 'published'),
(105, 11, 2025, 4000000, 500000, 100000, 5, 200000, 4200000, 'published'),
(106, 11, 2025, 4333333, 520000, 100000, 5, 216667, 4536666, 'published'),
(107, 11, 2025, 4666667, 560000, 100000, 5, 233333, 4893334, 'published'),
(108, 11, 2025, 3750000, 450000, 100000, 5, 187500, 3912500, 'published');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
