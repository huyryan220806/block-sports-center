<?php 
/**
 * app/views/invoices/create.php
 * Form tạo hóa đơn mới
 * Updated: 2025-11-18 12:24:47 UTC
 * Fixed: Loại bỏ lỗi undefined variable
 * Author: @huyryan220806
 */

$pageTitle   = 'Tạo hóa đơn mới';
$currentPage = 'invoices';

$members    = $data['members']    ?? [];
$promotions = $data['promotions'] ?? [];
$packages   = $data['packages']   ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - BLOCK SPORTS CENTER</title>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .invoice-item {
            background: #f8f9fa;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 12px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }
        
        .invoice-item:hover {
            border-color: #00B894;
            box-shadow: 0 2px 8px rgba(0, 184, 148, 0.1);
        }
        
        .package-suggestion {
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .package-suggestion:hover {
            transform: scale(1.02);
        }
        
        .package-suggestion:active {
            transform: scale(0.98);
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
                    <h2><i class="fas fa-plus-circle"></i> Tạo hóa đơn mới</h2>
                    <p>Nhập thông tin hóa đơn cho hội viên</p>
                </div>
                
                <form method="POST" action="?c=invoices&a=store" id="invoiceForm">
                    <!-- THÔNG TIN CHUNG -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle"></i> Thông tin chung</h3>
                        </div>
                        
                        <div class="form-layout">
                            <!-- Cột trái -->
                            <div>
                                <div class="form-group">
                                    <label class="form-label">
                                        Hội viên <span style="color: red;">*</span>
                                    </label>
                                    <select name="mahv" class="form-control" required>
                                        <option value="">-- Chọn hội viên --</option>
                                        <?php if (!empty($members)): ?>
                                            <?php foreach ($members as $m): ?>
                                                <option value="<?= $m->MAHV ?>">
                                                    #<?= $m->MAHV ?> - <?= e($m->HOVATEN) ?> 
                                                    <?php if (!empty($m->SDT)): ?>
                                                        - <?= e($m->SDT) ?>
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="" disabled>Chưa có hội viên nào</option>
                                        <?php endif; ?>
                                    </select>
                                    <small style="color: #999; display: block; margin-top: 4px;">
                                        <i class="fas fa-info-circle"></i> Chọn hội viên để tạo hóa đơn
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Ngày lập <span style="color: red;">*</span></label>
                                    <input type="datetime-local" 
                                           name="ngaylap" 
                                           class="form-control" 
                                           value="<?= date('Y-m-d\TH:i') ?>" 
                                           required>
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div>
                                <div class="form-group">
                                    <label class="form-label">Mã khuyến mãi (tùy chọn)</label>
                                    <select name="makm" class="form-control" id="promoSelect">
                                        <option value="">-- Không áp dụng --</option>
                                        <?php if (!empty($promotions)): ?>
                                            <?php foreach ($promotions as $km): ?>
                                                <option value="<?= $km->MAKM ?>" 
                                                        data-type="<?= e($km->LOAI ?? 'FIXED') ?>"
                                                        data-value="<?= $km->GIATRI ?? 0 ?>">
                                                    <?= e($km->CODE ?? '') ?> - 
                                                    <?php if (($km->LOAI ?? '') === 'PERCENT'): ?>
                                                        Giảm <?= $km->GIATRI ?? 0 ?>%
                                                    <?php else: ?>
                                                        Giảm <?= formatMoney($km->GIATRI ?? 0) ?>
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Trạng thái <span style="color: red;">*</span></label>
                                    <select name="trangthai" class="form-control" required>
                                        <option value="DRAFT">Nháp</option>
                                        <option value="ISSUED" selected>Đã xuất</option>
                                        <option value="PAID">Đã thanh toán</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CHI TIẾT HÓA ĐƠN -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-list"></i> Chi tiết hóa đơn</h3>
                            <button type="button" class="btn btn-ghost" onclick="addInvoiceItem()">
                                <i class="fas fa-plus"></i> Thêm dòng
                            </button>
                        </div>

                        <div id="invoiceItems" style="padding: 20px;">
                            <!-- Dòng mẫu -->
                            <div class="invoice-item" data-index="0">
                                <div style="display: flex; gap: 12px; align-items: flex-end;">
                                    <div style="flex: 2;">
                                        <label class="form-label">Loại hàng</label>
                                        <select name="items[0][loaihang]" class="form-control">
                                            <option value="MEMBERSHIP">Gói tập</option>
                                            <option value="CLASS">Lớp học</option>
                                            <option value="PT">PT Session</option>
                                            <option value="BOOKING">Đặt phòng</option>
                                            <option value="LOCKER">Tủ đồ</option>
                                            <option value="OTHER">Khác</option>
                                        </select>
                                    </div>

                                    <div style="flex: 4;">
                                        <label class="form-label">Mô tả <span style="color: red;">*</span></label>
                                        <input type="text" 
                                               name="items[0][mota]" 
                                               class="form-control" 
                                               placeholder="VD: Gói tập 3 tháng STANDARD"
                                               required>
                                    </div>

                                    <div style="flex: 1;">
                                        <label class="form-label">SL</label>
                                        <input type="number" 
                                               name="items[0][soluong]" 
                                               class="form-control" 
                                               value="1" 
                                               min="1"
                                               onchange="calculateTotal()">
                                    </div>

                                    <div style="flex: 2;">
                                        <label class="form-label">Đơn giá (VND)</label>
                                        <input type="number" 
                                               name="items[0][dongia]" 
                                               class="form-control item-price" 
                                               value="0" 
                                               min="0"
                                               onchange="calculateTotal()">
                                    </div>

                                    <button type="button" 
                                            class="btn" 
                                            style="background: #e74c3c; color: white; height: 42px;" 
                                            onclick="removeInvoiceItem(this)"
                                            title="Xóa dòng">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tổng tiền -->
                        <div style="margin: 20px; padding: 20px; background: #f8f9fa; border-radius: 8px; border-top: 2px solid #ddd;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 16px;">
                                <span>Tạm tính:</span>
                                <span id="subtotalAmount" style="font-weight: 700; color: #2d3436;">0đ</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #e74c3c;">
                                <span>Giảm giá:</span>
                                <span id="discountAmount" style="font-weight: 600;">-0đ</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding-top: 12px; border-top: 2px solid #ddd; font-size: 20px;">
                                <span style="font-weight: 700;">Tổng cộng:</span>
                                <span id="totalAmount" style="font-weight: 700; color: #00B894;">0đ</span>
                            </div>
                        </div>
                    </div>

                    <!-- GỢI Ý GÓI TẬP -->
                    <?php if (!empty($packages)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-lightbulb" style="color: #FFD33D;"></i> 
                                Gợi ý gói tập
                            </h3>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; padding: 20px;">
                            <?php foreach ($packages as $pkg): ?>
                                <?php
                                $gia = $pkg->GIA ?? 0;
                                $tenlg = $pkg->TENLG ?? 'Gói tập';
                                $thoihan = $pkg->THOIHAN ?? 0;
                                $capdo = $pkg->CAPDO ?? 'BASIC';
                                ?>
                                <div class="package-suggestion" 
                                     style="border: 2px solid #ddd; border-radius: 12px; padding: 16px; transition: all 0.3s;"
                                     onclick="selectPackage('<?= e($tenlg) ?>', <?= $gia ?>)">
                                    <div style="font-size: 16px; font-weight: 700; color: #00B894; margin-bottom: 8px;">
                                        <?= e($tenlg) ?>
                                    </div>
                                    <div style="font-size: 14px; color: #999; margin-bottom: 8px;">
                                        <?= $thoihan ?> ngày - <?= strtoupper($capdo) ?>
                                    </div>
                                    <div style="font-size: 20px; font-weight: 700; color: #E63946;">
                                        <?= formatMoney($gia) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- ACTIONS -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-ghost" onclick="history.back()">
                            <i class="fas fa-times"></i> Hủy
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu hóa đơn
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <?php include(__DIR__ . '/../layouts/footer.php'); ?>
    <script src="<?= asset('assets/js/main.js') ?>"></script>
    <script>
    let itemIndex = 1;

    // Thêm dòng hóa đơn mới
    function addInvoiceItem() {
        const container = document.getElementById('invoiceItems');
        const newItem = document.createElement('div');
        newItem.className = 'invoice-item';
        newItem.setAttribute('data-index', itemIndex);
        newItem.innerHTML = `
            <div style="display: flex; gap: 12px; align-items: flex-end;">
                <div style="flex: 2;">
                    <label class="form-label">Loại hàng</label>
                    <select name="items[${itemIndex}][loaihang]" class="form-control">
                        <option value="MEMBERSHIP">Gói tập</option>
                        <option value="CLASS">Lớp học</option>
                        <option value="PT">PT Session</option>
                        <option value="BOOKING">Đặt phòng</option>
                        <option value="LOCKER">Tủ đồ</option>
                        <option value="OTHER">Khác</option>
                    </select>
                </div>
                <div style="flex: 4;">
                    <label class="form-label">Mô tả <span style="color: red;">*</span></label>
                    <input type="text" name="items[${itemIndex}][mota]" class="form-control" placeholder="Mô tả sản phẩm/dịch vụ" required>
                </div>
                <div style="flex: 1;">
                    <label class="form-label">SL</label>
                    <input type="number" name="items[${itemIndex}][soluong]" class="form-control" value="1" min="1" onchange="calculateTotal()">
                </div>
                <div style="flex: 2;">
                    <label class="form-label">Đơn giá (VND)</label>
                    <input type="number" name="items[${itemIndex}][dongia]" class="form-control item-price" value="0" min="0" onchange="calculateTotal()">
                </div>
                <button type="button" class="btn" style="background: #e74c3c; color: white; height: 42px;" onclick="removeInvoiceItem(this)" title="Xóa dòng">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newItem);
        itemIndex++;
        calculateTotal();
    }

    // Xóa dòng
    function removeInvoiceItem(btn) {
        const items = document.querySelectorAll('.invoice-item');
        if (items.length > 1) {
            btn.closest('.invoice-item').remove();
            calculateTotal();
        } else {
            alert('Phải có ít nhất 1 dòng hóa đơn!');
        }
    }

    // Chọn gói tập
    function selectPackage(name, price) {
        const items = document.querySelectorAll('.invoice-item');
        const lastItem = items[items.length - 1];
        
        lastItem.querySelector('select[name*="loaihang"]').value = 'MEMBERSHIP';
        lastItem.querySelector('input[name*="mota"]').value = name;
        lastItem.querySelector('input[name*="dongia"]').value = price;
        
        calculateTotal();
        
        // Scroll to item
        lastItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
        lastItem.style.background = '#d4edda';
        setTimeout(() => lastItem.style.background = '#f8f9fa', 1000);
    }

    // Tính tổng tiền
    function calculateTotal() {
        let subtotal = 0;
        
        document.querySelectorAll('.invoice-item').forEach(item => {
            const qty = parseFloat(item.querySelector('input[name*="soluong"]')?.value) || 0;
            const price = parseFloat(item.querySelector('input[name*="dongia"]')?.value) || 0;
            subtotal += qty * price;
        });
        
        document.getElementById('subtotalAmount').textContent = subtotal.toLocaleString('vi-VN') + 'đ';
        
        // Tính giảm giá
        const promoSelect = document.getElementById('promoSelect');
        let discount = 0;
        
        if (promoSelect && promoSelect.value) {
            const selectedOption = promoSelect.options[promoSelect.selectedIndex];
            const type = selectedOption.dataset.type || 'FIXED';
            const value = parseFloat(selectedOption.dataset.value) || 0;
            
            if (type === 'PERCENT') {
                discount = subtotal * value / 100;
            } else {
                discount = value;
            }
        }
        
        document.getElementById('discountAmount').textContent = '-' + discount.toLocaleString('vi-VN') + 'đ';
        
        const final = subtotal - discount;
        document.getElementById('totalAmount').textContent = final.toLocaleString('vi-VN') + 'đ';
    }

    // Tính toán khi thay đổi khuyến mãi
    const promoSelect = document.getElementById('promoSelect');
    if (promoSelect) {
        promoSelect.addEventListener('change', calculateTotal);
    }

    // Tính toán ban đầu
    calculateTotal();

    // Validate form trước khi submit
    document.getElementById('invoiceForm').addEventListener('submit', function(e) {
        const items = document.querySelectorAll('.invoice-item');
        if (items.length === 0) {
            e.preventDefault();
            alert('Vui lòng thêm ít nhất 1 dòng hóa đơn!');
            return false;
        }
        
        let hasValidItem = false;
        items.forEach(item => {
            const mota = item.querySelector('input[name*="mota"]')?.value.trim();
            const dongia = parseFloat(item.querySelector('input[name*="dongia"]')?.value) || 0;
            if (mota && dongia > 0) {
                hasValidItem = true;
            }
        });
        
        if (!hasValidItem) {
            e.preventDefault();
            alert('Vui lòng nhập mô tả và đơn giá cho ít nhất 1 dòng hóa đơn!');
            return false;
        }
    });
    </script>
</body>
</html>