<!-- SIDEBAR - CHỈ SỬA HREF -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="/block-sports-center/public/assets/img/logo.png" 
             alt="Logo" 
             onerror="this.style.display='none'">
        <h1>BLOCK SPORTS CENTER</h1>
    </div>
    
    <nav class="sidebar-menu">

        <!-- Dashboard -->
        <a href="?c=dashboard&a=index" 
           class="menu-item <?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        <!-- Hội viên -->
        <a href="?c=members&a=index" 
           class="menu-item <?php echo ($currentPage == 'members') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            <span>Hội viên</span>
        </a>

        <!-- Phòng/Sân -->
        <a href="?c=rooms&a=index" 
           class="menu-item <?php echo ($currentPage == 'rooms') ? 'active' : ''; ?>">
            <i class="fas fa-door-open"></i>
            <span>Phòng/Sân</span>
        </a>

        <!-- Đặt phòng nhanh -->
        <a href="?c=bookings&a=index" 
           class="menu-item <?php echo ($currentPage == 'bookings') ? 'active' : ''; ?>">
            <i class="fas fa-calendar-plus"></i>
            <span>Đặt phòng</span>
        </a>

        <!-- Lớp học -->
        <a href="?c=classes&a=index" 
           class="menu-item <?php echo ($currentPage == 'classes') ? 'active' : ''; ?>">
            <i class="fas fa-layer-group"></i>
            <span>Lớp học</span>
        </a>

        <!-- Buổi lớp -->
        <a href="?c=sessions&a=index" 
           class="menu-item <?php echo ($currentPage == 'sessions') ? 'active' : ''; ?>">
            <i class="fas fa-calendar-check"></i>
            <span>Buổi lớp</span>
        </a>

        <!-- Huấn luyện viên -->
        <a href="?c=trainers&a=index" 
           class="menu-item <?php echo ($currentPage == 'trainers') ? 'active' : ''; ?>">
            <i class="fas fa-user-tie"></i>
            <span>Huấn luyện viên</span>
        </a>

        <!-- Locker -->
        <a href="?c=lockers&a=index" 
           class="menu-item <?php echo ($currentPage == 'lockers') ? 'active' : ''; ?>">
            <i class="fas fa-lock"></i>
            <span>Locker</span>
        </a>

        <!-- Hóa đơn -->
        <a href="?c=invoices&a=index" 
           class="menu-item <?php echo ($currentPage == 'invoices') ? 'active' : ''; ?>">
            <i class="fas fa-file-invoice-dollar"></i>
            <span>Hóa đơn</span>
        </a>

        <!-- Báo cáo -->
        <a href="?c=reports&a=index" 
           class="menu-item <?php echo ($currentPage == 'reports') ? 'active' : ''; ?>">
            <i class="fas fa-chart-pie"></i>
            <span>Báo cáo</span>
        </a>

        <!-- Cài đặt -->
        <a href="?c=settings&a=index" 
           class="menu-item <?php echo ($currentPage == 'settings') ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i>
            <span>Cài đặt</span>
        </a>

        <!-- Đăng xuất -->
        <a href="?c=auth&a=logout" 
           class="menu-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Đăng xuất</span>
        </a>

    </nav>
</aside>