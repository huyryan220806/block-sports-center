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
        <h1>Đặt phòng / Đặt sân</h1>
        <p>Chọn bộ môn, thời gian và sân mong muốn</p>
    </section>

    <!-- BOOKING FORM (GIAO DIỆN) -->
    <section class="quick-actions">
        <h2 class="section-title">Tạo đặt chỗ mới</h2>

        <div class="actions-grid">
            <div class="action-card">
                <h3>Thông tin đặt chỗ</h3>

                <form class="booking-form">
                    <div class="form-group">
                        <label>Bộ môn</label>
                        <select>
                            <option>Bơi</option>
                            <option>Futsal</option>
                            <option>Bóng chuyền</option>
                            <option>Bóng rổ</option>
                            <option>Cầu lông</option>
                            <option>Sân bóng đá 11</option>
                            <option>Pickleball</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Ngày</label>
                        <input type="date">
                    </div>

                    <div class="form-group form-row">
                        <div>
                            <label>Giờ bắt đầu</label>
                            <input type="time">
                        </div>
                        <div>
                            <label>Giờ kết thúc</label>
                            <input type="time">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea rows="3" placeholder="Ví dụ: đá giao hữu, ưu tiên sân gần bãi xe..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Gửi yêu cầu đặt chỗ</button>
                </form>
            </div>

            <div class="action-card">
                <h3>Gợi ý & lưu ý</h3>
                <ul class="schedule-list">
                    <li><i class="fas fa-info-circle"></i> Đặt chỗ trước ít nhất 2 giờ.</li>
                    <li><i class="fas fa-info-circle"></i> Nếu đến trễ quá 15 phút, sân có thể được chuyển cho khách khác.</li>
                    <li><i class="fas fa-info-circle"></i> Vui lòng mang theo thẻ thành viên khi đến nhận sân.</li>
                    <li><i class="fas fa-info-circle"></i> Một số khung giờ cao điểm có thể tính phụ thu.</li>
                </ul>

                <h4 style="margin-top:20px;">Lịch đặt gần đây</h4>
                <ul class="schedule-list">
                    <li>
                        <strong>Futsal</strong> - 19:00 hôm nay<br>
                        <span>Sân Futsal A</span>
                    </li>
                    <li>
                        <strong>Cầu lông</strong> - 20:00 ngày mai<br>
                        <span>Sân Cầu lông 2</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <style>
        .booking-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 14px;
        }

        .form-group label {
            font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
    </style>
</body>
</html>
