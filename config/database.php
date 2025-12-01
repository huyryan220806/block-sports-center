<?php
// config/database.php - Support localhost and Railway

// ✅ Railway provides DATABASE_URL in format:
// mysql://user:password@host:port/dbname

$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {
    // Parse Railway DATABASE_URL
    $dbParts = parse_url($databaseUrl);
    
    return [
        'host'    => $dbParts['host'] ?? '127. 0.0.1',
        'db'      => ltrim($dbParts['path'] ?? '/block_sports_center', '/'),
        'user'    => $dbParts['user'] ?? 'root',
        'pass'    => $dbParts['pass'] ?? '',
        'charset' => 'utf8mb4',
        'port'    => $dbParts['port'] ?? 3306,
    ];
} else {
    // Localhost configuration
    return [
        'host'    => getenv('DB_HOST') ?: '127.0.0.1',
        'db'      => getenv('DB_NAME') ?: 'block_sports_center',
        'user'    => getenv('DB_USER') ?: 'root',
        'pass'    => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
        'port'    => getenv('DB_PORT') ?: 3306,
    ];
}