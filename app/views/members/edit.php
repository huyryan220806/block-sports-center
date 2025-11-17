<?php 
$pageTitle = 'Chỉnh sửa hội viên';
$currentPage = 'members';
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
                        <a href="?c=members&a=index" 
                           class="btn btn-ghost btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>

                    <form method="post" action="?c=members&a=update&id=<?php echo isset($member->MAHOIVIEN) ? $member->MAHOIVIEN : ''; ?>">
                        <input type="hidden" name="id" value="<?php echo isset($member->MAHOIVIEN) ? $member->MAHOIVIEN : ''; ?>">
                        
                        <div class="form-layout">
                            <div>
                                <div class="form-group">
                                    <label class="form-label">Họ và tên <span style="color: red;">*</span></label>
                                    <input type="text" 
                                           name="full_name" 
                                           class="form-control" 
                                           placeholder="Nhập họ tên hội viên"
                                           value="<?php echo isset($member->HOTEN) ? htmlspecialchars($member->HOTEN) : ''; ?>"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Số điện thoại <span style="color: red;">*</span></label>
                                    <input type="tel" 
                                           name="phone" 
                                           class="form-control" 
                                           placeholder="Nhập số điện thoại"
                                           value="<?php echo isset($member->SODIENTHOAI) ? htmlspecialchars($member->SODIENTHOAI) : ''; ?>"
                                           pattern="[0-9]{10,11}"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email <span style="color: red;">*</span></label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control" 
                                           placeholder="Nhập địa chỉ email"
                                           value="<?php echo isset($member->EMAIL) ? htmlspecialchars($member->EMAIL) : ''; ?>"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Giới tính</label>
                                    <select name="gender" class="form-control">
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="Nam" <?php echo (isset($member->GIOITINH) && $member->GIOITINH == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                                        <option value="Nữ" <?php echo (isset($member->GIOITINH) && $member->GIOITINH == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                                        <option value="Khác" <?php echo (isset($member->GIOITINH) && $member->GIOITINH == 'Khác') ? 'selected' : ''; ?>>Khác</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <div class="form-group">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" 
                                           name="dob" 
                                           class="form-control"
                                           value="<?php echo isset($member->NGAYSINH) ? $member->NGAYSINH : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea name="address" 
                                              class="form-control" 
                                              rows="3"
                                              placeholder="Nhập địa chỉ"><?php echo isset($member->DIACHI) ? htmlspecialchars($member->DIACHI) : ''; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="status" class="form-control">
                                        <option value="ACTIVE" <?php echo (isset($member->TRANGTHAI) && $member->TRANGTHAI == 'Hoạt động') ? 'selected' : ''; ?>>Active (Hoạt động)</option>
                                        <option value="SUSPENDED" <?php echo (isset($member->TRANGTHAI) && $member->TRANGTHAI == 'Tạm ngưng') ? 'selected' : ''; ?>>Suspended (Tạm ngưng)</option>
                                        <option value="INACTIVE" <?php echo (isset($member->TRANGTHAI) && $member->TRANGTHAI == 'Không hoạt động') ? 'selected' : ''; ?>>Inactive (Không hoạt động)</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Ghi chú</label>
                                    <textarea name="notes" 
                                              class="form-control" 
                                              rows="2"
                                              placeholder="Ghi chú thêm (nếu có)"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="?c=members&a=index" 
                               class="btn btn-ghost">
                                Hủy
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