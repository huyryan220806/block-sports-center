<?php
// app/models/Member.php - MODEL HỘI VIÊN

// Version: FINAL (Không có GHICHU - theo cấu trúc DB của Team UI)

class Member extends Model {
    protected $table = 'HOIVIEN';
    
    // 1. getAll() - Lấy tất cả hội viên
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY MAHV DESC";
        return $this->db->query($sql)->fetchAll();
    }
    
    // 2. find($id) - Tìm 1 hội viên
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE MAHV = ?";
        return $this->db->query($sql, [$id])->fetch();
    }
    
    // 3. create($data) - Thêm hội viên mới
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (HOTEN, NGAYSINH, GIOITINH, SODIENTHOAI, EMAIL, DIACHI, NGAYTHAMGIA, TRANGTHAI) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['hoten'],
            $data['ngaysinh'] ?? null,
            $data['gioitinh'] ?? 'Nam',
            $data['sodienthoai'],
            $data['email'] ?? null,
            $data['diachi'] ?? null,
            date('Y-m-d'),
            $data['trangthai'] ?? 'Hoạt động'
        ];
        
        $this->db->execute($sql, $params);
        return $this->db->lastInsertId();
    }
    
    // 4. update($id, $data) - Cập nhật hội viên
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET HOTEN = ?, NGAYSINH = ?, GIOITINH = ?, SODIENTHOAI = ?, 
                    EMAIL = ?, DIACHI = ?, TRANGTHAI = ?
                WHERE MAHOIVIEN = ?";
        
        $params = [
            $data['hoten'],
            $data['ngaysinh'] ?? null,
            $data['gioitinh'] ?? 'Nam',
            $data['sodienthoai'],
            $data['email'] ?? null,
            $data['diachi'] ?? null,
            $data['trangthai'] ?? 'Hoạt động',
            $id
        ];
        
        return $this->db->execute($sql, $params);
    }
    
    // 5. delete($id) - Xóa hội viên
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE MAHOIVIEN = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    // Bonus: Tìm kiếm
    public function search($keyword) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE HOTEN LIKE ? OR SODIENTHOAI LIKE ? OR EMAIL LIKE ?
                ORDER BY MAHOIVIEN DESC";
        
        $keyword = "%{$keyword}%";
        return $this->db->query($sql, [$keyword, $keyword, $keyword])->fetchAll();
    }
}