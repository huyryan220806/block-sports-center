<?php

require_once __DIR__ . '/../core/Database.php';

class Room {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // ========================================
    // LẤY TẤT CẢ PHÒNG (JOIN VỚI KHU)
    // ========================================
    public function getAll() {
        $sql = "SELECT p.*, k.TENKHU 
                FROM phong p
                INNER JOIN khu k ON p.MAKHU = k.MAKHU
                WHERE p.HOATDONG = 1
                ORDER BY p.TENPHONG";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    // ========================================
    // LẤY PHÒNG THEO ID
    // ========================================
    public function getById($id) {
        $sql = "SELECT p.*, k.TENKHU 
                FROM phong p
                INNER JOIN khu k ON p.MAKHU = k.MAKHU
                WHERE p.MAPHONG = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    // ========================================
    // TÌM KIẾM PHÒNG
    // ========================================
    public function search($keyword) {
        $sql = "SELECT p.*, k.TENKHU 
                FROM phong p
                INNER JOIN khu k ON p.MAKHU = k.MAKHU
                WHERE p.TENPHONG LIKE :keyword
                OR k.TENKHU LIKE :keyword
                ORDER BY p.TENPHONG";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':keyword' => '%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
//