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
                    c.MoTa AS description,
                    c.TrangThai AS is_available,
                    c.NgayTao AS created_at,
                    COUNT(p.MaSP) AS product_count
                FROM {$this->table} c 
                LEFT JOIN SAN_PHAM p ON c.MaDM = p.MaDM
                GROUP BY c.MaDM, c.TenDM, c.MoTa, c.TrangThai, c.NgayTao
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
                    c.TrangThai AS is_available,
                    c.NgayTao AS created_at
                FROM {$this->table} c
                WHERE c.MaDM = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (TenDM, MoTa, TrangThai) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        $ok = $stmt->execute([
            $data['name'] ?? '',
            $data['description'] ?? null,
            $data['is_available'] ?? 1
        ]);
        
        if ($ok) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, $data)
    {
        $map = [
            'name' => 'TenDM',
            'description' => 'MoTa',
            'is_available' => 'TrangThai'
        ];
        
        $set = [];
        $params = [];
        
        foreach ($data as $k => $v) {
            if (isset($map[$k])) {
                $set[] = $map[$k] . ' = ?';
                $params[] = $v;
            }
        }
        
        if (empty($set)) return false;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE MaDM = ?";
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaDM = ?");
        return $stmt->execute([$id]);
    }

    public function getProductsCount($categoryId)
    {
        $sql = "SELECT COUNT(*) FROM SAN_PHAM WHERE MaDM = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoryId]);
        return $stmt->fetchColumn();
    }

    public function getActiveCategories()
    {
        $sql = "SELECT MaDM AS id, TenDM AS name 
                FROM {$this->table} 
                WHERE TrangThai = 1 
                ORDER BY TenDM ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
