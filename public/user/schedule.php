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

// Lấy thông tin user
$sessionName = $_SESSION['fullname'] ?? $_SESSION['username'] ?? 'Khách';
$avatar = mb_strtoupper(mb_substr(trim($sessionName), 0, 2, 'UTF-8'), 'UTF-8');

$user = [
    'name'      => $sessionName,
    'member_id' => 'HV' . str_pad($_SESSION['user_id'], 4, '0', STR_PAD_LEFT),
    'avatar'    => $avatar,
];

// Lấy lịch tập tuần này (từ Thứ 2 đến Chủ nhật)
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));

// Query lấy lịch đặt phòng của user
$bookingQuery = "
    SELECT 
        dp.MADP,
        dp.BATDAU,
        dp.KETTHUC,
        dp.TRANGTHAI,
        dp.MUCTIEU,
        p.TENPHONG,
        k.TENKHU
    FROM datphong dp
    JOIN phong p ON dp.MAPHONG = p.MAPHONG
    JOIN khu k ON p.MAKHU = k.MAKHU
    WHERE dp.MAHV = ?
      AND DATE(dp.BATDAU) BETWEEN ? AND ?
      AND dp.TRANGTHAI IN ('PENDING', 'CONFIRMED')
    ORDER BY dp.BATDAU
";

$bookingStmt = $db->prepare($bookingQuery);
$bookingStmt->execute([$_SESSION['user_id'], $startOfWeek, $endOfWeek]);
$bookings = $bookingStmt->fetchAll(PDO::FETCH_ASSOC);

// Query lấy lịch lớp học đã đăng ký
$classQuery = "
    SELECT 
        dk.MADK,
        b.BATDAU,
        b.KETTHUC,
        dk.TRANGTHAI,
        l.TENLOP,
        p.TENPHONG,
        k.TENKHU
    FROM dangky_lop dk
    JOIN buoilop b ON dk.MABUOI = b.MABUOI
    JOIN lop l ON b.MALOP = l.MALOP
    JOIN phong p ON b.MAPHONG = p.MAPHONG
    JOIN khu k ON p.MAKHU = k.MAKHU
    WHERE dk.MAHV = ?
      AND DATE(b.BATDAU) BETWEEN ? AND ?
      AND dk.TRANGTHAI IN ('BOOKED', 'ATTENDED')
    ORDER BY b.BATDAU
";

$classStmt = $db->prepare($classQuery);
$classStmt->execute([$_SESSION['user_id'], $startOfWeek, $endOfWeek]);
$classes = $classStmt->fetchAll(PDO::FETCH_ASSOC);

// Query lấy lịch PT Session
$ptQuery = "
    SELECT 
        pt.MAPT,
        pt.BATDAU,
        pt.KETTHUC,
        pt.TRANGTHAI,
        p.TENPHONG,
        k.TENKHU,
        nv.HOTEN as TEN_HLV
    FROM pt_session pt
    JOIN phong p ON pt.MAPHONG = p.MAPHONG
    JOIN khu k ON p.MAKHU = k.MAKHU
    JOIN nhanvien nv ON pt.MAHLV = nv.MANV
    WHERE pt.MAHV = ?
      AND DATE(pt.BATDAU) BETWEEN ? AND ?
      AND pt.TRANGTHAI IN ('SCHEDULED', 'DONE')
    ORDER BY pt.BATDAU
";

$ptStmt = $db->prepare($ptQuery);
$ptStmt->execute([$_SESSION['user_id'], $startOfWeek, $endOfWeek]);
$ptSessions = $ptStmt->fetchAll(PDO::FETCH_ASSOC);

// Gộp tất cả lịch vào một mảng theo ngày
$schedule = [
    'Monday' => [],
    'Tuesday' => [],
    'Wednesday' => [],
    'Thursday' => [],
    'Friday' => [],
    'Saturday' => [],
    'Sunday' => []
];

// Thêm đặt phòng
foreach ($bookings as $booking) {
    $dayOfWeek = date('l', strtotime($booking['BATDAU']));
    $schedule[$dayOfWeek][] = [
        'type' => 'BOOKING',
        'name' => $booking['TENKHU'],
        'time' => date('H:i', strtotime($booking['BATDAU'])) . ' - ' . date('H:i', strtotime($booking['KETTHUC'])),
        'location' => $booking['TENPHONG'],
        'status' => $booking['TRANGTHAI']
    ];
}

// Thêm lớp học
foreach ($classes as $class) {
    $dayOfWeek = date('l', strtotime($class['BATDAU']));
    $schedule[$dayOfWeek][] = [
        'type' => 'CLASS',
        'name' => $class['TENLOP'],
        'time' => date('H:i', strtotime($class['BATDAU'])) . ' - ' . date('H:i', strtotime($class['KETTHUC'])),
        'location' => $class['TENPHONG'],
        'status' => $class['TRANGTHAI']
    ];
}

// Thêm PT Session
foreach ($ptSessions as $pt) {
    $dayOfWeek = date('l', strtotime($pt['BATDAU']));
    $schedule[$dayOfWeek][] = [
        'type' => 'PT',
        'name' => 'PT với ' . $pt['TEN_HLV'],
        'time' => date('H:i', strtotime($pt['BATDAU'])) . ' - ' . date('H:i', strtotime($pt['KETTHUC'])),
        'location' => $pt['TENPHONG'],
        'status' => $pt['TRANGTHAI']
    ];
}

// Map trạng thái sang tiếng Việt
function getStatusText($status) {
    $statusMap = [
        'PENDING' => 'Chờ xác nhận',
        'CONFIRMED' => 'Đã xác nhận',
        'BOOKED' => 'Đã đăng ký',
        'ATTENDED' => 'Đã tham gia',
        'SCHEDULED' => 'Đã lên lịch',
        'DONE' => 'Hoàn thành'
    ];
    return $statusMap[$status] ?? $status;
}

// Map trạng thái sang màu
function getStatusColor($status) {
    $colorMap = [
        'PENDING' => '#fbbf24',
        'CONFIRMED' => '#10b981',
        'BOOKED' => '#3b82f6',
        'ATTENDED' => '#10b981',
        'SCHEDULED' => '#8b5cf6',
        'DONE' => '#6b7280'
    ];
    return $colorMap[$status] ?? '#6b7280';
}

$daysVN = [
    'Monday' => 'Thứ 2',
    'Tuesday' => 'Thứ 3',
    'Wednesday' => 'Thứ 4',
    'Thursday' => 'Thứ 5',
    'Friday' => 'Thứ 6',
    'Saturday' => 'Thứ 7',
    'Sunday' => 'Chủ nhật'
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLOCK SPORTS CENTER - Lịch tập</title>
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
            margin-bottom: 30px;
        }
        
        .quick-actions {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .section-title {
            color: white;
            font-size: 32px;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .action-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .action-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .action-card h3 {
            font-size: 20px;
            color: #667eea;
            margin-bottom: 15px;
        }

        .schedule-list {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }

        .schedule-list li {
            margin-bottom: 15px;
            font-size: 14px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            position: relative;
        }

        .schedule-list strong {
            font-size: 15px;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }

        .schedule-list span {
            display: block;
            color: #666;
            font-size: 13px;
            margin-top: 4px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            color: white;
            margin-top: 8px;
        }

        .type-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-booking {
            background: #dbeafe;
            color: #1e40af;
        }

        .type-class {
            background: #dcfce7;
            color: #15803d;
        }

        .type-pt {
            background: #fce7f3;
            color: #be185d;
        }

        .empty-day {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .hero h1 { font-size: 32px; }
            .nav { display: none; }
            .actions-grid { grid-template-columns: 1fr; }
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
                <a href="/block-sports-center/public/user/schedule.php" class="active">Lịch tập</a>
                <a href="/block-sports-center/public/user/booking.php">Đặt phòng</a>
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
        <h1>Lịch tập của bạn</h1>
        <p>Xem nhanh các buổi đã đăng ký trong tuần (<?= date('d/m/Y', strtotime($startOfWeek)) ?> - <?= date('d/m/Y', strtotime($endOfWeek)) ?>)</p>
    </section>

    <section class="quick-actions">
        <h2 class="section-title">Tuần này</h2>

        <div class="actions-grid">
            <?php foreach ($schedule as $dayEn => $activities): ?>
                <div class="action-card">
                    <h3><?= $daysVN[$dayEn] ?></h3>
                    <small style="color: #999;">
                        <?= date('d/m/Y', strtotime($startOfWeek . ' +' . array_search($dayEn, array_keys($schedule)) . ' days')) ?>
                    </small>
                    
                    <?php if (empty($activities)): ?>
                        <div class="empty-day">
                            <i class="fas fa-calendar-day" style="font-size: 32px; color: #ddd; margin-bottom: 10px;"></i>
                            <p>Chưa có lịch</p>
                        </div>
                    <?php else: ?>
                        <ul class="schedule-list">
                            <?php foreach ($activities as $activity): ?>
                                <li>
                                    <span class="type-badge type-<?= strtolower($activity['type']) ?>">
                                        <?= $activity['type'] === 'BOOKING' ? 'Đặt phòng' : ($activity['type'] === 'CLASS' ? 'Lớp học' : 'PT') ?>
                                    </span>
                                    
                                    <strong><?= htmlspecialchars($activity['name']) ?></strong>
                                    
                                    <span>
                                        <i class="fas fa-clock"></i> <?= htmlspecialchars($activity['time']) ?>
                                    </span>
                                    
                                    <span>
                                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($activity['location']) ?>
                                    </span>
                                    
                                    <span class="status-badge" style="background: <?= getStatusColor($activity['status']) ?>">
                                        <?= getStatusText($activity['status']) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>