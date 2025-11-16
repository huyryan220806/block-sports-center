<?php 
$pageTitle = 'Quản lý huấn luyện viên';
$currentPage = 'trainers';
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
                    <h2>Quản lý huấn luyện viên</h2>
                    <p>Danh sách tất cả PT và huấn luyện viên</p>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách huấn luyện viên</h3>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm HLV
                        </button>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Mã HLV</th>
                                    <th>Họ tên</th>
                                    <th>Chuyên môn</th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>#PT001</strong></td>
                                    <td>Ngọc Chi</td>
                                    <td><span class="badge scheduled">Yoga</span></td>
                                    <td>0911111111</td>
                                    <td>lan.pt@blocksp orts.vn</td>
                                    <td><span class="badge active">Active</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#PT002</strong></td>
                                    <td>Trần Văn Mạnh</td>
                                    <td><span class="badge active">Gym</span></td>
                                    <td>0922222222</td>
                                    <td>manh.pt@blocksports.vn</td>
                                    <td><span class="badge active">Active</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#PT003</strong></td>
                                    <td>Lê Thu Hà</td>
                                    <td><span class="badge full">Dance</span></td>
                                    <td>0933333333</td>
                                    <td>ha.pt@blocksports.vn</td>
                                    <td><span class="badge active">Active</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#PT004</strong></td>
                                    <td>Phạm Quốc Tuấn</td>
                                    <td><span class="badge suspended">Boxing</span></td>
                                    <td>0944444444</td>
                                    <td>tuan.pt@blocksports.vn</td>
                                    <td><span class="badge active">Active</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
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