<?php 
$pageTitle = 'Báo cáo';
$currentPage = 'reports';
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
                    <h2>Báo cáo & Thống kê</h2>
                    <p>Xem báo cáo hoạt động và doanh thu</p>
                </div>
                
                <!-- Filters -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bộ lọc báo cáo</h3>
                    </div>
                    <div class="search-bar">
                        <select class="form-control" style="max-width: 200px;">
                            <option value="month">Tháng này</option>
                            <option value="quarter">Quý này</option>
                            <option value="year">Năm này</option>
                            <option value="custom">Tùy chỉnh</option>
                        </select>
                        <input type="date" class="form-control" style="max-width: 180px;">
                        <span style="padding: 0 10px;">đến</span>
                        <input type="date" class="form-control" style="max-width: 180px;">
                        <button class="btn btn-primary">
                            <i class="fas fa-chart-bar"></i> Xem báo cáo
                        </button>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Doanh thu tháng</div>
                        <div class="stat-value">128.5M</div>
                        <div class="stat-change">↑ 8% vs tháng trước</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Hội viên mới</div>
                        <div class="stat-value">34</div>
                        <div class="stat-change">↑ 12% vs tháng trước</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Tổng buổi lớp</div>
                        <div class="stat-value">156</div>
                        <div class="stat-change">↑ 5% vs tháng trước</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Tỷ lệ tham gia</div>
                        <div class="stat-value">87%</div>
                        <div class="stat-change">↑ 2% vs tháng trước</div>
                    </div>
                </div>
                
                <!-- Quick Reports -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Báo cáo nhanh</h3>
                    </div>
                    <div class="quick-actions">
                        <div class="quick-action-card">
                            <div class="quick-action-icon"><i class="fas fa-file-excel"></i></div>
                            <div class="quick-action-title">Xuất Excel</div>
                        </div>
                        <div class="quick-action-card">
                            <div class="quick-action-icon"><i class="fas fa-file-pdf"></i></div>
                            <div class="quick-action-title">Xuất PDF</div>
                        </div>
                        <div class="quick-action-card">
                            <div class="quick-action-icon"><i class="fas fa-users"></i></div>
                            <div class="quick-action-title">BC Hội viên</div>
                        </div>
                        <div class="quick-action-card">
                            <div class="quick-action-icon"><i class="fas fa-dollar-sign"></i></div>
                            <div class="quick-action-title">BC Doanh thu</div>
                        </div>
                        <div class="quick-action-card">
                            <div class="quick-action-icon"><i class="fas fa-calendar-alt"></i></div>
                            <div class="quick-action-title">BC Buổi lớp</div>
                        </div>
                        <div class="quick-action-card">
                            <div class="quick-action-icon"><i class="fas fa-chart-line"></i></div>
                            <div class="quick-action-title">BC Tổng hợp</div>
                        </div>
                    </div>
                </div>
                
                <!-- Revenue Chart -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Biểu đồ doanh thu 12 tháng</h3>
                    </div>
                    <div style="padding: 40px; text-align: center; color: #999;">
                        <i class="fas fa-chart-area" style="font-size: 64px; color: var(--primary);"></i>
                        <p style="margin-top: 20px;">Biểu đồ sẽ hiển thị ở đây</p>
                        <p style="font-size: 13px;">(Cần tích hợp Chart.js hoặc thư viện vẽ biểu đồ)</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
</body>
</html>