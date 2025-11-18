<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($user) || !is_array($user)) {
    $sessionName = $_SESSION['fullname'] ?? $_SESSION['username'] ?? 'Khách';
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
    <title>BLOCK SPORTS CENTER - Thông tin cá nhân</title>
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

        .profile {
            max-width: 800px;
            margin: 0 auto 60px;
            padding: 0 20px;
        }

        .profile-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 32px;
        }

        .profile-header h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 5px;
        }

        .profile-header p {
            color: #666;
            font-size: 14px;
        }

        .profile-body {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .profile-row {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .profile-row label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .profile-row input,
        .profile-row select,
        .profile-row textarea {
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.2s;
        }

        .profile-row input:focus,
        .profile-row select:focus,
        .profile-row textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 15px;
            margin-top: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        @media (max-width: 768px) {
            .hero h1 { font-size: 32px; }
            .nav { display: none; }
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
        <h1>Thông tin cá nhân</h1>
        <p>Xem và cập nhật hồ sơ tập luyện của bạn</p>
    </section>

    <section class="profile">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?= htmlspecialchars($user['avatar']) ?>
                </div>
                <div>
                    <h2><?= htmlspecialchars($user['name']) ?></h2>
                    <p>Mã hội viên: <strong><?= htmlspecialchars($user['member_id']) ?></strong></p>
                </div>
            </div>

            <div class="profile-body">
                <div class="profile-row">
                    <label>Email</label>
                    <input type="email" value="<?= htmlspecialchars($_SESSION['email'] ?? 'user@example.com') ?>" readonly>
                </div>

                <div class="profile-row">
                    <label>Số điện thoại</label>
                    <input type="text" value="0901 234 567" placeholder="Chưa cập nhật">
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
                    <textarea rows="4" placeholder="Ví dụ: giảm 5kg trong 3 tháng, tăng sức bền, cải thiện sức khỏe tim mạch..."></textarea>
                </div>

                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu thông tin
                </button>
            </div>
        </div>
    </section>
</body>
</html>