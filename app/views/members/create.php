<?php 
$pageTitle = 'Thêm hội viên mới';
$currentPage = 'members';

// INCLUDE LAYOUTS ĐÚNG CHUẨN
include(__DIR__ . '/../layouts/header.php'); 
include(__DIR__ . '/../layouts/sidebar.php'); 
?>

<main class="main-content">
    <div class="content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <h2>Thêm hội viên mới</h2>
            <p>Điền thông tin để tạo hội viên mới</p>
        </div>
        
        <!-- CARD -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin hội viên</h3>
                <a href="/block-sports-center/public/index.php?page=members" class="btn btn-ghost btn-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
            
            <!-- FORM -->
            <form method="post" action="/block-sports-center/public/index.php?page=members_create">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Cột trái -->
                    <div>
                        <div class="form-group">
                            <label class="form-label">Họ và tên <span style="color: red;">*</span></label>
                            <input type="text" 
                                   name="full_name" 
                                   class="form-control" 
                                   placeholder="Nhập họ và tên đầy đủ"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Số điện thoại <span style="color: red;">*</span></label>
                            <input type="tel" 
                                   name="phone" 
                                   class="form-control" 
                                   placeholder="Nhập số điện thoại"
                                   pattern="[0-9]{10,11}"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email <span style="color: red;">*</span></label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control" 
                                   placeholder="Nhập địa chỉ email"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Giới tính</label>
                            <select name="gender" class="form-control">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Cột phải -->
                    <div>
                        <div class="form-group">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" 
                                   name="dob" 
                                   class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="address" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Nhập địa chỉ"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-control">
                                <option value="ACTIVE" selected>Active (Hoạt động)</option>
                                <option value="SUSPENDED">Suspended (Tạm ngưng)</option>
                                <option value="INACTIVE">Inactive (Không hoạt động)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" 
                                      class="form-control" 
                                      rows="2"
                                      placeholder="Ghi chú thêm (nếu có)"></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- FORM ACTIONS -->
                <div class="form-actions">
                    <a href="/block-sports-center/public/index.php?page=members" class="btn btn-ghost">
                        Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu hội viên
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</main>

<?php include(__DIR__ . '/../layouts/footer.php'); ?>
