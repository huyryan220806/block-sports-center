<?php 
$pageTitle   = 'Thêm phòng/sân';
$currentPage = 'rooms';

include(__DIR__ . '/../layouts/header.php'); 
include(__DIR__ . '/../layouts/sidebar.php'); 
?>

<main class="main-content">
    <div class="content">
        <div class="page-header">
            <h2>Thêm phòng/sân mới</h2>
            <p>Điền thông tin để thêm phòng/sân mới cho trung tâm</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin phòng/sân</h3>
                <a href="/block-sports-center/public/index.php?page=rooms" 
                   class="btn btn-ghost btn-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>

            <form method="post" action="">
                <div class="form-layout">
                    <div>
                        <div class="form-group">
                            <label class="form-label">Tên phòng/sân <span style="color: red;">*</span></label>
                            <input type="text" 
                                   name="room_name" 
                                   class="form-control" 
                                   placeholder="VD: Phòng Yoga A1"
                                   required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Khu vực</label>
                            <input type="text" 
                                   name="area" 
                                   class="form-control" 
                                   placeholder="VD: Tầng 1 - Khu A">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sức chứa (người)</label>
                            <input type="number" 
                                   name="capacity" 
                                   class="form-control" 
                                   min="1" 
                                   placeholder="VD: 20">
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label class="form-label">Loại phòng/sân</label>
                            <select name="type" class="form-control">
                                <option value="">-- Chọn loại --</option>
                                <option value="Yoga Studio">Yoga Studio</option>
                                <option value="Dance Studio">Dance Studio</option>
                                <option value="Gym Zone">Gym Zone</option>
                                <option value="Boxing Room">Boxing Room</option>
                                <option value="Sân ngoài trời">Sân ngoài trời</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-control">
                                <option value="AVAILABLE" selected>Available (Sẵn sàng)</option>
                                <option value="MAINTENANCE">Maintenance (Bảo trì)</option>
                                <option value="UNAVAILABLE">Unavailable (Không sử dụng)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Ghi chú thêm (nếu có)"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="/block-sports-center/public/index.php?page=rooms" 
                       class="btn btn-ghost">
                        Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu phòng/sân
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include(__DIR__ . '/../layouts/footer.php'); ?>
