<?php
class Booking {
    private $conn;
    private $table = 'datphong';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT 
                    dp.*,
                    hv.HOVATEN as TEN_HOIVIEN,
                    p.TENPHONG
                  FROM {$this->table} dp
                  LEFT JOIN hoivien hv ON dp.MAHV = hv.MAHV
                  LEFT JOIN phong p ON dp.MAPHONG = p.MAPHONG
                  ORDER BY dp.MADP ASC";
        //         ^^^^^^^^^^^^^^ ĐỔI TỪ dp.BATDAU DESC → dp.MADP ASC
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function find($id) {
        $query = "SELECT * FROM {$this->table} WHERE MADP = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (MAHV, MAPHONG, BATDAU, KETTHUC, MUCTIEU, TRANGTHAI) 
                  VALUES 
                  (:mahv, :maphong, :batdau, :ketthuc, :muctieu, :trangthai)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':mahv', $data['mahv']);
        $stmt->bindParam(':maphong', $data['maphong']);
        $stmt->bindParam(':batdau', $data['batdau']);
        $stmt->bindParam(':ketthuc', $data['ketthuc']);
        $stmt->bindParam(':muctieu', $data['muctieu']);
        $stmt->bindParam(':trangthai', $data['trangthai']);
        
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET 
                  MAHV = :mahv,
                  MAPHONG = :maphong,
                  BATDAU = :batdau,
                  KETTHUC = :ketthuc,
                  MUCTIEU = :muctieu,
                  TRANGTHAI = :trangthai
                  WHERE MADP = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':mahv', $data['mahv']);
        $stmt->bindParam(':maphong', $data['maphong']);
        $stmt->bindParam(':batdau', $data['batdau']);
        $stmt->bindParam(':ketthuc', $data['ketthuc']);
        $stmt->bindParam(':muctieu', $data['muctieu']);
        $stmt->bindParam(':trangthai', $data['trangthai']);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE MADP = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getMembers() {
        $query = "SELECT MAHV, HOVATEN FROM hoivien ORDER BY HOVATEN";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getRooms() {
        $query = "SELECT MAPHONG, TENPHONG FROM phong ORDER BY MAPHONG ASC";
        //                                            ^^^^^^^^^^^^^^^^^ THÊM ORDER BY
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function count() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
}
//