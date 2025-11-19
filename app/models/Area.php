<?php

class Room
{
    protected $db;

    public function __construct()
    {
        // Tùy theo project của bạn, có thể là Database::getInstance(), hoặc $GLOBALS['db'], ...
        // Ở đây giả định bạn có class Database trả về instance PDO.
        $this->db = Database::getInstance();
    }

    /**
     * Lấy tất cả phòng + tên khu
     */
    public function getAll()
    {
        $sql = "
            SELECT 
                p.MAPHONG,
                p.MAKHU,
                p.TENPHONG,
                p.SUCCHUA,
                p.GHICHU,
                p.HOATDONG,
                k.TENKHU
            FROM phong p
            JOIN khu k ON p.MAKHU = k.MAKHU
            ORDER BY p.MAPHONG DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy chi tiết phòng theo ID
     */
    public function find($id)
    {
        $sql = "
            SELECT 
                p.MAPHONG,
                p.MAKHU,
                p.TENPHONG,
                p.SUCCHUA,
                p.GHICHU,
                p.HOATDONG,
                k.TENKHU
            FROM phong p
            JOIN khu k ON p.MAKHU = k.MAKHU
            WHERE p.MAPHONG = :id
            LIMIT 1
        ";
        /** @var PDO $pdo */
        $pdo = $this->db;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Thêm phòng mới
     */
    public function create($data)
    {
        $sql = "
            INSERT INTO phong (MAKHU, TENPHONG, SUCCHUA, GHICHU, HOATDONG)
            VALUES (:MAKHU, :TENPHONG, :SUCCHUA, :GHICHU, :HOATDONG)
        ";
        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([
            'MAKHU'    => $data['MAKHU'],
            'TENPHONG' => $data['TENPHONG'],
            'SUCCHUA'  => $data['SUCCHUA'],
            'GHICHU'   => $data['GHICHU'],
            'HOATDONG' => $data['HOATDONG'],
        ]);

        return $ok ? $this->db->lastInsertId() : false;
        }

        /**
         * Cập nhật phòng
         */
        public function update($id, $data)
        {
        $sql = "
            UPDATE phong
            SET 
            MAKHU    = :MAKHU,
            TENPHONG = :TENPHONG,
            SUCCHUA  = :SUCCHUA,
            GHICHU   = :GHICHU,
            HOATDONG = :HOATDONG
            WHERE MAPHONG = :MAPHONG
        ";
        $stmt = $this->db->prepare($sql); // yellew underline
        return $stmt->execute([
            'MAKHU'    => $data['MAKHU'],
            'TENPHONG' => $data['TENPHONG'],
            'SUCCHUA'  => $data['SUCCHUA'],
            'GHICHU'   => $data['GHICHU'],
            'HOATDONG' => $data['HOATDONG'],
            'MAPHONG'  => $id,
        ]);
        }

        /**
         * Xóa phòng
         */
        public function delete($id)
        {
        $sql = "DELETE FROM phong WHERE MAPHONG = :id";
        $stmt = $this->db->prepare($sql); // yellew underline
        return $stmt->execute(['id' => $id]);
    }
}
//