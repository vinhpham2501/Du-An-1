<?php

namespace App\Models;

use App\Core\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    public function createMultiple($orderId, $items)
    {
        $sql = "INSERT INTO {$this->table} (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        foreach ($items as $item) {
            $stmt->execute([
                $orderId,
                $item['product_id'],
                $item['quantity'],
                $item['price']
            ]);
        }
        
        return true;
    }
}
