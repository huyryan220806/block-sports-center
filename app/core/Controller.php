<?php
// app/core/Controller.php - BASE CONTROLLER

class Controller {
    
    public function view($view, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "<div style='font-family: Arial; padding: 20px; background: #fff3cd; border-left: 5px solid #ff9800;'>
                    <h2 style='color: #856404;'>⚠️ View không tồn tại</h2>
                    <p><strong>View:</strong> <code>$view</code></p>
                    <p><strong>Đường dẫn:</strong> <code>$viewFile</code></p>
                    <hr>
                    <h3>💡 Backend đã sẵn sàng truyền data:</h3>
                    <pre style='background: #f5f5f5; padding: 10px;'>";
            print_r($data);
            echo "</pre>
                    <p><strong>Team UI</strong> cần tạo file: <code>app/views/$view.php</code></p>
                  </div>";
        }
    }
    
    public function redirect($url) {
        header("Location: " . $url);
        exit;
    }
    
    public function setFlash($key, $message) {
        $_SESSION['flash'][$key] = $message;
    }
    
    public function getFlash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
}