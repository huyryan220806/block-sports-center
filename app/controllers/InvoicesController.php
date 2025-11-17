<?php
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../../config/database.php';

class InvoicesController {
    private $db;
    private $invoice;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->invoice = new Invoice($this->db);
    }

    // Hiển thị danh sách hóa đơn
    public function index() {
        $invoices = $this->invoice->getAll();
        include __DIR__ . '/../views/invoices/index.php';
    }
    // Hiển thị chi tiết hóa đơn
    public function view() {
        $id = $_GET['id'] ?? 0;
        $invoice = $this->invoice->find($id);
        include __DIR__ . '/../views/invoices/view.php';
    }
    // Hiển thị form tạo hóa đơn mới
    public function create() {
        $members = $this->invoice->getMembers();
        $promotions = $this->invoice->getPromotions();
        include __DIR__ . '/../views/invoices/create.php';
    }
    // Lưu hóa đơn mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'mahv'       => $_POST['mahv'],
                'makm'       => $_POST['makm'] ?? null,
                'ngaylap'    => $_POST['ngaylap'],
                'trangthai'  => $_POST['trangthai'],
            ];
            if ($this->invoice->create($data)) {
                $_SESSION['success'] = 'Tạo hóa đơn thành công!';
                header('Location: ?c=invoices&a=index'); exit();
            } else {
                $error = 'Tạo hóa đơn thất bại!';
                $members = $this->invoice->getMembers();
                $promotions = $this->invoice->getPromotions();
                include __DIR__ . '/../views/invoices/create.php';
            }
        }
    }
    // Hiển thị form sửa hóa đơn
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $invoice = $this->invoice->find($id);
        $members = $this->invoice->getMembers();
        $promotions = $this->invoice->getPromotions();
        if (!$invoice) {
            $_SESSION['error'] = 'Không tìm thấy hóa đơn!';
            header('Location: ?c=invoices&a=index'); exit();
        }
        include __DIR__ . '/../views/invoices/edit.php';
    }
    // Xử lý cập nhật hóa đơn
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['mahdon'];
            $data = [
                'mahv'       => $_POST['mahv'],
                'makm'       => $_POST['makm'] ?? null,
                'ngaylap'    => $_POST['ngaylap'],
                'trangthai'  => $_POST['trangthai'],
            ];
            if ($this->invoice->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật hóa đơn thành công!';
                header('Location: ?c=invoices&a=index'); exit();
            } else {
                $error = 'Cập nhật hóa đơn thất bại!';
                $invoice = $this->invoice->find($id);
                $members = $this->invoice->getMembers();
                $promotions = $this->invoice->getPromotions();
                include __DIR__ . '/../views/invoices/edit.php';
            }
        }
    }
    // Xóa hóa đơn
    public function delete() {
        $id = $_GET['id'] ?? 0;
        if ($this->invoice->delete($id)) {
            $_SESSION['success'] = 'Xóa hóa đơn thành công!';
        } else {
            $_SESSION['error'] = 'Xóa thất bại!';
        }
        header('Location: ?c=invoices&a=index'); exit();
    }
}
?>