<?php

namespace App\Models;

use App\Core\Model;

class Address extends Model
{
    protected $table = 'DIA_CHI_GIAO_HANG';

    public function findById($id)
    {
        $sql = "SELECT MaDC AS id, MaKH AS user_id, DiaChi, PhuongXa, QuanHuyen, TinhThanh, GhiChu, MacDinh
                FROM {$this->table}
                WHERE MaDC = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getDefaultForUser($userId)
    {
        $sql = "SELECT MaDC AS id, MaKH AS user_id, DiaChi, PhuongXa, QuanHuyen, TinhThanh, GhiChu, MacDinh
                FROM {$this->table}
                WHERE MaKH = ?
                ORDER BY MacDinh DESC, MaDC DESC
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function createOrUpdateDefault($userId, $fullAddress, $note = null)
    {
        // Đơn giản: luôn tạo bản ghi mới và đặt MacDinh=1
        $sql = "INSERT INTO {$this->table} (MaKH, DiaChi, GhiChu, MacDinh)
                VALUES (?, ?, ?, 1)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$userId, $fullAddress, $note])) {
            return $this->db->lastInsertId();
        }
        return false;
    }
}
