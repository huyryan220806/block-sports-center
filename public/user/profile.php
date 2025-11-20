<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /block-sports-center/public/index.php?page=login');
    exit;
}

// Kết nối database
require_once __DIR__ . '/../../app/core/Database.php';
$db = Database::getInstance()->getConnection();

$userId = $_SESSION['user_id'];
$message = '';
$messageType = '';

// Lấy thông tin user từ bảng users
$userQuery = "SELECT id, username, email, fullname, phone, role FROM users WHERE id = :userId LIMIT 1";
$stmt = $db->prepare($userQuery);
$stmt->execute([':userId' => $userId]);
$userInfo = $stmt->fetch(PDO::FETCH_OBJ);

if (!$userInfo) {
    die("Không tìm thấy thông tin người dùng!");
}

// Lấy thông tin hội viên từ bảng hoivien (liên kết qua MAHV = users.id)
$memberQuery = "SELECT * FROM hoivien WHERE MAHV = :userId LIMIT 1";
$stmt = $db->prepare($memberQuery);
$stmt->execute([':userId' => $userId]);
$memberInfo = $stmt->fetch(PDO::FETCH_OBJ);

// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hovaten = trim($_POST['hovaten'] ?? '');
    $gioitinh = trim($_POST['gioitinh'] ?? 'Nam');
    $ngaysinh = trim($_POST['ngaysinh'] ?? '');
    $sdt = trim($_POST['sdt'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $diachi = trim($_POST['diachi'] ?? '');
    
    // Validate
    if (empty($hovaten) || empty($ngaysinh) || empty($sdt)) {
        $message = 'Vui lòng điền đầy đủ thông tin bắt buộc (Họ tên, Ngày sinh, SĐT)!';
        $messageType = 'error';
    } else {
        try {
            $db->beginTransaction();
            
            if ($memberInfo) {
                // Update thông tin hội viên hiện có
                $updateQuery = "
                    UPDATE hoivien 
                    SET HOVATEN = :hovaten,
                        GIOITINH = :gioitinh,
                        NGAYSINH = :ngaysinh,
                        SDT = :sdt,
                        EMAIL = :email,
                        DIACHI = :diachi
                    WHERE MAHV = :mahv
                ";
                
                $stmt = $db->prepare($updateQuery);
                $stmt->execute([
                    ':hovaten' => $hovaten,
                    ':gioitinh' => $gioitinh,
                    ':ngaysinh' => $ngaysinh,
                    ':sdt' => $sdt,
                    ':email' => $email,
                    ':diachi' => $diachi,
                    ':mahv' => $userId
                ]);
                
                $message = 'Cập nhật thông tin hội viên thành công!';
                $messageType = 'success';
                
            } else {
                // Tạo hội viên mới với MAHV = users.id
                $insertQuery = "
                    INSERT INTO hoivien (MAHV, HOVATEN, GIOITINH, NGAYSINH, SDT, EMAIL, DIACHI, TRANGTHAI, NGAYTAO)
                    VALUES (:mahv, :hovaten, :gioitinh, :ngaysinh, :sdt, :email, :diachi, 'ACTIVE', NOW())
                ";
                
                $stmt = $db->prepare($insertQuery);
                $stmt->execute([
                    ':mahv' => $userId,
                    ':hovaten' => $hovaten,
                    ':gioitinh' => $gioitinh,
                    ':ngaysinh' => $ngaysinh,
                    ':sdt' => $sdt,
                    ':email' => $email,
                    ':diachi' => $diachi
                ]);
                
                $message = 'Đăng ký hội viên thành công!';
                $messageType = 'success';
            }
            
            // Cập nhật lại fullname trong bảng users
            $updateUserQuery = "UPDATE users SET fullname = :fullname WHERE id = :userId";
            $stmt = $db->prepare($updateUserQuery);
            $stmt->execute([
                ':fullname' => $hovaten,
                ':userId' => $userId
            ]);
            
            // Update session fullname
            $_SESSION['fullname'] = $hovaten;
            
            $db->commit();
            
            // Reload thông tin hội viên
            $stmt = $db->prepare($memberQuery);
            $stmt->execute([':userId' => $userId]);
            $memberInfo = $stmt->fetch(PDO::FETCH_OBJ);
            
            // Reload thông tin user
            $stmt = $db->prepare($userQuery);
            $stmt->execute([':userId' => $userId]);
            $userInfo = $stmt->fetch(PDO::FETCH_OBJ);
            
        } catch (PDOException $e) {
            $db->rollBack();
            $message = 'Lỗi: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Xử lý hiển thị user menu
$sessionName = $memberInfo->HOVATEN ?? $userInfo->fullname ?? $_SESSION['username'] ?? 'Khách';
$avatar = mb_strtoupper(mb_substr(trim($sessionName), 0, 2, 'UTF-8'), 'UTF-8');

$user = [
    'name'      => $sessionName,
    'member_id' => 'HV' . str_pad($userId, 4, '0', STR_PAD_LEFT),
    'avatar'    => $avatar,
];

// Chuẩn bị dữ liệu mặc định cho form
$defaultEmail = $memberInfo->EMAIL ?? $userInfo->email ?? '';
$defaultPhone = $memberInfo->SDT ?? $userInfo->phone ?? '';
$defaultFullname = $memberInfo->HOVATEN ?? $userInfo->fullname ?? '';
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

        .profile-row label .required {
            color: #e74c3c;
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

        .profile-row input:read-only {
            background: #f5f5f5;
            cursor: not-allowed;
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

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
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

        .member-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-suspended {
            background: #fff3cd;
            color: #856404;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .info-note {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-note i {
            color: #2196F3;
            margin-right: 8px;
        }
        
        @media (max-width: 768px) {
            .hero h1 { font-size: 32px; }
            .nav { display: none; }
            .profile-header {
                flex-direction: column;
                text-align: center;
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
        <p>Xem và cập nhật hồ sơ hội viên của bạn</p>
    </section>

    <section class="profile">
        <div class="profile-card">
            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?>">
                    <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <?php if (!$memberInfo): ?>
                <div class="info-note">
                    <i class="fas fa-info-circle"></i>
                    <strong>Bạn chưa đăng ký làm hội viên!</strong> Vui lòng điền đầy đủ thông tin dưới đây để hoàn tất đăng ký.
                </div>
            <?php endif; ?>

            <div class="profile-header">
                <div class="profile-avatar">
                    <?= htmlspecialchars($user['avatar']) ?>
                </div>
                <div>
                    <h2>
                        <?= htmlspecialchars($user['name']) ?>
                        <?php if ($memberInfo): ?>
                            <span class="member-status status-<?= strtolower($memberInfo->TRANGTHAI ?? 'inactive') ?>">
                                <?php
                                    $statusMap = [
                                        'ACTIVE' => 'Đang hoạt động',
                                        'SUSPENDED' => 'Tạm ngưng',
                                        'INACTIVE' => 'Không hoạt động'
                                    ];
                                    echo $statusMap[$memberInfo->TRANGTHAI] ?? 'Chưa xác định';
                                ?>
                            </span>
                        <?php else: ?>
                            <span class="member-status status-inactive">Chưa đăng ký</span>
                        <?php endif; ?>
                    </h2>
                    <p>Mã hội viên: <strong><?= htmlspecialchars($user['member_id']) ?></strong></p>
                    <?php if ($memberInfo && $memberInfo->NGAYTAO): ?>
                        <p>Ngày tham gia: <?= date('d/m/Y', strtotime($memberInfo->NGAYTAO)) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <form method="POST" class="profile-body">
                <div class="profile-row">
                    <label>Họ và tên <span class="required">*</span></label>
                    <input type="text" name="hovaten" value="<?= htmlspecialchars($defaultFullname) ?>" required placeholder="Nhập họ và tên đầy đủ">
                </div>

                <div class="profile-row">
                    <label>Giới tính <span class="required">*</span></label>
                    <select name="gioitinh" required>
                        <option value="Nam" <?= ($memberInfo && $memberInfo->GIOITINH === 'Nam') ? 'selected' : '' ?>>Nam</option>
                        <option value="Nữ" <?= ($memberInfo && $memberInfo->GIOITINH === 'Nữ') ? 'selected' : '' ?>>Nữ</option>
                        <option value="Khác" <?= ($memberInfo && $memberInfo->GIOITINH === 'Khác') ? 'selected' : '' ?>>Khác</option>
                    </select>
                </div>

                <div class="profile-row">
                    <label>Ngày sinh <span class="required">*</span></label>
                    <input type="date" name="ngaysinh" value="<?= $memberInfo ? date('Y-m-d', strtotime($memberInfo->NGAYSINH)) : '' ?>" required max="<?= date('Y-m-d', strtotime('-10 years')) ?>">
                </div>

                <div class="profile-row">
                    <label>Số điện thoại <span class="required">*</span></label>
                    <input type="tel" name="sdt" value="<?= htmlspecialchars($defaultPhone) ?>" required pattern="[0-9]{10,11}" placeholder="0901234567">
                </div>

                <div class="profile-row">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($defaultEmail) ?>" placeholder="email@example.com">
                </div>

                <div class="profile-row">
                    <label>Địa chỉ</label>
                    <textarea name="diachi" rows="3" placeholder="Nhập địa chỉ đầy đủ"><?= htmlspecialchars($memberInfo->DIACHI ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= $memberInfo ? 'Cập nhật thông tin' : 'Đăng ký hội viên' ?>
                </button>
            </form>
        </div>
    </section>
</body>
</html>