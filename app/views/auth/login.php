<?php
// app/views/auth/login.php
// Trang đăng nhập
// Updated: 2025-11-18 12:53:14 UTC
// Fixed: Link quên mật khẩu
// Author: @huyryan220806
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - BLOCK SPORTS CENTER</title>

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
            --border-light: #dfe6e9;
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
            max-width: 420px;
            padding: 40px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-logo i {
            font-size: 64px;
            color: var(--primary-dark);
            margin-bottom: 12px;
        }

        .login-logo h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-top: 16px;
        }

        .login-logo p {
            color: var(--text-secondary);
            font-size: 14px;
            margin-top: 8px;
        }

        .error-msg {
            padding: 12px 16px;
            background: #ffe0e0;
            color: #c0392b;
            border: 1px solid #e74c3c;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
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

        .login-form {
            margin-top: 32px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            display: block;
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
            z-index: 1;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 184, 148, 0.15);
        }

        .forgot-password {
            text-align: right;
            margin-top: -16px;
            margin-bottom: 20px;
            position: relative;
            z-index: 10;
        }

        .forgot-password a {
            color: var(--primary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            display: inline-block;
            position: relative;
            z-index: 10;
        }

        .forgot-password a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
        }

        .btn-login:hover {
            background: var(--primary-dark);
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

        .login-footer {
            text-align: center;
            margin-top: 24px;
            color: var(--text-secondary);
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .login-box {
                padding: 30px 20px;
            }

            .login-logo i {
                font-size: 48px;
            }

            .login-logo h1 {
                font-size: 24px;
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

        <?php if (!empty($loginError)): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($loginError) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <div class="form-group">
                <label class="form-label">Tên đăng nhập hoặc Email</label>
                <div class="icon-input">
                    <i class="fas fa-user"></i>
                    <input type="text"
                           name="username"
                           class="form-control"
                           placeholder="Nhập username hoặc email"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                           required
                           autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu</label>
                <div class="icon-input">
                    <i class="fas fa-lock"></i>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Nhập mật khẩu"
                           required>
                </div>
            </div>

            <!-- ✅ FIX: Đổi từ ?c=auth&a=forgotPassword → ?page=forgot-password -->
            <div class="forgot-password">
                <a href="/block-sports-center/public/index.php?page=forgot-password">
                    <i class="fas fa-question-circle"></i> Quên mật khẩu?
                </a>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Đăng nhập
            </button>
        </form>

        <div class="register-link">
            Bạn chưa có tài khoản?
            <a href="/block-sports-center/public/index.php?page=register">
                Đăng ký ngay
            </a>
        </div>

        <div class="login-footer">
            © 2025 Block Sports Center. All rights reserved.
        </div>

    </div>
</div>

</body>
</html>
// 