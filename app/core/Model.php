<?php
// app/core/Model.php - BASE MODEL

class Model {
    protected $db;
    protected $table;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
}