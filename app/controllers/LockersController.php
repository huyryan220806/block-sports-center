<?php
require_once __DIR__ . '/../models/Locker.php';
require_once __DIR__ . '/../../config/database.php';

class LockersController {
    private $db;
    private $locker;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->locker = new Locker($this->db);
    }

    // Danh sách locker
    public function index() {
        $lockers = $this->locker->getAll();
        $rooms = $this->locker->getRooms();
        // Optional: Thống kê ở đây nếu cần
        include __DIR__ . '/../views/lockers/index.php';
    }

    // Hiển thị form thêm
    public function create() {
        $rooms = $this->locker->getRooms();
        include __DIR__ . '/../views/lockers/create.php';
    }

    // Lưu locker mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'maphong'   => $_POST['maphong'],
                'kitu'      => $_POST['kitu'],
                'hoatdong'  => $_POST['hoatdong'],
            ];
            if ($this->locker->create($data)) {
                $_SESSION['success'] = 'Thêm tủ khóa thành công!';
                header('Location: ?c=lockers&a=index');
                exit();
            } else {
                $error = 'Thêm tủ khóa thất bại!';
                $rooms = $this->locker->getRooms();
                include __DIR__ . '/../views/lockers/create.php';
            }
        }
    }

    // Hiển thị form sửa
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $locker = $this->locker->find($id);
        $rooms = $this->locker->getRooms();
        if (!$locker) {
            $_SESSION['error'] = 'Không tìm thấy tủ khóa!';
            header('Location: ?c=lockers&a=index');
            exit();
        }
        include __DIR__ . '/../views/lockers/edit.php';
    }

    // Cập nhật locker
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['matu'];
            $data = [
                'maphong'   => $_POST['maphong'],
                'kitu'      => $_POST['kitu'],
                'hoatdong'  => $_POST['hoatdong'],
            ];
            if ($this->locker->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật thành công!';
                header('Location: ?c=lockers&a=index');
                exit();
            } else {
                $error = 'Cập nhật thất bại!';
                $locker = $this->locker->find($id);
                $rooms = $this->locker->getRooms();
                include __DIR__ . '/../views/lockers/edit.php';
            }
        }
    }

    // Xóa locker
    public function delete() {
        $id = $_GET['id'] ?? 0;
        if ($this->locker->delete($id)) {
            $_SESSION['success'] = 'Xóa tủ khóa thành công!';
        } else {
            $_SESSION['error'] = 'Xóa thất bại!';
        }
        header('Location: ?c=lockers&a=index');
        exit();
    }
}
?>