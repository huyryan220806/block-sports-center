<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../core/Database.php';

// Nếu đã đăng nhập → redirect
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN') {
        header("Location: /block-sports-center/public/index.php?c=dashboard&a=index");
        exit;
    } else {
        // ✅ USER → VIEWS/USER/INDEX.PHP
        header("Location: /block-sports-center/public/index.php?page=user");
        exit;
    }
}

$error = "";
$success = "";

// Xử lý khi submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validate cơ bản
    if (empty($fullname) || empty($email) || empty($username) || empty($password)) {
        $error = "❌ Vui lòng điền đầy đủ thông tin bắt buộc!";
    } elseif ($password !== $password_confirm) {
        $error = "❌ Mật khẩu xác nhận không trùng khớp!";
    } elseif (strlen($password) < 6) {
        $error = "❌ Mật khẩu phải có ít nhất 6 ký tự!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Email không hợp lệ!";
    } else {
        try {
            // Kết nối DB
            $db = Database::getInstance()->getConnection();
            
            // Kiểm tra username hoặc email đã tồn tại
            $stmt = $db->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email
            ]);

            if ($stmt->rowCount() > 0) {
                $error = "❌ Tên đăng nhập hoặc email đã tồn tại!";
            } else {
                // Hash mật khẩu bằng bcrypt
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                // Thêm tài khoản mới
                $insertStmt = $db->prepare("
                    INSERT INTO users (fullname, email, phone, username, password_hash, role, created_at) 
                    VALUES (:fullname, :email, :phone, :username, :password_hash, 'USER', NOW())
                ");

                $result = $insertStmt->execute([
                    ':fullname' => $fullname,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':username' => $username,
                    ':password_hash' => $passwordHash
                ]);

                if ($result) {
                    // ✅ LẤY ID USER VỪA TẠO
                    $newUserId = $db->lastInsertId();
                    
                    // ✅ TỰ ĐỘNG ĐĂNG NHẬP (LƯU SESSION)
                    $_SESSION['user_id'] = $newUserId;
                    $_SESSION['username'] = $username;
                    $_SESSION['fullname'] = $fullname;
                    $_SESSION['email'] = $email;
                    $_SESSION['role'] = 'USER';
                    
                    // ✅ REDIRECT ĐẾN VIEWS/USER/INDEX.PHP
                    $success = "✅ Đăng ký thành công! Đang chuyển đến trang chủ...";
                    header("refresh:1; url=/block-sports-center/public/index.php?page=user");
                    exit;
                } else {
                    $error = "❌ Lỗi hệ thống, vui lòng thử lại!";
                }
            }
        } catch (PDOException $e) {
            $error = "❌ Lỗi database: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - BLOCK SPORTS CENTER</title>

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
            max-width: 480px;
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
            margin-bottom: 20px;
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

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 184, 148, 0.1);
        }

        .btn-login {
            width: 100%;
            border: none;
            padding: 14px 20px;
            border-radius: 8px;
            background-color: var(--primary);
            color: white;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 184, 148, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .register-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .register-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
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

            <div class="login-title">Tạo tài khoản mới</div>
            <div class="login-subtitle">Điền thông tin để đăng ký</div>

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
                               name="fullname" 
                               class="form-control" 
                               placeholder="Nguyễn Văn A"
                               value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span style="color: red;">*</span></label>
                    <div class="icon-input">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="email@example.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <div class="icon-input">
                        <i class="fas fa-phone"></i>
                        <input type="tel" 
                               name="phone" 
                               class="form-control" 
                               placeholder="0901234567"
                               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                               pattern="[0-9]{10,11}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tên đăng nhập <span style="color: red;">*</span></label>
                    <div class="icon-input">
                        <i class="fas fa-user-circle"></i>
                        <input type="text" 
                               name="username" 
                               class="form-control" 
                               placeholder="username123"
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mật khẩu <span style="color: red;">*</span></label>
                    <div class="icon-input">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="Ít nhất 6 ký tự"
                               minlength="6"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Xác nhận mật khẩu <span style="color: red;">*</span></label>
                    <div class="icon-input">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               name="password_confirm" 
                               class="form-control" 
                               placeholder="Nhập lại mật khẩu"
                               minlength="6"
                               required>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-user-plus"></i> Đăng ký
                </button>
            </form>

            <div class="register-link">
                Đã có tài khoản? 
                <a href="/block-sports-center/public/index.php?page=login">
                    Đăng nhập ngay
                </a>
            </div>

        </div>
    </div>
</body>
</html>
//