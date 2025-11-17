<?php
class Session {
    private $conn;
    private $table = 'buoilop';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT 
                    bl.*,
                    l.TENLOP,
                    p.TENPHONG,
                    nv.HOTEN as TEN_HLV
                  FROM {$this->table} bl
                  LEFT JOIN lop l ON bl.MALOP = l.MALOP
                  LEFT JOIN phong p ON bl.MAPHONG = p.MAPHONG
                  LEFT JOIN nhanvien nv ON bl.MAHLV = nv.MANV
                  ORDER BY bl.BATDAU ASC";
        //         ^^^^^^^^^^^^^^^^^^^^^ ĐỔI TỪ DESC → ASC
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function find($id) {
        $query = "SELECT * FROM {$this->table} WHERE MABUOI = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (MALOP, MAPHONG, MAHLV, BATDAU, KETTHUC, SISO, TRANGTHAI) 
                  VALUES 
                  (:malop, :maphong, :mahlv, :batdau, :ketthuc, :siso, :trangthai)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':malop', $data['malop']);
        $stmt->bindParam(':maphong', $data['maphong']);
        $stmt->bindParam(':mahlv', $data['mahlv']);
        $stmt->bindParam(':batdau', $data['batdau']);
        $stmt->bindParam(':ketthuc', $data['ketthuc']);
        $stmt->bindParam(':siso', $data['siso']);
        $stmt->bindParam(':trangthai', $data['trangthai']);
        
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET 
                  MALOP = :malop,
                  MAPHONG = :maphong,
                  MAHLV = :mahlv,
                  BATDAU = :batdau,
                  KETTHUC = :ketthuc,
                  SISO = :siso,
                  TRANGTHAI = :trangthai
                  WHERE MABUOI = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':malop', $data['malop']);
        $stmt->bindParam(':maphong', $data['maphong']);
        $stmt->bindParam(':mahlv', $data['mahlv']);
        $stmt->bindParam(':batdau', $data['batdau']);
        $stmt->bindParam(':ketthuc', $data['ketthuc']);
        $stmt->bindParam(':siso', $data['siso']);
        $stmt->bindParam(':trangthai', $data['trangthai']);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE MABUOI = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getClasses() {
        $query = "SELECT MALOP, TENLOP FROM lop ORDER BY MALOP ASC";
        //                                       ^^^^^^^^^^^^^^^^^ ĐỔI TỪ TENLOP → MALOP ASC
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getRooms() {
        $query = "SELECT MAPHONG, TENPHONG FROM phong ORDER BY MAPHONG ASC";
        //                                            ^^^^^^^^^^^^^^^^^^^ ĐỔI TỪ TENPHONG → MAPHONG ASC
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTrainers() {
        $query = "SELECT MANV, HOTEN FROM nhanvien WHERE VAITRO = 'TRAINER' OR VAITRO = 'ADMIN' ORDER BY HOTEN";
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
?>