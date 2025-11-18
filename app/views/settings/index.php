<?php
$pageTitle   = 'Cài đặt';
$currentPage = 'settings';

$settings = $data['settings'] ?? [];
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
                <h2><i class="fas fa-cog"></i> Cài đặt hệ thống</h2>
                <p>Quản lý cấu hình và thông tin trung tâm</p>
            </div>

            <?php if (!empty($_SESSION['flash']['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['flash']['success']); ?>
                </div>
                <?php unset($_SESSION['flash']['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash']['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['flash']['error']); ?>
                </div>
                <?php unset($_SESSION['flash']['error']); ?>
            <?php endif; ?>

            <form method="post" action="?c=settings&a=update">
                <!-- THÔNG TIN TRUNG TÂM -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-building"></i> Thông tin trung tâm</h3>
                    </div>
                    <div class="form-layout">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Tên trung tâm <span style="color:red">*</span></label>
                                <input type="text" 
                                       name="center_name" 
                                       class="form-control"
                                       value="<?= htmlspecialchars($settings['center_name'] ?? 'BLOCK SPORTS CENTER') ?>"
                                       required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" 
                                       name="center_address" 
                                       class="form-control"
                                       value="<?= htmlspecialchars($settings['center_address'] ?? '') ?>">
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" 
                                       name="center_phone" 
                                       class="form-control"
                                       value="<?= htmlspecialchars($settings['center_phone'] ?? '') ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" 
                                       name="center_email" 
                                       class="form-control"
                                       value="<?= htmlspecialchars($settings['center_email'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GIÁ DỊCH VỤ -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-dollar-sign"></i> Giá dịch vụ mặc định</h3>
                    </div>
                    <div class="form-layout">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Giá thuê locker (VNĐ/tháng)</label>
                                <input type="number" 
                                       name="locker_price" 
                                       class="form-control"
                                       min="0"
                                       value="<?= (int)($settings['locker_price'] ?? 100000) ?>">
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label class="form-label">Giá đặt phòng (VNĐ/giờ)</label>
                                <input type="number" 
                                       name="booking_price" 
                                       class="form-control"
                                       min="0"
                                       value="<?= (int)($settings['booking_price'] ?? 50000) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CẤU HÌNH HỆ THỐNG -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-server"></i> Cấu hình hệ thống</h3>
                    </div>
                    <div class="form-layout">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Múi giờ</label>
                                <select name="timezone" class="form-control">
                                    <option value="Asia/Ho_Chi_Minh" selected>GMT+7 (Việt Nam)</option>
                                    <option value="Asia/Bangkok">GMT+7 (Bangkok)</option>
                                    <option value="Asia/Singapore">GMT+8 (Singapore)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label class="form-label">Ngôn ngữ</label>
                                <select name="language" class="form-control">
                                    <option value="vi" selected>Tiếng Việt</option>
                                    <option value="en">English</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- THÔNG TIN ADMIN -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user-shield"></i> Thông tin quản trị viên</h3>
                    </div>
                    <div class="form-layout">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Tên đăng nhập</label>
                                <input type="text" 
                                       class="form-control"
                                       value="HungHIHI1603"
                                       disabled>
                                <small style="color: var(--text-secondary);">Liên hệ IT để thay đổi</small>
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label class="form-label">Email quản trị</label>
                                <input type="email" 
                                       class="form-control"
                                       value="admin@blocksports.vn"
                                       disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BACKUP & BẢO TRÌ -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-database"></i> Backup & Bảo trì</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                            <button type="button" class="btn btn-ghost" onclick="alert('Tính năng đang phát triển!')">
                                <i class="fas fa-download"></i> Backup dữ liệu
                            </button>
                            <button type="button" class="btn btn-ghost" onclick="alert('Tính năng đang phát triển!')">
                                <i class="fas fa-upload"></i> Khôi phục dữ liệu
                            </button>
                            <button type="button" class="btn btn-ghost" onclick="alert('Tính năng đang phát triển!')">
                                <i class="fas fa-broom"></i> Dọn dẹp cache
                            </button>
                        </div>
                    </div>
                </div>

                <!-- NÚT LƯU -->
                <div class="form-actions">
                    <button type="reset" class="btn btn-ghost">
                        <i class="fas fa-redo"></i> Đặt lại
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu cài đặt
                    </button>
                </div>
            </form>

        </div>
    </main>
</div>

<script src="/block-sports-center/public/assets/js/main.js"></script>
</body>
</html>