<?php
/**
 * app/views/auth/forgot-password.php
 * Trang quên mật khẩu
 * Updated: 2025-11-18 13:01:34 UTC
 * Fixed: Icon chìa khóa đơn giản (không có vòng tròn)
 * Author: @huyryan220806
 */

// Nếu đã đăng nhập → redirect
if (isset($_SESSION['user_id'])) {
    header('Location: /block-sports-center/public/index.php?c=dashboard&a=index');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - BLOCK SPORTS CENTER</title>
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

        .forgot-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #7FFFD4 0%, #5FD9B4 100%);
            padding: 20px;
        }

        .forgot-box {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 460px;
            padding: 40px;
        }

        .forgot-header {
            text-align: center;
            margin-bottom: 32px;
        }

        /* ✅ ICON ĐƠN GIẢN - KHÔNG CÓ VÒNG TRÒN */
        .forgot-icon {
            margin: 0 auto 20px;
            text-align: center;
        }

        .forgot-icon i {
            font-size: 64px;
            color: var(--primary);
        }

        .forgot-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
        }

        .forgot-header p {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.6;
        }

        .success-msg,
        .error-msg {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease-out;
        }

        .success-msg {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-msg {
            background: #ffe0e0;
            color: #c0392b;
            border: 1px solid #e74c3c;
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

        .btn-submit {
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

        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 184, 148, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-login a {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-to-login a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .info-box {
            background: #f0f7f4;
            border-left: 4px solid var(--primary);
            padding: 14px;
            border-radius: 8px;
            margin-top: 24px;
        }

        .info-box p {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.6;
            margin: 0;
        }

        .info-box strong {
            color: var(--text-primary);
        }

        @media (max-width: 768px) {
            .forgot-box {
                padding: 30px 20px;
            }

            .forgot-icon i {
                font-size: 48px;
            }

            .forgot-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="forgot-container">
    <div class="forgot-box">
        <div class="forgot-header">
            <!-- ✅ ICON ĐƠN GIẢN - CHỈ CÓ HÌNH CHÌA KHÓA -->
            <div class="forgot-icon">
                <i class="fas fa-key"></i>
            </div>
            <h1>Quên mật khẩu?</h1>
            <p>Nhập email của bạn, chúng tôi sẽ gửi hướng dẫn đặt lại mật khẩu</p>
        </div>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="success-msg">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="/block-sports-center/public/index.php?page=handle-forgot-password">
            <div class="form-group">
                <label class="form-label">Email của bạn</label>
                <div class="icon-input">
                    <i class="fas fa-envelope"></i>
                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Nhập địa chỉ email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required
                           autofocus>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Gửi link đặt lại mật khẩu
            </button>
        </form>

        <div class="back-to-login">
            <a href="/block-sports-center/public/index.php?page=login">
                <i class="fas fa-arrow-left"></i> Quay lại đăng nhập
            </a>
        </div>

        <div class="info-box">
            <p>
                <i class="fas fa-info-circle"></i>
                <strong>Lưu ý:</strong> Link đặt lại mật khẩu sẽ được gửi đến email của bạn. 
                Nếu không thấy email, vui lòng kiểm tra thư mục spam.
            </p>
        </div>
    </div>
</div>

</body>
</html>