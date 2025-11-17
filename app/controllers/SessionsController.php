<?php
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../../config/database.php';

class SessionsController {
    private $db;
    private $session;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->session = new Session($this->db);
    }

    public function index() {
        $sessions = $this->session->getAll();
        include __DIR__ . '/../views/sessions/index.php';
    }

    public function create() {
        $classes = $this->session->getClasses();
        $rooms = $this->session->getRooms();
        $trainers = $this->session->getTrainers();
        include __DIR__ . '/../views/sessions/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $batdau = $_POST['ngay'] . ' ' . $_POST['giobatdau'] . ':00';
            $ketthuc = $_POST['ngay'] . ' ' . $_POST['gioketthuc'] . ':00';
            
            $data = [
                'malop' => $_POST['malop'],
                'maphong' => $_POST['maphong'],
                'mahlv' => $_POST['mahlv'],
                'batdau' => $batdau,
                'ketthuc' => $ketthuc,
                'siso' => $_POST['siso'],
                'trangthai' => $_POST['trangthai']
            ];

            if ($this->session->create($data)) {
                $_SESSION['success'] = 'Thêm buổi lớp thành công!';
                header('Location: ?c=sessions&a=index');
                exit();
            } else {
                $error = 'Thêm buổi lớp thất bại!';
                $classes = $this->session->getClasses();
                $rooms = $this->session->getRooms();
                $trainers = $this->session->getTrainers();
                include __DIR__ . '/../views/sessions/create.php';
            }
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        $session = $this->session->find($id);
        
        if (!$session) {
            $_SESSION['error'] = 'Không tìm thấy buổi lớp!';
            header('Location: ?c=sessions&a=index');
            exit();
        }

        $classes = $this->session->getClasses();
        $rooms = $this->session->getRooms();
        $trainers = $this->session->getTrainers();
        include __DIR__ . '/../views/sessions/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            
            $batdau = $_POST['ngay'] . ' ' . $_POST['giobatdau'] . ':00';
            $ketthuc = $_POST['ngay'] . ' ' . $_POST['gioketthuc'] . ':00';
            
            $data = [
                'malop' => $_POST['malop'],
                'maphong' => $_POST['maphong'],
                'mahlv' => $_POST['mahlv'],
                'batdau' => $batdau,
                'ketthuc' => $ketthuc,
                'siso' => $_POST['siso'],
                'trangthai' => $_POST['trangthai']
            ];

            if ($this->session->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật buổi lớp thành công!';
                header('Location: ?c=sessions&a=index');
                exit();
            } else {
                $error = 'Cập nhật buổi lớp thất bại!';
                $session = $this->session->find($id);
                $classes = $this->session->getClasses();
                $rooms = $this->session->getRooms();
                $trainers = $this->session->getTrainers();
                include __DIR__ . '/../views/sessions/edit.php';
            }
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->session->delete($id)) {
            $_SESSION['success'] = 'Xóa buổi lớp thành công!';
        } else {
            $_SESSION['error'] = 'Xóa buổi lớp thất bại!';
        }
        
        header('Location: ?c=sessions&a=index');
        exit();
    }
}
?>