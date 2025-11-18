<?php
$pageTitle   = 'Chỉnh sửa buổi lớp';
$currentPage = 'sessions';

$session  = $data['session'];      // object buổi lớp hiện tại
$classes  = $data['classes']  ?? [];
$rooms    = $data['rooms']    ?? [];
$trainers = $data['trainers'] ?? [];

// nếu có old input (sau khi validate fail) thì ưu tiên old
$old = $data['old'] ?? [];

// tách ngày / giờ từ BATDAU, KETTHUC
$batdau  = $session->BATDAU ?? null;
$ketthuc = $session->KETTHUC ?? null;

$defaultDate       = $batdau ? date('Y-m-d', strtotime($batdau)) : '';
$defaultStartTime  = $batdau ? date('H:i',   strtotime($batdau)) : '';
$defaultEndTime    = $ketthuc ? date('H:i',  strtotime($ketthuc)) : '';

$date      = $old['date']        ?? $defaultDate;
$startTime = $old['start_time']  ?? $defaultStartTime;
$endTime   = $old['end_time']    ?? $defaultEndTime;
$capacity  = $old['capacity']    ?? $session->SISO;
$status    = $old['status']      ?? $session->TRANGTHAI;
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
                <h2>Chỉnh sửa buổi lớp</h2>
                <p>Cập nhật thông tin lịch học: lớp, phòng, huấn luyện viên, thời gian…</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Buổi #SS<?= str_pad($session->MABUOI, 3, '0', STR_PAD_LEFT); ?>
                    </h3>
                    <a href="?c=sessions&a=index" class="btn btn-ghost">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>

                <form method="post" action="?c=sessions&a=update&id=<?= $session->MABUOI ?>" class="form-grid">
                    <!-- Lớp học -->
                    <div class="form-group">
                        <label for="class_id">Lớp học <span class="required">*</span></label>
                        <select id="class_id" name="class_id" class="form-control" required>
                            <?php foreach ($classes as $cl): ?>
                                <option value="<?= $cl->MALOP ?>"
                                    <?= ($old['class_id'] ?? $session->MALOP) == $cl->MALOP ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cl->TENLOP) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Phòng / Sân -->
                    <div class="form-group">
                        <label for="room_id">Phòng / Sân <span class="required">*</span></label>
                        <select id="room_id" name="room_id" class="form-control" required>
                            <?php foreach ($rooms as $r): ?>
                                <?php
                                $active = property_exists($r, 'HOATDONG') ? (int)$r->HOATDONG === 1 : true;
                                $selectedRoom = ($old['room_id'] ?? $session->MAPHONG);
                                ?>
                                <option value="<?= $r->MAPHONG ?>"
                                    <?= !$active ? 'disabled' : '' ?>
                                    <?= $selectedRoom == $r->MAPHONG ? 'selected' : '' ?>>
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
                            <?php
                            $selectedTrainer = $old['trainer_id'] ?? ($session->MAHLV ?? '');
                            ?>
                            <?php foreach ($trainers as $t): ?>
                                <option value="<?= $t->MANV ?>"
                                    <?= $selectedTrainer == $t->MANV ? 'selected' : '' ?>>
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
                               value="<?= htmlspecialchars($date) ?>">
                    </div>

                    <!-- Giờ bắt đầu / kết thúc -->
                    <div class="form-group">
                        <label for="start_time">Giờ bắt đầu <span class="required">*</span></label>
                        <input type="time"
                               id="start_time"
                               name="start_time"
                               class="form-control"
                               required
                               value="<?= htmlspecialchars($startTime) ?>">
                    </div>

                    <div class="form-group">
                        <label for="end_time">Giờ kết thúc <span class="required">*</span></label>
                        <input type="time"
                               id="end_time"
                               name="end_time"
                               class="form-control"
                               required
                               value="<?= htmlspecialchars($endTime) ?>">
                    </div>

                    <!-- Sĩ số -->
                    <div class="form-group">
                        <label for="capacity">Sĩ số</label>
                        <input type="number"
                               id="capacity"
                               name="capacity"
                               min="0"
                               class="form-control"
                               value="<?= htmlspecialchars($capacity) ?>">
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select id="status" name="status" class="form-control">
                            <option value="SCHEDULED" <?= $status === 'SCHEDULED' ? 'selected' : '' ?>>Lịch học</option>
                            <option value="ONGOING"   <?= $status === 'ONGOING'   ? 'selected' : '' ?>>Đang học</option>
                            <option value="DONE"      <?= $status === 'DONE'      ? 'selected' : '' ?>>Hoàn thành</option>
                            <option value="CANCELLED" <?= $status === 'CANCELLED' ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập nhật
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