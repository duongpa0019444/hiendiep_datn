-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 03, 2025 lúc 04:59 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `hien_diep`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attendances`
--

CREATE TABLE `attendances` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('present','absent','late','excused') DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `attendances`
--

INSERT INTO `attendances` (`id`, `user_id`, `schedule_id`, `date`, `status`, `note`, `created_at`) VALUES
(1, 7, 1, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(2, 8, 1, '2025-06-02', 'absent', 'Nghỉ ốm', '2025-06-02 13:39:20'),
(3, 9, 1, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(4, 10, 1, '2025-06-02', 'late', 'Đến muộn 10 phút', '2025-06-02 13:39:20'),
(5, 11, 1, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(6, 12, 1, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(7, 13, 1, '2025-06-02', 'excused', 'Nghỉ có phép', '2025-06-02 13:39:20'),
(8, 14, 1, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(9, 15, 1, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(10, 16, 1, '2025-06-02', 'absent', NULL, '2025-06-02 13:39:20'),
(11, 17, 2, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(12, 18, 2, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(13, 19, 2, '2025-06-03', 'late', 'Đến muộn 5 phút', '2025-06-02 13:39:20'),
(14, 20, 2, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(15, 21, 2, '2025-06-03', 'absent', 'Nghỉ không phép', '2025-06-02 13:39:20'),
(16, 22, 2, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(17, 23, 2, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(18, 24, 2, '2025-06-03', 'excused', 'Nghỉ có phép', '2025-06-02 13:39:20'),
(19, 25, 2, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(20, 26, 2, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(21, 7, 3, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(22, 8, 3, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(23, 9, 3, '2025-06-02', 'absent', NULL, '2025-06-02 13:39:20'),
(24, 10, 3, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(25, 11, 3, '2025-06-02', 'late', 'Đến muộn 15 phút', '2025-06-02 13:39:20'),
(26, 7, 4, '2025-06-04', 'present', NULL, '2025-06-02 13:39:20'),
(27, 8, 4, '2025-06-04', 'present', NULL, '2025-06-02 13:39:20'),
(28, 9, 4, '2025-06-04', 'present', NULL, '2025-06-02 13:39:20'),
(29, 10, 4, '2025-06-04', 'excused', 'Nghỉ có phép', '2025-06-02 13:39:20'),
(30, 11, 4, '2025-06-04', 'present', NULL, '2025-06-02 13:39:20'),
(31, 7, 5, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(32, 8, 5, '2025-06-03', 'absent', NULL, '2025-06-02 13:39:20'),
(33, 9, 5, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(34, 10, 5, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(35, 11, 5, '2025-06-03', 'late', 'Đến muộn 10 phút', '2025-06-02 13:39:20'),
(36, 17, 6, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(37, 18, 6, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(38, 19, 6, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(39, 20, 6, '2025-06-02', 'absent', NULL, '2025-06-02 13:39:20'),
(40, 21, 6, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(41, 7, 7, '2025-06-04', 'present', NULL, '2025-06-02 13:39:20'),
(42, 8, 7, '2025-06-04', 'present', NULL, '2025-06-02 13:39:20'),
(43, 9, 7, '2025-06-04', 'present', NULL, '2025-06-02 13:39:20'),
(44, 10, 7, '2025-06-04', 'excused', 'Nghỉ có phép', '2025-06-02 13:39:20'),
(45, 11, 7, '2025-06-04', 'present', NULL, '2025-06-02 13:39:20'),
(46, 12, 8, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(47, 13, 8, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(48, 14, 8, '2025-06-03', 'absent', NULL, '2025-06-02 13:39:20'),
(49, 15, 8, '2025-06-03', 'present', NULL, '2025-06-02 13:39:20'),
(50, 16, 8, '2025-06-03', 'late', 'Đến muộn 5 phút', '2025-06-02 13:39:20'),
(51, 17, 9, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(52, 18, 9, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(53, 19, 9, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(54, 20, 9, '2025-06-02', 'absent', NULL, '2025-06-02 13:39:20'),
(55, 21, 9, '2025-06-02', 'present', NULL, '2025-06-02 13:39:20'),
(56, 22, 10, '2025-06-04', 'present', NULL, '2025-06-02 13:39:20'),
(57, 23, 10, '2025-06-04', 'present', NULL, '2025-06-02 13:39:20'),
(58, 24, 10, '2025-06-04', 'present', NULL, '2025-06-02 13:39:20'),
(59, 25, 10, '2025-06-04', 'excused', 'Nghỉ có phép', '2025-06-02 13:39:20'),
(60, 26, 10, '2025-06-04', 'present', NULL, '2025-06-02 13:39:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `courses_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `classes`
--

INSERT INTO `classes` (`id`, `name`, `teacher_id`, `courses_id`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 'Lớp A1-1', 2, 1, '2025-06-01', '2025-08-30', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(2, 'Lớp A1-2', 3, 1, '2025-06-01', '2025-08-30', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(3, 'Lớp A2-1', 4, 2, '2025-06-15', '2025-09-15', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(4, 'Lớp A2-2', 5, 2, '2025-06-15', '2025-09-15', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(5, 'Lớp IELTS-1', 6, 3, '2025-07-01', '2025-09-30', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(6, 'Lớp IELTS-2', 2, 3, '2025-07-01', '2025-09-30', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(7, 'Lớp TOEIC-1', 3, 4, '2025-07-15', '2025-10-15', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(8, 'Lớp TOEIC-2', 4, 4, '2025-07-15', '2025-10-15', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(9, 'Lớp Giao tiếp-1', 5, 5, '2025-06-01', '2025-08-30', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(10, 'Lớp Giao tiếp-2', 6, 5, '2025-03-01', '2025-05-30', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(13, 'Lớp A2-3', 3, 2, '2025-06-05', '2025-09-03', '2025-06-02 14:55:59', '2025-06-02 14:55:59'),
(14, 'Lớp IELTS-3', 4, 3, '2025-06-10', '2025-09-10', '2025-06-02 14:55:59', '2025-06-02 14:55:59'),
(15, 'Lớp TOEIC-3', 5, 4, '2025-06-15', '2025-09-15', '2025-06-02 14:55:59', '2025-06-02 14:55:59'),
(16, 'Lớp Giao tiếp-3', 6, 5, '2025-06-20', '2025-09-20', '2025-06-02 14:55:59', '2025-06-02 14:55:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `class_student`
--

CREATE TABLE `class_student` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `class_student`
--

INSERT INTO `class_student` (`id`, `class_id`, `student_id`, `note`, `created_at`) VALUES
(1, 1, 7, 'Học viên mới', '2025-06-02 13:32:34'),
(2, 1, 8, NULL, '2025-06-02 13:32:34'),
(3, 1, 9, NULL, '2025-06-02 13:32:34'),
(4, 1, 10, NULL, '2025-06-02 13:32:34'),
(5, 1, 11, NULL, '2025-06-02 13:32:34'),
(6, 1, 12, NULL, '2025-06-02 13:32:34'),
(7, 1, 13, NULL, '2025-06-02 13:32:34'),
(8, 1, 14, NULL, '2025-06-02 13:32:34'),
(9, 1, 15, NULL, '2025-06-02 13:32:34'),
(10, 1, 16, NULL, '2025-06-02 13:32:34'),
(11, 2, 17, NULL, '2025-06-02 13:32:34'),
(12, 2, 18, NULL, '2025-06-02 13:32:34'),
(13, 2, 19, NULL, '2025-06-02 13:32:34'),
(14, 2, 20, NULL, '2025-06-02 13:32:34'),
(15, 2, 21, NULL, '2025-06-02 13:32:34'),
(16, 2, 22, NULL, '2025-06-02 13:32:34'),
(17, 2, 23, NULL, '2025-06-02 13:32:34'),
(18, 2, 24, NULL, '2025-06-02 13:32:34'),
(19, 2, 25, NULL, '2025-06-02 13:32:34'),
(20, 2, 26, NULL, '2025-06-02 13:32:34'),
(21, 3, 7, NULL, '2025-06-02 13:32:34'),
(22, 3, 8, NULL, '2025-06-02 13:32:34'),
(23, 3, 9, NULL, '2025-06-02 13:32:34'),
(24, 3, 10, NULL, '2025-06-02 13:32:34'),
(25, 3, 11, NULL, '2025-06-02 13:32:34'),
(26, 3, 12, NULL, '2025-06-02 13:32:34'),
(27, 3, 13, NULL, '2025-06-02 13:32:34'),
(28, 3, 14, NULL, '2025-06-02 13:32:34'),
(29, 3, 15, NULL, '2025-06-02 13:32:34'),
(30, 3, 16, NULL, '2025-06-02 13:32:34'),
(31, 4, 17, NULL, '2025-06-02 13:32:34'),
(32, 4, 18, NULL, '2025-06-02 13:32:34'),
(33, 4, 19, NULL, '2025-06-02 13:32:34'),
(34, 4, 20, NULL, '2025-06-02 13:32:34'),
(35, 4, 21, NULL, '2025-06-02 13:32:34'),
(36, 4, 22, NULL, '2025-06-02 13:32:34'),
(37, 4, 23, NULL, '2025-06-02 13:32:34'),
(38, 4, 24, NULL, '2025-06-02 13:32:34'),
(39, 4, 25, NULL, '2025-06-02 13:32:34'),
(40, 4, 26, NULL, '2025-06-02 13:32:34'),
(41, 5, 7, NULL, '2025-06-02 13:32:34'),
(42, 5, 8, NULL, '2025-06-02 13:32:34'),
(43, 5, 9, NULL, '2025-06-02 13:32:34'),
(44, 5, 10, NULL, '2025-06-02 13:32:34'),
(45, 5, 11, NULL, '2025-06-02 13:32:34'),
(46, 5, 12, NULL, '2025-06-02 13:32:34'),
(47, 5, 13, NULL, '2025-06-02 13:32:34'),
(48, 5, 14, NULL, '2025-06-02 13:32:34'),
(49, 5, 15, NULL, '2025-06-02 13:32:34'),
(50, 5, 16, NULL, '2025-06-02 13:32:34'),
(51, 6, 17, NULL, '2025-06-02 13:32:34'),
(52, 6, 18, NULL, '2025-06-02 13:32:34'),
(53, 6, 19, NULL, '2025-06-02 13:32:34'),
(54, 6, 20, NULL, '2025-06-02 13:32:34'),
(55, 6, 21, NULL, '2025-06-02 13:32:34'),
(56, 7, 7, NULL, '2025-06-02 13:32:34'),
(57, 7, 8, NULL, '2025-06-02 13:32:34'),
(58, 7, 9, NULL, '2025-06-02 13:32:34'),
(59, 7, 10, NULL, '2025-06-02 13:32:34'),
(60, 7, 11, NULL, '2025-06-02 13:32:34'),
(61, 8, 12, NULL, '2025-06-02 13:32:34'),
(62, 8, 13, NULL, '2025-06-02 13:32:34'),
(63, 8, 14, NULL, '2025-06-02 13:32:34'),
(64, 8, 15, NULL, '2025-06-02 13:32:34'),
(65, 8, 16, NULL, '2025-06-02 13:32:34'),
(66, 9, 17, NULL, '2025-06-02 13:32:34'),
(67, 9, 18, NULL, '2025-06-02 13:32:34'),
(68, 9, 19, NULL, '2025-06-02 13:32:34'),
(69, 9, 20, NULL, '2025-06-02 13:32:34'),
(70, 9, 21, NULL, '2025-06-02 13:32:34'),
(71, 10, 22, NULL, '2025-06-02 13:32:34'),
(72, 10, 23, NULL, '2025-06-02 13:32:34'),
(73, 10, 24, NULL, '2025-06-02 13:32:34'),
(74, 10, 25, NULL, '2025-06-02 13:32:34'),
(75, 10, 26, NULL, '2025-06-02 13:32:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(11,0) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `courses`
--

INSERT INTO `courses` (`id`, `name`, `price`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Tiếng Anh A1', 5000000, 'Khóa học tiếng Anh cơ bản trình độ A1', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(2, 'Tiếng Anh A2', 6000000, 'Khóa học tiếng Anh trình độ A2', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(3, 'IELTS', 10000000, 'Khóa học luyện thi IELTS', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(4, 'TOEIC', 8000000, 'Khóa học luyện thi TOEIC', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(5, 'Giao tiếp', 7000000, 'Khóa học tiếng Anh giao tiếp', '2025-06-02 13:32:34', '2025-06-02 13:32:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `course_payments`
--

CREATE TABLE `course_payments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('unpaid','paid') DEFAULT 'unpaid',
  `payment_code` varchar(20) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `course_payments`
--

INSERT INTO `course_payments` (`id`, `student_id`, `class_id`, `course_id`, `amount`, `status`, `payment_code`, `method`, `note`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 1, 5000000.00, 'paid', 'PAY001', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(2, 8, 1, 1, 5000000.00, 'paid', 'PAY002', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(3, 9, 1, 1, 5000000.00, 'paid', 'PAY003', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(4, 10, 1, 1, 5000000.00, 'paid', 'PAY004', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(5, 11, 1, 1, 5000000.00, 'paid', 'PAY005', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(6, 17, 2, 1, 5000000.00, 'paid', 'PAY006', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(7, 18, 2, 1, 5000000.00, 'unpaid', 'PAY007', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(8, 19, 2, 1, 5000000.00, 'paid', 'PAY008', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(9, 20, 2, 1, 5000000.00, 'paid', 'PAY009', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(10, 21, 2, 1, 5000000.00, 'unpaid', 'PAY010', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(11, 7, 3, 2, 6000000.00, 'paid', 'PAY011', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(12, 8, 3, 2, 6000000.00, 'unpaid', 'PAY012', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(13, 9, 3, 2, 6000000.00, 'paid', 'PAY013', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(14, 10, 3, 2, 6000000.00, 'paid', 'PAY014', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(15, 11, 3, 2, 6000000.00, 'unpaid', 'PAY015', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(16, 17, 4, 2, 6000000.00, 'paid', 'PAY016', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(17, 18, 4, 2, 6000000.00, 'unpaid', 'PAY017', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(18, 19, 4, 2, 6000000.00, 'paid', 'PAY018', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(19, 20, 4, 2, 6000000.00, 'paid', 'PAY019', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(20, 21, 4, 2, 6000000.00, 'unpaid', 'PAY020', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(21, 7, 5, 3, 10000000.00, 'paid', 'PAY021', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(22, 8, 5, 3, 10000000.00, 'unpaid', 'PAY022', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(23, 9, 5, 3, 10000000.00, 'paid', 'PAY023', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(24, 10, 5, 3, 10000000.00, 'paid', 'PAY024', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(25, 11, 5, 3, 10000000.00, 'unpaid', 'PAY025', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(26, 17, 6, 3, 10000000.00, 'paid', 'PAY026', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(27, 18, 6, 3, 10000000.00, 'unpaid', 'PAY027', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(28, 19, 6, 3, 10000000.00, 'paid', 'PAY028', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(29, 20, 6, 3, 10000000.00, 'paid', 'PAY029', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(30, 21, 6, 3, 10000000.00, 'unpaid', 'PAY030', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(31, 7, 7, 4, 8000000.00, 'paid', 'PAY031', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(32, 8, 7, 4, 8000000.00, 'unpaid', 'PAY032', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(33, 9, 7, 4, 8000000.00, 'paid', 'PAY033', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(34, 10, 7, 4, 8000000.00, 'paid', 'PAY034', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(35, 11, 7, 4, 8000000.00, 'unpaid', 'PAY035', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(36, 12, 8, 4, 8000000.00, 'paid', 'PAY036', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(37, 13, 8, 4, 8000000.00, 'unpaid', 'PAY037', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(38, 14, 8, 4, 8000000.00, 'paid', 'PAY038', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(39, 15, 8, 4, 8000000.00, 'paid', 'PAY039', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(40, 16, 8, 4, 8000000.00, 'unpaid', 'PAY040', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(41, 17, 9, 5, 7000000.00, 'paid', 'PAY041', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(42, 18, 9, 5, 7000000.00, 'unpaid', 'PAY042', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(43, 19, 9, 5, 7000000.00, 'paid', 'PAY043', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(44, 20, 9, 5, 7000000.00, 'paid', 'PAY044', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(45, 21, 9, 5, 7000000.00, 'unpaid', 'PAY045', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(46, 22, 10, 5, 7000000.00, 'paid', 'PAY046', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(47, 23, 10, 5, 7000000.00, 'unpaid', 'PAY047', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(48, 24, 10, 5, 7000000.00, 'paid', 'PAY048', 'Bank Transfer', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(49, 25, 9, 5, 7000000.00, 'paid', 'PAY049', 'Cash', NULL, '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(50, 26, 10, 5, 7000000.00, 'unpaid', 'PAY050', NULL, 'Chưa thanh toán', '2025-06-02 13:34:18', '2025-06-02 13:34:18'),
(51, 12, 1, 1, 5000000.00, 'paid', 'PAYCOD01', 'Tiền mặt', NULL, '2025-06-03 02:54:26', '2025-06-03 02:54:26'),
(52, 13, 1, 1, 5000000.00, 'paid', 'PAYCOD01', 'Tiền mặt', NULL, '2025-06-03 02:55:12', '2025-06-03 02:55:12'),
(53, 14, 1, 1, 5000000.00, 'paid', 'PAYCOD01', 'Tiền mặt', NULL, '2025-06-03 02:55:12', '2025-06-03 02:55:12'),
(54, 15, 1, 1, 5000000.00, 'unpaid', 'PAYCOD01', 'Tiền mặt', NULL, '2025-06-03 02:55:12', '2025-06-03 02:55:12'),
(55, 16, 1, 1, 5000000.00, 'unpaid', 'PAYCOD01', 'Tiền mặt', NULL, '2025-06-03 02:55:12', '2025-06-03 02:55:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `fill_in_blank_answers`
--

CREATE TABLE `fill_in_blank_answers` (
  `id` int(11) NOT NULL,
  `attempt_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `fill_in_blank_questions`
--

CREATE TABLE `fill_in_blank_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `target_role` enum('student','teacher','staff','all') DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `content`, `target_role`, `created_by`, `created_at`) VALUES
(1, 'Khai giảng lớp A1-1', 'Lớp A1-1 sẽ khai giảng ngày 01/06/2025. Vui lòng chuẩn bị.', 'student', 1, '2025-06-02 13:39:20'),
(2, 'Nhắc nhở thanh toán', 'Hạn chót thanh toán học phí lớp A1-2 là 05/06/2025.', 'student', 1, '2025-06-02 13:39:20'),
(3, 'Lịch học mới', 'Lịch học lớp IELTS-1 đã được cập nhật. Kiểm tra chi tiết trên hệ thống.', 'student', 1, '2025-06-02 13:39:20'),
(4, 'Họp giáo viên', 'Cuộc họp giáo viên diễn ra vào 10/06/2025 lúc 15:00.', 'teacher', 1, '2025-06-02 13:39:20'),
(5, 'Cập nhật lương', 'Lương tháng 6/2025 sẽ được chi trả vào 15/07/2025.', 'teacher', 1, '2025-06-02 13:39:20'),
(6, 'Thông báo nghỉ lễ', 'Trung tâm nghỉ lễ từ 30/06/2025 đến 02/07/2025.', 'all', 1, '2025-06-02 13:39:20'),
(7, 'Kiểm tra giữa kỳ', 'Lịch kiểm tra giữa kỳ lớp TOEIC-1 vào 15/07/2025.', 'student', 1, '2025-06-02 13:39:20'),
(8, 'Tuyển dụng nhân viên', 'Trung tâm đang tuyển nhân viên hành chính. Liên hệ để biết thêm chi tiết.', 'staff', 1, '2025-06-02 13:39:20'),
(9, 'Cập nhật phòng học', 'Phòng học lớp Giao tiếp-1 đổi sang Phòng 501 từ 05/06/2025.', 'student', 1, '2025-06-02 13:39:20'),
(10, 'Khảo sát chất lượng', 'Vui lòng tham gia khảo sát chất lượng giảng dạy trước 20/06/2025.', 'all', 1, '2025-06-02 13:39:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `type` enum('single','multiple') DEFAULT 'single',
  `points` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `access_code` varchar(20) DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `duration_minutes` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `submitted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `score` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reorder_answers`
--

CREATE TABLE `reorder_answers` (
  `id` int(11) NOT NULL,
  `attempt_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `user_answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reorder_questions`
--

CREATE TABLE `reorder_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `original_sentence` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reorder_question_parts`
--

CREATE TABLE `reorder_question_parts` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `word` varchar(50) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `day_of_week` enum('Mon','Tue','Wed','Thu','Fri','Sat','Sun') DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `room` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `schedules`
--

INSERT INTO `schedules` (`id`, `class_id`, `day_of_week`, `start_time`, `end_time`, `room`, `created_at`) VALUES
(1, 1, 'Mon', '18:00:00', '20:00:00', 'Phòng 101', '2025-06-02 13:34:18'),
(2, 1, 'Wed', '18:00:00', '20:00:00', 'Phòng 101', '2025-06-02 13:34:18'),
(3, 2, 'Tue', '19:00:00', '21:00:00', 'Phòng 102', '2025-06-02 13:34:18'),
(4, 2, 'Thu', '19:00:00', '21:00:00', 'Phòng 102', '2025-06-02 13:34:18'),
(5, 3, 'Mon', '17:00:00', '19:00:00', 'Phòng 201', '2025-06-02 13:34:18'),
(6, 3, 'Fri', '17:00:00', '19:00:00', 'Phòng 201', '2025-06-02 13:34:18'),
(7, 4, 'Wed', '18:30:00', '20:30:00', 'Phòng 202', '2025-06-02 13:34:18'),
(8, 4, 'Sat', '18:30:00', '20:30:00', 'Phòng 202', '2025-06-02 13:34:18'),
(9, 5, 'Tue', '18:00:00', '20:00:00', 'Phòng 301', '2025-06-02 13:34:18'),
(10, 5, 'Thu', '18:00:00', '20:00:00', 'Phòng 301', '2025-06-02 13:34:18'),
(11, 6, 'Mon', '19:00:00', '21:00:00', 'Phòng 302', '2025-06-02 13:34:18'),
(12, 6, 'Fri', '19:00:00', '21:00:00', 'Phòng 302', '2025-06-02 13:34:18'),
(13, 7, 'Wed', '17:30:00', '19:30:00', 'Phòng 401', '2025-06-02 13:34:18'),
(14, 7, 'Sun', '17:30:00', '19:30:00', 'Phòng 401', '2025-06-02 13:34:18'),
(15, 8, 'Tue', '18:30:00', '20:30:00', 'Phòng 402', '2025-06-02 13:34:18'),
(16, 8, 'Sat', '18:30:00', '20:30:00', 'Phòng 402', '2025-06-02 13:34:18'),
(17, 9, 'Mon', '18:00:00', '20:00:00', 'Phòng 501', '2025-06-02 13:34:18'),
(18, 9, 'Thu', '18:00:00', '20:00:00', 'Phòng 501', '2025-06-02 13:34:18'),
(19, 10, 'Wed', '19:00:00', '21:00:00', 'Phòng 502', '2025-06-02 13:34:18'),
(20, 10, 'Fri', '19:00:00', '21:00:00', 'Phòng 502', '2025-06-02 13:34:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `score_type` enum('oral','15min','midterm','final') DEFAULT NULL,
  `score` float DEFAULT NULL,
  `exam_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `scores`
--

INSERT INTO `scores` (`id`, `student_id`, `class_id`, `score_type`, `score`, `exam_date`, `created_at`) VALUES
(1, 7, 1, 'oral', 8.5, '2025-06-15', '2025-06-02 13:39:20'),
(2, 7, 1, '15min', 7, '2025-06-10', '2025-06-02 13:39:20'),
(3, 8, 1, 'oral', 7.5, '2025-06-15', '2025-06-02 13:39:20'),
(4, 8, 1, '15min', 6.5, '2025-06-10', '2025-06-02 13:39:20'),
(5, 9, 1, 'oral', 9, '2025-06-15', '2025-06-02 13:39:20'),
(6, 10, 1, 'midterm', 8, '2025-07-15', '2025-06-02 13:39:20'),
(7, 11, 1, 'midterm', 7.5, '2025-07-15', '2025-06-02 13:39:20'),
(8, 17, 2, 'oral', 8, '2025-06-17', '2025-06-02 13:39:20'),
(9, 17, 2, '15min', 6, '2025-06-12', '2025-06-02 13:39:20'),
(10, 18, 2, 'oral', 7, '2025-06-17', '2025-06-02 13:39:20'),
(11, 19, 2, 'midterm', 8.5, '2025-07-17', '2025-06-02 13:39:20'),
(12, 7, 3, 'oral', 8.5, '2025-07-20', '2025-06-02 13:39:20'),
(13, 7, 3, '15min', 7.5, '2025-07-10', '2025-06-02 13:39:20'),
(14, 8, 3, 'midterm', 7, '2025-08-15', '2025-06-02 13:39:20'),
(15, 7, 5, 'oral', 9, '2025-07-20', '2025-06-02 13:39:20'),
(16, 8, 5, '15min', 6.5, '2025-07-10', '2025-06-02 13:39:20'),
(17, 9, 5, 'midterm', 8, '2025-08-15', '2025-06-02 13:39:20'),
(18, 7, 7, 'oral', 8, '2025-08-01', '2025-06-02 13:39:20'),
(19, 8, 7, '15min', 7, '2025-07-10', '2025-06-02 13:39:20'),
(20, 9, 7, 'midterm', 7.5, '2025-08-15', '2025-06-02 13:39:20'),
(21, 12, 8, 'oral', 8.5, '2025-08-01', '2025-06-02 13:39:20'),
(22, 13, 8, '15min', 6.5, '2025-07-10', '2025-06-02 13:39:20'),
(23, 14, 8, 'midterm', 8, '2025-08-15', '2025-06-02 13:39:20'),
(24, 17, 9, '', 7.5, '2025-06-15', '2025-06-02 13:39:20'),
(25, 18, 9, '15min', 7, '2025-06-10', '2025-06-02 13:39:20'),
(26, 19, 9, 'midterm', 8.5, '2025-07-15', '2025-06-02 13:39:20'),
(27, 23, 10, '15min', 6.5, '2025-06-10', '2025-06-02 13:39:20'),
(28, 25, 10, 'midterm', 7.5, '2025-07-15', '2025-06-02 13:39:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `student_answers`
--

CREATE TABLE `student_answers` (
  `id` int(11) NOT NULL,
  `attempt_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `teacher_salaries`
--

CREATE TABLE `teacher_salaries` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `total_sessions` int(11) DEFAULT NULL,
  `total_hours` int(11) DEFAULT NULL,
  `total_salary` decimal(12,2) DEFAULT NULL,
  `bonus` decimal(10,2) DEFAULT NULL,
  `penalty` decimal(10,2) DEFAULT NULL,
  `paid` tinyint(1) DEFAULT 0,
  `payment_date` date DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `teacher_salaries`
--

INSERT INTO `teacher_salaries` (`id`, `teacher_id`, `month`, `year`, `total_sessions`, `total_hours`, `total_salary`, `bonus`, `penalty`, `paid`, `payment_date`, `note`, `created_at`) VALUES
(1, 2, 6, 2025, 16, 32, 9600000.00, 1000000.00, 0.00, 1, '2025-07-15', 'Lương tháng 6', '2025-06-02 13:43:09'),
(2, 2, 7, 2025, 16, 32, 9600000.00, 500000.00, 200000.00, 0, NULL, 'Chưa thanh toán', '2025-06-02 13:43:09'),
(3, 3, 6, 2025, 20, 40, 8000000.00, 800000.00, 0.00, 1, '2025-07-15', NULL, '2025-06-02 13:43:09'),
(4, 3, 7, 2025, 10, 20, 4000000.00, 0.00, 0.00, 0, NULL, 'Chưa thanh toán', '2025-06-02 13:43:09'),
(5, 4, 6, 2025, 20, 40, 7200000.00, 600000.00, 0.00, 1, '2025-07-15', 'Lương tháng 6', '2025-06-02 13:43:09'),
(6, 4, 7, 2025, 10, 20, 3600000.00, 0.00, 0.00, 0, NULL, 'Chưa thanh toán', '2025-06-02 13:43:09'),
(7, 5, 6, 2025, 24, 48, 12000000.00, 1000000.00, 0.00, 1, '2025-07-15', NULL, '2025-06-02 13:43:09'),
(8, 5, 7, 2025, 24, 48, 12000000.00, 500000.00, 0.00, 0, NULL, 'Chưa thanh toán', '2025-06-02 13:43:09'),
(9, 6, 6, 2025, 20, 40, 10000000.00, 800000.00, 0.00, 1, '2025-07-15', NULL, '2025-06-02 13:43:09'),
(10, 6, 7, 2025, 10, 20, 5000000.00, 0.00, 0.00, 0, NULL, 'Chưa thanh toán', '2025-06-02 13:43:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `teacher_salary_rules`
--

CREATE TABLE `teacher_salary_rules` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `pay_rate` decimal(10,2) DEFAULT NULL,
  `unit` enum('per_session','per_hour') DEFAULT NULL,
  `effective_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `teacher_salary_rules`
--

INSERT INTO `teacher_salary_rules` (`id`, `teacher_id`, `class_id`, `pay_rate`, `unit`, `effective_date`) VALUES
(1, 2, 1, 300000.00, 'per_hour', '2025-06-01'),
(2, 2, 6, 600000.00, 'per_session', '2025-06-01'),
(3, 3, 2, 200000.00, 'per_hour', '2025-07-01'),
(4, 3, 7, 200000.00, 'per_hour', '2025-07-01'),
(5, 4, 3, 180000.00, 'per_hour', '2025-06-01'),
(6, 4, 8, 180000.00, 'per_hour', '2025-06-01'),
(7, 5, 4, 250000.00, 'per_hour', '2025-06-01'),
(8, 5, 9, 500000.00, 'per_session', '2025-06-01'),
(9, 6, 5, 250000.00, 'per_hour', '2025-06-01'),
(10, 6, 10, 500000.00, 'per_session', '2025-06-01');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `gender`, `birth_date`, `avatar`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '0961469353', '$2y$12$byCzcNtWKHpiXhXPikygouPE6r3Ej88gHCvLQuIvoscqpsszurct.', NULL, NULL, NULL, 'admin', '2025-06-01 07:56:49', '2025-06-01 07:56:49'),
(2, 'Nguyễn Thị Lan', 'lan.teacher@gmail.com', '0912345678', '$2y$12$hashedpassword1', 'female', '1985-03-15', NULL, 'teacher', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(3, 'Trần Văn Hùng', 'hung.teacher@gmail.com', '0923456789', '$2y$12$hashedpassword2', 'male', '1988-07-20', NULL, 'teacher', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(4, 'Phạm Minh Tuấn', 'tuan.teacher@gmail.com', '0934567890', '$2y$12$hashedpassword3', 'male', '1990-11-10', NULL, 'teacher', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(5, 'Lê Thị Mai', 'mai.teacher@gmail.com', '0945678901', '$2y$12$hashedpassword4', 'female', '1987-05-25', NULL, 'teacher', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(6, 'Hoàng Văn Nam', 'nam.teacher@gmail.com', '0956789012', '$2y$12$hashedpassword5', 'male', '1986-09-30', NULL, 'teacher', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(7, 'Nguyễn Văn An', 'an.student@gmail.com', '0967890123', '$2y$12$hashedpassword6', 'male', '2000-01-05', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(8, 'Trần Thị Bình', 'binh.student@gmail.com', '0978901234', '$2y$12$hashedpassword7', 'female', '2001-02-10', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(9, 'Phạm Văn Cường', 'cuong.student@gmail.com', '0989012345', '$2y$12$hashedpassword8', 'male', '1999-03-15', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(10, 'Lê Thị Dung', 'dung.student@gmail.com', '0990123456', '$2y$12$hashedpassword9', 'female', '2000-04-20', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(11, 'Hoàng Văn Em', 'em.student@gmail.com', '0901234567', '$2y$12$hashedpassword10', 'male', '2001-05-25', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(12, 'Nguyễn Thị Hoa', 'hoa.student@gmail.com', '0912345679', '$2y$12$hashedpassword11', 'female', '2000-06-30', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(13, 'Trần Văn Khang', 'khang.student@gmail.com', '0923456780', '$2y$12$hashedpassword12', 'male', '1999-07-05', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(14, 'Phạm Thị Linh', 'linh.student@gmail.com', '0934567891', '$2y$12$hashedpassword13', 'female', '2001-08-10', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(15, 'Lê Văn Minh', 'minh.student@gmail.com', '0945678902', '$2y$12$hashedpassword14', 'male', '2000-09-15', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(16, 'Hoàng Thị Nga', 'nga.student@gmail.com', '0956789013', '$2y$12$hashedpassword15', 'female', '1999-10-20', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(17, 'Nguyễn Văn Phong', 'phong.student@gmail.com', '0967890124', '$2y$12$hashedpassword16', 'male', '2001-11-25', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(18, 'Trần Thị Quyên', 'quyen.student@gmail.com', '0978901235', '$2y$12$hashedpassword17', 'female', '2000-12-30', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(19, 'Phạm Văn Sơn', 'son.student@gmail.com', '0989012346', '$2y$12$hashedpassword18', 'male', '1999-01-05', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(20, 'Lê Thị Thảo', 'thao.student@gmail.com', '0990123457', '$2y$12$hashedpassword19', 'female', '2001-02-10', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(21, 'Hoàng Văn Tùng', 'tung.student@gmail.com', '0901234568', '$2y$12$hashedpassword20', 'male', '2000-03-15', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(22, 'Nguyễn Thị Uyên', 'uyen.student@gmail.com', '0912345670', '$2y$12$hashedpassword21', 'female', '1999-04-20', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(23, 'Trần Văn Vinh', 'vinh.student@gmail.com', '0923456781', '$2y$12$hashedpassword22', 'male', '2001-05-25', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(24, 'Phạm Thị Xuân', 'xuan.student@gmail.com', '0934567892', '$2y$12$hashedpassword23', 'female', '2000-06-30', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(25, 'Lê Văn Ý', 'y.student@gmail.com', '0945678903', '$2y$12$hashedpassword24', 'male', '1999-07-05', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(26, 'Hoàng Thị Z', 'z.student@gmail.com', '0956789014', '$2y$12$hashedpassword25', 'female', '2001-08-10', NULL, 'student', '2025-06-02 13:32:34', '2025-06-02 13:32:34'),
(27, 'Nhân Viên 1', 'nhanvien1@gmail.com', '0987732882', 'nhanvien', 'nữ', '2000-06-01', '', 'staff', '2025-06-02 14:16:04', '2025-06-02 14:16:04');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Chỉ mục cho bảng `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attendances_user` (`user_id`),
  ADD KEY `fk_attendances_schedule` (`schedule_id`);

--
-- Chỉ mục cho bảng `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courses_id` (`courses_id`),
  ADD KEY `fk_classes_teacher` (`teacher_id`);

--
-- Chỉ mục cho bảng `class_student`
--
ALTER TABLE `class_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_class_student_class` (`class_id`),
  ADD KEY `fk_class_student_student` (`student_id`);

--
-- Chỉ mục cho bảng `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `course_payments`
--
ALTER TABLE `course_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Chỉ mục cho bảng `fill_in_blank_answers`
--
ALTER TABLE `fill_in_blank_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attempt_id` (`attempt_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Chỉ mục cho bảng `fill_in_blank_questions`
--
ALTER TABLE `fill_in_blank_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notifications_creator` (`created_by`);

--
-- Chỉ mục cho bảng `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Chỉ mục cho bảng `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Chỉ mục cho bảng `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `reorder_answers`
--
ALTER TABLE `reorder_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attempt_id` (`attempt_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Chỉ mục cho bảng `reorder_questions`
--
ALTER TABLE `reorder_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Chỉ mục cho bảng `reorder_question_parts`
--
ALTER TABLE `reorder_question_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Chỉ mục cho bảng `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Chỉ mục cho bảng `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `fk_scores_student` (`student_id`);

--
-- Chỉ mục cho bảng `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attempt_id` (`attempt_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `answer_id` (`answer_id`);

--
-- Chỉ mục cho bảng `teacher_salaries`
--
ALTER TABLE `teacher_salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_teacher_salaries_teacher` (`teacher_id`);

--
-- Chỉ mục cho bảng `teacher_salary_rules`
--
ALTER TABLE `teacher_salary_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `fk_salary_rules_teacher` (`teacher_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT cho bảng `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `class_student`
--
ALTER TABLE `class_student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT cho bảng `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `course_payments`
--
ALTER TABLE `course_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT cho bảng `fill_in_blank_answers`
--
ALTER TABLE `fill_in_blank_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `fill_in_blank_questions`
--
ALTER TABLE `fill_in_blank_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `reorder_answers`
--
ALTER TABLE `reorder_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `reorder_questions`
--
ALTER TABLE `reorder_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `reorder_question_parts`
--
ALTER TABLE `reorder_question_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `teacher_salaries`
--
ALTER TABLE `teacher_salaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `teacher_salary_rules`
--
ALTER TABLE `teacher_salary_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Các ràng buộc cho bảng `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `fk_attendances_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`),
  ADD CONSTRAINT `fk_attendances_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`courses_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `fk_classes_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `class_student`
--
ALTER TABLE `class_student`
  ADD CONSTRAINT `fk_class_student_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `fk_class_student_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `course_payments`
--
ALTER TABLE `course_payments`
  ADD CONSTRAINT `course_payments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `course_payments_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `course_payments_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Các ràng buộc cho bảng `fill_in_blank_answers`
--
ALTER TABLE `fill_in_blank_answers`
  ADD CONSTRAINT `fill_in_blank_answers_ibfk_1` FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempts` (`id`),
  ADD CONSTRAINT `fill_in_blank_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `fill_in_blank_questions` (`id`);

--
-- Các ràng buộc cho bảng `fill_in_blank_questions`
--
ALTER TABLE `fill_in_blank_questions`
  ADD CONSTRAINT `fill_in_blank_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`);

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`);

--
-- Các ràng buộc cho bảng `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `quizzes_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Các ràng buộc cho bảng `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`),
  ADD CONSTRAINT `quiz_attempts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `reorder_answers`
--
ALTER TABLE `reorder_answers`
  ADD CONSTRAINT `reorder_answers_ibfk_1` FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempts` (`id`),
  ADD CONSTRAINT `reorder_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `reorder_questions` (`id`);

--
-- Các ràng buộc cho bảng `reorder_questions`
--
ALTER TABLE `reorder_questions`
  ADD CONSTRAINT `reorder_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`);

--
-- Các ràng buộc cho bảng `reorder_question_parts`
--
ALTER TABLE `reorder_question_parts`
  ADD CONSTRAINT `reorder_question_parts_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `reorder_questions` (`id`);

--
-- Các ràng buộc cho bảng `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);

--
-- Các ràng buộc cho bảng `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `fk_scores_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);

--
-- Các ràng buộc cho bảng `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempts` (`id`),
  ADD CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `student_answers_ibfk_3` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`);

--
-- Các ràng buộc cho bảng `teacher_salaries`
--
ALTER TABLE `teacher_salaries`
  ADD CONSTRAINT `fk_teacher_salaries_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `teacher_salary_rules`
--
ALTER TABLE `teacher_salary_rules`
  ADD CONSTRAINT `fk_salary_rules_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `teacher_salary_rules_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
