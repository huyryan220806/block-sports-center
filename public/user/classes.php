<?php
// Fallback user nếu chưa truyền từ controller
if (!isset($user) || !is_array($user)) {
    $user = [
        'name'      => 'Nguyễn Văn An',
        'member_id' => 'MB001',
        'avatar'    => 'NA',
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

        .nav a.active {
            color: #667eea;
            font-weight: 700;
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

        .user-link {
            text-decoration: none;
            color: inherit;
            display: inline-flex;
            align-items: center;
            gap: 15px;
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
            margin-bottom: 10px;
        }

        .hero-sub {
            font-size: 18px;
            opacity: 0.9;
        }

        /* CLASS GRID */
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

        .class-meta span {
            display: block;
            margin-top: 4px;
        }

        .class-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
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

        /* DETAIL SECTION */
        .detail-section {
            max-width: 1200px;
            margin: 20px auto 60px;
            padding: 0 20px 40px;
        }

        .detail-card {
            background: white;
            border-radius: 20px;
            padding: 25px 25px 30px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }

        .detail-title {
            font-size: 22px;
            margin-bottom: 10px;
            color: #333;
        }

        .detail-body {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }

        .detail-body ul {
            padding-left: 20px;
            margin-top: 8px;
        }

        .detail-body li {
            margin-bottom: 4px;
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 32px; }
            .nav { display: none; }
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
                <a href="/block-sports-center/public/user/classes.php" class="active">Lớp học</a>
                <a href="/block-sports-center/public/user/schedule.php">Lịch tập</a>
                <a href="/block-sports-center/public/user/booking.php">Đặt phòng</a>
            </nav>

            <a href="/block-sports-center/public/user/profile.php" class="user-link">
                <div class="user-menu">
                    <span><?php echo $user['name']; ?></span>
                    <div class="user-avatar"><?php echo $user['avatar']; ?></div>
                </div>
            </a>
        </div>
    </header>

    <!-- HERO -->
    <section class="hero">
        <h1>Các lớp học tại BLOCK SPORTS CENTER</h1>
        <p class="hero-sub">Chọn bộ môn phù hợp với mục tiêu và lịch rảnh của bạn</p>
    </section>

    <!-- CLASS LIST -->
    <section class="classes-section">
        <h2 class="section-title">Danh sách bộ môn</h2>

        <div class="class-grid">

            <!-- Bơi -->
            <div class="class-card">
                <div class="class-icon">
                    <i class="fas fa-swimmer"></i>
                </div>
                <h3>Bơi lội</h3>
                <p class="class-desc">
                    Cải thiện sức bền, tốt cho tim mạch và khớp, phù hợp mọi lứa tuổi.
                </p>
                <div class="class-meta">
                    <span><i class="fas fa-clock"></i> 06:00 - 07:00, 17:00 - 18:00</span>
                    <span><i class="fas fa-signal"></i> Mức độ: Cơ bản &amp; Nâng cao</span>
                </div>
                <div class="class-actions">
                    <button class="btn btn-primary">Xem lịch lớp</button>
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
                    <span><i class="fas fa-clock"></i> 18:00 - 19:00</span>
                    <span><i class="fas fa-users"></i> 8 - 12 người / lớp</span>
                </div>
                <div class="class-actions">
                    <button class="btn btn-primary">Đăng ký tham gia</button>
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
                    <span><i class="fas fa-clock"></i> 19:00 - 20:30</span>
                    <span><i class="fas fa-user"></i> HL viên: Trần Minh Khoa</span>
                </div>
                <div class="class-actions">
                    <button class="btn btn-primary">Xem chi tiết lịch</button>
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
                    <span><i class="fas fa-clock"></i> 16:00 - 17:30</span>
                    <span><i class="fas fa-signal"></i> Mức độ: Trung bình</span>
                </div>
                <div class="class-actions">
                    <button class="btn btn-primary">Xem lịch lớp</button>
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
                    <span><i class="fas fa-clock"></i> 05:30 - 07:00, 19:00 - 21:00</span>
                    <span><i class="fas fa-users"></i> Tối đa 4 người / sân</span>
                </div>
                <div class="class-actions">
                    <button class="btn btn-primary">Đặt sân</button>
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
                    <span><i class="fas fa-clock"></i> 17:00 - 19:00, 19:00 - 21:00</span>
                    <span><i class="fas fa-users"></i> 14 - 22 người / trận</span>
                </div>
                <div class="class-actions">
                    <button class="btn btn-primary">Xem khung giờ trống</button>
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
                    <span><i class="fas fa-clock"></i> 15:00 - 17:00</span>
                    <span><i class="fas fa-heart"></i> Đốt ~400 kcal / giờ</span>
                </div>
                <div class="class-actions">
                    <button class="btn btn-primary">Thử trải nghiệm</button>
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
                    <span><i class="fas fa-clock"></i> 05:00 - 22:00</span>
                    <span><i class="fas fa-signal"></i> Tự do, có HLV hỗ trợ theo giờ</span>
                </div>
                <div class="class-actions">
                    <button class="btn btn-primary">Xem gói tập</button>
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
                    <span><i class="fas fa-clock"></i> 06:00 - 07:00, 19:00 - 20:00</span>
                    <span><i class="fas fa-user"></i> HLV: Nguyễn Mai Chi</span>
                </div>
                <div class="class-actions">
                    <button class="btn btn-primary">Đăng ký buổi thử</button>
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
                    <span><i class="fas fa-clock"></i> 18:30 - 20:00</span>
                    <span><i class="fas fa-users"></i> Nhóm nhỏ 6 - 10 học viên</span>
                </div>
                <div class="class-actions">
                    <button class="btn btn-primary">Xem lịch lớp</button>
                    <button class="btn btn-secondary" onclick="showDetail('boxing')">Chi tiết</button>
                </div>
            </div>

        </div>
    </section>

    <!-- DETAIL SECTION -->
    <section class="detail-section" id="class-detail">
        <h2 class="section-title">Chi tiết môn học</h2>
        <div class="detail-card" id="detail-card">
            <h3 class="detail-title" id="detail-title">Chọn một bộ môn để xem chi tiết</h3>
            <div class="detail-body" id="detail-body">
                Bấm nút <strong>Chi tiết</strong> ở từng bộ môn để xem giới thiệu, lợi ích và gợi ý đối tượng phù hợp.
            </div>
        </div>
    </section>

    <script>
        function showDetail(subject) {
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
                            <li>Phù hợp: mọi lứa tuổi, người cần phục hồi chức năng, người thừa cân.</li>
                            <li>Lợi ích: tăng sức bền, giảm mỡ, giảm stress, cải thiện tư thế.</li>
                            <li>Lưu ý: khởi động kỹ, chọn mức độ phù hợp thể lực.</li>
                        </ul>
                    `;
                    break;

                case 'futsal':
                    title = 'Futsal – Bóng đá trong nhà tốc độ cao';
                    html = `
                        <p>Futsal tập trung vào kỹ thuật cá nhân, phối hợp nhóm và phản xạ nhanh.</p>
                        <ul>
                            <li>Phù hợp: nhóm bạn, đội lớp, người thích vận động cường độ vừa đến cao.</li>
                            <li>Lợi ích: tăng sức bền, phản xạ, khả năng phối hợp và chiến thuật.</li>
                            <li>Gợi ý: nên mang giày chuyên futsal và khởi động kỹ đầu gối, cổ chân.</li>
                        </ul>
                    `;
                    break;

                case 'bongchuyen':
                    title = 'Bóng chuyền – Sức mạnh & tinh thần đồng đội';
                    html = `
                        <p>Bóng chuyền giúp tăng sức mạnh phần thân trên, chân và khả năng bật nhảy.</p>
                        <ul>
                            <li>Phù hợp: người thích chơi theo đội, môi trường năng động.</li>
                            <li>Lợi ích: cải thiện sức mạnh cơ tay, chân, phản xạ và giao tiếp nhóm.</li>
                            <li>Lưu ý: chú ý kỹ thuật tiếp đất khi nhảy để tránh chấn thương gối, cổ chân.</li>
                        </ul>
                    `;
                    break;

                case 'bongro':
                    title = 'Bóng rổ – Cải thiện chiều cao và sức bền';
                    html = `
                        <p>Bóng rổ là môn thể thao đòi hỏi di chuyển liên tục, bật nhảy và chuyền ném chính xác.</p>
                        <ul>
                            <li>Phù hợp: học sinh, sinh viên, người muốn tăng sức bền và chiều cao.</li>
                            <li>Lợi ích: phát triển chiều cao (ở lứa tuổi đang lớn), cải thiện tim mạch, phản xạ.</li>
                            <li>Gợi ý: nên dùng giày đế cao su bám tốt, có hỗ trợ cổ chân.</li>
                        </ul>
                    `;
                    break;

                case 'caulong':
                    title = 'Cầu lông – Nhẹ nhàng nhưng đốt nhiều năng lượng';
                    html = `
                        <p>Cầu lông phù hợp khi bạn muốn vận động linh hoạt nhưng không quá nặng như bóng đá.</p>
                        <ul>
                            <li>Phù hợp: người mới bắt đầu chơi thể thao, dân văn phòng.</li>
                            <li>Lợi ích: tăng linh hoạt khớp vai, cổ tay, phản xạ và độ nhanh nhẹn.</li>
                            <li>Gợi ý: rủ thêm bạn đi đôi/đánh đôi sẽ vui và đỡ mệt hơn.</li>
                        </ul>
                    `;
                    break;

                case 'bongda11':
                    title = 'Bóng đá 11 người – Cường độ cao, tinh thần đồng đội';
                    html = `
                        <p>Sân 11 người phù hợp cho đội bóng muốn luyện tập bài bản hoặc đá giao hữu.</p>
                        <ul>
                            <li>Phù hợp: đội bóng phong trào, nhóm bạn đông, công ty.</li>
                            <li>Lợi ích: tăng sức bền tim phổi, sức mạnh chân, tinh thần đoàn kết.</li>
                            <li>Lưu ý: nên khởi động kỹ, mang giày phù hợp mặt sân (cỏ nhân tạo/đất).</li>
                        </ul>
                    `;
                    break;

                case 'pickleball':
                    title = 'Pickleball – Môn thể thao giải trí đang hot';
                    html = `
                        <p>Pickleball kết hợp giữa tennis, cầu lông và bóng bàn, dễ chơi, vui và phù hợp nhiều lứa tuổi.</p>
                        <ul>
                            <li>Phù hợp: gia đình, nhóm bạn 2–4 người.</li>
                            <li>Lợi ích: vận động nhẹ đến trung bình, tăng linh hoạt và phản xạ.</li>
                            <li>Gợi ý: rất phù hợp cho người mới, chỉ cần 1–2 buổi là chơi được.</li>
                        </ul>
                    `;
                    break;

                case 'gym':
                    title = 'Gym – Tăng cơ, giảm mỡ, cải thiện vóc dáng';
                    html = `
                        <p>Gym tập trung vào sức mạnh cơ bắp, giúp định hình lại cơ thể và cải thiện sức khỏe tổng quát.</p>
                        <ul>
                            <li>Phù hợp: người muốn tăng cơ, giảm mỡ, cải thiện vóc dáng.</li>
                            <li>Lợi ích: tăng sức mạnh, cải thiện chuyển hóa, hỗ trợ xương khớp.</li>
                            <li>Gợi ý: nên có giáo trình rõ ràng, ưu tiên kỹ thuật đúng trước khi tăng tạ.</li>
                        </ul>
                    `;
                    break;

                case 'yoga':
                    title = 'Yoga – Cân bằng thân - tâm - trí';
                    html = `
                        <p>Yoga kết hợp giữa vận động nhẹ nhàng, kéo giãn và hít thở, giúp thư giãn và phục hồi cơ thể.</p>
                        <ul>
                            <li>Phù hợp: người bị căng thẳng, dân văn phòng, người mới bắt đầu vận động.</li>
                            <li>Lợi ích: giảm stress, cải thiện giấc ngủ, tăng linh hoạt và độ dẻo.</li>
                            <li>Gợi ý: luyện đều 2–3 buổi/tuần, ưu tiên lắng nghe cơ thể, không cố quá biên độ.</li>
                        </ul>
                    `;
                    break;

                case 'boxing':
                    title = 'Boxing – Đốt mỡ cực mạnh, xả stress cực đã';
                    html = `
                        <p>Boxing là môn đối kháng cường độ cao, tập trung vào đấm bao cát, di chuyển và phản xạ.</p>
                        <ul>
                            <li>Phù hợp: người muốn giảm mỡ nhanh, thích vận động mạnh và xả stress.</li>
                            <li>Lợi ích: tăng sức bền tim phổi, sức mạnh tay – vai – core, giải tỏa căng thẳng.</li>
                            <li>Lưu ý: cần quấn băng tay, mang găng đúng cỡ và khởi động kỹ vai – cổ tay.</li>
                        </ul>
                    `;
                    break;

                default:
                    title = 'Chi tiết môn học';
                    html  = 'Chọn một bộ môn để xem chi tiết.';
            }

            titleEl.textContent = title;
            bodyEl.innerHTML   = html;

            document.getElementById('class-detail').scrollIntoView({
                behavior: 'smooth'
            });
        }
    </script>
</body>
</html>
