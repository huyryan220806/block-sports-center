<?php

class Session
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * ✅ Lấy danh sách buổi lớp sắp diễn ra (7 ngày tới)
     */
    public function getUpcoming($limit = 10)
    {
        try {
            $query = "
                SELECT 
                    bl.MABUOI,
                    bl.BATDAU,
                    bl.KETTHUC,
                    bl.SISO,
                    bl.TRANGTHAI,
                    l.TENLOP,
                    p.TENPHONG,
                    p.SUCCHUA,
                    nv.HOTEN as TEN_HLV
                FROM buoilop bl
                INNER JOIN lop l ON bl.MALOP = l.MALOP
                INNER JOIN phong p ON bl.MAPHONG = p.MAPHONG
                INNER JOIN hlv h ON bl.MAHLV = h.MAHLV
                INNER JOIN nhanvien nv ON h.MAHLV = nv.MANV
                WHERE bl.BATDAU BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)
                AND bl.TRANGTHAI IN ('SCHEDULED', 'ONGOING')
                ORDER BY bl.BATDAU ASC
                LIMIT :limit
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            error_log("Session getUpcoming error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ✅ Lấy danh sách tất cả buổi lớp
     */
    public function getAll()
    {
        try {
            $query = "
                SELECT 
                    bl.*,
                    l.TENLOP,
                    p.TENPHONG,
                    p.SUCCHUA,
                    nv.HOTEN AS TEN_HLV
                FROM buoilop bl
                INNER JOIN lop l ON bl.MALOP = l.MALOP
                INNER JOIN phong p ON bl.MAPHONG = p.MAPHONG
                INNER JOIN hlv h ON bl.MAHLV = h.MAHLV
                INNER JOIN nhanvien nv ON h.MAHLV = nv.MANV
                ORDER BY bl.BATDAU DESC
            ";

            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            error_log("Session getAll error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ✅ Lấy thông tin buổi lớp theo ID
     */
    public function getById($id)
    {
        try {
            $query = "
                SELECT 
                    bl.*,
                    l.TENLOP,
                    p.TENPHONG,
                    p.SUCCHUA,
                    nv.HOTEN AS TEN_HLV
                FROM buoilop bl
                INNER JOIN lop l ON bl.MALOP = l.MALOP
                INNER JOIN phong p ON bl.MAPHONG = p.MAPHONG
                INNER JOIN hlv h ON bl.MAHLV = h.MAHLV
                INNER JOIN nhanvien nv ON h.MAHLV = nv.MANV
                WHERE bl.MABUOI = :id
                LIMIT 1
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            error_log("Session getById error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * ✅ Thêm buổi lớp mới
     */
    public function create($data)
    {
        try {
            $query = "
                INSERT INTO buoilop (MALOP, MAPHONG, MAHLV, BATDAU, KETTHUC, SISO, TRANGTHAI)
                VALUES (:malop, :maphong, :mahlv, :batdau, :ketthuc, :siso, 'SCHEDULED')
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':malop' => $data['malop'],
                ':maphong' => $data['maphong'],
                ':mahlv' => $data['mahlv'],
                ':batdau' => $data['batdau'],
                ':ketthuc' => $data['ketthuc'],
                ':siso' => $data['siso'] ?? 0
            ]);

            return $this->db->lastInsertId();

        } catch (PDOException $e) {
            error_log("Session create error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Cập nhật buổi lớp
     */
    public function update($id, $data)
    {
        try {
            $query = "
                UPDATE buoilop 
                SET MALOP = :malop,
                    MAPHONG = :maphong,
                    MAHLV = :mahlv,
                    BATDAU = :batdau,
                    KETTHUC = :ketthuc,
                    SISO = :siso,
                    TRANGTHAI = :trangthai
                WHERE MABUOI = :id
            ";

            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':id' => $id,
                ':malop' => $data['malop'],
                ':maphong' => $data['maphong'],
                ':mahlv' => $data['mahlv'],
                ':batdau' => $data['batdau'],
                ':ketthuc' => $data['ketthuc'],
                ':siso' => $data['siso'] ?? 0,
                ':trangthai' => $data['trangthai'] ?? 'SCHEDULED'
            ]);

        } catch (PDOException $e) {
            error_log("Session update error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Xóa buổi lớp
     */
    public function delete($id)
    {
        try {
            $query = "DELETE FROM buoilop WHERE MABUOI = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Session delete error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Lấy buổi lớp theo lớp học
     */
    public function getByClass($classId)
    {
        try {
            $query = "
                SELECT 
                    bl.*,
                    p.TENPHONG,
                    p.SUCCHUA,
                    nv.HOTEN AS TEN_HLV
                FROM buoilop bl
                INNER JOIN phong p ON bl.MAPHONG = p.MAPHONG
                INNER JOIN hlv h ON bl.MAHLV = h.MAHLV
                INNER JOIN nhanvien nv ON h.MAHLV = nv.MANV
                WHERE bl.MALOP = :classId
                ORDER BY bl.BATDAU ASC
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            error_log("Session getByClass error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ✅ Kiểm tra xung đột lịch phòng
     */
    public function hasConflict($roomId, $start, $end, $excludeSessionId = null)
    {
        try {
            $query = "
                SELECT COUNT(*) as count
                FROM buoilop
                WHERE MAPHONG = :roomId
                AND TRANGTHAI IN ('SCHEDULED', 'ONGOING')
                AND (
                    (BATDAU <= :start AND KETTHUC > :start)
                    OR (BATDAU < :end AND KETTHUC >= :end)
                    OR (BATDAU >= :start AND KETTHUC <= :end)
                )
            ";

            if ($excludeSessionId) {
                $query .= " AND MABUOI != :excludeSessionId";
            }

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':roomId', $roomId, PDO::PARAM_INT);
            $stmt->bindParam(':start', $start);
            $stmt->bindParam(':end', $end);

            if ($excludeSessionId) {
                $stmt->bindParam(':excludeSessionId', $excludeSessionId, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            return $result->count > 0;

        } catch (PDOException $e) {
            error_log("Session hasConflict error: " . $e->getMessage());
            return false;
        }
    }
}