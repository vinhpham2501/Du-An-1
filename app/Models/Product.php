<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{
    protected $table = 'SAN_PHAM';

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (TenSP, GioiThieu, ChiTiet, Gia, SoLuong, TrangThai, MaDM)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $soLuong = isset($data['quantity']) ? (int)$data['quantity'] : 0;
        $ok = $stmt->execute([
            $data['name'] ?? '',
            $data['intro'] ?? ($data['description'] ?? null),
            $data['detail'] ?? null,
            $data['price'] ?? 0,
            $soLuong,
            isset($data['is_available']) ? (int)$data['is_available'] : 1,
            $data['category_id'] ?? null,
        ]);
        if ($ok) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getAll($filters = [])
    {
        $sql = "SELECT 
                    p.MaSP AS id,
                    p.TenSP AS name,
                    p.GioiThieu AS intro,
                    p.ChiTiet AS detail,
                    p.GioiThieu AS description,
                    p.Gia AS price,
                    NULL AS sale_price,
                    (SELECT img.HinhAnh 
                     FROM SANPHAM_HINHANH img 
                     WHERE img.MaSP = p.MaSP 
                     ORDER BY img.MaHinh ASC 
                     LIMIT 1) AS image_url,
                    p.NgayTao AS created_at,
                    p.TrangThai AS is_available,
                    0 AS is_featured,
                    p.MaDM AS category_id,
                    c.TenDM AS category_name
                FROM {$this->table} p 
                LEFT JOIN DANH_MUC c ON p.MaDM = c.MaDM";

        $params = [];
        $conditions = [];

        // Mặc định chỉ lấy sản phẩm đang bán (TrangThai=1) trừ khi có filter khác
        if (!isset($filters['is_available'])) {
            $conditions[] = "p.TrangThai = ?";
            $params[] = 1;
        }

        if (isset($filters['category_id'])) {
            $conditions[] = "p.MaDM = ?";
            $params[] = $filters['category_id'];
        }

        if (isset($filters['is_available'])) {
            $conditions[] = "p.TrangThai = ?";
            $params[] = $filters['is_available'];
        }

        if (isset($filters['search'])) {
            $conditions[] = "(p.TenSP LIKE ? OR p.GioiThieu LIKE ? OR p.ChiTiet LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (isset($filters['price_min'])) {
            $conditions[] = "p.Gia >= ?";
            $params[] = (int)$filters['price_min'];
        }

        if (isset($filters['price_max']) && (int)$filters['price_max'] > 0) {
            $conditions[] = "p.Gia <= ?";
            $params[] = (int)$filters['price_max'];
        }

        if (isset($filters['exclude_id'])) {
            $conditions[] = "p.MaSP != ?";
            $params[] = $filters['exclude_id'];
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        // Sorting
        if (isset($filters['sort'])) {
            switch ($filters['sort']) {
                case 'newest':
                    $sql .= " ORDER BY p.NgayTao DESC";
                    break;
                case 'oldest':
                    $sql .= " ORDER BY p.NgayTao ASC";
                    break;
                case 'name':
                    $sql .= " ORDER BY p.TenSP ASC";
                    break;
                case 'price_low':
                    $sql .= " ORDER BY p.Gia ASC";
                    break;
                case 'price_high':
                    $sql .= " ORDER BY p.Gia DESC";
                    break;
            }
        } else {
            $sql .= " ORDER BY p.MaSP DESC";
        }

        // Limit & offset
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
        $sql = "SELECT COUNT(*) 
                FROM {$this->table} p 
                LEFT JOIN DANH_MUC c ON p.MaDM = c.MaDM";

        $params = [];
        $conditions = ["p.TrangThai = ?"];
        $params[] = 1;

        if (isset($filters['category_id'])) {
            $conditions[] = "p.MaDM = ?";
            $params[] = $filters['category_id'];
        }

        if (isset($filters['is_available'])) {
            $conditions[] = "p.TrangThai = ?";
            $params[] = $filters['is_available'];
        }

        if (isset($filters['search'])) {
            $conditions[] = "(p.TenSP LIKE ? OR p.GioiThieu LIKE ? OR p.ChiTiet LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (isset($filters['price_min'])) {
            $conditions[] = "p.Gia >= ?";
            $params[] = (int)$filters['price_min'];
        }

        if (isset($filters['price_max']) && (int)$filters['price_max'] > 0) {
            $conditions[] = "p.Gia <= ?";
            $params[] = (int)$filters['price_max'];
        }

        $sql .= " WHERE " . implode(' AND ', $conditions);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getByCategory($categoryId, $limit = null)
    {
        $sql = "SELECT 
                    p.MaSP AS id, 
                    p.TenSP AS name, 
                    p.GioiThieu AS intro,
                    p.ChiTiet AS detail,
                    p.GioiThieu AS description,
                    p.Gia AS price, 
                    NULL AS sale_price, 
                    (SELECT img.HinhAnh 
                     FROM SANPHAM_HINHANH img 
                     WHERE img.MaSP = p.MaSP 
                     ORDER BY img.MaHinh ASC 
                     LIMIT 1) AS image_url,
                    p.NgayTao AS created_at, 
                    p.TrangThai AS is_available,
                    0 AS is_featured, 
                    p.MaDM AS category_id
                FROM {$this->table} p
                WHERE p.MaDM = ? AND p.TrangThai = 1 
                ORDER BY p.NgayTao DESC";

        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function getTopSelling($limit = 10)
    {
        $sql = "SELECT 
                    p.MaSP AS id,
                    p.TenSP AS name,
                    p.GioiThieu AS intro,
                    p.ChiTiet AS detail,
                    p.GioiThieu AS description,
                    p.Gia AS price,
                    NULL AS sale_price,
                    (SELECT img.HinhAnh 
                     FROM SANPHAM_HINHANH img 
                     WHERE img.MaSP = p.MaSP 
                     ORDER BY img.MaHinh ASC 
                     LIMIT 1) AS image_url,
                    p.NgayTao AS created_at,
                    p.TrangThai AS is_available,
                    0 AS is_featured,
                    p.MaDM AS category_id,
                    c.TenDM AS category_name,
                    COALESCE(SUM(oi.SoLuong), 0) AS total_sold,
                    COALESCE(SUM(oi.SoLuong * oi.DonGia), 0) AS revenue
                FROM {$this->table} p 
                LEFT JOIN DANH_MUC c ON p.MaDM = c.MaDM
                LEFT JOIN CHI_TIET_DON_HANG oi ON p.MaSP = oi.MaSP
                LEFT JOIN DON_HANG o ON oi.MaDH = o.MaDH 
                WHERE p.TrangThai = 1 
                  AND (o.TrangThai IS NULL OR o.TrangThai NOT IN ('Hủy'))
                GROUP BY p.MaSP, p.MaDM, p.TenSP, p.GioiThieu, p.ChiTiet, p.Gia, p.TrangThai, p.NgayTao, c.TenDM
                ORDER BY total_sold DESC, p.NgayTao DESC 
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getSaleProducts($limit = 10)
    {
        $sql = "SELECT 
                    p.MaSP AS id, 
                    p.TenSP AS name, 
                    p.GioiThieu AS intro,
                    p.ChiTiet AS detail,
                    p.GioiThieu AS description,
                    p.Gia AS price, 
                    NULL AS sale_price, 
                    (SELECT img.HinhAnh 
                     FROM SANPHAM_HINHANH img 
                     WHERE img.MaSP = p.MaSP 
                     ORDER BY img.MaHinh ASC 
                     LIMIT 1) AS image_url,
                    p.NgayTao AS created_at, 
                    p.TrangThai AS is_available,
                    0 AS is_featured, 
                    p.MaDM AS category_id, 
                    c.TenDM AS category_name
                FROM {$this->table} p 
                LEFT JOIN DANH_MUC c ON p.MaDM = c.MaDM
                WHERE p.TrangThai = 1
                ORDER BY p.NgayTao DESC 
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getNewProducts($limit = 10)
    {
        $sql = "SELECT 
                    p.MaSP AS id, 
                    p.TenSP AS name, 
                    p.GioiThieu AS intro,
                    p.ChiTiet AS detail,
                    p.GioiThieu AS description,
                    p.Gia AS price, 
                    NULL AS sale_price, 
                    (SELECT img.HinhAnh 
                     FROM SANPHAM_HINHANH img 
                     WHERE img.MaSP = p.MaSP 
                     ORDER BY img.MaHinh ASC 
                     LIMIT 1) AS image_url,
                    p.NgayTao AS created_at, 
                    p.TrangThai AS is_available,
                    0 AS is_featured, 
                    p.MaDM AS category_id, 
                    c.TenDM AS category_name
                FROM {$this->table} p 
                LEFT JOIN DANH_MUC c ON p.MaDM = c.MaDM
                WHERE p.TrangThai = 1 
                ORDER BY p.NgayTao DESC 
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getFeaturedProducts($limit = 8)
    {
        $sql = "SELECT 
                    p.MaSP AS id, 
                    p.TenSP AS name, 
                    p.GioiThieu AS intro,
                    p.ChiTiet AS detail,
                    p.GioiThieu AS description,
                    p.Gia AS price, 
                    NULL AS sale_price, 
                    (SELECT img.HinhAnh 
                     FROM SANPHAM_HINHANH img 
                     WHERE img.MaSP = p.MaSP 
                     ORDER BY img.MaHinh ASC 
                     LIMIT 1) AS image_url,
                    p.NgayTao AS created_at, 
                    p.TrangThai AS is_available,
                    0 AS is_featured, 
                    p.MaDM AS category_id, 
                    c.TenDM AS category_name
                FROM {$this->table} p 
                LEFT JOIN DANH_MUC c ON p.MaDM = c.MaDM
                WHERE p.TrangThai = 1
                ORDER BY p.NgayTao DESC 
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT 
                    p.MaSP AS id, 
                    p.TenSP AS name, 
                    p.GioiThieu AS intro,
                    p.ChiTiet AS detail,
                    p.GioiThieu AS description,
                    p.Gia AS price, 
                    NULL AS sale_price, 
                    (SELECT img.HinhAnh 
                     FROM SANPHAM_HINHANH img 
                     WHERE img.MaSP = p.MaSP 
                     ORDER BY img.MaHinh ASC 
                     LIMIT 1) AS image_url,
                    p.NgayTao AS created_at, 
                    p.TrangThai AS is_available,
                    0 AS is_featured, 
                    p.MaDM AS category_id, 
                    c.TenDM AS category_name
                FROM {$this->table} p 
                LEFT JOIN DANH_MUC c ON p.MaDM = c.MaDM
                WHERE p.MaSP = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $map = [
            'name'        => 'TenSP',
            'intro'       => 'GioiThieu',
            'detail'      => 'ChiTiet',
            'description' => 'GioiThieu',
            'price'       => 'Gia',
            'quantity'    => 'SoLuong',
            'is_available'=> 'TrangThai',
            'category_id' => 'MaDM',
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
        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE MaSP = ?";
        $params[] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaSP = ?");
        return $stmt->execute([$id]);
    }
}
