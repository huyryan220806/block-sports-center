<?php
/**
 * Employees Edit View
 * Form chỉnh sửa nhân viên
 * Created: 2025-11-18 13:43:15 UTC
 * Author: @huyryan220806
 */

$pageTitle = 'Chỉnh sửa nhân viên';
$currentPage = 'employees';

$employee = $data['employee'] ?? null;

if (!$employee) {
    $_SESSION['error'] = 'Không tìm thấy nhân viên!';
    header('Location: ?c=employees&a=index');
    exit;
}

// ✅ HÀM HELPER LẤY GIÁ TRỊ AN TOÀN
function getVal($obj, $prop, $default = '') {
    return isset($obj->$prop) ? $obj->$prop : $default;
}

// ✅ LẤY TẤT CẢ GIÁ TRỊ TRƯỚC
$manv = getVal($employee, 'MANV');
$hoten = getVal($employee, 'HOTEN');
$ngaysinh = getVal($employee, 'NGAYSINH');
$gioitinh = getVal($employee, 'GIOITINH', 'MALE');
$sdt = getVal($employee, 'SDT');
$email = getVal($employee, 'EMAIL');
$diachi = getVal($employee, 'DIACHI');
$chucvu = getVal($employee, 'CHUCVU', 'STAFF');
$phongban = getVal($employee, 'PHONGBAN');
$ngayvaolam = getVal($employee, 'NGAYVAOLAM');
$luong = getVal($employee, 'LUONG', '0');
$trangthai = getVal($employee, 'TRANGTHAI', 'ACTIVE');
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
                    <h2>
                        <i class="fas fa-edit"></i> Chỉnh sửa nhân viên #<?= htmlspecialchars($manv) ?>
                    </h2>
                    <p>Cập nhật thông tin nhân viên</p>
                </div>

                <form method="POST" action="?c=employees&a=update">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($manv) ?>">

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
                                           value="<?= htmlspecialchars($hoten) ?>"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" 
                                           name="ngaysinh" 
                                           class="form-control"
                                           value="<?= htmlspecialchars($ngaysinh) ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Giới tính</label>
                                    <select name="gioitinh" class="form-control">
                                        <option value="MALE" <?= $gioitinh == 'MALE' ? 'selected' : '' ?>>
                                            Nam
                                        </option>
                                        <option value="FEMALE" <?= $gioitinh == 'FEMALE' ? 'selected' : '' ?>>
                                            Nữ
                                        </option>
                                        <option value="OTHER" <?= $gioitinh == 'OTHER' ? 'selected' : '' ?>>
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
                                           value="<?= htmlspecialchars($sdt) ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control" 
                                           placeholder="example@email.com"
                                           value="<?= htmlspecialchars($email) ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea name="diachi" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Nhập địa chỉ"><?= htmlspecialchars($diachi) ?></textarea>
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
                                        <option value="STAFF" <?= $chucvu == 'STAFF' ? 'selected' : '' ?>>
                                            Nhân viên
                                        </option>
                                        <option value="MANAGER" <?= $chucvu == 'MANAGER' ? 'selected' : '' ?>>
                                            Quản lý
                                        </option>
                                        <option value="RECEPTIONIST" <?= $chucvu == 'RECEPTIONIST' ? 'selected' : '' ?>>
                                            Lễ tân
                                        </option>
                                        <option value="CLEANER" <?= $chucvu == 'CLEANER' ? 'selected' : '' ?>>
                                            Vệ sinh
                                        </option>
                                        <option value="SECURITY" <?= $chucvu == 'SECURITY' ? 'selected' : '' ?>>
                                            Bảo vệ
                                        </option>
                                        <option value="OTHER" <?= $chucvu == 'OTHER' ? 'selected' : '' ?>>
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
                                           value="<?= htmlspecialchars($phongban) ?>">
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
                                           value="<?= htmlspecialchars($ngayvaolam) ?>"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Lương (VND)</label>
                                    <input type="number" 
                                           name="luong" 
                                           class="form-control" 
                                           placeholder="0"
                                           value="<?= htmlspecialchars($luong) ?>"
                                           min="0"
                                           step="100000">
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="padding: 0 20px 20px;">
                            <label class="form-label">Trạng thái</label>
                            <select name="trangthai" class="form-control">
                                <option value="ACTIVE" <?= $trangthai == 'ACTIVE' ? 'selected' : '' ?>>
                                    Đang làm việc
                                </option>
                                <option value="INACTIVE" <?= $trangthai == 'INACTIVE' ? 'selected' : '' ?>>
                                    Đã nghỉ việc
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-ghost" onclick="history.back()">
                            <i class="fas fa-times"></i> Hủy
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
</body>
</html>