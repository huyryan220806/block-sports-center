<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($user) || !is_array($user)) {
    $sessionName = $_SESSION['fullname'] ?? $_SESSION['username'] ?? 'User';
    $avatar = mb_strtoupper(mb_substr(trim($sessionName), 0, 2, 'UTF-8'), 'UTF-8');

    $user = [
        'name'      => $sessionName,
        'member_id' => 'MB001',
        'avatar'    => $avatar,
    ];
}

if (!isset($totalCalo)) {
    $totalCalo = 0;
}
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
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .schedule-list strong {
            font-size: 15px;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .schedule-list span {
            display: block;
            color: #666;
            font-size: 13px;
            margin-top: 4px;
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
        <p>Xem nhanh các buổi đã đăng ký trong tuần</p>
    </section>

    <section class="quick-actions">
        <h2 class="section-title">Tuần này</h2>

        <div class="actions-grid">
            <div class="action-card">
                <h3>Thứ 2</h3>
                <ul class="schedule-list">
                    <li>
                        <strong>Gym Strength</strong>
                        <span><i class="fas fa-clock"></i> 18:00 - 19:00</span>
                        <span><i class="fas fa-map-marker-alt"></i> Phòng Gym 2</span>
                    </li>
                    <li>
                        <strong>Cầu lông</strong>
                        <span><i class="fas fa-clock"></i> 19:30 - 21:00</span>
                        <span><i class="fas fa-map-marker-alt"></i> Sân Cầu lông 3</span>
                    </li>
                </ul>
            </div>

            <div class="action-card">
                <h3>Thứ 3</h3>
                <ul class="schedule-list">
                    <li>
                        <strong>Bơi lội</strong>
                        <span><i class="fas fa-clock"></i> 06:00 - 07:00</span>
                        <span><i class="fas fa-map-marker-alt"></i> Hồ bơi ngoài trời</span>
                    </li>
                </ul>
            </div>

            <div class="action-card">
                <h3>Thứ 4</h3>
                <ul class="schedule-list">
                    <li>
                        <strong>Bóng rổ</strong>
                        <span><i class="fas fa-clock"></i> 16:30 - 18:00</span>
                        <span><i class="fas fa-map-marker-alt"></i> Sân Bóng rổ A</span>
                    </li>
                </ul>
            </div>

            <div class="action-card">
                <h3>Thứ 5</h3>
                <ul class="schedule-list">
                    <li>
                        <strong>Futsal</strong>
                        <span><i class="fas fa-clock"></i> 18:00 - 19:30</span>
                        <span><i class="fas fa-map-marker-alt"></i> Sân Futsal B</span>
                    </li>
                </ul>
            </div>

            <div class="action-card">
                <h3>Thứ 6</h3>
                <ul class="schedule-list">
                    <li>
                        <strong>Pickleball</strong>
                        <span><i class="fas fa-clock"></i> 17:00 - 18:30</span>
                        <span><i class="fas fa-map-marker-alt"></i> Sân Pickleball 1</span>
                    </li>
                </ul>
            </div>

            <div class="action-card">
                <h3>Thứ 7 & Chủ nhật</h3>
                <ul class="schedule-list">
                    <li>
                        <strong>Bóng đá 11 người</strong>
                        <span><i class="fas fa-clock"></i> 19:00 - 21:00</span>
                        <span><i class="fas fa-map-marker-alt"></i> Sân 11 người</span>
                    </li>
                    <li>
                        <strong>Swimming Family</strong>
                        <span><i class="fas fa-clock"></i> 08:00 - 09:30</span>
                        <span><i class="fas fa-map-marker-alt"></i> Hồ bơi trong nhà</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</body>
</html>