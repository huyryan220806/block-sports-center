<?php



// public/index.php - ROUTER CHÍNH (FIXED)

session_start();

// Hiển thị lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DEBUG MODE - Bật để xem routing
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    echo "<!-- DEBUG: index.php started -->\n";
    echo "<!-- Controller: " . ($_GET['c'] ?? 'không có') . " -->\n";
    echo "<!-- Action: " . ($_GET['a'] ?? 'không có') . " -->\n";
}

// Load config
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';

// Load core classes
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Helpers.php';
require_once __DIR__ . '/../app/core/App.php';

// Khởi động ứng dụng
$app = new App();