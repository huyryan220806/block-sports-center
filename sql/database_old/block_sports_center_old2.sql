-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2025 at 07:50 PM
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
  `MABUOI` int(10) UNSIGNED NOT NULL,
  `MALOP` int(10) UNSIGNED NOT NULL,
  `MAPHONG` int(10) UNSIGNED NOT NULL,
  `MAHLV` int(10) UNSIGNED NOT NULL,
  `BATDAU` datetime NOT NULL,
  `KETTHUC` datetime NOT NULL,
  `SISO` int(10) UNSIGNED NOT NULL,
  `TRANGTHAI` enum('SCHEDULED','ONGOING','DONE','CANCELLED') NOT NULL DEFAULT 'SCHEDULED'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buoilop`
--

INSERT INTO `buoilop` (`MABUOI`, `MALOP`, `MAPHONG`, `MAHLV`, `BATDAU`, `KETTHUC`, `SISO`, `TRANGTHAI`) VALUES
(1, 1, 4, 4, '2025-01-05 18:00:00', '2025-01-05 19:00:00', 20, 'SCHEDULED'),
(2, 2, 1, 3, '2025-01-06 19:00:00', '2025-01-06 19:45:00', 18, 'SCHEDULED'),
(3, 3, 3, 3, '2025-01-07 07:00:00', '2025-01-07 08:00:00', 15, 'SCHEDULED');

-- --------------------------------------------------------

--
-- Table structure for table `checkin`
--

CREATE TABLE `checkin` (
  `MACI` int(10) UNSIGNED NOT NULL,
  `MAHV` int(10) UNSIGNED NOT NULL,
  `THOIGIAN` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkin`
--

INSERT INTO `checkin` (`MACI`, `MAHV`, `THOIGIAN`) VALUES
(1, 1, '2025-01-03 17:05:00'),
(2, 2, '2025-01-03 18:10:00'),
(3, 1, '2025-01-04 07:55:00');

-- --------------------------------------------------------

--
-- Table structure for table `dangky_lop`
--

CREATE TABLE `dangky_lop` (
  `MADK` int(10) UNSIGNED NOT NULL,
  `MABUOI` int(10) UNSIGNED NOT NULL,
  `MAHV` int(10) UNSIGNED NOT NULL,
  `NGAYDK` datetime NOT NULL DEFAULT current_timestamp(),
  `TRANGTHAI` enum('BOOKED','ATTENDED','NO_SHOW','CANCELLED') NOT NULL DEFAULT 'BOOKED'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dangky_lop`
--

INSERT INTO `dangky_lop` (`MADK`, `MABUOI`, `MAHV`, `NGAYDK`, `TRANGTHAI`) VALUES
(1, 1, 1, '2024-12-25 10:00:00', 'BOOKED'),
(2, 1, 2, '2024-12-26 11:00:00', 'BOOKED'),
(3, 2, 1, '2024-12-28 09:00:00', 'BOOKED');

-- --------------------------------------------------------

--
-- Table structure for table `datphong`
--

CREATE TABLE `datphong` (
  `MADP` int(10) UNSIGNED NOT NULL,
  `MAPHONG` int(10) UNSIGNED NOT NULL,
  `MAHV` int(10) UNSIGNED DEFAULT NULL,
  `BATDAU` datetime NOT NULL,
  `KETTHUC` datetime NOT NULL,
  `MUCTIEU` enum('TAP_TU_DO','CLB','GIU_CHO_SU_KIEN','KHAC') NOT NULL DEFAULT 'TAP_TU_DO',
  `TRANGTHAI` enum('PENDING','CONFIRMED','CANCELLED','DONE') NOT NULL DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datphong`
--

INSERT INTO `datphong` (`MADP`, `MAPHONG`, `MAHV`, `BATDAU`, `KETTHUC`, `MUCTIEU`, `TRANGTHAI`) VALUES
(8, 1, 1, '2025-11-22 07:00:00', '2025-11-22 08:30:00', 'TAP_TU_DO', 'CONFIRMED'),
(9, 2, 2, '2025-11-22 17:00:00', '2025-11-22 18:15:00', 'CLB', 'PENDING'),
(10, 3, 3, '2025-11-23 19:00:00', '2025-11-23 20:00:00', 'GIU_CHO_SU_KIEN', 'CONFIRMED'),
(11, 4, 4, '2025-11-20 06:00:00', '2025-11-20 07:00:00', 'TAP_TU_DO', 'DONE');

-- --------------------------------------------------------

--
-- Table structure for table `donghoadon`
--

CREATE TABLE `donghoadon` (
  `MADONG` int(10) UNSIGNED NOT NULL,
  `MAHDON` int(10) UNSIGNED NOT NULL,
  `LOAIHANG` enum('MEMBERSHIP','CLASS','PT','BOOKING','LOCKER','OTHER') NOT NULL,
  `REF_ID` int(10) UNSIGNED DEFAULT NULL,
  `MOTA` varchar(255) DEFAULT NULL,
  `SOLUONG` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `DONGIA` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donghoadon`
--

INSERT INTO `donghoadon` (`MADONG`, `MAHDON`, `LOAIHANG`, `REF_ID`, `MOTA`, `SOLUONG`, `DONGIA`) VALUES
(1, 1, 'MEMBERSHIP', 1, 'Hợp đồng 3 tháng cho Nguyễn Văn A', 1, 1300000.00),
(2, 1, 'LOCKER', 1, 'Thuê tủ G1-01 trong 1 tháng', 1, 100000.00),
(3, 2, 'MEMBERSHIP', 2, 'Hợp đồng 1 tháng cho Trần Thị B', 1, 500000.00);

-- --------------------------------------------------------

--
-- Table structure for table `hlv`
--

CREATE TABLE `hlv` (
  `MAHLV` int(10) UNSIGNED NOT NULL,
  `MOTA` text DEFAULT NULL,
  `PHI_GIO` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hlv`
--

INSERT INTO `hlv` (`MAHLV`, `MOTA`, `PHI_GIO`) VALUES
(3, 'HLV Gym chuyên tăng cơ, giảm mỡ', 200000.00),
(4, 'HLV Yoga, thiền, giãn cơ', 250000.00);

-- --------------------------------------------------------

--
-- Table structure for table `hoadon`
--

CREATE TABLE `hoadon` (
  `MAHDON` int(10) UNSIGNED NOT NULL,
  `MAHV` int(10) UNSIGNED NOT NULL,
  `MAKM` int(10) UNSIGNED DEFAULT NULL,
  `NGAYLAP` datetime NOT NULL DEFAULT current_timestamp(),
  `TRANGTHAI` enum('DRAFT','ISSUED','PAID','PARTIAL','VOID') NOT NULL DEFAULT 'DRAFT'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hoadon`
--

INSERT INTO `hoadon` (`MAHDON`, `MAHV`, `MAKM`, `NGAYLAP`, `TRANGTHAI`) VALUES
(1, 1, 1, '2024-12-30 09:00:00', 'PAID'),
(2, 2, NULL, '2025-01-02 14:30:00', 'ISSUED');

-- --------------------------------------------------------

--
-- Table structure for table `hoivien`
--

CREATE TABLE `hoivien` (
  `MAHV` int(10) UNSIGNED NOT NULL,
  `HOVATEN` varchar(100) NOT NULL,
  `GIOITINH` enum('Nam','Nữ','Khác') NOT NULL DEFAULT 'Nam',
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
(1, 'Nguyễn Văn A', 'Nam', '2000-01-15', '0901111111', 'a@example.com', 'Biên Hòa, Đồng Nai', 'ACTIVE', '2025-11-17 20:56:39'),
(2, 'Trần Thị B', 'Nữ', '1999-05-20', '0902222222', 'b@example.com', 'Thủ Đức, TP.HCM', 'ACTIVE', '2025-11-17 20:56:39'),
(3, 'Lê Minh C', 'Nam', '2001-09-10', '0903333333', 'cc@gmail.com', 'Long Thành, Đồng Nai', 'INACTIVE', '2025-11-17 20:56:39');

-- --------------------------------------------------------

--
-- Table structure for table `hopdong`
--

CREATE TABLE `hopdong` (
  `MAHD` int(10) UNSIGNED NOT NULL,
  `MAHV` int(10) UNSIGNED NOT NULL,
  `MALG` int(10) UNSIGNED NOT NULL,
  `NGAYBD` date NOT NULL,
  `NGAYKT` date NOT NULL,
  `TRANGTHAI` enum('ACTIVE','PAUSED','EXPIRED','CANCELLED') NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hopdong`
--

INSERT INTO `hopdong` (`MAHD`, `MAHV`, `MALG`, `NGAYBD`, `NGAYKT`, `TRANGTHAI`) VALUES
(1, 1, 2, '2025-01-01', '2025-03-31', 'ACTIVE'),
(2, 2, 1, '2025-01-10', '2025-02-09', 'ACTIVE'),
(3, 3, 3, '2024-01-01', '2024-12-31', 'EXPIRED');

-- --------------------------------------------------------

--
-- Table structure for table `khu`
--

CREATE TABLE `khu` (
  `MAKHU` int(10) UNSIGNED NOT NULL,
  `TENKHU` varchar(100) NOT NULL,
  `LOAIKHU` enum('GYM','POOL','STUDIO','COURT','OTHER') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khu`
--

INSERT INTO `khu` (`MAKHU`, `TENKHU`, `LOAIKHU`) VALUES
(1, 'Khu Gym Tổng Hợp', 'GYM'),
(2, 'Khu Hồ Bơi Ngoài Trời', 'POOL'),
(3, 'Phòng Yoga Tầng 2', 'STUDIO');

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `MAKM` int(10) UNSIGNED NOT NULL,
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
(1, 'NEWYEAR10', 'PERCENT', 10.00, '2024-12-25', '2025-01-10', 'Giảm 10% dịp năm mới'),
(2, 'WELCOME100', 'AMOUNT', 100000.00, '2024-11-01', '2025-03-31', 'Giảm 100k cho hợp đồng đầu tiên');

-- --------------------------------------------------------

--
-- Table structure for table `loaigoi`
--

CREATE TABLE `loaigoi` (
  `MALG` int(10) UNSIGNED NOT NULL,
  `TENLG` varchar(100) NOT NULL,
  `THOIHAN` int(10) UNSIGNED NOT NULL,
  `GIA` decimal(12,2) NOT NULL,
  `CAPDO` enum('BASIC','STANDARD','VIP') NOT NULL,
  `MOTA` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loaigoi`
--

INSERT INTO `loaigoi` (`MALG`, `TENLG`, `THOIHAN`, `GIA`, `CAPDO`, `MOTA`) VALUES
(1, 'Gói 1 tháng', 30, 500000.00, 'BASIC', 'Gói cơ bản 1 tháng'),
(2, 'Gói 3 tháng', 90, 1300000.00, 'STANDARD', 'Tiết kiệm hơn khi tập 3 tháng'),
(3, 'Gói 12 tháng', 365, 4500000.00, 'VIP', 'Gói VIP 12 tháng full dịch vụ');

-- --------------------------------------------------------

--
-- Table structure for table `locker`
--

CREATE TABLE `locker` (
  `MATU` int(10) UNSIGNED NOT NULL,
  `MAPHONG` int(10) UNSIGNED NOT NULL,
  `KITU` varchar(20) NOT NULL,
  `HOATDONG` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locker`
--

INSERT INTO `locker` (`MATU`, `MAPHONG`, `KITU`, `HOATDONG`) VALUES
(1, 1, 'G1-01', 1),
(2, 1, 'G1-02', 1),
(3, 3, 'P-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lop`
--

CREATE TABLE `lop` (
  `MALOP` int(10) UNSIGNED NOT NULL,
  `TENLOP` varchar(100) NOT NULL,
  `THOILUONG` int(10) UNSIGNED NOT NULL,
  `SISO_MACDINH` int(10) UNSIGNED NOT NULL,
  `MOTA` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lop`
--

INSERT INTO `lop` (`MALOP`, `TENLOP`, `THOILUONG`, `SISO_MACDINH`, `MOTA`) VALUES
(1, 'Yoga Cơ Bản', 60, 20, 'Lớp yoga cho người mới bắt đầu'),
(2, 'HIIT Giảm Mỡ', 45, 18, 'Cardio cường độ cao'),
(3, 'Bơi Người Lớn', 60, 15, 'Lớp bơi cho người lớn'),
(4, 'Boxing hihi', 100, 20, 'Đấm là chết queo');

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `MANV` int(10) UNSIGNED NOT NULL,
  `HOTEN` varchar(100) NOT NULL,
  `SDT` varchar(20) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `VAITRO` enum('ADMIN','FRONTDESK','MAINTENANCE','OTHER') NOT NULL DEFAULT 'OTHER',
  `NGAYVAOLAM` date NOT NULL,
  `TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`MANV`, `HOTEN`, `SDT`, `EMAIL`, `VAITRO`, `NGAYVAOLAM`, `TRANGTHAI`) VALUES
(1, 'Phạm Quốc D', '0904444444', 'nv.admin@example.com', 'ADMIN', '2024-01-02', 1),
(2, 'Ngô Thị E', '0905555555', 'nv.frontdesk@example.com', 'FRONTDESK', '2024-03-15', 1),
(3, 'Đỗ Văn F', '0906666666', 'hlv.gym@example.com', 'OTHER', '2023-11-01', 1),
(4, 'Lý Thu G', '0907777777', 'hlv.yoga@example.com', 'OTHER', '2023-10-10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `phong`
--

CREATE TABLE `phong` (
  `MAPHONG` int(10) UNSIGNED NOT NULL,
  `MAKHU` int(10) UNSIGNED NOT NULL,
  `TENPHONG` varchar(100) NOT NULL,
  `SUCCHUA` int(10) UNSIGNED NOT NULL,
  `GHICHU` text DEFAULT NULL,
  `HOATDONG` tinyint(1) NOT NULL DEFAULT 1,
  `CALO_MOI_GIO` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phong`
--

INSERT INTO `phong` (`MAPHONG`, `MAKHU`, `TENPHONG`, `SUCCHUA`, `GHICHU`, `HOATDONG`, `CALO_MOI_GIO`) VALUES
(1, 1, 'Phòng Gym 1', 40, 'Máy chạy bộ, tạ tự do', 1, 0),
(2, 1, 'Phòng Gym 2', 30, 'Máy kháng lực', 1, 0),
(3, 2, 'Hồ bơi 25m', 60, 'Có khu trẻ em', 1, 0),
(4, 1, 'Phòng Yoga 1', 25, 'hello', 1, 0),
(5, 3, 'Một 23', 22, '[Loại: Bóng Đá] CC', 1, 0);

--
-- Triggers `phong`
--
DELIMITER $$
CREATE TRIGGER `trg_phong_calo_before_insert` BEFORE INSERT ON `phong` FOR EACH ROW BEGIN
    SET NEW.CALO_MOI_GIO = CASE
        WHEN NEW.TENPHONG LIKE '%Gym%' THEN NEW.SUCCHUA * 15
        WHEN NEW.TENPHONG LIKE '%bơi%' THEN NEW.SUCCHUA * 10
        WHEN NEW.TENPHONG LIKE '%Yoga%' THEN NEW.SUCCHUA * 8
        ELSE NEW.SUCCHUA * 5
    END;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pt_session`
--

CREATE TABLE `pt_session` (
  `MAPT` int(10) UNSIGNED NOT NULL,
  `MAHLV` int(10) UNSIGNED NOT NULL,
  `MAHV` int(10) UNSIGNED NOT NULL,
  `MAPHONG` int(10) UNSIGNED NOT NULL,
  `BATDAU` datetime NOT NULL,
  `KETTHUC` datetime NOT NULL,
  `TRANGTHAI` enum('SCHEDULED','DONE','CANCELLED','NO_SHOW') NOT NULL DEFAULT 'SCHEDULED'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pt_session`
--

INSERT INTO `pt_session` (`MAPT`, `MAHLV`, `MAHV`, `MAPHONG`, `BATDAU`, `KETTHUC`, `TRANGTHAI`) VALUES
(1, 3, 1, 1, '2025-01-02 18:00:00', '2025-01-02 19:00:00', 'SCHEDULED'),
(2, 4, 2, 4, '2025-01-08 06:30:00', '2025-01-08 07:30:00', 'SCHEDULED');

-- --------------------------------------------------------

--
-- Table structure for table `thanhtoan`
--

CREATE TABLE `thanhtoan` (
  `MATTTOAN` int(10) UNSIGNED NOT NULL,
  `MAHDON` int(10) UNSIGNED NOT NULL,
  `SOTIEN` decimal(12,2) NOT NULL,
  `NGAYTT` datetime NOT NULL DEFAULT current_timestamp(),
  `PHUONGTHUC` enum('CASH','CARD','BANK','EWALLET') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thanhtoan`
--

INSERT INTO `thanhtoan` (`MATTTOAN`, `MAHDON`, `SOTIEN`, `NGAYTT`, `PHUONGTHUC`) VALUES
(1, 1, 1400000.00, '2024-12-30 09:05:00', 'CASH'),
(2, 2, 300000.00, '2025-01-02 15:00:00', 'CARD');

-- --------------------------------------------------------

--
-- Table structure for table `thuetu`
--

CREATE TABLE `thuetu` (
  `MATT` int(10) UNSIGNED NOT NULL,
  `MATU` int(10) UNSIGNED NOT NULL,
  `MAHV` int(10) UNSIGNED NOT NULL,
  `NGAYBD` date NOT NULL,
  `NGAYKT` date NOT NULL,
  `TRANGTHAI` enum('ACTIVE','EXPIRED','CANCELLED') NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thuetu`
--

INSERT INTO `thuetu` (`MATT`, `MATU`, `MAHV`, `NGAYBD`, `NGAYKT`, `TRANGTHAI`) VALUES
(1, 1, 1, '2025-01-01', '2025-01-31', 'ACTIVE'),
(2, 2, 2, '2025-01-10', '2025-02-09', 'ACTIVE');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buoilop`
--
ALTER TABLE `buoilop`
  ADD PRIMARY KEY (`MABUOI`),
  ADD KEY `fk_buoi_lop` (`MALOP`),
  ADD KEY `fk_buoi_phong` (`MAPHONG`),
  ADD KEY `fk_buoi_hlv` (`MAHLV`);

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
  ADD UNIQUE KEY `uq_dangky_buoi_hv` (`MABUOI`,`MAHV`),
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
  ADD KEY `fk_dong_hd` (`MAHDON`);

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
  ADD KEY `fk_hoadon_km` (`MAKM`);

--
-- Indexes for table `hoivien`
--
ALTER TABLE `hoivien`
  ADD PRIMARY KEY (`MAHV`),
  ADD UNIQUE KEY `uq_hoivien_sdt` (`SDT`),
  ADD UNIQUE KEY `uq_hoivien_email` (`EMAIL`);

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
  ADD UNIQUE KEY `uq_khuyenmai_code` (`CODE`);

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
  ADD UNIQUE KEY `uq_locker_phong_kitu` (`MAPHONG`,`KITU`);

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
  ADD UNIQUE KEY `uq_nhanvien_sdt` (`SDT`),
  ADD UNIQUE KEY `uq_nhanvien_email` (`EMAIL`);

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
  ADD KEY `fk_thuetu_tu` (`MATU`),
  ADD KEY `fk_thuetu_hoivien` (`MAHV`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buoilop`
--
ALTER TABLE `buoilop`
  MODIFY `MABUOI` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `checkin`
--
ALTER TABLE `checkin`
  MODIFY `MACI` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dangky_lop`
--
ALTER TABLE `dangky_lop`
  MODIFY `MADK` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `datphong`
--
ALTER TABLE `datphong`
  MODIFY `MADP` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `donghoadon`
--
ALTER TABLE `donghoadon`
  MODIFY `MADONG` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hoadon`
--
ALTER TABLE `hoadon`
  MODIFY `MAHDON` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hoivien`
--
ALTER TABLE `hoivien`
  MODIFY `MAHV` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hopdong`
--
ALTER TABLE `hopdong`
  MODIFY `MAHD` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `khu`
--
ALTER TABLE `khu`
  MODIFY `MAKHU` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `MAKM` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `loaigoi`
--
ALTER TABLE `loaigoi`
  MODIFY `MALG` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `locker`
--
ALTER TABLE `locker`
  MODIFY `MATU` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lop`
--
ALTER TABLE `lop`
  MODIFY `MALOP` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `MANV` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `phong`
--
ALTER TABLE `phong`
  MODIFY `MAPHONG` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pt_session`
--
ALTER TABLE `pt_session`
  MODIFY `MAPT` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `thanhtoan`
--
ALTER TABLE `thanhtoan`
  MODIFY `MATTTOAN` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `thuetu`
--
ALTER TABLE `thuetu`
  MODIFY `MATT` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buoilop`
--
ALTER TABLE `buoilop`
  ADD CONSTRAINT `fk_buoi_hlv` FOREIGN KEY (`MAHLV`) REFERENCES `hlv` (`MAHLV`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_buoi_lop` FOREIGN KEY (`MALOP`) REFERENCES `lop` (`MALOP`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_buoi_phong` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`) ON UPDATE CASCADE;

--
-- Constraints for table `checkin`
--
ALTER TABLE `checkin`
  ADD CONSTRAINT `fk_checkin_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON UPDATE CASCADE;

--
-- Constraints for table `dangky_lop`
--
ALTER TABLE `dangky_lop`
  ADD CONSTRAINT `fk_dk_buoi` FOREIGN KEY (`MABUOI`) REFERENCES `buoilop` (`MABUOI`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dk_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON UPDATE CASCADE;

--
-- Constraints for table `datphong`
--
ALTER TABLE `datphong`
  ADD CONSTRAINT `fk_datphong_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_datphong_phong` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`) ON UPDATE CASCADE;

--
-- Constraints for table `donghoadon`
--
ALTER TABLE `donghoadon`
  ADD CONSTRAINT `fk_dong_hd` FOREIGN KEY (`MAHDON`) REFERENCES `hoadon` (`MAHDON`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hlv`
--
ALTER TABLE `hlv`
  ADD CONSTRAINT `fk_hlv_nhanvien` FOREIGN KEY (`MAHLV`) REFERENCES `nhanvien` (`MANV`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `fk_hoadon_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hoadon_km` FOREIGN KEY (`MAKM`) REFERENCES `khuyenmai` (`MAKM`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `hopdong`
--
ALTER TABLE `hopdong`
  ADD CONSTRAINT `fk_hopdong_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hopdong_loaigoi` FOREIGN KEY (`MALG`) REFERENCES `loaigoi` (`MALG`) ON UPDATE CASCADE;

--
-- Constraints for table `locker`
--
ALTER TABLE `locker`
  ADD CONSTRAINT `fk_locker_phong` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`) ON UPDATE CASCADE;

--
-- Constraints for table `phong`
--
ALTER TABLE `phong`
  ADD CONSTRAINT `fk_phong_khu` FOREIGN KEY (`MAKHU`) REFERENCES `khu` (`MAKHU`) ON UPDATE CASCADE;

--
-- Constraints for table `pt_session`
--
ALTER TABLE `pt_session`
  ADD CONSTRAINT `fk_pt_hlv` FOREIGN KEY (`MAHLV`) REFERENCES `hlv` (`MAHLV`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pt_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pt_phong` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`) ON UPDATE CASCADE;

--
-- Constraints for table `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD CONSTRAINT `fk_thanhtoan_hoadon` FOREIGN KEY (`MAHDON`) REFERENCES `hoadon` (`MAHDON`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `thuetu`
--
ALTER TABLE `thuetu`
  ADD CONSTRAINT `fk_thuetu_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_thuetu_tu` FOREIGN KEY (`MATU`) REFERENCES `locker` (`MATU`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
