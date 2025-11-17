<?php
class Invoice {
    private $conn;
    private $table = 'hoadon';
    public function __construct($db) {
        $this->conn = $db;
    }
    // Lấy tất cả hóa đơn, sắp xếp theo MAHDON tăng dần
    public function getAll() {
    $query = "SELECT h.*, hv.HOVATEN as TEN_HOIVIEN,
              km.MOTA as TEN_KHUYENMAI
              FROM {$this->table} h
              LEFT JOIN hoivien hv ON h.MAHV = hv.MAHV
              LEFT JOIN khuyenmai km ON h.MAKM = km.MAKM
              ORDER BY h.MAHDON ASC";
    $stmt = $this->conn->prepare($query); $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
    // Lấy chi tiết hóa đơn
    public function find($id) {
        $query = "SELECT h.*, hv.HOVATEN, hv.SODIENTHOAI, hv.EMAIL FROM {$this->table} h
                  LEFT JOIN hoivien hv ON h.MAHV = hv.MAHV
                  WHERE h.MAHDON = :id";
        $stmt = $this->conn->prepare($query); $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    // Thêm hóa đơn mới
    public function create($data) {
        $query = "INSERT INTO {$this->table} (MAHV, MAKM, NGAYLAP, TRANGTHAI)
                  VALUES (:mahv, :makm, :ngaylap, :trangthai)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':mahv', $data['mahv']);
        $stmt->bindParam(':makm', $data['makm']);
        $stmt->bindParam(':ngaylap', $data['ngaylap']);
        $stmt->bindParam(':trangthai', $data['trangthai']);
        return $stmt->execute();
    }
    // Sửa hóa đơn
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET MAHV=:mahv, MAKM=:makm, NGAYLAP=:ngaylap, TRANGTHAI=:trangthai WHERE MAHDON=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':mahv', $data['mahv']);
        $stmt->bindParam(':makm', $data['makm']);
        $stmt->bindParam(':ngaylap', $data['ngaylap']);
        $stmt->bindParam(':trangthai', $data['trangthai']);
        return $stmt->execute();
    }
    // Xóa hóa đơn
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE MAHDON=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    // Lấy danh sách hội viên cho select
    public function getMembers() {
        $query = "SELECT MAHV, HOVATEN FROM hoivien ORDER BY HOVATEN";
        $stmt = $this->conn->prepare($query); $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    // Lấy danh sách khuyến mãi cho select (MOTA là mô tả khuyến mãi)
    public function getPromotions() {
        $query = "SELECT MAKM, MOTA FROM khuyenmai ORDER BY MAKM";
        $stmt = $this->conn->prepare($query); $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>