<?php
/**
 * Employees Create View
 * Updated: 2025-11-20 15:52:11 UTC
 * Author: @huyryan220806
 * Fixed: Thêm đầy đủ vai trò nhân viên
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
                    <!-- THÔNG TIN CÁ NHÂN -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user"></i> Thông tin cá nhân</h3>
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
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" 
                                           name="ngaysinh" 
                                           class="form-control"
                                           value="<?= htmlspecialchars($_POST['ngaysinh'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Giới tính</label>
                                    <select name="gioitinh" class="form-control">
                                        <option value="MALE" <?= ($_POST['gioitinh'] ?? 'MALE') == 'MALE' ? 'selected' : '' ?>>
                                            Nam
                                        </option>
                                        <option value="FEMALE" <?= ($_POST['gioitinh'] ?? '') == 'FEMALE' ? 'selected' : '' ?>>
                                            Nữ
                                        </option>
                                        <option value="OTHER" <?= ($_POST['gioitinh'] ?? '') == 'OTHER' ? 'selected' : '' ?>>
                                            Khác
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div>
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

                                <div class="form-group">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea name="diachi" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Nhập địa chỉ"><?= htmlspecialchars($_POST['diachi'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- THÔNG TIN CÔNG VIỆC -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-briefcase"></i> Thông tin công việc</h3>
                        </div>

                        <div class="form-layout">
                            <!-- Cột trái -->
                            <div>
                                <div class="form-group">
                                    <label class="form-label">
                                        Chức vụ <span style="color: red;">*</span>
                                    </label>
                                    <select name="chucvu" class="form-control" required>
                                        <option value="">-- Chọn chức vụ --</option>
                                        <option value="STAFF" <?= ($_POST['chucvu'] ?? '') == 'STAFF' ? 'selected' : '' ?>>
                                            Nhân viên
                                        </option>
                                        <option value="MANAGER" <?= ($_POST['chucvu'] ?? '') == 'MANAGER' ? 'selected' : '' ?>>
                                            Quản lý
                                        </option>
                                        <option value="RECEPTIONIST" <?= ($_POST['chucvu'] ?? '') == 'RECEPTIONIST' ? 'selected' : '' ?>>
                                            Lễ tân
                                        </option>
                                        <option value="CLEANER" <?= ($_POST['chucvu'] ?? '') == 'CLEANER' ? 'selected' : '' ?>>
                                            Vệ sinh
                                        </option>
                                        <option value="SECURITY" <?= ($_POST['chucvu'] ?? '') == 'SECURITY' ? 'selected' : '' ?>>
                                            Bảo vệ
                                        </option>
                                        <option value="OTHER" <?= ($_POST['chucvu'] ?? '') == 'OTHER' ? 'selected' : '' ?>>
                                            Khác
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Phòng ban</label>
                                    <input type="text" 
                                           name="phongban" 
                                           class="form-control" 
                                           placeholder="VD: Hành chính, Kỹ thuật..."
                                           value="<?= htmlspecialchars($_POST['phongban'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Vai trò <span style="color: red;">*</span>
                                    </label>
                                    <select name="vaitro" class="form-control" required>
                                        <option value="">-- Chọn vai trò --</option>
                                        <option value="ADMIN" <?= ($_POST['vaitro'] ?? '') == 'ADMIN' ? 'selected' : '' ?>>
                                            <i class="fas fa-shield-halved"></i> Admin
                                        </option>
                                        <option value="FRONTDESK" <?= ($_POST['vaitro'] ?? '') == 'FRONTDESK' ? 'selected' : '' ?>>
                                            <i class="fas fa-desk"></i> Lễ tân (Front Desk)
                                        </option>
                                        <option value="MAINTENANCE" <?= ($_POST['vaitro'] ?? '') == 'MAINTENANCE' ? 'selected' : '' ?>>
                                            <i class="fas fa-tools"></i> Bảo trì (Maintenance)
                                        </option>
                                        <option value="OTHER" <?= ($_POST['vaitro'] ?? '') == 'OTHER' ? 'selected' : '' ?>>
                                            <i class="fas fa-user"></i> Khác
                                        </option>
                                    </select>
                                    <small class="form-text">Vai trò quyết định quyền truy cập hệ thống</small>
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div>
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
                                    <label class="form-label">Lương (VND)</label>
                                    <input type="number" 
                                           name="luong" 
                                           class="form-control" 
                                           placeholder="0"
                                           value="<?= htmlspecialchars($_POST['luong'] ?? '0') ?>"
                                           min="0"
                                           step="100000">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="trangthai" class="form-control">
                                        <option value="ACTIVE" selected>Đang làm việc</option>
                                        <option value="INACTIVE">Đã nghỉ việc</option>
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