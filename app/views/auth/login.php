<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - BLOCK SPORTS CENTER</title>

    <!-- CSS DỰ ÁN MỚI -->
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
            max-width: 420px;
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
            color: var(--text-secondary);
            font-size: 14px;
            margin-top: 8px;
        }
        
        .login-form {
            margin-top: 32px;
        }
        
        .login-form .form-group {
            margin-bottom: 24px;
        }
        
        .login-form .form-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            display: block;
        }
        
        .login-form .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .login-form .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(127, 255, 212, 0.15);
        }
        
        .login-form .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }
        
        .login-form .btn-login:hover {
            background: var(--primary-dark);
            box-shadow: 0 4px 16px rgba(127, 255, 212, 0.3);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 24px;
            color: var(--text-secondary);
            font-size: 13px;
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
            
            <!-- ACTION ĐỔI SANG index.php?page=login -->
            <form method="post" action="/block-sports-center/public/index.php?page=login" class="login-form">
                <div class="form-group">
                    <label class="form-label">Tên đăng nhập hoặc Email</label>
                    <div class="icon-input">
                        <i class="fas fa-user"></i>
                        <input type="text" 
                               name="username" 
                               class="form-control" 
                               placeholder="Nhập username hoặc email"
                               required>
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
                
                <button type="submit" class="btn-login">
                    Đăng nhập
                </button>
            </form>
            
            <div style="text-align:center; margin-top: 15px; font-size: 14px;">
                 Bạn chưa có tài khoản?
                <a href="/block-sports-center/public/index.php?page=register" 
                   style="color: var(--primary); font-weight: 600; text-decoration: underline;"
                   onmouseover="this.style.opacity='0.8'" 
                   onmouseout="this.style.opacity='1'">
                     Đăng ký
                </a>
            </div>

            <div class="login-footer">
                © 2025 Block Sports Center. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>