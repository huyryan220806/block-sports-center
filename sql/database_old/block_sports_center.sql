-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2025 at 03:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `block_sports_center`
--

-- --------------------------------------------------------

--
-- Table structure for table `buoilop`
--

CREATE TABLE `buoilop` (
  `MABUOI` int(11) NOT NULL,
  `MALOP` int(11) NOT NULL,
  `MAPHONG` int(11) NOT NULL,
  `MAHLV` int(11) NOT NULL,
  `BATDAU` datetime NOT NULL,
  `KETTHUC` datetime NOT NULL,
  `SISO` int(11) NOT NULL,
  `TRANGTHAI` enum('SCHEDULED','ONGOING','DONE','CANCELLED') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buoilop`
--

INSERT INTO `buoilop` (`MABUOI`, `MALOP`, `MAPHONG`, `MAHLV`, `BATDAU`, `KETTHUC`, `SISO`, `TRANGTHAI`) VALUES
(1, 1, 4, 4, '2025-11-20 08:00:00', '2025-11-20 09:00:00', 20, 'SCHEDULED'),
(2, 1, 4, 4, '2025-11-21 18:00:00', '2025-11-21 19:00:00', 18, 'SCHEDULED'),
(3, 2, 1, 3, '2025-11-20 19:00:00', '2025-11-20 19:45:00', 18, 'SCHEDULED'),
(4, 3, 3, 5, '2025-11-22 07:00:00', '2025-11-22 08:00:00', 12, 'SCHEDULED'),
(5, 2, 2, 3, '2025-11-15 19:00:00', '2025-11-15 19:45:00', 16, 'DONE'),
(6, 1, 5, 4, '2025-11-10 06:00:00', '2025-11-10 07:00:00', 15, 'DONE');

-- --------------------------------------------------------

--
-- Table structure for table `checkin`
--

CREATE TABLE `checkin` (
  `MACI` int(11) NOT NULL,
  `MAHV` int(11) NOT NULL,
  `THOIGIAN` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkin`
--

INSERT INTO `checkin` (`MACI`, `MAHV`, `THOIGIAN`) VALUES
(1, 1, '2025-11-01 09:50:00'),
(2, 1, '2025-11-03 17:55:00'),
(3, 2, '2025-11-05 09:10:00'),
(4, 3, '2024-09-10 18:10:00'),
(5, 6, '2025-03-25 07:05:00'),
(6, 9, '2025-10-21 18:10:00'),
(7, 10, '2025-04-15 19:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `dangky_lop`
--

CREATE TABLE `dangky_lop` (
  `MADK` int(11) NOT NULL,
  `MABUOI` int(11) NOT NULL,
  `MAHV` int(11) NOT NULL,
  `NGAYDK` datetime NOT NULL,
  `TRANGTHAI` enum('BOOKED','ATTENDED','NO_SHOW','CANCELLED') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dangky_lop`
--

INSERT INTO `dangky_lop` (`MADK`, `MABUOI`, `MAHV`, `NGAYDK`, `TRANGTHAI`) VALUES
(1, 1, 1, '2025-11-18 09:00:00', 'BOOKED'),
(2, 1, 2, '2025-11-18 10:00:00', 'BOOKED'),
(3, 1, 6, '2025-11-19 08:30:00', 'BOOKED'),
(4, 3, 1, '2025-11-19 20:00:00', 'BOOKED'),
(5, 3, 3, '2025-11-19 20:05:00', 'BOOKED'),
(6, 4, 2, '2025-11-19 07:30:00', 'BOOKED'),
(7, 5, 5, '2025-11-13 18:00:00', 'ATTENDED'),
(8, 6, 4, '2025-11-08 06:30:00', 'ATTENDED');

-- --------------------------------------------------------

--
-- Table structure for table `datphong`
--

CREATE TABLE `datphong` (
  `MADP` int(11) NOT NULL,
  `MAPHONG` int(11) NOT NULL,
  `MAHV` int(11) DEFAULT NULL,
  `BATDAU` datetime NOT NULL,
  `KETTHUC` datetime NOT NULL,
  `MUCTIEU` enum('TAP_TU_DO','CLB','GIU_CHO_SU_KIEN') NOT NULL,
  `TRANGTHAI` enum('PENDING','CONFIRMED','CANCELLED','DONE') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datphong`
--

INSERT INTO `datphong` (`MADP`, `MAPHONG`, `MAHV`, `BATDAU`, `KETTHUC`, `MUCTIEU`, `TRANGTHAI`) VALUES
(1, 1, 1, '2025-11-22 07:00:00', '2025-11-22 08:30:00', 'TAP_TU_DO', 'CONFIRMED'),
(2, 3, 2, '2025-11-22 17:00:00', '2025-11-22 18:00:00', 'CLB', 'PENDING'),
(3, 6, 9, '2025-11-23 19:00:00', '2025-11-23 20:30:00', 'GIU_CHO_SU_KIEN', 'CONFIRMED'),
(4, 2, 6, '2025-11-20 06:00:00', '2025-11-20 07:00:00', 'TAP_TU_DO', 'DONE');

-- --------------------------------------------------------

--
-- Table structure for table `donghoadon`
--

CREATE TABLE `donghoadon` (
  `MADONG` int(11) NOT NULL,
  `MAHDON` int(11) NOT NULL,
  `LOAIHANG` enum('MEMBERSHIP','CLASS','PT','BOOKING','LOCKER','OTHER') NOT NULL,
  `REF_ID` bigint(20) NOT NULL,
  `MOTA` varchar(255) NOT NULL,
  `SOLUONG` int(11) NOT NULL,
  `DONGIA` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donghoadon`
--

INSERT INTO `donghoadon` (`MADONG`, `MAHDON`, `LOAIHANG`, `REF_ID`, `MOTA`, `SOLUONG`, `DONGIA`) VALUES
(1, 1, 'MEMBERSHIP', 1, 'Gia hạn gói VIP 12 tháng cho Nguyễn Anh Tuấn', 1, 4800000.00),
(2, 1, 'PT', 1, 'Gói 5 buổi PT cho Nguyễn Anh Tuấn', 5, 250000.00),
(3, 2, 'MEMBERSHIP', 2, 'Mua gói STANDARD 3 tháng cho Trần Thị Mai', 1, 1300000.00),
(4, 3, 'MEMBERSHIP', 6, 'Gói STANDARD 3 tháng cho Võ Thanh Thảo', 1, 1300000.00),
(5, 3, 'CLASS', 5, 'Tham gia lớp HIIT Đốt Mỡ (5 buổi)', 5, 80000.00),
(6, 4, 'MEMBERSHIP', 8, 'Gia hạn gói VIP 12 tháng cho Bùi Đức Long', 1, 4800000.00);

-- --------------------------------------------------------

--
-- Table structure for table `hlv`
--

CREATE TABLE `hlv` (
  `MAHLV` int(11) NOT NULL,
  `MOTA` text DEFAULT NULL,
  `PHI_GIO` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hlv`
--

INSERT INTO `hlv` (`MAHLV`, `MOTA`, `PHI_GIO`) VALUES
(3, 'HLV Gym, chuyên tăng cơ, giảm mỡ', 250000.00),
(4, 'HLV Yoga, chuyên Hatha & Gentle Yoga', 220000.00),
(5, 'HLV Bơi, kèm người lớn & trẻ em', 200000.00);

-- --------------------------------------------------------

--
-- Table structure for table `hoadon`
--

CREATE TABLE `hoadon` (
  `MAHDON` int(11) NOT NULL,
  `MAHV` int(11) NOT NULL,
  `MAKM` int(11) DEFAULT NULL,
  `NGAYLAP` datetime NOT NULL,
  `TRANGTHAI` enum('DRAFT','ISSUED','PAID','PARTIAL','VOID') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hoadon`
--

INSERT INTO `hoadon` (`MAHDON`, `MAHV`, `MAKM`, `NGAYLAP`, `TRANGTHAI`) VALUES
(1, 1, 1, '2025-11-01 10:00:00', 'PAID'),
(2, 2, NULL, '2025-11-05 09:30:00', 'ISSUED'),
(3, 6, 2, '2025-11-10 19:15:00', 'PARTIAL'),
(4, 9, NULL, '2025-10-20 18:00:00', 'PAID');

-- --------------------------------------------------------

--
-- Table structure for table `hoivien`
--

CREATE TABLE `hoivien` (
  `MAHV` int(11) NOT NULL,
  `HOVATEN` varchar(100) NOT NULL,
  `GIOITINH` enum('NAM','NU','KHAC') NOT NULL,
  `NGAYSINH` date NOT NULL,
  `SDT` varchar(20) NOT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `DIACHI` varchar(255) DEFAULT NULL,
  `TRANGTHAI` enum('ACTIVE','SUSPENDED','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `NGAYTAO` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hoivien`
--

INSERT INTO `hoivien` (`MAHV`, `HOVATEN`, `GIOITINH`, `NGAYSINH`, `SDT`, `EMAIL`, `DIACHI`, `TRANGTHAI`, `NGAYTAO`) VALUES
(1, 'Nguyễn Anh Tuấn', 'NAM', '1998-05-12', '0912000001', 'tuan.nguyen@example.com', 'Biên Hòa, Đồng Nai', 'ACTIVE', '2025-01-05 09:15:00'),
(2, 'Trần Thị Mai', 'NU', '2000-08-22', '0912000002', 'mai.tran@example.com', 'Biên Hòa, Đồng Nai', 'ACTIVE', '2025-02-10 10:00:00'),
(3, 'Lê Quốc Huy', 'NAM', '1995-11-03', '0912000003', 'huy.le@example.com', 'TP. HCM', 'ACTIVE', '2024-09-01 18:30:00'),
(4, 'Phạm Ngọc Bích', 'NU', '1999-02-14', '0912000004', 'bich.pham@example.com', 'Long Thành, Đồng Nai', 'ACTIVE', '2025-03-01 08:45:00'),
(5, 'Đỗ Minh Khoa', 'NAM', '1997-07-19', '0912000005', 'khoa.do@example.com', 'Biên Hòa, Đồng Nai', 'SUSPENDED', '2024-10-10 19:00:00'),
(6, 'Võ Thanh Thảo', 'NU', '2001-01-30', '0912000006', 'thao.vo@example.com', 'Vũng Tàu', 'ACTIVE', '2025-03-20 07:20:00'),
(7, 'Huỳnh Gia Bảo', 'NAM', '2003-09-09', '0912000007', 'bao.huynh@example.com', 'Biên Hòa, Đồng Nai', 'ACTIVE', '2025-04-02 12:10:00'),
(8, 'Ngô Hồng Nhung', 'NU', '1996-06-05', '0912000008', 'nhung.ngo@example.com', 'TP. HCM', 'INACTIVE', '2024-07-15 11:00:00'),
(9, 'Bùi Đức Long', 'NAM', '1994-03-27', '0912000009', 'long.bui@example.com', 'Biên Hòa, Đồng Nai', 'ACTIVE', '2025-02-18 17:05:00'),
(10, 'Phan Mỹ Linh', 'NU', '2002-12-11', '0912000010', 'linh.phan@example.com', 'Trảng Bom, Đồng Nai', 'ACTIVE', '2025-04-10 09:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `hopdong`
--

CREATE TABLE `hopdong` (
  `MAHD` int(11) NOT NULL,
  `MAHV` int(11) NOT NULL,
  `MALG` int(11) NOT NULL,
  `NGAYBD` date NOT NULL,
  `NGAYKT` date NOT NULL,
  `TRANGTHAI` enum('ACTIVE','PAUSED','EXPIRED','CANCELLED') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hopdong`
--

INSERT INTO `hopdong` (`MAHD`, `MAHV`, `MALG`, `NGAYBD`, `NGAYKT`, `TRANGTHAI`) VALUES
(1, 1, 3, '2025-01-05', '2025-12-31', 'ACTIVE'),
(2, 2, 2, '2025-03-01', '2025-05-30', 'ACTIVE'),
(3, 3, 1, '2024-09-01', '2024-09-30', 'EXPIRED'),
(4, 4, 2, '2025-02-15', '2025-05-15', 'PAUSED'),
(5, 5, 1, '2024-10-10', '2024-11-09', 'EXPIRED'),
(6, 6, 2, '2025-03-20', '2025-06-18', 'ACTIVE'),
(7, 7, 1, '2025-04-02', '2025-05-01', 'ACTIVE'),
(8, 9, 3, '2025-02-18', '2026-02-17', 'ACTIVE'),
(9, 10, 1, '2025-04-10', '2025-05-09', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `khu`
--

CREATE TABLE `khu` (
  `MAKHU` int(11) NOT NULL,
  `TENKHU` varchar(100) NOT NULL,
  `LOAIKHU` enum('GYM','POOL','STUDIO','COURT') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khu`
--

INSERT INTO `khu` (`MAKHU`, `TENKHU`, `LOAIKHU`) VALUES
(1, 'Khu Gym Tổng Hợp', 'GYM'),
(2, 'Khu Hồ Bơi', 'POOL'),
(3, 'Studio Yoga & GroupX', 'STUDIO'),
(4, 'Sân Cầu Lông', 'COURT');

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `MAKM` int(11) NOT NULL,
  `CODE` varchar(50) NOT NULL,
  `LOAI` enum('PERCENT','AMOUNT') NOT NULL,
  `GIATRI` decimal(12,2) NOT NULL,
  `NGAYBD` date NOT NULL,
  `NGAYKT` date NOT NULL,
  `MOTA` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khuyenmai`
--

INSERT INTO `khuyenmai` (`MAKM`, `CODE`, `LOAI`, `GIATRI`, `NGAYBD`, `NGAYKT`, `MOTA`) VALUES
(1, 'WELCOME10', 'PERCENT', 10.00, '2025-01-01', '2025-12-31', 'Giảm 10% cho hội viên mới'),
(2, 'VIP200K', 'AMOUNT', 200000.00, '2025-02-01', '2025-12-31', 'Giảm 200k cho gói VIP 12 tháng');

-- --------------------------------------------------------

--
-- Table structure for table `loaigoi`
--

CREATE TABLE `loaigoi` (
  `MALG` int(11) NOT NULL,
  `TENLG` varchar(100) NOT NULL,
  `THOIHAN` int(11) NOT NULL,
  `GIA` decimal(12,2) NOT NULL,
  `CAPDO` enum('BASIC','STANDARD','VIP') NOT NULL,
  `MOTA` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loaigoi`
--

INSERT INTO `loaigoi` (`MALG`, `TENLG`, `THOIHAN`, `GIA`, `CAPDO`, `MOTA`) VALUES
(1, 'Gói 1 tháng BASIC', 30, 500000.00, 'BASIC', 'Sử dụng phòng gym + khu cardio giờ hành chính'),
(2, 'Gói 3 tháng STANDARD', 90, 1300000.00, 'STANDARD', 'Gym + lớp groupX cơ bản, sử dụng cả ngày'),
(3, 'Gói 12 tháng VIP', 365, 4800000.00, 'VIP', 'Toàn bộ tiện ích, tặng kèm 5 buổi PT');

-- --------------------------------------------------------

--
-- Table structure for table `locker`
--

CREATE TABLE `locker` (
  `MATU` int(11) NOT NULL,
  `MAPHONG` int(11) NOT NULL,
  `KITU` varchar(20) NOT NULL,
  `HOATDONG` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locker`
--

INSERT INTO `locker` (`MATU`, `MAPHONG`, `KITU`, `HOATDONG`) VALUES
(1, 1, 'G1-01', 1),
(2, 1, 'G1-02', 1),
(3, 2, 'G2-01', 1),
(4, 3, 'P-01', 1),
(5, 3, 'P-02', 1),
(6, 4, 'Y-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lop`
--

CREATE TABLE `lop` (
  `MALOP` int(11) NOT NULL,
  `TENLOP` varchar(100) NOT NULL,
  `THOILUONG` int(11) NOT NULL,
  `SISO_MACDINH` int(11) NOT NULL,
  `MOTA` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lop`
--

INSERT INTO `lop` (`MALOP`, `TENLOP`, `THOILUONG`, `SISO_MACDINH`, `MOTA`) VALUES
(1, 'Yoga Cơ Bản', 60, 20, 'Lớp yoga dành cho người mới bắt đầu'),
(2, 'HIIT Đốt Mỡ', 45, 18, 'Lớp cường độ cao, tiêu hao calo nhanh'),
(3, 'Bơi Người Lớn Cơ Bản', 60, 15, 'Dạy bơi cho người lớn sợ nước'),
(4, 'BodyPump Tạ Nhóm', 50, 22, 'Luyện tập với tạ theo nhóm');

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `MANV` int(11) NOT NULL,
  `HOTEN` varchar(100) NOT NULL,
  `SDT` varchar(20) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `VAITRO` enum('ADMIN','FRONTDESK','MAINTENANCE','OTHER') NOT NULL,
  `NGAYVAOLAM` date NOT NULL,
  `TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`MANV`, `HOTEN`, `SDT`, `EMAIL`, `VAITRO`, `NGAYVAOLAM`, `TRANGTHAI`) VALUES
(1, 'Nguyễn Văn Quản Lý', '0901000001', 'quanly@blocksc.vn', 'ADMIN', '2024-01-10', 1),
(2, 'Trần Thị Lễ Tân', '0901000002', 'letan@blocksc.vn', 'FRONTDESK', '2024-02-01', 1),
(3, 'Lê Minh Trainer Gym', '0901000003', 'trainer.gym@blocksc.vn', 'OTHER', '2024-03-05', 1),
(4, 'Phạm Lan Yoga', '0901000004', 'trainer.yoga@blocksc.vn', 'OTHER', '2024-03-05', 1),
(5, 'Đỗ Hải Bơi', '0901000005', 'trainer.swim@blocksc.vn', 'OTHER', '2024-04-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `phong`
--

CREATE TABLE `phong` (
  `MAPHONG` int(11) NOT NULL,
  `MAKHU` int(11) NOT NULL,
  `TENPHONG` varchar(100) NOT NULL,
  `SUCCHUA` int(11) NOT NULL,
  `GHICHU` text DEFAULT NULL,
  `HOATDONG` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phong`
--

INSERT INTO `phong` (`MAPHONG`, `MAKHU`, `TENPHONG`, `SUCCHUA`, `GHICHU`, `HOATDONG`) VALUES
(1, 1, 'Phòng Gym 1', 40, 'Khu máy chạy & tạ tự do', 1),
(2, 1, 'Phòng Gym 2', 30, 'Máy cardio & khu functional', 1),
(3, 2, 'Hồ Bơi 4 làn', 60, 'Hồ bơi nước ấm', 1),
(4, 3, 'Studio A', 25, 'Phòng Yoga, Dance', 1),
(5, 3, 'Studio B', 20, 'Class cường độ trung bình', 1),
(6, 4, 'Sân Cầu Lông 1', 8, 'Sân cầu lông trong nhà', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pt_session`
--

CREATE TABLE `pt_session` (
  `MAPT` int(11) NOT NULL,
  `MAHLV` int(11) NOT NULL,
  `MAHV` int(11) NOT NULL,
  `MAPHONG` int(11) NOT NULL,
  `BATDAU` datetime NOT NULL,
  `KETTHUC` datetime NOT NULL,
  `TRANGTHAI` enum('SCHEDULED','DONE','CANCELLED','NO_SHOW') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pt_session`
--

INSERT INTO `pt_session` (`MAPT`, `MAHLV`, `MAHV`, `MAPHONG`, `BATDAU`, `KETTHUC`, `TRANGTHAI`) VALUES
(1, 3, 1, 1, '2025-11-03 18:00:00', '2025-11-03 19:00:00', 'DONE'),
(2, 3, 1, 1, '2025-11-07 18:00:00', '2025-11-07 19:00:00', 'DONE'),
(3, 3, 6, 2, '2025-11-15 17:00:00', '2025-11-15 18:00:00', 'SCHEDULED'),
(4, 4, 4, 4, '2025-11-21 06:00:00', '2025-11-21 07:00:00', 'SCHEDULED');

-- --------------------------------------------------------

--
-- Table structure for table `thanhtoan`
--

CREATE TABLE `thanhtoan` (
  `MATTTOAN` int(11) NOT NULL,
  `MAHDON` int(11) NOT NULL,
  `SOTIEN` decimal(12,2) NOT NULL,
  `NGAYTT` datetime NOT NULL,
  `PHUONGTHUC` enum('CASH','CARD','BANK','EWALLET') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thanhtoan`
--

INSERT INTO `thanhtoan` (`MATTTOAN`, `MAHDON`, `SOTIEN`, `NGAYTT`, `PHUONGTHUC`) VALUES
(1, 1, 5445000.00, '2025-11-01 10:05:00', 'CARD'),
(2, 3, 800000.00, '2025-11-10 19:20:00', 'CASH'),
(3, 3, 700000.00, '2025-11-20 18:00:00', 'BANK'),
(4, 4, 4800000.00, '2025-10-20 18:05:00', 'EWALLET');

-- --------------------------------------------------------

--
-- Table structure for table `thuetu`
--

CREATE TABLE `thuetu` (
  `MATT` int(11) NOT NULL,
  `MATU` int(11) NOT NULL,
  `MAHV` int(11) NOT NULL,
  `NGAYBD` date NOT NULL,
  `NGAYKT` date NOT NULL,
  `TRANGTHAI` enum('ACTIVE','EXPIRED','CANCELLED') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thuetu`
--

INSERT INTO `thuetu` (`MATT`, `MATU`, `MAHV`, `NGAYBD`, `NGAYKT`, `TRANGTHAI`) VALUES
(1, 1, 1, '2025-01-05', '2025-02-04', 'EXPIRED'),
(2, 2, 2, '2025-03-01', '2025-04-01', 'ACTIVE'),
(3, 4, 6, '2025-03-20', '2025-04-20', 'CANCELLED'),
(4, 5, 9, '2025-02-18', '2025-03-18', 'EXPIRED');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buoilop`
--
ALTER TABLE `buoilop`
  ADD PRIMARY KEY (`MABUOI`),
  ADD KEY `fk_buoilop_lop` (`MALOP`),
  ADD KEY `fk_buoilop_phong` (`MAPHONG`),
  ADD KEY `fk_buoilop_hlv` (`MAHLV`);

--
-- Indexes for table `checkin`
--
ALTER TABLE `checkin`
  ADD PRIMARY KEY (`MACI`),
  ADD KEY `fk_checkin_hoivien` (`MAHV`);

--
-- Indexes for table `dangky_lop`
--
ALTER TABLE `dangky_lop`
  ADD PRIMARY KEY (`MADK`),
  ADD UNIQUE KEY `uq_dk_buoilop_hoivien` (`MABUOI`,`MAHV`),
  ADD KEY `fk_dk_hoivien` (`MAHV`);

--
-- Indexes for table `datphong`
--
ALTER TABLE `datphong`
  ADD PRIMARY KEY (`MADP`),
  ADD KEY `fk_datphong_phong` (`MAPHONG`),
  ADD KEY `fk_datphong_hoivien` (`MAHV`);

--
-- Indexes for table `donghoadon`
--
ALTER TABLE `donghoadon`
  ADD PRIMARY KEY (`MADONG`),
  ADD KEY `fk_donghoadon_hoadon` (`MAHDON`);

--
-- Indexes for table `hlv`
--
ALTER TABLE `hlv`
  ADD PRIMARY KEY (`MAHLV`);

--
-- Indexes for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`MAHDON`),
  ADD KEY `fk_hoadon_hoivien` (`MAHV`),
  ADD KEY `fk_hoadon_khuyenmai` (`MAKM`);

--
-- Indexes for table `hoivien`
--
ALTER TABLE `hoivien`
  ADD PRIMARY KEY (`MAHV`),
  ADD UNIQUE KEY `SDT` (`SDT`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- Indexes for table `hopdong`
--
ALTER TABLE `hopdong`
  ADD PRIMARY KEY (`MAHD`),
  ADD KEY `fk_hopdong_hoivien` (`MAHV`),
  ADD KEY `fk_hopdong_loaigoi` (`MALG`);

--
-- Indexes for table `khu`
--
ALTER TABLE `khu`
  ADD PRIMARY KEY (`MAKHU`);

--
-- Indexes for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD PRIMARY KEY (`MAKM`),
  ADD UNIQUE KEY `CODE` (`CODE`);

--
-- Indexes for table `loaigoi`
--
ALTER TABLE `loaigoi`
  ADD PRIMARY KEY (`MALG`);

--
-- Indexes for table `locker`
--
ALTER TABLE `locker`
  ADD PRIMARY KEY (`MATU`),
  ADD KEY `fk_locker_phong` (`MAPHONG`);

--
-- Indexes for table `lop`
--
ALTER TABLE `lop`
  ADD PRIMARY KEY (`MALOP`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`MANV`),
  ADD UNIQUE KEY `SDT` (`SDT`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- Indexes for table `phong`
--
ALTER TABLE `phong`
  ADD PRIMARY KEY (`MAPHONG`),
  ADD KEY `fk_phong_khu` (`MAKHU`);

--
-- Indexes for table `pt_session`
--
ALTER TABLE `pt_session`
  ADD PRIMARY KEY (`MAPT`),
  ADD KEY `fk_pt_hlv` (`MAHLV`),
  ADD KEY `fk_pt_hoivien` (`MAHV`),
  ADD KEY `fk_pt_phong` (`MAPHONG`);

--
-- Indexes for table `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD PRIMARY KEY (`MATTTOAN`),
  ADD KEY `fk_thanhtoan_hoadon` (`MAHDON`);

--
-- Indexes for table `thuetu`
--
ALTER TABLE `thuetu`
  ADD PRIMARY KEY (`MATT`),
  ADD KEY `fk_thuetu_locker` (`MATU`),
  ADD KEY `fk_thuetu_hoivien` (`MAHV`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buoilop`
--
ALTER TABLE `buoilop`
  MODIFY `MABUOI` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `checkin`
--
ALTER TABLE `checkin`
  MODIFY `MACI` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dangky_lop`
--
ALTER TABLE `dangky_lop`
  MODIFY `MADK` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `datphong`
--
ALTER TABLE `datphong`
  MODIFY `MADP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donghoadon`
--
ALTER TABLE `donghoadon`
  MODIFY `MADONG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hoadon`
--
ALTER TABLE `hoadon`
  MODIFY `MAHDON` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hoivien`
--
ALTER TABLE `hoivien`
  MODIFY `MAHV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hopdong`
--
ALTER TABLE `hopdong`
  MODIFY `MAHD` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `khu`
--
ALTER TABLE `khu`
  MODIFY `MAKHU` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `MAKM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `loaigoi`
--
ALTER TABLE `loaigoi`
  MODIFY `MALG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `locker`
--
ALTER TABLE `locker`
  MODIFY `MATU` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lop`
--
ALTER TABLE `lop`
  MODIFY `MALOP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `MANV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `phong`
--
ALTER TABLE `phong`
  MODIFY `MAPHONG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pt_session`
--
ALTER TABLE `pt_session`
  MODIFY `MAPT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `thanhtoan`
--
ALTER TABLE `thanhtoan`
  MODIFY `MATTTOAN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `thuetu`
--
ALTER TABLE `thuetu`
  MODIFY `MATT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buoilop`
--
ALTER TABLE `buoilop`
  ADD CONSTRAINT `fk_buoilop_hlv` FOREIGN KEY (`MAHLV`) REFERENCES `hlv` (`MAHLV`),
  ADD CONSTRAINT `fk_buoilop_lop` FOREIGN KEY (`MALOP`) REFERENCES `lop` (`MALOP`),
  ADD CONSTRAINT `fk_buoilop_phong` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`);

--
-- Constraints for table `checkin`
--
ALTER TABLE `checkin`
  ADD CONSTRAINT `fk_checkin_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`);

--
-- Constraints for table `dangky_lop`
--
ALTER TABLE `dangky_lop`
  ADD CONSTRAINT `fk_dk_buoilop` FOREIGN KEY (`MABUOI`) REFERENCES `buoilop` (`MABUOI`),
  ADD CONSTRAINT `fk_dk_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`);

--
-- Constraints for table `datphong`
--
ALTER TABLE `datphong`
  ADD CONSTRAINT `fk_datphong_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`),
  ADD CONSTRAINT `fk_datphong_phong` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`);

--
-- Constraints for table `donghoadon`
--
ALTER TABLE `donghoadon`
  ADD CONSTRAINT `fk_donghoadon_hoadon` FOREIGN KEY (`MAHDON`) REFERENCES `hoadon` (`MAHDON`);

--
-- Constraints for table `hlv`
--
ALTER TABLE `hlv`
  ADD CONSTRAINT `fk_hlv_nhanvien` FOREIGN KEY (`MAHLV`) REFERENCES `nhanvien` (`MANV`);

--
-- Constraints for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `fk_hoadon_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`),
  ADD CONSTRAINT `fk_hoadon_khuyenmai` FOREIGN KEY (`MAKM`) REFERENCES `khuyenmai` (`MAKM`);

--
-- Constraints for table `hopdong`
--
ALTER TABLE `hopdong`
  ADD CONSTRAINT `fk_hopdong_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`),
  ADD CONSTRAINT `fk_hopdong_loaigoi` FOREIGN KEY (`MALG`) REFERENCES `loaigoi` (`MALG`);

--
-- Constraints for table `locker`
--
ALTER TABLE `locker`
  ADD CONSTRAINT `fk_locker_phong` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`);

--
-- Constraints for table `phong`
--
ALTER TABLE `phong`
  ADD CONSTRAINT `fk_phong_khu` FOREIGN KEY (`MAKHU`) REFERENCES `khu` (`MAKHU`);

--
-- Constraints for table `pt_session`
--
ALTER TABLE `pt_session`
  ADD CONSTRAINT `fk_pt_hlv` FOREIGN KEY (`MAHLV`) REFERENCES `hlv` (`MAHLV`),
  ADD CONSTRAINT `fk_pt_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`),
  ADD CONSTRAINT `fk_pt_phong` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`);

--
-- Constraints for table `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD CONSTRAINT `fk_thanhtoan_hoadon` FOREIGN KEY (`MAHDON`) REFERENCES `hoadon` (`MAHDON`);

--
-- Constraints for table `thuetu`
--
ALTER TABLE `thuetu`
  ADD CONSTRAINT `fk_thuetu_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`),
  ADD CONSTRAINT `fk_thuetu_locker` FOREIGN KEY (`MATU`) REFERENCES `locker` (`MATU`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
