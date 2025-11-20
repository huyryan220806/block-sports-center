<?php
// app/controllers/SettingsController.php
// Controller cho trang Cài đặt

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Setting.php';

class SettingsController extends Controller
{
    private $db;
    private $settingModel;

    public function __construct()
    {
        $this->settingModel = new Setting();
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    // ========================================
    // TRANG CHỦ CÀI ĐẶT
    // ========================================
    public function index()
    {
        // Lấy tất cả cài đặt từ bảng settings
        $settings = $this->getAllSettings();

        // ✅ LẤY THÔNG TIN USER ĐANG ĐĂNG NHẬP
        $currentUser = $this->getCurrentUserInfo();

        // ✅ LẤY THÔNG TIN NHÂN VIÊN (NẾU LÀ NHÂN VIÊN)
        $employeeInfo = $this->getEmployeeInfo();

        $this->view('settings/index', [
            'settings' => $settings,
            'currentUser' => $currentUser,
            'employeeInfo' => $employeeInfo,
        ]);
    }

    // ========================================
    // CẬP NHẬT CÀI ĐẶT
    // ========================================
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=settings&a=index');
            return;
        }

        // Lấy dữ liệu từ form
        $centerName    = trim($_POST['center_name'] ?? '');
        $centerAddress = trim($_POST['center_address'] ?? '');
        $centerPhone   = trim($_POST['center_phone'] ?? '');
        $centerEmail   = trim($_POST['center_email'] ?? '');
        $lockerPrice   = (float)($_POST['locker_price'] ?? 0);
        $bookingPrice  = (float)($_POST['booking_price'] ?? 0);

        // Validation cơ bản
        if (empty($centerName)) {
            $this->setFlash('error', 'Tên trung tâm không được để trống!');
            $this->redirect('?c=settings&a=index');
            return;
        }

        // Cập nhật từng setting
        $this->updateSetting('center_name', $centerName);
        $this->updateSetting('center_address', $centerAddress);
        $this->updateSetting('center_phone', $centerPhone);
        $this->updateSetting('center_email', $centerEmail);
        $this->updateSetting('locker_price', $lockerPrice);
        $this->updateSetting('booking_price', $bookingPrice);

        $this->setFlash('success', 'Cập nhật cài đặt thành công!');
        $this->redirect('?c=settings&a=index');
    }

    // ========================================
    // ✅ CẬP NHẬT THÔNG TIN CÁ NHÂN
    // ========================================
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=settings&a=index');
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $this->setFlash('error', 'Phiên đăng nhập hết hạn!');
            $this->redirect('?c=auth&a=login');
            return;
        }

        // Lấy dữ liệu từ form
        $email = trim($_POST['email'] ?? '');
        $currentPassword = trim($_POST['current_password'] ?? '');
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        try {
            // ✅ CẬP NHẬT EMAIL
            if (!empty($email)) {
                $updateEmailSql = "UPDATE users SET EMAIL = :email WHERE USERID = :userid";
                $stmt = $this->db->prepare($updateEmailSql);
                $stmt->execute([
                    ':email' => $email,
                    ':userid' => $userId
                ]);
            }

            // ✅ CẬP NHẬT MẬT KHẨU (NẾU CÓ)
            if (!empty($newPassword)) {
                // Kiểm tra mật khẩu hiện tại
                $checkPasswordSql = "SELECT PASSWORD FROM users WHERE USERID = :userid";
                $stmt = $this->db->prepare($checkPasswordSql);
                $stmt->execute([':userid' => $userId]);
                $user = $stmt->fetch(PDO::FETCH_OBJ);

                if (!$user || !password_verify($currentPassword, $user->PASSWORD)) {
                    $this->setFlash('error', 'Mật khẩu hiện tại không đúng!');
                    $this->redirect('?c=settings&a=index');
                    return;
                }

                // Kiểm tra mật khẩu mới khớp
                if ($newPassword !== $confirmPassword) {
                    $this->setFlash('error', 'Mật khẩu mới không khớp!');
                    $this->redirect('?c=settings&a=index');
                    return;
                }

                // Cập nhật mật khẩu mới
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePasswordSql = "UPDATE users SET PASSWORD = :password WHERE USERID = :userid";
                $stmt = $this->db->prepare($updatePasswordSql);
                $stmt->execute([
                    ':password' => $hashedPassword,
                    ':userid' => $userId
                ]);
            }

            $this->setFlash('success', 'Cập nhật thông tin thành công!');
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi: ' . $e->getMessage());
        }

        $this->redirect('?c=settings&a=index');
    }

    // ========================================
    // HÀM PHỤ
    // ========================================

    private function getAllSettings()
    {
        try {
            $sql = "SELECT * FROM settings";
            $stmt = $this->db->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

            $settings = [];
            foreach ($rows as $row) {
                $settings[$row->setting_key] = $row->setting_value;
            }

            return $settings;
        } catch (PDOException $e) {
            return [
                'center_name'    => 'BLOCK SPORTS CENTER',
                'center_address' => 'Đồng Nai, Việt Nam',
                'center_phone'   => '0901234567',
                'center_email'   => 'info@blocksports.vn',
                'locker_price'   => 100000,
                'booking_price'  => 50000,
            ];
        }
    }

    // ✅ LẤY THÔNG TIN USER HIỆN TẠI
    private function getCurrentUserInfo()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return null;
        }

        try {
            $sql = "SELECT USERID, USERNAME, EMAIL, VAITRO, CREATED_AT FROM users WHERE USERID = :userid";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':userid' => $userId]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return null;
        }
    }

    // ✅ LẤY THÔNG TIN NHÂN VIÊN (NẾU USER LÀ NHÂN VIÊN) - SỬA LẠI DÙNG VAITRO
    private function getEmployeeInfo()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return null;
        }

        try {
            // ✅ SỬA: SELECT các cột có trong bảng nhanvien thực tế
            $sql = "SELECT MANV, HOTEN, VAITRO, SDT, EMAIL, NGAYVAOLAM FROM nhanvien WHERE MANV = :manv";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':manv' => $userId]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return null;
        }
    }

    private function updateSetting($key, $value)
    {
        try {
            $checkSql = "SELECT COUNT(*) FROM settings WHERE setting_key = :key";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([':key' => $key]);
            $exists = $checkStmt->fetchColumn();

            if ($exists) {
                $sql = "UPDATE settings SET setting_value = :value WHERE setting_key = :key";
            } else {
                $sql = "INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':key'   => $key,
                ':value' => $value,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}