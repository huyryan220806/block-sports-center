<?php
require_once __DIR__ . '/../../config/database.php';

class SessionsController extends Controller
{
    /** @var PDO */
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Danh sách buổi lớp + tìm kiếm + phân trang.
     * URL: ?c=sessions&a=index&q=...&page=...
     */
    public function index()
    {
        $search = trim($_GET['q'] ?? '');

        // Phân trang
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $perPage = 10;

        // WHERE không dùng placeholder để tránh lỗi HY093
        $where = " WHERE 1 = 1 ";
        if ($search !== '') {
            $kw = $this->db->quote('%' . $search . '%');
            $where .= " AND (
                l.TENLOP    LIKE $kw
                OR p.TENPHONG LIKE $kw
                OR nv.HOTEN   LIKE $kw
            )";
        }

        // Đếm tổng bản ghi
        $countSql = "
            SELECT COUNT(*) AS total
            FROM buoilop b
            JOIN lop   l  ON b.MALOP   = l.MALOP
            JOIN phong p  ON b.MAPHONG = p.MAPHONG
            LEFT JOIN nhanvien nv ON b.MAHLV = nv.MANV
            $where
        ";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute();
        $totalRows = (int) $countStmt->fetchColumn();

        $totalPages = max(1, (int) ceil($totalRows / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $perPage;

        // Lấy dữ liệu trang hiện tại
        $listSql = "
            SELECT 
                b.MABUOI,
                b.MALOP,
                b.MAPHONG,
                b.MAHLV,
                b.BATDAU,
                b.KETTHUC,
                b.SISO,
                b.TRANGTHAI,
                l.TENLOP,
                p.TENPHONG,
                nv.HOTEN AS TENHLV
            FROM buoilop b
            JOIN lop   l  ON b.MALOP   = l.MALOP
            JOIN phong p  ON b.MAPHONG = p.MAPHONG
            LEFT JOIN nhanvien nv ON b.MAHLV = nv.MANV
            $where
            ORDER BY b.BATDAU DESC
            LIMIT :limit OFFSET :offset
        ";
        $listStmt = $this->db->prepare($listSql);
        $listStmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $listStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $listStmt->execute();
        $sessions = $listStmt->fetchAll(PDO::FETCH_OBJ);

        $this->view('sessions/index', [
            'sessions'   => $sessions,
            'search'     => $search,
            'page'       => $page,
            'perPage'    => $perPage,
            'total'      => $totalRows,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * Form tạo buổi lớp mới.
     */
    public function create()
    {
        $classes  = $this->getClasses();
        $rooms    = $this->getRooms();
        $trainers = $this->getTrainers();

        $this->view('sessions/create', [
            'classes'  => $classes,
            'rooms'    => $rooms,
            'trainers' => $trainers,
            'old'      => [],
            'errors'   => [],
        ]);
    }

    /**
     * Lưu buổi lớp mới vào database.
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=sessions&a=index');
            return;
        }

        $classId   = $_POST['class_id']   ?? null;
        $roomId    = $_POST['room_id']    ?? null;
        $trainerId = $_POST['trainer_id'] ?? null;
        $date      = $_POST['date']       ?? '';
        $startTime = $_POST['start_time'] ?? '';
        $endTime   = $_POST['end_time']   ?? '';
        $capacity  = isset($_POST['capacity']) ? (int) $_POST['capacity'] : 0;
        $status    = $_POST['status']     ?? 'SCHEDULED';

        // Validate đơn giản
        if (empty($classId) || empty($roomId) || empty($date) || empty($startTime) || empty($endTime)) {
            $this->setFlash('error', 'Vui lòng nhập đầy đủ lớp, phòng, ngày và giờ học.');
            $this->redirect('?c=sessions&a=create');
            return;
        }

        $batdau  = $date . ' ' . $startTime . ':00';
        $ketthuc = $date . ' ' . $endTime . ':00';

        $sql = "
            INSERT INTO buoilop (MALOP, MAPHONG, MAHLV, BATDAU, KETTHUC, SISO, TRANGTHAI)
            VALUES (:malop, :maphong, :mahlv, :batdau, :ketthuc, :siso, :trangthai)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':malop', $classId, PDO::PARAM_INT);
        $stmt->bindValue(':maphong', $roomId, PDO::PARAM_INT);
        if (!empty($trainerId)) {
            $stmt->bindValue(':mahlv', $trainerId, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':mahlv', null, PDO::PARAM_NULL);
        }
        $stmt->bindValue(':batdau', $batdau);
        $stmt->bindValue(':ketthuc', $ketthuc);
        $stmt->bindValue(':siso', $capacity, PDO::PARAM_INT);
        $stmt->bindValue(':trangthai', $status);

        if ($stmt->execute()) {
            $this->setFlash('success', 'Thêm buổi lớp thành công!');
        } else {
            $this->setFlash('error', 'Thêm buổi lớp thất bại!');
        }

        $this->redirect('?c=sessions&a=index');
    }

    /**
     * Form chỉnh sửa buổi lớp.
     */
    public function edit()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id <= 0) {
            $this->redirect('?c=sessions&a=index');
            return;
        }

        $session = $this->findSession($id);
        if (!$session) {
            $this->setFlash('error', 'Không tìm thấy buổi lớp!');
            $this->redirect('?c=sessions&a=index');
            return;
        }

        $classes  = $this->getClasses();
        $rooms    = $this->getRooms();
        $trainers = $this->getTrainers();

        $this->view('sessions/edit', [
            'session'  => $session,
            'classes'  => $classes,
            'rooms'    => $rooms,
            'trainers' => $trainers,
            'old'      => [],
            'errors'   => [],
        ]);
    }

    /**
     * Cập nhật buổi lớp.
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=sessions&a=index');
            return;
        }

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id <= 0) {
            $this->setFlash('error', 'Thiếu ID buổi lớp cần cập nhật!');
            $this->redirect('?c=sessions&a=index');
            return;
        }

        $classId   = $_POST['class_id']   ?? null;
        $roomId    = $_POST['room_id']    ?? null;
        $trainerId = $_POST['trainer_id'] ?? null;
        $date      = $_POST['date']       ?? '';
        $startTime = $_POST['start_time'] ?? '';
        $endTime   = $_POST['end_time']   ?? '';
        $capacity  = isset($_POST['capacity']) ? (int) $_POST['capacity'] : 0;
        $status    = $_POST['status']     ?? 'SCHEDULED';

        if (empty($classId) || empty($roomId) || empty($date) || empty($startTime) || empty($endTime)) {
            $this->setFlash('error', 'Vui lòng nhập đầy đủ lớp, phòng, ngày và giờ học.');
            $this->redirect('?c=sessions&a=edit&id=' . $id);
            return;
        }

        $batdau  = $date . ' ' . $startTime . ':00';
        $ketthuc = $date . ' ' . $endTime . ':00';

        $sql = "
            UPDATE buoilop
            SET MALOP = :malop,
                MAPHONG = :maphong,
                MAHLV = :mahlv,
                BATDAU = :batdau,
                KETTHUC = :ketthuc,
                SISO = :siso,
                TRANGTHAI = :trangthai
            WHERE MABUOI = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':malop', $classId, PDO::PARAM_INT);
        $stmt->bindValue(':maphong', $roomId, PDO::PARAM_INT);
        if (!empty($trainerId)) {
            $stmt->bindValue(':mahlv', $trainerId, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':mahlv', null, PDO::PARAM_NULL);
        }
        $stmt->bindValue(':batdau', $batdau);
        $stmt->bindValue(':ketthuc', $ketthuc);
        $stmt->bindValue(':siso', $capacity, PDO::PARAM_INT);
        $stmt->bindValue(':trangthai', $status);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->setFlash('success', 'Cập nhật buổi lớp thành công!');
        } else {
            $this->setFlash('error', 'Cập nhật buổi lớp thất bại!');
        }

        $this->redirect('?c=sessions&a=index');
    }

    /**
     * Xóa buổi lớp.
     */
    public function delete()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id <= 0) {
            $this->redirect('?c=sessions&a=index');
            return;
        }

        $sql = "DELETE FROM buoilop WHERE MABUOI = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->setFlash('success', 'Xóa buổi lớp thành công!');
        } else {
            $this->setFlash('error', 'Xóa buổi lớp thất bại!');
        }

        $this->redirect('?c=sessions&a=index');
    }

    // ================== HELPER METHODS ==================

    /**
     * Lấy danh sách lớp.
     */
    protected function getClasses()
    {
        $sql = "SELECT MALOP, TENLOP FROM lop ORDER BY TENLOP ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy danh sách phòng (có HOATDONG nếu bạn đã tạo cột đó).
     */
    protected function getRooms()
    {
        // Nếu bảng phong có HOATDONG thì OK, còn không bạn bỏ bớt cột này đi
        $sql = "SELECT MAPHONG, TENPHONG, HOATDONG FROM phong ORDER BY TENPHONG ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy danh sách huấn luyện viên.
     */
    protected function getTrainers()
    {
        // HLV = nhân viên có bản ghi trong bảng hlv
        $sql = "
            SELECT nv.MANV, nv.HOTEN
            FROM nhanvien nv
            JOIN hlv h ON nv.MANV = h.MAHLV
            ORDER BY nv.HOTEN ASC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Tìm 1 buổi lớp theo ID.
     */
    protected function findSession($id)
    {
        $sql = "
            SELECT 
                b.*,
                l.TENLOP,
                p.TENPHONG,
                nv.HOTEN AS TENHLV
            FROM buoilop b
            JOIN lop   l  ON b.MALOP   = l.MALOP
            JOIN phong p  ON b.MAPHONG = p.MAPHONG
            LEFT JOIN nhanvien nv ON b.MAHLV = nv.MANV
            WHERE b.MABUOI = :id
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
?>