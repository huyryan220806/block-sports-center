<?php

class Employee
{
    private $db;
    private $table = 'nhanvien';

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Lấy tất cả nhân viên
     */
    public function getAll()
    {
        try {
            $query = "SELECT * FROM {$this->table} ORDER BY MANV DESC";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Employee getAll error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy nhân viên theo ID
     */
    public function getById($id)
    {
        try {
            $query = "SELECT * FROM {$this->table} WHERE MANV = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Employee getById error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo nhân viên mới
     * Cấu trúc bảng: MANV, HOTEN, SDT, EMAIL, VAITRO, NGAYVAOLAM, TRANGTHAI
     */
    public function create($data)
    {
        try {
            $query = "INSERT INTO {$this->table} 
                      (HOTEN, SDT, EMAIL, VAITRO, NGAYVAOLAM, TRANGTHAI) 
                      VALUES 
                      (:hoten, :sdt, :email, :vaitro, :ngayvaolam, :trangthai)";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':hoten' => $data['hoten'],
                ':sdt' => $data['sdt'] ?? null,
                ':email' => $data['email'] ?? null,
                ':vaitro' => $data['vaitro'] ?? 'OTHER',
                ':ngayvaolam' => $data['ngayvaolam'] ?? date('Y-m-d'),
                ':trangthai' => $data['trangthai'] ?? 1
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Employee create error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật nhân viên
     */
    public function update($id, $data)
    {
        try {
            $query = "UPDATE {$this->table} SET
                      HOTEN = :hoten,
                      SDT = :sdt,
                      EMAIL = :email,
                      VAITRO = :vaitro,
                      NGAYVAOLAM = :ngayvaolam,
                      TRANGTHAI = :trangthai
                      WHERE MANV = :id";

            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':id' => $id,
                ':hoten' => $data['hoten'],
                ':sdt' => $data['sdt'],
                ':email' => $data['email'],
                ':vaitro' => $data['vaitro'],
                ':ngayvaolam' => $data['ngayvaolam'],
                ':trangthai' => $data['trangthai']
            ]);
        } catch (PDOException $e) {
            error_log("Employee update error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa nhân viên
     */
    public function delete($id)
    {
        try {
            $query = "DELETE FROM {$this->table} WHERE MANV = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Employee delete error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tìm kiếm nhân viên
     */
    public function search($keyword)
    {
        try {
            $query = "SELECT * FROM {$this->table} 
                      WHERE HOTEN LIKE :keyword 
                      OR SDT LIKE :keyword 
                      OR EMAIL LIKE :keyword
                      ORDER BY MANV DESC";

            $stmt = $this->db->prepare($query);
            $searchTerm = '%' . $keyword . '%';
            $stmt->bindParam(':keyword', $searchTerm);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Employee search error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy nhân viên theo vai trò
     */
    public function getByRole($role)
    {
        try {
            $query = "SELECT * FROM {$this->table} 
                      WHERE VAITRO = :role 
                      AND TRANGTHAI = 1
                      ORDER BY HOTEN ASC";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':role', $role);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Employee getByRole error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Đếm tổng nhân viên
     */
    public function count($status = null)
    {
        try {
            $query = "SELECT COUNT(*) as total FROM {$this->table}";
            
            if ($status !== null) {
                $query .= " WHERE TRANGTHAI = :status";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':status', $status, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                $stmt = $this->db->query($query);
            }

            return $stmt->fetch(PDO::FETCH_OBJ)->total;
        } catch (PDOException $e) {
            error_log("Employee count error: " . $e->getMessage());
            return 0;
        }
    }
}
/* */