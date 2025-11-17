<?php
// app/controllers/BookingsController.php

require_once __DIR__ . '/../core/Database.php';

class BookingsController extends Controller
{
    /**
     * @var PDO
     */
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // ===================== DANH SÁCH + LỌC =====================
    public function index()
    {
        $selectedDate = $_GET['date']    ?? '';
        $selectedRoom = $_GET['room_id'] ?? '';

        // Nếu format ngày sai thì bỏ lọc theo ngày
        if ($selectedDate !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = '';
        }

        // Lấy danh sách phòng cho dropdown lọc
        $roomsStmt = $this->db->query("SELECT MAPHONG, TENPHONG FROM phong ORDER BY TENPHONG ASC");
        $rooms = $roomsStmt->fetchAll(PDO::FETCH_OBJ);

        $sql = "SELECT 
                    dp.MADP,
                    dp.MAPHONG,
                    dp.MAHV,
                    dp.BATDAU,
                    dp.KETTHUC,
                    dp.MUCTIEU,
                    dp.TRANGTHAI,
                    p.TENPHONG,
                    hv.HOVATEN
                FROM datphong dp
                JOIN phong p ON dp.MAPHONG = p.MAPHONG
                LEFT JOIN hoivien hv ON dp.MAHV = hv.MAHV
                WHERE 1 = 1";

        $params = [];

        if ($selectedDate !== '') {
            $sql .= " AND DATE(dp.BATDAU) = :date";
            $params[':date'] = $selectedDate;
        }

        if ($selectedRoom !== '') {
            $sql .= " AND dp.MAPHONG = :room_id";
            $params[':room_id'] = (int)$selectedRoom;
        }

        $sql .= " ORDER BY dp.BATDAU DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $bookings = $stmt->fetchAll(PDO::FETCH_OBJ);

        $this->view('bookings/index', [
            'bookings'     => $bookings,
            'rooms'        => $rooms,
            'selectedDate' => $selectedDate,
            'selectedRoom' => $selectedRoom,
        ]);
    }

    // ===================== FORM TẠO =====================
    public function create()
    {
        // CHỈ LẤY PHÒNG ĐANG HOẠT ĐỘNG
        $roomsStmt = $this->db->query("
            SELECT MAPHONG, TENPHONG 
            FROM phong 
            WHERE HOATDONG = 1
            ORDER BY TENPHONG ASC
        ");
        $rooms = $roomsStmt->fetchAll(PDO::FETCH_OBJ);

        $membersStmt = $this->db->query("SELECT MAHV, HOVATEN FROM hoivien ORDER BY HOVATEN ASC");
        $members = $membersStmt->fetchAll(PDO::FETCH_OBJ);

        $this->view('bookings/create', [
            'rooms'   => $rooms,
            'members' => $members,
        ]);
    }

    // ===================== LƯU BOOKING MỚI =====================
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=bookings&a=index');
            return;
        }

        $roomId   = (int)($_POST['room_id'] ?? 0);
        $memberId = ($_POST['member_id'] ?? '') !== '' ? (int)$_POST['member_id'] : null;
        $date     = $_POST['date'] ?? '';
        $start    = $_POST['start_time'] ?? '';
        $end      = $_POST['end_time'] ?? '';
        $purpose  = $_POST['purpose'] ?? 'TAP_TU_DO';

        if ($roomId <= 0 || $date === '' || $start === '' || $end === '') {
            $this->setFlash('error', 'Vui lòng nhập đầy đủ phòng, ngày và thời gian.');
            $this->redirect('?c=bookings&a=create');
            return;
        }

        $batdau  = $date . ' ' . $start . ':00';
        $ketthuc = $date . ' ' . $end   . ':00';
        $checkStmt = $this->db->prepare("SELECT HOATDONG FROM phong WHERE MAPHONG = :id");
        $checkStmt->execute([':id' => $roomId]);
        $roomRow = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$roomRow || (int)$roomRow['HOATDONG'] !== 1) {
            $this->setFlash('error', 'Phòng này đang ngưng hoạt động, không thể đặt.');
            $this->redirect('?c=bookings&a=create');
            return;
        }

        try {
            $sql = "INSERT INTO datphong (MAPHONG, MAHV, BATDAU, KETTHUC, MUCTIEU, TRANGTHAI)
                    VALUES (:room, :member, :batdau, :ketthuc, :muctieu, 'PENDING')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':room'    => $roomId,
                ':member'  => $memberId,
                ':batdau'  => $batdau,
                ':ketthuc' => $ketthuc,
                ':muctieu' => $purpose,
            ]);
            $this->setFlash('success', 'Tạo đặt phòng mới thành công (đang ở trạng thái PENDING).');
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi tạo đặt phòng: ' . $e->getMessage());
        }

        $this->redirect('?c=bookings&a=index');
    }
    

    // ===================== FORM SỬA =====================
    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('error', 'Không tìm thấy mã đặt phòng.');
            $this->redirect('?c=bookings&a=index');
            return;
        }
        $roomsStmt = $this->db->query("
        SELECT MAPHONG, TENPHONG 
        FROM phong 
        WHERE HOATDONG = 1
        ORDER BY TENPHONG ASC
        ");
        $rooms = $roomsStmt->fetchAll(PDO::FETCH_OBJ);

        $sql = "SELECT 
                    dp.*,
                    p.TENPHONG,
                    hv.HOVATEN
                FROM datphong dp
                JOIN phong p ON dp.MAPHONG = p.MAPHONG
                LEFT JOIN hoivien hv ON dp.MAHV = hv.MAHV
                WHERE dp.MADP = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $booking = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$booking) {
            $this->setFlash('error', 'Đặt phòng không tồn tại.');
            $this->redirect('?c=bookings&a=index');
            return;
        }

        $roomsStmt = $this->db->query("SELECT MAPHONG, TENPHONG FROM phong ORDER BY TENPHONG ASC");
        $rooms = $roomsStmt->fetchAll(PDO::FETCH_OBJ);

        $membersStmt = $this->db->query("SELECT MAHV, HOVATEN FROM hoivien ORDER BY HOVATEN ASC");
        $members = $membersStmt->fetchAll(PDO::FETCH_OBJ);

        $this->view('bookings/edit', [
            'booking' => $booking,
            'rooms'   => $rooms,
            'members' => $members,
        ]);
    }

    // ===================== CẬP NHẬT BOOKING =====================
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=bookings&a=index');
            return;
        }

        $id       = (int)($_POST['id'] ?? 0);
        $roomId   = (int)($_POST['room_id'] ?? 0);
        $memberId = ($_POST['member_id'] ?? '') !== '' ? (int)$_POST['member_id'] : null;
        $date     = $_POST['date'] ?? '';
        $start    = $_POST['start_time'] ?? '';
        $end      = $_POST['end_time'] ?? '';
        $purpose  = $_POST['purpose'] ?? 'TAP_TU_DO';
        $status   = $_POST['status'] ?? 'PENDING';

        if ($id <= 0 || $roomId <= 0 || $date === '' || $start === '' || $end === '') {
            $this->setFlash('error', 'Thiếu thông tin khi cập nhật đặt phòng.');
            $this->redirect('?c=bookings&a=edit&id=' . $id);
            return;
        }

        $batdau  = $date . ' ' . $start . ':00';
        $ketthuc = $date . ' ' . $end   . ':00';
        $checkStmt = $this->db->prepare("SELECT HOATDONG FROM phong WHERE MAPHONG = :id");
        $checkStmt->execute([':id' => $roomId]);
        $roomRow = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$roomRow || (int)$roomRow['HOATDONG'] !== 1) {
            $this->setFlash('error', 'Phòng này đang ngưng hoạt động, không thể gán cho lịch đặt.');
            $this->redirect('?c=bookings&a=edit&id=' . $id);
            return;
        }

        try {
            $sql = "UPDATE datphong
                    SET MAPHONG = :room,
                        MAHV    = :member,
                        BATDAU  = :batdau,
                        KETTHUC = :ketthuc,
                        MUCTIEU = :muctieu,
                        TRANGTHAI = :status
                    WHERE MADP = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':room'    => $roomId,
                ':member'  => $memberId,
                ':batdau'  => $batdau,
                ':ketthuc' => $ketthuc,
                ':muctieu' => $purpose,
                ':status'  => $status,
                ':id'      => $id,
            ]);
            $this->setFlash('success', 'Cập nhật đặt phòng thành công.');
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi cập nhật đặt phòng: ' . $e->getMessage());
        }

        $this->redirect('?c=bookings&a=index');
    }

    // ===================== CONFIRM / CANCEL / DELETE =====================
    public function confirm()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('?c=bookings&a=index');
            return;
        }

        $sql = "UPDATE datphong
                SET TRANGTHAI = 'CONFIRMED'
                WHERE MADP = :id AND TRANGTHAI = 'PENDING'";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute([':id' => $id]);
            if ($stmt->rowCount() > 0) {
                $this->setFlash('success', 'Đã xác nhận đặt phòng (CONFIRMED).');
            } else {
                $this->setFlash('error', 'Không thể xác nhận (trạng thái hiện tại không phải PENDING).');
            }
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi xác nhận: ' . $e->getMessage());
        }

        $this->redirect('?c=bookings&a=index');
    }

    public function cancel()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('?c=bookings&a=index');
            return;
        }

        $sql = "UPDATE datphong
                SET TRANGTHAI = 'CANCELLED'
                WHERE MADP = :id AND TRANGTHAI = 'PENDING'";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute([':id' => $id]);
            if ($stmt->rowCount() > 0) {
                $this->setFlash('success', 'Đã hủy đặt phòng (CANCELLED).');
            } else {
                $this->setFlash('error', 'Không thể hủy (trạng thái hiện tại không phải PENDING).');
            }
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi hủy đặt phòng: ' . $e->getMessage());
        }

        $this->redirect('?c=bookings&a=index');
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('?c=bookings&a=index');
            return;
        }

        $sql = "DELETE FROM datphong WHERE MADP = :id";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute([':id' => $id]);
            if ($stmt->rowCount() > 0) {
                $this->setFlash('success', 'Đã xóa đặt phòng.');
            } else {
                $this->setFlash('error', 'Không thể xóa đặt phòng.');
            }
        } catch (PDOException $e) {
            $this->setFlash('error', 'Lỗi khi xóa đặt phòng: ' . $e->getMessage());
        }

        $this->redirect('?c=bookings&a=index');
    }
}