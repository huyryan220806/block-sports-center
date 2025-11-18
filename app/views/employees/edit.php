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
                        <i class="fas fa-edit"></i> Chỉnh sửa nhân viên #<?= $employee->MANV ?>
                    </h2>
                    <p>Cập nhật thông tin nhân viên</p>
                </div>

                <form method="POST" action="?c=employees&a=update">
                    <input type="hidden" name="id" value="<?= $employee->MANV ?>">

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
                                           value="<?= htmlspecialchars($employee->HOTEN) ?>"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" 
                                           name="ngaysinh" 
                                           class="form-control"
                                           value="<?= htmlspecialchars($employee->NGAYSINH ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Giới tính</label>
                                    <select name="gioitinh" class="form-control">
                                        <option value="MALE" <?= $employee->GIOITINH == 'MALE' ? 'selected' : '' ?>>
                                            👨 Nam
                                        </option>
                                        <option value="FEMALE" <?= $employee->GIOITINH == 'FEMALE' ? 'selected' : '' ?>>
                                            👩 Nữ
                                        </option>
                                        <option value="OTHER" <?= $employee->GIOITINH == 'OTHER' ? 'selected' : '' ?>>
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
                                           value="<?= htmlspecialchars($employee->SDT ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control" 
                                           placeholder="example@email.com"
                                           value="<?= htmlspecialchars($employee->EMAIL ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea name="diachi" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Nhập địa chỉ"><?= htmlspecialchars($employee->DIACHI ?? '') ?></textarea>
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
                                        <option value="STAFF" <?= $employee->CHUCVU == 'STAFF' ? 'selected' : '' ?>>
                                            Nhân viên
                                        </option>
                                        <option value="MANAGER" <?= $employee->CHUCVU == 'MANAGER' ? 'selected' : '' ?>>
                                            Quản lý
                                        </option>
                                        <option value="RECEPTIONIST" <?= $employee->CHUCVU == 'RECEPTIONIST' ? 'selected' : '' ?>>
                                            Lễ tân
                                        </option>
                                        <option value="CLEANER" <?= $employee->CHUCVU == 'CLEANER' ? 'selected' : '' ?>>
                                            Vệ sinh
                                        </option>
                                        <option value="SECURITY" <?= $employee->CHUCVU == 'SECURITY' ? 'selected' : '' ?>>
                                            Bảo vệ
                                        </option>
                                        <option value="OTHER" <?= $employee->CHUCVU == 'OTHER' ? 'selected' : '' ?>>
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
                                           value="<?= htmlspecialchars($employee->PHONGBAN ?? '') ?>">
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
                                           value="<?= htmlspecialchars($employee->NGAYVAOLAM) ?>"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Lương (VND)</label>
                                    <input type="number" 
                                           name="luong" 
                                           class="form-control" 
                                           placeholder="0"
                                           value="<?= htmlspecialchars($employee->LUONG ?? '0') ?>"
                                           min="0"
                                           step="100000">
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="padding: 0 20px 20px;">
                            <label class="form-label">Trạng thái</label>
                            <select name="trangthai" class="form-control">
                                <option value="ACTIVE" <?= $employee->TRANGTHAI == 'ACTIVE' ? 'selected' : '' ?>>
                                    ✅ Đang làm việc
                                </option>
                                <option value="INACTIVE" <?= $employee->TRANGTHAI == 'INACTIVE' ? 'selected' : '' ?>>
                                    ❌ Đã nghỉ việc
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