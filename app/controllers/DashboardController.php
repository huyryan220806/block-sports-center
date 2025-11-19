<?php


require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Session.php';

class DashboardController extends Controller {

    private $db;
    private $sessionModel;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->sessionModel = new Session($this->db);
    }

    public function index()
    {
        // ✅ Thống kê từ Database
        $memberStats = $this->getMemberStats();
        $revenueStats = $this->getRevenueStats();
        $sessionStats = $this->getSessionStats();
        $roomStats = $this->getRoomStats();
        
        // ✅ Lấy 10 buổi lớp sắp diễn ra
        $upcomingSessions = $this->sessionModel->getUpcoming(10);

        $this->view('dashboard/index', [
            'title'            => 'Dashboard',
            'memberStats'      => $memberStats,
            'revenueStats'     => $revenueStats,
            'sessionStats'     => $sessionStats,
            'roomStats'        => $roomStats,
            'upcomingSessions' => $upcomingSessions,
        ]);
    }

    // ========================================
    // ✅ 1. TÍNH HỘI VIÊN HOẠT ĐỘNG
    // ========================================
    private function getMemberStats()
    {
        try {
            // Tháng hiện tại
            $query = "SELECT COUNT(*) as total 
                      FROM hoivien 
                      WHERE TRANGTHAI = 'ACTIVE'";
            $stmt = $this->db->query($query);
            $current = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Tháng trước
            $query = "SELECT COUNT(*) as total 
                      FROM hoivien 
                      WHERE TRANGTHAI = 'ACTIVE' 
                      AND NGAYTAO <= LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
            $stmt = $this->db->query($query);
            $previous = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Tính % thay đổi
            $change = 0;
            if ($previous > 0) {
                $change = round((($current - $previous) / $previous) * 100, 1);
            }

            return [
                'total' => $current,
                'change' => $change,
                'direction' => $change >= 0 ? 'up' : 'down'
            ];

        } catch (Exception $e) {
            error_log("Dashboard getMemberStats error: " . $e->getMessage());
            return ['total' => 0, 'change' => 0, 'direction' => 'up'];
        }
    }

    // ========================================
    // ✅ 2. TÍNH DOANH THU THÁNG
    // ========================================
    private function getRevenueStats()
    {
        try {
            // Doanh thu tháng hiện tại
            $query = "SELECT COALESCE(SUM(tt.SOTIEN), 0) as total
                      FROM thanhtoan tt
                      JOIN hoadon hd ON tt.MAHDON = hd.MAHDON
                      WHERE hd.TRANGTHAI = 'PAID'
                      AND MONTH(tt.NGAYTT) = MONTH(CURDATE())
                      AND YEAR(tt.NGAYTT) = YEAR(CURDATE())";
            $stmt = $this->db->query($query);
            $current = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Doanh thu tháng trước
            $query = "SELECT COALESCE(SUM(tt.SOTIEN), 0) as total
                      FROM thanhtoan tt
                      JOIN hoadon hd ON tt.MAHDON = hd.MAHDON
                      WHERE hd.TRANGTHAI = 'PAID'
                      AND MONTH(tt.NGAYTT) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
                      AND YEAR(tt.NGAYTT) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
            $stmt = $this->db->query($query);
            $previous = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Tính % thay đổi
            $change = 0;
            if ($previous > 0) {
                $change = round((($current - $previous) / $previous) * 100, 1);
            }

            // Format hiển thị (M = triệu, K = nghìn)
            $formatted = $current >= 1000000 
                ? number_format($current / 1000000, 1, '.', '') . 'M'
                : number_format($current / 1000, 0, '.', '') . 'K';

            return [
                'total' => $current,
                'formatted' => $formatted,
                'change' => $change,
                'direction' => $change >= 0 ? 'up' : 'down'
            ];

        } catch (Exception $e) {
            error_log("Dashboard getRevenueStats error: " . $e->getMessage());
            return ['total' => 0, 'formatted' => '0', 'change' => 0, 'direction' => 'up'];
        }
    }

    // ========================================
    // ✅ 3. TÍNH BUỔI LỚP HÔM NAY
    // ========================================
    private function getSessionStats()
    {
        try {
            // Tổng số buổi hôm nay
            $query = "SELECT COUNT(*) as total 
                      FROM buoilop 
                      WHERE DATE(BATDAU) = CURDATE()";
            $stmt = $this->db->query($query);
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Buổi đã hoàn thành
            $query = "SELECT COUNT(*) as completed 
                      FROM buoilop 
                      WHERE DATE(BATDAU) = CURDATE()
                      AND TRANGTHAI = 'DONE'";
            $stmt = $this->db->query($query);
            $completed = $stmt->fetch(PDO::FETCH_ASSOC)['completed'];

            // Buổi đang diễn ra
            $query = "SELECT COUNT(*) as ongoing 
                      FROM buoilop 
                      WHERE BATDAU <= NOW() 
                      AND KETTHUC > NOW()
                      AND TRANGTHAI IN ('SCHEDULED', 'ONGOING')";
            $stmt = $this->db->query($query);
            $ongoing = $stmt->fetch(PDO::FETCH_ASSOC)['ongoing'];

            return [
                'total' => $total,
                'completed' => $completed,
                'ongoing' => $ongoing
            ];

        } catch (Exception $e) {
            error_log("Dashboard getSessionStats error: " . $e->getMessage());
            return ['total' => 0, 'completed' => 0, 'ongoing' => 0];
        }
    }

    // ========================================
    // ✅ 4. TÍNH PHÒNG ĐANG SỬ DỤNG
    // ========================================
    private function getRoomStats()
    {
        try {
            // Tổng số phòng hoạt động
            $query = "SELECT COUNT(*) as total 
                      FROM phong 
                      WHERE HOATDONG = 1";
            $stmt = $this->db->query($query);
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Phòng đang được sử dụng (có buổi lớp đang diễn ra)
            $query = "SELECT COUNT(DISTINCT bl.MAPHONG) as used
                      FROM buoilop bl
                      WHERE bl.BATDAU <= NOW() 
                      AND bl.KETTHUC > NOW()
                      AND bl.TRANGTHAI IN ('SCHEDULED', 'ONGOING')";
            $stmt = $this->db->query($query);
            $used = $stmt->fetch(PDO::FETCH_ASSOC)['used'];

            // Tính tỷ lệ %
            $percentage = $total > 0 ? round(($used / $total) * 100) : 0;

            return [
                'total' => $total,
                'used' => $used,
                'percentage' => $percentage
            ];

        } catch (Exception $e) {
            error_log("Dashboard getRoomStats error: " . $e->getMessage());
            return ['total' => 0, 'used' => 0, 'percentage' => 0];
        }
    }
}
//