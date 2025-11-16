<?php 
$pageTitle = 'Quản lý tủ khóa';
$currentPage = 'lockers';
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
                    <h2>Quản lý tủ khóa</h2>
                    <p>Theo dõi và quản lý tủ khóa cho hội viên</p>
                </div>
                
                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Tổng tủ khóa</div>
                        <div class="stat-value">200</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Đang sử dụng</div>
                        <div class="stat-value">142</div>
                        <div class="stat-change">Tỷ lệ: 71%</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Còn trống</div>
                        <div class="stat-value">58</div>
                        <div class="stat-change">29% khả dụng</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Sắp hết hạn</div>
                        <div class="stat-value">12</div>
                        <div class="stat-change" style="color: var(--danger);">Cần gia hạn</div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách tủ khóa</h3>
                        <div class="search-bar" style="margin: 0;">
                            <select class="form-control" style="max-width: 150px;">
                                <option value="">Tất cả</option>
                                <option value="OCCUPIED">Đang thuê</option>
                                <option value="AVAILABLE">Trống</option>
                                <option value="MAINTENANCE">Bảo trì</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Số tủ</th>
                                    <th>Khu vực</th>
                                    <th>Hội viên</th>
                                    <th>Ngày thuê</th>
                                    <th>Hết hạn</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>#L001</strong></td>
                                    <td>Tầng 1 - Nam</td>
                                    <td>Nguyễn Văn An</td>
                                    <td>01/11/2024</td>
                                    <td>01/12/2024</td>
                                    <td><span class="badge active">Đang thuê</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" title="Gia hạn"><i class="fas fa-sync"></i></button>
                                            <button class="action-btn delete" title="Hủy"><i class="fas fa-times"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#L002</strong></td>
                                    <td>Tầng 1 - Nữ</td>
                                    <td>Trần Thị Bình</td>
                                    <td>05/11/2024</td>
                                    <td>05/12/2024</td>
                                    <td><span class="badge active">Đang thuê</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-sync"></i></button>
                                            <button class="action-btn delete"><i class="fas fa-times"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#L003</strong></td>
                                    <td>Tầng 2 - Nam</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><span class="badge scheduled">Trống</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" title="Cho thuê"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#L004</strong></td>
                                    <td>Tầng 1 - Nam</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><span class="badge inactive">Bảo trì</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-wrench"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
</body>
</html>