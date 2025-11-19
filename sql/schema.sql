-- ============================================================
--  BLOCK SPORTS CENTER - SCHEMA
--  Database: block_sports_center
--  Engine: InnoDB, utf8mb4
-- ============================================================

-- Tạo database nếu chưa tồn tại
CREATE DATABASE IF NOT EXISTS block_sports_center
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE block_sports_center;

-- ============================================================
-- XÓA BẢNG NẾU ĐÃ TỒN TẠI (ĐÚNG THỨ TỰ RÀNG BUỘC)
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS
  CHECKIN,
  THANHTOAN,
  DONGHOADON,
  HOADON,
  KHUYENMAI,
  THUETU,
  LOCKER,
  PT_SESSION,
  DATPHONG,
  DANGKY_LOP,
  BUOILOP,
  LOP,
  HLV,
  NHANVIEN,
  PHONG,
  KHU,
  HOPDONG,
  LOAIGOI,
  HOIVIEN;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- 3.1. HOIVIEN
-- ============================================================

CREATE TABLE HOIVIEN (
  MAHV        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  HOVATEN     VARCHAR(100)       NOT NULL,
  GIOITINH    ENUM('NAM','NU','KHAC') NOT NULL,
  NGAYSINH    DATE               NOT NULL,
  SDT         VARCHAR(20)        NOT NULL,
  EMAIL       VARCHAR(100)       NULL,
  DIACHI      VARCHAR(255)       NULL,
  TRANGTHAI   ENUM('ACTIVE','SUSPENDED','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  NGAYTAO     DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT uq_hoivien_sdt   UNIQUE (SDT),
  CONSTRAINT uq_hoivien_email UNIQUE (EMAIL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.2. LOAIGOI
-- ============================================================

CREATE TABLE LOAIGOI (
  MALG      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  TENLG     VARCHAR(100) NOT NULL,
  THOIHAN   INT          NOT NULL,  -- số ngày hoặc tháng tùy nghiệp vụ
  GIA       DECIMAL(15,2) NOT NULL,
  CAPDO     ENUM('BASIC','STANDARD','VIP') NOT NULL,
  MOTA      TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.3. HOPDONG
-- ============================================================

CREATE TABLE HOPDONG (
  MAHD       BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MAHV       BIGINT UNSIGNED NOT NULL,
  MALG       BIGINT UNSIGNED NOT NULL,
  NGAYBD     DATE NOT NULL,
  NGAYKT     DATE NOT NULL,
  TRANGTHAI  ENUM('ACTIVE','PAUSED','EXPIRED','CANCELLED') NOT NULL DEFAULT 'ACTIVE',
  CONSTRAINT fk_hopdong_hoivien
    FOREIGN KEY (MAHV) REFERENCES HOIVIEN(MAHV),
  CONSTRAINT fk_hopdong_loaigoi
    FOREIGN KEY (MALG) REFERENCES LOAIGOI(MALG),
  INDEX idx_hopdong_mahv (MAHV),
  INDEX idx_hopdong_malg (MALG),
  INDEX idx_hopdong_trangthai (TRANGTHAI)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.4. KHU
-- ============================================================

CREATE TABLE KHU (
  MAKHU     BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  TENKHU    VARCHAR(100) NOT NULL,
  LOAIKHU   ENUM('GYM','POOL','STUDIO','COURT','OTHER') NOT NULL DEFAULT 'OTHER'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.5. PHONG
-- ============================================================

CREATE TABLE PHONG (
  MAPHONG    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MAKHU      BIGINT UNSIGNED NOT NULL,
  TENPHONG   VARCHAR(100) NOT NULL,
  SUCCHUA    INT          NOT NULL,
  GHICHU     TEXT NULL,
  HOATDONG   TINYINT(1)   NOT NULL DEFAULT 1,
  CONSTRAINT fk_phong_khu
    FOREIGN KEY (MAKHU) REFERENCES KHU(MAKHU),
  INDEX idx_phong_makhu (MAKHU),
  INDEX idx_phong_hoatdong (HOATDONG)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.6. NHANVIEN
-- ============================================================

CREATE TABLE NHANVIEN (
  MANV        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  HOTEN       VARCHAR(100) NOT NULL,
  SDT         VARCHAR(20)  NOT NULL,
  EMAIL       VARCHAR(100) NOT NULL,
  VAITRO      ENUM('ADMIN','FRONTDESK','MAINTENANCE','OTHER') NOT NULL DEFAULT 'OTHER',
  NGAYVAOLAM  DATE NOT NULL,
  TRANGTHAI   TINYINT(1) NOT NULL DEFAULT 1,
  CONSTRAINT uq_nhanvien_sdt   UNIQUE (SDT),
  CONSTRAINT uq_nhanvien_email UNIQUE (EMAIL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.7. HLV (subtype của NHANVIEN)
-- ============================================================

CREATE TABLE HLV (
  MAHLV    BIGINT UNSIGNED PRIMARY KEY,
  MOTA     TEXT NULL,
  PHI_GIO  DECIMAL(15,2) NOT NULL,
  CONSTRAINT fk_hlv_nhanvien
    FOREIGN KEY (MAHLV) REFERENCES NHANVIEN(MANV)
      ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.8. LOP
-- ============================================================

CREATE TABLE LOP (
  MALOP         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  TENLOP        VARCHAR(100) NOT NULL,
  THOILUONG     INT          NOT NULL, -- phút
  SISO_MACDINH  INT          NOT NULL,
  MOTA          TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.9. BUOILOP
-- ============================================================

CREATE TABLE BUOILOP (
  MABUOI      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MALOP       BIGINT UNSIGNED NOT NULL,
  MAPHONG     BIGINT UNSIGNED NOT NULL,
  MAHLV       BIGINT UNSIGNED NOT NULL,
  BATDAU      DATETIME NOT NULL,
  KETTHUC     DATETIME NOT NULL,
  SISO        INT NOT NULL,
  TRANGTHAI   ENUM('SCHEDULED','ONGOING','DONE','CANCELLED') NOT NULL DEFAULT 'SCHEDULED',
  CONSTRAINT fk_buoilop_lop
    FOREIGN KEY (MALOP) REFERENCES LOP(MALOP),
  CONSTRAINT fk_buoilop_phong
    FOREIGN KEY (MAPHONG) REFERENCES PHONG(MAPHONG),
  CONSTRAINT fk_buoilop_hlv
    FOREIGN KEY (MAHLV) REFERENCES HLV(MAHLV),
  INDEX idx_buoilop_malop (MALOP),
  INDEX idx_buoilop_maphong (MAPHONG),
  INDEX idx_buoilop_mahlv (MAHLV),
  INDEX idx_buoilop_batdau (BATDAU),
  INDEX idx_buoilop_trangthai (TRANGTHAI)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.10. DANGKY_LOP
-- ============================================================

CREATE TABLE DANGKY_LOP (
  MADK        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MABUOI      BIGINT UNSIGNED NOT NULL,
  MAHV        BIGINT UNSIGNED NOT NULL,
  NGAYDK      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  TRANGTHAI   ENUM('BOOKED','ATTENDED','NO_SHOW','CANCELLED') NOT NULL DEFAULT 'BOOKED',
  CONSTRAINT fk_dk_buoilop
    FOREIGN KEY (MABUOI) REFERENCES BUOILOP(MABUOI),
  CONSTRAINT fk_dk_hoivien
    FOREIGN KEY (MAHV) REFERENCES HOIVIEN(MAHV),
  CONSTRAINT uq_dk_buoi_hv UNIQUE (MABUOI, MAHV),
  INDEX idx_dk_mabuoi (MABUOI),
  INDEX idx_dk_mahv (MAHV),
  INDEX idx_dk_trangthai (TRANGTHAI)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.11. DATPHONG
-- ============================================================

CREATE TABLE DATPHONG (
  MADP        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MAPHONG     BIGINT UNSIGNED NOT NULL,
  MAHV        BIGINT UNSIGNED NULL,
  BATDAU      DATETIME NOT NULL,
  KETTHUC     DATETIME NOT NULL,
  MUCTIEU     ENUM('TAP_TU_DO','CLB','GIU_CHO_SU_KIEN','OTHER') NOT NULL DEFAULT 'OTHER',
  TRANGTHAI   ENUM('PENDING','CONFIRMED','CANCELLED','DONE') NOT NULL DEFAULT 'PENDING',
  CONSTRAINT fk_datphong_phong
    FOREIGN KEY (MAPHONG) REFERENCES PHONG(MAPHONG),
  CONSTRAINT fk_datphong_hoivien
    FOREIGN KEY (MAHV) REFERENCES HOIVIEN(MAHV),
  INDEX idx_datphong_maphong (MAPHONG),
  INDEX idx_datphong_mahv (MAHV),
  INDEX idx_datphong_batdau (BATDAU),
  INDEX idx_datphong_trangthai (TRANGTHAI)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.12. PT_SESSION
-- ============================================================

CREATE TABLE PT_SESSION (
  MAPT        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MAHLV       BIGINT UNSIGNED NOT NULL,
  MAHV        BIGINT UNSIGNED NOT NULL,
  MAPHONG     BIGINT UNSIGNED NOT NULL,
  BATDAU      DATETIME NOT NULL,
  KETTHUC     DATETIME NOT NULL,
  TRANGTHAI   ENUM('SCHEDULED','DONE','CANCELLED','NO_SHOW') NOT NULL DEFAULT 'SCHEDULED',
  CONSTRAINT fk_pt_hlv
    FOREIGN KEY (MAHLV) REFERENCES HLV(MAHLV),
  CONSTRAINT fk_pt_hoivien
    FOREIGN KEY (MAHV) REFERENCES HOIVIEN(MAHV),
  CONSTRAINT fk_pt_phong
    FOREIGN KEY (MAPHONG) REFERENCES PHONG(MAPHONG),
  INDEX idx_pt_mahlv (MAHLV),
  INDEX idx_pt_mahv (MAHV),
  INDEX idx_pt_maphong (MAPHONG),
  INDEX idx_pt_batdau (BATDAU),
  INDEX idx_pt_trangthai (TRANGTHAI)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.13. LOCKER
-- ============================================================

CREATE TABLE LOCKER (
  MATU       BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MAPHONG    BIGINT UNSIGNED NOT NULL,
  KITU       VARCHAR(50) NOT NULL,
  HOATDONG   TINYINT(1)  NOT NULL DEFAULT 1,
  CONSTRAINT fk_locker_phong
    FOREIGN KEY (MAPHONG) REFERENCES PHONG(MAPHONG),
  CONSTRAINT uq_locker_phong_kitu UNIQUE (MAPHONG, KITU),
  INDEX idx_locker_maphong (MAPHONG),
  INDEX idx_locker_hoatdong (HOATDONG)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.14. THUETU
-- ============================================================

CREATE TABLE THUETU (
  MATT       BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MATU       BIGINT UNSIGNED NOT NULL,
  MAHV       BIGINT UNSIGNED NOT NULL,
  NGAYBD     DATE NOT NULL,
  NGAYKT     DATE NOT NULL,
  TRANGTHAI  ENUM('ACTIVE','EXPIRED','CANCELLED') NOT NULL DEFAULT 'ACTIVE',
  CONSTRAINT fk_thuetu_locker
    FOREIGN KEY (MATU) REFERENCES LOCKER(MATU),
  CONSTRAINT fk_thuetu_hoivien
    FOREIGN KEY (MAHV) REFERENCES HOIVIEN(MAHV),
  INDEX idx_thuetu_matu (MATU),
  INDEX idx_thuetu_mahv (MAHV),
  INDEX idx_thuetu_trangthai (TRANGTHAI)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.15. KHUYENMAI
-- ============================================================

CREATE TABLE KHUYENMAI (
  MAKM      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  CODE      VARCHAR(50)  NOT NULL,
  LOAI      ENUM('PERCENT','AMOUNT') NOT NULL,
  GIATRI    DECIMAL(15,2) NOT NULL,
  NGAYBD    DATE NOT NULL,
  NGAYKT    DATE NOT NULL,
  MOTA      TEXT NULL,
  CONSTRAINT uq_khuyenmai_code UNIQUE (CODE),
  INDEX idx_khuyenmai_ngaybd (NGAYBD),
  INDEX idx_khuyenmai_ngaykt (NGAYKT)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.16. HOADON
-- ============================================================

CREATE TABLE HOADON (
  MAHDON     BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MAHV       BIGINT UNSIGNED NOT NULL,
  MAKM       BIGINT UNSIGNED NULL,
  NGAYLAP    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  TRANGTHAI  ENUM('DRAFT','ISSUED','PAID','PARTIAL','VOID') NOT NULL DEFAULT 'DRAFT',
  CONSTRAINT fk_hoadon_hoivien
    FOREIGN KEY (MAHV) REFERENCES HOIVIEN(MAHV),
  CONSTRAINT fk_hoadon_khuyenmai
    FOREIGN KEY (MAKM) REFERENCES KHUYENMAI(MAKM),
  INDEX idx_hoadon_mahv (MAHV),
  INDEX idx_hoadon_makm (MAKM),
  INDEX idx_hoadon_trangthai (TRANGTHAI),
  INDEX idx_hoadon_ngaylap (NGAYLAP)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.17. DONGHOADON
-- ============================================================

CREATE TABLE DONGHOADON (
  MADONG     BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MAHDON     BIGINT UNSIGNED NOT NULL,
  LOAIHANG   ENUM('MEMBERSHIP','CLASS','PT','BOOKING','LOCKER','OTHER') NOT NULL,
  REF_ID     BIGINT UNSIGNED NOT NULL,
  MOTA       VARCHAR(255) NOT NULL,
  SOLUONG    INT NOT NULL DEFAULT 1,
  DONGIA     DECIMAL(15,2) NOT NULL,
  CONSTRAINT fk_donghd_hoadon
    FOREIGN KEY (MAHDON) REFERENCES HOADON(MAHDON),
  INDEX idx_donghd_mahdon (MAHDON),
  INDEX idx_donghd_loaihang (LOAIHANG),
  INDEX idx_donghd_refid (REF_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.18. THANHTOAN
-- ============================================================

CREATE TABLE THANHTOAN (
  MATTTOAN   BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MAHDON     BIGINT UNSIGNED NOT NULL,
  SOTIEN     DECIMAL(15,2) NOT NULL,
  NGAYTT     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PHUONGTHUC ENUM('CASH','CARD','BANK','EWALLET') NOT NULL,
  CONSTRAINT fk_thanhtoan_hoadon
    FOREIGN KEY (MAHDON) REFERENCES HOADON(MAHDON),
  INDEX idx_thanhtoan_mahdon (MAHDON),
  INDEX idx_thanhtoan_ngaytt (NGAYTT),
  INDEX idx_thanhtoan_phuongthuc (PHUONGTHUC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3.19. CHECKIN
-- ============================================================

CREATE TABLE CHECKIN (
  MACI      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MAHV      BIGINT UNSIGNED NOT NULL,
  THOIGIAN  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_checkin_hoivien
    FOREIGN KEY (MAHV) REFERENCES HOIVIEN(MAHV),
  INDEX idx_checkin_mahv (MAHV),
  INDEX idx_checkin_thoigian (THOIGIAN)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- HOÀN TẤT SCHEMA
-- ============================================================
/**/;