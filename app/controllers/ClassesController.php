<?php
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../../config/database.php';

class ClassesController {
    private $db;
    private $class;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->class = new ClassModel($this->db);
    }

    public function index() {
        $classes = $this->class->getAll();
        include __DIR__ . '/../views/classes/index.php';
    }

    public function create() {
        include __DIR__ . '/../views/classes/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'tenlop' => $_POST['tenlop'],
                'thoiluong' => $_POST['thoiluong'],
                'siso' => $_POST['siso'],
                'mota' => $_POST['mota']
            ];

            if ($this->class->create($data)) {
                $_SESSION['success'] = 'Thêm lớp học thành công!';
                header('Location: ?c=classes&a=index');
                exit();
            } else {
                $error = 'Thêm lớp học thất bại!';
                include __DIR__ . '/../views/classes/create.php';
            }
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        $class = $this->class->find($id);
        
        if (!$class) {
            $_SESSION['error'] = 'Không tìm thấy lớp học!';
            header('Location: ?c=classes&a=index');
            exit();
        }

        include __DIR__ . '/../views/classes/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            
            $data = [
                'tenlop' => $_POST['tenlop'],
                'thoiluong' => $_POST['thoiluong'],
                'siso' => $_POST['siso'],
                'mota' => $_POST['mota']
            ];

            if ($this->class->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật lớp học thành công!';
                header('Location: ?c=classes&a=index');
                exit();
            } else {
                $error = 'Cập nhật lớp học thất bại!';
                $class = $this->class->find($id);
                include __DIR__ . '/../views/classes/edit.php';
            }
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->class->delete($id)) {
            $_SESSION['success'] = 'Xóa lớp học thành công!';
        } else {
            $_SESSION['error'] = 'Xóa lớp học thất bại!';
        }
        
        header('Location: ?c=classes&a=index');
        exit();
    }
}
?>