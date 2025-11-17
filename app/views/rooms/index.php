<?php 
// app/views/rooms/index.php
$pageTitle   = 'Quản lý phòng/sân';
$currentPage = 'rooms';

$rooms      = $data['rooms']      ?? [];
$sort       = $data['sort']       ?? 'id_desc';
$page       = $data['page']       ?? 1;
$totalPages = $data['totalPages'] ?? 1;
$total      = $data['total']      ?? 0;
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
                <div class="page-header">
                    <h2>Quản lý phòng/sân</h2>
                    <p>Danh sách tất cả phòng và sân trong trung tâm</p>
                </div>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách phòng/sân</h3>
                        <button class="btn btn-primary"
                                onclick="location.href='?c=rooms&a=create'">
                            <i class="fas fa-plus"></i> Thêm phòng/sân
                        </button>
                    </div>
                    
                    <!-- SEARCH TRONG CARD (chưa xử lý backend) -->
                    <div class="search-bar">
                        <form method="get" action="">
                            <input type="hidden" name="c" value="rooms">
                            <input type="hidden" name="a" value="index">
                            <input type="text"
                                   name="q"
                                   value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>"
                                   placeholder="Tìm kiếm theo tên phòng, khu vực...">
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
                                    Mã phòng
                                    <a href="?c=rooms&a=index&sort=id_asc&page=1">↑</a>
                                    <a href="?c=rooms&a=index&sort=id_desc&page=1">↓</a>
                                </th>
                                <th>
                                    Tên phòng
                                    <a href="?c=rooms&a=index&sort=name_asc&page=1">A-Z</a>
                                    <a href="?c=rooms&a=index&sort=name_desc&page=1">Z-A</a>
                                </th>
                                <th>
                                    Sức chứa
                                    <a href="?c=rooms&a=index&sort=capacity_asc&page=1">↑</a>
                                    <a href="?c=rooms&a=index&sort=capacity_desc&page=1">↓</a>
                                </th>
                                <th>Khu vực</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                                <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tbody id="roomTableBody">
                            <?php if (!empty($rooms)): ?>
                                <?php foreach ($rooms as $r): ?>
                                    <tr>
                                        <td><?= $r->MAPHONG ?></td>
                                        <td><?= htmlspecialchars($r->TENPHONG) ?></td>
                                        <td><?= (int)$r->SUCCHUA ?></td>
                                        <td><?= htmlspecialchars($r->TENKHU) ?></td>
                                        <td>
                                            <?php
                                            $statusText  = $r->HOATDONG ? 'Hoạt động' : 'Ngưng';
                                            $statusClass = $r->HOATDONG ? 'badge active' : 'badge inactive';
                                            ?>
                                            <span class="badge <?= $statusClass ?>">
                                                <?= $statusText ?>
                                            </span>
                                        </td>

                                        <!-- Ghi chú -->
                                        <td><?= nl2br(htmlspecialchars($r->GHICHU ?? '')) ?></td>
                                        <!-- Hành động -->
                                        <td>
                                            <div class="action-btns">
                                                <button class="action-btn edit"
                                                        onclick="location.href='?c=rooms&a=edit&id=<?= $r->MAPHONG ?>'">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="action-btn delete"
                                                        onclick="confirmDelete(<?= $r->MAPHONG ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align:center;">Chưa có phòng/sân nào trong hệ thống.</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="/block-sports-center/public/assets/js/main.js"></script>
    <script>
    function confirmDelete(id) {
        if (confirm('Bạn có chắc chắn muốn xóa phòng/sân này?')) {
            window.location.href = '?c=rooms&a=delete&id=' + encodeURIComponent(id);
        }
    }
    </script>
</body>
</html>