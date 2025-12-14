<?php

namespace App\Models;

use App\Core\Model;

class Review extends Model
{
    protected $table = 'BINH_LUAN_DANH_GIA';

    public function findByProductId($productId, $limit = null, $approvedOnly = true)
    {
        $sql = "SELECT 
                    r.MaBL AS id,
                    r.MaKH AS user_id,
                    r.MaSP AS product_id,
                    r.NoiDung AS comment,
                    r.SoSao AS rating,
                    r.NgayDang AS created_at,
                    r.TrangThai AS status,
                    COALESCE(r.DaXoa, 0) AS is_deleted,
                    u.HoTen AS user_name,
                    pr.NoiDung AS reply,
                    pr.NgayPhanHoi AS reply_date,
                    pr.NguoiPhanHoi AS replied_by
                FROM {$this->table} r 
                JOIN KHACH_HANG u ON r.MaKH = u.MaKH 
                LEFT JOIN PHAN_HOI_DANH_GIA pr ON r.MaBL = pr.MaBL
                WHERE r.MaSP = ? AND COALESCE(r.DaXoa, 0) = 0";
        
        // Chỉ lấy đánh giá đã duyệt khi hiển thị ở frontend
        if ($approvedOnly) {
            $sql .= " AND r.TrangThai = 1";
        }
        
        $sql .= " ORDER BY r.NgayDang DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function getAverageRating($productId, $approvedOnly = true)
    {
        $sql = "SELECT AVG(SoSao) as avg_rating, COUNT(*) as total_reviews 
                FROM {$this->table} 
                WHERE MaSP = ? AND COALESCE(DaXoa, 0) = 0";
        
        // Chỉ tính đánh giá đã duyệt khi hiển thị ở frontend
        if ($approvedOnly) {
            $sql .= " AND TrangThai = 1";
        }
        
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

    public function getAll($filters = [])
    {
        $sql = "SELECT 
                    r.MaBL AS id,
                    r.MaKH AS user_id,
                    r.MaSP AS product_id,
                    r.NoiDung AS comment,
                    r.SoSao AS rating,
                    r.NgayDang AS created_at,
                    r.TrangThai AS status,
                    COALESCE(r.DaXoa, 0) AS is_deleted,
                    u.HoTen AS user_name,
                    u.Email AS user_email,
                    p.TenSP AS product_name
                FROM {$this->table} r 
                JOIN KHACH_HANG u ON r.MaKH = u.MaKH 
                LEFT JOIN SAN_PHAM p ON r.MaSP = p.MaSP 
                WHERE 1=1";
        
        $params = [];
        
        // Admin vẫn thấy đánh giá đã bị xóa, không filter theo DaXoa
        
        if (!empty($filters['status'])) {
            $sql .= " AND r.TrangThai = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['rating'])) {
            $sql .= " AND r.SoSao = ?";
            $params[] = $filters['rating'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (u.HoTen LIKE ? OR r.NoiDung LIKE ? OR p.TenSP LIKE ?)";
            $search = '%' . $filters['search'] . '%';
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }
        
        $sql .= " ORDER BY r.NgayDang DESC";
        
        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
            if (!empty($filters['offset'])) {
                $sql .= " OFFSET " . (int)$filters['offset'];
            }
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function count($filters = [])
    {
        $sql = "SELECT COUNT(*) as total
                FROM {$this->table} r 
                JOIN KHACH_HANG u ON r.MaKH = u.MaKH 
                LEFT JOIN SAN_PHAM p ON r.MaSP = p.MaSP 
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND r.TrangThai = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['rating'])) {
            $sql .= " AND r.SoSao = ?";
            $params[] = $filters['rating'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (u.HoTen LIKE ? OR r.NoiDung LIKE ? OR p.TenSP LIKE ?)";
            $search = '%' . $filters['search'] . '%';
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function findById($id)
    {
        try {
            $sql = "SELECT 
                        r.MaBL AS id,
                        r.MaKH AS user_id,
                        r.MaSP AS product_id,
                        r.NoiDung AS comment,
                        r.SoSao AS rating,
                        r.NgayDang AS created_at,
                        r.TrangThai AS status,
                        COALESCE(r.DaXoa, 0) AS is_deleted,
                        COALESCE(u.HoTen, 'N/A') AS user_name,
                        COALESCE(u.Email, '') AS user_email,
                        COALESCE(p.TenSP, 'N/A') AS product_name,
                        (SELECT img.HinhAnh 
                         FROM SANPHAM_HINHANH img 
                         WHERE img.MaSP = p.MaSP 
                         ORDER BY img.MaHinh ASC 
                         LIMIT 1) AS product_image,
                        pr.NoiDung AS reply,
                        pr.NgayPhanHoi AS reply_date,
                        pr.NguoiPhanHoi AS replied_by
                    FROM {$this->table} r 
                    LEFT JOIN KHACH_HANG u ON r.MaKH = u.MaKH 
                    LEFT JOIN SAN_PHAM p ON r.MaSP = p.MaSP 
                    LEFT JOIN PHAN_HOI_DANH_GIA pr ON r.MaBL = pr.MaBL
                    WHERE r.MaBL = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            
            // Đảm bảo tất cả các key cần thiết đều có giá trị
            if ($result) {
                $result['user_name'] = $result['user_name'] ?? 'N/A';
                $result['user_email'] = $result['user_email'] ?? '';
                $result['product_name'] = $result['product_name'] ?? 'N/A';
                $result['product_image'] = $result['product_image'] ?? null;
                $result['comment'] = $result['comment'] ?? '';
                $result['rating'] = $result['rating'] ?? 0;
                $result['status'] = $result['status'] ?? '0';
                $result['is_deleted'] = $result['is_deleted'] ?? 0;
                $result['reply'] = $result['reply'] ?? null;
                $result['reply_date'] = $result['reply_date'] ?? null;
                $result['replied_by'] = $result['replied_by'] ?? 'Shop';
            }
            
            return $result;
        } catch (\Exception $e) {
            error_log("Review findById error: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET TrangThai = ? WHERE MaBL = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE MaBL = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findByUserAndProduct($userId, $productId)
    {
        $sql = "SELECT 
                    r.MaBL AS id,
                    r.MaKH AS user_id,
                    r.MaSP AS product_id,
                    r.NoiDung AS comment,
                    r.SoSao AS rating,
                    r.NgayDang AS created_at,
                    r.TrangThai AS status,
                    COALESCE(r.DaXoa, 0) AS is_deleted
                FROM {$this->table} r 
                WHERE r.MaKH = ? AND r.MaSP = ? AND COALESCE(r.DaXoa, 0) = 0
                ORDER BY r.NgayDang DESC
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $productId]);
        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET NoiDung = ?, SoSao = ? WHERE MaBL = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['comment'] ?? '',
            $data['rating'] ?? 0,
            $id
        ]);
    }

    public function softDelete($id)
    {
        $sql = "UPDATE {$this->table} SET DaXoa = 1 WHERE MaBL = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getRatingCounts()
    {
        $sql = "SELECT SoSao AS rating, COUNT(*) as count 
                FROM {$this->table} 
                GROUP BY SoSao 
                ORDER BY SoSao DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        $counts = [];
        foreach ($results as $row) {
            $counts[$row['rating']] = $row['count'];
        }
        
        return $counts;
    }

    public function getStatusCounts()
    {
        $sql = "SELECT TrangThai AS status, COUNT(*) as count 
                FROM {$this->table} 
                GROUP BY TrangThai";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        $counts = [
            '0' => 0,
            '1' => 0
        ];
        
        foreach ($results as $row) {
            $counts[$row['status']] = $row['count'];
        }
        
        return $counts;
    }
}
