<?php

namespace App\Models;

use App\Core\Model;

class OrderItem extends Model
{
    protected $table = 'CHI_TIET_DON_HANG';

    public function createMultiple($orderId, $items)
    {
        $sql = "INSERT INTO {$this->table} (MaDH, MaSP, SoLuong, DonGia, ThanhTien) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        foreach ($items as $item) {
            $qty = (int)($item['quantity'] ?? 1);
            $price = (float)($item['price'] ?? 0);
            $stmt->execute([
                $orderId,
                $item['product_id'],
                $qty,
                $price,
                $qty * $price
            ]);
        }
        
        return true;
    }
}
