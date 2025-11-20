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
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLOCK SPORTS CENTER - Lớp học</title>
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

        .classes-section {
            max-width: 1200px;
            margin: 30px auto 40px;
            padding: 0 20px 20px;
        }

        .section-title {
            color: white;
            font-size: 32px;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .class-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .class-card {
            background: white;
            border-radius: 20px;
            padding: 30px 25px;
            text-align: center;
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .class-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(0,0,0,0.2);
        }

        .class-icon {
            font-size: 60px;
            margin-bottom: 18px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .class-card h3 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #333;
        }

        .class-desc {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .class-meta {
            font-size: 13px;
            color: #666;
            margin-bottom: 18px;
        }

        .class-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 18px;
            border-radius: 999px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f3f4ff;
            color: #4f46e5;
        }

        .btn-secondary:hover {
            background: #e0e7ff;
        }

        /* ✅ DETAIL SECTION - KHUNG TRẮNG */
        .detail-section {
            max-width: 1200px;
            margin: 40px auto 60px;
            padding: 0 20px;
            display: none;
        }

        .detail-section.show {
            display: block;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .detail-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .detail-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .detail-body {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
        }

        .detail-body p {
            margin-bottom: 16px;
        }

        .detail-body ul {
            margin-left: 20px;
            margin-top: 12px;
        }

        .detail-body li {
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 32px; }
            .nav { display: none; }
            .detail-card {
                padding: 25px;
            }
            .detail-title {
                font-size: 22px;
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
                <a href="/block-sports-center/public/user/classes.php" class="active">Lớp học</a>
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
        <h1>Các lớp học tại BLOCK SPORTS CENTER</h1>
        <p class="hero-sub">Chọn bộ môn phù hợp với mục tiêu và lịch rảnh của bạn</p>
    </section>

    <section class="classes-section">
        <h2 class="section-title">Danh sách bộ môn</h2>
        <div class="class-grid">
            
            <!-- Bơi lội -->
            <div class="class-card">
                <div class="class-icon">
                    <i class="fas fa-swimmer"></i>
                </div>
                <h3>Bơi lội</h3>
                <p class="class-desc">Cải thiện sức bền, tốt cho tim mạch và khớp.</p>
                <div class="class-meta">
                    <span><i class="fas fa-clock"></i> 06:00 - 21:00</span>
                </div>
                <div class="class-actions">
                    <a href="/block-sports-center/public/user/booking.php" class="btn btn-primary">Đăng ký ngay</a>
                    <button class="btn btn-secondary" onclick="showDetail('boi')">Chi tiết</button>
                </div>
            </div>

            <!-- Futsal -->
            <div class="class-card">
                <div class="class-icon">
                    <i class="fas fa-futbol"></i>
                </div>
                <h3>Futsal</h3>
                <p class="class-desc">
                    Luyện phản xạ nhanh, khả năng phối hợp đồng đội và sức bền.
                </p>
                <div class="class-meta">
                    <span><i class="fas fa-clock"></i> 06:00 - 21:00</span>
                </div>
                <div class="class-actions">
                    <a href="/block-sports-center/public/user/booking.php" class="btn btn-primary">Đăng ký ngay</a>
                    <button class="btn btn-secondary" onclick="showDetail('futsal')">Chi tiết</button>
                </div>
            </div>

            <!-- Bóng chuyền -->
            <div class="class-card">
                <div class="class-icon">
                    <i class="fas fa-volleyball-ball"></i>
                </div>
                <h3>Bóng chuyền</h3>
                <p class="class-desc">
                    Tăng sức mạnh tay, chân và khả năng nhảy, phù hợp chơi theo nhóm.
                </p>
                <div class="class-meta">
                    <span><i class="fas fa-clock"></i> 06:00 - 21:00</span>
                </div>
                <div class="class-actions">
                    <a href="/block-sports-center/public/user/booking.php" class="btn btn-primary">Đăng ký ngay</a>
                    <button class="btn btn-secondary" onclick="showDetail('bongchuyen')">Chi tiết</button>
                </div>
            </div>

            <!-- Bóng rổ -->
            <div class="class-card">
                <div class="class-icon">
                    <i class="fas fa-basketball-ball"></i>
                </div>
                <h3>Bóng rổ</h3>
                <p class="class-desc">
                    Đốt nhiều calories, cải thiện chiều cao, sức bền và phản xạ.
                </p>
                <div class="class-meta">
                    <span><i class="fas fa-clock"></i> 06:00 - 21:00</span>
                </div>
                <div class="class-actions">
                    <a href="/block-sports-center/public/user/booking.php" class="btn btn-primary">Đăng ký ngay</a>
                    <button class="btn btn-secondary" onclick="showDetail('bongro')">Chi tiết</button>
                </div>
            </div>

            <!-- Cầu lông -->
            <div class="class-card">
                <div class="class-icon">
                    <i class="fas fa-table-tennis"></i>
                </div>
                <h3>Cầu lông</h3>
                <p class="class-desc">
                    Bộ môn nhẹ nhàng, linh hoạt, phù hợp khi muốn vận động nhưng không quá nặng.
                </p>
                <div class="class-meta">
                    <span><i class="fas fa-clock"></i> 06:00 - 21:00</span>
                </div>
                <div class="class-actions">
                    <a href="/block-sports-center/public/user/booking.php" class="btn btn-primary">Đăng ký ngay</a>
                    <button class="btn btn-secondary" onclick="showDetail('caulong')">Chi tiết</button>
                </div>
            </div>

            <!-- Bóng đá 11 người -->
            <div class="class-card">
                <div class="class-icon">
                    <i class="fas fa-futbol"></i>
                </div>
                <h3>Sân bóng đá 11 người</h3>
                <p class="class-desc">
                    Cường độ cao, phù hợp cho đội nhóm muốn tập luyện hoặc đá giao hữu.
                </p>
                <div class="class-meta">
                    <span><i class="fas fa-clock"></i> 06:00 - 21:00</span>
                </div>
                <div class="class-actions">
                    <a href="/block-sports-center/public/user/booking.php" class="btn btn-primary">Đăng ký ngay</a>
                    <button class="btn btn-secondary" onclick="showDetail('bongda11')">Chi tiết</button>
                </div>
            </div>

            <!-- Pickleball -->
            <div class="class-card">
                <div class="class-icon">
                    <i class="fas fa-table-tennis"></i>
                </div>
                <h3>Pickleball</h3>
                <p class="class-desc">
                    Môn thể thao mới, vui, dễ chơi, phù hợp nhóm bạn 2 - 4 người.
                </p>
                <div class="class-meta">
                    <span><i class="fas fa-clock"></i> 06:00 - 21:00</span>
                </div>
                <div class="class-actions">
                    <a href="/block-sports-center/public/user/booking.php" class="btn btn-primary">Đăng ký ngay</a>
                    <button class="btn btn-secondary" onclick="showDetail('pickleball')">Chi tiết</button>
                </div>
            </div>

            <!-- GYM -->
            <div class="class-card">
                <div class="class-icon">
                    <i class="fas fa-dumbbell"></i>
                </div>
                <h3>Gym</h3>
                <p class="class-desc">
                    Luyện sức mạnh, tăng cơ, giảm mỡ với hệ thống máy tập hiện đại.
                </p>
                <div class="class-meta">
                    <span><i class="fas fa-clock"></i> 24/7 - Luôn mở cửa</span>
                </div>
                <div class="class-actions">
                    <a href="/block-sports-center/public/user/booking.php" class="btn btn-primary">Đăng ký ngay</a>
                    <button class="btn btn-secondary" onclick="showDetail('gym')">Chi tiết</button>
                </div>
            </div>

            <!-- YOGA -->
            <div class="class-card">
                <div class="class-icon">
                    <i class="fas fa-spa"></i>
                </div>
                <h3>Yoga</h3>
                <p class="class-desc">
                    Giãn cơ, thư giãn tâm trí, cải thiện tư thế và độ linh hoạt của cơ thể.
                </p>
                <div class="class-meta">
                    <span><i class="fas fa-clock"></i> 06:00 - 21:00</span>
                </div>
                <div class="class-actions">
                    <a href="/block-sports-center/public/user/booking.php" class="btn btn-primary">Đăng ký ngay</a>
                    <button class="btn btn-secondary" onclick="showDetail('yoga')">Chi tiết</button>
                </div>
            </div>

            <!-- BOXING -->
            <div class="class-card">
                <div class="class-icon">
                    <i class="fas fa-hand-fist"></i>
                </div>
                <h3>Boxing</h3>
                <p class="class-desc">
                    Môn cường độ cao giúp đốt mỡ nhanh, tăng sức bền và phản xạ.
                </p>
                <div class="class-meta">
                    <span><i class="fas fa-clock"></i> 06:00 - 21:00</span>
                </div>
                <div class="class-actions">
                    <a href="/block-sports-center/public/user/booking.php" class="btn btn-primary">Đăng ký ngay</a>
                    <button class="btn btn-secondary" onclick="showDetail('boxing')">Chi tiết</button>
                </div>
            </div>

        </div>
    </section>

    <!-- ✅ DETAIL SECTION - KHUNG TRẮNG -->
    <section class="detail-section" id="class-detail">
        <div class="detail-card">
            <h3 class="detail-title" id="detail-title">Chi tiết môn học</h3>
            <div class="detail-body" id="detail-body">
                <p>Bấm nút <strong>Chi tiết</strong> ở từng bộ môn để xem giới thiệu, lợi ích và gợi ý đối tượng phù hợp.</p>
            </div>
        </div>
    </section>

    <script>
        function showDetail(subject) {
            const detailSection = document.getElementById('class-detail');
            const titleEl = document.getElementById('detail-title');
            const bodyEl  = document.getElementById('detail-body');

            let title = '';
            let html  = '';

            switch (subject) {
                case 'boi':
                    title = 'Bơi lội – Môn thể thao toàn thân';
                    html = `
                        <p>Bơi lội giúp vận động toàn bộ cơ thể, đặc biệt tốt cho tim mạch, phổi và hệ xương khớp.</p>
                        <ul>
                            <li><strong>Phù hợp:</strong> mọi lứa tuổi, người cần phục hồi chức năng, người thừa cân.</li>
                            <li><strong>Lợi ích:</strong> tăng sức bền, giảm mỡ, giảm stress, cải thiện tư thế.</li>
                            <li><strong>Lưu ý:</strong> khởi động kỹ, chọn mức độ phù hợp thể lực.</li>
                        </ul>
                    `;
                    break;

                case 'futsal':
                    title = 'Futsal – Bóng đá trong nhà tốc độ cao';
                    html = `
                        <p>Futsal tập trung vào kỹ thuật cá nhân, phối hợp nhóm và phản xạ nhanh.</p>
                        <ul>
                            <li><strong>Phù hợp:</strong> nhóm bạn, đội lớp, người thích vận động cường độ vừa đến cao.</li>
                            <li><strong>Lợi ích:</strong> tăng sức bền, phản xạ, khả năng phối hợp và chiến thuật.</li>
                            <li><strong>Gợi ý:</strong> nên mang giày chuyên futsal và khởi động kỹ đầu gối, cổ chân.</li>
                        </ul>
                    `;
                    break;

                case 'bongchuyen':
                    title = 'Bóng chuyền – Sức mạnh & tinh thần đồng đội';
                    html = `
                        <p>Bóng chuyền giúp tăng sức mạnh phần thân trên, chân và khả năng bật nhảy.</p>
                        <ul>
                            <li><strong>Phù hợp:</strong> người thích chơi theo đội, môi trường năng động.</li>
                            <li><strong>Lợi ích:</strong> cải thiện sức mạnh cơ tay, chân, phản xạ và giao tiếp nhóm.</li>
                            <li><strong>Lưu ý:</strong> chú ý kỹ thuật tiếp đất khi nhảy để tránh chấn thương gối, cổ chân.</li>
                        </ul>
                    `;
                    break;

                case 'bongro':
                    title = 'Bóng rổ – Cải thiện chiều cao và sức bền';
                    html = `
                        <p>Bóng rổ là môn thể thao đòi hỏi di chuyển liên tục, bật nhảy và chuyền ném chính xác.</p>
                        <ul>
                            <li><strong>Phù hợp:</strong> học sinh, sinh viên, người muốn tăng sức bền và chiều cao.</li>
                            <li><strong>Lợi ích:</strong> phát triển chiều cao (ở lứa tuổi đang lớn), cải thiện tim mạch, phản xạ.</li>
                            <li><strong>Gợi ý:</strong> nên dùng giày đế cao su bám tốt, có hỗ trợ cổ chân.</li>
                        </ul>
                    `;
                    break;

                case 'caulong':
                    title = 'Cầu lông – Nhẹ nhàng nhưng đốt nhiều năng lượng';
                    html = `
                        <p>Cầu lông phù hợp khi bạn muốn vận động linh hoạt nhưng không quá nặng như bóng đá.</p>
                        <ul>
                            <li><strong>Phù hợp:</strong> người mới bắt đầu chơi thể thao, dân văn phòng.</li>
                            <li><strong>Lợi ích:</strong> tăng linh hoạt khớp vai, cổ tay, phản xạ và độ nhanh nhẹn.</li>
                            <li><strong>Gợi ý:</strong> rủ thêm bạn đi đôi/đánh đôi sẽ vui và đỡ mệt hơn.</li>
                        </ul>
                    `;
                    break;

                case 'bongda11':
                    title = 'Bóng đá 11 người – Cường độ cao, tinh thần đồng đội';
                    html = `
                        <p>Sân 11 người phù hợp cho đội bóng muốn luyện tập bài bản hoặc đá giao hữu.</p>
                        <ul>
                            <li><strong>Phù hợp:</strong> đội bóng phong trào, nhóm bạn đông, công ty.</li>
                            <li><strong>Lợi ích:</strong> tăng sức bền tim phổi, sức mạnh chân, tinh thần đoàn kết.</li>
                            <li><strong>Lưu ý:</strong> nên khởi động kỹ, mang giày phù hợp mặt sân (cỏ nhân tạo/đất).</li>
                        </ul>
                    `;
                    break;

                case 'pickleball':
                    title = 'Pickleball – Môn thể thao giải trí đang hot';
                    html = `
                        <p>Pickleball kết hợp giữa tennis, cầu lông và bóng bàn, dễ chơi, vui và phù hợp nhiều lứa tuổi.</p>
                        <ul>
                            <li><strong>Phù hợp:</strong> gia đình, nhóm bạn 2–4 người.</li>
                            <li><strong>Lợi ích:</strong> vận động nhẹ đến trung bình, tăng linh hoạt và phản xạ.</li>
                            <li><strong>Gợi ý:</strong> rất phù hợp cho người mới, chỉ cần 1–2 buổi là chơi được.</li>
                        </ul>
                    `;
                    break;

                case 'gym':
                    title = 'Gym – Tăng cơ, giảm mỡ, cải thiện vóc dáng';
                    html = `
                        <p>Gym tập trung vào sức mạnh cơ bắp, giúp định hình lại cơ thể và cải thiện sức khỏe tổng quát.</p>
                        <ul>
                            <li><strong>Phù hợp:</strong> người muốn tăng cơ, giảm mỡ, cải thiện vóc dáng.</li>
                            <li><strong>Lợi ích:</strong> tăng sức mạnh, cải thiện chuyển hóa, hỗ trợ xương khớp.</li>
                            <li><strong>Giờ mở cửa:</strong> 24/7 - Luôn sẵn sàng phục vụ bạn bất cứ lúc nào.</li>
                            <li><strong>Gợi ý:</strong> nên có giáo trình rõ ràng, ưu tiên kỹ thuật đúng trước khi tăng tạ.</li>
                        </ul>
                    `;
                    break;

                case 'yoga':
                    title = 'Yoga – Cân bằng thân - tâm - trí';
                    html = `
                        <p>Yoga kết hợp giữa vận động nhẹ nhàng, kéo giãn và hít thở, giúp thư giãn và phục hồi cơ thể.</p>
                        <ul>
                            <li><strong>Phù hợp:</strong> người bị căng thẳng, dân văn phòng, người mới bắt đầu vận động.</li>
                            <li><strong>Lợi ích:</strong> giảm stress, cải thiện giấc ngủ, tăng linh hoạt và độ dẻo.</li>
                            <li><strong>Gợi ý:</strong> luyện đều 2–3 buổi/tuần, ưu tiên lắng nghe cơ thể, không cố quá biên độ.</li>
                        </ul>
                    `;
                    break;

                case 'boxing':
                    title = 'Boxing – Đốt mỡ cực mạnh, xả stress cực đã';
                    html = `
                        <p>Boxing là môn đối kháng cường độ cao, tập trung vào đấm bao cát, di chuyển và phản xạ.</p>
                        <ul>
                            <li><strong>Phù hợp:</strong> người muốn giảm mỡ nhanh, thích vận động mạnh và xả stress.</li>
                            <li><strong>Lợi ích:</strong> tăng sức bền tim phổi, sức mạnh tay – vai – core, giải tỏa căng thẳng.</li>
                            <li><strong>Lưu ý:</strong> cần quấn băng tay, mang găng đúng cỡ và khởi động kỹ vai – cổ tay.</li>
                        </ul>
                    `;
                    break;

                default:
                    title = 'Chi tiết môn học';
                    html  = '<p>Chọn một bộ môn để xem chi tiết.</p>';
            }

            titleEl.textContent = title;
            bodyEl.innerHTML   = html;

            detailSection.classList.add('show');
            detailSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    </script>
</body>
</html>