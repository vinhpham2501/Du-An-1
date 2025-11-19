<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model
{
    protected $table = 'DANH_MUC';

    public function getAll($filters = [])
    {
        $sql = "SELECT 
                    c.MaDM AS id,
                    c.TenDM AS name,
                    COUNT(p.MaSP) AS product_count
                FROM {$this->table} c 
                LEFT JOIN SAN_PHAM p ON c.MaDM = p.MaDM AND p.TrangThai = 1
                WHERE c.TrangThai = 1
                GROUP BY c.MaDM, c.TenDM
                ORDER BY c.TenDM ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT 
                    c.MaDM AS id,
                    c.TenDM AS name,
                    c.MoTa AS description,
                    c.TrangThai AS is_active,
                    c.NgayTao AS created_at
                FROM {$this->table} c
                WHERE c.MaDM = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
