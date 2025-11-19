<?php

namespace App\Models;

use App\Core\Model;

class Review extends Model
{
    protected $table = 'BINH_LUAN_DANH_GIA';

    public function findByProductId($productId, $limit = null)
    {
        $sql = "SELECT 
                    r.MaBL AS id,
                    r.MaKH AS user_id,
                    r.MaSP AS product_id,
                    r.NoiDung AS comment,
                    r.SoSao AS rating,
                    r.NgayDang AS created_at,
                    r.TrangThai AS status,
                    u.HoTen AS user_name
                FROM {$this->table} r 
                JOIN KHACH_HANG u ON r.MaKH = u.MaKH 
                WHERE r.MaSP = ? 
                ORDER BY r.NgayDang DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function getAverageRating($productId)
    {
        $sql = "SELECT AVG(SoSao) as avg_rating, COUNT(*) as total_reviews 
                FROM {$this->table} 
                WHERE MaSP = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        // Expecting keys: user_id, product_id, rating, comment
        $sql = "INSERT INTO {$this->table} (MaKH, MaSP, NoiDung, SoSao, NgayDang, TrangThai)
                VALUES (?, ?, ?, ?, NOW(), 1)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['user_id'] ?? 0,
            $data['product_id'] ?? 0,
            $data['comment'] ?? '',
            $data['rating'] ?? 0,
        ]);
    }
}
