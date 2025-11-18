<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';

class AuthController extends Controller
{
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Hiển thị form đăng nhập
     */
    public function login() {
        if ($this->isLoggedIn()) {
            if ($this->isAdmin()) {
                $this->redirect('?c=dashboard&a=index');
            } else {
                $this->redirect('?page=user');
            }
            return;
        }
        
        $this->view('auth/login');
    }
    
    /**
     * Xử lý đăng nhập
     */
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=auth&a=login');
            return;
        }
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $this->setFlash('error', 'Vui lòng nhập đầy đủ thông tin!');
            $this->redirect('?c=auth&a=login');
            return;
        }
        
        try {
            // Query tìm user theo username HOẶC email
            $query = "SELECT * FROM users WHERE username = :username OR email = :username LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Kiểm tra password
                if (password_verify($password, $user['password_hash'])) {
                    // ✅ Đăng nhập thành công
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['fullname'] ?? $user['username'];
                    $_SESSION['email'] = $user['email'] ?? '';
                    $_SESSION['phone'] = $user['phone'] ?? '';
                    $_SESSION['role'] = $user['role'] ?? 'USER';
                    $_SESSION['logged_in'] = true;
                    
                    $this->setFlash('success', 'Đăng nhập thành công! Chào mừng ' . $user['username']);
                    
                    // Redirect after login
                    if (isset($_SESSION['redirect_after_login'])) {
                        $redirect = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']);
                        $this->redirect($redirect);
                    } else {
                        if ($user['role'] === 'ADMIN') {
                            $this->redirect('?c=dashboard&a=index');
                        } else {
                            $this->redirect('?page=user');
                        }
                    }
                    return;
                }
            }
            
            // Đăng nhập thất bại
            $this->setFlash('error', 'Tên đăng nhập hoặc mật khẩu không đúng!');
            $this->redirect('?c=auth&a=login');
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $this->setFlash('error', 'Lỗi hệ thống: ' . $e->getMessage());
            $this->redirect('?c=auth&a=login');
        }
    }
    
    /**
     * Đăng xuất
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION = [];
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        session_destroy();
        
        session_start();
        $this->setFlash('success', 'Đăng xuất thành công!');
        
        $this->redirect('?c=auth&a=login');
    }
    
    /**
     * Hiển thị form đăng ký
     */
    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirect('?c=dashboard&a=index');
            return;
        }
        
        $this->view('auth/register');
    }
    
    /**
     * Xử lý đăng ký
     */
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=auth&a=register');
            return;
        }
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $fullname = trim($_POST['fullname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        
        // Validate
        if (empty($username) || empty($email) || empty($password)) {
            $this->setFlash('error', 'Vui lòng nhập đầy đủ thông tin bắt buộc!');
            $this->redirect('?c=auth&a=register');
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Email không hợp lệ!');
            $this->redirect('?c=auth&a=register');
            return;
        }
        
        if ($password !== $confirmPassword) {
            $this->setFlash('error', 'Mật khẩu xác nhận không khớp!');
            $this->redirect('?c=auth&a=register');
            return;
        }
        
        if (strlen($password) < 6) {
            $this->setFlash('error', 'Mật khẩu phải có ít nhất 6 ký tự!');
            $this->redirect('?c=auth&a=register');
            return;
        }
        
        try {
            // Kiểm tra username hoặc email đã tồn tại
            $checkQuery = "SELECT * FROM users WHERE username = :username OR email = :email LIMIT 1";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->execute([
                ':username' => $username,
                ':email' => $email
            ]);
            
            if ($checkStmt->rowCount() > 0) {
                $this->setFlash('error', 'Tên đăng nhập hoặc email đã tồn tại!');
                $this->redirect('?c=auth&a=register');
                return;
            }
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user mới
            $insertQuery = "INSERT INTO users (username, email, password_hash, fullname, phone, role) 
                           VALUES (:username, :email, :password, :fullname, :phone, 'USER')";
            
            $insertStmt = $this->db->prepare($insertQuery);
            $insertStmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':fullname' => $fullname,
                ':phone' => $phone
            ]);
            
            $this->setFlash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
            $this->redirect('?c=auth&a=login');
            
        } catch (Exception $e) {
            error_log("Register error: " . $e->getMessage());
            $this->setFlash('error', 'Lỗi hệ thống: ' . $e->getMessage());
            $this->redirect('?c=auth&a=register');
        }
    }
    
    /**
     * Hiển thị form quên mật khẩu
     */
    public function forgotPassword() {
        if ($this->isLoggedIn()) {
            $this->redirect('?c=dashboard&a=index');
            return;
        }
        
        $this->view('auth/forgot-password');
    }
    
    /**
     * Xử lý quên mật khẩu
     */
    public function handleForgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=auth&a=forgotPassword');
            return;
        }
        
        $email = trim($_POST['email'] ?? '');
        
        if (empty($email)) {
            $this->setFlash('error', 'Vui lòng nhập địa chỉ email!');
            $this->redirect('?c=auth&a=forgotPassword');
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Địa chỉ email không hợp lệ!');
            $this->redirect('?c=auth&a=forgotPassword');
            return;
        }
        
        try {
            // Kiểm tra email có tồn tại không
            $query = "SELECT id, username, email FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Tạo token reset (trong thực tế cần tạo bảng password_resets)
                // Hiện tại chỉ log để test
                error_log("Password reset requested for user ID: {$user['id']}, email: {$email}");
                
                // TODO: Tạo token, lưu vào DB, gửi email
            }
            
            // Luôn hiển thị thông báo thành công (security - không lộ email có tồn tại hay không)
            $this->setFlash('success', 'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi hướng dẫn đặt lại mật khẩu đến hộp thư của bạn!');
            $this->redirect('?c=auth&a=forgotPassword');
            
        } catch (Exception $e) {
            error_log("Forgot password error: " . $e->getMessage());
            $this->setFlash('error', 'Lỗi hệ thống: ' . $e->getMessage());
            $this->redirect('?c=auth&a=forgotPassword');
        }
    }
}