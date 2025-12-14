<?php

namespace App\Models;

use App\Core\Model;

class ReviewReply extends Model
{
    protected $table = 'PHAN_HOI_DANH_GIA';

    public function findByReviewId($reviewId)
    {
        $sql = "SELECT 
                    pr.MaPH AS id,
                    pr.MaBL AS review_id,
                    pr.NoiDung AS reply,
                    pr.NgayPhanHoi AS created_at,
                    pr.NguoiPhanHoi AS replied_by
                FROM {$this->table} pr 
                WHERE pr.MaBL = ? 
                ORDER BY pr.NgayPhanHoi DESC 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$reviewId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        // Expecting keys: review_id, reply, replied_by
        // Xóa phản hồi cũ nếu có (vì mỗi đánh giá chỉ có 1 phản hồi)
        $this->delete($data['review_id'] ?? 0);
        
        // Tạo phản hồi mới
        $sql = "INSERT INTO {$this->table} (MaBL, NoiDung, NgayPhanHoi, NguoiPhanHoi)
                VALUES (?, ?, NOW(), ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['review_id'] ?? 0,
            $data['reply'] ?? '',
            $data['replied_by'] ?? 'Admin'
        ]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($reviewId, $reply, $repliedBy = 'Admin')
    {
        // Xóa phản hồi cũ nếu có
        $this->delete($reviewId);
        
        // Tạo phản hồi mới
        return $this->create([
            'review_id' => $reviewId,
            'reply' => $reply,
            'replied_by' => $repliedBy
        ]);
    }

    public function delete($reviewId)
    {
        $sql = "DELETE FROM {$this->table} WHERE MaBL = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$reviewId]);
    }
}

