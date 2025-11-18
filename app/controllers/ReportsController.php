<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Report.php';

class ReportsController extends Controller
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * @var Report
     */
    private $reportModel;

    public function __construct()
    {


        // Kết nối DB
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    // ========================================
    // TRANG CHỦ BÁO CÁO
    // ========================================
    public function index()
    {
        // Lấy khoảng thời gian từ query string (mặc định 30 ngày gần nhất)
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate   = $_GET['end_date']   ?? date('Y-m-d');

        // ===== BÁO CÁO TỔNG QUAN =====
        $stats = [
            'total_members'    => $this->getTotalMembers(),
            'active_members'   => $this->getActiveMembers(),
            'total_revenue'    => $this->getTotalRevenue($startDate, $endDate),
            'total_bookings'   => $this->getTotalBookings($startDate, $endDate),
            'total_classes'    => $this->getTotalClasses(),
            'total_rooms'      => $this->getTotalRooms(),
            'total_trainers'   => $this->getTotalTrainers(),
            'active_contracts' => $this->getActiveContracts(),
        ];

        // ===== DOANH THU THEO THÁNG (12 tháng gần nhất) =====
        $revenueByMonth = $this->getRevenueByMonth(12);

        // ===== HỘI VIÊN MỚI THEO THÁNG (12 tháng gần nhất) =====
        $newMembersByMonth = $this->getNewMembersByMonth(12);

        // ===== TOP 5 HỘI VIÊN TÍCH CỰC NHẤT =====
        $topMembers = $this->getTopActiveMembers(5);

        // ===== PHÒNG/SÂN ĐƯỢC ĐẶT NHIỀU NHẤT =====
        $topRooms = $this->getTopBookedRooms($startDate, $endDate, 5);

        // ===== LỚP HỌC ĐƯỢC ĐĂNG KÝ NHIỀU NHẤT =====
        $topClasses = $this->getTopRegisteredClasses($startDate, $endDate, 5);

        // ===== THỐNG KÊ THEO TRẠNG THÁI HỢP ĐỒNG =====
        $contractStats = $this->getContractStats();

        // ===== THỐNG KÊ THANH TOÁN THEO PHƯƠNG THỨC =====
        $paymentMethods = $this->getPaymentMethodStats($startDate, $endDate);

        $this->view('reports/index', [
            'stats'             => $stats,
            'revenueByMonth'    => $revenueByMonth,
            'newMembersByMonth' => $newMembersByMonth,
            'topMembers'        => $topMembers,
            'topRooms'          => $topRooms,
            'topClasses'        => $topClasses,
            'contractStats'     => $contractStats,
            'paymentMethods'    => $paymentMethods,
            'startDate'         => $startDate,
            'endDate'           => $endDate,
        ]);
    }

    // ========================================
    // HÀM PHỤ - LẤY DỮ LIỆU BÁO CÁO
    // ========================================

    // Tổng số hội viên
    private function getTotalMembers()
    {
        $sql = "SELECT COUNT(*) as total FROM hoivien";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total ?? 0;
    }

    // Hội viên đang hoạt động
    private function getActiveMembers()
    {
        $sql = "SELECT COUNT(*) as total FROM hoivien WHERE TRANGTHAI = 'ACTIVE'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total ?? 0;
    }

    // Tổng doanh thu trong khoảng thời gian
    private function getTotalRevenue($startDate, $endDate)
    {
        $sql = "SELECT COALESCE(SUM(tt.SOTIEN), 0) as total 
                FROM thanhtoan tt
                WHERE DATE(tt.NGAYTT) BETWEEN :start AND :end";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':start' => $startDate, ':end' => $endDate]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total ?? 0;
    }

    // Tổng số lượt đặt phòng
    private function getTotalBookings($startDate, $endDate)
    {
        $sql = "SELECT COUNT(*) as total 
                FROM datphong 
                WHERE DATE(BATDAU) BETWEEN :start AND :end";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':start' => $startDate, ':end' => $endDate]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total ?? 0;
    }

    // Tổng số lớp học
    private function getTotalClasses()
    {
        $sql = "SELECT COUNT(*) as total FROM lop";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total ?? 0;
    }

    // Tổng số phòng/sân đang hoạt động
    private function getTotalRooms()
    {
        $sql = "SELECT COUNT(*) as total FROM phong WHERE HOATDONG = 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total ?? 0;
    }

    // Tổng số huấn luyện viên
    private function getTotalTrainers()
    {
        $sql = "SELECT COUNT(*) as total FROM hlv";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total ?? 0;
    }

    // Số hợp đồng đang hoạt động
    private function getActiveContracts()
    {
        $sql = "SELECT COUNT(*) as total FROM hopdong WHERE TRANGTHAI = 'ACTIVE'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total ?? 0;
    }

    // Doanh thu theo tháng (12 tháng gần nhất)
    private function getRevenueByMonth($months = 12)
    {
        $sql = "SELECT 
                    DATE_FORMAT(tt.NGAYTT, '%Y-%m') as month,
                    COALESCE(SUM(tt.SOTIEN), 0) as revenue
                FROM thanhtoan tt
                WHERE tt.NGAYTT >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
                GROUP BY month
                ORDER BY month ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':months' => $months]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Hội viên mới theo tháng (12 tháng gần nhất)
    private function getNewMembersByMonth($months = 12)
    {
        $sql = "SELECT 
                    DATE_FORMAT(NGAYTAO, '%Y-%m') as month,
                    COUNT(*) as total
                FROM hoivien
                WHERE NGAYTAO >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
                GROUP BY month
                ORDER BY month ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':months' => $months]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Top 5 hội viên tích cực nhất (dựa trên check-in, đặt phòng, đăng ký lớp)
    private function getTopActiveMembers($limit = 5)
    {
        $sql = "SELECT 
                    hv.MAHV,
                    hv.HOVATEN,
                    hv.SDT,
                    hv.EMAIL,
                    COUNT(DISTINCT ci.MACI) as checkin_count,
                    COUNT(DISTINCT dp.MADP) as booking_count,
                    COUNT(DISTINCT dk.MADK) as class_count,
                    (COUNT(DISTINCT ci.MACI) + COUNT(DISTINCT dp.MADP) + COUNT(DISTINCT dk.MADK)) as activity_score
                FROM hoivien hv
                LEFT JOIN checkin ci ON hv.MAHV = ci.MAHV
                LEFT JOIN datphong dp ON hv.MAHV = dp.MAHV
                LEFT JOIN dangky_lop dk ON hv.MAHV = dk.MAHV
                WHERE hv.TRANGTHAI = 'ACTIVE'
                GROUP BY hv.MAHV, hv.HOVATEN, hv.SDT, hv.EMAIL
                ORDER BY activity_score DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Top phòng/sân được đặt nhiều nhất
    private function getTopBookedRooms($startDate, $endDate, $limit = 5)
    {
        $sql = "SELECT 
                    p.MAPHONG,
                    p.TENPHONG,
                    k.TENKHU,
                    p.SUCCHUA,
                    COUNT(dp.MADP) as booking_count
                FROM phong p
                JOIN khu k ON p.MAKHU = k.MAKHU
                LEFT JOIN datphong dp ON p.MAPHONG = dp.MAPHONG 
                    AND DATE(dp.BATDAU) BETWEEN :start AND :end
                WHERE p.HOATDONG = 1
                GROUP BY p.MAPHONG, p.TENPHONG, k.TENKHU, p.SUCCHUA
                ORDER BY booking_count DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':start', $startDate);
        $stmt->bindValue(':end', $endDate);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Top lớp học được đăng ký nhiều nhất
    private function getTopRegisteredClasses($startDate, $endDate, $limit = 5)
    {
        $sql = "SELECT 
                    l.MALOP,
                    l.TENLOP,
                    l.THOILUONG,
                    COUNT(DISTINCT bl.MABUOI) as session_count,
                    COUNT(DISTINCT dk.MADK) as registration_count
                FROM lop l
                LEFT JOIN buoilop bl ON l.MALOP = bl.MALOP
                    AND DATE(bl.BATDAU) BETWEEN :start AND :end
                LEFT JOIN dangky_lop dk ON bl.MABUOI = dk.MABUOI
                GROUP BY l.MALOP, l.TENLOP, l.THOILUONG
                ORDER BY registration_count DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':start', $startDate);
        $stmt->bindValue(':end', $endDate);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Thống kê hợp đồng theo trạng thái
    private function getContractStats()
    {
        $sql = "SELECT 
                    TRANGTHAI,
                    COUNT(*) as total
                FROM hopdong
                GROUP BY TRANGTHAI";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Thống kê thanh toán theo phương thức
    private function getPaymentMethodStats($startDate, $endDate)
    {
        $sql = "SELECT 
                    PHUONGTHUC,
                    COUNT(*) as count,
                    SUM(SOTIEN) as total_amount
                FROM thanhtoan
                WHERE DATE(NGAYTT) BETWEEN :start AND :end
                GROUP BY PHUONGTHUC
                ORDER BY total_amount DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':start' => $startDate, ':end' => $endDate]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}