<?php

namespace App\Models;

use App\Core\Model;

class ProductImage extends Model
{
    protected $table = 'SANPHAM_HINHANH';

    public function addImages($productId, $images)
    {
        if (empty($images)) {
            return;
        }

        if (!is_array($images)) {
            $images = [$images];
        }

        $sql = "INSERT INTO {$this->table} (MaSP, HinhAnh) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        foreach ($images as $image) {
            $image = trim($image);
            if ($image === '') {
                continue;
            }
            $stmt->execute([$productId, $image]);
        }
    }

    public function getByProduct($productId)
    {
        $sql = "SELECT 
                    MaHinh AS id,
                    MaSP AS product_id,
                    HinhAnh AS image_url
                FROM {$this->table}
                WHERE MaSP = ?
                ORDER BY MaHinh ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function deleteByProduct($productId)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaSP = ?");
        return $stmt->execute([$productId]);
    }
}
