-- phpMyAdmin SQL Dump
-- BLOCK SPORTS CENTER - DATABASE WITH CALO CALCULATION
-- Updated: 2025-11-18 10:15:00 UTC
-- Author: @huyryan220806
-- Feature: Tự động tính CALO dựa trên loại phòng

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `block_sports_center`
--
CREATE DATABASE IF NOT EXISTS `block_sports_center` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `block_sports_center`;

-- --------------------------------------------------------
-- BẢNG KHU (Khu vực tập luyện)
-- --------------------------------------------------------
CREATE TABLE `khu` (
  `MAKHU` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `TENKHU` varchar(100) NOT NULL,
  `LOAIKHU` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`MAKHU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `khu` (`MAKHU`, `TENKHU`, `LOAIKHU`) VALUES
(1, 'Khu Gym Tổng Hợp', 'GYM'),
(2, 'Khu Hồ Bơi Ngoài Trời', 'POOL'),
(3, 'Phòng Yoga Tầng 2', 'STUDIO'),
(4, 'Khu Hồ Bơi Trong Nhà', 'POOL'),
(5, 'Sân Futsal', 'FUTSAL'),
(6, 'Sân Bóng Chuyền', 'VOLLEY'),
(7, 'Sân Bóng Rổ', 'BASKET'),
(8, 'Sân Cầu Lông (4 sân)', 'BADMINT'),
(9, 'Sân Bóng Đá 11 Người', 'FOOTBAL'),
(10, 'Sân Pickleball', 'PICKLE'),
(11, 'Khu Máy Tập Tạ Lớn', 'GYM'),
(13, 'Phòng Boxing & Kickboxing', 'BOXING');

-- --------------------------------------------------------
-- BẢNG PHÒNG (Phòng tập) - ✅ CÓ CALO_MOI_GIO
-- --------------------------------------------------------
CREATE TABLE `phong` (
  `MAPHONG` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MAKHU` int(10) UNSIGNED NOT NULL,
  `TENPHONG` varchar(100) NOT NULL,
  `SUCCHUA` int(10) UNSIGNED NOT NULL,
  `GHICHU` text DEFAULT NULL,
  `HOATDONG` tinyint(1) NOT NULL DEFAULT 1,
  `CALO_MOI_GIO` int(11) NOT NULL DEFAULT 0 COMMENT 'Calo tiêu thụ trung bình mỗi giờ',
  PRIMARY KEY (`MAPHONG`),
  KEY `fk_phong_khu` (`MAKHU`),
  CONSTRAINT `fk_phong_khu` FOREIGN KEY (`MAKHU`) REFERENCES `khu` (`MAKHU`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `phong` (`MAPHONG`, `MAKHU`, `TENPHONG`, `SUCCHUA`, `GHICHU`, `HOATDONG`, `CALO_MOI_GIO`) VALUES
(1, 1, 'Phòng Gym 1', 40, 'Máy chạy bộ, tạ tự do', 1, 600),
(2, 1, 'Phòng Gym 2', 30, 'Máy kháng lực', 1, 450),
(3, 2, 'Hồ bơi 25m', 60, 'Có khu trẻ em', 1, 600),
(4, 3, 'Phòng Yoga 1', 25, 'Phòng Yoga cơ bản', 1, 200),
(5, 1, 'Phòng Tạ Nặng', 20, 'Khu vực dành cho các bài tập tạ chuyên sâu', 1, 300),
(6, 2, 'Hồ Bơi Sâu 50m', 40, 'Dành cho bơi lội chuyên nghiệp', 1, 400),
(7, 3, 'Phòng Yoga 2', 30, 'Yoga bay, thảm cố định', 1, 240),
(8, 5, 'Sân Futsal Số 1', 20, 'Sân có lưới chắn tiêu chuẩn', 1, 100),
(9, 7, 'Sân Bóng Rổ Trong Nhà', 15, 'Sân gỗ, có ghế khán giả', 1, 75),
(10, 8, 'Sân Cầu Lông Số 1', 8, 'Sân số 1', 1, 40),
(11, 13, 'Phòng Boxing Lớn', 25, 'Có 8 bao cát, 1 ring đấu tập', 1, 125),
(30, 2, 'Hồ bơi Phụ', 15, 'Hồ nhỏ cho lớp bơi cơ bản', 1, 150),
(31, 5, 'Sân Futsal A', 22, 'Sân 5 người', 1, 110),
(32, 6, 'Sân Bóng Chuyền A', 20, 'Sân trong nhà', 1, 100),
(33, 7, 'Sân Bóng Rổ B', 16, 'Sân nửa/toàn sân', 1, 80),
(34, 8, 'Sân Cầu Lông B', 12, 'Có 2 lưới', 1, 60),
(35, 13, 'Võ Đài Boxing', 15, 'Có bao cát và khu tập đối kháng', 1, 75);

-- --------------------------------------------------------
-- ✅ TRIGGER TỰ ĐỘNG TÍNH CALO KHI THÊM PHÒNG MỚI
-- --------------------------------------------------------
DELIMITER $$
CREATE TRIGGER `trg_phong_calo_before_insert` 
BEFORE INSERT ON `phong` 
FOR EACH ROW 
BEGIN
    -- Tự động tính CALO dựa trên tên phòng và sức chứa
    SET NEW.CALO_MOI_GIO = CASE
        -- Gym: 15 calo/người/giờ
        WHEN NEW.TENPHONG LIKE '%Gym%' THEN NEW.SUCCHUA * 15
        
        -- Hồ bơi: 10 calo/người/giờ
        WHEN NEW.TENPHONG LIKE '%bơi%' OR NEW.TENPHONG LIKE '%Bơi%' THEN NEW.SUCCHUA * 10
        
        -- Yoga: 8 calo/người/giờ
        WHEN NEW.TENPHONG LIKE '%Yoga%' THEN NEW.SUCCHUA * 8
        
        -- Boxing: 5 calo/người/giờ
        WHEN NEW.TENPHONG LIKE '%Boxing%' THEN NEW.SUCCHUA * 5
        
        -- Các loại sân khác: 5 calo/người/giờ
        ELSE NEW.SUCCHUA * 5
    END;
END$$
DELIMITER ;

-- --------------------------------------------------------
-- ✅ TRIGGER TỰ ĐỘNG TÍNH LẠI CALO KHI CẬP NHẬT PHÒNG
-- --------------------------------------------------------
DELIMITER $$
CREATE TRIGGER `trg_phong_calo_before_update` 
BEFORE UPDATE ON `phong` 
FOR EACH ROW 
BEGIN
    -- Chỉ tính lại nếu tên phòng hoặc sức chứa thay đổi
    IF NEW.TENPHONG != OLD.TENPHONG OR NEW.SUCCHUA != OLD.SUCCHUA THEN
        SET NEW.CALO_MOI_GIO = CASE
            WHEN NEW.TENPHONG LIKE '%Gym%' THEN NEW.SUCCHUA * 15
            WHEN NEW.TENPHONG LIKE '%bơi%' OR NEW.TENPHONG LIKE '%Bơi%' THEN NEW.SUCCHUA * 10
            WHEN NEW.TENPHONG LIKE '%Yoga%' THEN NEW.SUCCHUA * 8
            WHEN NEW.TENPHONG LIKE '%Boxing%' THEN NEW.SUCCHUA * 5
            ELSE NEW.SUCCHUA * 5
        END;
    END IF;
END$$
DELIMITER ;

-- --------------------------------------------------------
-- BẢNG LOCKER (Tủ đồ)
-- --------------------------------------------------------
CREATE TABLE `locker` (
  `MATU` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MAPHONG` int(10) UNSIGNED NOT NULL,
  `KITU` varchar(20) NOT NULL,
  `HOATDONG` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`MATU`),
  UNIQUE KEY `uq_locker_phong_kitu` (`MAPHONG`,`KITU`),
  CONSTRAINT `fk_locker_phong` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `locker` (`MATU`, `MAPHONG`, `KITU`, `HOATDONG`) VALUES
(1, 1, 'G1-01', 1),
(2, 1, 'G1-02', 1),
(3, 3, 'P-01', 1),
(4, 3, 'P-02', 1),
(5, 3, 'P-03', 1),
(7, 32, 'A-01', 1),
(8, 32, 'A-02', 1),
(9, 32, 'A-03', 1),
(10, 34, 'L1-01', 1),
(11, 34, 'L1-02', 1),
(12, 34, 'L2-01', 1),
(13, 34, 'L2-02', 1),
(14, 30, 'P2-001', 1),
(15, 30, 'P2-002', 1),
(16, 30, 'P2-003', 1),
(18, 1, 'G1-03', 1),
(19, 5, 'N1-01', 1),
(20, 5, 'N1-02', 1),
(21, 4, 'Y1-01', 1);

-- --------------------------------------------------------
-- BẢNG HỘI VIÊN
-- --------------------------------------------------------
CREATE TABLE `hoivien` (
  `MAHV` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `HOVATEN` varchar(100) NOT NULL,
  `GIOITINH` enum('Nam','Nữ','Khác') NOT NULL DEFAULT 'Nam',
  `NGAYSINH` date NOT NULL,
  `SDT` varchar(20) NOT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `DIACHI` varchar(255) DEFAULT NULL,
  `TRANGTHAI` enum('ACTIVE','SUSPENDED','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `NGAYTAO` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`MAHV`),
  UNIQUE KEY `uq_hoivien_sdt` (`SDT`),
  UNIQUE KEY `uq_hoivien_email` (`EMAIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `hoivien` (`MAHV`, `HOVATEN`, `GIOITINH`, `NGAYSINH`, `SDT`, `EMAIL`, `DIACHI`, `TRANGTHAI`, `NGAYTAO`) VALUES
(1, 'Nguyễn Văn An', 'Nam', '2000-01-15', '0901777825', 'An0115200@gmail.com', 'Biên Hòa, Đồng Nai', 'ACTIVE', '2025-11-01 08:56:39'),
(2, 'Trần Thị Bé Ba', 'Nữ', '1999-05-20', '0902222222', 'BBa1999@gmail.com', 'Thủ Đức, TP.HCM', 'ACTIVE', '2025-11-01 13:43:30'),
(3, 'Lê Minh Cường', 'Nam', '2001-09-10', '0962891582', 'Cuongle09@gmail.com', 'Long Thành, Đồng Nai', 'SUSPENDED', '2025-11-02 07:32:39'),
(4, 'Phạm Văn Dương', 'Nam', '2002-01-01', '0912344478', 'vanduong2002@gmail.com', 'Quận 1, TP.HCM', 'ACTIVE', '2025-11-02 13:33:21'),
(5, 'La Thị Mai', 'Nữ', '1997-01-22', '0910789003', 'mai001@gmail.com', 'Binh Thanh , TP HCM', 'ACTIVE', '2025-11-02 14:56:43'),
(6, 'Trương Văn Lớn', 'Nam', '2003-10-10', '0910000002', 'tvlon2003@gmail.com', 'TP. Biên Hòa, Đồng Nai', 'ACTIVE', '2025-11-03 08:58:10'),
(7, 'Bùi Văn Giang', 'Nam', '2001-11-02', '0907486777', 'giangdz@gmail.com', 'Dĩ An, Bình Dương', 'ACTIVE', '2025-11-03 14:00:18'),
(8, 'Phan Thị Hạnh', 'Nữ', '1998-08-28', '0910000005', 'hanhbeautiful28@gmail.com', 'Gò Vấp, TP HCM', 'ACTIVE', '2025-11-04 07:03:05'),
(9, 'Ngô Thị Hà', 'Nữ', '1996-04-12', '0908881955', 'hango1996@gmail.com', 'Quận 1, TP HCM', 'ACTIVE', '2025-11-05 14:06:28'),
(10, 'Mai Thị Kim', 'Nữ', '1999-02-08', '0914871401', 'maikim02@gmail.com', 'Phú Nhuận, TP HCM', 'ACTIVE', '2025-11-06 11:06:28');

-- --------------------------------------------------------
-- BẢNG NHÂN VIÊN
-- --------------------------------------------------------
CREATE TABLE `nhanvien` (
  `MANV` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `HOTEN` varchar(100) NOT NULL,
  `SDT` varchar(20) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `VAITRO` enum('ADMIN','FRONTDESK','MAINTENANCE','OTHER') NOT NULL DEFAULT 'OTHER',
  `NGAYVAOLAM` date NOT NULL,
  `TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`MANV`),
  UNIQUE KEY `uq_nhanvien_sdt` (`SDT`),
  UNIQUE KEY `uq_nhanvien_email` (`EMAIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `nhanvien` (`MANV`, `HOTEN`, `SDT`, `EMAIL`, `VAITRO`, `NGAYVAOLAM`, `TRANGTHAI`) VALUES
(1, 'Phạm Quốc Danh', '0904468924', 'quocdanh2003@gmail.com', 'ADMIN', '2025-10-29', 1),
(2, 'Ngô Thị Yến', '0905258916', 'yen1912@gmail.com', 'FRONTDESK', '2025-10-30', 1),
(3, 'Đỗ Văn Đạt', '0906666666', 'vandat16@gmail.com', 'OTHER', '2025-10-30', 1),
(4, 'Lý Thu Hằng', '0907777777', 'hangly5354@gmail.com', 'OTHER', '2025-11-01', 1),
(11, 'Trần Văn Hải', '0908822547', 'hai2289@gmail.com', 'OTHER', '2024-11-02', 1),
(12, 'Nguyễn Thị Kim', '0909678239', 'kim116773@gmail.com', 'OTHER', '2025-11-02', 1);

-- --------------------------------------------------------
-- BẢNG HLV (Huấn Luyện Viên)
-- --------------------------------------------------------
CREATE TABLE `hlv` (
  `MAHLV` int(10) UNSIGNED NOT NULL,
  `MOTA` text DEFAULT NULL,
  `PHI_GIO` decimal(12,2) NOT NULL,
  PRIMARY KEY (`MAHLV`),
  CONSTRAINT `fk_hlv_nhanvien` FOREIGN KEY (`MAHLV`) REFERENCES `nhanvien` (`MANV`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `hlv` (`MAHLV`, `MOTA`, `PHI_GIO`) VALUES
(1, 'HLV Boxing', 250000.00),
(3, 'HLV Gym chuyên tăng cơ, giảm mỡ', 200000.00),
(4, 'HLV Yoga, thiền, giãn cơ', 250000.00),
(11, 'HLV Bơi', 250000.00);

-- --------------------------------------------------------
-- BẢNG LỚP HỌC
-- --------------------------------------------------------
CREATE TABLE `lop` (
  `MALOP` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `TENLOP` varchar(100) NOT NULL,
  `THOILUONG` int(10) UNSIGNED NOT NULL,
  `SISO_MACDINH` int(10) UNSIGNED NOT NULL,
  `MOTA` text DEFAULT NULL,
  PRIMARY KEY (`MALOP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `lop` (`MALOP`, `TENLOP`, `THOILUONG`, `SISO_MACDINH`, `MOTA`) VALUES
(1, 'Yoga Cơ Bản', 60, 20, 'Lớp yoga cho người mới bắt đầu'),
(2, 'HIIT Giảm Mỡ', 45, 18, 'Cardio cường độ cao'),
(3, 'Bơi Người Lớn', 60, 15, 'Lớp bơi cho người lớn'),
(4, 'Boxing Cơ Bản', 90, 12, 'Kỹ thuật đấm, di chuyển và phòng thủ'),
(5, 'Futsal Kỹ Năng', 90, 20, 'Kỹ năng cơ bản và nâng cao cho Futsal'),
(6, 'Bóng Chuyền Cơ bản', 90, 18, 'Kỹ thuật chuyền, đệm, phát bóng'),
(7, 'Bóng Rổ Shooting', 90, 15, 'Tập trung vào kỹ thuật ném rổ và di chuyển'),
(8, 'Cầu Lông Đôi', 60, 10, 'Chiến thuật và kỹ thuật đánh đôi');

-- --------------------------------------------------------
-- BẢNG BUỔI LỚP
-- --------------------------------------------------------
CREATE TABLE `buoilop` (
  `MABUOI` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MALOP` int(10) UNSIGNED NOT NULL,
  `MAPHONG` int(10) UNSIGNED NOT NULL,
  `MAHLV` int(10) UNSIGNED NOT NULL,
  `BATDAU` datetime NOT NULL,
  `KETTHUC` datetime NOT NULL,
  `SISO` int(10) UNSIGNED NOT NULL,
  `TRANGTHAI` enum('SCHEDULED','ONGOING','DONE','CANCELLED') NOT NULL DEFAULT 'SCHEDULED',
  PRIMARY KEY (`MABUOI`),
  KEY `fk_buoi_lop` (`MALOP`),
  KEY `fk_buoi_phong` (`MAPHONG`),
  KEY `fk_buoi_hlv` (`MAHLV`),
  CONSTRAINT `fk_buoi_hlv` FOREIGN KEY (`MAHLV`) REFERENCES `hlv` (`MAHLV`) ON UPDATE CASCADE,
  CONSTRAINT `fk_buoi_lop` FOREIGN KEY (`MALOP`) REFERENCES `lop` (`MALOP`) ON UPDATE CASCADE,
  CONSTRAINT `fk_buoi_phong` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `buoilop` (`MABUOI`, `MALOP`, `MAPHONG`, `MAHLV`, `BATDAU`, `KETTHUC`, `SISO`, `TRANGTHAI`) VALUES
(1, 1, 4, 4, '2025-11-20 18:00:00', '2025-11-20 19:00:00', 20, 'SCHEDULED'),
(2, 2, 1, 3, '2025-11-21 19:00:00', '2025-11-21 19:45:00', 18, 'SCHEDULED'),
(3, 3, 3, 11, '2025-11-22 07:00:00', '2025-11-22 08:00:00', 15, 'SCHEDULED');

-- --------------------------------------------------------
-- BẢNG LOẠI GÓI TẬP
-- --------------------------------------------------------
CREATE TABLE `loaigoi` (
  `MALG` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `TENLG` varchar(100) NOT NULL,
  `THOIHAN` int(10) UNSIGNED NOT NULL,
  `GIA` decimal(12,2) NOT NULL,
  `CAPDO` enum('BASIC','STANDARD','VIP') NOT NULL,
  `MOTA` text DEFAULT NULL,
  PRIMARY KEY (`MALG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `loaigoi` (`MALG`, `TENLG`, `THOIHAN`, `GIA`, `CAPDO`, `MOTA`) VALUES
(1, 'Gói 1 tháng', 30, 500000.00, 'BASIC', 'Gói cơ bản 1 tháng'),
(2, 'Gói 3 tháng', 90, 1300000.00, 'STANDARD', 'Tiết kiệm hơn khi tập 3 tháng'),
(3, 'Gói 12 tháng', 365, 4500000.00, 'VIP', 'Gói VIP 12 tháng full dịch vụ');

-- --------------------------------------------------------
-- BẢNG HỢP ĐỒNG
-- --------------------------------------------------------
CREATE TABLE `hopdong` (
  `MAHD` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MAHV` int(10) UNSIGNED NOT NULL,
  `MALG` int(10) UNSIGNED NOT NULL,
  `NGAYBD` date NOT NULL,
  `NGAYKT` date NOT NULL,
  `TRANGTHAI` enum('ACTIVE','PAUSED','EXPIRED','CANCELLED') NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`MAHD`),
  KEY `fk_hopdong_hoivien` (`MAHV`),
  KEY `fk_hopdong_loaigoi` (`MALG`),
  CONSTRAINT `fk_hopdong_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON UPDATE CASCADE,
  CONSTRAINT `fk_hopdong_loaigoi` FOREIGN KEY (`MALG`) REFERENCES `loaigoi` (`MALG`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `hopdong` (`MAHD`, `MAHV`, `MALG`, `NGAYBD`, `NGAYKT`, `TRANGTHAI`) VALUES
(1, 1, 1, '2025-11-01', '2025-12-01', 'ACTIVE'),
(2, 2, 2, '2025-11-01', '2026-01-30', 'ACTIVE'),
(3, 3, 3, '2025-11-01', '2026-10-31', 'ACTIVE');

-- --------------------------------------------------------
-- BẢNG USERS (Đăng nhập hệ thống)
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('ADMIN','USER') NOT NULL DEFAULT 'USER',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Mật khẩu: admin123
INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `fullname`, `phone`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@blocksports.vn', '$2y$10$1F20HsjQsZ9b2G8ZCWaZYOIpHeY5oGD0w7yJPtqmzRoPR6xXqEh1a', 'Administrator', '0901234567', 'ADMIN', '2025-11-18 10:00:00');

-- --------------------------------------------------------
-- BẢNG KHUYẾN MÃI
-- --------------------------------------------------------
CREATE TABLE `khuyenmai` (
  `MAKM` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `CODE` varchar(50) NOT NULL,
  `LOAI` enum('PERCENT','AMOUNT') NOT NULL,
  `GIATRI` decimal(12,2) NOT NULL,
  `NGAYBD` date NOT NULL,
  `NGAYKT` date NOT NULL,
  `MOTA` text DEFAULT NULL,
  PRIMARY KEY (`MAKM`),
  UNIQUE KEY `uq_khuyenmai_code` (`CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `khuyenmai` (`MAKM`, `CODE`, `LOAI`, `GIATRI`, `NGAYBD`, `NGAYKT`, `MOTA`) VALUES
(1, 'NEWYEAR10', 'PERCENT', 10.00, '2024-12-25', '2026-01-10', 'Giảm 10% dịp năm mới'),
(2, 'WELCOME100', 'AMOUNT', 100000.00, '2024-11-01', '2026-03-31', 'Giảm 100k cho hợp đồng đầu tiên');

-- --------------------------------------------------------
-- CÁC BẢNG KHÁC (Đơn giản hóa)
-- --------------------------------------------------------

CREATE TABLE `checkin` (
  `MACI` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MAHV` int(10) UNSIGNED NOT NULL,
  `THOIGIAN` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`MACI`),
  KEY `fk_checkin_hoivien` (`MAHV`),
  CONSTRAINT `fk_checkin_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `dangky_lop` (
  `MADK` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MABUOI` int(10) UNSIGNED NOT NULL,
  `MAHV` int(10) UNSIGNED NOT NULL,
  `NGAYDK` datetime NOT NULL DEFAULT current_timestamp(),
  `TRANGTHAI` enum('BOOKED','ATTENDED','NO_SHOW','CANCELLED') NOT NULL DEFAULT 'BOOKED',
  PRIMARY KEY (`MADK`),
  UNIQUE KEY `uq_dangky_buoi_hv` (`MABUOI`,`MAHV`),
  KEY `fk_dk_hoivien` (`MAHV`),
  CONSTRAINT `fk_dk_buoi` FOREIGN KEY (`MABUOI`) REFERENCES `buoilop` (`MABUOI`) ON UPDATE CASCADE,
  CONSTRAINT `fk_dk_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `datphong` (
  `MADP` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MAPHONG` int(10) UNSIGNED NOT NULL,
  `MAHV` int(10) UNSIGNED DEFAULT NULL,
  `BATDAU` datetime NOT NULL,
  `KETTHUC` datetime NOT NULL,
  `MUCTIEU` enum('TAP_TU_DO','CLB','GIU_CHO_SU_KIEN','KHAC') NOT NULL DEFAULT 'TAP_TU_DO',
  `TRANGTHAI` enum('PENDING','CONFIRMED','CANCELLED','DONE') NOT NULL DEFAULT 'PENDING',
  PRIMARY KEY (`MADP`),
  KEY `fk_datphong_phong` (`MAPHONG`),
  KEY `fk_datphong_hoivien` (`MAHV`),
  CONSTRAINT `fk_datphong_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_datphong_phong` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pt_session` (
  `MAPT` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MAHLV` int(10) UNSIGNED NOT NULL,
  `MAHV` int(10) UNSIGNED NOT NULL,
  `MAPHONG` int(10) UNSIGNED NOT NULL,
  `BATDAU` datetime NOT NULL,
  `KETTHUC` datetime NOT NULL,
  `TRANGTHAI` enum('SCHEDULED','DONE','CANCELLED','NO_SHOW') NOT NULL DEFAULT 'SCHEDULED',
  PRIMARY KEY (`MAPT`),
  KEY `fk_pt_hlv` (`MAHLV`),
  KEY `fk_pt_hoivien` (`MAHV`),
  KEY `fk_pt_phong` (`MAPHONG`),
  CONSTRAINT `fk_pt_hlv` FOREIGN KEY (`MAHLV`) REFERENCES `hlv` (`MAHLV`) ON UPDATE CASCADE,
  CONSTRAINT `fk_pt_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON UPDATE CASCADE,
  CONSTRAINT `fk_pt_phong` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `thuetu` (
  `MATT` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MATU` int(10) UNSIGNED NOT NULL,
  `MAHV` int(10) UNSIGNED NOT NULL,
  `NGAYBD` date NOT NULL,
  `NGAYKT` date NOT NULL,
  `TRANGTHAI` enum('ACTIVE','EXPIRED','CANCELLED') NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`MATT`),
  KEY `fk_thuetu_tu` (`MATU`),
  KEY `fk_thuetu_hoivien` (`MAHV`),
  CONSTRAINT `fk_thuetu_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON UPDATE CASCADE,
  CONSTRAINT `fk_thuetu_tu` FOREIGN KEY (`MATU`) REFERENCES `locker` (`MATU`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `hoadon` (
  `MAHDON` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MAHV` int(10) UNSIGNED NOT NULL,
  `MAKM` int(10) UNSIGNED DEFAULT NULL,
  `NGAYLAP` datetime NOT NULL DEFAULT current_timestamp(),
  `TRANGTHAI` enum('DRAFT','ISSUED','PAID','PARTIAL','VOID') NOT NULL DEFAULT 'DRAFT',
  PRIMARY KEY (`MAHDON`),
  KEY `fk_hoadon_hoivien` (`MAHV`),
  KEY `fk_hoadon_km` (`MAKM`),
  CONSTRAINT `fk_hoadon_hoivien` FOREIGN KEY (`MAHV`) REFERENCES `hoivien` (`MAHV`) ON UPDATE CASCADE,
  CONSTRAINT `fk_hoadon_km` FOREIGN KEY (`MAKM`) REFERENCES `khuyenmai` (`MAKM`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `donghoadon` (
  `MADONG` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MAHDON` int(10) UNSIGNED NOT NULL,
  `LOAIHANG` enum('MEMBERSHIP','CLASS','PT','BOOKING','LOCKER','OTHER') NOT NULL,
  `REF_ID` int(10) UNSIGNED DEFAULT NULL,
  `MOTA` varchar(255) DEFAULT NULL,
  `SOLUONG` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `DONGIA` decimal(12,2) NOT NULL,
  PRIMARY KEY (`MADONG`),
  KEY `fk_dong_hd` (`MAHDON`),
  CONSTRAINT `fk_dong_hd` FOREIGN KEY (`MAHDON`) REFERENCES `hoadon` (`MAHDON`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `thanhtoan` (
  `MATTTOAN` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `MAHDON` int(10) UNSIGNED NOT NULL,
  `SOTIEN` decimal(12,2) NOT NULL,
  `NGAYTT` datetime NOT NULL DEFAULT current_timestamp(),
  `PHUONGTHUC` enum('CASH','CARD','BANK','EWALLET') NOT NULL,
  PRIMARY KEY (`MATTTOAN`),
  KEY `fk_thanhtoan_hoadon` (`MAHDON`),
  CONSTRAINT `fk_thanhtoan_hoadon` FOREIGN KEY (`MAHDON`) REFERENCES `hoadon` (`MAHDON`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;