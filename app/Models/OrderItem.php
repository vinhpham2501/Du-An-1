<?php

namespace App\Models;

use App\Core\Model;

class OrderItem extends Model
{
    protected $table = 'CHI_TIET_DON_HANG';

    public function createMultiple($orderId, $items)
    {
        // Gộp các dòng có cùng sản phẩm trong cùng đơn hàng để tránh trùng khóa chính (MaDH, MaSP)
        $merged = [];
        foreach ($items as $item) {
            $productId = (int)($item['product_id'] ?? 0);
            if (!$productId) {
                continue;
            }

            $qty   = (int)($item['quantity'] ?? 1);
            $price = (float)($item['price'] ?? 0);

            if (isset($merged[$productId])) {
                // Cộng dồn số lượng nếu cùng sản phẩm
                $merged[$productId]['quantity'] += $qty;
                // Đơn giá giữ nguyên (giả định cùng giá cho một sản phẩm trong một đơn)
            } else {
                $merged[$productId] = [
                    'product_id' => $productId,
                    'quantity'   => $qty,
                    'price'      => $price,
                ];
            }
        }

        if (empty($merged)) {
            return false;
        }

        $sql  = "INSERT INTO {$this->table} (MaDH, MaSP, SoLuong, DonGia, ThanhTien) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        foreach ($merged as $row) {
            $qty   = (int)$row['quantity'];
            $price = (float)$row['price'];
            $stmt->execute([
                $orderId,
                $row['product_id'],
                $qty,
                $price,
                $qty * $price,
            ]);
        }

        return true;
    }
}
