<?php
// config/database.php - Support localhost and Railway

$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {
    // Parse Railway DATABASE_URL
    $dbParts = parse_url($databaseUrl);
    
    $config = [
        'host'    => $dbParts['host'] ?? '127.0.0.1',
        'db'      => ltrim($dbParts['path'] ?? '/block_sports_center', '/'),
        'user'    => $dbParts['user'] ?? 'root',
        'pass'    => $dbParts['pass'] ?? '',
        'charset' => 'utf8mb4',
        'port'    => $dbParts['port'] ?? 3306,
    ];
} else {
    // Localhost or custom environment variables
    $config = [
        'host'    => getenv('DB_HOST') ?: '127.0.0.1',
        'db'      => getenv('DB_NAME') ?: 'block_sports_center',
        'user'    => getenv('DB_USER') ?: 'root',
        'pass'    => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
        'port'    => getenv('DB_PORT') ?: 3306,
    ];
}

// ✅ Create PDO connection
try {
    $dsn = sprintf(
        "mysql:host=%s;port=%d;dbname=%s;charset=%s",
        $config['host'],
        $config['port'],
        $config['db'],
        $config['charset']
    );
    
    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Success - you can use $pdo globally
    
} catch (PDOException $e) {
    // Log error and show user-friendly message
    error_log("Database connection failed: " . $e->getMessage());
    
    // Show detailed error only in development
    if (getenv('APP_ENV') === 'development') {
        die("❌ Lỗi kết nối database: " . $e->getMessage());
    } else {
        die("❌ Không thể kết nối database.  Vui lòng thử lại sau.");
    }
}

return $pdo;