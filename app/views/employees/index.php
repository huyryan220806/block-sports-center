<?php
$pageTitle   = 'Quản lý nhân viên';
$currentPage = 'employees';

$employees   = $data['employees']      ?? [];
$keyword     = $data['keyword']        ?? '';
$page        = (int)($data['page']     ?? 1);
$totalPages  = (int)($data['totalPages'] ?? 1);
$total       = (int)($data['total']    ?? count($employees));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - BLOCK SPORTS CENTER</title>
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
                
                <div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <h2><i class="fas fa-user-tie"></i> Quản lý nhân viên</h2>
                        <p>Danh sách tất cả nhân viên trong trung tâm</p>
                    </div>
                    <a href="?c=employees&a=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm nhân viên
                    </a>
                </div>

                <!-- SEARCH & FILTER -->
                <div class="card">
                    <form method="GET" action="?c=employees&a=index" class="search-bar">
                        <input type="hidden" name="c" value="employees">
                        <input type="hidden" name="a" value="index">
                        <input type="text" 
                               name="search" 
                               placeholder="Tìm theo tên, SĐT, email..." 
                               value="<?= htmlspecialchars($keyword) ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <?php if (!empty($keyword)): ?>
                            <a href="?c=employees&a=index" class="btn btn-ghost">
                                <i class="fas fa-times"></i> Xóa lọc
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- TABLE -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i> Danh sách nhân viên 
                            <span class="badge"><?= $total ?></span>
                        </h3>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Họ tên</th>
                                    <th>SĐT</th>
                                    <th>Email</th>
                                    <th>Vai trò</th>
                                    <th>Ngày vào làm</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($employees)): ?>
                                    <?php foreach ($employees as $emp): ?>
                                        <tr>
                                            <td><strong>#<?= $emp->MANV ?></strong></td>
                                            <td><strong><?= htmlspecialchars($emp->HOTEN) ?></strong></td>
                                            <td><?= htmlspecialchars($emp->SDT ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($emp->EMAIL ?? 'N/A') ?></td>
                                            <td>
                                                <?php
                                                $vaitroLabels = [
                                                    'ADMIN'        => 'Admin',
                                                    'FRONTDESK'    => 'Lễ tân',
                                                    'MAINTENANCE'  => 'Bảo trì',
                                                    'OTHER'        => 'Khác'
                                                ];
                                                echo $vaitroLabels[$emp->VAITRO] ?? $emp->VAITRO;
                                                ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($emp->NGAYVAOLAM)) ?></td>
                                            <td>
                                                <?php if ($emp->TRANGTHAI == 1): ?>
                                                    <span class="badge active">Đang làm</span>
                                                <?php else: ?>
                                                    <span class="badge inactive">Đã nghỉ</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="action-buttons" style="display:flex; gap:6px;">
                                                    <a href="?c=employees&a=edit&id=<?= $emp->MANV ?>" 
                                                       class="btn btn-sm"
                                                       style="background:#E0FFF6; color:#00b894; border-radius:8px; padding:6px 8px;"
                                                       title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?c=employees&a=delete&id=<?= $emp->MANV ?>" 
                                                       class="btn btn-sm"
                                                       style="background:#FFEFEF; color:#FF6B6B; border-radius:8px; padding:6px 8px;"
                                                       title="Xóa"
                                                       onclick="return confirm('Bạn có chắc muốn xóa nhân viên này?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 40px;">
                                            <i class="fas fa-user-slash" style="font-size: 48px; color: #ddd;"></i>
                                            <p style="color: #999; margin-top: 12px;">
                                                <?= !empty($keyword) ? 'Không tìm thấy nhân viên nào!' : 'Chưa có nhân viên nào!' ?>
                                            </p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- PHÂN TRANG 10 DÒNG / TRANG, STYLE GIỐNG HÌNH -->
                        <?php if ($totalPages > 1): ?>
                            <?php
                            $queryBase = '?c=employees&a=index';
                            if (!empty($keyword)) {
                                $queryBase .= '&search=' . urlencode($keyword);
                            }
                            ?>
                            <div class="pagination" style="margin-top:16px; display:flex; justify-content:center; gap:4px;">
                                <?php if ($page > 1): ?>
                                    <a class="page-link"
                                       href="<?= $queryBase . '&page=' . ($page - 1) ?>"
                                       style="padding:4px 8px; border-radius:4px; text-decoration:none; border:1px solid #ddd;">
                                        &laquo;
                                    </a>
                                <?php endif; ?>

                                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                    <?php if ($p === $page): ?>
                                        <span class="page-link"
                                              style="font-weight:bold; background:#00b894; color:#fff; padding:4px 10px; border-radius:4px; display:inline-flex; align-items:center; justify-content:center;">
                                            <?= $p ?>
                                        </span>
                                    <?php else: ?>
                                        <a class="page-link"
                                           href="<?= $queryBase . '&page=' . $p ?>"
                                           style="padding:4px 10px; border-radius:4px; text-decoration:none; border:1px solid #ddd; color:#333; background:#fff; display:inline-flex; align-items:center; justify-content:center;">
                                            <?= $p ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                    <a class="page-link"
                                       href="<?= $queryBase . '&page=' . ($page + 1) ?>"
                                       style="padding:4px 8px; border-radius:4px; text-decoration:none; border:1px solid #ddd;">
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
</body>
</html>