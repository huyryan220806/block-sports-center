<?php

class UserDashboard
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Lấy thống kê tháng này của hội viên
     */
    public function getMonthlyStats($userId)
    {
        $currentMonth = date('Y-m');
        
        // Tính số buổi tập tháng này
        $sessionsQuery = "
            SELECT COUNT(*) as total
            FROM dangky_lop dk
            INNER JOIN buoilop bl ON dk.MABUOI = bl.MABUOI
            WHERE dk.MAHV = :userId 
            AND dk.TRANGTHAI = 'ATTENDED'
            AND DATE_FORMAT(bl.BATDAU, '%Y-%m') = :currentMonth
        ";
        
        $stmt = $this->db->prepare($sessionsQuery);
        $stmt->execute([
            ':userId' => $userId,
            ':currentMonth' => $currentMonth
        ]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $sessionsThisMonth = $result->total ?? 0;
        
        // Tính tổng calories đã đốt (dựa trên phòng tập và thời lượng)
        $caloriesQuery = "
            SELECT 
                SUM(
                    TIMESTAMPDIFF(MINUTE, bl.BATDAU, bl.KETTHUC) / 60 * p.CALO_MOI_GIO
                ) as totalCalo
            FROM dangky_lop dk
            INNER JOIN buoilop bl ON dk.MABUOI = bl.MABUOI
            INNER JOIN phong p ON bl.MAPHONG = p.MAPHONG
            WHERE dk.MAHV = :userId 
            AND dk.TRANGTHAI = 'ATTENDED'
            AND DATE_FORMAT(bl.BATDAU, '%Y-%m') = :currentMonth
        ";
        
        $stmt = $this->db->prepare($caloriesQuery);
        $stmt->execute([
            ':userId' => $userId,
            ':currentMonth' => $currentMonth
        ]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $totalCalo = $result->totalCalo ?? 0;
        
        // Tính tổng giờ tập
        $hoursQuery = "
            SELECT 
                SUM(TIMESTAMPDIFF(MINUTE, bl.BATDAU, bl.KETTHUC)) / 60 as totalHours
            FROM dangky_lop dk
            INNER JOIN buoilop bl ON dk.MABUOI = bl.MABUOI
            WHERE dk.MAHV = :userId 
            AND dk.TRANGTHAI = 'ATTENDED'
            AND DATE_FORMAT(bl.BATDAU, '%Y-%m') = :currentMonth
        ";
        
        $stmt = $this->db->prepare($hoursQuery);
        $stmt->execute([
            ':userId' => $userId,
            ':currentMonth' => $currentMonth
        ]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $hoursThisMonth = $result->totalHours ?? 0;
        
        // Tính thành tựu (số tuần tập >= 3 buổi trong tháng)
        $achievementsQuery = "
            SELECT COUNT(DISTINCT week_year) as achievements
            FROM (
                SELECT 
                    CONCAT(YEAR(bl.BATDAU), '-', WEEK(bl.BATDAU)) as week_year,
                    COUNT(*) as sessions_per_week
                FROM dangky_lop dk
                INNER JOIN buoilop bl ON dk.MABUOI = bl.MABUOI
                WHERE dk.MAHV = :userId 
                AND dk.TRANGTHAI = 'ATTENDED'
                AND DATE_FORMAT(bl.BATDAU, '%Y-%m') = :currentMonth
                GROUP BY week_year
                HAVING sessions_per_week >= 3
            ) as weekly_stats
        ";
        
        $stmt = $this->db->prepare($achievementsQuery);
        $stmt->execute([
            ':userId' => $userId,
            ':currentMonth' => $currentMonth
        ]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $achievements = $result->achievements ?? 0;
        
        return [
            'sessionsThisMonth' => (int)$sessionsThisMonth,
            'totalCalo' => round($totalCalo, 0),
            'hoursThisMonth' => round($hoursThisMonth, 1),
            'achievements' => (int)$achievements
        ];
    }
    
    /**
     * Lấy danh sách lớp học sắp tới
     */
    public function getUpcomingClasses($userId, $limit = 5)
    {
        $query = "
            SELECT 
                l.TENLOP,
                bl.MABUOI,
                bl.BATDAU,
                bl.KETTHUC,
                p.TENPHONG,
                nv.HOTEN as TENHLV,
                k.TENKHU,
                bl.TRANGTHAI as BUOI_TRANGTHAI
            FROM dangky_lop dk
            INNER JOIN buoilop bl ON dk.MABUOI = bl.MABUOI
            INNER JOIN lop l ON bl.MALOP = l.MALOP
            INNER JOIN phong p ON bl.MAPHONG = p.MAPHONG
            INNER JOIN khu k ON p.MAKHU = k.MAKHU
            INNER JOIN hlv h ON bl.MAHLV = h.MAHLV
            INNER JOIN nhanvien nv ON h.MAHLV = nv.MANV
            WHERE dk.MAHV = :userId
            AND dk.TRANGTHAI = 'BOOKED'
            AND bl.TRANGTHAI = 'SCHEDULED'
            AND bl.BATDAU > NOW()
            ORDER BY bl.BATDAU ASC
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Lấy thông tin hội viên từ bảng hoivien
     */
    public function getMemberInfo($userId)
    {
        $query = "
            SELECT 
                hv.MAHV,
                hv.HOVATEN,
                hv.EMAIL,
                hv.SDT,
                hv.GIOITINH,
                hv.NGAYSINH,
                hv.TRANGTHAI,
                hd.NGAYKT as CONTRACT_END,
                lg.TENLG as GOI_HIEN_TAI
            FROM hoivien hv
            LEFT JOIN hopdong hd ON hv.MAHV = hd.MAHV 
                AND hd.TRANGTHAI = 'ACTIVE'
            LEFT JOIN loaigoi lg ON hd.MALG = lg.MALG
            WHERE hv.MAHV = :userId
            LIMIT 1
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([':userId' => $userId]);
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    /**
     * Lấy thông tin user từ bảng users
     */
    public function getUserInfo($userId)
    {
        $query = "
            SELECT 
                u.id,
                u.username,
                u.email,
                u.fullname,
                u.phone,
                u.role,
                u.created_at
            FROM users u
            WHERE u.id = :userId
            LIMIT 1
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([':userId' => $userId]);
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    /**
     * Đăng ký lớp học mới
     */
    public function registerClass($userId, $sessionId)
    {
        try {
            // Kiểm tra xem đã đăng ký chưa
            $checkQuery = "
                SELECT COUNT(*) as count 
                FROM dangky_lop 
                WHERE MAHV = :userId AND MABUOI = :sessionId
            ";
            
            $stmt = $this->db->prepare($checkQuery);
            $stmt->execute([
                ':userId' => $userId,
                ':sessionId' => $sessionId
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            if ($result->count > 0) {
                return ['success' => false, 'message' => 'Bạn đã đăng ký lớp này rồi!'];
            }
            
            // Kiểm tra còn chỗ không
            $capacityQuery = "
                SELECT 
                    bl.SISO as maxCapacity,
                    COUNT(dk.MADK) as currentCount
                FROM buoilop bl
                LEFT JOIN dangky_lop dk ON bl.MABUOI = dk.MABUOI 
                    AND dk.TRANGTHAI IN ('BOOKED', 'ATTENDED')
                WHERE bl.MABUOI = :sessionId
                GROUP BY bl.MABUOI, bl.SISO
            ";
            
            $stmt = $this->db->prepare($capacityQuery);
            $stmt->execute([':sessionId' => $sessionId]);
            $capacity = $stmt->fetch(PDO::FETCH_OBJ);
            
            if ($capacity && $capacity->currentCount >= $capacity->maxCapacity) {
                return ['success' => false, 'message' => 'Lớp học đã đầy!'];
            }
            
            // Kiểm tra thời gian lớp học
            $timeQuery = "SELECT BATDAU FROM buoilop WHERE MABUOI = :sessionId";
            $stmt = $this->db->prepare($timeQuery);
            $stmt->execute([':sessionId' => $sessionId]);
            $timeResult = $stmt->fetch(PDO::FETCH_OBJ);
            
            if ($timeResult && strtotime($timeResult->BATDAU) < time()) {
                return ['success' => false, 'message' => 'Không thể đăng ký lớp đã qua!'];
            }
            
            // Đăng ký
            $insertQuery = "
                INSERT INTO dangky_lop (MABUOI, MAHV, NGAYDK, TRANGTHAI)
                VALUES (:sessionId, :userId, NOW(), 'BOOKED')
            ";
            
            $stmt = $this->db->prepare($insertQuery);
            $stmt->execute([
                ':sessionId' => $sessionId,
                ':userId' => $userId
            ]);
            
            return ['success' => true, 'message' => 'Đăng ký thành công!'];
            
        } catch (PDOException $e) {
            error_log("Register class error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống. Vui lòng thử lại!'];
        }
    }
    
    /**
     * Hủy đăng ký lớp học
     */
    public function cancelClass($userId, $sessionId)
    {
        try {
            // Kiểm tra thời gian lớp học
            $timeQuery = "
                SELECT bl.BATDAU 
                FROM buoilop bl
                INNER JOIN dangky_lop dk ON bl.MABUOI = dk.MABUOI
                WHERE dk.MAHV = :userId 
                AND dk.MABUOI = :sessionId
                AND dk.TRANGTHAI = 'BOOKED'
            ";
            
            $stmt = $this->db->prepare($timeQuery);
            $stmt->execute([
                ':userId' => $userId,
                ':sessionId' => $sessionId
            ]);
            
            $timeResult = $stmt->fetch(PDO::FETCH_OBJ);
            
            if (!$timeResult) {
                return ['success' => false, 'message' => 'Không tìm thấy lớp học đã đăng ký!'];
            }
            
            // Kiểm tra có thể hủy không (trước 2 giờ)
            $hoursBeforeClass = (strtotime($timeResult->BATDAU) - time()) / 3600;
            if ($hoursBeforeClass < 2) {
                return ['success' => false, 'message' => 'Chỉ có thể hủy trước 2 giờ khi lớp bắt đầu!'];
            }
            
            // Hủy đăng ký
            $query = "
                UPDATE dangky_lop 
                SET TRANGTHAI = 'CANCELLED'
                WHERE MAHV = :userId 
                AND MABUOI = :sessionId
                AND TRANGTHAI = 'BOOKED'
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':userId' => $userId,
                ':sessionId' => $sessionId
            ]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Hủy đăng ký thành công!'];
            } else {
                return ['success' => false, 'message' => 'Không thể hủy đăng ký!'];
            }
            
        } catch (PDOException $e) {
            error_log("Cancel class error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống. Vui lòng thử lại!'];
        }
    }
    
    /**
     * Lấy danh sách tất cả lớp học có thể đăng ký
     */
    public function getAvailableClasses($limit = 20)
    {
        $query = "
            SELECT 
                bl.MABUOI,
                l.TENLOP,
                bl.BATDAU,
                bl.KETTHUC,
                p.TENPHONG,
                nv.HOTEN as TENHLV,
                bl.SISO as MAX_CAPACITY,
                COUNT(dk.MADK) as CURRENT_COUNT,
                (bl.SISO - COUNT(dk.MADK)) as SLOTS_LEFT
            FROM buoilop bl
            INNER JOIN lop l ON bl.MALOP = l.MALOP
            INNER JOIN phong p ON bl.MAPHONG = p.MAPHONG
            INNER JOIN hlv h ON bl.MAHLV = h.MAHLV
            INNER JOIN nhanvien nv ON h.MAHLV = nv.MANV
            LEFT JOIN dangky_lop dk ON bl.MABUOI = dk.MABUOI 
                AND dk.TRANGTHAI IN ('BOOKED', 'ATTENDED')
            WHERE bl.TRANGTHAI = 'SCHEDULED'
            AND bl.BATDAU > NOW()
            GROUP BY bl.MABUOI
            HAVING SLOTS_LEFT > 0
            ORDER BY bl.BATDAU ASC
            LIMIT :limit
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Kiểm tra xem user đã đăng ký lớp này chưa
     */
    public function isRegistered($userId, $sessionId)
    {
        $query = "
            SELECT COUNT(*) as count
            FROM dangky_lop
            WHERE MAHV = :userId 
            AND MABUOI = :sessionId
            AND TRANGTHAI IN ('BOOKED', 'ATTENDED')
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':userId' => $userId,
            ':sessionId' => $sessionId
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->count > 0;
    }
}