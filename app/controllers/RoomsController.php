<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Room.php';

class RoomsController extends Controller
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * @var Room
     */
    private $roomModel;

    public function __construct()
    {
        $this->roomModel = new Room(); // để đó, sau xài cũng được

        // Kết nối DB qua Database singleton
        $database   = Database::getInstance();
        $this->db   = $database->getConnection(); // PDO
    }

    // ========================================
    // DANH SÁCH PHÒNG + SORT + PHÂN TRANG
    // ========================================
    public function index()
    {
        // sort: id_asc, id_desc, name_asc, name_desc, capacity_asc, capacity_desc
        $sort = $_GET['sort'] ?? 'id_desc';

        // page: mặc định trang 1
        $page = isset($_GET['page']) && ctype_digit($_GET['page'])
            ? (int)$_GET['page']
            : 1;

        $perPage = 10; // 10 phòng / trang

        // ORDER BY an toàn
        switch ($sort) {
            case 'name_asc':
                $orderBy = 'p.TENPHONG ASC';
                break;
            case 'name_desc':
                $orderBy = 'p.TENPHONG DESC';
                break;
            case 'capacity_asc':
                $orderBy = 'p.SUCCHUA ASC';
                break;
            case 'capacity_desc':
                $orderBy = 'p.SUCCHUA DESC';
                break;
            case 'id_asc':
                $orderBy = 'p.MAPHONG ASC';
                break;
            case 'id_desc':
            default:
                $orderBy = 'p.MAPHONG DESC';
                $sort    = 'id_desc';
                break;
        }

        // Đếm tổng số phòng
        $countStmt = $this->db->query("SELECT COUNT(*) FROM phong");
        $total     = (int)$countStmt->fetchColumn();

        $totalPages = max(1, (int)ceil($total / $perPage));
        if ($page > $totalPages) $page = $totalPages;
        if ($page < 1) $page = 1;

        $offset = ($page - 1) * $perPage;

        // Lấy danh sách phòng kèm tên khu
        $sql = "SELECT 
                    p.MAPHONG,
                    p.MAKHU,
                    p.TENPHONG,
                    p.SUCCHUA,
                    p.GHICHU,
                    p.HOATDONG,
                    k.TENKHU,
                    k.LOAIKHU
                FROM phong p
                JOIN khu k ON p.MAKHU = k.MAKHU
                ORDER BY $orderBy
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();

        $rooms = $stmt->fetchAll(PDO::FETCH_OBJ);

        $this->view('rooms/index', [
            'rooms'      => $rooms,
            'sort'       => $sort,
            'page'       => $page,
            'totalPages' => $totalPages,
            'total'      => $total,
        ]);
    }

    // ========================================
    // FORM THÊM PHÒNG
    // ========================================
    public function create()
    {
        $areas = $this->getAreas();

        $this->view('rooms/create', [
            'areas' => $areas,
        ]);
    }

    // ========================================
    // XỬ LÝ THÊM PHÒNG
    // ========================================
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=rooms&a=index');
            return;
        }

        // Form rooms/create.php đang dùng:
        // room_name, area, capacity, type, status, notes
        $data = [
            'tenphong'  => trim($_POST['room_name'] ?? ''),
            // area: mình hiểu là id khu (MAKHU) – sau bạn cho select option thì value chính là MAKHU
            'makhu'     => (int)($_POST['area'] ?? 0),
            'succhua'   => (int)($_POST['capacity'] ?? 0),
            'loaiphong' => trim($_POST['type'] ?? ''),   // sẽ nhét vào GHICHU
            'trangthai' => $_POST['status'] ?? 'ACTIVE', // Hoạt động / Ngưng / 1 / 0...
            'ghichu'    => trim($_POST['notes'] ?? ''),
        ];

        // ====== VALIDATION CƠ BẢN ======
        if (empty($data['tenphong'])) {
            $this->setFlash('error', 'Vui lòng nhập tên phòng/sân!');
            $this->redirect('?c=rooms&a=create');
            return;
        }

        if ($data['makhu'] <= 0) {
            $this->setFlash('error', 'Vui lòng chọn khu vực hợp lệ!');
            $this->redirect('?c=rooms&a=create');
            return;
        }

        if ($data['succhua'] <= 0) {
            $this->setFlash('error', 'Sức chứa phải lớn hơn 0!');
            $this->redirect('?c=rooms&a=create');
            return;
        }

        $hoatdong = $this->mapStatusToActiveFlag($data['trangthai']);

        // Ghép type + notes vào GHICHU cho dễ lưu
        $noteParts = [];
        if (!empty($data['loaiphong'])) {
            $noteParts[] = '[Loại: ' . $data['loaiphong'] . ']';
        }
        if (!empty($data['ghichu'])) {
            $noteParts[] = $data['ghichu'];
        }
        $ghichu = implode(' ', $noteParts);

        // ========== INSERT ==========
        $sql = "INSERT INTO phong (MAKHU, TENPHONG, SUCCHUA, GHICHU, HOATDONG)
                VALUES (:makhu, :tenphong, :succhua, :ghichu, :hoatdong)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':makhu'    => $data['makhu'],
                ':tenphong' => $data['tenphong'],
                ':succhua'  => $data['succhua'],
                ':ghichu'   => $ghichu ?: null,
                ':hoatdong' => $hoatdong,
            ]);

            $this->setFlash('success', 'Thêm phòng/sân mới thành công!');
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi thêm phòng/sân: ' . $e->getMessage());
        }

        $this->redirect('?c=rooms&a=index');
    }

    // ========================================
    // FORM CHỈNH SỬA PHÒNG
    // ========================================
    public function edit()
    {
        $id = $_GET['id'] ?? 0;

        if (empty($id)) {
            $this->setFlash('error', 'Không tìm thấy ID phòng/sân!');
            $this->redirect('?c=rooms&a=index');
            return;
        }

        $sql  = "SELECT 
                    p.MAPHONG,
                    p.MAKHU,
                    p.TENPHONG,
                    p.SUCCHUA,
                    p.GHICHU,
                    p.HOATDONG,
                    k.TENKHU,
                    k.LOAIKHU
                 FROM phong p
                 JOIN khu k ON p.MAKHU = k.MAKHU
                 WHERE p.MAPHONG = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $room = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$room) {
            $this->setFlash('error', 'Phòng/sân không tồn tại!');
            $this->redirect('?c=rooms&a=index');
            return;
        }

        $areas = $this->getAreas();

        $this->view('rooms/edit', [
            'room'  => $room,
            'areas' => $areas,
        ]);
    }

    // ========================================
    // XỬ LÝ CẬP NHẬT PHÒNG
    // ========================================
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=rooms&a=index');
            return;
        }

        $id = $_POST['id'] ?? 0;

        if (empty($id)) {
            $this->setFlash('error', 'Không tìm thấy ID phòng/sân!');
            $this->redirect('?c=rooms&a=index');
            return;
        }

        $data = [
            'tenphong'  => trim($_POST['room_name'] ?? ''),
            'makhu'     => (int)($_POST['area'] ?? 0),
            'succhua'   => (int)($_POST['capacity'] ?? 0),
            'loaiphong' => trim($_POST['type'] ?? ''),
            'trangthai' => $_POST['status'] ?? 'ACTIVE',
            'ghichu'    => trim($_POST['notes'] ?? ''),
        ];

        // ===== VALIDATION =====
        if (empty($data['tenphong'])) {
            $this->setFlash('error', 'Vui lòng nhập tên phòng/sân!');
            $this->redirect('?c=rooms&a=edit&id=' . $id);
            return;
        }

        if ($data['makhu'] <= 0) {
            $this->setFlash('error', 'Vui lòng chọn khu vực hợp lệ!');
            $this->redirect('?c=rooms&a=edit&id=' . $id);
            return;
        }

        if ($data['succhua'] <= 0) {
            $this->setFlash('error', 'Sức chứa phải lớn hơn 0!');
            $this->redirect('?c=rooms&a=edit&id=' . $id);
            return;
        }

        $hoatdong = $this->mapStatusToActiveFlag($data['trangthai']);

        $noteParts = [];
        if (!empty($data['loaiphong'])) {
            $noteParts[] = '[Loại: ' . $data['loaiphong'] . ']';
        }
        if (!empty($data['ghichu'])) {
            $noteParts[] = $data['ghichu'];
        }
        $ghichu = implode(' ', $noteParts);

        // ===== UPDATE =====
        $sql = "UPDATE phong
                SET MAKHU    = :makhu,
                    TENPHONG = :tenphong,
                    SUCCHUA  = :succhua,
                    GHICHU   = :ghichu,
                    HOATDONG = :hoatdong
                WHERE MAPHONG = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':makhu'    => $data['makhu'],
                ':tenphong' => $data['tenphong'],
                ':succhua'  => $data['succhua'],
                ':ghichu'   => $ghichu ?: null,
                ':hoatdong' => $hoatdong,
                ':id'       => $id,
            ]);

            $this->setFlash('success', 'Cập nhật phòng/sân thành công!');
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi cập nhật phòng/sân: ' . $e->getMessage());
        }

        $this->redirect('?c=rooms&a=index');
    }

    // ========================================
    // XÓA PHÒNG
    // ========================================
    public function delete()
    {
        $id = $_GET['id'] ?? 0;

        if (empty($id)) {
            $this->setFlash('error', 'Không tìm thấy ID phòng/sân!');
            $this->redirect('?c=rooms&a=index');
            return;
        }

        // Lấy tên phòng để hiển thị message
        $stmt = $this->db->prepare("SELECT TENPHONG FROM phong WHERE MAPHONG = :id");
        $stmt->execute([':id' => $id]);
        $room = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$room) {
            $this->setFlash('error', 'Phòng/sân không tồn tại!');
            $this->redirect('?c=rooms&a=index');
            return;
        }

        $sql = "DELETE FROM phong WHERE MAPHONG = :id";

        try {
            $delStmt = $this->db->prepare($sql);
            $delStmt->execute([':id' => $id]);

            $this->setFlash('success', "Đã xóa phòng/sân: {$room->TENPHONG}");
        } catch (PDOException $e) {
            // Có thể dính FK (buoilop, datphong, pt_session, locker,...)
            $this->setFlash('error', 'Không thể xóa phòng/sân (đang có dữ liệu liên quan)!');
        }

        $this->redirect('?c=rooms&a=index');
    }

    // ========================================
    // HÀM PHỤ LẤY DANH SÁCH KHU (AREA)
    // ========================================
    private function getAreas()
    {
        $sql  = "SELECT MAKHU, TENKHU, LOAIKHU FROM khu ORDER BY TENKHU ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Map status từ form → cờ HOATDONG (tinyint)
    private function mapStatusToActiveFlag($status)
    {
        // chấp nhận: 1 / 0 / ACTIVE / INACTIVE / Hoạt động / Ngưng / ...
        $status = trim((string)$status);

        $inactiveValues = ['0', 'INACTIVE', 'Ngưng', 'NGUNG', 'OFF', 'DISABLED'];

        return in_array(strtoupper($status), array_map('strtoupper', $inactiveValues)) ? 0 : 1;
    }
}
//