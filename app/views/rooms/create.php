<?php
$pageTitle   = 'Thêm phòng/sân';
$currentPage = 'rooms';

// Danh sách khu do RoomsController::create() truyền xuống
$areas = $data['areas'] ?? [];
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
                <h2>Thêm phòng/sân mới</h2>
                <p>Nhập thông tin phòng/sân để quản lý lịch đặt, buổi tập...</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin phòng/sân</h3>
                    <a href="?c=rooms&a=index" class="btn btn-ghost btn-sm">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>

                <!-- QUAN TRỌNG: action & name phải đúng như RoomsController::store() -->
                <form method="post" action="?c=rooms&a=store">
                    <div class="form-layout">
                        <div>
                            <!-- Tên phòng/sân -->
                            <div class="form-group">
                                <label class="form-label">Tên phòng/sân <span style="color:red">*</span></label>
                                <input type="text"
                                       name="room_name"
                                       class="form-control"
                                       placeholder="VD: Phòng Gym 1, Sân bóng 7 người..."
                                       required>
                            </div>

                            <!-- Khu vực -->
                            <div class="form-group">
                                <label class="form-label">Khu vực <span style="color:red">*</span></label>
                                <select name="area" class="form-control" required>
                                    <option value="">-- Chọn khu --</option>
                                    <?php foreach ($areas as $area): ?>
                                        <option value="<?= $area->MAKHU ?>">
                                            <?= htmlspecialchars($area->TENKHU) ?>
                                            <?= $area->LOAIKHU ? ' - ' . htmlspecialchars($area->LOAIKHU) : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Sức chứa -->
                            <div class="form-group">
                                <label class="form-label">Sức chứa (người) <span style="color:red">*</span></label>
                                <input type="number"
                                       name="capacity"
                                       class="form-control"
                                       min="1"
                                       placeholder="VD: 20"
                                       required>
                            </div>
                        </div>

                        <div>
                            <!-- Loại phòng/sân -->
                            <div class="form-group">
                                <label class="form-label">Loại phòng/sân</label>
                                <input type="text"
                                       name="type"
                                       class="form-control"
                                       placeholder="VD: Gym, Yoga, Bơi, Bóng đá...">
                            </div>

                            <!-- Trạng thái hoạt động -->
                            <div class="form-group">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-control">
                                    <option value="ACTIVE">Hoạt động</option>
                                    <option value="INACTIVE">Ngưng sử dụng</option>
                                </select>
                            </div>

                            <!-- Ghi chú -->
                            <div class="form-group">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="notes"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Ghi chú thêm về phòng/sân nếu có"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="?c=rooms&a=index" class="btn btn-ghost">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu phòng/sân
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include(__DIR__ . '/../layouts/footer.php'); ?>
</body>
</html>