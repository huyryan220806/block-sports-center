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
        // Lấy tất cả cài đặt từ bảng settings (hoặc hardcode nếu chưa có bảng)
        $settings = $this->getAllSettings();

        $this->view('settings/index', [
            'settings' => $settings,
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
    // HÀM PHỤ
    // ========================================

    private function getAllSettings()
    {
        // Kiểm tra xem bảng settings có tồn tại không
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
            // Nếu bảng chưa tồn tại, trả về giá trị mặc định
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

    private function updateSetting($key, $value)
    {
        try {
            // Kiểm tra xem key đã tồn tại chưa
            $checkSql = "SELECT COUNT(*) FROM settings WHERE setting_key = :key";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([':key' => $key]);
            $exists = $checkStmt->fetchColumn();

            if ($exists) {
                // Update
                $sql = "UPDATE settings SET setting_value = :value WHERE setting_key = :key";
            } else {
                // Insert
                $sql = "INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':key'   => $key,
                ':value' => $value,
            ]);
        } catch (PDOException $e) {
            // Nếu bảng chưa tồn tại, bỏ qua
            return false;
        }
    }
}
//