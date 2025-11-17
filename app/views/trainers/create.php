<?php 
$pageTitle   = 'Thêm huấn luyện viên';
$currentPage = 'trainers';
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
                <h2>Thêm huấn luyện viên</h2>
                <p>Nhập thông tin HLV cá nhân.</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin huấn luyện viên</h3>
                    <a href="?c=trainers&a=index" class="btn btn-ghost btn-sm">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>

                <form method="post" action="?c=trainers&a=store">
                    <div class="form-layout">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Họ tên <span style="color:red">*</span></label>
                                <input type="text" name="hoten" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Số điện thoại <span style="color:red">*</span></label>
                                <input type="text" name="sdt" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email <span style="color:red">*</span></label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Ngày vào làm <span style="color:red">*</span></label>
                                <input type="date" name="ngayvaolam" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label class="form-label">Chuyên môn (mô tả)</label>
                                <textarea name="chuyenmon" class="form-control" rows="4"></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Phí/giờ (VND) <span style="color:red">*</span></label>
                                <input type="number" name="phi_gio" class="form-control" min="0" step="10000" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Trạng thái</label>
                                <select name="trangthai" class="form-control">
                                    <option value="1">Đang làm</option>
                                    <option value="0">Ngưng</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="?c=trainers&a=index" class="btn btn-ghost">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu HLV
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