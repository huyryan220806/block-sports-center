<?php
class Trainer {
    private $conn;
    private $table = 'nhanvien';
    private $hlvTable = 'hlv';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT 
                    nv.*,
                    hlv.MOTA as CHUYENMON,
                    hlv.PHI_GIO
                  FROM {$this->table} nv
                  LEFT JOIN {$this->hlvTable} hlv ON nv.MANV = hlv.MAHLV
                  WHERE nv.VAITRO IN ('TRAINER', 'OTHER')
                  ORDER BY nv.HOTEN ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function find($id) {
        $query = "SELECT 
                    nv.*,
                    hlv.MOTA as CHUYENMON,
                    hlv.PHI_GIO,
                    hlv.MAHLV
                  FROM {$this->table} nv
                  LEFT JOIN {$this->hlvTable} hlv ON nv.MANV = hlv.MAHLV
                  WHERE nv.MANV = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function create($data) {
        try {
            $this->conn->beginTransaction();
            
            // Thêm nhân viên
            $query1 = "INSERT INTO {$this->table} 
                      (HOTEN, TENDANGNHAP, MATKHAU, SDT, EMAIL, VAITRO, NGAYVAOLAM, TRANGTHAI) 
                      VALUES 
                      (:hoten, :tendangnhap, :matkhau, :sdt, :email, 'TRAINER', :ngayvaolam, :trangthai)";
            
            $stmt1 = $this->conn->prepare($query1);
            
            $matkhau = password_hash($data['matkhau'], PASSWORD_DEFAULT);
            
            $stmt1->bindParam(':hoten', $data['hoten']);
            $stmt1->bindParam(':tendangnhap', $data['tendangnhap']);
            $stmt1->bindParam(':matkhau', $matkhau);
            $stmt1->bindParam(':sdt', $data['sdt']);
            $stmt1->bindParam(':email', $data['email']);
            $stmt1->bindParam(':ngayvaolam', $data['ngayvaolam']);
            $stmt1->bindParam(':trangthai', $data['trangthai']);
            
            $stmt1->execute();
            $manv = $this->conn->lastInsertId();
            
            // Thêm thông tin HLV
            $query2 = "INSERT INTO {$this->hlvTable} 
                      (MAHLV, MOTA, PHI_GIO) 
                      VALUES 
                      (:mahlv, :mota, :phi_gio)";
            
            $stmt2 = $this->conn->prepare($query2);
            
            $stmt2->bindParam(':mahlv', $manv);
            $stmt2->bindParam(':mota', $data['chuyenmon']);
            $stmt2->bindParam(':phi_gio', $data['phi_gio']);
            
            $stmt2->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $this->conn->beginTransaction();
            
            // Cập nhật nhân viên
            $query1 = "UPDATE {$this->table} SET 
                      HOTEN = :hoten,
                      TENDANGNHAP = :tendangnhap,
                      SDT = :sdt,
                      EMAIL = :email,
                      NGAYVAOLAM = :ngayvaolam,
                      TRANGTHAI = :trangthai
                      WHERE MANV = :id";
            
            $stmt1 = $this->conn->prepare($query1);
            
            $stmt1->bindParam(':id', $id);
            $stmt1->bindParam(':hoten', $data['hoten']);
            $stmt1->bindParam(':tendangnhap', $data['tendangnhap']);
            $stmt1->bindParam(':sdt', $data['sdt']);
            $stmt1->bindParam(':email', $data['email']);
            $stmt1->bindParam(':ngayvaolam', $data['ngayvaolam']);
            $stmt1->bindParam(':trangthai', $data['trangthai']);
            
            $stmt1->execute();
            
            // Cập nhật hoặc thêm thông tin HLV
            $checkHlv = "SELECT MAHLV FROM {$this->hlvTable} WHERE MAHLV = :id";
            $stmtCheck = $this->conn->prepare($checkHlv);
            $stmtCheck->bindParam(':id', $id);
            $stmtCheck->execute();
            
            if ($stmtCheck->rowCount() > 0) {
                // Cập nhật
                $query2 = "UPDATE {$this->hlvTable} SET 
                          MOTA = :mota,
                          PHI_GIO = :phi_gio
                          WHERE MAHLV = :id";
            } else {
                // Thêm mới
                $query2 = "INSERT INTO {$this->hlvTable} 
                          (MAHLV, MOTA, PHI_GIO) 
                          VALUES 
                          (:id, :mota, :phi_gio)";
            }
            
            $stmt2 = $this->conn->prepare($query2);
            
            $stmt2->bindParam(':id', $id);
            $stmt2->bindParam(':mota', $data['chuyenmon']);
            $stmt2->bindParam(':phi_gio', $data['phi_gio']);
            
            $stmt2->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function delete($id) {
        try {
            $this->conn->beginTransaction();
            
            // Xóa thông tin HLV
            $query1 = "DELETE FROM {$this->hlvTable} WHERE MAHLV = :id";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(':id', $id);
            $stmt1->execute();
            
            // Xóa nhân viên
            $query2 = "DELETE FROM {$this->table} WHERE MANV = :id";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':id', $id);
            $stmt2->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function count() {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE VAITRO = 'TRAINER' OR VAITRO = 'ADMIN'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
}
?>