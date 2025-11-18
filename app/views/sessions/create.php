<?php
$pageTitle   = 'Thêm buổi lớp';
$currentPage = 'sessions';

$classes  = $data['classes']  ?? [];
$rooms    = $data['rooms']    ?? [];
$trainers = $data['trainers'] ?? [];

// nếu có dùng flash error / old input thì lấy ra
$errors = $data['errors'] ?? [];
$old    = $data['old']    ?? [];
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
                <h2>Thêm buổi lớp</h2>
                <p>Tạo lịch học mới cho các lớp trong trung tâm.</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin buổi lớp</h3>
                    <a href="?c=sessions&a=index" class="btn btn-ghost">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>

                <form method="post" action="?c=sessions&a=store" class="form-grid">
                    <!-- Lớp học -->
                    <div class="form-group">
                        <label for="class_id">Lớp học <span class="required">*</span></label>
                        <select id="class_id" name="class_id" class="form-control" required>
                            <option value="">-- Chọn lớp --</option>
                            <?php foreach ($classes as $cl): ?>
                                <option value="<?= $cl->MALOP ?>"
                                    <?= (isset($old['class_id']) && $old['class_id'] == $cl->MALOP) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cl->TENLOP) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Phòng / Sân -->
                    <div class="form-group">
                        <label for="room_id">Phòng / Sân <span class="required">*</span></label>
                        <select id="room_id" name="room_id" class="form-control" required>
                            <option value="">-- Chọn phòng/sân --</option>
                            <?php foreach ($rooms as $r): ?>
                                <?php
                                // nếu có cột HOATDONG, chỉ cho đặt phòng đang hoạt động
                                $active = property_exists($r, 'HOATDONG') ? (int)$r->HOATDONG === 1 : true;
                                ?>
                                <option value="<?= $r->MAPHONG ?>"
                                    <?= !$active ? 'disabled' : '' ?>
                                    <?= (isset($old['room_id']) && $old['room_id'] == $r->MAPHONG) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($r->TENPHONG) ?><?= !$active ? ' (Ngưng)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Huấn luyện viên -->
                    <div class="form-group">
                        <label for="trainer_id">Huấn luyện viên</label>
                        <select id="trainer_id" name="trainer_id" class="form-control">
                            <option value="">-- Chưa gán HLV --</option>
                            <?php foreach ($trainers as $t): ?>
                                <option value="<?= $t->MANV ?>"
                                    <?= (isset($old['trainer_id']) && $old['trainer_id'] == $t->MANV) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t->HOTEN) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Ngày -->
                    <div class="form-group">
                        <label for="date">Ngày học <span class="required">*</span></label>
                        <input type="date"
                               id="date"
                               name="date"
                               class="form-control"
                               required
                               value="<?= htmlspecialchars($old['date'] ?? '') ?>">
                    </div>

                    <!-- Giờ bắt đầu / kết thúc -->
                    <div class="form-group">
                        <label for="start_time">Giờ bắt đầu <span class="required">*</span></label>
                        <input type="time"
                               id="start_time"
                               name="start_time"
                               class="form-control"
                               required
                               value="<?= htmlspecialchars($old['start_time'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="end_time">Giờ kết thúc <span class="required">*</span></label>
                        <input type="time"
                               id="end_time"
                               name="end_time"
                               class="form-control"
                               required
                               value="<?= htmlspecialchars($old['end_time'] ?? '') ?>">
                    </div>

                    <!-- Sĩ số -->
                    <div class="form-group">
                        <label for="capacity">Sĩ số dự kiến</label>
                        <input type="number"
                               id="capacity"
                               name="capacity"
                               min="0"
                               class="form-control"
                               value="<?= htmlspecialchars($old['capacity'] ?? '0') ?>">
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select id="status" name="status" class="form-control">
                            <?php
                            $oldStatus = $old['status'] ?? 'SCHEDULED';
                            ?>
                            <option value="SCHEDULED" <?= $oldStatus === 'SCHEDULED' ? 'selected' : '' ?>>Lịch học</option>
                            <option value="ONGOING"   <?= $oldStatus === 'ONGOING'   ? 'selected' : '' ?>>Đang học</option>
                            <option value="DONE"      <?= $oldStatus === 'DONE'      ? 'selected' : '' ?>>Hoàn thành</option>
                            <option value="CANCELLED" <?= $oldStatus === 'CANCELLED' ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu buổi lớp
                        </button>
                        <a href="?c=sessions&a=index" class="btn btn-ghost">
                            Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include(__DIR__ . '/../layouts/footer.php'); ?>
</body>
</html>