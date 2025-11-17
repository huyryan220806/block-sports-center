<?php
require_once __DIR__ . '/../models/Trainer.php';
require_once __DIR__ . '/../../config/database.php';

class TrainersController {
    private $db;
    private $trainer;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->trainer = new Trainer($this->db);
    }

    public function index() {
        $trainers = $this->trainer->getAll();
        include __DIR__ . '/../views/trainers/index.php';
    }

    public function create() {
        include __DIR__ . '/../views/trainers/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'hoten' => $_POST['hoten'],
                'tendangnhap' => $_POST['tendangnhap'],
                'matkhau' => $_POST['matkhau'],
                'sdt' => $_POST['sdt'],
                'email' => $_POST['email'],
                'chuyenmon' => $_POST['chuyenmon'],
                'phi_gio' => $_POST['phi_gio'],
                'ngayvaolam' => $_POST['ngayvaolam'],
                'trangthai' => $_POST['trangthai']
            ];

            if ($this->trainer->create($data)) {
                $_SESSION['success'] = 'Thêm huấn luyện viên thành công!';
                header('Location: ?c=trainers&a=index');
                exit();
            } else {
                $error = 'Thêm huấn luyện viên thất bại!';
                include __DIR__ . '/../views/trainers/create.php';
            }
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        $trainer = $this->trainer->find($id);
        
        if (!$trainer) {
            $_SESSION['error'] = 'Không tìm thấy huấn luyện viên!';
            header('Location: ?c=trainers&a=index');
            exit();
        }

        include __DIR__ . '/../views/trainers/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            
            $data = [
                'hoten' => $_POST['hoten'],
                'tendangnhap' => $_POST['tendangnhap'],
                'sdt' => $_POST['sdt'],
                'email' => $_POST['email'],
                'chuyenmon' => $_POST['chuyenmon'],
                'phi_gio' => $_POST['phi_gio'],
                'ngayvaolam' => $_POST['ngayvaolam'],
                'trangthai' => $_POST['trangthai']
            ];

            if ($this->trainer->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật huấn luyện viên thành công!';
                header('Location: ?c=trainers&a=index');
                exit();
            } else {
                $error = 'Cập nhật huấn luyện viên thất bại!';
                $trainer = $this->trainer->find($id);
                include __DIR__ . '/../views/trainers/edit.php';
            }
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->trainer->delete($id)) {
            $_SESSION['success'] = 'Xóa huấn luyện viên thành công!';
        } else {
            $_SESSION['error'] = 'Xóa huấn luyện viên thất bại!';
        }
        
        header('Location: ?c=trainers&a=index');
        exit();
    }
}
?>