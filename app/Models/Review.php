<?php

namespace App\Models;

use App\Core\Model;

class Review extends Model
{
    protected $table = 'BINH_LUAN_DANH_GIA';
    protected $primaryKey = 'MaBL';

    public function findById($id)
    {
        $sql = "SELECT
                    r.MaBL AS id,
                    r.MaKH AS user_id,
                    r.MaSP AS product_id,
                    r.NoiDung AS comment,
                    r.SoSao AS rating,
                    r.NgayDang AS created_at,
                    r.TrangThai AS status,
                    r.DaXoa AS is_deleted,
                    u.HoTen AS user_name,
                    u.Email AS user_email,
                    p.TenSP AS product_name,
                    (SELECT img.HinhAnh 
                     FROM SANPHAM_HINHANH img 
                     WHERE img.MaSP = r.MaSP 
                     ORDER BY img.MaHinh ASC 
                     LIMIT 1) AS product_image
                FROM {$this->table} r
                LEFT JOIN KHACH_HANG u ON r.MaKH = u.MaKH
                LEFT JOIN SAN_PHAM p ON r.MaSP = p.MaSP
                WHERE r.MaBL = ?
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    public function getAll($filters = [])
    {
        // Admin list for /admin/reviews
        $sql = "SELECT
                    r.MaBL AS id,
                    r.MaKH AS user_id,
                    r.MaSP AS product_id,
                    r.NoiDung AS comment,
                    r.SoSao AS rating,
                    r.NgayDang AS created_at,
                    r.TrangThai AS status,
                    r.DaXoa AS is_deleted,
                    u.HoTen AS user_name,
                    u.Email AS user_email,
                    p.TenSP AS product_name,
                    ar.NoiDung AS admin_reply,
                    ar.NgayPhanHoi AS admin_replied_at
                FROM {$this->table} r
                JOIN KHACH_HANG u ON r.MaKH = u.MaKH
                JOIN SAN_PHAM p ON r.MaSP = p.MaSP
                LEFT JOIN PHAN_HOI_DANH_GIA ar ON ar.MaBL = r.MaBL";

        $params = [];
        $conditions = [];

        // Status filter: 0/1
        if (isset($filters['status']) && $filters['status'] !== '') {
            $conditions[] = 'r.TrangThai = ?';
            $params[] = (int)$filters['status'];
        }

        // Rating filter: 1..5
        if (isset($filters['rating']) && $filters['rating'] !== '') {
            $conditions[] = 'r.SoSao = ?';
            $params[] = (int)$filters['rating'];
        }

        // Search filter
        if (!empty($filters['search'])) {
            $conditions[] = '(u.HoTen LIKE ? OR u.Email LIKE ? OR p.TenSP LIKE ? OR r.NoiDung LIKE ?)';
            $q = '%' . trim((string)$filters['search']) . '%';
            $params[] = $q;
            $params[] = $q;
            $params[] = $q;
            $params[] = $q;
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY r.NgayDang DESC';

        if (isset($filters['limit'])) {
            $sql .= ' LIMIT ' . (int)$filters['limit'];
            if (isset($filters['offset'])) {
                $sql .= ' OFFSET ' . (int)$filters['offset'];
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function count($filters = [])
    {
        $sql = "SELECT COUNT(*)
                FROM {$this->table} r
                JOIN KHACH_HANG u ON r.MaKH = u.MaKH
                JOIN SAN_PHAM p ON r.MaSP = p.MaSP";

        $params = [];
        $conditions = [];

        if (isset($filters['status']) && $filters['status'] !== '') {
            $conditions[] = 'r.TrangThai = ?';
            $params[] = (int)$filters['status'];
        }

        if (isset($filters['rating']) && $filters['rating'] !== '') {
            $conditions[] = 'r.SoSao = ?';
            $params[] = (int)$filters['rating'];
        }

        if (!empty($filters['search'])) {
            $conditions[] = '(u.HoTen LIKE ? OR u.Email LIKE ? OR p.TenSP LIKE ? OR r.NoiDung LIKE ?)';
            $q = '%' . trim((string)$filters['search']) . '%';
            $params[] = $q;
            $params[] = $q;
            $params[] = $q;
            $params[] = $q;
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getRatingCounts()
    {
        // Return counts for 1..5 stars to render filter/stat cards
        $sql = "SELECT SoSao AS rating, COUNT(*) AS cnt
                FROM {$this->table}
                GROUP BY SoSao";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($rows as $r) {
            $rating = (int)($r['rating'] ?? 0);
            if ($rating >= 1 && $rating <= 5) {
                $counts[$rating] = (int)($r['cnt'] ?? 0);
            }
        }
        return $counts;
    }

    public function getStatusCounts()
    {
        $sql = "SELECT TrangThai AS status, COUNT(*) AS cnt
                FROM {$this->table}
                GROUP BY TrangThai";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        // status 1: hiển thị/đã duyệt, 0: ẩn/chưa duyệt
        $counts = ['1' => 0, '0' => 0];
        foreach ($rows as $r) {
            $status = (string)($r['status'] ?? '');
            if ($status === '0' || $status === '1') {
                $counts[$status] = (int)($r['cnt'] ?? 0);
            }
        }
        return $counts;
    }

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
        $sql = "SELECT 1 FROM {$this->table} WHERE MaKH = ? AND MaSP = ? AND DaXoa = 0 LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int)$userId, (int)$productId]);
        return (bool)$stmt->fetchColumn();
    }

    public function findByUserAndProduct($userId, $productId)
    {
        $sql = "SELECT
                    MaBL AS id,
                    MaKH AS user_id,
                    MaSP AS product_id,
                    NoiDung AS comment,
                    SoSao AS rating,
                    NgayDang AS created_at,
                    TrangThai AS status,
                    DaXoa AS is_deleted
                FROM {$this->table}
                WHERE MaKH = ? AND MaSP = ?
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int)$userId, (int)$productId]);
        return $stmt->fetch();
    }

    public function canUserReviewProduct($userId, $productId)
    {
        if (!$userId || !$productId) return false;
        if (!$this->userHasPurchasedProduct($userId, $productId)) return false;
        if ($this->userHasReviewedProduct($userId, $productId)) return false;
        return true;
    }

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
                    u.HoTen AS user_name,
                    ar.NoiDung AS admin_reply,
                    ar.NgayPhanHoi AS admin_replied_at
                FROM {$this->table} r 
                JOIN KHACH_HANG u ON r.MaKH = u.MaKH 
                LEFT JOIN PHAN_HOI_DANH_GIA ar ON ar.MaBL = r.MaBL
                WHERE r.MaSP = ? AND r.DaXoa = 0";

        if ($approvedOnly) {
            $sql .= " AND r.TrangThai = 1";
        }

        $sql .= "
                ORDER BY r.NgayDang DESC";
        
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
                WHERE MaSP = ? AND DaXoa = 0";

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
        if ($stmt->execute([
            $data['user_id'] ?? 0,
            $data['product_id'] ?? 0,
            $data['comment'] ?? '',
            $data['rating'] ?? 0,
        ])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($reviewId, $data)
    {
        $rating = isset($data['rating']) ? (int)$data['rating'] : null;
        $comment = isset($data['comment']) ? (string)$data['comment'] : null;

        $set = [];
        $params = [];

        if ($rating !== null) {
            $set[] = 'SoSao = ?';
            $params[] = $rating;
        }

        if ($comment !== null) {
            $set[] = 'NoiDung = ?';
            $params[] = $comment;
        }

        if (empty($set)) {
            return false;
        }

        $params[] = (int)$reviewId;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE MaBL = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function softDelete($reviewId)
    {
        $sql = "UPDATE {$this->table} SET DaXoa = 1 WHERE MaBL = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([(int)$reviewId]);
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
                    0 AS is_deleted,
                    u.HoTen AS user_name,
                    u.Email AS user_email,
                    p.TenSP AS product_name,
                    ar.NoiDung AS admin_reply,
                    ar.NgayPhanHoi AS admin_replied_at
                FROM {$this->table} r
                JOIN KHACH_HANG u ON r.MaKH = u.MaKH
                JOIN SAN_PHAM p ON r.MaSP = p.MaSP
                LEFT JOIN PHAN_HOI_DANH_GIA ar ON ar.MaBL = r.MaBL";

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
            $conditions[] = '(u.HoTen LIKE ? OR u.Email LIKE ? OR p.TenSP LIKE ? OR r.NoiDung LIKE ?)';
            $q = '%' . $filters['q'] . '%';
            $params[] = $q;
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

    public function updateStatus($reviewId, $status)
    {
        return $this->setStatus($reviewId, $status);
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

        // Lấy tên admin từ bảng KHACH_HANG
        $adminName = 'Admin';
        if ($adminId) {
            $userSql = "SELECT HoTen FROM KHACH_HANG WHERE MaKH = ? LIMIT 1";
            $userStmt = $this->db->prepare($userSql);
            $userStmt->execute([(int)$adminId]);
            $user = $userStmt->fetch();
            if ($user && !empty($user['HoTen'])) {
                $adminName = $user['HoTen'];
            }
        }

        // Kiểm tra xem đã có phản hồi chưa
        $checkSql = "SELECT MaPH FROM PHAN_HOI_DANH_GIA WHERE MaBL = ? LIMIT 1";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->execute([(int)$reviewId]);
        $existing = $checkStmt->fetch();

        if ($existing) {
            // Cập nhật phản hồi hiện có
            $sql = "UPDATE PHAN_HOI_DANH_GIA 
                    SET NoiDung = ?, NgayPhanHoi = NOW(), NguoiPhanHoi = ?
                    WHERE MaBL = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$content, $adminName, (int)$reviewId]);
        } else {
            // Tạo phản hồi mới
            $sql = "INSERT INTO PHAN_HOI_DANH_GIA (MaBL, NoiDung, NgayPhanHoi, NguoiPhanHoi)
                    VALUES (?, ?, NOW(), ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([(int)$reviewId, $content, $adminName]);
        }
    }
}
