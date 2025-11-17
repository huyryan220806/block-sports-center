<?php
// app/models/Room.php

class Room extends Model {
    protected $table = 'PHONG';
    
    // 1. getAll() - Lấy tất cả phòng
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY MAPHONG ASC";
        //                                              ^^^ ĐỔI TỪ DESC → ASC
        return $this->db->query($sql)->fetchAll();
    }
    
    // 2. find($id) - Tìm 1 phòng
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE MAPHONG = ?";
        return $this->db->query($sql, [$id])->fetch();
    }
    
    // 3. create($data) - Thêm phòng mới
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (TENPHONG, LOAIPHONG, SUCCHUA, TRANGTHAI, MOTA) 
                VALUES (?, ?, ?, ?, ?)";
        
        $params = [
            $data['tenphong'],
            $data['loaiphong'] ?? 'Phòng tập',
            $data['succhua'] ?? 20,
            $data['trangthai'] ?? 'Hoạt động',
            $data['mota'] ?? null
        ];
        
        $this->db->execute($sql, $params);
        return $this->db->lastInsertId();
    }
    
    // 4. update($id, $data) - Cập nhật phòng
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET TENPHONG = ?, LOAIPHONG = ?, SUCCHUA = ?, 
                    TRANGTHAI = ?, MOTA = ?
                WHERE MAPHONG = ?";
        
        $params = [
            $data['tenphong'],
            $data['loaiphong'],
            $data['succhua'],
            $data['trangthai'],
            $data['mota'] ?? null,
            $id
        ];
        
        return $this->db->execute($sql, $params);
    }
    
    // 5. delete($id) - Xóa phòng
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE MAPHONG = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    // 6. search($keyword) - Tìm kiếm
    public function search($keyword) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE TENPHONG LIKE ? OR LOAIPHONG LIKE ?
                ORDER BY MAPHONG ASC";
        //               ^^^ ĐỔI TỪ DESC → ASC
        
        $keyword = "%{$keyword}%";
        return $this->db->query($sql, [$keyword, $keyword])->fetchAll();
    }
    
    // 7. count() - Đếm tổng số phòng
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->query($sql)->fetch();
        return $result->total ?? 0;
    }
    
    // 8. getByStatus($status) - Lấy theo trạng thái
    public function getByStatus($status) {
        $sql = "SELECT * FROM {$this->table} WHERE TRANGTHAI = ? ORDER BY MAPHONG ASC";
        //                                                               ^^^ ĐỔI TỪ DESC → ASC
        return $this->db->query($sql, [$status])->fetchAll();
    }
}