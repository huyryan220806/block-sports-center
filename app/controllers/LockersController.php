<?php

require_once __DIR__ . '/../models/Locker.php';
require_once __DIR__ . '/../models/Room.php';

class LockersController extends Controller {
    
    private $lockerModel;
    private $roomModel;
    
    public function __construct() {
        $this->lockerModel = new Locker();
        $this->roomModel = new Room();
    }
    
    // ========================================
    // HIỂN THỊ DANH SÁCH TỦ ĐỒ + PHÂN TRANG
    // ========================================
    public function index() {
        // Lấy tham số từ URL
        $search = $_GET['search'] ?? '';
        $roomFilter = $_GET['room'] ?? '';
        $page = isset($_GET['page']) && ctype_digit($_GET['page']) 
            ? (int)$_GET['page'] 
            : 1;
        
        $perPage = 10; // 10 dòng / trang
        
        // Đếm tổng số tủ
        $total = $this->lockerModel->count($search, $roomFilter);
        $totalPages = max(1, (int)ceil($total / $perPage));
        
        if ($page > $totalPages) $page = $totalPages;
        if ($page < 1) $page = 1;
        
        $offset = ($page - 1) * $perPage;
        
        // Lấy danh sách tủ có phân trang
        if (!empty($search) || !empty($roomFilter)) {
            $lockers = $this->lockerModel->search($search, $roomFilter, $perPage, $offset);
        } else {
            $lockers = $this->lockerModel->getAll($perPage, $offset);
        }
        
        // Thống kê
        $stats = $this->lockerModel->getStatistics();
        
        // Lấy danh sách phòng cho filter
        $rooms = $this->roomModel->getAll();
        
        $data = [
            'lockers' => $lockers,
            'total' => $total,
            'available' => $stats['available'] ?? 0,
            'occupied' => $stats['occupied'] ?? 0,
            'search' => $search,
            'roomFilter' => $roomFilter,
            'rooms' => $rooms,
            'page' => $page,
            'totalPages' => $totalPages
        ];
        
        $this->view('lockers/index', $data);
    }
    
    // ========================================
    // HIỂN THỊ FORM TẠO TỦ MỚI
    // ========================================
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
        } else {
            $rooms = $this->roomModel->getAll();
            $data = ['rooms' => $rooms];
            $this->view('lockers/create', $data);
        }
    }
    
    // ========================================
    // XỬ LÝ TẠO TỦ MỚI
    // ========================================
    private function handleCreate() {
        $maphong = $_POST['maphong'] ?? '';
        $kitu = $_POST['kitu'] ?? '';
        $hoatdong = isset($_POST['hoatdong']) ? 1 : 0;
        
        if (empty($maphong) || empty($kitu)) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin!';
            header('Location: ?c=lockers&a=create');
            exit;
        }
        
        if ($this->lockerModel->checkDuplicate($maphong, $kitu)) {
            $_SESSION['error'] = 'Ký tự tủ đã tồn tại trong phòng này!';
            header('Location: ?c=lockers&a=create');
            exit;
        }
        
        $data = [
            'MAPHONG' => $maphong,
            'KITU' => $kitu,
            'HOATDONG' => $hoatdong
        ];
        
        if ($this->lockerModel->create($data)) {
            $_SESSION['success'] = 'Thêm tủ thành công!';
            header('Location: ?c=lockers&a=index');
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
            header('Location: ?c=lockers&a=create');
        }
        exit;
    }
    
    // ========================================
    // HIỂN THỊ FORM SỬA TỦ
    // ========================================
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ!';
            header('Location: ?c=lockers&a=index');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEdit($id);
        } else {
            $locker = $this->lockerModel->getById($id);
            
            if (!$locker) {
                $_SESSION['error'] = 'Không tìm thấy tủ!';
                header('Location: ?c=lockers&a=index');
                exit;
            }
            
            $rooms = $this->roomModel->getAll();
            $data = [
                'locker' => $locker,
                'rooms' => $rooms
            ];
            
            $this->view('lockers/edit', $data);
        }
    }
    
    // ========================================
    // XỬ LÝ CẬP NHẬT TỦ
    // ========================================
    private function handleEdit($id) {
        $maphong = $_POST['maphong'] ?? '';
        $kitu = $_POST['kitu'] ?? '';
        $hoatdong = isset($_POST['hoatdong']) ? 1 : 0;
        
        if (empty($maphong) || empty($kitu)) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin!';
            header('Location: ?c=lockers&a=edit&id=' . $id);
            exit;
        }
        
        if ($this->lockerModel->checkDuplicateExcept($maphong, $kitu, $id)) {
            $_SESSION['error'] = 'Ký tự tủ đã tồn tại trong phòng này!';
            header('Location: ?c=lockers&a=edit&id=' . $id);
            exit;
        }
        
        $data = [
            'MAPHONG' => $maphong,
            'KITU' => $kitu,
            'HOATDONG' => $hoatdong
        ];
        
        if ($this->lockerModel->update($id, $data)) {
            $_SESSION['success'] = 'Cập nhật tủ thành công!';
            header('Location: ?c=lockers&a=index');
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
            header('Location: ?c=lockers&a=edit&id=' . $id);
        }
        exit;
    }
    
    // ========================================
    // XÓA TỦ
    // ========================================
    public function delete() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ!';
            header('Location: ?c=lockers&a=index');
            exit;
        }
        
        if ($this->lockerModel->isInUse($id)) {
            $_SESSION['error'] = 'Không thể xóa! Tủ đang được sử dụng.';
            header('Location: ?c=lockers&a=index');
            exit;
        }
        
        if ($this->lockerModel->delete($id)) {
            $_SESSION['success'] = 'Xóa tủ thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
        }
        
        header('Location: ?c=lockers&a=index');
        exit;
    }
}
//