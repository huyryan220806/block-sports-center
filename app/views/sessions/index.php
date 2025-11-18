<?php 
$pageTitle   = 'Quản lý buổi lớp';
$currentPage = 'sessions';

$sessions   = $data['sessions']   ?? [];
$search     = $data['search']     ?? '';
$page       = (int)($data['page'] ?? 1);
$totalPages = (int)($data['totalPages'] ?? 1);
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
            <div class="page-header">
                <h2>Quản lý buổi lớp</h2>
                <p>Theo dõi lịch học của các lớp trong trung tâm.</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách buổi lớp</h3>
                    <a href="?c=sessions&a=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm buổi lớp
                    </a>
                </div>

                <!-- THANH TÌM KIẾM -->
                <form method="get" class="search-bar">
                    <input type="hidden" name="c" value="sessions">
                    <input type="hidden" name="a" value="index">

                    <input 
                        type="text"
                        name="q"
                        class="search-input"
                        placeholder="Tìm theo tên lớp, phòng/sân, huấn luyện viên..."
                        value="<?php echo htmlspecialchars($search); ?>"
                    >

                    <button type="submit" class="btn btn-ghost">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </form>

                <div class="table-container">
                    <table>
                        <thead>
                        <tr>
                            <th>Mã buổi</th>
                            <th>Ngày</th>
                            <th>Thời gian</th>
                            <th>Lớp</th>
                            <th>Phòng/Sân</th>
                            <th>Huấn luyện viên</th>
                            <th>Sĩ số</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($sessions)): ?>
                            <?php foreach ($sessions as $s): ?>
                                <?php
                                $date = date('d/m/Y', strtotime($s->BATDAU));
                                $time = date('H:i', strtotime($s->BATDAU)) . ' - ' . date('H:i', strtotime($s->KETTHUC));

                                $trainerName = $s->TENHLV ?? 'Chưa gán HLV';

                                // mapping trạng thái → badge
                                $status      = $s->TRANGTHAI;
                                $statusLabel = 'Lịch học';
                                $statusClass = 'badge scheduled';

                                if ($status === 'ONGOING') {
                                    $statusLabel = 'Đang học';
                                    $statusClass = 'badge active';
                                } elseif ($status === 'DONE') {
                                    $statusLabel = 'Hoàn thành';
                                    $statusClass = 'badge full';
                                } elseif ($status === 'CANCELLED') {
                                    $statusLabel = 'Đã hủy';
                                    $statusClass = 'badge inactive';
                                }
                                ?>
                                <tr>
                                    <td><strong>#SS<?= str_pad($s->MABUOI, 3, '0', STR_PAD_LEFT); ?></strong></td>
                                    <td><?= $date ?></td>
                                    <td><?= $time ?></td>
                                    <td><?= htmlspecialchars($s->TENLOP) ?></td>
                                    <td><?= htmlspecialchars($s->TENPHONG) ?></td>
                                    <td><?= htmlspecialchars($trainerName) ?></td>
                                    <td><?= (int)$s->SISO ?></td>
                                    <td><span class="<?= $statusClass ?>"><?= $statusLabel ?></span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button type="button"
                                                    class="action-btn edit"
                                                    onclick="location.href='?c=sessions&a=edit&id=<?= $s->MABUOI ?>'">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button"
                                                    class="action-btn delete"
                                                    onclick="if(confirm('Xóa buổi lớp này?')) location.href='?c=sessions&a=delete&id=<?= $s->MABUOI ?>';">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align:center; padding:16px 0;">
                                    Không có buổi lớp nào phù hợp.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- PHÂN TRANG STYLE GIỐNG MEMBERS / HÌNH -->
                <?php if ($totalPages > 1): ?>
                    <?php
                    // base giữ query tìm kiếm
                    $queryBase = '?c=sessions&a=index';
                    if (!empty($search)) {
                        $queryBase .= '&q=' . urlencode($search);
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
                                <!-- trang hiện tại: nền xanh -->
                                <span class="page-link"
                                      style="font-weight:bold; background:#00b894; color:#fff; padding:4px 10px; border-radius:4px; display:inline-flex; align-items:center; justify-content:center;">
                                    <?= $p ?>
                                </span>
                            <?php else: ?>
                                <!-- trang khác: viền xám -->
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
    </main>
</div>

<?php include(__DIR__ . '/../layouts/footer.php'); ?>
</body>
</html>