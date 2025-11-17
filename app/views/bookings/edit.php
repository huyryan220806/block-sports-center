<?php
$pageTitle   = 'Chỉnh sửa đặt phòng';
$currentPage = 'bookings';

$booking = $data['booking'] ?? null;
$rooms   = $data['rooms']   ?? [];
$members = $data['members'] ?? [];

if (!$booking) {
    echo "Không tìm thấy dữ liệu đặt phòng.";
    exit;
}

// Tách datetime thành date + time cho input
$editDate  = substr($booking->BATDAU, 0, 10);
$startTime = substr($booking->BATDAU, 11, 5);
$endTime   = substr($booking->KETTHUC, 11, 5);
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
                <h2>Chỉnh sửa đặt phòng</h2>
                <p>Cập nhật thông tin đặt phòng/sân.</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin đặt phòng</h3>
                    <a href="?c=bookings&a=index" class="btn btn-ghost btn-sm">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>

                <form method="post" action="?c=bookings&a=update">
                    <input type="hidden" name="id" value="<?= $booking->MADP ?>">

                    <div class="form-layout">
                        <div>
                            <!-- Phòng -->
                            <div class="form-group">
                                <label class="form-label">Phòng/Sân <span style="color:red">*</span></label>
                                <select name="room_id" class="form-control" required>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?= $room->MAPHONG ?>" <?= ($room->MAPHONG == $booking->MAPHONG ? 'selected' : '') ?>>
                                            <?= htmlspecialchars($room->TENPHONG) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Hội viên -->
                            <div class="form-group">
                                <label class="form-label">Hội viên</label>
                                <select name="member_id" class="form-control">
                                    <option value="">Khách lẻ</option>
                                    <?php foreach ($members as $m): ?>
                                        <option value="<?= $m->MAHV ?>" <?= ($booking->MAHV == $m->MAHV ? 'selected' : '') ?>>
                                            <?= htmlspecialchars($m->HOVATEN) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Mục đích -->
                            <div class="form-group">
                                <label class="form-label">Mục đích</label>
                                <select name="purpose" class="form-control">
                                    <option value="TAP_TU_DO"       <?= $booking->MUCTIEU === 'TAP_TU_DO' ? 'selected' : '' ?>>Tập tự do</option>
                                    <option value="CLB"             <?= $booking->MUCTIEU === 'CLB' ? 'selected' : '' ?>>Câu lạc bộ</option>
                                    <option value="GIU_CHO_SU_KIEN" <?= $booking->MUCTIEU === 'GIU_CHO_SU_KIEN' ? 'selected' : '' ?>>Giữ chỗ sự kiện</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <!-- Ngày -->
                            <div class="form-group">
                                <label class="form-label">Ngày <span style="color:red">*</span></label>
                                <input type="date"
                                       name="date"
                                       class="form-control"
                                       value="<?= htmlspecialchars($editDate) ?>"
                                       required>
                            </div>

                            <!-- Giờ bắt đầu -->
                            <div class="form-group">
                                <label class="form-label">Giờ bắt đầu <span style="color:red">*</span></label>
                                <input type="time"
                                       name="start_time"
                                       class="form-control"
                                       value="<?= htmlspecialchars($startTime) ?>"
                                       required>
                            </div>

                            <!-- Giờ kết thúc -->
                            <div class="form-group">
                                <label class="form-label">Giờ kết thúc <span style="color:red">*</span></label>
                                <input type="time"
                                       name="end_time"
                                       class="form-control"
                                       value="<?= htmlspecialchars($endTime) ?>"
                                       required>
                            </div>

                            <!-- Trạng thái -->
                            <div class="form-group">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-control">
                                    <option value="PENDING"   <?= $booking->TRANGTHAI === 'PENDING'   ? 'selected' : '' ?>>Pending</option>
                                    <option value="CONFIRMED" <?= $booking->TRANGTHAI === 'CONFIRMED' ? 'selected' : '' ?>>Confirmed</option>
                                    <option value="CANCELLED" <?= $booking->TRANGTHAI === 'CANCELLED' ? 'selected' : '' ?>>Cancelled</option>
                                    <option value="DONE"      <?= $booking->TRANGTHAI === 'DONE'      ? 'selected' : '' ?>>Done</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="?c=bookings&a=index" class="btn btn-ghost">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu thay đổi
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