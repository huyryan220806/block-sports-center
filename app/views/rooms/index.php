<?php 
// app/views/rooms/index.php
$pageTitle   = 'Quản lý phòng/sân';
$currentPage = 'rooms';

$rooms      = $data['rooms']      ?? [];
$sort       = $data['sort']       ?? 'id_desc';
$page       = $data['page']       ?? 1;
$totalPages = $data['totalPages'] ?? 1;
$total      = $data['total']      ?? 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - BLOCK SPORTS CENTER</title>
    <link rel="stylesheet" href="/block-sports-center/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ✅ AREA BADGE - MÀU PASTEL GIỐNG ẢNH */
        .area-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s;
        }

        .area-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        /* Màu pastel - nền nhạt + chữ đậm */
        .area-badge.gym {
            background: #FFE5E5;
            color: #E63946;
        }

        .area-badge.pool {
            background: #D1F4F0;
            color: #00897B;
        }

        .area-badge.studio {
            background: #E8DAFF;
            color: #6C5CE7;
        }

        .area-badge.boxing {
            background: #FFD4D4;
            color: #C0392B;
        }

        .area-badge.futsal {
            background: #D4F1E8;
            color: #00967A;
        }

        .area-badge.volleyball {
            background: #FFF4D4;
            color: #E17055;
        }

        .area-badge.basketball {
            background: #FFD4ED;
            color: #E84393;
        }

        .area-badge.badminton {
            background: #D4FFF4;
            color: #00B894;
        }

        .area-badge.football {
            background: #D4E8FF;
            color: #0984E3;
        }

        .area-badge.pickleball {
            background: #FFD4D8;
            color: #D63031;
        }

        .area-badge.other {
            background: #E8E8E8;
            color: #636E72;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php include(__DIR__ . '/../layouts/sidebar.php'); ?>
        <main class="main-content">
            <?php include(__DIR__ . '/../layouts/header.php'); ?>
            <div class="content">
                <?php include(__DIR__ . '/../layouts/alerts.php'); ?>
                
                <div class="page-header">
                    <h2>Quản lý phòng/sân</h2>
                    <p>Danh sách tất cả phòng và sân trong trung tâm (Tổng: <?= $total ?>)</p>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách phòng/sân</h3>
                        <button class="btn btn-primary"
                                onclick="location.href='?c=rooms&a=create'">
                            <i class="fas fa-plus"></i> Thêm phòng/sân
                        </button>
                    </div>
                    
                    <!-- SEARCH BAR -->
                    <div class="search-bar">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Tìm kiếm theo tên phòng, khu vực...">
                        <button class="btn btn-ghost">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        MÃ PHÒNG
                                        <a href="?c=rooms&a=index&sort=id_asc&page=1"
                                           style="font-size:12px; margin-left:4px; text-decoration:none;<?= $sort === 'id_asc' ? 'font-weight:bold;' : '' ?>">
                                            ↑
                                        </a>
                                        <a href="?c=rooms&a=index&sort=id_desc&page=1"
                                           style="font-size:12px; margin-left:2px; text-decoration:none;<?= $sort === 'id_desc' ? 'font-weight:bold;' : '' ?>">
                                            ↓
                                        </a>
                                    </th>
                                    <th>
                                        TÊN PHÒNG
                                        <a href="?c=rooms&a=index&sort=name_asc&page=1"
                                           style="font-size:12px; margin-left:4px; text-decoration:none;<?= $sort === 'name_asc' ? 'font-weight:bold;' : '' ?>">
                                            A-Z
                                        </a>
                                        <a href="?c=rooms&a=index&sort=name_desc&page=1"
                                           style="font-size:12px; margin-left:2px; text-decoration:none;<?= $sort === 'name_desc' ? 'font-weight:bold;' : '' ?>">
                                            Z-A
                                        </a>
                                    </th>
                                    <th>
                                        SỨC CHỨA
                                        <a href="?c=rooms&a=index&sort=capacity_asc&page=1"
                                           style="font-size:12px; margin-left:4px; text-decoration:none;<?= $sort === 'capacity_asc' ? 'font-weight:bold;' : '' ?>">
                                            ↑
                                        </a>
                                        <a href="?c=rooms&a=index&sort=capacity_desc&page=1"
                                           style="font-size:12px; margin-left:2px; text-decoration:none;<?= $sort === 'capacity_desc' ? 'font-weight:bold;' : '' ?>">
                                            ↓
                                        </a>
                                    </th>
                                    <th>KHU VỰC</th>
                                    <th>TRẠNG THÁI</th>
                                    <th>GHI CHÚ</th>
                                    <th>HÀNH ĐỘNG</th>
                                </tr>
                            </thead>
                            <tbody id="roomTableBody">
                                <?php if (!empty($rooms)): ?>
                                    <?php foreach ($rooms as $r): ?>
                                        <tr>
                                            <td><strong>#RM<?= str_pad($r->MAPHONG, 3, '0', STR_PAD_LEFT) ?></strong></td>
                                            <td><?= htmlspecialchars($r->TENPHONG) ?></td>
                                            <td><?= (int)$r->SUCCHUA ?> người</td>
                                            <td>
                                                <?php
                                                // Lấy thông tin badge theo loại khu
                                                $badgeInfo = AreaHelper::getBadgeInfo($r->LOAIKHU);
                                                ?>
                                                <span class="area-badge <?= $badgeInfo['class'] ?>">
                                                    <?= htmlspecialchars($r->TENKHU) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $statusText  = $r->HOATDONG ? 'HOẠT ĐỘNG' : 'NGỪNG';
                                                $statusClass = $r->HOATDONG ? 'active' : 'inactive';
                                                ?>
                                                <span class="badge <?= $statusClass ?>">
                                                    <?= $statusText ?>
                                                </span>
                                            </td>
                                            <td><?= nl2br(htmlspecialchars($r->GHICHU ?? '')) ?></td>
                                            <td>
                                                <div class="action-btns">
                                                    <button class="action-btn edit"
                                                            onclick="location.href='?c=rooms&a=edit&id=<?= $r->MAPHONG ?>'">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="action-btn delete"
                                                            onclick="confirmDelete(<?= $r->MAPHONG ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align:center; padding: 40px;">
                                            <i class="fas fa-inbox" style="font-size: 48px; color: #ccc;"></i>
                                            <p style="margin-top: 10px; color: #999;">Chưa có phòng/sân nào trong hệ thống.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- PHÂN TRANG -->
                        <?php if ($totalPages > 1): ?>
                            <div class="pagination" style="margin-top:16px; display:flex; justify-content:center; gap:4px;">
                                <?php if ($page > 1): ?>
                                    <a class="page-link" 
                                       href="?c=rooms&a=index&page=<?= $page-1 ?>&sort=<?= urlencode($sort) ?>">
                                        &laquo;
                                    </a>
                                <?php endif; ?>

                                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                    <?php if ($p == $page): ?>
                                        <span class="page-link" 
                                              style="font-weight:bold; background:#00b894; color:#fff; padding:4px 8px; border-radius:4px;">
                                            <?= $p ?>
                                        </span>
                                    <?php else: ?>
                                        <a class="page-link"
                                           href="?c=rooms&a=index&page=<?= $p ?>&sort=<?= urlencode($sort) ?>"
                                           style="padding:4px 8px; border-radius:4px; text-decoration:none; border:1px solid #ddd;">
                                            <?= $p ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                    <a class="page-link" 
                                       href="?c=rooms&a=index&page=<?= $page+1 ?>&sort=<?= urlencode($sort) ?>">
                                        &raquo;
                                    </a>
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
        if (confirm('Bạn có chắc chắn muốn xóa phòng/sân #' + id + '?')) {
            window.location.href = '?c=rooms&a=delete&id=' + id;
        }
    }

    // TÌM KIẾM
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.querySelector('.search-bar .btn-ghost');
        const tableBody = document.getElementById('roomTableBody');
        
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
        
        if (searchBtn) {
            searchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                performSearch();
            });
        }
        
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