<?php
// app/controllers/MembersController.php
// Updated: 2025-11-16 by @dohoangphuc2006
// Tương thích với tên fields của Team UI

require_once __DIR__ . '/../models/Member.php';

class MembersController extends Controller {
    
    private $memberModel;
    
    public function __construct() {
        $this->memberModel = new Member();
    }
    
    // ========================================
    // HIỂN THỊ DANH SÁCH HỘI VIÊN
    // ========================================
    public function index() {
        $members = $this->memberModel->getAll();
        
        $this->view('members/index', [
            'title' => 'Quản lý Hội viên',
            'members' => $members,
            'success' => $this->getFlash('success'),
            'error' => $this->getFlash('error')
        ]);
    }
    
    // ========================================
    // HIỂN THỊ FORM THÊM HỘI VIÊN
    // ========================================
    public function create() {
        $this->view('members/create', [
            'title' => 'Thêm Hội viên mới',
            'error' => $this->getFlash('error')
        ]);
    }
    
    // ========================================
    // XỬ LÝ THÊM HỘI VIÊN MỚI
    // ========================================
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=members&a=create');
            return;
        }
        
        // ✅ MAPPING TÊN FIELDS TỪ TEAM UI → DATABASE
        // Team UI gửi: full_name, phone, email, gender, dob, address, status, notes
        // Database cần: hoten, sodienthoai, email, gioitinh, ngaysinh, diachi, trangthai, ghichu
        
        $data = [
            'hoten' => trim($_POST['full_name'] ?? ''),      
            'sodienthoai' => trim($_POST['phone'] ?? ''),    
            'email' => trim($_POST['email'] ?? ''),          
            'gioitinh' => $_POST['gender'] ?? 'Nam',         
            'ngaysinh' => $_POST['dob'] ?? null,             
            'diachi' => trim($_POST['address'] ?? ''),       
            'trangthai' => $this->mapStatus($_POST['status'] ?? 'ACTIVE'),
            'ghichu' => trim($_POST['notes'] ?? '')
        ];
        
        // Validation
        if (empty($data['hoten'])) {
            $this->setFlash('error', 'Vui lòng nhập họ tên!');
            $this->redirect('?c=members&a=create');
            return;
        }
        
        if (empty($data['sodienthoai'])) {
            $this->setFlash('error', 'Vui lòng nhập số điện thoại!');
            $this->redirect('?c=members&a=create');
            return;
        }
        
        // Validate email format
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Email không hợp lệ!');
            $this->redirect('?c=members&a=create');
            return;
        }
        
        // Validate phone number (10-11 digits)
        if (!preg_match('/^[0-9]{10,11}$/', $data['sodienthoai'])) {
            $this->setFlash('error', 'Số điện thoại phải có 10-11 chữ số!');
            $this->redirect('?c=members&a=create');
            return;
        }
        
        // Thêm vào database
        $id = $this->memberModel->create($data);
        
        if ($id) {
            $maHV = 'HV' . str_pad($id, 4, '0', STR_PAD_LEFT);
            $this->setFlash('success', "Thêm hội viên thành công! Mã hội viên: $maHV");
            $this->redirect('?c=members&a=index');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra khi thêm hội viên!');
            $this->redirect('?c=members&a=create');
        }
    }
    
    // ========================================
    // HIỂN THỊ FORM SỬA HỘI VIÊN
    // ========================================
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if (empty($id)) {
            $this->setFlash('error', 'Không tìm thấy ID hội viên!');
            $this->redirect('?c=members&a=index');
            return;
        }
        
        $member = $this->memberModel->find($id);
        
        if (!$member) {
            $this->setFlash('error', 'Không tìm thấy hội viên!');
            $this->redirect('?c=members&a=index');
            return;
        }
        
        $this->view('members/edit', [
            'title' => 'Sửa thông tin Hội viên',
            'member' => $member,
            'error' => $this->getFlash('error')
        ]);
    }
    
    // ========================================
    // XỬ LÝ CẬP NHẬT HỘI VIÊN
    // ========================================
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=members&a=index');
            return;
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (empty($id)) {
            $this->setFlash('error', 'Không tìm thấy ID hội viên!');
            $this->redirect('?c=members&a=index');
            return;
        }
        
        // ✅ MAPPING TÊN FIELDS TỪ TEAM UI → DATABASE
        $data = [
            'hoten' => trim($_POST['full_name'] ?? ''),      
            'sodienthoai' => trim($_POST['phone'] ?? ''),    
            'email' => trim($_POST['email'] ?? ''),          
            'gioitinh' => $_POST['gender'] ?? 'Nam',         
            'ngaysinh' => $_POST['dob'] ?? null,             
            'diachi' => trim($_POST['address'] ?? ''),       
            'trangthai' => $this->mapStatus($_POST['status'] ?? 'ACTIVE'),
            'ghichu' => trim($_POST['notes'] ?? '')
        ];
        
        // Validation
        if (empty($data['hoten'])) {
            $this->setFlash('error', 'Vui lòng nhập họ tên!');
            $this->redirect('?c=members&a=edit&id=' . $id);
            return;
        }
        
        if (empty($data['sodienthoai'])) {
            $this->setFlash('error', 'Vui lòng nhập số điện thoại!');
            $this->redirect('?c=members&a=edit&id=' . $id);
            return;
        }
        
        // Validate email format
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Email không hợp lệ!');
            $this->redirect('?c=members&a=edit&id=' . $id);
            return;
        }
        
        // Validate phone number
        if (!preg_match('/^[0-9]{10,11}$/', $data['sodienthoai'])) {
            $this->setFlash('error', 'Số điện thoại phải có 10-11 chữ số!');
            $this->redirect('?c=members&a=edit&id=' . $id);
            return;
        }
        
        // Cập nhật database
        if ($this->memberModel->update($id, $data)) {
            $this->setFlash('success', 'Cập nhật thông tin hội viên thành công!');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra khi cập nhật!');
        }
        
        $this->redirect('?c=members&a=index');
    }
    
    // ========================================
    // XÓA HỘI VIÊN
    // ========================================
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if (empty($id)) {
            $this->setFlash('error', 'Không tìm thấy ID hội viên!');
            $this->redirect('?c=members&a=index');
            return;
        }
        
        // Lấy thông tin hội viên trước khi xóa
        $member = $this->memberModel->find($id);
        
        if (!$member) {
            $this->setFlash('error', 'Không tìm thấy hội viên!');
            $this->redirect('?c=members&a=index');
            return;
        }
        
        // Xóa
        if ($this->memberModel->delete($id)) {
            $this->setFlash('success', "Đã xóa hội viên: {$member->hoten}");
        } else {
            $this->setFlash('error', 'Không thể xóa hội viên! Có thể đã có dữ liệu liên quan.');
        }
        
        $this->redirect('?c=members&a=index');
    }
    
    // ========================================
    // XEM CHI TIẾT HỘI VIÊN
    // ========================================
    public function show() {
        $id = $_GET['id'] ?? 0;
        
        if (empty($id)) {
            $this->setFlash('error', 'Không tìm thấy ID hội viên!');
            $this->redirect('?c=members&a=index');
            return;
        }
        
        $member = $this->memberModel->find($id);
        
        if (!$member) {
            $this->setFlash('error', 'Không tìm thấy hội viên!');
            $this->redirect('?c=members&a=index');
            return;
        }
        
        $this->view('members/show', [
            'title' => 'Chi tiết Hội viên',
            'member' => $member
        ]);
    }
    
    // ========================================
    // HELPER: MAPPING STATUS TỪ TIẾNG ANH → TIẾNG VIỆT
    // ========================================
    private function mapStatus($status) {
        $statusMap = [
            'ACTIVE' => 'Hoạt động',
            'SUSPENDED' => 'Tạm ngưng',
            'INACTIVE' => 'Không hoạt động',
            // Fallback: nếu Team UI gửi tiếng Việt thì giữ nguyên
            'Hoạt động' => 'Hoạt động',
            'Tạm ngưng' => 'Tạm ngưng',
            'Không hoạt động' => 'Không hoạt động'
        ];
        
        return $statusMap[$status] ?? 'Hoạt động';
    }
}