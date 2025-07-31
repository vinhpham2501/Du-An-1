<?php

namespace App\Models;

use App\Core\Model;

class Order extends Model
{
    protected $table = 'orders';

    public function getByUserId($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getOrderItems($orderId)
    {
        $sql = "SELECT oi.*, p.name, p.image, p.unit 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function getStatistics($filters = [])
    {
        $conditions = ["status IN ('completed', 'delivering', 'preparing')"];
        $params = [];

        if (isset($filters['date_from'])) {
            $conditions[] = "DATE(created_at) >= ?";
            $params[] = $filters['date_from'];
        }

        if (isset($filters['date_to'])) {
            $conditions[] = "DATE(created_at) <= ?";
            $params[] = $filters['date_to'];
        }

        $sql = "SELECT 
                    COUNT(*) as total_orders,
                    COALESCE(SUM(total_amount), 0) as total_revenue,
                    COALESCE(AVG(total_amount), 0) as avg_order_value
                FROM {$this->table} 
                WHERE " . implode(' AND ', $conditions);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function getDailyRevenue($days = 7)
    {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as orders,
                    COALESCE(SUM(total_amount), 0) as revenue
                FROM {$this->table} 
                WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                    AND status IN ('completed', 'delivering', 'preparing')
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }

    public function getAll($filters = [])
    {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM {$this->table} o 
                JOIN users u ON o.user_id = u.id";
        $params = [];
        $conditions = [];

        if (isset($filters['status'])) {
            $conditions[] = "o.status = ?";
            $params[] = $filters['status'];
        }

        if (isset($filters['date_from'])) {
            $conditions[] = "DATE(o.created_at) >= ?";
            $params[] = $filters['date_from'];
        }

        if (isset($filters['date_to'])) {
            $conditions[] = "DATE(o.created_at) <= ?";
            $params[] = $filters['date_to'];
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $sql .= " ORDER BY o.created_at DESC";

        if (isset($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
