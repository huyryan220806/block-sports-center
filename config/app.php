<?php
// config/app. php - Dynamic configuration for localhost and Railway

// ✅ Auto-detect protocol (http or https)
$protocol = 'http';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $protocol = 'https';
}

// ✅ Auto-detect host and base path
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ??  '/index.php';
$basePath = str_replace('/index. php', '', dirname($scriptName));
$basePath = ($basePath === '/' || $basePath === '\\') ? '' : $basePath;

// ✅ Dynamic BASE_URL
define('BASE_URL', $protocol . '://' . $host . $basePath . '/');
define('APP_NAME', 'Block Sports Center');

date_default_timezone_set('Asia/Ho_Chi_Minh');

// ✅ Helper functions for generating URLs and asset paths
if (!function_exists('url')) {
    function url($path = '') {
        return BASE_URL . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset($path = '') {
        return BASE_URL . 'assets/' . ltrim($path, '/');
    }
}