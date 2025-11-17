<?php 
$pageTitle   = 'Chỉnh sửa hội viên';
$currentPage = 'members';

// Lấy dữ liệu member do Controller truyền xuống
$member = $data['member'] ?? null;

// Helper: lấy giá trị an toàn
$mahv      = $member->MAHV      ?? ($member->MAHOIVIEN   ?? '');
$fullName  = $member->HOVATEN   ?? ($member->HOTEN       ?? '');
$phone     = $member->SDT       ?? ($member->SODIENTHOAI ?? '');
$email     = $member->EMAIL     ?? '';
$gender    = $member->GIOITINH  ?? '';
$dob       = $member->NGAYSINH  ?? '';
$address   = $member->DIACHI    ?? '';
$status    = $member->TRANGTHAI ?? 'ACTIVE';
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
                    <h2>Chỉnh sửa hội viên</h2>
                    <p>Cập nhật thông tin hội viên trong hệ thống</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin hội viên</h3>
                        <a href="?c=members&a=index" class="btn btn-ghost btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>

                    <form method="post" action="?c=members&a=update&id=<?php echo $mahv; ?>">
                        <input type="hidden" name="id" value="<?php echo $mahv; ?>">

                        <div class="form-layout">
                            <div>
                                <!-- Họ tên -->
                                <div class="form-group">
                                    <label class="form-label">Họ và tên <span style="color:red">*</span></label>
                                    <input type="text"
                                           name="full_name"
                                           class="form-control"
                                           placeholder="Nhập họ và tên"
                                           value="<?php echo htmlspecialchars($fullName); ?>"
                                           required>
                                </div>

                                <!-- Số điện thoại -->
                                <div class="form-group">
                                    <label class="form-label">Số điện thoại <span style="color:red">*</span></label>
                                    <input type="text"
                                           name="phone"
                                           class="form-control"
                                           placeholder="Nhập số điện thoại"
                                           value="<?php echo htmlspecialchars($phone); ?>"
                                           pattern="[0-9]{9,11}"
                                           required>
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label class="form-label">Email <span style="color:red">*</span></label>
                                    <input type="email"
                                           name="email"
                                           class="form-control"
                                           placeholder="Nhập địa chỉ email"
                                           value="<?php echo htmlspecialchars($email); ?>"
                                           required>
                                </div>

                                <!-- Giới tính -->
                                <div class="form-group">
                                    <label class="form-label">Giới tính</label>
                                    <select name="gender" class="form-control">
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="Nam"
                                            <?php echo in_array($gender, ['Nam','Nam']) ? 'selected' : ''; ?>>
                                            Nam
                                        </option>
                                        <option value="Nữ"
                                            <?php echo in_array($gender, ['Nữ','Nữ']) ? 'selected' : ''; ?>>
                                            Nữ
                                        </option>
                                        <option value="Khác"
                                            <?php echo in_array($gender, ['Khác','Khác']) ? 'selected' : ''; ?>>
                                            Khác
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <!-- Ngày sinh -->
                                <div class="form-group">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date"
                                           name="dob"
                                           class="form-control"
                                           value="<?php echo $dob; ?>">
                                </div>

                                <!-- Địa chỉ -->
                                <div class="form-group">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea name="address"
                                              class="form-control"
                                              rows="4"
                                              placeholder="Nhập địa chỉ liên lạc"><?php 
                                        echo htmlspecialchars($address);
                                    ?></textarea>
                                </div>

                                <!-- Trạng thái -->
                                <div class="form-group">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="status" class="form-control">
                                        <option value="ACTIVE"
                                            <?php echo in_array($status, ['ACTIVE','Hoạt động']) ? 'selected' : ''; ?>>
                                            Hoạt động
                                        </option>
                                        <option value="SUSPENDED"
                                            <?php echo in_array($status, ['SUSPENDED','Tạm ngưng']) ? 'selected' : ''; ?>>
                                            Tạm ngưng
                                        </option>
                                        <option value="INACTIVE"
                                            <?php echo in_array($status, ['INACTIVE','Không hoạt động']) ? 'selected' : ''; ?>>
                                            Không hoạt động
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="?c=members&a=index" class="btn btn-ghost">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật hội viên
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