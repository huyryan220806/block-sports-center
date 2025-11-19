<?php
class ClassModel {
    private $conn;
    private $table = 'lop';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY MALOP ASC";
        //                                       ^^^^^^^^^^^^^ ĐỔI TỪ TENLOP ASC → MALOP ASC
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function find($id) {
        $query = "SELECT * FROM {$this->table} WHERE MALOP = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (TENLOP, THOILUONG, SISO_MACHINHI, MOTA) 
                  VALUES 
                  (:tenlop, :thoiluong, :siso, :mota)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':tenlop', $data['tenlop']);
        $stmt->bindParam(':thoiluong', $data['thoiluong']);
        $stmt->bindParam(':siso', $data['siso']);
        $stmt->bindParam(':mota', $data['mota']);
        
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET 
                  TENLOP = :tenlop,
                  THOILUONG = :thoiluong,
                  SISO_MACHINHI = :siso,
                  MOTA = :mota
                  WHERE MALOP = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':tenlop', $data['tenlop']);
        $stmt->bindParam(':thoiluong', $data['thoiluong']);
        $stmt->bindParam(':siso', $data['siso']);
        $stmt->bindParam(':mota', $data['mota']);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE MALOP = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
//
    public function count() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
}
?>

