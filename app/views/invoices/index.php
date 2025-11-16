<?php 
$pageTitle = 'Quản lý hóa đơn';
$currentPage = 'invoices';
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
                    <h2>Quản lý hóa đơn</h2>
                    <p>Danh sách tất cả hóa đơn trong hệ thống</p>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách hóa đơn</h3>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tạo hóa đơn mới
                        </button>
                    </div>
                    
                    <!-- SEARCH TRONG CARD -->
                    <div class="search-bar">
                        <input type="text" placeholder="Tìm kiếm theo mã hóa đơn, tên hội viên...">
                        <select class="form-control" style="max-width: 200px;">
                            <option value="">Tất cả trạng thái</option>
                            <option value="PAID">Đã thanh toán</option>
                            <option value="PENDING">Chờ thanh toán</option>
                            <option value="OVERDUE">Quá hạn</option>
                            <option value="CANCELLED">Đã hủy</option>
                        </select>
                        <button class="btn btn-ghost">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Mã hóa đơn</th>
                                    <th>Ngày tạo</th>
                                    <th>Hội viên</th>
                                    <th>Nội dung</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>#INV001</strong></td>
                                    <td>14/11/2024</td>
                                    <td>Nguyễn Văn An</td>
                                    <td>Gói tập 3 tháng + PT</td>
                                    <td><strong>4.500.000đ</strong></td>
                                    <td><span class="badge active">Paid</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" onclick="location.href='/block-sports-center/public/index.php?page=invoices-view&id=1'"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-print"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#INV002</strong></td>
                                    <td>13/11/2024</td>
                                    <td>Trần Thị Bình</td>
                                    <td>Gói tập 1 tháng</td>
                                    <td><strong>1.200.000đ</strong></td>
                                    <td><span class="badge scheduled">Pending</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" onclick="location.href='/block-sports-center/public/index.php?page=invoices-view&id=2'"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-print"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#INV003</strong></td>
                                    <td>12/11/2024</td>
                                    <td>Lê Hoàng Cường</td>
                                    <td>Thuê sân tennis</td>
                                    <td><strong>300.000đ</strong></td>
                                    <td><span class="badge active">Paid</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" onclick="location.href='/block-sports-center/public/index.php?page=invoices-view&id=3'"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-print"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#INV004</strong></td>
                                    <td>11/11/2024</td>
                                    <td>Phạm Thị Dung</td>
                                    <td>Gói tập 6 tháng</td>
                                    <td><strong>6.000.000đ</strong></td>
                                    <td><span class="badge active">Paid</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" onclick="location.href='/block-sports-center/public/index.php?page=invoices-view&id=4'"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-print"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#INV005</strong></td>
                                    <td>10/11/2024</td>
                                    <td>Vũ Minh Em</td>
                                    <td>Gia hạn thẻ locker</td>
                                    <td><strong>200.000đ</strong></td>
                                    <td><span class="badge suspended">Overdue</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" onclick="location.href='/block-sports-center/public/index.php?page=invoices-view&id=5'"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-print"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#INV006</strong></td>
                                    <td>09/11/2024</td>
                                    <td>Đỗ Thu Hằng</td>
                                    <td>Gói PT 10 buổi</td>
                                    <td><strong>3.500.000đ</strong></td>
                                    <td><span class="badge active">Paid</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" onclick="location.href='/block-sports-center/public/index.php?page=invoices-view&id=6'"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-print"></i></button>
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
    
    <script src="/block-sports-center/public/assets/js/main.js"></script>
</body>
</html>