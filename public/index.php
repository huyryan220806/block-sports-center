<?php
// public/index.php
// Front controller đơn giản cho BLOCK SPORTS CENTER

// Lấy tham số page, mặc định là dashboard
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Đường dẫn gốc tới thư mục views
$baseViewPath = __DIR__ . '/../app/views/';

switch ($page) {
    case 'dashboard':
        require $baseViewPath . 'dashboard/index.php';
        break;

    case 'members':
        require $baseViewPath . 'members/index.php';
        break;

    case 'rooms':
        require $baseViewPath . 'rooms/index.php';
        break;

    case 'lockers':
        require $baseViewPath . 'lockers/index.php';
        break;

    case 'classes':
        require $baseViewPath . 'classes/index.php';
        break;

    case 'sessions':
        require $baseViewPath . 'sessions/index.php';
        break;

    case 'bookings':
        require $baseViewPath . 'bookings/index.php';
        break;

    case 'invoices':
        require $baseViewPath . 'invoices/index.php';
        break;

    case 'reports':
        require $baseViewPath . 'reports/index.php';
        break;

    case 'trainers':
        require $baseViewPath . 'trainers/index.php';
        break;

    case 'settings':
        require $baseViewPath . 'settings/index.php';
        break;

    case 'login':
        require $baseViewPath . 'auth/login.php';
        break;

    case 'register':
        require __DIR__ . '/../app/views/auth/register.php';
        break;

    default:
        http_response_code(404);
        echo '404 - Page not found';
        break;
}