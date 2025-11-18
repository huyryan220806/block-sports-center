<?php 
$pageTitle = 'Quản lý tủ đồ';
$currentPage = 'lockers';

$lockers = $data['lockers'] ?? [];
$total = $data['total'] ?? 0;
$available = $data['available'] ?? 0;
$occupied = $data['occupied'] ?? 0;
$search = $data['search'] ?? '';
$roomFilter = $data['roomFilter'] ?? '';
$rooms = $data['rooms'] ?? [];
$page = $data['page'] ?? 1;
$totalPages = $data['totalPages'] ?? 1;
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
                <h2>Quản lý tủ đồ</h2>
                <p>Theo dõi số lượng tủ và tình trạng sử dụng.</p>
            </div>

            <!-- THỐNG KÊ -->
            <div class="stats-grid">
                <div class="stat-box">
                    <h3><?php echo $total; ?></h3>
                    <p>Tổng số tủ</p>
                </div>
                <div class="stat-box">
                    <h3><?php echo $available; ?></h3>
                    <p>Tủ hoạt động</p>
                </div>
                <div class="stat-box">
                    <h3><?php echo $occupied; ?></h3>
                    <p>Tủ bảo trì</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách tủ đồ</h3>
                    <a href="?c=lockers&a=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm tủ
                    </a>
                </div>

                <!-- SEARCH BAR (GIỐNG MEMBERS) -->
                <div class="search-bar">
                    <form method="GET" action="" style="display: flex; gap: 10px; width: 100%;">
                        <input type="hidden" name="c" value="lockers">
                        <input type="hidden" name="a" value="index">
                        
                        <input type="text" 
                               name="search" 
                               id="searchInput"
                               placeholder="Tìm theo ký tự tủ hoặc tên phòng..." 
                               value="<?php echo htmlspecialchars($search); ?>"
                               style="flex: 1;">
                        
                        <select name="room" style="padding: 8px 12px;">
                            <option value="">-- Tất cả phòng --</option>
                            <?php foreach ($rooms as $r): ?>
                                <option value="<?php echo $r->MAPHONG; ?>" 
                                        <?php echo ($roomFilter == $r->MAPHONG) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($r->TENPHONG); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <button type="submit" class="btn btn-ghost">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </form>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Mã Tủ</th>
                                <th>Ký Tự Tủ</th>
                                <th>Phòng</th>
                                <th>Khu vực</th>
                                <th>Trạng Thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="lockerTableBody">
                        <?php if (empty($lockers)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-inbox" style="font-size: 48px; color: #ccc;"></i>
                                    <p style="margin-top: 10px; color: #999;">Không có dữ liệu</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lockers as $lk): ?>
                                <tr>
                                    <td>#<?php echo str_pad($lk->MATU, 3, '0', STR_PAD_LEFT); ?></td>
                                    <td><strong><?php echo htmlspecialchars($lk->KITU); ?></strong></td>
                                    <td><?php echo htmlspecialchars($lk->TENPHONG); ?></td>
                                    <td><?php echo htmlspecialchars($lk->TENKHU); ?></td>
                                    <td>
                                        <?php if ($lk->HOATDONG == 1): ?>
                                            <span class="badge active">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge inactive">Bảo trì</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"
                                                    onclick="location.href='?c=lockers&a=edit&id=<?php echo $lk->MATU; ?>'"
                                                    title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-btn delete"
                                                    onclick="confirmDelete(<?php echo $lk->MATU; ?>)"
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

                    <!-- PHÂN TRANG (GIỐNG MEMBERS) -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination" style="margin-top:16px; display:flex; justify-content:center; gap:4px;">
                            <?php if ($page > 1): ?>
                                <a class="page-link" href="?c=lockers&a=index&page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&room=<?= urlencode($roomFilter) ?>">&laquo;</a>
                            <?php endif; ?>

                            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                <?php if ($p == $page): ?>
                                    <span class="page-link" style="font-weight:bold; background:#00b894; color:#fff; padding:4px 8px; border-radius:4px;">
                                        <?= $p ?>
                                    </span>
                                <?php else: ?>
                                    <a class="page-link"
                                       href="?c=lockers&a=index&page=<?= $p ?>&search=<?= urlencode($search) ?>&room=<?= urlencode($roomFilter) ?>"
                                       style="padding:4px 8px; border-radius:4px; text-decoration:none; border:1px solid #ddd;">
                                        <?= $p ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a class="page-link" href="?c=lockers&a=index&page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&room=<?= urlencode($roomFilter) ?>">&raquo;</a>
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
    if (confirm('Bạn có chắc chắn muốn xóa tủ #' + id + '?')) {
        window.location.href = '?c=lockers&a=delete&id=' + id;
    }
}
</script>
</body>
</html>