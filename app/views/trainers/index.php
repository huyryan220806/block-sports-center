<?php 
$pageTitle   = 'Quản lý huấn luyện viên';
$currentPage = 'trainers';

$trainers = $data['trainers'] ?? [];
$search   = $data['search']   ?? '';
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
                    <h2>Quản lý huấn luyện viên</h2>
                    <p>Theo dõi danh sách HLV cá nhân, chuyên môn và phí huấn luyện.</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách huấn luyện viên</h3>
                        <a href="?c=trainers&a=create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm HLV
                        </a>
                    </div>

                    <!-- THANH TÌM KIẾM HLV -->
                    <form method="get" class="search-bar">
                        <input type="hidden" name="c" value="trainers">
                        <input type="hidden" name="a" value="index">
                        <input 
                            type="text" 
                            name="q" 
                            class="search-input"
                            placeholder="Tìm theo tên, SĐT, email, chuyên môn..."
                            value="<?php echo htmlspecialchars($search); ?>"
                        >
                        <button class="btn btn-ghost">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </form>
                    
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
                            <tbody>
                                <?php if (!empty($trainers)): ?>
                                    <?php foreach ($trainers as $t): ?>
                                        <?php
                                            $statusLabel = $t->TRANGTHAI ? 'Đang làm' : 'Ngưng';
                                            $statusClass = $t->TRANGTHAI ? 'badge active' : 'badge inactive';
                                            $feeText     = number_format((float)$t->PHI_GIO, 0, ',', '.') . ' đ/giờ';
                                            $startDate   = date('d/m/Y', strtotime($t->NGAYVAOLAM));
                                        ?>
                                        <tr>
                                            <td><strong>#PT<?= str_pad($t->MANV, 3, '0', STR_PAD_LEFT); ?></strong></td>
                                            <td><?= htmlspecialchars($t->HOTEN) ?></td>
                                            <td><?= htmlspecialchars($t->SDT) ?></td>
                                            <td><?= htmlspecialchars($t->EMAIL) ?></td>
                                            <td><?= nl2br(htmlspecialchars($t->MOTA ?? '')) ?></td>
                                            <td><?= $feeText ?></td>
                                            <td><?= $startDate ?></td>
                                            <td><span class="<?= $statusClass ?>"><?= $statusLabel ?></span></td>
                                            <td>
                                                <div class="action-btns">
                                                    <button type="button"
                                                            class="action-btn edit"
                                                            onclick="location.href='?c=trainers&a=edit&id=<?= $t->MANV ?>'">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button"
                                                            class="action-btn delete"
                                                            onclick="if(confirm('Xóa huấn luyện viên này?')) location.href='?c=trainers&a=delete&id=<?= $t->MANV ?>';">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" style="text-align:center; padding: 16px 0;">
                                            Không tìm thấy huấn luyện viên nào.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
</body>
</html>