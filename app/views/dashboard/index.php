<?php 
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
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
                    <h2>Dashboard</h2>
                    <p>Tổng quan hoạt động trung tâm thể thao</p>
                </div>
                
                <!-- STATS GRID -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Hội viên hoạt động</div>
                        <div class="stat-value">247</div>
                        <div class="stat-change">↑ 12% so với tháng trước</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-label">Doanh thu tháng</div>
                        <div class="stat-value">128.5M</div>
                        <div class="stat-change">↑ 8% so với tháng trước</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-label">Buổi lớp hôm nay</div>
                        <div class="stat-value">18</div>
                        <div class="stat-change">6 đã hoàn thành</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-label">Phòng đang sử dụng</div>
                        <div class="stat-value">7/12</div>
                        <div class="stat-change">Tỷ lệ: 58%</div>
                    </div>
                </div>
                
                <!-- QUICK ACTIONS -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thao tác nhanh</h3>
                    </div>
                    
                    <div class="quick-actions">
                        <div class="quick-action-card" onclick="location.href='/block-sports-center/public/index.php?page=members-create'">
                            <div class="quick-action-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="quick-action-title">Tạo hợp đồng</div>
                        </div>
                        
                        <div class="quick-action-card" onclick="location.href='/block-sports-center/public/index.php?page=sessions'">
                            <div class="quick-action-icon">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div class="quick-action-title">Thêm buổi lớp</div>
                        </div>
                        
                        <div class="quick-action-card" onclick="location.href='/block-sports-center/public/index.php?page=bookings'">
                            <div class="quick-action-icon">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <div class="quick-action-title">Đặt phòng nhanh</div>
                        </div>
                        
                        <div class="quick-action-card" onclick="location.href='/block-sports-center/public/index.php?page=invoices'">
                            <div class="quick-action-icon">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="quick-action-title">Lập hóa đơn</div>
                        </div>
                        
                        <div class="quick-action-card" onclick="location.href='/block-sports-center/public/index.php?page=lockers'">
                            <div class="quick-action-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="quick-action-title">Quản lý locker</div>
                        </div>
                        
                        <div class="quick-action-card" onclick="location.href='/block-sports-center/public/index.php?page=reports'">
                            <div class="quick-action-icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <div class="quick-action-title">Báo cáo tháng</div>
                        </div>
                    </div>
                </div>
                
                <!-- UPCOMING SESSIONS - SEARCH TRONG CARD -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Buổi lớp sắp diễn ra</h3>
                        <a href="/block-sports-center/public/index.php?page=sessions" class="btn btn-sm btn-ghost">Xem tất cả</a>
                    </div>
                    
                    <!-- SEARCH BAR TRONG CARD -->
                    <div class="search-bar">
                        <input type="text" id="sessionSearchInput" placeholder="Tìm kiếm buổi lớp, phòng, HLV...">
                        <button class="btn btn-ghost">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Tên lớp</th>
                                    <th>Phòng</th>
                                    <th>Huấn luyện viên</th>
                                    <th>Chỗ</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody id="sessionTableBody">
                                <tr>
                                    <td>08:00 - 09:30</td>
                                    <td>Yoga Căn Bản</td>
                                    <td>Phòng A1</td>
                                    <td>Nguyễn Thị Lan</td>
                                    <td>15/20</td>
                                    <td><span class="badge scheduled">Scheduled</span></td>
                                </tr>
                                <tr>
                                    <td>09:00 - 10:00</td>
                                    <td>Gym Strength</td>
                                    <td>Gym Floor</td>
                                    <td>Trần Văn Mạnh</td>
                                    <td>25/25</td>
                                    <td><span class="badge full">Full</span></td>
                                </tr>
                                <tr>
                                    <td>10:00 - 11:00</td>
                                    <td>Zumba Dance</td>
                                    <td>Phòng B2</td>
                                    <td>Lê Thu Hà</td>
                                    <td>18/30</td>
                                    <td><span class="badge scheduled">Scheduled</span></td>
                                </tr>
                                <tr>
                                    <td>14:00 - 15:30</td>
                                    <td>Boxing Cơ Bản</td>
                                    <td>Phòng C1</td>
                                    <td>Phạm Quốc Tuấn</td>
                                    <td>10/15</td>
                                    <td><span class="badge scheduled">Scheduled</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </main>
    </div>
    
    <script>
    // ========== TÌM KIẾM BUỔI LỚP ==========
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('sessionSearchInput');
        const searchBtn = document.querySelector('.search-bar .btn-ghost');
        const tableBody = document.getElementById('sessionTableBody');
        
        // Hàm thực hiện tìm kiếm
        function performSearch() {
            if (searchInput && tableBody) {
                const filter = searchInput.value.toLowerCase().trim();
                const rows = tableBody.getElementsByTagName('tr');
                
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const text = row.textContent.toLowerCase();
                    
                    if (filter === '' || text.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            }
        }
        
        // Khi click nút "Tìm kiếm"
        if (searchBtn) {
            searchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                performSearch();
            });
        }
        
        // Hoặc nhấn Enter trong ô input
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });
        }
    });
    </script>
    
    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
</body>
</html>