<?php 
/**
 * app/views/invoices/packages.php
 * Gói tập & Khuyến mãi
 * Updated: 2025-12-01
 * Author: @huyryan220806
 */

$pageTitle   = 'Gói tập & Khuyến mãi';
$currentPage = 'invoices';

$packages   = $data['packages']   ?? [];
$promotions = $data['promotions'] ?? [];

// Helper functions
if (!function_exists('formatMoney')) {
    function formatMoney($amount) {
        return number_format($amount, 0, ',', '. ') . 'đ';
    }
}

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ??  '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('formatDateShort')) {
    function formatDateShort($date) {
        if (empty($date)) return 'N/A';
        return date('d/m/Y', strtotime($date));
    }
}
?>
<! DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - BLOCK SPORTS CENTER</title>
    <link rel="stylesheet" href="/block-sports-center/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs. cloudflare.com/ajax/libs/font-awesome/6. 4.0/css/all. min.css">
    
    <style>
        .package-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .package-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            border-color: #00B894;
        }
        
        .package-card.basic {
            border-left: 4px solid #74B9FF;
        }
        
        .package-card.standard {
            border-left: 4px solid #00B894;
        }
        
        .package-card.vip {
            border-left: 4px solid #FFD33D;
            background: linear-gradient(135deg, #FFF9E6 0%, #FFF 100%);
        }
        
        .promo-card {
            background: linear-gradient(135deg, #FFE5E5 0%, #FFF 100%);
            border-radius: 12px;
            padding: 20px;
            border: 2px dashed #E63946;
            margin-bottom: 16px;
            transition: all 0.3s;
        }
        
        .promo-card:hover {
            border-style: solid;
            box-shadow: 0 4px 12px rgba(230, 57, 70, 0.2);
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php include(__DIR__ . '/../layouts/sidebar.php'); ?>
        <main class="main-content">
            <?php include(__DIR__ . '/../layouts/header.php'); ?>
            <div class="content">
                <div class="page-header">
                    <h2><i class="fas fa-tags"></i> Gói tập & Khuyến mãi</h2>
                    <p>Danh sách các gói tập và chương trình khuyến mãi đang áp dụng</p>
                </div>
                
                <!-- GÓI TẬP -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-box"></i> Các gói tập (<?= count($packages) ?> gói)
                        </h3>
                        <button class="btn btn-ghost" onclick="location.href='? c=invoices&a=index'">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </button>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; padding: 20px;">
                        <?php if (!empty($packages)): ?>
                            <?php foreach ($packages as $pkg): ?>
                                <?php 
                                $levelClass = strtolower($pkg->CAPDO ?? 'basic');
                                $gia = $pkg->GIA ?? 0;
                                $thoihan = $pkg->THOIHAN ?? 1;
                                ?>
                                <div class="package-card <?= $levelClass ?>">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                                        <div>
                                            <span class="badge <?= $levelClass === 'vip' ? 'full' : ($levelClass === 'standard' ?  'active' : 'scheduled') ?>">
                                                <?= strtoupper($pkg->CAPDO ?? 'BASIC') ?>
                                            </span>
                                        </div>
                                        <div style="font-size: 28px; font-weight: 700; color: #00B894;">
                                            <?= formatMoney($gia) ?>
                                        </div>
                                    </div>
                                    
                                    <h3 style="font-size: 20px; margin-bottom: 8px; color: #2d3436;">
                                        <?= e($pkg->TENLG ??  'Gói tập') ?>
                                    </h3>
                                    
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px; color: #636e72;">
                                        <i class="fas fa-clock"></i>
                                        <span><?= $thoihan ?> ngày</span>
                                    </div>
                                    
                                    <p style="color: #636e72; font-size: 14px; line-height: 1.6;">
                                        <?= nl2br(e($pkg->MOTA ?? 'Không có mô tả')) ?>
                                    </p>
                                    
                                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                                        <div style="font-size: 13px; color: #999;">
                                            Giá/ngày: 
                                            <strong style="color: #00B894;">
                                                <?= formatMoney($thoihan > 0 ? $gia / $thoihan : 0) ?>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                                <i class="fas fa-box-open" style="font-size: 64px; color: #ddd; margin-bottom: 16px;"></i>
                                <p style="color: #999; font-size: 16px;">Chưa có gói tập nào trong hệ thống. </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- KHUYẾN MÃI -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-percent"></i> Chương trình khuyến mãi (<?= count($promotions) ?> chương trình)
                        </h3>
                    </div>
                    
                    <div style="padding: 20px;">
                        <?php if (!empty($promotions)): ?>
                            <?php foreach ($promotions as $km): ?>
                                <div class="promo-card">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                        <div style="flex: 1;">
                                            <div style="display: inline-block; background: #E63946; color: white; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 700; margin-bottom: 12px;">
                                                <?= e($km->CODE ?? '') ?>
                                            </div>
                                            
                                            <h4 style="font-size: 18px; margin-bottom: 8px; color: #2d3436;">
                                                <?= e($km->MOTA ?? 'Không có mô tả') ?>
                                            </h4>
                                            
                                            <p style="color: #636e72; font-size: 14px; margin-bottom: 12px;">
                                                <i class="fas fa-calendar"></i>
                                                Từ <?= formatDateShort($km->NGAYBD ??  '') ?> 
                                                đến <?= formatDateShort($km->NGAYKT ?? '') ?>
                                            </p>
                                        </div>
                                        
                                        <div style="text-align: right;">
                                            <div style="font-size: 32px; font-weight: 700; color: #E63946;">
                                                <?php if (($km->LOAI ??  '') === 'PERCENT'): ?>
                                                    <?= $km->GIATRI ??  0 ?>%
                                                <?php else: ?>
                                                    <?= formatMoney($km->GIATRI ??  0) ?>
                                                <?php endif; ?>
                                            </div>
                                            <div style="font-size: 13px; color: #999;">
                                                <?= ($km->LOAI ?? '') === 'PERCENT' ? 'Giảm theo %' : 'Giảm trực tiếp' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-tag" style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
                                <p>Hiện tại chưa có chương trình khuyến mãi nào.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
    <script src="/block-sports-center/public/assets/js/main.js"></script>
</body>
</html>