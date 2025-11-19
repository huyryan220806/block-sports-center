<?php

require_once __DIR__ . '/../core/Database.php';

class TrainersController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // ========================================
    // DANH SÁCH HLV + TÌM KIẾM + PHÂN TRANG
    // ========================================
    public function index()
    {
        // Lấy tham số từ URL
        $search = trim($_GET['search'] ?? '');
        $page = isset($_GET['page']) && ctype_digit($_GET['page']) 
            ? (int)$_GET['page'] 
            : 1;
        
        $perPage = 10; // 10 dòng / trang
        
        // Đếm tổng số HLV
        $total = $this->countTrainers($search);
        $totalPages = max(1, (int)ceil($total / $perPage));
        
        if ($page > $totalPages) $page = $totalPages;
        if ($page < 1) $page = 1;
        
        $offset = ($page - 1) * $perPage;
        
        // Lấy danh sách HLV
        $trainers = $this->searchTrainers($search, $perPage, $offset);
        
        // Trả về view
        $this->view('trainers/index', [
            'trainers'   => $trainers,
            'search'     => $search,
            'page'       => $page,
            'totalPages' => $totalPages,
            'total'      => $total
        ]);
    }
    
    // ========================================
    // ĐẾM TỔNG SỐ HLV (CÓ SEARCH)
    // ========================================
    private function countTrainers($keyword = '')
    {
        $sql = "SELECT COUNT(*) 
                FROM nhanvien nv
                JOIN hlv h ON nv.MANV = h.MAHLV
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($keyword)) {
            $sql .= " AND (
                nv.HOTEN LIKE :keyword1 
                OR nv.SDT LIKE :keyword2 
                OR nv.EMAIL LIKE :keyword3 
                OR h.MOTA LIKE :keyword4
            )";
            $params[':keyword1'] = '%' . $keyword . '%';
            $params[':keyword2'] = '%' . $keyword . '%';
            $params[':keyword3'] = '%' . $keyword . '%';
            $params[':keyword4'] = '%' . $keyword . '%';
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
    
    // ========================================
    // TÌM KIẾM HLV (CÓ PHÂN TRANG)
    // ========================================
    private function searchTrainers($keyword = '', $limit = 10, $offset = 0)
    {
        $sql = "SELECT 
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
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($keyword)) {
            $sql .= " AND (
                nv.HOTEN LIKE :keyword1 
                OR nv.SDT LIKE :keyword2 
                OR nv.EMAIL LIKE :keyword3 
                OR h.MOTA LIKE :keyword4
            )";
            $params[':keyword1'] = '%' . $keyword . '%';
            $params[':keyword2'] = '%' . $keyword . '%';
            $params[':keyword3'] = '%' . $keyword . '%';
            $params[':keyword4'] = '%' . $keyword . '%';
        }
        
        $sql .= " ORDER BY nv.HOTEN ASC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        // Bind search params
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        // Bind pagination
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // ========================================
    // FORM TẠO HLV MỚI
    // ========================================
    public function create()
    {
        $this->view('trainers/create');
    }

    // ========================================
    // LƯU HLV MỚI
    // ========================================
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

        // Validate
        if ($name === '') {
            $_SESSION['error'] = 'Vui lòng nhập họ tên HLV.';
            $this->redirect('?c=trainers&a=create');
            return;
        }

        if ($phone === '') {
            $_SESSION['error'] = 'Vui lòng nhập số điện thoại.';
            $this->redirect('?c=trainers&a=create');
            return;
        }

        if ($email === '') {
            $_SESSION['error'] = 'Vui lòng nhập email.';
            $this->redirect('?c=trainers&a=create');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email không hợp lệ.';
            $this->redirect('?c=trainers&a=create');
            return;
        }

        if ($hourRate <= 0) {
            $_SESSION['error'] = 'Phí/giờ phải lớn hơn 0.';
            $this->redirect('?c=trainers&a=create');
            return;
        }

        if ($startDate === '') {
            $_SESSION['error'] = 'Vui lòng chọn ngày vào làm.';
            $this->redirect('?c=trainers&a=create');
            return;
        }

        try {
            $this->db->beginTransaction();

            // Thêm vào nhanvien
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

            // Thêm vào hlv
            $sql2 = "INSERT INTO hlv (MAHLV, MOTA, PHI_GIO)
                     VALUES (:mahlv, :mota, :phi_gio)";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([
                ':mahlv'   => $newId,
                ':mota'    => $skill !== '' ? $skill : null,
                ':phi_gio' => $hourRate,
            ]);

            $this->db->commit();

            $_SESSION['success'] = 'Thêm huấn luyện viên mới thành công.';
        } catch (PDOException $e) {
            $this->db->rollBack();
            $_SESSION['error'] = 'Lỗi khi thêm HLV: ' . $e->getMessage();
        }

        $this->redirect('?c=trainers&a=index');
    }

    // ========================================
    // FORM SỬA HLV
    // ========================================
    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'Không tìm thấy mã HLV.';
            $this->redirect('?c=trainers&a=index');
            return;
        }

        $sql = "SELECT 
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
                WHERE nv.MANV = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $trainer = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$trainer) {
            $_SESSION['error'] = 'Huấn luyện viên không tồn tại.';
            $this->redirect('?c=trainers&a=index');
            return;
        }

        $this->view('trainers/edit', [
            'trainer' => $trainer,
        ]);
    }

    // ========================================
    // CẬP NHẬT HLV
    // ========================================
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
            $_SESSION['error'] = 'Mã HLV không hợp lệ.';
            $this->redirect('?c=trainers&a=index');
            return;
        }

        // Validate
        if ($name === '') {
            $_SESSION['error'] = 'Vui lòng nhập họ tên HLV.';
            $this->redirect('?c=trainers&a=edit&id=' . $id);
            return;
        }

        if ($phone === '') {
            $_SESSION['error'] = 'Vui lòng nhập số điện thoại.';
            $this->redirect('?c=trainers&a=edit&id=' . $id);
            return;
        }

        if ($email === '') {
            $_SESSION['error'] = 'Vui lòng nhập email.';
            $this->redirect('?c=trainers&a=edit&id=' . $id);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email không hợp lệ.';
            $this->redirect('?c=trainers&a=edit&id=' . $id);
            return;
        }

        if ($hourRate <= 0) {
            $_SESSION['error'] = 'Phí/giờ phải lớn hơn 0.';
            $this->redirect('?c=trainers&a=edit&id=' . $id);
            return;
        }

        if ($startDate === '') {
            $_SESSION['error'] = 'Vui lòng chọn ngày vào làm.';
            $this->redirect('?c=trainers&a=edit&id=' . $id);
            return;
        }

        try {
            $this->db->beginTransaction();

            // Cập nhật nhanvien
            $sql1 = "UPDATE nhanvien
                    SET HOTEN      = :hoten,
                        SDT        = :sdt,
                        EMAIL      = :email,
                        NGAYVAOLAM = :ngayvaolam,
                        TRANGTHAI  = :trangthai
                    WHERE MANV = :id";
            
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute([
                ':hoten'      => $name,
                ':sdt'        => $phone,
                ':email'      => $email,
                ':ngayvaolam' => $startDate,
                ':trangthai'  => $status,
                ':id'         => $id,
            ]);

            // Cập nhật hlv
            $sql2 = "UPDATE hlv
                    SET MOTA    = :mota,
                        PHI_GIO = :phi_gio
                    WHERE MAHLV = :id";
            
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([
                ':mota'    => $skill !== '' ? $skill : null,
                ':phi_gio' => $hourRate,
                ':id'      => $id,
            ]);

            $this->db->commit();

            $_SESSION['success'] = 'Cập nhật huấn luyện viên thành công.';
        } catch (PDOException $e) {
            $this->db->rollBack();
            $_SESSION['error'] = 'Lỗi khi cập nhật HLV: ' . $e->getMessage();
        }

        $this->redirect('?c=trainers&a=index');
    }

    // ========================================
    // XÓA HLV
    // ========================================
    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('?c=trainers&a=index');
            return;
        }

        try {
            $this->db->beginTransaction();

            // Xóa hlv trước
            $stmt1 = $this->db->prepare("DELETE FROM hlv WHERE MAHLV = :id");
            $stmt1->execute([':id' => $id]);

            // Xóa nhanvien
            $stmt2 = $this->db->prepare("DELETE FROM nhanvien WHERE MANV = :id");
            $stmt2->execute([':id' => $id]);

            $this->db->commit();

            if ($stmt2->rowCount() > 0) {
                $_SESSION['success'] = 'Xóa huấn luyện viên thành công.';
            } else {
                $_SESSION['error'] = 'Không thể xóa HLV.';
            }
        } catch (PDOException $e) {
            $this->db->rollBack();
            $_SESSION['error'] = 'Lỗi khi xóa HLV: ' . $e->getMessage();
        }
        
        $this->redirect('?c=trainers&a=index');
    }
}
//