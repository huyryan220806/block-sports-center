<?php
// config/database.php - ONLY return config array, DO NOT create PDO

$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {
    // Parse Railway DATABASE_URL
    // Format: mysql://user:password@host:port/dbname
    $dbParts = parse_url($databaseUrl);
    
    return [
        'host'    => $dbParts['host'] ?? '127. 0.0.1',
        'port'    => $dbParts['port'] ?? 3306,
        'db'      => ltrim($dbParts['path'] ??  '/block_sports_center', '/'),
        'user'    => $dbParts['user'] ??  'root',
        'pass'    => $dbParts['pass'] ?? '',
        'charset' => 'utf8mb4',
    ];
} else {
    // Localhost or custom environment variables
    return [
        'host'    => getenv('DB_HOST') ?: '127.0.0.1',
        'port'    => (int)(getenv('DB_PORT') ?: 3306),
        'db'      => getenv('DB_NAME') ?: 'block_sports_center',
        'user'    => getenv('DB_USER') ?: 'root',
        'pass'    => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ];
}