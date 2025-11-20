<?php

class UserController extends Controller
{
    private $dashboardModel;
    
    public function __construct()
    {
        // Kiểm tra đăng nhập
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /block-sports-center/public/index.php?page=login');
            exit;
        }
        
        require_once __DIR__ . '/../models/UserDashboard.php';
        $this->dashboardModel = new UserDashboard();
    }
    
    public function index()
    {
        $userId = $_SESSION['user_id'];
        
        // Lấy thống kê
        $stats = $this->dashboardModel->getMonthlyStats($userId);
        
        // Lấy lớp học sắp tới
        $upcomingClasses = $this->dashboardModel->getUpcomingClasses($userId);
        
        // Lấy thông tin người dùng từ bảng users
        $userInfo = $this->dashboardModel->getUserInfo($userId);
        
        // XỬ LÝ TÊN HIỂN THỊ
        $fullName = 'Khách'; // Giá trị mặc định
        
        // Kiểm tra các nguồn dữ liệu theo thứ tự ưu tiên
        if (!empty($userInfo) && is_object($userInfo)) {
            // Ưu tiên 1: fullname từ database
            if (!empty($userInfo->fullname)) {
                $fullName = $userInfo->fullname;
            }
            // Ưu tiên 2: username từ database
            else if (!empty($userInfo->username)) {
                $fullName = $userInfo->username;
            }
        }
        
        // Ưu tiên 3: fullname từ session
        if ($fullName === 'Khách' && !empty($_SESSION['fullname'])) {
            $fullName = $_SESSION['fullname'];
        }
        
        // Ưu tiên 4: username từ session
        if ($fullName === 'Khách' && !empty($_SESSION['username'])) {
            $fullName = $_SESSION['username'];
        }
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'title' => 'Trang người dùng',
            'user' => [
                'name' => $fullName,
                'member_id' => 'MB' . str_pad($userId, 3, '0', STR_PAD_LEFT),
                'avatar' => mb_strtoupper(mb_substr($fullName, 0, 2, 'UTF-8'), 'UTF-8')
            ],
            'sessionsThisMonth' => $stats['sessionsThisMonth'],
            'totalCalo' => $stats['totalCalo'],
            'hoursThisMonth' => $stats['hoursThisMonth'],
            'achievements' => $stats['achievements'],
            'upcomingClasses' => $upcomingClasses
        ];
        
        $this->view('user/index', $data);
    }
    
    /**
     * API để đăng ký lớp học
     */
    public function registerClass()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $sessionId = $_POST['session_id'] ?? null;
        
        if (!$sessionId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }
        
        $result = $this->dashboardModel->registerClass($userId, $sessionId);
        echo json_encode($result);
        exit;
    }
    
    /**
     * API để hủy đăng ký lớp học
     */
    public function cancelClass()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $sessionId = $_POST['session_id'] ?? null;
        
        if (!$sessionId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }
        
        $result = $this->dashboardModel->cancelClass($userId, $sessionId);
        echo json_encode($result);
        exit;
    }
    
    public function booking()
    {
        $data['title'] = 'Đặt sân / đặt phòng';
        $this->view('user/booking', $data);
    }
}