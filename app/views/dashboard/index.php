<?php 
/**
 * Dashboard View
 * Updated: 2025-11-18 14:40:00 UTC
 * Added: Hiển thị ngày tháng cho buổi lớp
 * Author: @huyryan220806
 */

$pageTitle = 'Dashboard';
$currentPage = 'dashboard';

$memberStats  = $data['memberStats']  ?? ['total' => 0, 'change' => 0, 'direction' => 'up'];
$revenueStats = $data['revenueStats'] ?? ['formatted' => '0', 'change' => 0, 'direction' => 'up'];
$sessionStats = $data['sessionStats'] ?? ['total' => 0, 'completed' => 0, 'ongoing' => 0];
$roomStats    = $data['roomStats']    ?? ['total' => 0, 'used' => 0, 'percentage' => 0];
$upcomingSessions = $data['upcomingSessions'] ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - BLOCK SPORTS CENTER</title>
    <link rel="stylesheet" href="/block-sports-center/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .quick-action-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            transition: all 0.3s;
            border: 2px solid #e9ecef;
        }

        .quick-action-icon i {
            font-size: 32px;
            transition: all 0.3s;
        }

        .quick-action-card:hover .quick-action-icon {
            border-color: #00b894;
            background: rgba(0, 184, 148, 0.05);
            transform: scale(1.1);
        }

        .quick-action-card:hover .quick-action-icon i {
            transform: scale(1.1);
        }

        /* ✅ Style cho cột ngày */
        .date-column {
            min-width: 140px;
            font-weight: 600;
        }
        
        .date-day {
            font-size: 14px;
            color: var(--primary);
            font-weight: 700;
        }
        
        .date-date {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php include(__DIR__ . '/../layouts/sidebar.php'); ?>
        <main class="main-content">
            <?php include(__DIR__ . '/../layouts/header.php'); ?>
            <div class="content">
                <?php include(__DIR__ . '/../layouts/alerts.php'); ?>
                
                <div class="page-header">
                    <h2><i class="fas fa-chart-line"></i> Dashboard</h2>
                    <p>Tổng quan hoạt động trung tâm thể thao</p>
                </div>
                
                <!-- STATS GRID -->
                <div class="stats-grid">
                    <!-- Hội viên hoạt động -->
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-users"></i> Hội viên hoạt động
                        </div>
                        <div class="stat-value"><?= number_format($memberStats['total']) ?></div>
                        <div class="stat-change">
                            <?php if ($memberStats['change'] >= 0): ?>
                                <span style="color: #00b894;">
                                    <i class="fas fa-arrow-up"></i> <?= abs($memberStats['change']) ?>%
                                </span>
                            <?php else: ?>
                                <span style="color: #e74c3c;">
                                    <i class="fas fa-arrow-down"></i> <?= abs($memberStats['change']) ?>%
                                </span>
                            <?php endif; ?>
                            so với tháng trước
                        </div>
                    </div>
                    
                    <!-- Doanh thu tháng -->
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-money-bill-wave"></i> Doanh thu tháng
                        </div>
                        <div class="stat-value"><?= $revenueStats['formatted'] ?></div>
                        <div class="stat-change">
                            <?php if ($revenueStats['change'] >= 0): ?>
                                <span style="color: #00b894;">
                                    <i class="fas fa-arrow-up"></i> <?= abs($revenueStats['change']) ?>%
                                </span>
                            <?php else: ?>
                                <span style="color: #e74c3c;">
                                    <i class="fas fa-arrow-down"></i> <?= abs($revenueStats['change']) ?>%
                                </span>
                            <?php endif; ?>
                            so với tháng trước
                        </div>
                    </div>
                    
                    <!-- Buổi lớp hôm nay -->
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-calendar-day"></i> Buổi lớp hôm nay
                        </div>
                        <div class="stat-value"><?= $sessionStats['total'] ?></div>
                        <div class="stat-change">
                            <?= $sessionStats['completed'] ?> đã hoàn thành • 
                            <span style="color: #00b894;"><?= $sessionStats['ongoing'] ?> đang diễn ra</span>
                        </div>
                    </div>
                    
                    <!-- Phòng đang sử dụng -->
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-door-open"></i> Phòng đang sử dụng
                        </div>
                        <div class="stat-value"><?= $roomStats['used'] ?>/<?= $roomStats['total'] ?></div>
                        <div class="stat-change">
                            Tỷ lệ: <strong style="color: #00b894;"><?= $roomStats['percentage'] ?>%</strong>
                        </div>
                    </div>
                </div>
                
                <!-- QUICK ACTIONS -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-bolt"></i> Thao tác nhanh</h3>
                    </div>
                    
                    <div class="quick-actions">
                        <div class="quick-action-card" onclick="location.href='?c=members&a=create'">
                            <div class="quick-action-icon">
                                <i class="fas fa-user-plus" style="color: #667eea;"></i>
                            </div>
                            <div class="quick-action-title">Tạo hợp đồng</div>
                        </div>
                        
                        <div class="quick-action-card" onclick="location.href='?c=sessions&a=create'">
                            <div class="quick-action-icon">
                                <i class="fas fa-calendar-plus" style="color: #f093fb;"></i>
                            </div>
                            <div class="quick-action-title">Thêm buổi lớp</div>
                        </div>
                        
                        <div class="quick-action-card" onclick="location.href='?c=bookings&a=create'">
                            <div class="quick-action-icon">
                                <i class="fas fa-door-open" style="color: #4facfe;"></i>
                            </div>
                            <div class="quick-action-title">Đặt phòng nhanh</div>
                        </div>
                        
                        <div class="quick-action-card" onclick="location.href='?c=invoices&a=create'">
                            <div class="quick-action-icon">
                                <i class="fas fa-file-invoice" style="color: #43e97b;"></i>
                            </div>
                            <div class="quick-action-title">Lập hóa đơn</div>
                        </div>
                        
                        <div class="quick-action-card" onclick="location.href='?c=lockers&a=index'">
                            <div class="quick-action-icon">
                                <i class="fas fa-lock" style="color: #fa709a;"></i>
                            </div>
                            <div class="quick-action-title">Quản lý locker</div>
                        </div>
                        
                        <div class="quick-action-card" onclick="location.href='?c=reports&a=index'">
                            <div class="quick-action-icon">
                                <i class="fas fa-chart-pie" style="color: #feca57;"></i>
                            </div>
                            <div class="quick-action-title">Báo cáo tháng</div>
                        </div>
                    </div>
                </div>
                
                <!-- UPCOMING SESSIONS -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-clock"></i> Buổi lớp sắp diễn ra</h3>
                        <a href="?c=sessions&a=index" class="btn btn-sm btn-ghost">
                            Xem tất cả <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    
                    <!-- SEARCH BAR -->
                    <div class="search-bar">
                        <input type="text" 
                               id="sessionSearchInput" 
                               placeholder="Tìm kiếm buổi lớp, phòng, HLV...">
                        <button class="btn btn-ghost" type="button">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th class="date-column">Ngày</th>
                                    <th>Thời gian</th>
                                    <th>Tên lớp</th>
                                    <th>Phòng</th>
                                    <th>Huấn luyện viên</th>
                                    <th>Chỗ</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody id="sessionTableBody">
                                <?php if (!empty($upcomingSessions)): ?>
                                    <?php 
                                    $now = new DateTime();
                                    $daysVietnamese = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
                                    
                                    foreach ($upcomingSessions as $s): 
                                        $start = new DateTime($s->BATDAU);
                                        $end = new DateTime($s->KETTHUC);
                                        
                                        // ✅ Format ngày tháng
                                        $dayOfWeek = $daysVietnamese[$start->format('w')];
                                        $dateFormatted = $start->format('d/m/Y');
                                        $timeRange = $start->format('H:i') . ' - ' . $end->format('H:i');

                                        $capacity = isset($s->SUCCHUA) ? (int)$s->SUCCHUA : 0;
                                        $slotsText = $capacity > 0 ? ($s->SISO . '/' . $capacity) : $s->SISO;
                                        $isFull = $capacity > 0 && $s->SISO >= $capacity;
                                        $diffHours = ($start->getTimestamp() - $now->getTimestamp()) / 3600;

                                        if ($isFull) {
                                            $badgeClass = 'full';
                                            $badgeLabel = 'FULL';
                                        } elseif ($diffHours <= 24) {
                                            $badgeClass = 'active';
                                            $badgeLabel = 'SẮP DIỄN RA';
                                        } else {
                                            $badgeClass = 'scheduled';
                                            $badgeLabel = 'SCHEDULED';
                                        }
                                    ?>
                                        <tr>
                                            <td class="date-column">
                                                <div class="date-day"><?= $dayOfWeek ?></div>
                                                <div class="date-date"><?= $dateFormatted ?></div>
                                            </td>
                                            <td><?= $timeRange ?></td>
                                            <td><?= htmlspecialchars($s->TENLOP) ?></td>
                                            <td><?= htmlspecialchars($s->TENPHONG) ?></td>
                                            <td><?= htmlspecialchars($s->TEN_HLV) ?></td>
                                            <td><?= $slotsText ?></td>
                                            <td>
                                                <span class="badge <?= $badgeClass ?>">
                                                    <?= $badgeLabel ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align:center; padding:40px;">
                                            <i class="fas fa-calendar-times" style="font-size: 48px; color: #ddd; margin-bottom: 12px;"></i>
                                            <p style="color: #999;">Chưa có buổi lớp nào trong 7 ngày tới.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </main>
    </div>
    
    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
    
    <script>
    // Tìm kiếm buổi lớp
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('sessionSearchInput');
        const searchBtn = document.querySelector('.search-bar .btn-ghost');
        const tableBody = document.getElementById('sessionTableBody');
        
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
        
        if (searchBtn) {
            searchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                performSearch();
            });
        }
        
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
</body>
</html>