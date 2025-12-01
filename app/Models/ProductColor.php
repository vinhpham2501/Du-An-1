<?php

namespace App\Models;

use App\Core\Model;

class ProductColor extends Model
{
    protected $table = 'SANPHAM_MAU';

    public function addColors($productId, $colors)
    {
        if (empty($colors)) {
            return;
        }

        if (!is_array($colors)) {
            $colors = [$colors];
        }

        $sql = "INSERT INTO {$this->table} (MaSP, TenMau) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        foreach ($colors as $color) {
            $color = trim($color);
            if ($color === '') {
                continue;
            }
            $stmt->execute([$productId, $color]);
        }
    }

    public function getByProduct($productId)
    {
        $sql = "SELECT 
                    MaMau AS id,
                    MaSP AS product_id,
                    TenMau AS name
                FROM {$this->table}
                WHERE MaSP = ?
                ORDER BY MaMau ASC";

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
