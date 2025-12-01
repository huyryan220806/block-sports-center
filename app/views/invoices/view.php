<?php 
/**
 * app/views/invoices/view.php
 * Xem chi tiết hóa đơn
 * Updated: 2025-12-01
 * Author: @huyryan220806
 */

$pageTitle   = 'Chi tiết hóa đơn';
$currentPage = 'invoices';

$invoice = $data['invoice'] ?? null;

if (!$invoice) {
    $_SESSION['error'] = 'Không tìm thấy hóa đơn!';
    header('Location: ? c=invoices&a=index');
    exit;
}

// Helper function
if (!function_exists('formatMoney')) {
    function formatMoney($amount) {
        return number_format($amount, 0, ',', '. ') . 'đ';
    }
}

// Tính tổng tiền
$subtotal = 0;
$items = $invoice->items ??  [];
foreach ($items as $item) {
    $subtotal += ($item->SOLUONG ??  1) * ($item->DONGIA ?? 0);
}

// Tính giảm giá
$discount = 0;
if (! empty($invoice->GIATRI_KM)) {
    if ($invoice->LOAI_KM === 'PERCENT') {
        $discount = $subtotal * $invoice->GIATRI_KM / 100;
    } else {
        $discount = $invoice->GIATRI_KM;
    }
}
$total = $subtotal - $discount;

// Status badge
$statusMap = [
    'DRAFT' => ['class' => 'inactive', 'text' => 'Nháp'],
    'ISSUED' => ['class' => 'scheduled', 'text' => 'Đã xuất'],
    'PAID' => ['class' => 'active', 'text' => 'Đã thanh toán'],
    'PARTIAL' => ['class' => 'full', 'text' => 'Thanh toán 1 phần'],
    'VOID' => ['class' => 'suspended', 'text' => 'Đã hủy'],
];
$status = $statusMap[$invoice->TRANGTHAI] ?? ['class' => 'inactive', 'text' => 'Không xác định'];
?>
<! DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - BLOCK SPORTS CENTER</title>
    <link rel="stylesheet" href="/block-sports-center/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .invoice-detail {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .info-group {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        .info-group h4 {
            margin: 0 0 15px 0;
            color: #2d3436;
            font-size: 16px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #eee;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #636e72; font-size: 14px; }
        .info-value { font-weight: 600; color: #2d3436; }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #00B894;
            color: white;
            padding: 12px 15px;
            text-align: left;
        }
        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        .items-table tr:hover { background: #f8f9fa; }
        .totals-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 16px;
        }
        .total-row.final {
            border-top: 2px solid #ddd;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 20px;
            font-weight: 700;
        }
        .total-row.final .total-value { color: #00B894; }
        
        @media print {
            .admin-layout { display: block ! important; }
            .sidebar, .header, .page-header .action-btns { display: none !important; }
            .main-content { margin: 0 !important; padding: 0 !important; }
            .invoice-detail { box-shadow: none !important; }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php include(__DIR__ . '/../layouts/sidebar.php'); ?>
        <main class="main-content">
            <?php include(__DIR__ . '/../layouts/header.php'); ?>
            <div class="content">
                <?php include(__DIR__ . '/../layouts/alerts.php'); ?>
                
                <div class="page-header">
                    <h2>
                        <i class="fas fa-file-invoice"></i> 
                        Chi tiết hóa đơn #INV<?= str_pad($invoice->MAHDON, 4, '0', STR_PAD_LEFT) ?>
                    </h2>
                    <div class="action-btns" style="display: flex; gap: 10px;">
                        <button class="btn btn-ghost" onclick="window.print()">
                            <i class="fas fa-print"></i> In hóa đơn
                        </button>
                        <button class="btn btn-primary" onclick="location.href='?c=invoices&a=edit&id=<?= $invoice->MAHDON ?>'">
                            <i class="fas fa-edit"></i> Sửa
                        </button>
                        <button class="btn btn-ghost" onclick="location.href='? c=invoices&a=index'">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </button>
                    </div>
                </div>
                
                <div class="invoice-detail">
                    <div class="invoice-header">
                        <div>
                            <h1 style="margin: 0; color: #00B894; font-size: 28px;">BLOCK SPORTS CENTER</h1>
                            <p style="margin: 5px 0 0 0; color: #636e72;">Hệ thống quản lý trung tâm thể thao</p>
                        </div>
                        <div style="text-align: right;">
                            <h2 style="margin: 0; font-size: 24px;">
                                HÓA ĐƠN #INV<?= str_pad($invoice->MAHDON, 4, '0', STR_PAD_LEFT) ?>
                            </h2>
                            <p style="margin: 5px 0;">
                                Ngày lập: <strong><?= date('d/m/Y H:i', strtotime($invoice->NGAYLAP)) ?></strong>
                            </p>
                            <span class="badge <?= $status['class'] ?>"><?= $status['text'] ?></span>
                        </div>
                    </div>
                    
                    <div class="invoice-info">
                        <div class="info-group">
                            <h4><i class="fas fa-user"></i> Thông tin hội viên</h4>
                            <div class="info-row">
                                <span class="info-label">Mã HV:</span>
                                <span class="info-value">#<?= $invoice->MAHV ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Họ tên:</span>
                                <span class="info-value"><?= htmlspecialchars($invoice->HOVATEN ??  'N/A') ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">SĐT:</span>
                                <span class="info-value"><?= htmlspecialchars($invoice->SDT ?? 'N/A') ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email:</span>
                                <span class="info-value"><?= htmlspecialchars($invoice->EMAIL ?? 'N/A') ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Địa chỉ:</span>
                                <span class="info-value"><?= htmlspecialchars($invoice->DIACHI ?? 'N/A') ?></span>
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <h4><i class="fas fa-tag"></i> Thông tin khuyến mãi</h4>
                            <?php if (! empty($invoice->MA_KHUYENMAI)): ?>
                                <div class="info-row">
                                    <span class="info-label">Mã KM:</span>
                                    <span class="info-value" style="color: #E63946;">
                                        <?= htmlspecialchars($invoice->MA_KHUYENMAI) ?>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Mô tả:</span>
                                    <span class="info-value"><?= htmlspecialchars($invoice->MOTA_KHUYENMAI ??  '') ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Giá trị:</span>
                                    <span class="info-value" style="color: #E63946;">
                                        <?php if ($invoice->LOAI_KM === 'PERCENT'): ?>
                                            Giảm <?= $invoice->GIATRI_KM ?>%
                                        <?php else: ?>
                                            Giảm <?= formatMoney($invoice->GIATRI_KM) ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php else: ?>
                                <p style="color: #999; text-align: center; padding: 20px;">
                                    Không áp dụng khuyến mãi
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <h3 style="margin-bottom: 15px;">
                        <i class="fas fa-list"></i> Chi tiết hóa đơn
                    </h3>
                    
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Loại</th>
                                <th>Mô tả</th>
                                <th style="text-align: center;">SL</th>
                                <th style="text-align: right;">Đơn giá</th>
                                <th style="text-align: right;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $index => $item): ?>
                                    <?php 
                                    $lineTotal = ($item->SOLUONG ?? 1) * ($item->DONGIA ?? 0);
                                    $loaiHang = [
                                        'MEMBERSHIP' => 'Gói tập',
                                        'CLASS' => 'Lớp học',
                                        'PT' => 'PT Session',
                                        'BOOKING' => 'Đặt phòng',
                                        'LOCKER' => 'Tủ đồ',
                                        'OTHER' => 'Khác'
                                    ];
                                    ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <span class="badge scheduled">
                                                <?= $loaiHang[$item->LOAIHANG] ??  $item->LOAIHANG ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($item->MOTA ??  '') ?></td>
                                        <td style="text-align: center;"><?= $item->SOLUONG ??  1 ?></td>
                                        <td style="text-align: right;"><?= formatMoney($item->DONGIA ?? 0) ?></td>
                                        <td style="text-align: right; font-weight: 600;">
                                            <?= formatMoney($lineTotal) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 30px; color: #999;">
                                        Không có chi tiết hóa đơn
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                    <div class="totals-section">
                        <div class="total-row">
                            <span>Tạm tính:</span>
                            <span><?= formatMoney($subtotal) ?></span>
                        </div>
                        <?php if ($discount > 0): ?>
                        <div class="total-row" style="color: #E63946;">
                            <span>Giảm giá (<?= $invoice->MA_KHUYENMAI ?>):</span>
                            <span>-<?= formatMoney($discount) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="total-row final">
                            <span>TỔNG CỘNG:</span>
                            <span class="total-value"><?= formatMoney($total) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
    <script src="/block-sports-center/public/assets/js/main.js"></script>
</body>
</html>