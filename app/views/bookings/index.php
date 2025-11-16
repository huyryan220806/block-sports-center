<?php 
$pageTitle = 'Quản lý đặt phòng';
$currentPage = 'bookings';
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
                    <h2>Quản lý đặt phòng</h2>
                    <p>Lịch đặt phòng/sân cho hội viên</p>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách đặt phòng</h3>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> Đặt phòng mới
                        </button>
                    </div>
                    
                    <div class="search-bar">
                        <input type="date" class="form-control" style="max-width: 180px;" value="<?php echo date('Y-m-d'); ?>">
                        <select class="form-control" style="max-width: 200px;">
                            <option value="">Tất cả phòng</option>
                            <option value="A1">Phòng A1</option>
                            <option value="B2">Phòng B2</option>
                            <option value="Tennis1">Sân Tennis 1</option>
                        </select>
                        <button class="btn btn-ghost">
                            <i class="fas fa-search"></i> Lọc
                        </button>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Mã đặt</th>
                                    <th>Ngày</th>
                                    <th>Thời gian</th>
                                    <th>Phòng/Sân</th>
                                    <th>Hội viên</th>
                                    <th>Mục đích</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>#BK001</strong></td>
                                    <td>15/11/2024</td>
                                    <td>14:00 - 16:00</td>
                                    <td>Sân Tennis 1</td>
                                    <td>Lê Hoàng Cường</td>
                                    <td>Chơi tennis</td>
                                    <td><span class="badge scheduled">Confirmed</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete"><i class="fas fa-times"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#BK002</strong></td>
                                    <td>16/11/2024</td>
                                    <td>10:00 - 12:00</td>
                                    <td>Phòng Dance B2</td>
                                    <td>Nhóm Dance Club</td>
                                    <td>Tập nhóm</td>
                                    <td><span class="badge scheduled">Confirmed</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete"><i class="fas fa-times"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#BK003</strong></td>
                                    <td>17/11/2024</td>
                                    <td>18:00 - 20:00</td>
                                    <td>Phòng Boxing C1</td>
                                    <td>Phạm Thị Dung</td>
                                    <td>PT riêng</td>
                                    <td><span class="badge full">Pending</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" title="Xác nhận"><i class="fas fa-check"></i></button>
                                            <button class="action-btn delete"><i class="fas fa-times"></i></button>
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