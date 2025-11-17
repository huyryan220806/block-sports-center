<?php
// app/controllers/ClassesController.php

require_once __DIR__ . '/../core/Database.php';

class ClassesController extends Controller
{
    /**
     * @var PDO
     */
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // ======================= DANH SÁCH LỚP =======================
    public function index()
    {
        $stmt = $this->db->query("
            SELECT MALOP, TENLOP, THOILUONG, SISO_MACDINH, MOTA
            FROM lop
            ORDER BY MALOP DESC
        ");
        $classes = $stmt->fetchAll(PDO::FETCH_OBJ);

        $this->view('classes/index', [
            'classes' => $classes,
        ]);
    }

    // ======================= FORM TẠO LỚP =======================
    public function create()
    {
        $this->view('classes/create');
    }

    // ======================= LƯU LỚP MỚI ========================
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=classes&a=index');
            return;
        }

        $name        = trim($_POST['name']        ?? '');
        $duration    = (int)($_POST['duration']   ?? 0);
        $capacity    = (int)($_POST['capacity']   ?? 0);
        $description = trim($_POST['description'] ?? '');

        // Validate đơn giản
        if ($name === '') {
            $this->setFlash('error', 'Vui lòng nhập tên lớp.');
            $this->redirect('?c=classes&a=create');
            return;
        }

        if ($duration <= 0) {
            $this->setFlash('error', 'Thời lượng phải > 0 phút.');
            $this->redirect('?c=classes&a=create');
            return;
        }

        if ($capacity <= 0) {
            $this->setFlash('error', 'Sĩ số mặc định phải > 0.');
            $this->redirect('?c=classes&a=create');
            return;
        }

        try {
            $sql = "INSERT INTO lop (TENLOP, THOILUONG, SISO_MACDINH, MOTA)
                    VALUES (:tenlop, :thoiluong, :siso, :mota)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':tenlop'    => $name,
                ':thoiluong' => $duration,
                ':siso'      => $capacity,
                ':mota'      => $description !== '' ? $description : null,
            ]);

            $this->setFlash('success', 'Thêm lớp học mới thành công.');
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi thêm lớp: ' . $e->getMessage());
        }

        $this->redirect('?c=classes&a=index');
    }

    // ======================= FORM SỬA LỚP =======================
    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('error', 'Không tìm thấy mã lớp.');
            $this->redirect('?c=classes&a=index');
            return;
        }

        $stmt = $this->db->prepare("
            SELECT MALOP, TENLOP, THOILUONG, SISO_MACDINH, MOTA
            FROM lop
            WHERE MALOP = :id
        ");
        $stmt->execute([':id' => $id]);
        $class = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$class) {
            $this->setFlash('error', 'Lớp học không tồn tại.');
            $this->redirect('?c=classes&a=index');
            return;
        }

        $this->view('classes/edit', [
            'class' => $class,
        ]);
    }

    // ======================= CẬP NHẬT LỚP =======================
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=classes&a=index');
            return;
        }

        $id          = (int)($_POST['id']        ?? 0);
        $name        = trim($_POST['name']       ?? '');
        $duration    = (int)($_POST['duration']  ?? 0);
        $capacity    = (int)($_POST['capacity']  ?? 0);
        $description = trim($_POST['description'] ?? '');

        if ($id <= 0) {
            $this->setFlash('error', 'Mã lớp không hợp lệ.');
            $this->redirect('?c=classes&a=index');
            return;
        }

        if ($name === '') {
            $this->setFlash('error', 'Vui lòng nhập tên lớp.');
            $this->redirect('?c=classes&a=edit&id=' . $id);
            return;
        }

        if ($duration <= 0) {
            $this->setFlash('error', 'Thời lượng phải > 0 phút.');
            $this->redirect('?c=classes&a=edit&id=' . $id);
            return;
        }

        if ($capacity <= 0) {
            $this->setFlash('error', 'Sĩ số mặc định phải > 0.');
            $this->redirect('?c=classes&a=edit&id=' . $id);
            return;
        }

        try {
            $sql = "UPDATE lop
                    SET TENLOP = :tenlop,
                        THOILUONG = :thoiluong,
                        SISO_MACDINH = :siso,
                        MOTA = :mota
                    WHERE MALOP = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':tenlop'    => $name,
                ':thoiluong' => $duration,
                ':siso'      => $capacity,
                ':mota'      => $description !== '' ? $description : null,
                ':id'        => $id,
            ]);

            $this->setFlash('success', 'Cập nhật lớp học thành công.');
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi cập nhật lớp: ' . $e->getMessage());
        }

        $this->redirect('?c=classes&a=index');
    }

    // ======================= XOÁ LỚP =======================
    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('?c=classes&a=index');
            return;
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM lop WHERE MALOP = :id");
            $stmt->execute([':id' => $id]);

            if ($stmt->rowCount() > 0) {
                $this->setFlash('success', 'Xóa lớp học thành công.');
            } else {
                $this->setFlash('error', 'Không thể xóa lớp (có thể lớp không tồn tại hoặc đang được sử dụng trong buổi lớp / đăng ký).');
            }
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi xóa lớp: ' . $e->getMessage());
        }

        $this->redirect('?c=classes&a=index');
    }
}