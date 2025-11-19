<?php

class Invoice {
    private $conn;
    private $table = 'hoadon';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Lấy tất cả hóa đơn với sort và phân trang
     */
    public function getAll($sort = 'id_desc', $page = 1, $perPage = 10, $search = '') {
        // ORDER BY
        switch ($sort) {
            case 'id_asc':
                $orderBy = 'hd.MAHDON ASC';
                break;
            case 'date_asc':
                $orderBy = 'hd.NGAYLAP ASC';
                break;
            case 'date_desc':
                $orderBy = 'hd.NGAYLAP DESC';
                break;
            case 'amount_asc':
                $orderBy = 'total_amount ASC';
                break;
            case 'amount_desc':
                $orderBy = 'total_amount DESC';
                break;
            case 'id_desc':
            default:
                $orderBy = 'hd.MAHDON DESC';
                break;
        }
        
        // WHERE search
        $whereClause = "1=1";
        $params = [];
        
        if (!empty($search)) {
            $whereClause .= " AND (
                hv.HOVATEN LIKE :search 
                OR hd.MAHDON LIKE :search
                OR km.CODE LIKE :search
            )";
            $params[':search'] = "%{$search}%";
        }
        
        // Tính tổng
        $countQuery = "SELECT COUNT(DISTINCT hd.MAHDON) 
                       FROM {$this->table} hd
                       LEFT JOIN hoivien hv ON hd.MAHV = hv.MAHV
                       LEFT JOIN khuyenmai km ON hd.MAKM = km.MAKM
                       WHERE {$whereClause}";
        
        $countStmt = $this->conn->prepare($countQuery);
        foreach ($params as $key => $val) {
            $countStmt->bindValue($key, $val);
        }
        $countStmt->execute();
        $total = (int)$countStmt->fetchColumn();
        
        // LIMIT OFFSET
        $offset = ($page - 1) * $perPage;
        
        // Query chính
        $query = "SELECT 
                    hd.MAHDON,
                    hd.MAHV,
                    hd.MAKM,
                    hd.NGAYLAP,
                    hd.TRANGTHAI,
                    hv.HOVATEN as TEN_HOIVIEN,
                    hv.SDT,
                    hv.EMAIL,
                    km.CODE as MA_KHUYENMAI,
                    km.MOTA as MOTA_KHUYENMAI,
                    km.LOAI as LOAI_KM,
                    km.GIATRI as GIATRI_KM,
                    COALESCE(SUM(dhd.SOLUONG * dhd.DONGIA), 0) as total_amount
                  FROM {$this->table} hd
                  LEFT JOIN hoivien hv ON hd.MAHV = hv.MAHV
                  LEFT JOIN khuyenmai km ON hd.MAKM = km.MAKM
                  LEFT JOIN donghoadon dhd ON hd.MAHDON = dhd.MAHDON
                  WHERE {$whereClause}
                  GROUP BY hd.MAHDON
                  ORDER BY {$orderBy}
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'data' => $stmt->fetchAll(PDO::FETCH_OBJ),
            'total' => $total,
            'totalPages' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Lấy chi tiết hóa đơn kèm dòng hóa đơn
     */
    public function find($id) {
        // Thông tin hóa đơn
        $query = "SELECT 
                    hd.*,
                    hv.HOVATEN,
                    hv.SDT,
                    hv.EMAIL,
                    hv.DIACHI,
                    km.CODE as MA_KHUYENMAI,
                    km.MOTA as MOTA_KHUYENMAI,
                    km.LOAI as LOAI_KM,
                    km.GIATRI as GIATRI_KM
                  FROM {$this->table} hd
                  LEFT JOIN hoivien hv ON hd.MAHV = hv.MAHV
                  LEFT JOIN khuyenmai km ON hd.MAKM = km.MAKM
                  WHERE hd.MAHDON = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $invoice = $stmt->fetch(PDO::FETCH_OBJ);
        
        if (!$invoice) return null;
        
        // Lấy các dòng hóa đơn
        $itemsQuery = "SELECT * FROM donghoadon WHERE MAHDON = :id ORDER BY MADONG";
        $itemsStmt = $this->conn->prepare($itemsQuery);
        $itemsStmt->bindParam(':id', $id);
        $itemsStmt->execute();
        $invoice->items = $itemsStmt->fetchAll(PDO::FETCH_OBJ);
        
        return $invoice;
    }
    
    /**
     * Thêm hóa đơn mới
     */
    public function create($data) {
        try {
            $this->conn->beginTransaction();
            
            // Insert hóa đơn
            $query = "INSERT INTO {$this->table} (MAHV, MAKM, NGAYLAP, TRANGTHAI)
                      VALUES (:mahv, :makm, :ngaylap, :trangthai)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':mahv' => $data['mahv'],
                ':makm' => !empty($data['makm']) ? $data['makm'] : null,
                ':ngaylap' => $data['ngaylap'],
                ':trangthai' => $data['trangthai']
            ]);
            
            $invoiceId = $this->conn->lastInsertId();
            
            // Insert các dòng hóa đơn
            if (!empty($data['items'])) {
                $itemQuery = "INSERT INTO donghoadon (MAHDON, LOAIHANG, REF_ID, MOTA, SOLUONG, DONGIA)
                              VALUES (:mahdon, :loaihang, :ref_id, :mota, :soluong, :dongia)";
                
                $itemStmt = $this->conn->prepare($itemQuery);
                
                foreach ($data['items'] as $item) {
                    $itemStmt->execute([
                        ':mahdon' => $invoiceId,
                        ':loaihang' => $item['loaihang'],
                        ':ref_id' => $item['ref_id'] ?? null,
                        ':mota' => $item['mota'],
                        ':soluong' => $item['soluong'],
                        ':dongia' => $item['dongia']
                    ]);
                }
            }
            
            $this->conn->commit();
            return $invoiceId;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
    /**
     * Cập nhật hóa đơn
     */
    public function update($id, $data) {
        try {
            $this->conn->beginTransaction();
            
            // Update hóa đơn
            $query = "UPDATE {$this->table} 
                      SET MAHV = :mahv, 
                          MAKM = :makm, 
                          NGAYLAP = :ngaylap, 
                          TRANGTHAI = :trangthai
                      WHERE MAHDON = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':mahv' => $data['mahv'],
                ':makm' => !empty($data['makm']) ? $data['makm'] : null,
                ':ngaylap' => $data['ngaylap'],
                ':trangthai' => $data['trangthai']
            ]);
            
            // Xóa dòng cũ
            $deleteItems = $this->conn->prepare("DELETE FROM donghoadon WHERE MAHDON = :id");
            $deleteItems->execute([':id' => $id]);
            
            // Insert dòng mới
            if (!empty($data['items'])) {
                $itemQuery = "INSERT INTO donghoadon (MAHDON, LOAIHANG, REF_ID, MOTA, SOLUONG, DONGIA)
                              VALUES (:mahdon, :loaihang, :ref_id, :mota, :soluong, :dongia)";
                
                $itemStmt = $this->conn->prepare($itemQuery);
                
                foreach ($data['items'] as $item) {
                    $itemStmt->execute([
                        ':mahdon' => $id,
                        ':loaihang' => $item['loaihang'],
                        ':ref_id' => $item['ref_id'] ?? null,
                        ':mota' => $item['mota'],
                        ':soluong' => $item['soluong'],
                        ':dongia' => $item['dongia']
                    ]);
                }
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
    /**
     * Xóa hóa đơn
     */
    public function delete($id) {
        try {
            // Xóa cascade: donghoadon + thanhtoan sẽ tự động xóa do FK
            $query = "DELETE FROM {$this->table} WHERE MAHDON = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Lấy danh sách hội viên (cho dropdown)
     */
    public function getMembers() {
        $query = "SELECT MAHV, HOVATEN, SDT, EMAIL 
                  FROM hoivien 
                  WHERE TRANGTHAI = 'ACTIVE'
                  ORDER BY HOVATEN";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Lấy danh sách khuyến mãi (cho dropdown)
     */
    public function getPromotions() {
        $query = "SELECT MAKM, CODE, MOTA, LOAI, GIATRI, NGAYBD, NGAYKT
                  FROM khuyenmai 
                  WHERE CURDATE() BETWEEN NGAYBD AND NGAYKT
                  ORDER BY MAKM";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Lấy danh sách loại gói tập (cho dropdown)
     */
    public function getPackages() {
        $query = "SELECT MALG, TENLG, THOIHAN, GIA, CAPDO, MOTA
                  FROM loaigoi 
                  ORDER BY GIA ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
/* */