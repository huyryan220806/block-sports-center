<?php
// app/controllers/MembersController.php
// Dùng MySQL thật (bảng hoivien), sort A–Z, create/edit/update/delete

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Member.php';

class MembersController extends Controller
{
    /**
     * @var Member
     */
    private $memberModel;

    /**
     * @var PDO
     */
    private $db;

    public function __construct()
    {
        // Model giữ lại nếu sau này cần tới
        $this->memberModel = new Member();

        // Kết nối DB qua singleton Database
        $database   = Database::getInstance();
        $this->db   = $database->getConnection();
    }

    /**
     * Danh sách hội viên + sắp xếp
     * ?c=members&a=index&sort=name_asc|name_desc|id_asc|id_desc
     */
    public function index()
{
    // Lấy tham số sort & page từ URL
    $sort = $_GET['sort'] ?? 'id_desc';  // id_desc, id_asc, name_asc, name_desc
    $page = isset($_GET['page']) && ctype_digit($_GET['page'])
        ? (int)$_GET['page']
        : 1;

    $perPage = 10; // 10 dòng / trang

    // Quy định ORDER BY an toàn
    switch ($sort) {
        case 'name_asc':
            $orderBy = 'HOVATEN ASC';
            break;
        case 'name_desc':
            $orderBy = 'HOVATEN DESC';
            break;
        case 'id_asc':
            $orderBy = 'MAHV ASC';
            break;
        case 'id_desc':
        default:
            $orderBy = 'MAHV DESC';
            $sort    = 'id_desc';
            break;
    }

    // Đếm tổng số bản ghi
    $countStmt = $this->db->query("SELECT COUNT(*) FROM hoivien");
    $total     = (int)$countStmt->fetchColumn();

    $totalPages = max(1, (int)ceil($total / $perPage));
    if ($page > $totalPages) $page = $totalPages;
    if ($page < 1) $page = 1;

    $offset = ($page - 1) * $perPage;

    // Lấy danh sách hội viên có sort + phân trang
    $sql = "SELECT MAHV, HOVATEN, GIOITINH, NGAYSINH, SDT, EMAIL, DIACHI, TRANGTHAI
            FROM hoivien
            ORDER BY $orderBy
            LIMIT :limit OFFSET :offset";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
    $stmt->execute();

    $members = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Gửi dữ liệu xuống view
    $this->view('members/index', [
        'members'    => $members,
        'sort'       => $sort,
        'page'       => $page,
        'totalPages' => $totalPages,
        'total'      => $total,
    ]);
    }

    /**
     * Form tạo mới hội viên
     * GET: ?c=members&a=create
     */
    public function create()
    {
        $this->view('members/create');
    }

    /**
     * Lưu hội viên mới vào MySQL
     * POST: ?c=members&a=store (form create.php trỏ về đây)
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=members&a=index');
            return;
        }

        $data = [
            'hoten'       => trim($_POST['full_name'] ?? ''),
            'sodienthoai' => trim($_POST['phone'] ?? ''),
            'email'       => trim($_POST['email'] ?? ''),
            'gioitinh'    => $_POST['gender'] ?? null,
            'ngaysinh'    => $_POST['dob'] ?? null,
            'diachi'      => trim($_POST['address'] ?? ''),
            'trangthai'   => $this->mapStatus($_POST['status'] ?? 'ACTIVE'),
        ];

        // ===== VALIDATION =====
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

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Email không hợp lệ!');
            $this->redirect('?c=members&a=create');
            return;
        }

        if (!preg_match('/^[0-9]{10,11}$/', $data['sodienthoai'])) {
            $this->setFlash('error', 'Số điện thoại phải có 10-11 chữ số!');
            $this->redirect('?c=members&a=create');
            return;
        }

        // ===== INSERT vào MySQL =====
        $sql = "INSERT INTO hoivien (HOVATEN, NGAYSINH, GIOITINH, SDT, EMAIL, DIACHI, TRANGTHAI)
                VALUES (:hoten, :ngaysinh, :gioitinh, :sodienthoai, :email, :diachi, :trangthai)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':hoten'       => $data['hoten'],
                ':ngaysinh'    => $data['ngaysinh'] ?: null,
                ':gioitinh'    => $data['gioitinh'] ?: null,
                ':sodienthoai' => $data['sodienthoai'],
                ':email'       => $data['email'],
                ':diachi'      => $data['diachi'],
                ':trangthai'   => $data['trangthai'],
            ]);

            $this->setFlash('success', 'Thêm hội viên mới thành công!');
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi thêm hội viên: ' . $e->getMessage());
        }

        $this->redirect('?c=members&a=index');
    }

    /**
     * Form chỉnh sửa hội viên
     * GET: ?c=members&a=edit&id=10
     */
    public function edit()
    {
        $id = $_GET['id'] ?? 0;

        if (empty($id)) {
            $this->setFlash('error', 'Không tìm thấy ID hội viên!');
            $this->redirect('?c=members&a=index');
            return;
        }

        $sql  = "SELECT MAHV, HOVATEN, GIOITINH, NGAYSINH, SDT, EMAIL, DIACHI, TRANGTHAI
                 FROM hoivien
                 WHERE MAHV = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $member = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$member) {
            $this->setFlash('error', 'Hội viên không tồn tại!');
            $this->redirect('?c=members&a=index');
            return;
        }

        $this->view('members/edit', [
            'member' => $member,
        ]);
    }

    /**
     * Cập nhật hội viên
     * POST: ?c=members&a=update
     */
    public function update()
    {
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

        $data = [
            'hoten'       => trim($_POST['full_name'] ?? ''),
            'sodienthoai' => trim($_POST['phone'] ?? ''),
            'email'       => trim($_POST['email'] ?? ''),
            'gioitinh'    => $_POST['gender'] ?? null,
            'ngaysinh'    => $_POST['dob'] ?? null,
            'diachi'      => trim($_POST['address'] ?? ''),
            'trangthai'   => $this->mapStatus($_POST['status'] ?? 'ACTIVE'),
        ];

        // ===== VALIDATION =====
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

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Email không hợp lệ!');
            $this->redirect('?c=members&a=edit&id=' . $id);
            return;
        }

        if (!preg_match('/^[0-9]{10,11}$/', $data['sodienthoai'])) {
            $this->setFlash('error', 'Số điện thoại phải có 10-11 chữ số!');
            $this->redirect('?c=members&a=edit&id=' . $id);
            return;
        }

        // ===== UPDATE MySQL =====
        $sql = "UPDATE hoivien
                SET HOVATEN   = :hoten,
                    NGAYSINH  = :ngaysinh,
                    GIOITINH  = :gioitinh,
                    SDT       = :sodienthoai,
                    EMAIL     = :email,
                    DIACHI    = :diachi,
                    TRANGTHAI = :trangthai
                WHERE MAHV    = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':hoten'       => $data['hoten'],
                ':ngaysinh'    => $data['ngaysinh'] ?: null,
                ':gioitinh'    => $data['gioitinh'] ?: null,
                ':sodienthoai' => $data['sodienthoai'],
                ':email'       => $data['email'],
                ':diachi'      => $data['diachi'],
                ':trangthai'   => $data['trangthai'],
                ':id'          => $id,
            ]);

            $this->setFlash('success', 'Cập nhật thông tin hội viên thành công!');
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi cập nhật hội viên: ' . $e->getMessage());
        }

        $this->redirect('?c=members&a=index');
    }

    /**
     * Xóa hội viên
     * GET: ?c=members&a=delete&id=10
     * Được gọi từ JS confirmDelete(id)
     */
    public function delete()
    {
        $id = $_GET['id'] ?? 0;

        if (empty($id)) {
            $this->setFlash('error', 'Không tìm thấy ID hội viên!');
            $this->redirect('?c=members&a=index');
            return;
        }

        $sql  = "DELETE FROM hoivien WHERE MAHV = :id";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute([':id' => $id]);
            $this->setFlash('success', 'Xóa hội viên thành công!');
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi xóa hội viên: ' . $e->getMessage());
        }

        $this->redirect('?c=members&a=index');
    }

    /**
     * Chuẩn hóa trạng thái:
     * - Nhận: ACTIVE / SUSPENDED / INACTIVE hoặc Hoạt động / Tạm ngưng / Không hoạt động
     * - Trả về: ACTIVE / SUSPENDED / INACTIVE (lưu trong DB)
     */
    private function mapStatus($status)
    {
        $statusMap = [
            'ACTIVE'           => 'ACTIVE',
            'Hoạt động'       => 'ACTIVE',
            'SUSPENDED'        => 'SUSPENDED',
            'Tạm ngưng'       => 'SUSPENDED',
            'INACTIVE'         => 'INACTIVE',
            'Không hoạt động' => 'INACTIVE',
        ];

        return $statusMap[$status] ?? 'ACTIVE';
    }
}