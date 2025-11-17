<?php 
$pageTitle = 'Quản lý hội viên';
$currentPage = 'members';
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
                    <h2>Quản lý hội viên</h2>
                    <p>Danh sách tất cả hội viên trong hệ thống</p>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách hội viên</h3>
                        <a href="?c=members&a=create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm hội viên
                        </a>
                    </div>
                    
                    <!-- SEARCH TRONG CARD -->
                    <div class="search-bar">
                        <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên, số điện thoại, email...">
                        <button class="btn btn-ghost">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Mã HV</th>
                                    <th>Họ tên</th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th>Giới tính</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="memberTableBody">
                                <tr>
                                    <td>#MB001</td>
                                    <td>Nguyễn Văn An</td>
                                    <td>0901234567</td>
                                    <td>nguyenvanan@email.com</td>
                                    <td>Nam</td>
                                    <td><span class="badge active">Active</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" onclick="location.href='?c=members&a=edit&id=1'">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-btn delete" onclick="confirmDelete(1)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#MB002</td>
                                    <td>Trần Thị Bình</td>
                                    <td>0912345678</td>
                                    <td>tranthibinh@email.com</td>
                                    <td>Nữ</td>
                                    <td><span class="badge active">Active</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" onclick="location.href='?c=members&a=edit&id=2'">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-btn delete" onclick="confirmDelete(2)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#MB003</td>
                                    <td>Lê Hoàng Cường</td>
                                    <td>0923456789</td>
                                    <td>lehoangcuong@email.com</td>
                                    <td>Nam</td>
                                    <td><span class="badge suspended">Suspended</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" onclick="location.href='?c=members&a=edit&id=3'">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-btn delete" onclick="confirmDelete(3)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#MB004</td>
                                    <td>Phạm Thị Dung</td>
                                    <td>0934567890</td>
                                    <td>phamthidung@email.com</td>
                                    <td>Nữ</td>
                                    <td><span class="badge active">Active</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" onclick="location.href='?c=members&a=edit&id=4'">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-btn delete" onclick="confirmDelete(4)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#MB005</td>
                                    <td>Vũ Minh Em</td>
                                    <td>0945678901</td>
                                    <td>vuminhem@email.com</td>
                                    <td>Nam</td>
                                    <td><span class="badge inactive">Inactive</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" onclick="location.href='?c=members&a=edit&id=5'">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-btn delete" onclick="confirmDelete(5)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#MB006</td>
                                    <td>Đỗ Thu Hằng</td>
                                    <td>0956789012</td>
                                    <td>dothuhang@email.com</td>
                                    <td>Nữ</td>
                                    <td><span class="badge active">Active</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn edit" onclick="location.href='?c=members&a=edit&id=6'">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-btn delete" onclick="confirmDelete(6)">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
    <script src="/block-sports-center/public/assets/js/main.js"></script>
    <script>
    function confirmDelete(id) {
        if (confirm('Bạn có chắc chắn muốn xóa hội viên #' + id + '?')) {
            window.location.href = '?c=members&a=delete&id=' + id;
        }
    }
    
// ========== TÌM KIẾM KHI CLICK NÚT ==========
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.querySelector('.search-bar .btn-ghost');
    const tableBody = document.getElementById('memberTableBody');
    
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
</body>
</html>