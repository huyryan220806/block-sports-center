<?php

class Controller {
    
    /**
     * Load view file và truyền data
     * ✅ ĐỔI TÊN: view() → render()
     * 
     * @param string $view - Đường dẫn view (VD: 'invoices/index')
     * @param array $data - Dữ liệu truyền vào view
     */
      public function view($view, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "<div style='font-family: Arial; padding: 20px; background: #fff3cd; border-left: 5px solid #ff9800;'>
                    <h2 style='color: #856404;'>⚠️ View không tồn tại</h2>
                    <p><strong>View:</strong> <code>{$view}</code></p>
                    <p><strong>Đường dẫn:</strong> <code>{$viewFile}</code></p>
                    <hr>
                    <h3>💡 Backend đã sẵn sàng truyền data:</h3>
                    <pre style='background: #f5f5f5; padding: 10px; border-radius: 4px;'>";
            print_r($data);
            echo "</pre>
                    <p><strong>Team UI</strong> cần tạo file: <code>app/views/{$view}.php</code></p>
                  </div>";
        }
    }
    
    
    /**
     * Redirect đến URL khác
     * 
     * @param string $url - URL đích
     */
    public function redirect($url) {
        header("Location: " . $url);
        exit;
    }
    
    /**
     * Lưu flash message vào session
     * 
     * @param string $key - Key lưu (success, error, warning, info)
     * @param string $message - Nội dung message
     */
    public function setFlash($key, $message) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[$key] = $message;
    }
    
    /**
     * Lấy flash message từ session (và xóa sau khi lấy)
     * 
     * @param string $key - Key cần lấy
     * @return string|null
     */
    public function getFlash($key) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION[$key])) {
            $message = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $message;
        }
        return null;
    }
    
    /**
     * Lấy giá trị từ $_GET
     * 
     * @param string $key - Key cần lấy
     * @param mixed $default - Giá trị mặc định nếu không tồn tại
     * @return mixed
     */
    protected function get($key, $default = null) {
        return $_GET[$key] ?? $default;
    }
    
    /**
     * Lấy giá trị từ $_POST
     * 
     * @param string $key - Key cần lấy
     * @param mixed $default - Giá trị mặc định nếu không tồn tại
     * @return mixed
     */
    protected function post($key, $default = null) {
        return $_POST[$key] ?? $default;
    }
    
    /**
     * Kiểm tra request method
     * 
     * @param string $method - GET, POST, PUT, DELETE, PATCH
     * @return bool
     */
    protected function isMethod($method) {
        return strtoupper($_SERVER['REQUEST_METHOD']) === strtoupper($method);
    }
    
    /**
     * Kiểm tra request có phải AJAX không
     * 
     * @return bool
     */
    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
               && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Trả về JSON response
     * 
     * @param mixed $data - Dữ liệu trả về
     * @param int $statusCode - HTTP status code
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Validate dữ liệu required
     * 
     * @param array $fields - ['field1', 'field2', ...]
     * @param array $data - $_POST hoặc $_GET
     * @return array - Mảng lỗi (rỗng nếu hợp lệ)
     */
    protected function validateRequired($fields, $data) {
        $errors = [];
        
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "Trường {$field} là bắt buộc!";
            }
        }
        
        return $errors;
    }
    
    /**
     * Sanitize dữ liệu đầu vào (chống XSS)
     * 
     * @param string|array $data
     * @return string|array
     */
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Kiểm tra user đã đăng nhập chưa
     * 
     * @return bool
     */
    protected function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Kiểm tra user có role ADMIN không
     * 
     * @return bool
     */
    protected function isAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN';
    }
    
    /**
     * Bắt buộc đăng nhập (redirect nếu chưa login)
     */
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->setFlash('error', 'Vui lòng đăng nhập để tiếp tục!');
            $this->redirect('/block-sports-center/public/index.php?page=login');
        }
    }
    
    /**
     * Bắt buộc quyền ADMIN (redirect nếu không phải admin)
     */
    protected function requireAdmin() {
        $this->requireLogin();
        
        if (!$this->isAdmin()) {
            $this->setFlash('error', 'Bạn không có quyền truy cập!');
            $this->redirect('/block-sports-center/public/index.php?page=user');
        }
    }
    
    /**
     * Upload file
     * 
     * @param array $file - $_FILES['fieldname']
     * @param string $uploadDir - Thư mục upload (VD: 'uploads/images/')
     * @param array $allowedTypes - Các MIME type cho phép
     * @param int $maxSize - Kích thước tối đa (bytes)
     * @return array ['success' => bool, 'filename' => string|null, 'error' => string|null]
     */
    protected function uploadFile($file, $uploadDir = 'uploads/', $allowedTypes = ['image/jpeg', 'image/png'], $maxSize = 2097152) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Lỗi khi upload file!'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'File quá lớn! Tối đa ' . ($maxSize / 1024 / 1024) . 'MB'];
        }
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'error' => 'Loại file không được phép!'];
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        
        $fullPath = __DIR__ . '/../../public/' . $uploadDir;
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
        
        $destination = $fullPath . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => true, 'filename' => $uploadDir . $filename];
        }
        
        return ['success' => false, 'error' => 'Không thể lưu file!'];
    }
    
    /**
     * Phân trang đơn giản
     * 
     * @param int $total - Tổng số bản ghi
     * @param int $perPage - Số bản ghi mỗi trang
     * @param int $currentPage - Trang hiện tại
     * @return array ['offset' => int, 'limit' => int, 'totalPages' => int]
     */
    protected function paginate($total, $perPage = 10, $currentPage = 1) {
        $totalPages = ceil($total / $perPage);
        
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages) $currentPage = $totalPages;
        
        $offset = ($currentPage - 1) * $perPage;
        
        return [
            'offset' => $offset,
            'limit' => $perPage,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage
        ];
    }
}