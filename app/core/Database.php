<?php

class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        // ✅ Load config
        $config = require __DIR__ . '/../../config/database.php';
        
        // ✅ Validate config
        if (!is_array($config)) {
            die("❌ Lỗi: config/database.php phải return array, nhận được: " . gettype($config));
        }
        
        // ✅ Extract config với giá trị mặc định
        $host    = $config['host'] ?? '127.0.0.1';
        $port    = $config['port'] ??  3306;
        $dbname  = $config['db'] ?? 'block_sports_center';
        $user    = $config['user'] ?? 'root';
        $pass    = $config['pass'] ?? '';
        $charset = $config['charset'] ?? 'utf8mb4';

        // ✅ Tạo DSN
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

        try {
            $this->conn = new PDO(
                $dsn,
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            // ✅ Show chi tiết lỗi để debug
            $errorMsg = "❌ Lỗi kết nối database<br>";
            $errorMsg .= "Message: " . $e->getMessage() . "<br>";
            $errorMsg .= "Host: {$host}<br>";
            $errorMsg .= "Port: {$port}<br>";
            $errorMsg .= "Database: {$dbname}<br>";
            $errorMsg .= "User: {$user}<br>";
            $errorMsg .= "DSN: {$dsn}";
            die($errorMsg);
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
            die("❌ Lỗi query: " . $e->getMessage() . "<br>SQL: " . htmlspecialchars($sql));
        }
    }
    
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("❌ Lỗi execute: " . $e->getMessage() . "<br>SQL: " . htmlspecialchars($sql));
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