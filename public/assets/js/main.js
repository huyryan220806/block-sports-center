// ============================================
// BLOCK SPORTS CENTER - MAIN JAVASCRIPT
// ============================================

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    
    // ========== SIDEBAR TOGGLE - ẨN/HIỆN MENU ==========
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const adminLayout = document.querySelector('.admin-layout');
    
    if (sidebarToggle && adminLayout) {
        // Lấy trạng thái từ localStorage
        const sidebarState = localStorage.getItem('sidebarCollapsed');
        if (sidebarState === 'true') {
            adminLayout.classList.add('sidebar-collapsed');
        }
        
        // Xử lý click nút toggle
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Sidebar toggle clicked!');
            
            adminLayout.classList.toggle('sidebar-collapsed');
            
            // Lưu trạng thái vào localStorage
            const isCollapsed = adminLayout.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
            
            console.log('Sidebar collapsed:', isCollapsed);
        });
    } else {
        console.warn('Sidebar toggle button hoặc admin-layout không tìm thấy');
    }
    
    // ========== SEARCH FUNCTIONALITY ==========
    const searchInputs = document.querySelectorAll('[id^="search"]');
    searchInputs.forEach(input => {
        input.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                performSearch(this.value);
            }
        });
    });
    
    // ========== FORM VALIDATION ==========
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#FF6B6B';
                } else {
                    field.style.borderColor = '#BFEEE2';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ các trường bắt buộc!');
            }
        });
    });
    
    // ========== AUTO-HIDE ALERTS ==========
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // ========== TABLE ROW CLICK ==========
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Skip if clicking on action buttons
            if (e.target.closest('.action-btns')) return;
            
            // Add selection effect
            tableRows.forEach(r => r.style.background = '');
            this.style.background = 'rgba(127, 255, 212, 0.1)';
        });
    });
    
    // ========== TOPBAR SEARCH ANIMATION ==========
    const topbarSearch = document.querySelector('.topbar-search input');
    if (topbarSearch) {
        topbarSearch.addEventListener('focus', function() {
            this.style.width = '100%';
        });
        
        topbarSearch.addEventListener('blur', function() {
            if (!this.value) {
                this.style.width = '';
            }
        });
    }
    
    // ========== USER AVATAR DROPDOWN ==========
    const userAvatar = document.querySelector('.user-avatar');
    if (userAvatar) {
        userAvatar.addEventListener('click', function() {
            // Toggle dropdown menu (backend sẽ xử lý)
            console.log('User menu clicked');
        });
    }
    
});

// ========== HELPER FUNCTIONS ==========

function performSearch(query) {
    console.log('Searching for:', query);
    // Backend sẽ xử lý tìm kiếm thực tế
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        background: ${type === 'success' ? '#51CF66' : '#FF6B6B'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// ========== FORMAT FUNCTIONS ==========

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('vi-VN');
}

// ========== ANIMATIONS ==========

const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);