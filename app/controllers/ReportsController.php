<?php
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../../config/database.php';

class ReportsController {
    private $db;
    private $report;
    public function __construct() {
        $this->db = Database::getInstance()->getConn();
        $this->report = new Report($this->db);
    }
    public function index() {
        $totalInvoices = $this->report->totalInvoices();
        $totalRevenue  = $this->report->totalRevenue();
        $totalMembers  = $this->report->totalMembers();
        $totalLockers  = $this->report->totalLockers();
        $invoiceStatus = $this->report->invoiceByStatus();
        include __DIR__ . '/../views/reports/index.php';
    }
}
?>