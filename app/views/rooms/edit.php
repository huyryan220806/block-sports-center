<?php
$pageTitle   = 'Chỉnh sửa phòng/sân';
$currentPage = 'rooms';

// Dữ liệu phòng & khu do RoomsController::edit() truyền xuống
$room  = $data['room']  ?? null;
$areas = $data['areas'] ?? [];

if (!$room) {
    echo "Không tìm thấy dữ liệu phòng/sân.";
    exit;
}
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
                <h2>Chỉnh sửa phòng/sân</h2>
                <p>Cập nhật thông tin phòng/sân trong hệ thống</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin phòng/sân</h3>
                    <a href="?c=rooms&a=index" class="btn btn-ghost btn-sm">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>

                <!-- QUAN TRỌNG: action & name phải đúng như RoomsController::update() -->
                <form method="post" action="?c=rooms&a=update">
                    <!-- ID phòng -->
                    <input type="hidden" name="id" value="<?= $room->MAPHONG ?>">

                    <div class="form-layout">
                        <div>
                            <!-- Tên phòng/sân -->
                            <div class="form-group">
                                <label class="form-label">Tên phòng/sân <span style="color:red">*</span></label>
                                <input type="text"
                                       name="room_name"
                                       class="form-control"
                                       value="<?= htmlspecialchars($room->TENPHONG) ?>"
                                       required>
                            </div>

                            <!-- Khu vực -->
                            <div class="form-group">
                                <label class="form-label">Khu vực <span style="color:red">*</span></label>
                                <select name="area" class="form-control" required>
                                    <option value="">-- Chọn khu --</option>
                                    <?php foreach ($areas as $area): ?>
                                        <option value="<?= $area->MAKHU ?>"
                                            <?= $area->MAKHU == $room->MAKHU ? 'selected' : '' ?>>
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
                                       value="<?= (int)$room->SUCCHUA ?>"
                                       required>
                            </div>
                        </div>

                        <div>
                            <!-- Loại phòng/sân (lấy tạm từ GHICHU nếu bạn có format riêng thì chỉnh sau) -->
                            <div class="form-group">
                                <label class="form-label">Loại phòng/sân</label>
                                <input type="text"
                                       name="type"
                                       class="form-control"
                                       placeholder="VD: Gym, Yoga, Bơi..."
                                       value="">
                            </div>

                            <!-- Trạng thái -->
                            <div class="form-group">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-control">
                                    <option value="ACTIVE"   <?= $room->HOATDONG ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="INACTIVE" <?= !$room->HOATDONG ? 'selected' : '' ?>>Ngưng sử dụng</option>
                                </select>
                            </div>

                            <!-- Ghi chú -->
                            <div class="form-group">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="notes"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Ghi chú thêm về phòng/sân nếu có"><?=
                                    htmlspecialchars($room->GHICHU ?? '')
                                ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="?c=rooms&a=index" class="btn btn-ghost">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập nhật phòng/sân
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