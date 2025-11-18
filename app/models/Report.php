<?php
// app/models/Report.php
// Model cho báo cáo (nếu cần thêm logic phức tạp)

class Report extends Model
{
    // Model này có thể để trống hoặc thêm các phương thức xử lý báo cáo phức tạp
    
    /**
     * Format tiền VNĐ
     */
    public function formatCurrency($amount)
    {
        return number_format($amount, 0, ',', '.') . ' đ';
    }

    /**
     * Format số lượng
     */
    public function formatNumber($number)
    {
        return number_format($number, 0, ',', '.');
    }

    /**
     * Tính phần trăm tăng trưởng
     */
    public function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 2);
    }
}