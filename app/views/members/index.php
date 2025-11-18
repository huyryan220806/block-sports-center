<?php
$pageTitle   = 'Quản lý hội viên';
$currentPage = 'members';

// dữ liệu $members đã được MembersController truyền xuống
// nếu Controller truyền dạng ['members' => $members]:
if (isset($data['members'])){
    $members = $data['members'];
}

$members    = $data['members']    ?? [];
$sort       = $data['sort']       ?? 'id_desc';
$page       = $data['page']       ?? 1;
$totalPages = $data['totalPages'] ?? 1;
$total      = $data['total']      ?? 0;
// Lấy danh sách hội viên từ bảng hoivien
$sql    = "SELECT MAHV, HOVATEN, GIOITINH, NGAYSINH, SDT, EMAIL, DIACHI, TRANGTHAI
           FROM hoivien
           ORDER BY MAHV DESC";
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
                <?php include(__DIR__ . '/../layouts/alerts.php'); ?>
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
                                    <th>
                                        MÃ HV
                                        <!-- sort theo mã: bé -> lớn / lớn -> bé -->
                                        <a href="?c=members&a=index&sort=id_asc&page=1"
                                        style="font-size:12px; margin-left:4px; text-decoration:none;<?= $sort === 'id_asc' ? 'font-weight:bold;' : '' ?>">
                                            ↑
                                        </a>
                                        <a href="?c=members&a=index&sort=id_desc&page=1"
                                        style="font-size:12px; margin-left:2px; text-decoration:none;<?= $sort === 'id_desc' ? 'font-weight:bold;' : '' ?>">
                                            ↓
                                        </a>
                                    </th>

                                    <th>
                                        HỌ TÊN
                                        <!-- sort A-Z / Z-A -->
                                        <a href="?c=members&a=index&sort=name_asc&page=1"
                                        style="font-size:12px; margin-left:4px; text-decoration:none;<?= $sort === 'name_asc' ? 'font-weight:bold;' : '' ?>">
                                            A-Z
                                        </a>
                                        <a href="?c=members&a=index&sort=name_desc&page=1"
                                        style="font-size:12px; margin-left:2px; text-decoration:none;<?= $sort === 'name_desc' ? 'font-weight:bold;' : '' ?>">
                                            Z-A
                                        </a>
                                    </th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th>Giới tính</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="memberTableBody">
                                <?php if (!empty($members)): ?>
                                    <?php foreach ($members as $m): ?>
                                        <tr>
                                            <td>#MB<?= str_pad($m->MAHV, 3, '0', STR_PAD_LEFT); ?></td>
                                            <td><?= htmlspecialchars($m->HOVATEN); ?></td>
                                            <td><?= htmlspecialchars($m->SDT); ?></td>
                                            <td><?= htmlspecialchars($m->EMAIL); ?></td>
                                            <td><?= htmlspecialchars($m->GIOITINH); ?></td>
                                            <td>
                                                <?php
                                                // LẤY ĐÚNG TÊN CỘT TỪ SQL: TRANGTHAI (chữ HOA)
                                                $status = strtoupper(trim($m->TRANGTHAI ?? ''));

                                                // mặc định coi như INACTIVE
                                                $badgeClass = 'inactive';
                                                $label      = 'NGỪNG';

                                                if ($status === 'ACTIVE') {
                                                    $badgeClass = 'active';
                                                    $label      = 'HOẠT ĐỘNG';
                                                } elseif ($status === 'SUSPENDED') {
                                                    $badgeClass = 'suspended';
                                                    $label      = 'TẠM KHÓA';
                                                } elseif ($status === 'INACTIVE') {
                                                    $badgeClass = 'inactive';
                                                    $label      = 'NGỪNG';
                                                }
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= $label ?></span>
                                            </td>
                                            <td>
                                                <div class="action-btns">
                                                    <button class="action-btn edit"
                                                            onclick="location.href='?c=members&a=edit&id=<?= $m->MAHV ?>'">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="action-btn delete"
                                                            onclick="confirmDelete(<?= $m->MAHV ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align:center;">Chưa có hội viên nào</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if ($totalPages > 1): ?>
                            <div class="pagination" style="margin-top:16px; display:flex; justify-content:center; gap:4px;">
                                <?php if ($page > 1): ?>
                                    <a class="page-link" href="?c=members&a=index&page=<?= $page-1 ?>&sort=<?= urlencode($sort) ?>">&laquo;</a>
                                <?php endif; ?>

                                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                    <?php if ($p == $page): ?>
                                        <span class="page-link" style="font-weight:bold; background:#00b894; color:#fff; padding:4px 8px; border-radius:4px;">
                                            <?= $p ?>
                                        </span>
                                    <?php else: ?>
                                        <a class="page-link"
                                        href="?c=members&a=index&page=<?= $p ?>&sort=<?= urlencode($sort) ?>"
                                        style="padding:4px 8px; border-radius:4px; text-decoration:none; border:1px solid #ddd;">
                                            <?= $p ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                    <a class="page-link" href="?c=members&a=index&page=<?= $page+1 ?>&sort=<?= urlencode($sort) ?>">&raquo;</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
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