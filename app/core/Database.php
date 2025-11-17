<?php
// app/core/Database.php - KẾT NỐI DATABASE

class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        $config  = require __DIR__ . '/../../config/database.php';
        $charset = $config['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host={$config['host']};dbname={$config['db']};charset={$charset}";

        try {
            $this->conn = new PDO(
                $dsn,
                $config['user'],
                $config['pass'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            die("❌ Lỗi kết nối database: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function query($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("❌ Lỗi query: " . $e->getMessage() . "<br>SQL: " . $sql);
        }
    }
    
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("❌ Lỗi execute: " . $e->getMessage() . "<br>SQL: " . $sql);
        }
    }
    
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
    
    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }
    
    public function commit() {
        return $this->conn->commit();
    }
    
    public function rollback() {
        return $this->conn->rollBack();
    }
}