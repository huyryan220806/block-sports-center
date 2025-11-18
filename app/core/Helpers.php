<?php

/**
 * Format số tiền VND
 */
if (!function_exists('formatMoney')) {
    function formatMoney($amount) {
        if ($amount === null || $amount === '') {
            return '0đ';
        }
        
        $amount = (float)$amount;
        
        return number_format($amount, 0, ',', '.') . 'đ';
    }
}

/**
 * Format ngày giờ
 */
if (!function_exists('formatDate')) {
    function formatDate($datetime, $format = 'd/m/Y H:i') {
        if (empty($datetime)) return '';
        return date($format, strtotime($datetime));
    }
}

/**
 * Format ngày giờ ngắn gọn
 */
if (!function_exists('formatDateShort')) {
    function formatDateShort($datetime) {
        if (empty($datetime)) return '';
        return date('d/m/Y', strtotime($datetime));
    }
}

/**
 * Escape HTML
 */
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Debug helper - dump and die
 */
if (!function_exists('dd')) {
    function dd(...$vars) {
        echo '<pre style="background: #1e1e1e; color: #dcdcdc; padding: 20px; border-radius: 8px; margin: 20px; font-family: monospace;">';
        foreach ($vars as $var) {
            var_dump($var);
            echo '<hr style="border: 1px solid #444; margin: 10px 0;">';
        }
        echo '</pre>';
        die();
    }
}

/**
 * Get badge HTML cho trạng thái hóa đơn
 */
if (!function_exists('getInvoiceStatusBadge')) {
    function getInvoiceStatusBadge($status) {
        $mapping = [
            'DRAFT'    => ['class' => 'inactive', 'text' => 'Nháp'],
            'ISSUED'   => ['class' => 'scheduled', 'text' => 'Đã xuất'],
            'PAID'     => ['class' => 'active', 'text' => 'Đã thanh toán'],
            'PARTIAL'  => ['class' => 'full', 'text' => 'Thanh toán 1 phần'],
            'VOID'     => ['class' => 'suspended', 'text' => 'Đã hủy'],
        ];
        
        $badge = $mapping[strtoupper($status)] ?? ['class' => 'inactive', 'text' => 'Không xác định'];
        
        return $badge;
    }
}

/**
 * Get badge HTML cho trạng thái chung
 */
if (!function_exists('getStatusBadge')) {
    function getStatusBadge($status) {
        $mapping = [
            'ACTIVE'    => ['class' => 'active', 'text' => 'Hoạt động'],
            'INACTIVE'  => ['class' => 'inactive', 'text' => 'Ngừng'],
            'SUSPENDED' => ['class' => 'suspended', 'text' => 'Tạm khóa'],
            'PENDING'   => ['class' => 'scheduled', 'text' => 'Chờ xử lý'],
        ];
        
        $badge = $mapping[strtoupper($status)] ?? ['class' => 'inactive', 'text' => 'Không xác định'];
        
        return $badge;
    }
}

/**
 * Tính tổng tiền từ items
 */
if (!function_exists('calculateTotal')) {
    function calculateTotal($items) {
        $total = 0;
        if (is_array($items)) {
            foreach ($items as $item) {
                if (is_object($item)) {
                    $total += ($item->SOLUONG ?? 0) * ($item->DONGIA ?? 0);
                } else {
                    $total += ($item['SOLUONG'] ?? 0) * ($item['DONGIA'] ?? 0);
                }
            }
        }
        return $total;
    }
}

/**
 * Tính tiền giảm giá
 */
if (!function_exists('calculateDiscount')) {
    function calculateDiscount($subtotal, $discountType, $discountValue) {
        if (empty($discountValue)) return 0;
        
        if (strtoupper($discountType) === 'PERCENT') {
            return $subtotal * $discountValue / 100;
        }
        
        return $discountValue;
    }
}

/**
 * Format số điện thoại
 */
if (!function_exists('formatPhone')) {
    function formatPhone($phone) {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) === 10) {
            return substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7);
        }
        
        return $phone;
    }
}

/**
 * Truncate text
 */
if (!function_exists('str_limit')) {
    function str_limit($text, $limit = 100, $end = '...') {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }
        
        return mb_substr($text, 0, $limit) . $end;
    }
}

/**
 * Asset URL helper
 */
if (!function_exists('asset')) {
    function asset($path) {
        return '/block-sports-center/public/' . ltrim($path, '/');
    }
}

/**
 * URL helper
 */
if (!function_exists('url')) {
    function url($path = '') {
        return '/block-sports-center/public/index.php' . ($path ? '?' . ltrim($path, '?') : '');
    }
}