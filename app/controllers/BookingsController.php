<?php
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../../config/database.php';

class BookingsController {
    private $db;
    private $booking;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->booking = new Booking($this->db);
    }

    public function index() {
        $bookings = $this->booking->getAll();
        include __DIR__ . '/../views/bookings/index.php';
    }

    public function create() {
        $members = $this->booking->getMembers();
        $rooms = $this->booking->getRooms();
        include __DIR__ . '/../views/bookings/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $batdau = $_POST['ngaydat'] . ' ' . $_POST['giobatdau'] . ':00';
            $ketthuc = $_POST['ngaydat'] . ' ' . $_POST['gioketthuc'] . ':00';
            
            $data = [
                'mahv' => $_POST['mahv'],
                'maphong' => $_POST['maphong'],
                'batdau' => $batdau,
                'ketthuc' => $ketthuc,
                'muctieu' => $_POST['muctieu'],
                'trangthai' => $_POST['trangthai']
            ];

            if ($this->booking->create($data)) {
                $_SESSION['success'] = 'Dat phong thanh cong!';
                header('Location: ?c=bookings&a=index');
                exit();
            } else {
                $error = 'Dat phong that bai!';
                $members = $this->booking->getMembers();
                $rooms = $this->booking->getRooms();
                include __DIR__ . '/../views/bookings/create.php';
            }
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        $booking = $this->booking->find($id);
        
        if (!$booking) {
            $_SESSION['error'] = 'Khong tim thay dat phong!';
            header('Location: ?c=bookings&a=index');
            exit();
        }

        $members = $this->booking->getMembers();
        $rooms = $this->booking->getRooms();
        include __DIR__ . '/../views/bookings/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            
            $batdau = $_POST['ngaydat'] . ' ' . $_POST['giobatdau'] . ':00';
            $ketthuc = $_POST['ngaydat'] . ' ' . $_POST['gioketthuc'] . ':00';
            
            $data = [
                'mahv' => $_POST['mahv'],
                'maphong' => $_POST['maphong'],
                'batdau' => $batdau,
                'ketthuc' => $ketthuc,
                'muctieu' => $_POST['muctieu'],
                'trangthai' => $_POST['trangthai']
            ];

            if ($this->booking->update($id, $data)) {
                $_SESSION['success'] = 'Cap nhat thanh cong!';
                header('Location: ?c=bookings&a=index');
                exit();
            } else {
                $error = 'Cap nhat that bai!';
                $booking = $this->booking->find($id);
                $members = $this->booking->getMembers();
                $rooms = $this->booking->getRooms();
                include __DIR__ . '/../views/bookings/edit.php';
            }
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->booking->delete($id)) {
            $_SESSION['success'] = 'Xoa thanh cong!';
        } else {
            $_SESSION['error'] = 'Xoa that bai!';
        }
        
        header('Location: ?c=bookings&a=index');
        exit();
    }
}