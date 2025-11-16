<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - BLOCK SPORTS CENTER</title>

    <!-- CSS dự án (giữ y như file login.php của bạn) -->
    <link rel="stylesheet" href="/block-sports-center/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
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
            margin-bottom: 32px;
        }
        
        .login-logo h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-top: 16px;
        }
        
        .login-logo p {
            font-size: 14px;
            color: var(--text-secondary);
            margin-top: 4px;
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
        
        .login-form {
            margin-top: 8px;
        }
        
        .login-form .form-group {
            margin-bottom: 18px;
        }
        
        .login-form .form-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
            display: block;
        }
        
        .login-form .form-control {
            width: 100%;
            padding: 10px 16px;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .login-form .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(127, 255, 212, 0.35);
        }
        
        .btn-login {
            width: 100%;
            border: none;
            outline: none;
            padding: 12px 18px;
            border-radius: 999px;
            background-color: var(--primary);
            color: white;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }
        
        .btn-login:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }
        
        .login-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: var(--text-secondary);
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
        }

        .icon-input .form-control {
            padding-left: 44px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-logo">
                <div style="font-size: 64px; color: var(--primary-dark);">
                    <i class="fas fa-dumbbell"></i>
                </div>
                <h1>BLOCK SPORTS CENTER</h1>
                <p>Hệ thống quản lý trung tâm thể thao</p>
            </div>

            <div>
                <div class="login-title">Tạo tài khoản mới</div>
                <div class="login-subtitle">
                    Điền đầy đủ thông tin bên dưới để đăng ký tài khoản quản lý.
                </div>
            </div>

            <form class="login-form" method="post" action="">
                <!-- Họ và tên -->
                <div class="form-group">
                    <label class="form-label" for="fullname">Họ và tên</label>
                    <div class="icon-input">
                        <i class="fas fa-user"></i>
                        <input 
                            type="text" 
                            id="fullname" 
                            name="fullname" 
                            class="form-control" 
                            placeholder="Nguyễn Văn A" 
                            required>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="icon-input">
                        <i class="fas fa-envelope"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="you@example.com" 
                            required>
                    </div>
                </div>

                <!-- Số điện thoại -->
                <div class="form-group">
                    <label class="form-label" for="phone">Số điện thoại</label>
                    <div class="icon-input">
                        <i class="fas fa-phone"></i>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-control" 
                            placeholder="Ví dụ: 0912345678">
                    </div>
                </div>

                <!-- Tên đăng nhập -->
                <div class="form-group">
                    <label class="form-label" for="username">Tên đăng nhập</label>
                    <div class="icon-input">
                        <i class="fas fa-user-circle"></i>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-control" 
                            placeholder="Tên đăng nhập" 
                            required>
                    </div>
                </div>

                <!-- Mật khẩu -->
                <div class="form-group">
                    <label class="form-label" for="password">Mật khẩu</label>
                    <div class="icon-input">
                        <i class="fas fa-lock"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="Nhập mật khẩu" 
                            required>
                    </div>
                </div>

                <!-- Xác nhận mật khẩu -->
                <div class="form-group">
                    <label class="form-label" for="password_confirm">Xác nhận mật khẩu</label>
                    <div class="icon-input">
                        <i class="fas fa-lock"></i>
                        <input 
                            type="password" 
                            id="password_confirm" 
                            name="password_confirm" 
                            class="form-control" 
                            placeholder="Nhập lại mật khẩu" 
                            required>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    Đăng ký
                </button>
            </form>

            <!-- Link về đăng nhập -->
            <div style="text-align:center; margin-top: 15px; font-size: 14px;">
                Bạn đã có tài khoản?
                <a href="/block-sports-center/public/index.php?page=login"
                   style="color: var(--primary); font-weight: 600; text-decoration: underline;">
                    Đăng nhập
                </a>
            </div>

            <div class="login-footer">
                © 2025 Block Sports Center. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>