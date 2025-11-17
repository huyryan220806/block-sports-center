<?php
// Giả sử user đã đăng nhập
$user = [
    'name' => 'Nguyễn Văn An',
    'member_id' => 'MB001',
    'avatar' => 'NA'
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLOCK SPORTS CENTER - Trang chủ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* HEADER */
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
        
        .nav a:hover {
            color: #667eea;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
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
            cursor: pointer;
        }
        
        /* HERO SECTION */
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
        
        /* STATS */
        .stats {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 14px;
        }
        
        /* QUICK ACTIONS */
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
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .action-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        
        .action-icon {
            font-size: 64px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }
        
        .action-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .action-desc {
            color: #666;
            font-size: 14px;
        }
        
        /* UPCOMING CLASSES */
        .upcoming-classes {
            max-width: 1200px;
            margin: 60px auto 40px;
            padding: 0 20px;
        }
        
        .class-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .class-card:hover {
            transform: translateX(10px);
        }
        
        .class-info h4 {
            font-size: 20px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .class-details {
            display: flex;
            gap: 20px;
            color: #666;
            font-size: 14px;
            margin-top: 8px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        @media (max-width: 768px) {
            .hero h1 { font-size: 32px; }
            .nav { display: none; }
            .stats { grid-template-columns: 1fr; }
            .actions-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <i class="fas fa-dumbbell"></i>
                BLOCK SPORTS CENTER
            </div>
            
            <nav class="nav">
                <a href="/block-sports-center/public/user/index.php">Trang chủ</a>
                <a href="/block-sports-center/public/user/classes.php">Lớp học</a>
                <a href="/block-sports-center/public/user/schedule.php">Lịch tập</a>
                <a href="/block-sports-center/public/user/booking.php">Đặt phòng</a>
            </nav>
            
            <div class="user-menu">
                <span><?php echo $user['name']; ?></span>
                <div class="user-avatar"><?php echo $user['avatar']; ?></div>
            </div>
        </div>
    </header>
    
    <!-- HERO -->
    <section class="hero">
        <h1>Chào mừng trở lại, <?php echo explode(' ', $user['name'])[count(explode(' ', $user['name']))-1]; ?>! 👋</h1>
        <p>Hãy cùng bắt đầu một ngày tập luyện tuyệt vời</p>
    </section>
    
    <!-- STATS -->
    <section class="stats">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-value">12</div>
            <div class="stat-label">Buổi tập tháng này</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-fire"></i></div>
            <div class="stat-value">2,450</div>
            <div class="stat-label">Calories đã đốt</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-value">18</div>
            <div class="stat-label">Giờ tập luyện</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-trophy"></i></div>
            <div class="stat-value">5</div>
            <div class="stat-label">Thành tựu đạt được</div>
        </div>
    </section>
    
    <!-- QUICK ACTIONS -->
    <section class="quick-actions">
        <h2 class="section-title">Thao tác nhanh</h2>
        
        <div class="actions-grid">
            <div class="action-card" onclick="location.href='/block-sports-center/public/user/classes.php'">
                <div class="action-icon"><i class="fas fa-calendar-plus"></i></div>
                <div class="action-title">Đăng ký lớp học</div>
                <div class="action-desc">Tìm và đăng ký lớp học phù hợp</div>
            </div>
            
            <div class="action-card" onclick="location.href='/block-sports-center/public/user/schedule.php'">
                <div class="action-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="action-title">Xem lịch tập</div>
                <div class="action-desc">Kiểm tra lịch tập của bạn</div>
            </div>
            
            <div class="action-card" onclick="location.href='/block-sports-center/public/user/booking.php'">
                <div class="action-icon"><i class="fas fa-door-open"></i></div>
                <div class="action-title">Đặt phòng</div>
                <div class="action-desc">Đặt phòng tập riêng hoặc sân</div>
            </div>
            
            <div class="action-card" onclick="location.href='/block-sports-center/public/user/profile.php'">
                <div class="action-icon"><i class="fas fa-user"></i></div>
                <div class="action-title">Thông tin cá nhân</div>
                <div class="action-desc">Xem và cập nhật hồ sơ</div>
            </div>
        </div>
    </section>
    
    <!-- UPCOMING CLASSES -->
    <section class="upcoming-classes">
        <h2 class="section-title">Lớp học sắp tới</h2>
        
        <div class="class-card">
            <div class="class-info">
                <h4>Yoga Căn Bản</h4>
                <div class="class-details">
                    <span><i class="fas fa-clock"></i> 08:00 - 09:30</span>
                    <span><i class="fas fa-map-marker-alt"></i> Phòng A1</span>
                    <span><i class="fas fa-user"></i> Nguyễn Thị Lan</span>
                </div>
            </div>
            <button class="btn btn-primary">Chi tiết</button>
        </div>
        
        <div class="class-card">
            <div class="class-info">
                <h4>Gym Strength Training</h4>
                <div class="class-details">
                    <span><i class="fas fa-clock"></i> 18:00 - 19:00</span>
                    <span><i class="fas fa-map-marker-alt"></i> Gym Floor</span>
                    <span><i class="fas fa-user"></i> Trần Văn Mạnh</span>
                </div>
            </div>
            <button class="btn btn-primary">Chi tiết</button>
        </div>
    </section>
</body>
</html>