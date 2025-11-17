<?php
class Locker {
    private $conn;
    private $table = 'locker';

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Lấy tất cả tủ khóa, sắp xếp theo MATU tăng dần
    public function getAll() {
        $query = "SELECT 
                    l.*,
                    p.TENPHONG
                  FROM {$this->table} l
                  LEFT JOIN phong p ON l.MAPHONG = p.MAPHONG
                  ORDER BY l.MATU ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 2. Lấy 1 tủ theo MATU
    public function find($id) {
        $query = "SELECT * FROM {$this->table} WHERE MATU = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // 3. Thêm tủ mới
    public function create($data) {
        $query = "INSERT INTO {$this->table} (MAPHONG, KITU, HOATDONG)
                  VALUES (:maphong, :kitu, :hoatdong)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maphong', $data['maphong']);
        $stmt->bindParam(':kitu', $data['kitu']);
        $stmt->bindParam(':hoatdong', $data['hoatdong']);
        return $stmt->execute();
    }

    // 4. Cập nhật thông tin tủ
    public function update($id, $data) {
        $query = "UPDATE {$this->table} 
                  SET MAPHONG = :maphong, KITU = :kitu, HOATDONG = :hoatdong
                  WHERE MATU = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':maphong', $data['maphong']);
        $stmt->bindParam(':kitu', $data['kitu']);
        $stmt->bindParam(':hoatdong', $data['hoatdong']);
        return $stmt->execute();
    }

    // 5. Xóa tủ
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE MATU = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // 6. Lấy danh sách phòng/sân cho dropdown
    public function getRooms() {
        $query = "SELECT MAPHONG, TENPHONG FROM phong ORDER BY MAPHONG ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 7. Đếm tổng số tủ
    public function count() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total ?? 0;
    }
}
?>