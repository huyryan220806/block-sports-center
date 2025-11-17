<?php
class Report {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    // Tổng số hóa đơn
    public function totalInvoices() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM hoadon");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }
    // Tổng doanh thu (giả sử có cột THANHTIEN trong hoadon, nếu chưa có có thể SUM theo chi tiết)
    public function totalRevenue() {
        // Nếu tổng tiền nằm ở bảng chi tiết, cần JOIN/SUM cho chuẩn
        $stmt = $this->conn->prepare("SELECT SUM(GIATRI) as revenue FROM hoadon WHERE TRANGTHAI='PAID'");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ)->revenue ?? 0;
    }
    // Tổng số hội viên
    public function totalMembers() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM hoivien");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }
    // Tổng số locker hiện có
    public function totalLockers() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM locker");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }
    // Số hóa đơn theo trạng thái
    public function invoiceByStatus() {
        $stmt = $this->conn->prepare("SELECT TRANGTHAI, COUNT(*) as total FROM hoadon GROUP BY TRANGTHAI");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>