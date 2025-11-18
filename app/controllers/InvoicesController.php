<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Invoice.php';

class InvoicesController extends Controller
{
    private $db;
    private $invoiceModel;
    
    public function __construct() {
        $this->invoiceModel = new Invoice(Database::getInstance()->getConnection());
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Danh sách hóa đơn
     */
    public function index() {
        $sort = $_GET['sort'] ?? 'id_desc';
        $page = isset($_GET['page']) && ctype_digit($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $_GET['q'] ?? '';
        $perPage = 10;
        
        $result = $this->invoiceModel->getAll($sort, $page, $perPage, $search);
        
        $this->view('invoices/index', [
            'invoices' => $result['data'],
            'total' => $result['total'],
            'totalPages' => $result['totalPages'],
            'page' => $page,
            'sort' => $sort,
            'search' => $search
        ]);
    }
    
    /**
     * ✅ ĐỔI TÊN: view() → show()
     * Xem chi tiết hóa đơn
     */
    public function show() {
        $id = $_GET['id'] ?? 0;
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            $this->setFlash('error', 'Không tìm thấy hóa đơn!');
            $this->redirect('?c=invoices&a=index');
            return;
        }
        
        $this->view('invoices/view', ['invoice' => $invoice]);
    }
    
    /**
     * Form tạo hóa đơn mới
     */
    public function create() {
        $members = $this->invoiceModel->getMembers();
        $promotions = $this->invoiceModel->getPromotions();
        $packages = $this->invoiceModel->getPackages();
        
        $this->view('invoices/create', [
            'members' => $members,
            'promotions' => $promotions,
            'packages' => $packages
        ]);
    }
    
    /**
     * Lưu hóa đơn mới
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=invoices&a=index');
            return;
        }
        
        $data = [
            'mahv' => (int)$_POST['mahv'],
            'makm' => !empty($_POST['makm']) ? (int)$_POST['makm'] : null,
            'ngaylap' => $_POST['ngaylap'] ?? date('Y-m-d H:i:s'),
            'trangthai' => $_POST['trangthai'] ?? 'DRAFT',
            'items' => []
        ];
        
        if (!empty($_POST['items'])) {
            foreach ($_POST['items'] as $item) {
                if (!empty($item['mota']) && !empty($item['dongia'])) {
                    $data['items'][] = [
                        'loaihang' => $item['loaihang'] ?? 'OTHER',
                        'ref_id' => !empty($item['ref_id']) ? (int)$item['ref_id'] : null,
                        'mota' => trim($item['mota']),
                        'soluong' => (int)($item['soluong'] ?? 1),
                        'dongia' => (float)$item['dongia']
                    ];
                }
            }
        }
        
        if (empty($data['items'])) {
            $this->setFlash('error', 'Vui lòng thêm ít nhất 1 dòng hóa đơn!');
            $this->redirect('?c=invoices&a=create');
            return;
        }
        
        $result = $this->invoiceModel->create($data);
        
        if ($result) {
            $this->setFlash('success', 'Tạo hóa đơn thành công!');
            // ✅ ĐỔI: a=view → a=show
            $this->redirect('?c=invoices&a=show&id=' . $result);
        } else {
            $this->setFlash('error', 'Tạo hóa đơn thất bại!');
            $this->redirect('?c=invoices&a=create');
        }
    }
    
    /**
     * Form sửa hóa đơn
     */
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            $this->setFlash('error', 'Không tìm thấy hóa đơn!');
            $this->redirect('?c=invoices&a=index');
            return;
        }
        
        $members = $this->invoiceModel->getMembers();
        $promotions = $this->invoiceModel->getPromotions();
        $packages = $this->invoiceModel->getPackages();
        
        $this->view('invoices/edit', [
            'invoice' => $invoice,
            'members' => $members,
            'promotions' => $promotions,
            'packages' => $packages
        ]);
    }
    
    /**
     * Cập nhật hóa đơn
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=invoices&a=index');
            return;
        }
        
        $id = (int)$_POST['mahdon'];
        
        $data = [
            'mahv' => (int)$_POST['mahv'],
            'makm' => !empty($_POST['makm']) ? (int)$_POST['makm'] : null,
            'ngaylap' => $_POST['ngaylap'],
            'trangthai' => $_POST['trangthai'],
            'items' => []
        ];
        
        if (!empty($_POST['items'])) {
            foreach ($_POST['items'] as $item) {
                if (!empty($item['mota']) && !empty($item['dongia'])) {
                    $data['items'][] = [
                        'loaihang' => $item['loaihang'] ?? 'OTHER',
                        'ref_id' => !empty($item['ref_id']) ? (int)$item['ref_id'] : null,
                        'mota' => trim($item['mota']),
                        'soluong' => (int)($item['soluong'] ?? 1),
                        'dongia' => (float)$item['dongia']
                    ];
                }
            }
        }
        
        $result = $this->invoiceModel->update($id, $data);
        
        if ($result) {
            $this->setFlash('success', 'Cập nhật hóa đơn thành công!');
            // ✅ ĐỔI: a=view → a=show
            $this->redirect('?c=invoices&a=show&id=' . $id);
        } else {
            $this->setFlash('error', 'Cập nhật hóa đơn thất bại!');
            $this->redirect('?c=invoices&a=edit&id=' . $id);
        }
    }
    
    /**
     * Xóa hóa đơn
     */
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->invoiceModel->delete($id)) {
            $this->setFlash('success', 'Xóa hóa đơn thành công!');
        } else {
            $this->setFlash('error', 'Xóa hóa đơn thất bại!');
        }
        
        $this->redirect('?c=invoices&a=index');
    }
    
    /**
     * Xem danh sách gói tập, khuyến mãi
     */
    public function packages() {
        $packages = $this->invoiceModel->getPackages();
        $promotions = $this->invoiceModel->getPromotions();
        
        $this->view('invoices/packages', [
            'packages' => $packages,
            'promotions' => $promotions
        ]);
    }
}