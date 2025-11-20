<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /block-sports-center/public/index.php?page=login');
    exit;
}

require_once __DIR__ . '/../../app/core/Database.php';

$db = Database::getInstance()->getConnection();

// ✅ KIỂM TRA XEM USER ĐÃ LÀ HỘI VIÊN CHƯA
$checkMemberQuery = "SELECT MAHV FROM hoivien WHERE MAHV = ? AND TRANGTHAI = 'ACTIVE'";
$checkMemberStmt = $db->prepare($checkMemberQuery);
$checkMemberStmt->execute([$_SESSION['user_id']]);
$isMember = $checkMemberStmt->fetch(PDO::FETCH_ASSOC);

// Lấy thông tin user
$sessionName = $_SESSION['fullname'] ?? $_SESSION['username'] ?? 'Khách';
$avatar = mb_strtoupper(mb_substr(trim($sessionName), 0, 2, 'UTF-8'), 'UTF-8');

$user = [
    'name'      => $sessionName,
    'member_id' => 'HV' . str_pad($_SESSION['user_id'], 4, '0', STR_PAD_LEFT),
    'avatar'    => $avatar,
];

// Lấy danh sách khu (môn thể thao)
$areasQuery = "SELECT MAKHU, TENKHU, LOAIKHU FROM khu ORDER BY TENKHU";
$areasStmt = $db->query($areasQuery);
$areas = $areasStmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy khu được chọn (mặc định là khu đầu tiên)
$selectedArea = $_GET['area'] ?? ($areas[0]['MAKHU'] ?? null);

// Lấy thông tin chi tiết khu đã chọn
$areaInfo = null;
if ($selectedArea) {
    $areaInfoQuery = "SELECT * FROM khu WHERE MAKHU = ?";
    $areaInfoStmt = $db->prepare($areaInfoQuery);
    $areaInfoStmt->execute([$selectedArea]);
    $areaInfo = $areaInfoStmt->fetch(PDO::FETCH_ASSOC);
}

// Lấy danh sách phòng thuộc khu đã chọn
$rooms = [];
if ($selectedArea) {
    $roomsQuery = "SELECT MAPHONG, TENPHONG, SUCCHUA FROM phong WHERE MAKHU = ? AND HOATDONG = 1";
    $roomsStmt = $db->prepare($roomsQuery);
    $roomsStmt->execute([$selectedArea]);
    $rooms = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy lịch đặt phòng của tuần này
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));

$bookings = [];
if ($selectedArea && count($rooms) > 0) {
    $roomIds = array_column($rooms, 'MAPHONG');
    $placeholders = implode(',', array_fill(0, count($roomIds), '?'));
    
    $bookingsQuery = "
        SELECT 
            dp.MADP,
            dp.MAPHONG,
            dp.BATDAU,
            dp.KETTHUC,
            dp.TRANGTHAI,
            p.TENPHONG,
            p.SUCCHUA,
            COUNT(dp2.MADP) as CURRENT_BOOKINGS
        FROM datphong dp
        JOIN phong p ON dp.MAPHONG = p.MAPHONG
        LEFT JOIN datphong dp2 ON dp2.MAPHONG = dp.MAPHONG 
            AND dp2.BATDAU = dp.BATDAU 
            AND dp2.KETTHUC = dp.KETTHUC
            AND dp2.TRANGTHAI IN ('PENDING', 'CONFIRMED')
        WHERE dp.MAPHONG IN ($placeholders)
          AND DATE(dp.BATDAU) BETWEEN ? AND ?
          AND dp.TRANGTHAI IN ('PENDING', 'CONFIRMED')
        GROUP BY dp.MADP, dp.MAPHONG, dp.BATDAU, dp.KETTHUC, dp.TRANGTHAI, p.TENPHONG, p.SUCCHUA
        ORDER BY dp.BATDAU
    ";
    
    $bookingsStmt = $db->prepare($bookingsQuery);
    $params = array_merge($roomIds, [$startOfWeek, $endOfWeek]);
    $bookingsStmt->execute($params);
    $bookings = $bookingsStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Tạo mảng lịch theo ngày và giờ
$daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$daysVN = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];

// Tạo khung giờ mặc định từ 6:00 đến 21:00 (mỗi slot 1.5 giờ)
$timeSlots = [
    '06:00 - 07:30',
    '08:00 - 09:30',
    '10:00 - 11:30',
    '12:00 - 13:30',
    '14:00 - 15:30',
    '16:00 - 17:30',
    '18:00 - 19:30',
    '19:30 - 21:00'
];

// Xử lý đặt phòng
$bookingMessage = '';
$bookingType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking'])) {
    // ✅ KIỂM TRA LẠI KHI SUBMIT
    if (!$isMember) {
        $bookingMessage = 'BẠN CẦN CẬP NHẬT LẠI THÔNG TIN ĐỂ TRỞ THÀNH HỘI VIÊN MỚI ĐƯỢC ĐĂNG KÝ LỊCH TẬP!';
        $bookingType = 'error';
    } else {
        $maphong = (int)$_POST['maphong'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $muctieu = $_POST['muctieu'];
        $mahv = (int)$_SESSION['user_id'];
        
        // Parse thời gian
        $timeParts = explode(' - ', $time);
        $startTime = trim($timeParts[0]);
        $endTime = trim($timeParts[1]);
        $batdau = $date . ' ' . $startTime . ':00';
        $ketthuc = $date . ' ' . $endTime . ':00';
        
        try {
            $db->beginTransaction();
            
            // ✅ LẤY THÔNG TIN PHÒNG VÀ SỐ NGƯỜI ĐÃ ĐẶT
            $roomInfoQuery = "SELECT SUCCHUA FROM phong WHERE MAPHONG = ?";
            $roomInfoStmt = $db->prepare($roomInfoQuery);
            $roomInfoStmt->execute([$maphong]);
            $roomInfo = $roomInfoStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$roomInfo) {
                throw new Exception('Không tìm thấy thông tin phòng!');
            }
            
            $maxCapacity = (int)$roomInfo['SUCCHUA'];
            
            // ✅ ĐẾM SỐ NGƯỜI ĐÃ ĐẶT TRONG KHUNG GIỜ NÀY
            $checkQuery = "
                SELECT COUNT(*) as total 
                FROM datphong 
                WHERE MAPHONG = ? 
                  AND BATDAU = ?
                  AND KETTHUC = ?
                  AND TRANGTHAI IN ('PENDING', 'CONFIRMED')
            ";
            $checkStmt = $db->prepare($checkQuery);
            $checkStmt->execute([$maphong, $batdau, $ketthuc]);
            $checkResult = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            $currentBookings = (int)$checkResult['total'];
            
            // ✅ KIỂM TRA XEM CÒN CHỖ KHÔNG
            if ($currentBookings >= $maxCapacity) {
                $bookingMessage = "Phòng đã đầy! (Đã có {$currentBookings}/{$maxCapacity} người đặt)";
                $bookingType = 'error';
                $db->rollBack();
            } else {
                // ✅ KIỂM TRA USER ĐÃ ĐẶT KHUNG GIỜ NÀY CHƯA
                $checkDuplicateQuery = "
                    SELECT COUNT(*) as total 
                    FROM datphong 
                    WHERE MAPHONG = ? 
                      AND MAHV = ?
                      AND BATDAU = ?
                      AND KETTHUC = ?
                      AND TRANGTHAI IN ('PENDING', 'CONFIRMED')
                ";
                $checkDuplicateStmt = $db->prepare($checkDuplicateQuery);
                $checkDuplicateStmt->execute([$maphong, $mahv, $batdau, $ketthuc]);
                $duplicateResult = $checkDuplicateStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($duplicateResult['total'] > 0) {
                    $bookingMessage = 'Bạn đã đặt khung giờ này rồi!';
                    $bookingType = 'error';
                    $db->rollBack();
                } else {
                    // Thêm đặt phòng
                    $insertQuery = "
                        INSERT INTO datphong (MAPHONG, MAHV, BATDAU, KETTHUC, MUCTIEU, TRANGTHAI)
                        VALUES (?, ?, ?, ?, ?, 'PENDING')
                    ";
                    $insertStmt = $db->prepare($insertQuery);
                    $insertStmt->execute([
                        $maphong,
                        $mahv,
                        $batdau,
                        $ketthuc,
                        $muctieu
                    ]);
                    
                    $remainingSlots = $maxCapacity - ($currentBookings + 1);
                    $bookingMessage = "Đặt phòng thành công! Còn {$remainingSlots}/{$maxCapacity} chỗ. Vui lòng chờ xác nhận.";
                    $bookingType = 'success';
                    
                    $db->commit();
                }
            }
            
            // ✅ RELOAD BOOKINGS
            $bookings = [];
            if ($selectedArea && count($rooms) > 0) {
                $reloadRoomIds = array_column($rooms, 'MAPHONG');
                $reloadPlaceholders = implode(',', array_fill(0, count($reloadRoomIds), '?'));
                
                $reloadQuery = "
                    SELECT 
                        dp.MADP,
                        dp.MAPHONG,
                        dp.BATDAU,
                        dp.KETTHUC,
                        dp.TRANGTHAI,
                        p.TENPHONG,
                        p.SUCCHUA,
                        COUNT(dp2.MADP) as CURRENT_BOOKINGS
                    FROM datphong dp
                    JOIN phong p ON dp.MAPHONG = p.MAPHONG
                    LEFT JOIN datphong dp2 ON dp2.MAPHONG = dp.MAPHONG 
                        AND dp2.BATDAU = dp.BATDAU 
                        AND dp2.KETTHUC = dp.KETTHUC
                        AND dp2.TRANGTHAI IN ('PENDING', 'CONFIRMED')
                    WHERE dp.MAPHONG IN ($reloadPlaceholders)
                      AND DATE(dp.BATDAU) BETWEEN ? AND ?
                      AND dp.TRANGTHAI IN ('PENDING', 'CONFIRMED')
                    GROUP BY dp.MADP, dp.MAPHONG, dp.BATDAU, dp.KETTHUC, dp.TRANGTHAI, p.TENPHONG, p.SUCCHUA
                    ORDER BY dp.BATDAU
                ";
                
                $reloadStmt = $db->prepare($reloadQuery);
                $reloadParamsArray = array_merge($reloadRoomIds, [$startOfWeek, $endOfWeek]);
                $reloadStmt->execute($reloadParamsArray);
                $bookings = $reloadStmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $bookingMessage = 'Lỗi: ' . $e->getMessage();
            $bookingType = 'error';
        }
    }
}

// ✅ TẠO LỊCH BOOKING THEO NGÀY VỚI THÔNG TIN SỨC CHỨA
$bookedSlots = [];
foreach ($bookings as $booking) {
    $dayOfWeek = date('l', strtotime($booking['BATDAU']));
    $timeSlot = date('H:i', strtotime($booking['BATDAU'])) . ' - ' . date('H:i', strtotime($booking['KETTHUC']));
    
    $key = $dayOfWeek . '|' . $booking['MAPHONG'] . '|' . $timeSlot;
    
    // ✅ LƯU THÔNG TIN VỀ SỨC CHỨA
    if (!isset($bookedSlots[$key])) {
        $bookedSlots[$key] = [
            'current' => 0,
            'max' => (int)$booking['SUCCHUA']
        ];
    }
    
    // Đếm số lượng đặt
    $countQuery = "
        SELECT COUNT(*) as total 
        FROM datphong 
        WHERE MAPHONG = ? 
          AND BATDAU = ?
          AND KETTHUC = ?
          AND TRANGTHAI IN ('PENDING', 'CONFIRMED')
    ";
    $countStmt = $db->prepare($countQuery);
    $countStmt->execute([
        $booking['MAPHONG'], 
        $booking['BATDAU'], 
        $booking['KETTHUC']
    ]);
    $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
    
    $bookedSlots[$key]['current'] = (int)$countResult['total'];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLOCK SPORTS CENTER - Đặt phòng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav a:hover, .nav a.active {
            color: #667eea;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .logout-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid rgba(231, 76, 60, 0.3);
        }

        .logout-link:hover {
            background: #e74c3c;
            color: #fff;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
        }

        .hero {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            color: white;
            text-align: center;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .hero p {
            font-size: 20px;
            opacity: 0.9;
        }

        .booking-section {
            max-width: 1200px;
            margin: 0 auto 40px;
            padding: 0 20px;
        }

        .area-selector {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .area-selector h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .area-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }

        .area-btn {
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .area-btn:hover {
            border-color: #667eea;
            background: #f5f7ff;
            transform: translateY(-2px);
        }

        .area-btn.active {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .schedule-container {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .schedule-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .schedule-header h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .week-info {
            color: #666;
            font-size: 14px;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
        }

        .day-column {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
        }

        .day-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
        }

        .time-slots {
            padding: 10px;
        }

        .time-slot {
            margin-bottom: 10px;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            background: #f0fff4;
            border-color: #4ade80;
        }

        .time-slot:hover:not(.full):not(.disabled) {
            border-color: #667eea;
            transform: scale(1.02);
        }

        /* ✅ SLOT ĐÃ ĐẦY */
        .time-slot.full {
            background: #fef2f2;
            border-color: #f87171;
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* ✅ SLOT CÒN CHỖ NHƯNG GẦN ĐẦY */
        .time-slot.almost-full {
            background: #fefce8;
            border-color: #fbbf24;
        }

        .time-slot.disabled {
            background: #f3f4f6;
            border-color: #d1d5db;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .time-slot.selected {
            background: #dbeafe;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .time-text {
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .slot-info {
            font-size: 11px;
            color: #666;
        }

        /* ✅ HIỂN THỊ SỐ CHỖ */
        .capacity-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 700;
            margin-top: 5px;
        }

        .capacity-badge.available {
            background: #dcfce7;
            color: #15803d;
        }

        .capacity-badge.almost-full {
            background: #fef3c7;
            color: #92400e;
        }

        .capacity-badge.full {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .empty-schedule {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-schedule i {
            font-size: 64px;
            margin-bottom: 20px;
            color: #ddd;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 16px;
            max-width: 500px;
            width: 90%;
            animation: slideUp 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .modal-header h3 {
            font-size: 22px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #d0d0d0;
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 32px; }
            .nav { display: none; }
            .schedule-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <i class="fas fa-dumbbell"></i>
                BLOCK SPORTS CENTER
            </div>

            <nav class="nav">
                <a href="/block-sports-center/public/index.php?page=user">Trang chủ</a>
                <a href="/block-sports-center/public/user/classes.php">Lớp học</a>
                <a href="/block-sports-center/public/user/schedule.php">Lịch tập</a>
                <a href="/block-sports-center/public/user/booking.php" class="active">Đặt phòng</a>
            </nav>

            <div class="user-menu">
                <span>Xin chào, <?php echo htmlspecialchars($user['name']); ?></span>
                <div class="user-avatar"><?php echo htmlspecialchars($user['avatar']); ?></div>
                <a href="/block-sports-center/public/index.php?page=logout" class="logout-link">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </header>

    <section class="hero">
        <h1>Đặt lịch tập luyện</h1>
        <p>Chọn khu và khung giờ phù hợp với bạn</p>
    </section>

    <section class="booking-section">
        
        <?php if (!$isMember): ?>
            <!-- ✅ THÔNG BÁO CHƯA LÀ HỘI VIÊN -->
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>BẠN CẦN CẬP NHẬT LẠI THÔNG TIN ĐỂ TRỞ THÀNH HỘI VIÊN MỚI ĐƯỢC ĐĂNG KÝ LỊCH TẬP!</strong>
            </div>
        <?php endif; ?>

        <?php if ($bookingMessage): ?>
            <div class="alert alert-<?= $bookingType ?>">
                <i class="fas fa-<?= $bookingType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                <?= htmlspecialchars($bookingMessage) ?>
            </div>
        <?php endif; ?>

        <!-- Chọn khu -->
        <div class="area-selector">
            <h2><i class="fas fa-list"></i> Chọn khu vực</h2>
            <div class="area-grid">
                <?php foreach ($areas as $area): ?>
                    <a href="?area=<?= $area['MAKHU'] ?>" 
                       class="area-btn <?= $selectedArea == $area['MAKHU'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($area['TENKHU']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Lịch trong tuần -->
        <?php if ($areaInfo): ?>
            <div class="schedule-container">
                <div class="schedule-header">
                    <h2>
                        <i class="fas fa-calendar-week"></i> 
                        Lịch <?= htmlspecialchars($areaInfo['TENKHU']) ?> - Tuần này
                    </h2>
                    <div class="week-info">
                        Từ <?= date('d/m/Y', strtotime($startOfWeek)) ?> 
                        đến <?= date('d/m/Y', strtotime($endOfWeek)) ?>
                    </div>
                </div>

                <?php if (count($rooms) > 0): ?>
                    <div class="schedule-grid">
                        <?php foreach ($daysOfWeek as $index => $day): ?>
                            <div class="day-column">
                                <div class="day-header">
                                    <?= $daysVN[$index] ?><br>
                                    <small><?= date('d/m', strtotime($startOfWeek . ' +' . $index . ' days')) ?></small>
                                </div>
                                <div class="time-slots">
                                    <?php foreach ($timeSlots as $timeSlot): ?>
                                        <?php
                                        $slotDate = date('Y-m-d', strtotime($startOfWeek . ' +' . $index . ' days'));
                                        $firstRoom = $rooms[0];
                                        $slotKey = $day . '|' . $firstRoom['MAPHONG'] . '|' . $timeSlot;
                                        
                                        $currentBookings = 0;
                                        $maxCapacity = (int)$firstRoom['SUCCHUA'];
                                        
                                        if (isset($bookedSlots[$slotKey])) {
                                            $currentBookings = $bookedSlots[$slotKey]['current'];
                                            $maxCapacity = $bookedSlots[$slotKey]['max'];
                                        }
                                        
                                        $isFull = $currentBookings >= $maxCapacity;
                                        $isAlmostFull = $currentBookings >= ($maxCapacity * 0.8);
                                        $isDisabled = !$isMember;
                                        
                                        // Xác định class CSS
                                        $cssClass = '';
                                        if ($isDisabled) {
                                            $cssClass = 'disabled';
                                        } elseif ($isFull) {
                                            $cssClass = 'full';
                                        } elseif ($isAlmostFull) {
                                            $cssClass = 'almost-full';
                                        }
                                        
                                        // Xác định text hiển thị
                                        $slotText = '';
                                        if ($isDisabled) {
                                            $slotText = 'Không khả dụng';
                                        } elseif ($isFull) {
                                            $slotText = 'Đã đầy';
                                        } else {
                                            $slotText = "Còn " . ($maxCapacity - $currentBookings) . "/" . $maxCapacity;
                                        }
                                        
                                        // Xác định badge class
                                        $badgeClass = '';
                                        if ($isFull) {
                                            $badgeClass = 'full';
                                        } elseif ($isAlmostFull) {
                                            $badgeClass = 'almost-full';
                                        } else {
                                            $badgeClass = 'available';
                                        }
                                        
                                        $onclick = ($isFull || $isDisabled) ? '' : 'openBookingModal(' . $firstRoom['MAPHONG'] . ', \'' . $slotDate . '\', \'' . $timeSlot . '\', \'' . htmlspecialchars($firstRoom['TENPHONG'], ENT_QUOTES) . '\', ' . $currentBookings . ', ' . $maxCapacity . ')';
                                        ?>
                                        <div class="time-slot <?= $cssClass ?>"
                                             onclick="<?= $onclick ?>">
                                            <div class="time-text"><?= $timeSlot ?></div>
                                            <div class="slot-info"><?= $slotText ?></div>
                                            <?php if (!$isDisabled): ?>
                                                <span class="capacity-badge <?= $badgeClass ?>">
                                                    <i class="fas fa-users"></i> <?= $currentBookings ?>/<?= $maxCapacity ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-schedule">
                        <i class="fas fa-calendar-times"></i>
                        <p>Chưa có phòng nào cho khu <strong><?= htmlspecialchars($areaInfo['TENKHU']) ?></strong></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </section>

    <!-- Modal Xác Nhận Đặt Phòng -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-calendar-check"></i> Xác nhận đặt phòng</h3>
            </div>
            
            <form method="POST" id="bookingForm">
                <input type="hidden" name="maphong" id="modal_maphong">
                <input type="hidden" name="date" id="modal_date">
                <input type="hidden" name="time" id="modal_time">
                
                <div class="form-group">
                    <label>Phòng:</label>
                    <input type="text" class="form-control" id="modal_room_name" readonly>
                </div>
                
                <div class="form-group">
                    <label>Ngày:</label>
                    <input type="text" class="form-control" id="modal_date_display" readonly>
                </div>
                
                <div class="form-group">
                    <label>Giờ:</label>
                    <input type="text" class="form-control" id="modal_time_display" readonly>
                </div>
                
                <div class="form-group">
                    <label>Sức chứa:</label>
                    <input type="text" class="form-control" id="modal_capacity" readonly>
                </div>
                
                <div class="form-group">
                    <label>Mục tiêu sử dụng: <span style="color: red;">*</span></label>
                    <select name="muctieu" class="form-control" required>
                        <option value="">-- Chọn mục tiêu --</option>
                        <option value="TAP_TU_DO">Tập tự do</option>
                        <option value="CLB">Hoạt động CLB</option>
                        <option value="GIU_CHO_SU_KIEN">Giữ chỗ sự kiện</option>
                        <option value="KHAC">Khác</option>
                    </select>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeBookingModal()">Hủy</button>
                    <button type="submit" name="confirm_booking" class="btn btn-primary">Xác nhận đặt</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openBookingModal(maphong, date, time, roomName, currentBookings, maxCapacity) {
            document.getElementById('modal_maphong').value = maphong;
            document.getElementById('modal_date').value = date;
            document.getElementById('modal_time').value = time;
            document.getElementById('modal_room_name').value = roomName;
            document.getElementById('modal_date_display').value = formatDate(date);
            document.getElementById('modal_time_display').value = time;
            document.getElementById('modal_capacity').value = `Đã đặt ${currentBookings}/${maxCapacity} chỗ`;
            
            document.getElementById('bookingModal').classList.add('show');
        }
        
        function closeBookingModal() {
            document.getElementById('bookingModal').classList.remove('show');
        }
        
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }
        
        // Đóng modal khi click bên ngoài
        window.onclick = function(event) {
            const modal = document.getElementById('bookingModal');
            if (event.target == modal) {
                closeBookingModal();
            }
        }
    </script>

</body>
</html>