<?php 
$pageTitle   = 'Quản lý huấn luyện viên';
$currentPage = 'trainers';

$trainers   = $data['trainers']   ?? [];
$search     = $data['search']     ?? '';
$page       = $data['page']       ?? 1;
$totalPages = $data['totalPages'] ?? 1;
$total      = $data['total']      ?? 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - BLOCK SPORTS CENTER</title>
    <link rel="stylesheet" href="/block-sports-center/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-layout">
        <?php include(__DIR__ . '/../layouts/sidebar.php'); ?>

        <main class="main-content">
            <?php include(__DIR__ . '/../layouts/header.php'); ?>

            <div class="content">
                <!-- THÔNG BÁO -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="page-header">
                    <h2>Quản lý huấn luyện viên</h2>
                    <p>Theo dõi danh sách HLV cá nhân, chuyên môn và phí huấn luyện. (Tổng: <?php echo $total; ?>)</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách huấn luyện viên</h3>
                        <a href="?c=trainers&a=create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm HLV
                        </a>
                    </div>

                    <!-- SEARCH BAR (GIỐNG LOCKERS) -->
                    <div class="search-bar">
                        <form method="GET" action="" style="display: flex; gap: 10px; width: 100%;">
                            <input type="hidden" name="c" value="trainers">
                            <input type="hidden" name="a" value="index">
                            
                            <input type="text" 
                                   name="search" 
                                   id="searchInput"
                                   placeholder="Tìm theo tên, SĐT, email, chuyên môn..." 
                                   value="<?php echo htmlspecialchars($search); ?>"
                                   style="flex: 1;">
                            
                            <button type="submit" class="btn btn-ghost">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </form>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Mã HLV</th>
                                    <th>Họ tên</th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th>Chuyên môn</th>
                                    <th>Phí/giờ</th>
                                    <th>Ngày vào làm</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="trainerTableBody">
                                <?php if (empty($trainers)): ?>
                                    <tr>
                                        <td colspan="9" style="text-align: center; padding: 40px;">
                                            <i class="fas fa-inbox" style="font-size: 48px; color: #ccc;"></i>
                                            <p style="margin-top: 10px; color: #999;">Không có dữ liệu</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($trainers as $t): ?>
                                        <?php
                                            $statusLabel = $t->TRANGTHAI ? 'Đang làm' : 'Ngưng';
                                            $statusClass = $t->TRANGTHAI ? 'badge active' : 'badge inactive';
                                            $feeText     = number_format((float)$t->PHI_GIO, 0, ',', '.') . ' đ';
                                            $startDate   = date('d/m/Y', strtotime($t->NGAYVAOLAM));
                                        ?>
                                        <tr>
                                            <td><strong>#PT<?= str_pad($t->MANV, 3, '0', STR_PAD_LEFT); ?></strong></td>
                                            <td><?= htmlspecialchars($t->HOTEN) ?></td>
                                            <td><?= htmlspecialchars($t->SDT) ?></td>
                                            <td><?= htmlspecialchars($t->EMAIL) ?></td>
                                            <td><?= nl2br(htmlspecialchars($t->MOTA ?? 'Chưa cập nhật')) ?></td>
                                            <td><?= $feeText ?>/giờ</td>
                                            <td><?= $startDate ?></td>
                                            <td><span class="<?= $statusClass ?>"><?= $statusLabel ?></span></td>
                                            <td>
                                                <div class="action-btns">
                                                    <button class="action-btn edit"
                                                            onclick="location.href='?c=trainers&a=edit&id=<?= $t->MANV ?>'"
                                                            title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="action-btn delete"
                                                            onclick="confirmDelete(<?= $t->MANV ?>)"
                                                            title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- PHÂN TRANG (GIỐNG LOCKERS) -->
                        <?php if ($totalPages > 1): ?>
                            <div class="pagination" style="margin-top:16px; display:flex; justify-content:center; gap:4px;">
                                <?php if ($page > 1): ?>
                                    <a class="page-link" href="?c=trainers&a=index&page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">&laquo;</a>
                                <?php endif; ?>

                                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                    <?php if ($p == $page): ?>
                                        <span class="page-link" style="font-weight:bold; background:#00b894; color:#fff; padding:4px 8px; border-radius:4px;">
                                            <?= $p ?>
                                        </span>
                                    <?php else: ?>
                                        <a class="page-link"
                                           href="?c=trainers&a=index&page=<?= $p ?>&search=<?= urlencode($search) ?>"
                                           style="padding:4px 8px; border-radius:4px; text-decoration:none; border:1px solid #ddd;">
                                            <?= $p ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                    <a class="page-link" href="?c=trainers&a=index&page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">&raquo;</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
    <script>
    function confirmDelete(id) {
        if (confirm('Bạn có chắc chắn muốn xóa HLV #' + id + '?')) {
            window.location.href = '?c=trainers&a=delete&id=' + id;
        }
    }
    </script>
</body>
</html>