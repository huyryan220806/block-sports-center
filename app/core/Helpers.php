<?php
// app/core/Helpers.php - HÀM HỖ TRỢ

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ?c=auth&a=login");
        exit;
    }
}

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function formatMoney($amount) {
    return number_format($amount, 0, ',', '.') . ' ₫';
}

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}