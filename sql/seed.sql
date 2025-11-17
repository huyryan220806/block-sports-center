-- ============================================================
--  BLOCK SPORTS CENTER - SEED DATA
--  Database: block_sports_center
--  YÊU CẦU: Import sau khi đã chạy schema.sql
-- ============================================================

USE block_sports_center;

SET FOREIGN_KEY_CHECKS = 0;

-- XÓA DỮ LIỆU CŨ (GIỮ LẠI CẤU TRÚC BẢNG)
TRUNCATE TABLE CHECKIN;
TRUNCATE TABLE THANHTOAN;
TRUNCATE TABLE DONGHOADON;
TRUNCATE TABLE HOADON;
TRUNCATE TABLE KHUYENMAI;
TRUNCATE TABLE THUETU;
TRUNCATE TABLE LOCKER;
TRUNCATE TABLE PT_SESSION;
TRUNCATE TABLE DATPHONG;
TRUNCATE TABLE DANGKY_LOP;
TRUNCATE TABLE BUOILOP;
TRUNCATE TABLE LOP;
TRUNCATE TABLE HLV;
TRUNCATE TABLE NHANVIEN;
TRUNCATE TABLE PHONG;
TRUNCATE TABLE KHU;
TRUNCATE TABLE HOPDONG;
TRUNCATE TABLE LOAIGOI;
TRUNCATE TABLE HOIVIEN;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- 1. HOIVIEN (5–10 hội viên)
-- ============================================================

INSERT INTO HOIVIEN (MAHV, HOVATEN, GIOITINH, NGAYSINH, SDT, EMAIL, DIACHI, TRANGTHAI, NGAYTAO) VALUES
(1, 'Nguyen Van A', 'NAM',  '1995-01-10', '0900000001', 'a.nguyen@example.com',  'Quan 1, TP.HCM', 'ACTIVE',    '2025-01-01 09:00:00'),
(2, 'Tran Thi B',   'NU',   '1998-05-20', '0900000002', 'b.tran@example.com',    'Quan 3, TP.HCM', 'ACTIVE',    '2025-01-02 09:00:00'),
(3, 'Le Van C',     'NAM',  '1990-03-15', '0900000003', 'c.le@example.com',      'Quan 7, TP.HCM', 'SUSPENDED', '2025-01-03 09:00:00'),
(4, 'Pham Thi D',   'NU',   '1988-07-30', '0900000004', 'd.pham@example.com',    'Thu Duc, TP.HCM','ACTIVE',    '2025-01-04 09:00:00'),
(5, 'Hoang Van E',  'NAM',  '1992-11-25', '0900000005', 'e.hoang@example.com',   'Binh Thanh, TP.HCM','INACTIVE','2025-01-05 09:00:00'),
(6, 'Nguyen Thi F', 'NU',   '1999-09-09', '0900000006', 'f.nguyen@example.com',  'Quan 10, TP.HCM','ACTIVE',    '2025-01-06 09:00:00'),
(7, 'Do Van G',     'NAM',  '1993-12-12', '0900000007', 'g.do@example.com',      'Quan 5, TP.HCM', 'ACTIVE',    '2025-01-07 09:00:00');

-- ============================================================
-- 2. LOAIGOI (3 gói tập)
-- ============================================================

INSERT INTO LOAIGOI (MALG, TENLG, THOIHAN, GIA, CAPDO, MOTA) VALUES
(1, 'Goi 1 thang Basic',    30,  800000,  'BASIC',    'Su dung tat ca khu co ban trong 1 thang'),
(2, 'Goi 3 thang Standard', 90,  2100000, 'STANDARD', 'Goi 3 thang, tang 1 buoi PT 1-1'),
(3, 'Goi 12 thang VIP',     365, 7000000, 'VIP',      'Su dung full dich vu trong 1 nam, uu tien dat phong');

-- ============================================================
-- 3. HOPDONG (hợp đồng demo cho vài hội viên)
-- ============================================================

INSERT INTO HOPDONG (MAHD, MAHV, MALG, NGAYBD, NGAYKT, TRANGTHAI) VALUES
(1, 1, 2, '2025-01-01', '2025-03-31', 'ACTIVE'),
(2, 2, 1, '2025-02-01', '2025-03-02', 'ACTIVE'),
(3, 3, 1, '2024-10-01', '2024-10-31', 'EXPIRED'),
(4, 4, 3, '2025-01-15', '2026-01-14', 'ACTIVE'),
(5, 6, 2, '2025-02-10', '2025-05-10', 'PAUSED');

-- ============================================================
-- 4. KHU (khu vực)
-- ============================================================

INSERT INTO KHU (MAKHU, TENKHU, LOAIKHU) VALUES
(1, 'Khu Gym Tong Hop', 'GYM'),
(2, 'Khu Ho Boi',       'POOL'),
(3, 'Khu Studio Yoga',  'STUDIO');

-- ============================================================
-- 5. PHONG (3 phòng)
-- ============================================================

INSERT INTO PHONG (MAPHONG, MAKHU, TENPHONG, SUCCHUA, GHICHU, HOATDONG) VALUES
(1, 1, 'Phong Gym 1',     40, 'Khu tap ta may',             1),
(2, 2, 'Ho Boi 25m',      30, 'Ho boi trong nha',           1),
(3, 3, 'Studio Yoga 1',   25, 'Phong tap Yoga, co guong',   1);

-- ============================================================
-- 6. NHANVIEN & HLV (2–3 HLV)
-- ============================================================

-- NHANVIEN: tao 3 nhan vien, trong do 2 la HLV
INSERT INTO NHANVIEN (MANV, HOTEN, SDT, EMAIL, VAITRO, NGAYVAOLAM, TRANGTHAI) VALUES
(1, 'Nguyen HLV 1',  '0911000001', 'hlv1@example.com', 'OTHER',       '2024-01-01', 1),
(2, 'Tran HLV 2',    '0911000002', 'hlv2@example.com', 'OTHER',       '2024-02-01', 1),
(3, 'Le Frontdesk',  '0911000003', 'frontdesk@example.com', 'FRONTDESK','2024-03-01', 1);

-- HLV: subtype cua NHANVIEN (MANV 1 va 2)
INSERT INTO HLV (MAHLV, MOTA, PHI_GIO) VALUES
(1, 'Chuyen mon: Gym, Suc manh',        300000),
(2, 'Chuyen mon: Yoga, Giam can',      350000);

-- ============================================================
-- 7. LOP (2–3 lớp)
-- ============================================================

INSERT INTO LOP (MALOP, TENLOP, THOILUONG, SISO_MACDINH, MOTA) VALUES
(1, 'Lop Yoga Co Ban',      60, 20, 'Lop yoga danh cho nguoi moi bat dau'),
(2, 'Lop HIIT Dot Mo',      45, 25, 'Lop HIIT cuong do cao dot mo'),
(3, 'Lop Boi Nguoi Lon',    60, 15, 'Lop boi cai thien ky nang boi co ban');

-- ============================================================
-- 8. BUOILOP (vài buổi lớp cụ thể)
-- Thoi gian demo trong tuan dau T2/2025
-- ============================================================

INSERT INTO BUOILOP (MABUOI, MALOP, MAPHONG, MAHLV, BATDAU, KETTHUC, SISO, TRANGTHAI) VALUES
(1, 1, 3, 2, '2025-02-03 07:00:00', '2025-02-03 08:00:00', 20, 'SCHEDULED'),
(2, 1, 3, 2, '2025-02-05 07:00:00', '2025-02-05 08:00:00', 20, 'SCHEDULED'),
(3, 2, 1, 1, '2025-02-03 18:00:00', '2025-02-03 18:45:00', 25, 'SCHEDULED'),
(4, 2, 1, 1, '2025-02-04 18:00:00', '2025-02-04 18:45:00', 25, 'SCHEDULED'),
(5, 3, 2, 1, '2025-02-02 09:00:00', '2025-02-02 10:00:00', 15, 'SCHEDULED');

-- ============================================================
-- 9. DANGKY_LOP (một số đăng ký lớp)
-- ============================================================

INSERT INTO DANGKY_LOP (MADK, MABUOI, MAHV, NGAYDK, TRANGTHAI) VALUES
(1, 1, 1, '2025-01-30 10:00:00', 'BOOKED'),
(2, 1, 2, '2025-01-30 11:00:00', 'BOOKED'),
(3, 3, 1, '2025-01-31 09:00:00', 'BOOKED'),
(4, 3, 4, '2025-01-31 09:30:00', 'BOOKED'),
(5, 5, 2, '2025-01-31 10:00:00', 'CANCELLED');

-- ============================================================
-- 10. DATPHONG (demo)
-- ============================================================

INSERT INTO DATPHONG (MADP, MAPHONG, MAHV, BATDAU, KETTHUC, MUCTIEU, TRANGTHAI) VALUES
(1, 1, 1, '2025-02-01 10:00:00', '2025-02-01 11:30:00', 'TAP_TU_DO', 'CONFIRMED'),
(2, 2, 2, '2025-02-02 15:00:00', '2025-02-02 16:30:00', 'CLB',       'PENDING');

-- ============================================================
-- 11. PT_SESSION (demo buổi tập PT 1-1)
-- ============================================================

INSERT INTO PT_SESSION (MAPT, MAHLV, MAHV, MAPHONG, BATDAU, KETTHUC, TRANGTHAI) VALUES
(1, 1, 1, 1, '2025-02-03 16:00:00', '2025-02-03 17:00:00', 'SCHEDULED'),
(2, 2, 2, 3, '2025-02-05 08:30:00', '2025-02-05 09:30:00', 'SCHEDULED');

-- ============================================================
-- 12. LOCKER & THUETU
-- ============================================================

-- LOCKER: 4 tu do cho phong Gym 1 (MAPHONG = 1)
INSERT INTO LOCKER (MATU, MAPHONG, KITU, HOATDONG) VALUES
(1, 1, 'G1-01', 1),
(2, 1, 'G1-02', 1),
(3, 1, 'G1-03', 1),
(4, 1, 'G1-04', 0);

-- THUETU: 2 ban ghi demo
INSERT INTO THUETU (MATT, MATU, MAHV, NGAYBD, NGAYKT, TRANGTHAI) VALUES
(1, 1, 1, '2025-01-01', '2025-01-31', 'EXPIRED'),
(2, 2, 2, '2025-02-01', '2025-02-28', 'ACTIVE');

-- ============================================================
-- 13. KHUYENMAI (demo)
-- ============================================================

INSERT INTO KHUYENMAI (MAKM, CODE, LOAI, GIATRI, NGAYBD, NGAYKT, MOTA) VALUES
(1, 'NEWYEAR10', 'PERCENT', 10,     '2025-01-01', '2025-01-31', 'Giam 10% nhan dip nam moi'),
(2, 'WELCOME100', 'AMOUNT',  100000, '2025-01-01', '2025-12-31', 'Giam 100k cho hoi vien moi');

-- ============================================================
-- 14. HOADON (1–2 hóa đơn mẫu)
-- ============================================================

INSERT INTO HOADON (MAHDON, MAHV, MAKM, NGAYLAP, TRANGTHAI) VALUES
(1, 1, 1, '2025-01-01 10:00:00', 'PAID'),
(2, 2, NULL, '2025-02-01 11:00:00', 'PARTIAL');

-- ============================================================
-- 15. DONGHOADON (dòng chi tiết cho hóa đơn)
-- ============================================================

-- Hoa don 1: membership va 1 buoi PT
INSERT INTO DONGHOADON (MADONG, MAHDON, LOAIHANG, REF_ID, MOTA, SOLUONG, DONGIA) VALUES
(1, 1, 'MEMBERSHIP', 1, 'Goi 3 thang Standard cho HV 1', 1, 2100000),
(2, 1, 'PT',         1, 'Buoi PT 1-1 voi HLV 1',         1, 300000);

-- Hoa don 2: membership cho HV 2
INSERT INTO DONGHOADON (MADONG, MAHDON, LOAIHANG, REF_ID, MOTA, SOLUONG, DONGIA) VALUES
(3, 2, 'MEMBERSHIP', 1, 'Goi 1 thang Basic cho HV 2',   1, 800000);

-- ============================================================
-- 16. THANHTOAN (các lần thanh toán)
-- ============================================================

-- Hoa don 1: da thanh toan du
INSERT INTO THANHTOAN (MATTTOAN, MAHDON, SOTIEN, NGAYTT, PHUONGTHUC) VALUES
(1, 1, 2400000, '2025-01-01 10:05:00', 'CARD');

-- Hoa don 2: moi thanh toan 1 phan
INSERT INTO THANHTOAN (MATTTOAN, MAHDON, SOTIEN, NGAYTT, PHUONGTHUC) VALUES
(2, 2, 400000, '2025-02-01 11:05:00', 'CASH');

-- ============================================================
-- 17. CHECKIN (một số lượt checkin)
-- ============================================================

INSERT INTO CHECKIN (MACI, MAHV, THOIGIAN) VALUES
(1, 1, '2025-01-02 07:05:00'),
(2, 1, '2025-01-03 18:10:00'),
(3, 2, '2025-02-02 08:00:00'),
(4, 4, '2025-01-20 19:30:00');

-- ============================================================
-- HƯỚNG DẪN TEST NHANH (chạy sau khi import)
-- ============================================================
-- SELECT * FROM HOIVIEN;
-- SELECT * FROM LOAIGOI;
-- SELECT * FROM HOPDONG;
-- SELECT * FROM PHONG;
-- SELECT * FROM LOP;
-- SELECT * FROM BUOILOP;
-- SELECT * FROM DANGKY_LOP;
-- SELECT * FROM HOADON;
-- SELECT * FROM DONGHOADON;
-- SELECT * FROM THANHTOAN;