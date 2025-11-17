<?php
// Nếu controller chưa truyền $user thì dùng giá trị mặc định
if (!isset($user) || !is_array($user)) {
    $user = [
        'name'      => 'Nguyễn Văn An',
        'member_id' => 'MB001',
        'avatar'    => 'NA',
    ];
}

// Nếu chưa có biến $totalCalo thì cho = 0 để khỏi báo lỗi
if (!isset($totalCalo)) {
    $totalCalo = 0;
}
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
                <a href="/block-sports-center/public/index.php?page=user">Trang chủ</a>
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
        <h1>Thông tin cá nhân</h1>
        <p>Xem và cập nhật hồ sơ tập luyện của bạn</p>
    </section>

    <!-- PROFILE CONTENT -->
    <section class="profile">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?= htmlspecialchars($user['avatar'] ?? 'NA') ?>
                </div>
                <div>
                    <h2><?= htmlspecialchars($user['name'] ?? 'Nguyễn Văn An') ?></h2>
                    <p>Mã hội viên: <strong><?= htmlspecialchars($user['member_id'] ?? 'MB001') ?></strong></p>
                </div>
            </div>

            <div class="profile-body">
                <div class="profile-row">
                    <label>Email</label>
                    <input type="email" value="an.nguyen@example.com">
                </div>

                <div class="profile-row">
                    <label>Số điện thoại</label>
                    <input type="text" value="0901 234 567">
                </div>

                <div class="profile-row">
                    <label>Ngày sinh</label>
                    <input type="date" value="2000-01-01">
                </div>

                <div class="profile-row">
                    <label>Giới tính</label>
                    <select>
                        <option>Nam</option>
                        <option>Nữ</option>
                        <option>Khác</option>
                    </select>
                </div>

                <div class="profile-row">
                    <label>Mục tiêu luyện tập</label>
                    <textarea rows="3" placeholder="Ví dụ: giảm 5kg trong 3 tháng, tăng sức bền, cải thiện sức khỏe tim mạch..."></textarea>
                </div>

                <button class="btn btn-primary">Lưu thông tin</button>
            </div>
        </div>
    </section>

    <style>
        .profile {
            max-width: 800px;
            margin: 0 auto 60px;
            padding: 0 20px;
        }

        .profile-card {
            background: white;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .profile-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #6366F1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 20px;
        }

        .profile-body {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .profile-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 14px;
        }

        .profile-row label {
            font-weight: 600;
        }

        .profile-row input,
        .profile-row select,
        .profile-row textarea {
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
    </style>
</body>
</html>
