<?php

class AreaHelper
{
    /**
     * Mapping từ LOAIKHU trong DB → class CSS
     */
    private static $areaMapping = [
        'GYM'     => ['class' => 'gym',        'name' => 'Gym',          'color' => '#FF6B6B'],
        'POOL'    => ['class' => 'pool',       'name' => 'Hồ Bơi',       'color' => '#4ECDC4'],
        'STUDIO'  => ['class' => 'studio',     'name' => 'Yoga',         'color' => '#A29BFE'],
        'BOXING'  => ['class' => 'boxing',     'name' => 'Boxing',       'color' => '#D63031'],
        'BOXN'    => ['class' => 'boxing',     'name' => 'Boxing',       'color' => '#D63031'],
        'FUTSAL'  => ['class' => 'futsal',     'name' => 'Futsal',       'color' => '#00B894'],
        'FUTL'    => ['class' => 'futsal',     'name' => 'Futsal',       'color' => '#00B894'],
        'VOLLEY'  => ['class' => 'volleyball', 'name' => 'Bóng Chuyền',  'color' => '#FDCB6E'],
        'VOLY'    => ['class' => 'volleyball', 'name' => 'Bóng Chuyền',  'color' => '#FDCB6E'],
        'BASKET'  => ['class' => 'basketball', 'name' => 'Bóng Rổ',      'color' => '#FD79A8'],
        'BSKT'    => ['class' => 'basketball', 'name' => 'Bóng Rổ',      'color' => '#FD79A8'],
        'BADMINT' => ['class' => 'badminton',  'name' => 'Cầu Lông',     'color' => '#55EFC4'],
        'BDMT'    => ['class' => 'badminton',  'name' => 'Cầu Lông',     'color' => '#55EFC4'],
        'FOOTBAL' => ['class' => 'football',   'name' => 'Bóng Đá',      'color' => '#74B9FF'],
        'FBAL'    => ['class' => 'football',   'name' => 'Bóng Đá',      'color' => '#74B9FF'],
        'PICKLE'  => ['class' => 'pickleball', 'name' => 'Pickleball',   'color' => '#FF7675'],
        'PICK'    => ['class' => 'pickleball', 'name' => 'Pickleball',   'color' => '#FF7675'],
    ];

    /**
     * Lấy thông tin badge theo LOAIKHU
     * 
     * @param string $loaikhu - Mã loại khu từ DB (VD: GYM, POOL, BOXING...)
     * @return array ['class' => 'gym', 'name' => 'Gym', 'color' => '#FF6B6B']
     */
    public static function getBadgeInfo($loaikhu)
    {
        $loaikhu = strtoupper(trim($loaikhu ?? ''));
        
        return self::$areaMapping[$loaikhu] ?? [
            'class' => 'other',
            'name'  => 'Khác',
            'color' => '#B2BEC3'
        ];
    }

    /**
     * Lấy tên hiển thị theo LOAIKHU
     * 
     * @param string $loaikhu
     * @return string
     */
    public static function getDisplayName($loaikhu)
    {
        $info = self::getBadgeInfo($loaikhu);
        return $info['name'];
    }

    /**
     * Lấy class CSS theo LOAIKHU
     * 
     * @param string $loaikhu
     * @return string
     */
    public static function getCssClass($loaikhu)
    {
        $info = self::getBadgeInfo($loaikhu);
        return $info['class'];
    }

    /**
     * Lấy màu hex theo LOAIKHU
     * 
     * @param string $loaikhu
     * @return string
     */
    public static function getColor($loaikhu)
    {
        $info = self::getBadgeInfo($loaikhu);
        return $info['color'];
    }

    /**
     * Render HTML badge
     * 
     * @param string $loaikhu
     * @param string $displayText (optional) - Text tùy chỉnh, mặc định lấy từ DB
     * @return string HTML
     */
    public static function renderBadge($loaikhu, $displayText = null)
    {
        $info = self::getBadgeInfo($loaikhu);
        $text = $displayText ?? $info['name'];
        $class = $info['class'];
        
        return sprintf(
            '<span class="area-badge %s">%s</span>',
            htmlspecialchars($class),
            htmlspecialchars($text)
        );
    }
}