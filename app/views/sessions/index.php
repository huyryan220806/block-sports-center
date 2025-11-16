<?php 
$pageTitle = 'Quản lý buổi lớp';
$currentPage = 'sessions';
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
                    <h2>Quản lý buổi lớp</h2>
                    <p>Lịch trình các buổi lớp trong tuần</p>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách buổi lớp</h3>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm buổi lớp
                        </button>
                    </div>
                    
                    <!-- SEARCH TRONG CARD -->
                    <div class="search-bar">
                        <input type="date" class="form-control" style="max-width: 200px;" value="<?php echo date('Y-m-d'); ?>">
                        <select class="form-control" style="max-width: 200px;">
                            <option value="">Tất cả trạng thái</option>
                            <option value="SCHEDULED">Scheduled</option>
                            <option value="IN_PROGRESS">Đang diễn ra</option>
                            <option value="COMPLETED">Hoàn thành</option>
                            <option value="CANCELLED">Đã hủy</option>
                        </select>
                        <button class="btn btn-ghost">
                            <i class="fas fa-search"></i> Lọc
                        </button>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Thời gian</th>
                                    <th>Tên lớp</th>
                                    <th>Phòng</th>
                                    <th>Huấn luyện viên</th>
                                    <th>Số chỗ</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>15/11/2024</strong></td>
                                    <td>08:00 - 09:30</td>
                                    <td>Yoga Căn Bản</td>
                                    <td>Phòng A1</td>
                                    <td>Nguyễn Thị Lan</td>
                                    <td><strong>15/20</strong></td>
                                    <td><span class="badge scheduled">Scheduled</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" title="Xem chi tiết"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit" title="Sửa"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(1)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>15/11/2024</strong></td>
                                    <td>09:00 - 10:00</td>
                                    <td>Gym Strength</td>
                                    <td>Gym Floor</td>
                                    <td>Trần Văn Mạnh</td>
                                    <td><strong>25/25</strong></td>
                                    <td><span class="badge full">Full</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(2)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>15/11/2024</strong></td>
                                    <td>10:00 - 11:00</td>
                                    <td>Zumba Dance</td>
                                    <td>Phòng B2</td>
                                    <td>Lê Thu Hà</td>
                                    <td><strong>18/30</strong></td>
                                    <td><span class="badge scheduled">Scheduled</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(3)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>15/11/2024</strong></td>
                                    <td>14:00 - 15:30</td>
                                    <td>Boxing Cơ Bản</td>
                                    <td>Phòng C1</td>
                                    <td>Phạm Quốc Tuấn</td>
                                    <td><strong>10/15</strong></td>
                                    <td><span class="badge scheduled">Scheduled</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(4)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>15/11/2024</strong></td>
                                    <td>16:00 - 17:00</td>
                                    <td>Pilates</td>
                                    <td>Phòng A2</td>
                                    <td>Đỗ Minh Châu</td>
                                    <td><strong>12/20</strong></td>
                                    <td><span class="badge scheduled">Scheduled</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(5)"><i class="fas fa-trash"></i></button>
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
    <script>
    function confirmDelete(id) {
        if (confirm('Bạn có chắc chắn muốn xóa buổi lớp này?')) {
            alert('Đã xóa buổi lớp #' + id);
        }
    }
    </script>
</body>
</html>