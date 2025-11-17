<?php
$pageTitle   = 'Quản lý lớp học';
$currentPage = 'classes';

$classes = $data['classes'] ?? [];
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
                <h2>Quản lý lớp học</h2>
                <p>Danh sách các lớp tập luyện trong trung tâm.</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách lớp học</h3>
                    <a href="?c=classes&a=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm lớp học
                    </a>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                        <tr>
                            <th>Mã lớp</th>
                            <th>Tên lớp</th>
                            <th>Thời lượng</th>
                            <th>Sĩ số mặc định</th>
                            <th>Mô tả</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $cl): ?>
                                <tr>
                                    <td><strong>#CL<?= str_pad($cl->MALOP, 3, '0', STR_PAD_LEFT); ?></strong></td>
                                    <td><?= htmlspecialchars($cl->TENLOP) ?></td>
                                    <td><?= (int)$cl->THOILUONG ?> phút</td>
                                    <td><?= (int)$cl->SISO_MACDINH ?> người</td>
                                    <td><?= nl2br(htmlspecialchars($cl->MOTA ?? '')) ?></td>
                                    <td>
                                        <div class="action-btns">
                                            <button type="button"
                                                    class="action-btn edit"
                                                    onclick="location.href='?c=classes&a=edit&id=<?= $cl->MALOP ?>'">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button type="button"
                                                    class="action-btn delete"
                                                    onclick="if(confirm('Xóa lớp này?')) location.href='?c=classes&a=delete&id=<?= $cl->MALOP ?>';">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center; padding:16px 0;">
                                    Không có lớp học nào.
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