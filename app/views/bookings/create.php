<?php
$pageTitle   = 'Đặt phòng mới';
$currentPage = 'bookings';

$rooms   = $data['rooms']   ?? [];
$members = $data['members'] ?? [];
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
                <h2>Đặt phòng mới</h2>
                <p>Chọn phòng/sân, hội viên và thời gian đặt.</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin đặt phòng</h3>
                    <a href="?c=bookings&a=index" class="btn btn-ghost btn-sm">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>

                <form method="post" action="?c=bookings&a=store">
                    <div class="form-layout">
                        <div>
                            <!-- Phòng -->
                            <div class="form-group">
                                <label class="form-label">Phòng/Sân <span style="color:red">*</span></label>
                                <select name="room_id" class="form-control" required>
                                    <option value="">-- Chọn phòng/sân --</option>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?= $room->MAPHONG ?>">
                                            <?= htmlspecialchars($room->TENPHONG) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Hội viên -->
                            <div class="form-group">
                                <label class="form-label">Hội viên (có thể bỏ trống nếu khách lẻ)</label>
                                <select name="member_id" class="form-control">
                                    <option value="">Khách lẻ</option>
                                    <?php foreach ($members as $m): ?>
                                        <option value="<?= $m->MAHV ?>">
                                            <?= htmlspecialchars($m->HOVATEN) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Mục đích -->
                            <div class="form-group">
                                <label class="form-label">Mục đích</label>
                                <select name="purpose" class="form-control">
                                    <option value="TAP_TU_DO">Tập tự do</option>
                                    <option value="CLB">Câu lạc bộ</option>
                                    <option value="GIU_CHO_SU_KIEN">Giữ chỗ sự kiện</option>
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
                                       value="<?= date('Y-m-d') ?>"
                                       required>
                            </div>

                            <!-- Giờ bắt đầu -->
                            <div class="form-group">
                                <label class="form-label">Giờ bắt đầu <span style="color:red">*</span></label>
                                <input type="time"
                                       name="start_time"
                                       class="form-control"
                                       required>
                            </div>

                            <!-- Giờ kết thúc -->
                            <div class="form-group">
                                <label class="form-label">Giờ kết thúc <span style="color:red">*</span></label>
                                <input type="time"
                                       name="end_time"
                                       class="form-control"
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="?c=bookings&a=index" class="btn btn-ghost">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu đặt phòng
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