<?php 
$pageTitle   = 'Quản lý đặt phòng';
$currentPage = 'bookings';

// DỮ LIỆU TỪ CONTROLLER
$bookings        = $data['bookings']        ?? ($bookings ?? []);   // danh sách đặt phòng
$rooms           = $data['rooms']           ?? ($rooms ?? []);      // danh sách phòng
$sort            = $data['sort']            ?? 'id_desc';
$page            = $data['page']            ?? 1;
$totalPages      = $data['totalPages']      ?? 1;
$total           = $data['total']           ?? 0;
$filterDateValue = $data['filterDateValue'] ?? ($filterDateValue ?? '');
$filterRoomValue = $data['filterRoomValue'] ?? ($filterRoomValue ?? '');

$selectedDate = $filterDateValue;
$selectedRoom = $filterRoomValue;

if ($selectedDate === '' || $selectedDate === null) {
    $selectedDate = date('Y-m-d');
}
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
                    <h2>Quản lý đặt phòng</h2>
                    <p>Theo dõi và quản lý các lịch đặt phòng/sân trong trung tâm.</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách đặt phòng</h3>
                        <a href="?c=bookings&a=create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Đặt phòng mới
                        </a>
                    </div>
                    
                    <!-- FORM LỌC THEO NGÀY + PHÒNG -->
                    <form method="get" class="search-bar">
                        <input type="hidden" name="c" value="bookings">
                        <input type="hidden" name="a" value="index">

                        <input type="date"
                               name="date"
                               class="form-control"
                               style="max-width: 180px;"
                               value="<?php echo htmlspecialchars($selectedDate); ?>">

                        <select name="room_id" class="form-control" style="max-width: 200px;">
                            <option value="">Tất cả phòng</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?= $room->MAPHONG ?>" <?= ($selectedRoom == $room->MAPHONG ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($room->TENPHONG) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button class="btn btn-ghost">
                            <i class="fas fa-search"></i> Lọc
                        </button>
                    </form>
                    
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
                                <?php if (!empty($bookings)): ?>
                                    <?php foreach ($bookings as $bk): ?>
                                        <?php
                                            $date  = date('d/m/Y', strtotime($bk->BATDAU));
                                            $time  = date('H:i', strtotime($bk->BATDAU)) . ' - ' . date('H:i', strtotime($bk->KETTHUC));
                                            $memberName = $bk->HOVATEN ? $bk->HOVATEN : 'Khách lẻ';

                                            // Mục đích
                                            $purpose = 'Tập tự do';
                                            if ($bk->MUCTIEU === 'CLB') {
                                                $purpose = 'Câu lạc bộ';
                                            } elseif ($bk->MUCTIEU === 'GIU_CHO_SU_KIEN') {
                                                $purpose = 'Giữ chỗ sự kiện';
                                            }

                                            // TRANGTHAI: PENDING / CONFIRMED / CANCELLED / DONE
                                            $status = $bk->TRANGTHAI;
                                            $statusClass = 'badge full';
                                            $statusLabel = 'Pending';

                                            if ($status === 'CONFIRMED') {
                                                $statusClass = 'badge scheduled';
                                                $statusLabel = 'Confirmed';
                                            } elseif ($status === 'CANCELLED') {
                                                $statusClass = 'badge inactive';
                                                $statusLabel = 'Cancelled';
                                            } elseif ($status === 'DONE') {
                                                $statusClass = 'badge active';
                                                $statusLabel = 'Done';
                                            }
                                        ?>
                                        <tr>
                                            <td><strong>#BK<?= str_pad($bk->MADP, 3, '0', STR_PAD_LEFT); ?></strong></td>
                                            <td><?= $date ?></td>
                                            <td><?= $time ?></td>
                                            <td><?= htmlspecialchars($bk->TENPHONG) ?></td>
                                            <td><?= htmlspecialchars($memberName) ?></td>
                                            <td><?= htmlspecialchars($purpose) ?></td>
                                            <td><span class="<?= $statusClass ?>"><?= $statusLabel ?></span></td>
                                            <td>
                                                <div class="action-btns">
                                                    <!-- Edit -->
                                                    <button type="button"
                                                            class="action-btn edit"
                                                            onclick="location.href='?c=bookings&a=edit&id=<?= $bk->MADP ?>'">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <?php if ($status === 'PENDING'): ?>
                                                        <!-- Confirm -->
                                                        <button type="button"
                                                                class="action-btn edit"
                                                                title="Xác nhận"
                                                                onclick="if(confirm('Xác nhận đặt phòng này?')) location.href='?c=bookings&a=confirm&id=<?= $bk->MADP ?>';">
                                                            <i class="fas fa-check"></i>
                                                        </button>

                                                        <!-- Cancel -->
                                                        <button type="button"
                                                                class="action-btn delete"
                                                                title="Hủy đặt phòng"
                                                                onclick="if(confirm('Hủy đặt phòng này?')) location.href='?c=bookings&a=cancel&id=<?= $bk->MADP ?>';">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>

                                                    <!-- Delete -->
                                                    <button type="button"
                                                            class="action-btn delete"
                                                            title="Xóa"
                                                            onclick="if(confirm('Xóa đặt phòng này?')) location.href='?c=bookings&a=delete&id=<?= $bk->MADP ?>';">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" style="text-align:center; padding: 16px 0;">
                                            Không có đặt phòng nào phù hợp.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                       <?php if ($totalPages > 1): ?>
                            <?php
                            // Build base query giữ lại filter + sort
                            $queryBase = '?c=bookings&a=index';
                            if (!empty($selectedDate)) {
                                $queryBase .= '&date=' . urlencode($selectedDate);
                            }
                            if (!empty($selectedRoom)) {
                                $queryBase .= '&room_id=' . urlencode($selectedRoom);
                            }
                            if (!empty($sort)) {
                                $queryBase .= '&sort=' . urlencode($sort);
                            }
                            ?>
                            <div class="pagination" style="margin-top:16px; display:flex; justify-content:center; gap:4px;">
                                <?php if ($page > 1): ?>
                                    <a class="page-link"
                                       href="<?= $queryBase . '&page=' . ($page - 1) ?>"
                                       style="padding:4px 8px; border-radius:4px; text-decoration:none; border:1px solid #ddd;">
                                        &laquo;
                                    </a>
                                <?php endif; ?>

                                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                    <?php if ($p == $page): ?>
                                        <!-- trang hiện tại: nền xanh giống hình -->
                                        <span class="page-link"
                                              style="font-weight:bold; background:#00b894; color:#fff; padding:4px 10px; border-radius:4px; display:inline-flex; align-items:center; justify-content:center;">
                                            <?= $p ?>
                                        </span>
                                    <?php else: ?>
                                        <!-- trang khác: viền xám trắng giống hình -->
                                        <a class="page-link"
                                           href="<?= $queryBase . '&page=' . $p ?>"
                                           style="padding:4px 10px; border-radius:4px; text-decoration:none; border:1px solid #ddd; color:#333; background:#fff; display:inline-flex; align-items:center; justify-content:center;">
                                            <?= $p ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                    <a class="page-link"
                                       href="<?= $queryBase . '&page=' . ($page + 1) ?>"
                                       style="padding:4px 8px; border-radius:4px; text-decoration:none; border:1px solid #ddd;">
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
</body>
</html>
// 