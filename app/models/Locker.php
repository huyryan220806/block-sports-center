<?php

require_once __DIR__ . '/../core/Database.php';

class Locker {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // ========================================
    // LẤY TẤT CẢ TỦ (CÓ PHÂN TRANG)
    // ========================================
    public function getAll($limit = 10, $offset = 0) {
        $sql = "SELECT l.*, p.TENPHONG, k.TENKHU
                FROM locker l
                INNER JOIN phong p ON l.MAPHONG = p.MAPHONG
                INNER JOIN khu k ON p.MAKHU = k.MAKHU
                ORDER BY l.MATU DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    // ========================================
    // ĐẾM TỔNG SỐ TỦ - ✅ FIXED: 2 PLACEHOLDER
    // ========================================
    public function count($keyword = '', $roomId = '') {
        $sql = "SELECT COUNT(*) FROM locker l
                INNER JOIN phong p ON l.MAPHONG = p.MAPHONG
                WHERE 1=1";
        
        $params = [];
        
        // ✅ DÙNG 2 PLACEHOLDER KHÁC NHAU: keyword1 và keyword2
        if (!empty($keyword)) {
            $sql .= " AND (l.KITU LIKE :keyword1 OR p.TENPHONG LIKE :keyword2)";
            $params[':keyword1'] = '%' . $keyword . '%';
            $params[':keyword2'] = '%' . $keyword . '%';
        }
        
        if (!empty($roomId)) {
            $sql .= " AND l.MAPHONG = :room";
            $params[':room'] = $roomId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
    
    // ========================================
    // TÌM KIẾM TỦ - ✅ FIXED: 2 PLACEHOLDER
    // ========================================
    public function search($keyword = '', $roomId = '', $limit = 10, $offset = 0) {
        $sql = "SELECT l.*, p.TENPHONG, k.TENKHU
                FROM locker l
                INNER JOIN phong p ON l.MAPHONG = p.MAPHONG
                INNER JOIN khu k ON p.MAKHU = k.MAKHU
                WHERE 1=1";
        
        $params = [];
        
        // ✅ DÙNG 2 PLACEHOLDER KHÁC NHAU
        if (!empty($keyword)) {
            $sql .= " AND (l.KITU LIKE :keyword1 OR p.TENPHONG LIKE :keyword2)";
            $params[':keyword1'] = '%' . $keyword . '%';
            $params[':keyword2'] = '%' . $keyword . '%';
        }
        
        if (!empty($roomId)) {
            $sql .= " AND l.MAPHONG = :room";
            $params[':room'] = $roomId;
        }
        
        $sql .= " ORDER BY l.MATU DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        // Bind search params
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        // Bind pagination
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    // ========================================
    // LẤY THỐNG KÊ
    // ========================================
    public function getStatistics() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN HOATDONG = 1 THEN 1 ELSE 0 END) as available,
                SUM(CASE WHEN HOATDONG = 0 THEN 1 ELSE 0 END) as occupied
                FROM locker";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // ========================================
    // LẤY TỦ THEO ID
    // ========================================
    public function getById($id) {
        $sql = "SELECT l.*, p.TENPHONG
                FROM locker l
                INNER JOIN phong p ON l.MAPHONG = p.MAPHONG
                WHERE l.MATU = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    // ========================================
    // KIỂM TRA TRÙNG
    // ========================================
    public function checkDuplicate($maphong, $kitu) {
        $sql = "SELECT COUNT(*) FROM locker 
                WHERE MAPHONG = :maphong AND KITU = :kitu";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':maphong' => $maphong,
            ':kitu' => $kitu
        ]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    // ========================================
    // KIỂM TRA TRÙNG (TRỪ CHÍNH NÓ)
    // ========================================
    public function checkDuplicateExcept($maphong, $kitu, $exceptId) {
        $sql = "SELECT COUNT(*) FROM locker 
                WHERE MAPHONG = :maphong 
                AND KITU = :kitu 
                AND MATU != :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':maphong' => $maphong,
            ':kitu' => $kitu,
            ':id' => $exceptId
        ]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    // ========================================
    // TẠO TỦ MỚI
    // ========================================
    public function create($data) {
        $sql = "INSERT INTO locker (MAPHONG, KITU, HOATDONG) 
                VALUES (:maphong, :kitu, :hoatdong)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':maphong' => $data['MAPHONG'],
            ':kitu' => $data['KITU'],
            ':hoatdong' => $data['HOATDONG']
        ]);
    }
    
    // ========================================
    // CẬP NHẬT TỦ
    // ========================================
    public function update($id, $data) {
        $sql = "UPDATE locker 
                SET MAPHONG = :maphong,
                    KITU = :kitu,
                    HOATDONG = :hoatdong
                WHERE MATU = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':maphong' => $data['MAPHONG'],
            ':kitu' => $data['KITU'],
            ':hoatdong' => $data['HOATDONG'],
            ':id' => $id
        ]);
    }
    
    // ========================================
    // KIỂM TRA TỦ CÓ ĐANG ĐƯỢC SỬ DỤNG
    // ========================================
    public function isInUse($id) {
        $sql = "SELECT COUNT(*) FROM thuetu 
                WHERE MATU = :id 
                AND TRANGTHAI = 'ACTIVE'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() > 0;
    }
    
    // ========================================
    // XÓA TỦ
    // ========================================
    public function delete($id) {
        $sql = "DELETE FROM locker WHERE MATU = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}