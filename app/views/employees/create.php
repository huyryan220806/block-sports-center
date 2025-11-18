<?php
/**
 * Employees Create View
 * Updated: 2025-11-18 13:57:42 UTC
 * Fixed: Chỉ sử dụng field có trong bảng thực tế
 */

$pageTitle = 'Thêm nhân viên mới';
$currentPage = 'employees';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - BLOCK SPORTS CENTER</title>
    <link rel="stylesheet" href="/block-sports-center/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-layout">
        <?php include(__DIR__ . '/../layouts/sidebar.php'); ?>
        <main class="main-content">
            <?php include(__DIR__ . '/../layouts/header.php'); ?>
            <div class="content">
                <?php include(__DIR__ . '/../layouts/alerts.php'); ?>
                
                <div class="page-header">
                    <h2><i class="fas fa-user-plus"></i> Thêm nhân viên mới</h2>
                    <p>Nhập thông tin nhân viên mới</p>
                </div>

                <form method="POST" action="?c=employees&a=store">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user"></i> Thông tin nhân viên</h3>
                        </div>

                        <div class="form-layout">
                            <!-- Cột trái -->
                            <div>
                                <div class="form-group">
                                    <label class="form-label">
                                        Họ và tên <span style="color: red;">*</span>
                                    </label>
                                    <input type="text" 
                                           name="hoten" 
                                           class="form-control" 
                                           placeholder="Nguyễn Văn A"
                                           value="<?= htmlspecialchars($_POST['hoten'] ?? '') ?>"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" 
                                           name="sdt" 
                                           class="form-control" 
                                           placeholder="0901234567"
                                           value="<?= htmlspecialchars($_POST['sdt'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control" 
                                           placeholder="example@email.com"
                                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div>
                                <div class="form-group">
                                    <label class="form-label">
                                        Vai trò <span style="color: red;">*</span>
                                    </label>
                                    <select name="vaitro" class="form-control" required>
                                        <option value="ADMIN" <?= ($_POST['vaitro'] ?? '') == 'ADMIN' ? 'selected' : '' ?>>
                                            👑 Admin
                                        </option>
                                        <option value="FRONTDESK" <?= ($_POST['vaitro'] ?? '') == 'FRONTDESK' ? 'selected' : '' ?>>
                                            🏪 Lễ tân (Front Desk)
                                        </option>
                                        <option value="MAINTENANCE" <?= ($_POST['vaitro'] ?? '') == 'MAINTENANCE' ? 'selected' : '' ?>>
                                            🔧 Bảo trì (Maintenance)
                                        </option>
                                        <option value="OTHER" <?= ($_POST['vaitro'] ?? 'OTHER') == 'OTHER' ? 'selected' : '' ?>>
                                            👤 Khác
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Ngày vào làm <span style="color: red;">*</span>
                                    </label>
                                    <input type="date" 
                                           name="ngayvaolam" 
                                           class="form-control"
                                           value="<?= htmlspecialchars($_POST['ngayvaolam'] ?? date('Y-m-d')) ?>"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="trangthai" class="form-control">
                                        <option value="1" selected>✅ Đang làm việc</option>
                                        <option value="0">❌ Đã nghỉ việc</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-ghost" onclick="history.back()">
                            <i class="fas fa-times"></i> Hủy
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu nhân viên
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
</body>
</html>