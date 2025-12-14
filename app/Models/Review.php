<?php

namespace App\Models;

use App\Core\Model;

class Review extends Model
{
    protected $table = 'BINH_LUAN_DANH_GIA';

    public function userHasPurchasedProduct($userId, $productId)
    {
        $sql = "SELECT 1
                FROM DON_HANG o
                JOIN CHI_TIET_DON_HANG od ON od.MaDH = o.MaDH
                WHERE o.MaKH = ?
                  AND od.MaSP = ?
                  AND o.TrangThai IN ('Hoàn tất', 'Hoàn thành', 'completed')
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int)$userId, (int)$productId]);
        return (bool)$stmt->fetchColumn();
    }

    public function userHasReviewedProduct($userId, $productId)
    {
        $sql = "SELECT 1 FROM {$this->table} WHERE MaKH = ? AND MaSP = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int)$userId, (int)$productId]);
        return (bool)$stmt->fetchColumn();
    }

    public function canUserReviewProduct($userId, $productId)
    {
        if (!$userId || !$productId) return false;
        if (!$this->userHasPurchasedProduct($userId, $productId)) return false;
        if ($this->userHasReviewedProduct($userId, $productId)) return false;
        return true;
    }

    public function findByProductId($productId, $limit = null)
    {
        $sql = "SELECT 
                    r.MaBL AS id,
                    r.MaKH AS user_id,
                    r.MaSP AS product_id,
                    r.NoiDung AS comment,
                    r.SoSao AS rating,
                    r.NgayDang AS created_at,
                    r.TrangThai AS status,
                    u.HoTen AS user_name,
                    ar.NoiDung AS admin_reply,
                    ar.NgayDang AS admin_replied_at
                FROM {$this->table} r 
                JOIN KHACH_HANG u ON r.MaKH = u.MaKH 
                LEFT JOIN BINH_LUAN_DANH_GIA_TRA_LOI ar ON ar.MaBL = r.MaBL AND ar.TrangThai = 1
                WHERE r.MaSP = ? 
                  AND r.TrangThai = 1
                ORDER BY r.NgayDang DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function getAverageRating($productId)
    {
        $sql = "SELECT AVG(SoSao) as avg_rating, COUNT(*) as total_reviews 
                FROM {$this->table} 
                WHERE MaSP = ?
                  AND TrangThai = 1";
        
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

    public function getAllForAdmin($filters = [])
    {
        $sql = "SELECT
                    r.MaBL AS id,
                    r.MaKH AS user_id,
                    r.MaSP AS product_id,
                    r.NoiDung AS comment,
                    r.SoSao AS rating,
                    r.NgayDang AS created_at,
                    r.TrangThai AS status,
                    u.HoTen AS user_name,
                    p.TenSP AS product_name,
                    ar.NoiDung AS admin_reply,
                    ar.NgayDang AS admin_replied_at
                FROM {$this->table} r
                JOIN KHACH_HANG u ON r.MaKH = u.MaKH
                JOIN SAN_PHAM p ON r.MaSP = p.MaSP
                LEFT JOIN BINH_LUAN_DANH_GIA_TRA_LOI ar ON ar.MaBL = r.MaBL AND ar.TrangThai = 1";

        $params = [];
        $conditions = [];

        if (isset($filters['status'])) {
            $conditions[] = 'r.TrangThai = ?';
            $params[] = (int)$filters['status'];
        }

        if (!empty($filters['product_id'])) {
            $conditions[] = 'r.MaSP = ?';
            $params[] = (int)$filters['product_id'];
        }

        if (!empty($filters['q'])) {
            $conditions[] = '(u.HoTen LIKE ? OR p.TenSP LIKE ? OR r.NoiDung LIKE ?)';
            $q = '%' . $filters['q'] . '%';
            $params[] = $q;
            $params[] = $q;
            $params[] = $q;
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY r.NgayDang DESC';

        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ' . (int)$filters['limit'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function setStatus($reviewId, $status)
    {
        $sql = "UPDATE {$this->table} SET TrangThai = ? WHERE MaBL = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([(int)$status, (int)$reviewId]);
    }

    public function deleteById($reviewId)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaBL = ?");
        return $stmt->execute([(int)$reviewId]);
    }

    public function upsertAdminReply($reviewId, $adminId, $content)
    {
        $content = trim((string)$content);
        if ($content === '') return false;

        $sql = "REPLACE INTO BINH_LUAN_DANH_GIA_TRA_LOI (MaBL, MaAdmin, NoiDung, NgayDang, TrangThai)
                VALUES (?, ?, ?, NOW(), 1)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([(int)$reviewId, (int)$adminId, $content]);
    }
}
