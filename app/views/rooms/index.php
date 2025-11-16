<?php 
$pageTitle = 'Quản lý phòng/sân';
$currentPage = 'rooms';
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
                    <h2>Quản lý phòng/sân</h2>
                    <p>Danh sách tất cả phòng và sân trong trung tâm</p>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách phòng/sân</h3>
                        <button class="btn btn-primary" onclick="location.href='/block-sports-center/public/index.php?page=rooms-create'">
                            <i class="fas fa-plus"></i> Thêm phòng/sân
                        </button>
                    </div>
                    
                    <!-- SEARCH TRONG CARD -->
                    <div class="search-bar">
                        <input type="text" placeholder="Tìm kiếm theo tên phòng, khu vực...">
                        <button class="btn btn-ghost">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Mã phòng</th>
                                    <th>Tên phòng/sân</th>
                                    <th>Khu vực</th>
                                    <th>Sức chứa</th>
                                    <th>Loại</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#RM001</td>
                                    <td>Phòng Yoga A1</td>
                                    <td>Tầng 1 - Khu A</td>
                                    <td>20 người</td>
                                    <td>Yoga Studio</td>
                                    <td><span class="badge active">Available</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(1)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#RM002</td>
                                    <td>Phòng Dance B2</td>
                                    <td>Tầng 2 - Khu B</td>
                                    <td>30 người</td>
                                    <td>Dance Studio</td>
                                    <td><span class="badge active">Available</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(2)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#RM003</td>
                                    <td>Gym Floor</td>
                                    <td>Tầng 1 - Khu C</td>
                                    <td>50 người</td>
                                    <td>Gym</td>
                                    <td><span class="badge scheduled">In Use</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(3)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#RM004</td>
                                    <td>Phòng Boxing C1</td>
                                    <td>Tầng 2 - Khu C</td>
                                    <td>15 người</td>
                                    <td>Boxing</td>
                                    <td><span class="badge active">Available</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(4)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#RM005</td>
                                    <td>Sân Tennis Ngoài trời 1</td>
                                    <td>Ngoài trời</td>
                                    <td>4 người</td>
                                    <td>Tennis Court</td>
                                    <td><span class="badge active">Available</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(5)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#RM006</td>
                                    <td>Phòng Spinning A2</td>
                                    <td>Tầng 1 - Khu A</td>
                                    <td>25 người</td>
                                    <td>Spinning</td>
                                    <td><span class="badge inactive">Maintenance</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(6)"><i class="fas fa-trash"></i></button>
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
        if (confirm('Bạn có chắc chắn muốn xóa phòng/sân này?')) {
            alert('Đã xóa phòng #' + id);
        }
    }
    </script>
</body>
</html>