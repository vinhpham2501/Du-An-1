<?php

namespace App\Models;

use App\Core\Model;

class ProductSize extends Model
{
    protected $table = 'SANPHAM_SIZE';

    public function addSizes($productId, $sizes)
    {
        if (empty($sizes)) return false;
        
        $sql = "INSERT INTO {$this->table} (MaSP, TenSize) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        
        foreach ($sizes as $size) {
            $stmt->execute([$productId, $size]);
        }
        
        return true;
    }

    public function getByProduct($productId)
    {
        $sql = "SELECT MaSize AS id, MaSP AS product_id, TenSize AS name 
                FROM {$this->table} 
                WHERE MaSP = ? 
                ORDER BY FIELD(TenSize, 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', '2XL', '3XL', '4XL', '5XL'), TenSize ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function deleteByProduct($productId)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaSP = ?");
        return $stmt->execute([$productId]);
    }

    public function updateSizes($productId, $sizes)
    {
        // Delete existing sizes
        $this->deleteByProduct($productId);
        
        // Add new sizes
        if (!empty($sizes)) {
            return $this->addSizes($productId, $sizes);
        }
        
        return true;
    }
}
