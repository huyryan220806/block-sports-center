<?php
// app/models/Setting.php
// Model cho cài đặt hệ thống

class Setting extends Model
{
    protected $table = 'settings';

    /**
     * Lấy tất cả cài đặt
     */
    public function getAll()
    {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $result = $this->db->query($sql)->fetchAll();

            $settings = [];
            foreach ($result as $row) {
                $settings[$row->setting_key] = $row->setting_value;
            }

            return $settings;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Lấy một cài đặt theo key
     */
    public function get($key, $default = null)
    {
        try {
            $sql = "SELECT setting_value FROM {$this->table} WHERE setting_key = ?";
            $result = $this->db->query($sql, [$key])->fetch();
            return $result->setting_value ?? $default;
        } catch (PDOException $e) {
            return $default;
        }
    }

    /**
     * Cập nhật hoặc tạo mới cài đặt
     */
    public function set($key, $value)
    {
        try {
            $checkSql = "SELECT COUNT(*) FROM {$this->table} WHERE setting_key = ?";
            $exists = $this->db->query($checkSql, [$key])->fetchColumn();

            if ($exists) {
                $sql = "UPDATE {$this->table} SET setting_value = ? WHERE setting_key = ?";
                return $this->db->execute($sql, [$value, $key]);
            } else {
                $sql = "INSERT INTO {$this->table} (setting_key, setting_value) VALUES (?, ?)";
                return $this->db->execute($sql, [$key, $value]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }
}