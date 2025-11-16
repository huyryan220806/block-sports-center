<?php 
$pageTitle = 'Quản lý lớp học';
$currentPage = 'classes';
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
                    <h2>Quản lý lớp học</h2>
                    <p>Danh sách tất cả các lớp học trong trung tâm</p>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách lớp học</h3>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm lớp học mới
                        </button>
                    </div>
                    
                    <!-- SEARCH TRONG CARD -->
                    <div class="search-bar">
                        <input type="text" placeholder="Tìm kiếm theo tên lớp, loại...">
                        <select class="form-control" style="max-width: 200px;">
                            <option value="">Tất cả loại</option>
                            <option value="Yoga">Yoga</option>
                            <option value="Gym">Gym</option>
                            <option value="Dance">Dance</option>
                            <option value="Boxing">Boxing</option>
                            <option value="Swimming">Bơi lội</option>
                        </select>
                        <button class="btn btn-ghost">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Mã lớp</th>
                                    <th>Tên lớp</th>
                                    <th>Loại</th>
                                    <th>Thời lượng</th>
                                    <th>Giá/buổi</th>
                                    <th>Mô tả</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>#CL001</strong></td>
                                    <td>Yoga Căn Bản</td>
                                    <td><span class="badge scheduled">Yoga</span></td>
                                    <td>90 phút</td>
                                    <td>150.000đ</td>
                                    <td>Yoga cho người mới bắt đầu</td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(1)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#CL002</strong></td>
                                    <td>Gym Strength Training</td>
                                    <td><span class="badge active">Gym</span></td>
                                    <td>60 phút</td>
                                    <td>200.000đ</td>
                                    <td>Tập tăng cơ bắp</td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(2)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#CL003</strong></td>
                                    <td>Zumba Dance</td>
                                    <td><span class="badge full">Dance</span></td>
                                    <td>60 phút</td>
                                    <td>180.000đ</td>
                                    <td>Nhảy Zumba giảm cân</td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(3)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#CL004</strong></td>
                                    <td>Boxing Cơ Bản</td>
                                    <td><span class="badge suspended">Boxing</span></td>
                                    <td>90 phút</td>
                                    <td>250.000đ</td>
                                    <td>Học boxing từ căn bản</td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(4)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#CL005</strong></td>
                                    <td>Pilates</td>
                                    <td><span class="badge scheduled">Pilates</span></td>
                                    <td>60 phút</td>
                                    <td>170.000đ</td>
                                    <td>Pilates giảm stress</td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete" onclick="confirmDelete(5)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#CL006</strong></td>
                                    <td>Spinning Cardio</td>
                                    <td><span class="badge active">Cycling</span></td>
                                    <td>45 phút</td>
                                    <td>160.000đ</td>
                                    <td>Đạp xe trong nhà</td>
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
        if (confirm('Bạn có chắc chắn muốn xóa lớp học này?')) {
            alert('Đã xóa lớp #' + id);
        }
    }
    </script>
</body>
</html>