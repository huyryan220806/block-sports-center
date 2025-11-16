<?php 
$pageTitle   = 'Chi tiết hóa đơn';
$currentPage = 'invoices';

include(__DIR__ . '/../layouts/header.php'); 
include(__DIR__ . '/../layouts/sidebar.php'); 
?>

<main class="main-content">
    <div class="content">
        <div class="page-header">
            <h2>Chi tiết hóa đơn #INV001</h2>
            <p>Thông tin chi tiết hóa đơn thanh toán dịch vụ</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin chung</h3>
                <a href="/block-sports-center/public/index.php?page=invoices" 
                   class="btn btn-ghost btn-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>

            <div class="invoice-info" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                <div>
                    <p><strong>Mã hóa đơn:</strong> #INV001</p>
                    <p><strong>Ngày lập:</strong> 14/11/2024</p>
                    <p><strong>Trạng thái:</strong> <span class="badge active">Paid</span></p>
                </div>
                <div>
                    <p><strong>Hội viên:</strong> Nguyễn Văn An</p>
                    <p><strong>Số điện thoại:</strong> 0901234567</p>
                    <p><strong>Email:</strong> nguyenvanan@email.com</p>
                </div>
                <div>
                    <p><strong>Phương thức thanh toán:</strong> Chuyển khoản</p>
                    <p><strong>Nhân viên lập hóa đơn:</strong> Trần Thị B</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Chi tiết dịch vụ</h3>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Dịch vụ</th>
                            <th>Gói / Thời hạn</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Gói tập Gym toàn thời gian</td>
                            <td>3 tháng</td>
                            <td>1</td>
                            <td>2.500.000đ</td>
                            <td><strong>2.500.000đ</strong></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Dịch vụ PT 1:1</td>
                            <td>12 buổi</td>
                            <td>1</td>
                            <td>2.000.000đ</td>
                            <td><strong>2.000.000đ</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tổng kết thanh toán</h3>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <div style="max-width: 320px; width: 100%;">
                    <p style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                        <span>Tạm tính:</span>
                        <span>4.500.000đ</span>
                    </p>
                    <p style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                        <span>Giảm giá:</span>
                        <span>0đ</span>
                    </p>
                    <hr style="margin: 10px 0;">
                    <p style="display: flex; justify-content: space-between; font-weight: 700; font-size: 16px;">
                        <span>Tổng thanh toán:</span>
                        <span>4.500.000đ</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include(__DIR__ . '/../layouts/footer.php'); ?>
