<?php
// app/controllers/TrainersController.php

require_once __DIR__ . '/../core/Database.php';

class TrainersController extends Controller
{
    /**
     * @var PDO
     */
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // ======================= DANH SÁCH + TÌM KIẾM =======================
    public function index()
    {
        $search = trim($_GET['q'] ?? '');

        $sql = "
            SELECT 
                nv.MANV,
                nv.HOTEN,
                nv.SDT,
                nv.EMAIL,
                nv.VAITRO,
                nv.NGAYVAOLAM,
                nv.TRANGTHAI,
                h.MAHLV,
                h.MOTA,
                h.PHI_GIO
            FROM nhanvien nv
            JOIN hlv h ON nv.MANV = h.MAHLV
            WHERE 1 = 1
        ";

        $params = [];

        if ($search !== '') {
            $sql .= " AND (
                        nv.HOTEN LIKE :q
                        OR nv.SDT LIKE :q
                        OR nv.EMAIL LIKE :q
                        OR h.MOTA LIKE :q
                    )";
            $params[':q'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY nv.HOTEN ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $trainers = $stmt->fetchAll(PDO::FETCH_OBJ);

        $this->view('trainers/index', [
            'trainers' => $trainers,
            'search'   => $search,
        ]);
    }

    // ======================= FORM TẠO HLV =======================
    public function create()
    {
        $this->view('trainers/create');
    }

    // ======================= LƯU HLV MỚI =======================
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=trainers&a=index');
            return;
        }

        $name        = trim($_POST['hoten']       ?? '');
        $phone       = trim($_POST['sdt']         ?? '');
        $email       = trim($_POST['email']       ?? '');
        $skill       = trim($_POST['chuyenmon']   ?? '');
        $hourRate    = (float)($_POST['phi_gio']  ?? 0);
        $startDate   = $_POST['ngayvaolam']       ?? '';
        $statusInput = $_POST['trangthai']        ?? '1';

        $status = ($statusInput === '0') ? 0 : 1;

        // Validate cơ bản
        if ($name === '') {
            $this->setFlash('error', 'Vui lòng nhập họ tên HLV.');
            $this->redirect('?c=trainers&a=create');
            return;
        }

        if ($phone === '') {
            $this->setFlash('error', 'Vui lòng nhập số điện thoại.');
            $this->redirect('?c=trainers&a=create');
            return;
        }

        if ($email === '') {
            $this->setFlash('error', 'Vui lòng nhập email.');
            $this->redirect('?c=trainers&a=create');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Email không hợp lệ.');
            $this->redirect('?c=trainers&a=create');
            return;
        }

        if ($hourRate <= 0) {
            $this->setFlash('error', 'Phí/giờ phải lớn hơn 0.');
            $this->redirect('?c=trainers&a=create');
            return;
        }

        if ($startDate === '') {
            $this->setFlash('error', 'Vui lòng chọn ngày vào làm.');
            $this->redirect('?c=trainers&a=create');
            return;
        }

        try {
            $this->db->beginTransaction();

            // 1. Thêm vào bảng nhanvien
            $sql1 = "INSERT INTO nhanvien (HOTEN, SDT, EMAIL, VAITRO, NGAYVAOLAM, TRANGTHAI)
                     VALUES (:hoten, :sdt, :email, 'OTHER', :ngayvaolam, :trangthai)";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute([
                ':hoten'      => $name,
                ':sdt'        => $phone,
                ':email'      => $email,
                ':ngayvaolam' => $startDate,
                ':trangthai'  => $status,
            ]);

            $newId = (int)$this->db->lastInsertId();

            // 2. Thêm vào bảng hlv (MAHLV trùng MANV)
            $sql2 = "INSERT INTO hlv (MAHLV, MOTA, PHI_GIO)
                     VALUES (:mahlv, :mota, :phi_gio)";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([
                ':mahlv'   => $newId,
                ':mota'    => $skill !== '' ? $skill : null,
                ':phi_gio' => $hourRate,
            ]);

            $this->db->commit();

            $this->setFlash('success', 'Thêm huấn luyện viên mới thành công.');
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->setFlash('error', 'Lỗi khi thêm HLV: ' . $e->getMessage());
        }

        $this->redirect('?c=trainers&a=index');
    }

    // ======================= FORM SỬA HLV =======================
    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('error', 'Không tìm thấy mã HLV.');
            $this->redirect('?c=trainers&a=index');
            return;
        }

        $sql = "
            SELECT 
                nv.MANV,
                nv.HOTEN,
                nv.SDT,
                nv.EMAIL,
                nv.VAITRO,
                nv.NGAYVAOLAM,
                nv.TRANGTHAI,
                h.MOTA,
                h.PHI_GIO
            FROM nhanvien nv
            JOIN hlv h ON nv.MANV = h.MAHLV
            WHERE nv.MANV = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $trainer = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$trainer) {
            $this->setFlash('error', 'Huấn luyện viên không tồn tại.');
            $this->redirect('?c=trainers&a=index');
            return;
        }

        $this->view('trainers/edit', [
            'trainer' => $trainer,
        ]);
    }

    // ======================= CẬP NHẬT HLV =======================
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=trainers&a=index');
            return;
        }

        $id          = (int)($_POST['id']          ?? 0);
        $name        = trim($_POST['hoten']        ?? '');
        $phone       = trim($_POST['sdt']          ?? '');
        $email       = trim($_POST['email']        ?? '');
        $skill       = trim($_POST['chuyenmon']    ?? '');
        $hourRate    = (float)($_POST['phi_gio']   ?? 0);
        $startDate   = $_POST['ngayvaolam']        ?? '';
        $statusInput = $_POST['trangthai']         ?? '1';

        $status = ($statusInput === '0') ? 0 : 1;

        if ($id <= 0) {
            $this->setFlash('error', 'Mã HLV không hợp lệ.');
            $this->redirect('?c=trainers&a=index');
            return;
        }

        // Validate cơ bản
        if ($name === '') {
            $this->setFlash('error', 'Vui lòng nhập họ tên HLV.');
            $this->redirect('?c=trainers&a=edit&id=' . $id);
            return;
        }

        if ($phone === '') {
            $this->setFlash('error', 'Vui lòng nhập số điện thoại.');
            $this->redirect('?c=trainers&a=edit&id=' . $id);
            return;
        }

        if ($email === '') {
            $this->setFlash('error', 'Vui lòng nhập email.');
            $this->redirect('?c=trainers&a=edit&id=' . $id);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Email không hợp lệ.');
            $this->redirect('?c=trainers&a=edit&id=' . $id);
            return;
        }

        if ($hourRate <= 0) {
            $this->setFlash('error', 'Phí/giờ phải lớn hơn 0.');
            $this->redirect('?c=trainers&a=edit&id=' . $id);
            return;
        }

        if ($startDate === '') {
            $this->setFlash('error', 'Vui lòng chọn ngày vào làm.');
            $this->redirect('?c=trainers&a=edit&id=' . $id);
            return;
        }

        try {
            $this->db->beginTransaction();

            // 1. Cập nhật nhanvien
            $sql1 = "
                UPDATE nhanvien
                SET HOTEN      = :hoten,
                    SDT        = :sdt,
                    EMAIL      = :email,
                    NGAYVAOLAM = :ngayvaolam,
                    TRANGTHAI  = :trangthai
                WHERE MANV = :id
            ";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute([
                ':hoten'      => $name,
                ':sdt'        => $phone,
                ':email'      => $email,
                ':ngayvaolam' => $startDate,
                ':trangthai'  => $status,
                ':id'         => $id,
            ]);

            // 2. Cập nhật hlv
            $sql2 = "
                UPDATE hlv
                SET MOTA    = :mota,
                    PHI_GIO = :phi_gio
                WHERE MAHLV = :id
            ";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([
                ':mota'    => $skill !== '' ? $skill : null,
                ':phi_gio' => $hourRate,
                ':id'      => $id,
            ]);

            $this->db->commit();

            $this->setFlash('success', 'Cập nhật huấn luyện viên thành công.');
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->setFlash('error', 'Lỗi khi cập nhật HLV: ' . $e->getMessage());
        }

        $this->redirect('?c=trainers&a=index');
    }

    // ======================= XOÁ HLV =======================
    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('?c=trainers&a=index');
            return;
        }

        try {
            $this->db->beginTransaction();

            // Xoá trước trong bảng hlv (vì các FK khác tham chiếu tới hlv)
            $stmt1 = $this->db->prepare("DELETE FROM hlv WHERE MAHLV = :id");
            $stmt1->execute([':id' => $id]);

            // Sau đó xoá trong bảng nhanvien (chỉ xóa nếu là VAITRO OTHER)
            $stmt2 = $this->db->prepare("DELETE FROM nhanvien WHERE MANV = :id AND VAITRO = 'OTHER'");
            $stmt2->execute([':id' => $id]);

            $this->db->commit();

            if ($stmt2->rowCount() > 0) {
                $this->setFlash('success', 'Xóa huấn luyện viên thành công.');
            } else {
                $this->setFlash('error', 'Không thể xóa HLV (có thể không phải HLV, hoặc đang được sử dụng ở buổi lớp/PT).');
            }
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->setFlash('error', 'Lỗi khi xóa HLV: ' . $e->getMessage());
        }
        $this->redirect('?c=trainers&a=index');
    }
}