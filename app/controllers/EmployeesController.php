<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Employee.php';

class EmployeesController extends Controller
{
    private $db;
    private $employeeModel;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->employeeModel = new Employee($this->db);
    }

    /**
     * Danh sách nhân viên
     */
    public function index()
    {
        $keyword = $_GET['search'] ?? '';
        
        if (!empty($keyword)) {
            $employees = $this->employeeModel->search($keyword);
        } else {
            $employees = $this->employeeModel->getAll();
        }

        $this->view('employees/index', [
            'employees' => $employees,
            'keyword' => $keyword
        ]);
    }

    /**
     * Form tạo nhân viên mới
     */
    public function create()
    {
        $this->view('employees/create');
    }

    /**
     * Xử lý tạo nhân viên
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=employees&a=index');
            return;
        }

        // Validate
        $hoten = trim($_POST['hoten'] ?? '');

        if (empty($hoten)) {
            $this->setFlash('error', 'Vui lòng nhập họ tên nhân viên!');
            $this->redirect('?c=employees&a=create');
            return;
        }

        // Chuẩn bị dữ liệu theo cấu trúc bảng thực tế
        $data = [
            'hoten' => $hoten,
            'sdt' => trim($_POST['sdt'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'vaitro' => $_POST['vaitro'] ?? 'OTHER',
            'ngayvaolam' => $_POST['ngayvaolam'] ?? date('Y-m-d'),
            'trangthai' => isset($_POST['trangthai']) ? (int)$_POST['trangthai'] : 1
        ];

        $result = $this->employeeModel->create($data);

        if ($result) {
            $this->setFlash('success', 'Thêm nhân viên thành công!');
            $this->redirect('?c=employees&a=index');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra khi thêm nhân viên!');
            $this->redirect('?c=employees&a=create');
        }
    }

    /**
     * Form chỉnh sửa nhân viên
     */
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $employee = $this->employeeModel->getById($id);

        if (!$employee) {
            $this->setFlash('error', 'Không tìm thấy nhân viên!');
            $this->redirect('?c=employees&a=index');
            return;
        }

        $this->view('employees/edit', [
            'employee' => $employee
        ]);
    }

    /**
     * Xử lý cập nhật nhân viên
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=employees&a=index');
            return;
        }

        $id = $_POST['id'] ?? 0;
        $employee = $this->employeeModel->getById($id);

        if (!$employee) {
            $this->setFlash('error', 'Không tìm thấy nhân viên!');
            $this->redirect('?c=employees&a=index');
            return;
        }

        // Validate
        $hoten = trim($_POST['hoten'] ?? '');

        if (empty($hoten)) {
            $this->setFlash('error', 'Vui lòng nhập họ tên nhân viên!');
            $this->redirect('?c=employees&a=edit&id=' . $id);
            return;
        }

        // Chuẩn bị dữ liệu
        $data = [
            'hoten' => $hoten,
            'sdt' => trim($_POST['sdt'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'vaitro' => $_POST['vaitro'] ?? 'OTHER',
            'ngayvaolam' => $_POST['ngayvaolam'] ?? date('Y-m-d'),
            'trangthai' => isset($_POST['trangthai']) ? (int)$_POST['trangthai'] : 1
        ];

        $result = $this->employeeModel->update($id, $data);

        if ($result) {
            $this->setFlash('success', 'Cập nhật nhân viên thành công!');
            $this->redirect('?c=employees&a=index');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra khi cập nhật nhân viên!');
            $this->redirect('?c=employees&a=edit&id=' . $id);
        }
    }

    /**
     * Xóa nhân viên
     */
    public function delete()
    {
        $id = $_GET['id'] ?? 0;
        $employee = $this->employeeModel->getById($id);

        if (!$employee) {
            $this->setFlash('error', 'Không tìm thấy nhân viên!');
            $this->redirect('?c=employees&a=index');
            return;
        }

        $result = $this->employeeModel->delete($id);

        if ($result) {
            $this->setFlash('success', 'Xóa nhân viên thành công!');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra khi xóa nhân viên!');
        }

        $this->redirect('?c=employees&a=index');
    }
}
//