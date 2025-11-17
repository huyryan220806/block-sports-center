-- ============================================================
--  BLOCK SPORTS CENTER - TRIGGERS
--  Database: block_sports_center
--  Yêu cầu: chạy sau khi đã import schema.sql và seed.sql
-- ============================================================

USE block_sports_center;

-- TẮT CHECK FK để có thể DROP trigger tự do
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- XÓA TRIGGER CŨ (NẾU CÓ)
-- ============================================================

DROP TRIGGER IF EXISTS trg_checkin_check_membership;
DROP TRIGGER IF EXISTS trg_dangky_lop_check_membership;
DROP TRIGGER IF EXISTS trg_dangky_lop_check_capacity;
DROP TRIGGER IF EXISTS trg_datphong_no_overlap;
DROP TRIGGER IF EXISTS trg_datphong_no_overlap_update;
DROP TRIGGER IF EXISTS trg_buoilop_no_overlap_room;
DROP TRIGGER IF EXISTS trg_buoilop_no_overlap_room_update;
DROP TRIGGER IF EXISTS trg_buoilop_no_overlap_hlv;
DROP TRIGGER IF EXISTS trg_buoilop_no_overlap_hlv_update;
DROP TRIGGER IF EXISTS trg_pt_no_overlap_room;
DROP TRIGGER IF EXISTS trg_pt_no_overlap_room_update;
DROP TRIGGER IF EXISTS trg_pt_no_overlap_hlv;
DROP TRIGGER IF EXISTS trg_pt_no_overlap_hlv_update;
DROP TRIGGER IF EXISTS trg_thanhtoan_check_total;
DROP TRIGGER IF EXISTS trg_thanhtoan_check_total_update;

SET FOREIGN_KEY_CHECKS = 1;

DELIMITER $$

-- ============================================================
-- 1. CHECK MEMBERSHIP CÒN HẠN CHO CHECKIN
--   - Khi CHECKIN: MAHV phải có HOPDONG ACTIVE, ngày nằm trong khoảng
-- ============================================================

CREATE TRIGGER trg_checkin_check_membership
BEFORE INSERT ON CHECKIN
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    SELECT COUNT(*)
    INTO v_cnt
    FROM HOPDONG hd
    WHERE hd.MAHV = NEW.MAHV
      AND hd.TRANGTHAI = 'ACTIVE'
      AND hd.NGAYBD <= DATE(NEW.THOIGIAN)
      AND hd.NGAYKT >= DATE(NEW.THOIGIAN);

    IF v_cnt = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'CHECKIN bi tu choi: hoi vien khong co membership con han (HOPDONG ACTIVE).';
    END IF;
END$$

-- ============================================================
-- 2. CHECK MEMBERSHIP CÒN HẠN CHO DANGKY_LOP
--   - Khi ĐĂNG KÝ LỚP: MAHV phải có HOPDONG ACTIVE, ngày đăng ký nằm trong khoảng
-- ============================================================

CREATE TRIGGER trg_dangky_lop_check_membership
BEFORE INSERT ON DANGKY_LOP
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    SELECT COUNT(*)
    INTO v_cnt
    FROM HOPDONG hd
    WHERE hd.MAHV = NEW.MAHV
      AND hd.TRANGTHAI = 'ACTIVE'
      AND hd.NGAYBD <= DATE(NEW.NGAYDK)
      AND hd.NGAYKT >= DATE(NEW.NGAYDK);

    IF v_cnt = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Dang ky lop bi tu choi: hoi vien khong co membership con han (HOPDONG ACTIVE).';
    END IF;
END$$

-- ============================================================
-- 3. KHÔNG CHO VƯỢT SĨ SỐ LỚP (BUOILOP.SISO)
--   - Chỉ tính các đăng ký có TRANGTHAI <> 'CANCELLED'
-- ============================================================

CREATE TRIGGER trg_dangky_lop_check_capacity
BEFORE INSERT ON DANGKY_LOP
FOR EACH ROW
BEGIN
    DECLARE v_current INT;
    DECLARE v_limit   INT;

    -- Lấy sĩ số hiện tại (không tính CANCELLED)
    SELECT COUNT(*)
    INTO v_current
    FROM DANGKY_LOP dk
    WHERE dk.MABUOI = NEW.MABUOI
      AND dk.TRANGTHAI <> 'CANCELLED';

    -- Lấy sĩ số tối đa của buổi
    SELECT bl.SISO
    INTO v_limit
    FROM BUOILOP bl
    WHERE bl.MABUOI = NEW.MABUOI;

    IF v_limit IS NULL THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Buoi lop khong ton tai hoac chua co SISO.';
    ELSEIF (v_current + 1) > v_limit THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Dang ky lop bi tu choi: da vuot qua si so toi da cua buoi lop.';
    END IF;
END$$

-- ============================================================
-- 4. KHÔNG TRÙNG LỊCH PHÒNG (DATPHONG)
--   - Kiểm tra overlap:
--       NEW.BATDAU < KETTHUC_CU
--       AND NEW.KETTHUC > BATDAU_CU
-- ============================================================

CREATE TRIGGER trg_datphong_no_overlap
BEFORE INSERT ON DATPHONG
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    IF NEW.BATDAU >= NEW.KETTHUC THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'DATPHONG bi tu choi: BATDAU phai truoc KETTHUC.';
    END IF;

    SELECT COUNT(*)
    INTO v_cnt
    FROM DATPHONG dp
    WHERE dp.MAPHONG = NEW.MAPHONG
      AND dp.TRANGTHAI <> 'CANCELLED'
      AND dp.IDP != NEW.MADP -- (không cần trong INSERT, nhưng để an toàn, trường này không có, bỏ)
      AND NEW.BATDAU < dp.KETTHUC
      AND NEW.KETTHUC > dp.BATDAU;

    -- Lưu ý: bảng không có IDP, đoạn trên có thể gây lỗi, ta bỏ điều kiện ID khác
END$$

-- Sửa lại trigger DATPHONG với điều kiện đúng
DROP TRIGGER IF EXISTS trg_datphong_no_overlap$$

CREATE TRIGGER trg_datphong_no_overlap
BEFORE INSERT ON DATPHONG
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    IF NEW.BATDAU >= NEW.KETTHUC THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'DATPHONG bi tu choi: BATDAU phai truoc KETTHUC.';
    END IF;

    SELECT COUNT(*)
    INTO v_cnt
    FROM DATPHONG dp
    WHERE dp.MAPHONG = NEW.MAPHONG
      AND dp.TRANGTHAI <> 'CANCELLED'
      AND NEW.BATDAU < dp.KETTHUC
      AND NEW.KETTHUC > dp.BATDAU;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'DATPHONG bi tu choi: phong da duoc dat trong khoang thoi gian nay.';
    END IF;
END$$

-- Trigger cho UPDATE DATPHONG (đổi thời gian / phòng)
CREATE TRIGGER trg_datphong_no_overlap_update
BEFORE UPDATE ON DATPHONG
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    IF NEW.BATDAU >= NEW.KETTHUC THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cap nhat DATPHONG bi tu choi: BATDAU phai truoc KETTHUC.';
    END IF;

    SELECT COUNT(*)
    INTO v_cnt
    FROM DATPHONG dp
    WHERE dp.MAPHONG = NEW.MAPHONG
      AND dp.TRANGTHAI <> 'CANCELLED'
      AND dp.MADP <> OLD.MADP
      AND NEW.BATDAU < dp.KETTHUC
      AND NEW.KETTHUC > dp.BATDAU;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cap nhat DATPHONG bi tu choi: phong da duoc dat trong khoang thoi gian nay.';
    END IF;
END$$

-- ============================================================
-- 5. KHÔNG TRÙNG LỊCH PHÒNG CHO BUOILOP
-- ============================================================

CREATE TRIGGER trg_buoilop_no_overlap_room
BEFORE INSERT ON BUOILOP
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    IF NEW.BATDAU >= NEW.KETTHUC THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'BUOILOP bi tu choi: BATDAU phai truoc KETTHUC.';
    END IF;

    SELECT COUNT(*)
    INTO v_cnt
    FROM BUOILOP bl
    WHERE bl.MAPHONG = NEW.MAPHONG
      AND bl.TRANGTHAI <> 'CANCELLED'
      AND NEW.BATDAU < bl.KETTHUC
      AND NEW.KETTHUC > bl.BATDAU;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'BUOILOP bi tu choi: trung lich phong (MAPHONG) voi buoi lop khac.';
    END IF;
END$$

CREATE TRIGGER trg_buoilop_no_overlap_room_update
BEFORE UPDATE ON BUOILOP
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    IF NEW.BATDAU >= NEW.KETTHUC THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cap nhat BUOILOP bi tu choi: BATDAU phai truoc KETTHUC.';
    END IF;

    SELECT COUNT(*)
    INTO v_cnt
    FROM BUOILOP bl
    WHERE bl.MAPHONG = NEW.MAPHONG
      AND bl.TRANGTHAI <> 'CANCELLED'
      AND bl.MABUOI <> OLD.MABUOI
      AND NEW.BATDAU < bl.KETTHUC
      AND NEW.KETTHUC > bl.BATDAU;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cap nhat BUOILOP bi tu choi: trung lich phong (MAPHONG) voi buoi lop khac.';
    END IF;
END$$

-- ============================================================
-- 6. KHÔNG TRÙNG LỊCH HLV CHO BUOILOP
-- ============================================================

CREATE TRIGGER trg_buoilop_no_overlap_hlv
BEFORE INSERT ON BUOILOP
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    SELECT COUNT(*)
    INTO v_cnt
    FROM BUOILOP bl
    WHERE bl.MAHLV = NEW.MAHLV
      AND bl.TRANGTHAI <> 'CANCELLED'
      AND NEW.BATDAU < bl.KETTHUC
      AND NEW.KETTHUC > bl.BATDAU;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'BUOILOP bi tu choi: trung lich HLV (MAHLV) voi buoi lop khac.';
    END IF;
END$$

CREATE TRIGGER trg_buoilop_no_overlap_hlv_update
BEFORE UPDATE ON BUOILOP
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    SELECT COUNT(*)
    INTO v_cnt
    FROM BUOILOP bl
    WHERE bl.MAHLV = NEW.MAHLV
      AND bl.TRANGTHAI <> 'CANCELLED'
      AND bl.MABUOI <> OLD.MABUOI
      AND NEW.BATDAU < bl.KETTHUC
      AND NEW.KETTHUC > bl.BATDAU;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cap nhat BUOILOP bi tu choi: trung lich HLV (MAHLV) voi buoi lop khac.';
    END IF;
END$$

-- ============================================================
-- 7. KHÔNG TRÙNG LỊCH PHÒNG CHO PT_SESSION
-- ============================================================

CREATE TRIGGER trg_pt_no_overlap_room
BEFORE INSERT ON PT_SESSION
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    IF NEW.BATDAU >= NEW.KETTHUC THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'PT_SESSION bi tu choi: BATDAU phai truoc KETTHUC.';
    END IF;

    SELECT COUNT(*)
    INTO v_cnt
    FROM PT_SESSION pt
    WHERE pt.MAPHONG = NEW.MAPHONG
      AND pt.TRANGTHAI <> 'CANCELLED'
      AND NEW.BATDAU < pt.KETTHUC
      AND NEW.KETTHUC > pt.BATDAU;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'PT_SESSION bi tu choi: trung lich phong (MAPHONG) voi PT khac.';
    END IF;
END$$

CREATE TRIGGER trg_pt_no_overlap_room_update
BEFORE UPDATE ON PT_SESSION
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    IF NEW.BATDAU >= NEW.KETTHUC THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cap nhat PT_SESSION bi tu choi: BATDAU phai truoc KETTHUC.';
    END IF;

    SELECT COUNT(*)
    INTO v_cnt
    FROM PT_SESSION pt
    WHERE pt.MAPHONG = NEW.MAPHONG
      AND pt.TRANGTHAI <> 'CANCELLED'
      AND pt.MAPT <> OLD.MAPT
      AND NEW.BATDAU < pt.KETTHUC
      AND NEW.KETTHUC > pt.BATDAU;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cap nhat PT_SESSION bi tu choi: trung lich phong (MAPHONG) voi PT khac.';
    END IF;
END$$

-- ============================================================
-- 8. KHÔNG TRÙNG LỊCH HLV CHO PT_SESSION
-- ============================================================

CREATE TRIGGER trg_pt_no_overlap_hlv
BEFORE INSERT ON PT_SESSION
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    SELECT COUNT(*)
    INTO v_cnt
    FROM PT_SESSION pt
    WHERE pt.MAHLV = NEW.MAHLV
      AND pt.TRANGTHAI <> 'CANCELLED'
      AND NEW.BATDAU < pt.KETTHUC
      AND NEW.KETTHUC > pt.BATDAU;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'PT_SESSION bi tu choi: trung lich HLV (MAHLV) voi PT khac.';
    END IF;
END$$

CREATE TRIGGER trg_pt_no_overlap_hlv_update
BEFORE UPDATE ON PT_SESSION
FOR EACH ROW
BEGIN
    DECLARE v_cnt INT;

    SELECT COUNT(*)
    INTO v_cnt
    FROM PT_SESSION pt
    WHERE pt.MAHLV = NEW.MAHLV
      AND pt.TRANGTHAI <> 'CANCELLED'
      AND pt.MAPT <> OLD.MAPT
      AND NEW.BATDAU < pt.KETTHUC
      AND NEW.KETTHUC > pt.BATDAU;

    IF v_cnt > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cap nhat PT_SESSION bi tu choi: trung lich HLV (MAHLV) voi PT khac.';
    END IF;
END$$

-- ============================================================
-- 9. THANH TOÁN KHÔNG VƯỢT TỔNG HÓA ĐƠN
--   - Giả sử tổng tiền hóa đơn = SUM(DONGHOADON.SOLUONG * DONGHOADON.DONGIA)
--   - Trigger check trước INSERT / UPDATE THANHTOAN
-- ============================================================

CREATE TRIGGER trg_thanhtoan_check_total
BEFORE INSERT ON THANHTOAN
FOR EACH ROW
BEGIN
    DECLARE v_tong_hd   DECIMAL(15,2);
    DECLARE v_tong_tt   DECIMAL(15,2);
    DECLARE v_tong_moi  DECIMAL(15,2);

    -- Tổng tiền hóa đơn (chưa trừ khuyến mãi, nếu muốn trừ thì phải thêm logic tính theo KHUYENMAI)
    SELECT COALESCE(SUM(dh.SOLUONG * dh.DONGIA), 0)
    INTO v_tong_hd
    FROM DONGHOADON dh
    WHERE dh.MAHDON = NEW.MAHDON;

    -- Tổng tiền đã thanh toán trước đó
    SELECT COALESCE(SUM(tt.SOTIEN), 0)
    INTO v_tong_tt
    FROM THANHTOAN tt
    WHERE tt.MAHDON = NEW.MAHDON;

    SET v_tong_moi = v_tong_tt + NEW.SOTIEN;

    IF v_tong_moi > v_tong_hd THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'THANHTOAN bi tu choi: tong so tien thanh toan vuot tong gia tri hoa don.';
    END IF;
END$$

CREATE TRIGGER trg_thanhtoan_check_total_update
BEFORE UPDATE ON THANHTOAN
FOR EACH ROW
BEGIN
    DECLARE v_tong_hd   DECIMAL(15,2);
    DECLARE v_tong_tt   DECIMAL(15,2);
    DECLARE v_tong_moi  DECIMAL(15,2);

    -- Tổng tiền hóa đơn
    SELECT COALESCE(SUM(dh.SOLUONG * dh.DONGIA), 0)
    INTO v_tong_hd
    FROM DONGHOADON dh
    WHERE dh.MAHDON = NEW.MAHDON;

    -- Tổng tiền các lần thanh toán khác (không tính bản ghi đang update)
    SELECT COALESCE(SUM(tt.SOTIEN), 0)
    INTO v_tong_tt
    FROM THANHTOAN tt
    WHERE tt.MAHDON = NEW.MAHDON
      AND tt.MATTTOAN <> OLD.MATTTOAN;

    SET v_tong_moi = v_tong_tt + NEW.SOTIEN;

    IF v_tong_moi > v_tong_hd THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cap nhat THANHTOAN bi tu choi: tong so tien thanh toan vuot tong gia tri hoa don.';
    END IF;
END$$

DELIMITER ;

-- ============================================================
-- GỢI Ý CÁCH TEST (chạy riêng trong phpMyAdmin sau khi import)
-- ============================================================
-- 1. Test membership cho CHECKIN (nên thử hội viên không có HOPDONG ACTIVE):
--    INSERT INTO CHECKIN (MAHV, THOIGIAN) VALUES (5, '2025-02-01 10:00:00');
--
-- 2. Test membership cho DANGKY_LOP:
--    INSERT INTO DANGKY_LOP (MABUOI, MAHV, NGAYDK) VALUES (1, 5, '2025-02-01 10:00:00');
--
-- 3. Test sĩ số lớp:
--    Lặp INSERT nhiều lần vào DANGKY_LOP cùng MABUOI cho các MAHV khác nhau
--    đến khi vượt SISO trong BUOILOP.SISO.
--
-- 4. Test trùng lịch phòng DATPHONG:
--    INSERT INTO DATPHONG (MAPHONG, MAHV, BATDAU, KETTHUC, MUCTIEU, TRANGTHAI)
--    VALUES (1, 1, '2025-02-01 10:30:00', '2025-02-01 11:00:00', 'TAP_TU_DO', 'PENDING');
--
-- 5. Test trùng lịch HLV hoặc phòng cho BUOILOP / PT_SESSION bằng cách
--    tạo thêm buổi trùng khoảng thời gian với cùng MAHLV / MAPHONG.
--
-- 6. Test thanh toán vượt tổng:
--    Với hoa don 1 trong seed.sql:
--    INSERT INTO THANHTOAN (MAHDON, SOTIEN, PHUONGTHUC)
--    VALUES (1, 100000, 'CASH');  -- sẽ bị chặn vì tổng > tổng hóa đơn.