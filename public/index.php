<?php
// public/index.php - ROUTER CHÍNH
// Updated: 2025-12-01
// Fixed: Dynamic paths for Railway deployment
// Author: @huyryan220806

// Bật session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hiển thị lỗi khi debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load config
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';

// Load core classes
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Helpers.php';
require_once __DIR__ .  '/../app/core/App.php';
require_once __DIR__ . '/../app/core/AreaHelper.php';

// ✅ HÀM TẠO URL ĐỘNG - HOẠT ĐỘNG CẢ LOCALHOST VÀ RAILWAY
function url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
    return $protocol . '://' . $host . $scriptName . '/' .  ltrim($path, '/');
}

// Lấy tham số
$page = $_GET['page'] ??  '';
$controller = $_GET['c'] ?? '';
$action = $_GET['a'] ?? '';

/**
 * Hàm lấy PDO connection
 */
function get_pdo() {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = database::getInstance()->getConnection();
    }
    return $pdo;
}

/* =====================================================
 * ===============  ĐĂNG XUẤT  =========================
 * ===================================================*/
if ($page === 'logout') {
    $_SESSION = [];
    
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    
    session_destroy();
    
    header('Location: ' . url('? page=login'));
    exit;
}

/* =====================================================
 * ===============  ĐĂNG NHẬP  =========================
 * ===================================================*/
if ($page === 'login') {
    // Nếu đã đăng nhập → redirect
    if (isset($_SESSION['user_id'])) {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN') {
            header('Location: ' . url('? c=dashboard&a=index'));
        } else {
            header('Location: ' . url('? page=user'));
        }
        exit;
    }

    // Xử lý POST login
    $loginError = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usernameOrEmail = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($usernameOrEmail) || empty($password)) {
            $loginError = '❌ Vui lòng nhập đầy đủ thông tin!';
        } else {
            try {
                $pdo = get_pdo();
                
                $sql = 'SELECT * FROM users WHERE username = :username OR email = :email LIMIT 1';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':username' => $usernameOrEmail,
                    ':email' => $usernameOrEmail
                ]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password_hash'])) {
                    // ✅ LƯU SESSION
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['fullname'] = $user['fullname'] ?? $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    // ✅ REDIRECT THEO ROLE
                    if ($user['role'] === 'ADMIN') {
                        header('Location: ' . url('?c=dashboard&a=index'));
                    } else {
                        header('Location: ' . url('?page=user'));
                    }
                    exit;
                } else {
                    $loginError = '❌ Tên đăng nhập hoặc mật khẩu không chính xác! ';
                }
            } catch (Exception $e) {
                $loginError = '❌ Lỗi hệ thống: ' . $e->getMessage();
            }
        }
    }

    // Hiển thị form login
    include __DIR__ . '/../app/views/auth/login.php';
    exit;
}

/* =====================================================
 * ===============  ĐĂNG KÝ  ===========================
 * ===================================================*/
if ($page === 'register') {
    // Nếu đã đăng nhập → redirect
    if (isset($_SESSION['user_id'])) {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN') {
            header('Location: ' . url('?c=dashboard&a=index'));
        } else {
            header('Location: ' . url('?page=user'));
        }
        exit;
    }

    // Include file register
    include __DIR__ . '/../app/views/auth/register.php';
    exit;
}

if ($page === 'forgot-password') {
    // Hiển thị form quên mật khẩu
    include __DIR__ . '/../app/views/auth/forgot-password.php';
    exit;
}

// ✅ XỬ LÝ FORM SUBMIT QUÊN MẬT KHẨU
if ($page === 'handle-forgot-password') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        
        if (empty($email)) {
            $_SESSION['error'] = 'Vui lòng nhập địa chỉ email!';
            header('Location: ' . url('?page=forgot-password'));
            exit;
        }
        
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Địa chỉ email không hợp lệ!';
            header('Location: ' . url('?page=forgot-password'));
            exit;
        }
        
        try {
            $pdo = get_pdo();
            
            // Kiểm tra email có tồn tại không
            $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // TODO: Tạo token reset và gửi email
                // Hiện tại chỉ log để test
                error_log("Password reset requested for email: {$email}");
            }
            
            // Luôn hiển thị thông báo thành công (security - không lộ email có tồn tại hay không)
            $_SESSION['success'] = 'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi hướng dẫn đặt lại mật khẩu đến hộp thư của bạn!';
            header('Location: ' .  url('?page=forgot-password'));
            exit;
            
        } catch (Exception $e) {
            error_log("Forgot password error: " . $e->getMessage());
            $_SESSION['error'] = 'Lỗi hệ thống.  Vui lòng thử lại sau!';
            header('Location: ' . url('? page=forgot-password'));
            exit;
        }
    }
    
    header('Location: ' . url('?page=forgot-password'));
    exit;
}

/* =====================================================
 * ===============  ĐĂNG KÝ - BƯỚC 2  ==================
 * ===================================================*/
if ($page === 'register-member') {
    // Kiểm tra đã hoàn thành bước 1 chưa
    if (!isset($_SESSION['temp_user_id'])) {
        header('Location: ' . url('?page=register'));
        exit;
    }

    // ✅ HIỂN THỊ TRANG ĐĂNG KÝ HỘI VIÊN (BƯỚC 2)
    require_once __DIR__ . '/../app/views/auth/register-member.php';
    exit;
}

/* =====================================================
 * ===============  TRANG USER  ========================
 * ===================================================*/
if ($page === 'user') {
    // Kiểm tra đăng nhập
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . url('?page=login'));
        exit;
    }
    
    // Nếu là ADMIN thì đá về dashboard
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN') {
        header('Location: ' . url('?c=dashboard&a=index'));
        exit;
    }
    
    // Hiển thị trang user
    include __DIR__ . '/../app/views/user/index.php';
    exit;
}

/* =====================================================
 * ===  XỬ LÝ ADMIN ROUTING (c=... & a=...)  ========
 * ===================================================*/

// ✅ NẾU CÓ THAM SỐ ?c= (Controller) → ĐÂY LÀ ADMIN ROUTE
if (!empty($controller)) {
    // Kiểm tra đăng nhập
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . url('?page=login'));
        exit;
    }
    
    // Kiểm tra quyền ADMIN
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ADMIN') {
        // Nếu không phải ADMIN → đá về trang user
        header('Location: ' . url('?page=user'));
        exit;
    }
    
    // ✅ GIAO CHO LỚP App XỬ LÝ (MVC)
    $app = new App();
    exit;
}

/* =====================================================
 * ===============  MẶC ĐỊNH: REDIRECT  ================
 * ===================================================*/

// Nếu chưa đăng nhập → về trang login
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . url('?page=login'));
    exit;
}

// Nếu đã đăng nhập nhưng không có tham số → điều hướng theo role
if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN') {
    header('Location: ' . url('?c=dashboard&a=index'));
} else {
    header('Location: ' . url('?page=user'));
}
exit;