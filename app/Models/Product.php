<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{
    protected $table = 'products';

    public function getAll($filters = [])
    {
        $sql = "SELECT p.*, c.name as category_name FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id";
        $params = [];
        $conditions = [];

        // Mặc định chỉ lấy sản phẩm available (trừ khi có filter khác)
        if (!isset($filters['is_available'])) {
            $conditions[] = "p.is_available = ?";
            $params[] = 1;
        }

        if (isset($filters['category_id'])) {
            $conditions[] = "p.category_id = ?";
            $params[] = $filters['category_id'];
        }

        if (isset($filters['is_available'])) {
            $conditions[] = "p.is_available = ?";
            $params[] = $filters['is_available'];
        }

        if (isset($filters['search'])) {
            $conditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (isset($filters['exclude_id'])) {
            $conditions[] = "p.id != ?";
            $params[] = $filters['exclude_id'];
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        // Apply sorting
        if (isset($filters['sort'])) {
            switch ($filters['sort']) {
                case 'newest':
                    $sql .= " ORDER BY p.created_at DESC";
                    break;
                case 'oldest':
                    $sql .= " ORDER BY p.created_at ASC";
                    break;
                case 'name':
                    $sql .= " ORDER BY p.name ASC";
                    break;
                case 'price_low':
                    $sql .= " ORDER BY p.price ASC";
                    break;
                case 'price_high':
                    $sql .= " ORDER BY p.price DESC";
                    break;
            }
        } else {
            $sql .= " ORDER BY p.id DESC";
        }

        // Apply limit
        if (isset($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
            if (isset($filters['offset'])) {
                $sql .= " OFFSET " . (int)$filters['offset'];
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function count($filters = [])
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id";
        $params = [];
        $conditions = ['p.is_available = ?'];
        $params[] = true;

        if (isset($filters['category_id'])) {
            $conditions[] = "p.category_id = ?";
            $params[] = $filters['category_id'];
        }

        if (isset($filters['is_available'])) {
            $conditions[] = "p.is_available = ?";
            $params[] = $filters['is_available'];
        }

        if (isset($filters['search'])) {
            $conditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        $sql .= " WHERE " . implode(' AND ', $conditions);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getByCategory($categoryId, $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE category_id = ? AND is_available = true ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function getTopSelling($limit = 10)
    {
        $sql = "SELECT p.*, c.name as category_name, 
                COALESCE(SUM(oi.quantity), 0) as total_sold,
                COALESCE(SUM(oi.quantity * oi.price), 0) as revenue
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN order_items oi ON p.id = oi.product_id
                LEFT JOIN orders o ON oi.order_id = o.id 
                WHERE p.is_available = true AND (o.status IS NULL OR o.status IN ('completed', 'delivering'))
                GROUP BY p.id, p.category_id, p.name, p.description, p.price, p.image_url, p.is_available, p.is_featured, p.created_at, p.updated_at, c.name
                ORDER BY total_sold DESC, p.created_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getSaleProducts($limit = 10)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_available = true AND p.sale_price IS NOT NULL AND p.sale_price > 0 AND p.sale_price < p.price
                ORDER BY ((p.price - p.sale_price) / p.price) DESC, p.created_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getNewProducts($limit = 10)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_available = true 
                ORDER BY p.created_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getFeaturedProducts($limit = 8)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_available = true AND p.is_featured = true
                ORDER BY p.created_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
