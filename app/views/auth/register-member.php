<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đã hoàn thành bước 1 chưa
if (!isset($_SESSION['temp_user_id'])) {
    header("Location: /block-sports-center/public/index.php?page=register");
    exit;
}

require_once __DIR__ . '/../../core/Database.php';

$error = "";
$success = "";

$userId = $_SESSION['temp_user_id'];
$fullname = $_SESSION['temp_fullname'] ?? '';
$email = $_SESSION['temp_email'] ?? '';
$phone = $_SESSION['temp_phone'] ?? '';

// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hovaten = trim($_POST['hovaten'] ?? '');
    $gioitinh = trim($_POST['gioitinh'] ?? 'Nam');
    $ngaysinh = trim($_POST['ngaysinh'] ?? '');
    $sdt = trim($_POST['sdt'] ?? '');
    $emailInput = trim($_POST['email'] ?? '');
    $diachi = trim($_POST['diachi'] ?? '');
    
    // Validate
    if (empty($hovaten) || empty($ngaysinh) || empty($sdt)) {
        $error = '❌ Vui lòng điền đầy đủ thông tin bắt buộc (Họ tên, Ngày sinh, SĐT)!';
    } else {
        try {
            $db = Database::getInstance()->getConnection();
            $db->beginTransaction();
            
            // ✅ INSERT hoặc UPDATE nếu trùng PRIMARY KEY
            $upsertQuery = "
                INSERT INTO hoivien (MAHV, HOVATEN, GIOITINH, NGAYSINH, SDT, EMAIL, DIACHI, TRANGTHAI, NGAYTAO)
                VALUES (:mahv, :hovaten, :gioitinh, :ngaysinh, :sdt, :email, :diachi, 'ACTIVE', NOW())
                ON DUPLICATE KEY UPDATE
                    HOVATEN = VALUES(HOVATEN),
                    GIOITINH = VALUES(GIOITINH),
                    NGAYSINH = VALUES(NGAYSINH),
                    SDT = VALUES(SDT),
                    EMAIL = VALUES(EMAIL),
                    DIACHI = VALUES(DIACHI),
                    TRANGTHAI = 'ACTIVE'
            ";
            
            $stmt = $db->prepare($upsertQuery);
            $stmt->execute([
                ':mahv' => $userId,
                ':hovaten' => $hovaten,
                ':gioitinh' => $gioitinh,
                ':ngaysinh' => $ngaysinh,
                ':sdt' => $sdt,
                ':email' => $emailInput,
                ':diachi' => $diachi
            ]);
            
            // Cập nhật lại fullname trong bảng users
            $updateUserQuery = "UPDATE users SET fullname = :fullname WHERE id = :userId";
            $stmt = $db->prepare($updateUserQuery);
            $stmt->execute([
                ':fullname' => $hovaten,
                ':userId' => $userId
            ]);
            
            $db->commit();
            
            // ✅ ĐĂNG KÝ HOÀN TẤT - TỰ ĐỘNG ĐĂNG NHẬP
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $_SESSION['temp_username'];
            $_SESSION['fullname'] = $hovaten;
            $_SESSION['email'] = $emailInput;
            $_SESSION['role'] = 'USER';
            
            // Xóa temp session
            unset($_SESSION['temp_user_id']);
            unset($_SESSION['temp_username']);
            unset($_SESSION['temp_fullname']);
            unset($_SESSION['temp_email']);
            unset($_SESSION['temp_phone']);
            
            // Hiển thị thông báo thành công
            $success = "✅ Đăng ký hội viên thành công! Đang chuyển đến trang chủ...";
            header("refresh:2; url=/block-sports-center/public/index.php?page=user");
            
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $error = '❌ Lỗi: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký hội viên - BLOCK SPORTS CENTER</title>

    <link rel="stylesheet" href="/block-sports-center/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #00b894;
            --primary-dark: #00967a;
            --text-primary: #2d3436;
            --text-secondary: #636e72;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #7FFFD4 0%, #5FD9B4 100%);
            padding: 20px;
        }
        
        .login-box {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 520px;
            padding: 40px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 24px;
        }

        .login-logo i {
            font-size: 64px;
            color: var(--primary-dark);
            margin-bottom: 12px;
        }

        .login-logo h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .login-logo p {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .login-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-primary);
        }

        .login-subtitle {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 24px;
        }

        .info-note {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 13px;
        }

        .info-note i {
            color: #2196F3;
            margin-right: 8px;
        }

        .info-note strong {
            color: #0D47A1;
        }

        .error-msg, .success-msg {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-msg {
            background: #ffe0e0;
            color: #c0392b;
            border: 1px solid #e74c3c;
        }

        .success-msg {
            background: #d4edda;
            color: #155724;
            border: 1px solid #28a745;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .icon-input {
            position: relative;
        }

        .icon-input i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        select.form-control {
            padding-left: 16px;
        }

        textarea.form-control {
            padding-left: 16px;
            resize: vertical;
            min-height: 80px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 184, 148, 0.1);
        }

        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-login {
            flex: 1;
            border: none;
            padding: 14px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 184, 148, 0.3);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .login-box {
                padding: 30px 20px;
            }

            .login-logo i {
                font-size: 48px;
            }

            .login-logo h1 {
                font-size: 20px;
            }

            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">

            <div class="login-logo">
                <i class="fas fa-dumbbell"></i>
                <h1>BLOCK SPORTS CENTER</h1>
                <p>Hệ thống quản lý trung tâm thể thao</p>
            </div>

            <div class="login-title">Đăng ký hội viên</div>
            <div class="login-subtitle">Hoàn tất thông tin để trở thành hội viên</div>

            <div class="info-note">
                <i class="fas fa-info-circle"></i>
                <strong>Bước cuối!</strong> Vui lòng điền đầy đủ thông tin để hoàn tất đăng ký.
            </div>

            <?php if ($error): ?>
                <div class="error-msg">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-msg">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="">

                <div class="form-group">
                    <label class="form-label">Họ và tên <span style="color: red;">*</span></label>
                    <div class="icon-input">
                        <i class="fas fa-user"></i>
                        <input type="text" 
                               name="hovaten" 
                               class="form-control" 
                               placeholder="Nhập họ và tên đầy đủ"
                               value="<?= htmlspecialchars($fullname) ?>"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Giới tính <span style="color: red;">*</span></label>
                    <select name="gioitinh" class="form-control" required>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Ngày sinh <span style="color: red;">*</span></label>
                    <div class="icon-input">
                        <i class="fas fa-calendar"></i>
                        <input type="date" 
                               name="ngaysinh" 
                               class="form-control" 
                               required 
                               max="<?= date('Y-m-d', strtotime('-10 years')) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại <span style="color: red;">*</span></label>
                    <div class="icon-input">
                        <i class="fas fa-phone"></i>
                        <input type="tel" 
                               name="sdt" 
                               class="form-control" 
                               placeholder="0901234567"
                               value="<?= htmlspecialchars($phone) ?>"
                               required 
                               pattern="[0-9]{10,11}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="icon-input">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="email@example.com"
                               value="<?= htmlspecialchars($email) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Địa chỉ</label>
                    <textarea name="diachi" 
                              class="form-control" 
                              rows="3" 
                              placeholder="Nhập địa chỉ đầy đủ"></textarea>
                </div>

                <div class="btn-group">
                    <a href="/block-sports-center/public/index.php?page=register" class="btn-login btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn-login btn-primary">
                        <i class="fas fa-check"></i> Hoàn tất
                    </button>
                </div>

            </form>

        </div>
    </div>
</body>
</html>