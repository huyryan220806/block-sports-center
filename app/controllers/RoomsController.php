<?php
// app/controllers/RoomsController.php

require_once __DIR__ . '/../models/Room.php';

class RoomsController extends Controller {
    
    private $roomModel;
    
    public function __construct() {
        $this->roomModel = new Room();
    }
    
    // ========================================
    // HIỂN THỊ DANH SÁCH PHÒNG
    // ========================================
    public function index() {
        $rooms = $this->roomModel->getAll();
        
        $this->view('rooms/index', [
            'title' => 'Quản lý Phòng/Sân',
            'rooms' => $rooms,
            'success' => $this->getFlash('success'),
            'error' => $this->getFlash('error')
        ]);
    }
    
    // ========================================
    // HIỂN THỊ FORM THÊM PHÒNG
    // ========================================
    public function create() {
        $this->view('rooms/create', [
            'title' => 'Thêm Phòng/Sân mới',
            'error' => $this->getFlash('error')
        ]);
    }
    
    // ========================================
    // XỬ LÝ THÊM PHÒNG MỚI
    // ========================================
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=rooms&a=create');
            return;
        }
        
        $data = [
            'tenphong' => trim($_POST['tenphong'] ?? ''),
            'loaiphong' => $_POST['loaiphong'] ?? 'Phòng tập',
            'succhua' => (int)($_POST['succhua'] ?? 20),
            'trangthai' => $_POST['trangthai'] ?? 'Hoạt động',
            'mota' => trim($_POST['mota'] ?? '')
        ];
        
        // Validation
        if (empty($data['tenphong'])) {
            $this->setFlash('error', 'Vui lòng nhập tên phòng!');
            $this->redirect('?c=rooms&a=create');
            return;
        }
        
        if ($data['succhua'] <= 0) {
            $this->setFlash('error', 'Sức chứa phải lớn hơn 0!');
            $this->redirect('?c=rooms&a=create');
            return;
        }
        
        $id = $this->roomModel->create($data);
        
        if ($id) {
            $this->setFlash('success', 'Thêm phòng/sân thành công!');
            $this->redirect('?c=rooms&a=index');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra khi thêm phòng/sân!');
            $this->redirect('?c=rooms&a=create');
        }
    }
    
    // ========================================
    // HIỂN THỊ FORM SỬA PHÒNG
    // ========================================
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if (empty($id)) {
            $this->setFlash('error', 'Không tìm thấy ID phòng!');
            $this->redirect('?c=rooms&a=index');
            return;
        }
        
        $room = $this->roomModel->find($id);
        
        if (!$room) {
            $this->setFlash('error', 'Không tìm thấy phòng/sân!');
            $this->redirect('?c=rooms&a=index');
            return;
        }
        
        $this->view('rooms/edit', [
            'title' => 'Sửa thông tin Phòng/Sân',
            'room' => $room,
            'error' => $this->getFlash('error')
        ]);
    }
    
    // ========================================
    // XỬ LÝ CẬP NHẬT PHÒNG
    // ========================================
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=rooms&a=index');
            return;
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (empty($id)) {
            $this->setFlash('error', 'Không tìm thấy ID phòng!');
            $this->redirect('?c=rooms&a=index');
            return;
        }
        
        $data = [
            'tenphong' => trim($_POST['tenphong'] ?? ''),
            'loaiphong' => $_POST['loaiphong'] ?? 'Phòng tập',
            'succhua' => (int)($_POST['succhua'] ?? 20),
            'trangthai' => $_POST['trangthai'] ?? 'Hoạt động',
            'mota' => trim($_POST['mota'] ?? '')
        ];
        
        // Validation
        if (empty($data['tenphong'])) {
            $this->setFlash('error', 'Vui lòng nhập tên phòng!');
            $this->redirect('?c=rooms&a=edit&id=' . $id);
            return;
        }
        
        if ($this->roomModel->update($id, $data)) {
            $this->setFlash('success', 'Cập nhật phòng/sân thành công!');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra khi cập nhật!');
        }
        
        $this->redirect('?c=rooms&a=index');
    }
    
    // ========================================
    // XÓA PHÒNG
    // ========================================
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if (empty($id)) {
            $this->setFlash('error', 'Không tìm thấy ID phòng!');
            $this->redirect('?c=rooms&a=index');
            return;
        }
        
        $room = $this->roomModel->find($id);
        
        if (!$room) {
            $this->setFlash('error', 'Không tìm thấy phòng/sân!');
            $this->redirect('?c=rooms&a=index');
            return;
        }
        
        if ($this->roomModel->delete($id)) {
            $this->setFlash('success', "Đã xóa phòng/sân: {$room->TENPHONG}");
        } else {
            $this->setFlash('error', 'Không thể xóa! Có thể đã có dữ liệu liên quan.');
        }
        
        $this->redirect('?c=rooms&a=index');
    }
}