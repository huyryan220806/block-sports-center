<?php 
// app/views/invoices/index.php
$pageTitle   = 'Quản lý hóa đơn';
$currentPage = 'invoices';

$invoices   = $data['invoices']   ?? [];
$sort       = $data['sort']       ?? 'id_desc';
$page       = $data['page']       ?? 1;
$totalPages = $data['totalPages'] ?? 1;
$total      = $data['total']      ?? 0;
$search     = $data['search']     ?? '';

// ❌ XÓA DÒNG NÀY - ĐÃ CÓ TRONG Helpers.php
// function formatMoney($amount) { ... }

// ❌ XÓA DÒNG NÀY - ĐÃ CÓ TRONG Helpers.php
// function getStatusBadge($status) { ... }

/**
 * ✅ SỬ DỤNG FUNCTION TỪ Helpers.php
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - BLOCK SPORTS CENTER</title>
    <link rel="stylesheet" href="/block-sports-center/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-layout">
        <?php include(__DIR__ . '/../layouts/sidebar.php'); ?>
        <main class="main-content">
            <?php include(__DIR__ . '/../layouts/header.php'); ?>
            <div class="content">
                <?php include(__DIR__ . '/../layouts/alerts.php'); ?>
                
                <div class="page-header">
                    <h2>Quản lý hóa đơn</h2>
                    <p>Danh sách tất cả hóa đơn trong hệ thống (Tổng: <?= $total ?>)</p>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách hóa đơn</h3>
                        <div style="display: flex; gap: 12px;">
                            <button class="btn btn-ghost"
                                    onclick="location.href='?c=invoices&a=packages'">
                                <i class="fas fa-tags"></i> Gói tập & Khuyến mãi
                            </button>
                            <button class="btn btn-primary"
                                    onclick="location.href='?c=invoices&a=create'">
                                <i class="fas fa-plus"></i> Tạo hóa đơn mới
                            </button>
                        </div>
                    </div>
                    
                    <!-- SEARCH BAR -->
                    <div class="search-bar">
                        <form method="get" action="">
                            <input type="hidden" name="c" value="invoices">
                            <input type="hidden" name="a" value="index">
                            <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                            
                            <input type="text"
                                   name="q"
                                   value="<?= htmlspecialchars($search) ?>"
                                   placeholder="Tìm kiếm theo mã, tên hội viên..."
                                   style="flex: 1;">
                            
                            <button class="btn btn-ghost" type="submit">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </form>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        MÃ HĐ
                                        <a href="?c=invoices&a=index&sort=id_asc&page=1"
                                           style="font-size:12px; margin-left:4px; text-decoration:none;<?= $sort === 'id_asc' ? 'font-weight:bold;' : '' ?>">
                                            ↑
                                        </a>
                                        <a href="?c=invoices&a=index&sort=id_desc&page=1"
                                           style="font-size:12px; margin-left:2px; text-decoration:none;<?= $sort === 'id_desc' ? 'font-weight:bold;' : '' ?>">
                                            ↓
                                        </a>
                                    </th>
                                    <th>
                                        NGÀY LẬP
                                        <a href="?c=invoices&a=index&sort=date_asc&page=1"
                                           style="font-size:12px; margin-left:4px; text-decoration:none;<?= $sort === 'date_asc' ? 'font-weight:bold;' : '' ?>">
                                            ↑
                                        </a>
                                        <a href="?c=invoices&a=index&sort=date_desc&page=1"
                                           style="font-size:12px; margin-left:2px; text-decoration:none;<?= $sort === 'date_desc' ? 'font-weight:bold;' : '' ?>">
                                            ↓
                                        </a>
                                    </th>
                                    <th>HỘI VIÊN</th>
                                    <th>KHUYẾN MÃI</th>
                                    <th>
                                        TỔNG TIỀN
                                        <a href="?c=invoices&a=index&sort=amount_asc&page=1"
                                           style="font-size:12px; margin-left:4px; text-decoration:none;<?= $sort === 'amount_asc' ? 'font-weight:bold;' : '' ?>">
                                            ↑
                                        </a>
                                        <a href="?c=invoices&a=index&sort=amount_desc&page=1"
                                           style="font-size:12px; margin-left:2px; text-decoration:none;<?= $sort === 'amount_desc' ? 'font-weight:bold;' : '' ?>">
                                            ↓
                                        </a>
                                    </th>
                                    <th>TRẠNG THÁI</th>
                                    <th>HÀNH ĐỘNG</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceTableBody">
                                <?php if (!empty($invoices)): ?>
                                    <?php foreach ($invoices as $inv): ?>
                                        <tr>
                                            <td><strong>#INV<?= str_pad($inv->MAHDON, 4, '0', STR_PAD_LEFT) ?></strong></td>
                                            <td><?= date('d/m/Y H:i', strtotime($inv->NGAYLAP)) ?></td>
                                            <td>
                                                <?= htmlspecialchars($inv->TEN_HOIVIEN) ?>
                                                <br>
                                                <small style="color: #999;"><?= htmlspecialchars($inv->SDT ?? '') ?></small>
                                            </td>
                                            <td>
                                                <?php if ($inv->MA_KHUYENMAI): ?>
                                                    <span class="badge" style="background: #FFF4D4; color: #E17055;">
                                                        <?= htmlspecialchars($inv->MA_KHUYENMAI) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span style="color: #999;">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><strong style="color: #00B894;"><?= formatMoney($inv->total_amount) ?></strong></td>
                                            <td>
                                                <?php
                                                // ✅ Inline badge logic (hoặc tạo helper riêng)
                                                $status = strtoupper($inv->TRANGTHAI);
                                                if ($status === 'PAID') {
                                                    $badgeClass = 'active';
                                                    $badgeText = 'Đã thanh toán';
                                                } elseif ($status === 'ISSUED') {
                                                    $badgeClass = 'scheduled';
                                                    $badgeText = 'Đã xuất';
                                                } elseif ($status === 'PARTIAL') {
                                                    $badgeClass = 'full';
                                                    $badgeText = 'Thanh toán 1 phần';
                                                } elseif ($status === 'VOID') {
                                                    $badgeClass = 'suspended';
                                                    $badgeText = 'Đã hủy';
                                                } else {
                                                    $badgeClass = 'inactive';
                                                    $badgeText = 'Nháp';
                                                }
                                                ?>
                                                <span class="badge <?= $badgeClass ?>">
                                                    <?= $badgeText ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-btns">
                                                    <button class="action-btn edit"
                                                            onclick="location.href='?c=invoices&a=show&id=<?= $inv->MAHDON ?>'"
                                                            title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="action-btn edit"
                                                            onclick="location.href='?c=invoices&a=edit&id=<?= $inv->MAHDON ?>'"
                                                            title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="action-btn delete"
                                                            onclick="confirmDelete(<?= $inv->MAHDON ?>)"
                                                            title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align:center; padding: 40px;">
                                            <i class="fas fa-inbox" style="font-size: 48px; color: #ccc;"></i>
                                            <p style="margin-top: 10px; color: #999;">Chưa có hóa đơn nào.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- PHÂN TRANG -->
                        <?php if ($totalPages > 1): ?>
                            <div class="pagination" style="margin-top:16px; display:flex; justify-content:center; gap:4px;">
                                <?php if ($page > 1): ?>
                                    <a class="page-link" 
                                       href="?c=invoices&a=index&page=<?= $page-1 ?>&sort=<?= urlencode($sort) ?>">
                                        &laquo;
                                    </a>
                                <?php endif; ?>

                                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                    <?php if ($p == $page): ?>
                                        <span class="page-link" 
                                              style="font-weight:bold; background:#00b894; color:#fff; padding:4px 8px; border-radius:4px;">
                                            <?= $p ?>
                                        </span>
                                    <?php else: ?>
                                        <a class="page-link"
                                           href="?c=invoices&a=index&page=<?= $p ?>&sort=<?= urlencode($sort) ?>"
                                           style="padding:4px 8px; border-radius:4px; text-decoration:none; border:1px solid #ddd;">
                                            <?= $p ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                    <a class="page-link" 
                                       href="?c=invoices&a=index&page=<?= $page+1 ?>&sort=<?= urlencode($sort) ?>">
                                        &raquo;
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
    <script src="/block-sports-center/public/assets/js/main.js"></script>
    <script>
    function confirmDelete(id) {
        if (confirm('Bạn có chắc chắn muốn xóa hóa đơn #INV' + String(id).padStart(4, '0') + '?')) {
            window.location.href = '?c=invoices&a=delete&id=' + id;
        }
    }
    </script>
</body>
</html>