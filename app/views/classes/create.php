<?php
$pageTitle   = 'Thêm lớp học';
$currentPage = 'classes';
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
                <h2>Thêm lớp học</h2>
                <p>Tạo mới một lớp tập luyện.</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin lớp học</h3>
                    <a href="?c=classes&a=index" class="btn btn-ghost btn-sm">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>

                <form method="post" action="?c=classes&a=store">
                    <div class="form-layout">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Tên lớp <span style="color:red">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" class="form-control" rows="4"></textarea>
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label class="form-label">Thời lượng (phút) <span style="color:red">*</span></label>
                                <input type="number" name="duration" class="form-control" min="1" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Sĩ số mặc định <span style="color:red">*</span></label>
                                <input type="number" name="capacity" class="form-control" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="?c=classes&a=index" class="btn btn-ghost">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu lớp học
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