<?php 
$pageTitle = 'Cài đặt hệ thống';
$currentPage = 'settings';
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
                    <h2>Cài đặt hệ thống</h2>
                    <p>Quản lý cấu hình và thiết lập hệ thống</p>
                </div>
                
                <!-- General Settings -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin chung</h3>
                    </div>
                    <form>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <div class="form-group">
                                    <label class="form-label">Tên trung tâm</label>
                                    <input type="text" class="form-control" value="BLOCK SPORTS CENTER">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" value="113">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="info@blocksports.vn">
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea class="form-control" rows="3">Dĩ An Bình Dương</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Giờ mở cửa</label>
                                    <input type="text" class="form-control" value="05:00 - 23:00">
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- System Settings -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cài đặt hệ thống</h3>
                    </div>
                    <div class="table-container">
                        <table>
                            <tbody>
                                <tr>
                                    <td><strong>Tự động gửi email nhắc nhở</strong><br><small style="color: #999;">Gửi email cho hội viên trước buổi lớp</small></td>
                                    <td style="text-align: right;">
                                        <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                                            <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                                            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--primary); transition: .4s; border-radius: 30px;"></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Bảo trì hệ thống</strong><br><small style="color: #999;">Bật chế độ bảo trì (tạm khóa hệ thống)</small></td>
                                    <td style="text-align: right;">
                                        <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                                            <input type="checkbox" style="opacity: 0; width: 0; height: 0;">
                                            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 30px;"></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Chế độ tối (Dark Mode)</strong><br><small style="color: #999;">Giao diện tối cho admin</small></td>
                                    <td style="text-align: right;">
                                        <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                                            <input type="checkbox" style="opacity: 0; width: 0; height: 0;">
                                            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 30px;"></span>
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Backup & Restore -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sao lưu & Khôi phục</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div style="text-align: center; padding: 30px; background: #f8f9fa; border-radius: 8px;">
                                <i class="fas fa-download" style="font-size: 48px; color: var(--primary); margin-bottom: 15px;"></i>
                                <h4>Sao lưu dữ liệu</h4>
                                <p style="color: #999; font-size: 13px; margin: 10px 0;">Tạo bản backup toàn bộ database</p>
                                <button class="btn btn-primary" style="margin-top: 10px;">
                                    <i class="fas fa-download"></i> Backup ngay
                                </button>
                            </div>
                            <div style="text-align: center; padding: 30px; background: #f8f9fa; border-radius: 8px;">
                                <i class="fas fa-upload" style="font-size: 48px; color: var(--primary); margin-bottom: 15px;"></i>
                                <h4>Khôi phục dữ liệu</h4>
                                <p style="color: #999; font-size: 13px; margin: 10px 0;">Khôi phục từ file backup</p>
                                <button class="btn btn-ghost" style="margin-top: 10px;">
                                    <i class="fas fa-upload"></i> Chọn file
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- About -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin phiên bản</h3>
                    </div>
                    <div style="padding: 20px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Phiên bản hệ thống:</strong></td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">v1.0.0</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Cập nhật lần cuối:</strong></td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">15/11/2024</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Developer:</strong></td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">Block Sports Team</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
</body>
</html>