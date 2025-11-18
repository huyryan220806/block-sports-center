<?php 
$pageTitle = 'Sửa thông tin tủ đồ';
$currentPage = 'lockers';
$locker = $data['locker'] ?? null;
$rooms = $data['rooms'] ?? []; // ✅ ĐỔI TÊN

if (!$locker) {
    header('Location: ?c=lockers&a=index');
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
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="page-header">
                <h2><i class="fas fa-edit"></i> Sửa tủ đồ #<?php echo $locker->MATU; ?></h2>
                <a href="?c=lockers&a=index" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="?c=lockers&a=edit&id=<?php echo $locker->MATU; ?>">
                        
                        <div class="form-group">
                            <label for="maphong">Chọn Phòng <span style="color: red;">*</span></label>
                            <select name="maphong" id="maphong" class="form-control" required>
                                <option value="">-- Chọn phòng --</option>
                                <?php foreach ($rooms as $r): ?> 
                                    <option value="<?php echo $r->MAPHONG; ?>"
                                            <?php echo ($locker->MAPHONG == $r->MAPHONG) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($r->TENPHONG . ' - ' . $r->TENKHU); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kitu">Ký Tự Tủ <span style="color: red;">*</span></label>
                            <input type="text" 
                                   name="kitu" 
                                   id="kitu" 
                                   class="form-control" 
                                   value="<?php echo htmlspecialchars($locker->KITU); ?>"
                                   required
                                   maxlength="20">
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" 
                                       name="hoatdong" 
                                       value="1" 
                                       <?php echo ($locker->HOATDONG == 1) ? 'checked' : ''; ?>>
                                Tủ đang hoạt động
                            </label>
                        </div>

                        <div class="form-actions" style="display: flex; gap: 10px; margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật
                            </button>
                            <a href="?c=lockers&a=index" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>