<?php

?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span><?php echo htmlspecialchars($_SESSION['success']); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span><?php echo htmlspecialchars($_SESSION['error']); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['warning'])): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <span><?php echo htmlspecialchars($_SESSION['warning']); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php unset($_SESSION['warning']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['info'])): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <span><?php echo htmlspecialchars($_SESSION['info']); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php unset($_SESSION['info']); ?>
<?php endif; ?>

<style>
/* Alert Styles */
.alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-size: 14px;
    animation: slideDown 0.3s ease-out;
    position: relative;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.alert i:first-child {
    font-size: 20px;
    flex-shrink: 0;
}

.alert span {
    flex: 1;
    line-height: 1.5;
}

.alert-close {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px 8px;
    opacity: 0.7;
    transition: opacity 0.2s;
    font-size: 16px;
    flex-shrink: 0;
}

.alert-close:hover {
    opacity: 1;
}

/* Success Alert (Xanh lá) */
.alert-success {
    background: #d4edda;
    border-left: 4px solid #28a745;
    color: #155724;
}

.alert-success i {
    color: #28a745;
}

/* Error Alert (Đỏ) */
.alert-error {
    background: #f8d7da;
    border-left: 4px solid #dc3545;
    color: #721c24;
}

.alert-error i {
    color: #dc3545;
}

/* Warning Alert (Vàng) */
.alert-warning {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    color: #856404;
}

.alert-warning i {
    color: #ffc107;
}

/* Info Alert (Xanh dương) */
.alert-info {
    background: #d1ecf1;
    border-left: 4px solid #17a2b8;
    color: #0c5460;
}

.alert-info i {
    color: #17a2b8;
}

/* Animation */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .alert {
        padding: 12px 16px;
        font-size: 13px;
    }
    
    .alert i:first-child {
        font-size: 18px;
    }
}
</style>

<script>
// Tự động ẩn thông báo sau 5 giây
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s, transform 0.5s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});
</script>