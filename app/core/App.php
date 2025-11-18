<?php

class App {
    protected $controller = 'DashboardController';
    protected $action = 'index';
    
    public function __construct() {
        
        $controllerParam = null;
        $actionParam = 'index';
        
        // ========================================
        // ĐỌC THAM SỐ URL - HỖ TRỢ 2 FORMAT
        // ========================================
        
        // Format 1: ?c=members&a=index (Backend format)
        if (isset($_GET['c']) && !empty($_GET['c'])) {
            $controllerParam = $_GET['c'];
            $actionParam = $_GET['a'] ?? 'index';
        }
        // Format 2: ?page=members (Team UI format)
        elseif (isset($_GET['page']) && !empty($_GET['page'])) {
            $page = $_GET['page'];
            
            // ✅ MAPPING TỪ FORMAT CŨ → MỚI
            $pageMap = [
                'members' => ['controller' => 'members', 'action' => 'index'],
                'members-create' => ['controller' => 'members', 'action' => 'create'],
                'members-edit' => ['controller' => 'members', 'action' => 'edit'],
                'members-delete' => ['controller' => 'members', 'action' => 'delete'],
                'rooms' => ['controller' => 'rooms', 'action' => 'index'],
                'bookings' => ['controller' => 'bookings', 'action' => 'index'],
                'classes' => ['controller' => 'classes', 'action' => 'index'],
                'trainers' => ['controller' => 'trainers', 'action' => 'index'],
                'dashboard' => ['controller' => 'dashboard', 'action' => 'index'],
                'user'      => ['controller' => 'user', 'action' => 'index'],
            ];
            
            if (isset($pageMap[$page])) {
                $controllerParam = $pageMap[$page]['controller'];
                $actionParam = $pageMap[$page]['action'];
            } else {
                // Fallback: Tự động tách tên
                $parts = explode('-', $page);
                if (count($parts) == 2) {
                    $controllerParam = $parts[0]; // members
                    $actionParam = $parts[1];     // create, edit
                } else {
                    $controllerParam = $page;
                    $actionParam = 'index';
                }
            }
            
            // Tạo $_GET['c'] và $_GET['a'] cho tương thích
            $_GET['c'] = $controllerParam;
            $_GET['a'] = $actionParam;
        }
        
        // ========================================
        // TẠO BIẾN GLOBAL $currentPage
        // ========================================
        $GLOBALS['currentPage'] = $controllerParam ?? 'dashboard';
        
        // ========================================
        // XÁC ĐỊNH TÊN CONTROLLER VÀ FILE
        // ========================================
        $controllerName = 'DashboardController';
        $controllerFile = __DIR__ . '/../controllers/DashboardController.php';
        
        if (!empty($controllerParam)) {
            $controllerName = ucfirst(strtolower($controllerParam)) . 'Controller';
            $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
        }
        
        // ========================================
        // KIỂM TRA FILE TỒN TẠI
        // ========================================
        if (!file_exists($controllerFile)) {
            die("
                <div style='font-family: Arial; padding: 20px; background: #ffe6e6; border-left: 5px solid #ff0000;'>
                    <h2 style='color: #d00;'>❌ Controller không tồn tại</h2>
                    <p><strong>Controller:</strong> <code>$controllerName</code></p>
                    <p><strong>File:</strong> <code>$controllerFile</code></p>
                    <p><strong>URL:</strong> <code>" . htmlspecialchars($_SERVER['REQUEST_URI']) . "</code></p>
                    <hr>
                    <h3>💡 Giải pháp:</h3>
                    <p>1. Tạo file: <code>app/controllers/$controllerName.php</code></p>
                    <p>2. Hoặc kiểm tra tên file có đúng không?</p>
                </div>
            ");
        }
        
        // ========================================
        // ✅ REQUIRE FILE CONTROLLER
        // ========================================
        require_once $controllerFile;
        
        // ========================================
        // KIỂM TRA CLASS TỒN TẠI
        // ========================================
        if (!class_exists($controllerName)) {
            die("
                <div style='font-family: Arial; padding: 20px; background: #ffe6e6; border-left: 5px solid #ff0000;'>
                    <h2 style='color: #d00;'>❌ Class không tồn tại trong file</h2>
                    <p><strong>Class:</strong> <code>$controllerName</code></p>
                    <p><strong>File:</strong> <code>$controllerFile</code></p>
                    <hr>
                    <h3>💡 Giải pháp:</h3>
                    <p>Mở file <code>$controllerFile</code></p>
                    <p>Đảm bảo có dòng: <code>class $controllerName extends Controller {</code></p>
                </div>
            ");
        }
        
        // ========================================
        // ✅ KHỞI TẠO CONTROLLER
        // ========================================
        $this->controller = new $controllerName();
        
        // ========================================
        // KIỂM TRA ACTION TỒN TẠI
        // ========================================
        if (!method_exists($this->controller, $actionParam)) {
            die("
                <div style='font-family: Arial; padding: 20px; background: #ffe6e6; border-left: 5px solid #ff0000;'>
                    <h2 style='color: #d00;'>❌ Action không tồn tại</h2>
                    <p><strong>Action:</strong> <code>$actionParam()</code></p>
                    <p><strong>Controller:</strong> <code>$controllerName</code></p>
                    <hr>
                    <h3>💡 Giải pháp:</h3>
                    <p>Thêm method vào Controller:</p>
                    <pre style='background: #f5f5f5; padding: 10px;'>
public function $actionParam() {
    // Code here
}
                    </pre>
                </div>
            ");
        }
        
        $this->action = $actionParam;
        
        // ========================================
        // ✅ GỌI ACTION
        // ========================================
        call_user_func([$this->controller, $this->action]);
    }
}