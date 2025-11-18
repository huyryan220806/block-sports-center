<?php
$pageTitle   = 'Báo cáo';
$currentPage = 'reports';

$stats             = $data['stats']             ?? [];
$revenueByMonth    = $data['revenueByMonth']    ?? [];
$newMembersByMonth = $data['newMembersByMonth'] ?? [];
$topMembers        = $data['topMembers']        ?? [];
$topRooms          = $data['topRooms']          ?? [];
$topClasses        = $data['topClasses']        ?? [];
$contractStats     = $data['contractStats']     ?? [];
$paymentMethods    = $data['paymentMethods']    ?? [];
$startDate         = $data['startDate']         ?? date('Y-m-d', strtotime('-30 days'));
$endDate           = $data['endDate']           ?? date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - BLOCK SPORTS CENTER</title>
    <link rel="stylesheet" href="/block-sports-center/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="admin-layout">
    <?php include(__DIR__ . '/../layouts/sidebar.php'); ?>

    <main class="main-content">
        <?php include(__DIR__ . '/../layouts/header.php'); ?>

        <div class="content">
            <div class="page-header">
                <h2><i class="fas fa-chart-pie"></i> Báo cáo tổng quan</h2>
                <p>Thống kê và phân tích dữ liệu trung tâm thể thao</p>
            </div>

            <!-- BỘ LỌC THỜI GIAN -->
            <div class="card">
                <form method="get" action="?c=reports&a=index" class="search-bar">
                    <input type="hidden" name="c" value="reports">
                    <input type="hidden" name="a" value="index">
                    <label style="font-weight: 600;">Từ ngày:</label>
                    <input type="date" name="start_date" value="<?= $startDate ?>" class="form-control" style="width: auto;">
                    <label style="font-weight: 600;">Đến ngày:</label>
                    <input type="date" name="end_date" value="<?= $endDate ?>" class="form-control" style="width: auto;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                </form>
            </div>

            <!-- THỐNG KÊ TỔNG QUAN -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label"><i class="fas fa-users"></i> Tổng hội viên</div>
                    <div class="stat-value"><?= number_format($stats['total_members']) ?></div>
                    <div class="stat-change">
                        <i class="fas fa-user-check"></i> <?= number_format($stats['active_members']) ?> đang hoạt động
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-label"><i class="fas fa-coins"></i> Doanh thu</div>
                    <div class="stat-value"><?= number_format($stats['total_revenue'], 0, ',', '.') ?>đ</div>
                    <div class="stat-change">
                        <i class="fas fa-calendar-alt"></i> Từ <?= date('d/m', strtotime($startDate)) ?> đến <?= date('d/m', strtotime($endDate)) ?>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-label"><i class="fas fa-calendar-check"></i> Lượt đặt phòng</div>
                    <div class="stat-value"><?= number_format($stats['total_bookings']) ?></div>
                    <div class="stat-change">
                        <i class="fas fa-door-open"></i> <?= number_format($stats['total_rooms']) ?> phòng/sân hoạt động
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-label"><i class="fas fa-chalkboard-teacher"></i> Lớp học & HLV</div>
                    <div class="stat-value"><?= number_format($stats['total_classes']) ?></div>
                    <div class="stat-change">
                        <i class="fas fa-user-tie"></i> <?= number_format($stats['total_trainers']) ?> huấn luyện viên
                    </div>
                </div>
            </div>

            <!-- BIỂU ĐỒ DOANH THU -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line"></i> Doanh thu 12 tháng gần nhất</h3>
                </div>
                <canvas id="revenueChart" style="max-height: 400px; padding: 20px;"></canvas>
            </div>

            <!-- BIỂU ĐỒ HỘI VIÊN MỚI -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-plus"></i> Hội viên mới 12 tháng gần nhất</h3>
                </div>
                <canvas id="memberChart" style="max-height: 400px; padding: 20px;"></canvas>
            </div>

            <!-- 2 BIỂU ĐỒ TRÒN: HỢP ĐỒNG & THANH TOÁN -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <!-- Biểu đồ tròn: Trạng thái hợp đồng -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-file-contract"></i> Trạng thái hợp đồng</h3>
                    </div>
                    <canvas id="contractChart" style="max-height: 300px; padding: 20px;"></canvas>
                </div>

                <!-- Biểu đồ tròn: Phương thức thanh toán -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-credit-card"></i> Phương thức thanh toán</h3>
                    </div>
                    <canvas id="paymentChart" style="max-height: 300px; padding: 20px;"></canvas>
                </div>
            </div>

            <!-- TOP HỘI VIÊN TÍCH CỰC -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-trophy"></i> Top 5 hội viên tích cực nhất</h3>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Mã HV</th>
                                <th>Họ tên</th>
                                <th>Số điện thoại</th>
                                <th>Check-in</th>
                                <th>Đặt phòng</th>
                                <th>Đăng ký lớp</th>
                                <th>Điểm hoạt động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topMembers)): ?>
                                <?php foreach ($topMembers as $member): ?>
                                    <tr>
                                        <td><?= $member->MAHV ?></td>
                                        <td><?= htmlspecialchars($member->HOVATEN) ?></td>
                                        <td><?= htmlspecialchars($member->SDT) ?></td>
                                        <td><?= number_format($member->checkin_count) ?></td>
                                        <td><?= number_format($member->booking_count) ?></td>
                                        <td><?= number_format($member->class_count) ?></td>
                                        <td><span class="badge active"><?= number_format($member->activity_score) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" style="text-align:center;">Chưa có dữ liệu</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TOP PHÒNG/SÂN ĐƯỢC ĐẶT NHIỀU NHẤT -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-fire"></i> Top 5 phòng/sân được đặt nhiều nhất</h3>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Mã phòng</th>
                                <th>Tên phòng</th>
                                <th>Khu vực</th>
                                <th>Sức chứa</th>
                                <th>Lượt đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topRooms)): ?>
                                <?php foreach ($topRooms as $room): ?>
                                    <tr>
                                        <td><?= $room->MAPHONG ?></td>
                                        <td><?= htmlspecialchars($room->TENPHONG) ?></td>
                                        <td><?= htmlspecialchars($room->TENKHU) ?></td>
                                        <td><?= number_format($room->SUCCHUA) ?></td>
                                        <td><span class="badge active"><?= number_format($room->booking_count) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" style="text-align:center;">Chưa có dữ liệu</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TOP LỚP HỌC ĐƯỢC ĐĂNG KÝ NHIỀU NHẤT -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-star"></i> Top 5 lớp học được đăng ký nhiều nhất</h3>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Mã lớp</th>
                                <th>Tên lớp</th>
                                <th>Thời lượng (phút)</th>
                                <th>Số buổi</th>
                                <th>Lượt đăng ký</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topClasses)): ?>
                                <?php foreach ($topClasses as $class): ?>
                                    <tr>
                                        <td><?= $class->MALOP ?></td>
                                        <td><?= htmlspecialchars($class->TENLOP) ?></td>
                                        <td><?= number_format($class->THOILUONG) ?> phút</td>
                                        <td><?= number_format($class->session_count) ?></td>
                                        <td><span class="badge active"><?= number_format($class->registration_count) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" style="text-align:center;">Chưa có dữ liệu</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</div>

<script src="/block-sports-center/public/assets/js/main.js"></script>
<script>
// ===== BIỂU ĐỒ DOANH THU =====
const revenueData = <?= json_encode($revenueByMonth) ?>;
const revenueLabels = revenueData.map(item => item.month);
const revenueValues = revenueData.map(item => parseFloat(item.revenue));

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: revenueValues,
            backgroundColor: 'rgba(127, 255, 212, 0.6)',
            borderColor: 'rgba(127, 255, 212, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: { 
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + 'đ';
                    }
                }
            }
        }
    }
});

// ===== BIỂU ĐỒ HỘI VIÊN MỚI =====
const memberData = <?= json_encode($newMembersByMonth) ?>;
const memberLabels = memberData.map(item => item.month);
const memberValues = memberData.map(item => parseInt(item.total));

new Chart(document.getElementById('memberChart'), {
    type: 'line',
    data: {
        labels: memberLabels,
        datasets: [{
            label: 'Hội viên mới',
            data: memberValues,
            backgroundColor: 'rgba(95, 217, 180, 0.2)',
            borderColor: 'rgba(95, 217, 180, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// ===== BIỂU ĐỒ TRÒN: TRẠNG THÁI HỢP ĐỒNG =====
const contractStats = <?= json_encode($contractStats) ?>;
const contractLabels = contractStats.map(item => item.TRANGTHAI);
const contractValues = contractStats.map(item => parseInt(item.total));

new Chart(document.getElementById('contractChart'), {
    type: 'doughnut',
    data: {
        labels: contractLabels,
        datasets: [{
            data: contractValues,
            backgroundColor: [
                'rgba(81, 207, 102, 0.7)',
                'rgba(255, 211, 61, 0.7)',
                'rgba(158, 158, 158, 0.7)',
                'rgba(255, 107, 107, 0.7)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// ===== BIỂU ĐỒ TRÒN: PHƯƠNG THỨC THANH TOÁN =====
const paymentMethods = <?= json_encode($paymentMethods) ?>;
const paymentLabels = paymentMethods.map(item => item.PHUONGTHUC);
const paymentValues = paymentMethods.map(item => parseFloat(item.total_amount));

new Chart(document.getElementById('paymentChart'), {
    type: 'pie',
    data: {
        labels: paymentLabels,
        datasets: [{
            data: paymentValues,
            backgroundColor: [
                'rgba(127, 255, 212, 0.7)',
                'rgba(95, 217, 180, 0.7)',
                'rgba(81, 207, 102, 0.7)',
                'rgba(255, 211, 61, 0.7)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed.toLocaleString('vi-VN') + 'đ';
                    }
                }
            }
        }
    }
});
</script>
</body>
</html>