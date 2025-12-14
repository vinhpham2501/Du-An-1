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
        $diaChi = is_array($fullAddress) ? ($fullAddress['DiaChi'] ?? '') : (string)$fullAddress;
        $phuongXa = is_array($fullAddress) ? ($fullAddress['PhuongXa'] ?? null) : null;
        $quanHuyen = is_array($fullAddress) ? ($fullAddress['QuanHuyen'] ?? null) : null;
        $tinhThanh = is_array($fullAddress) ? ($fullAddress['TinhThanh'] ?? null) : null;
        $ghiChu = is_array($fullAddress) ? ($fullAddress['GhiChu'] ?? $note) : $note;

        $diaChi = trim((string)$diaChi);
        if ($diaChi === '') {
            return false;
        }

        $existing = $this->getDefaultForUser($userId);

        $this->db->prepare("UPDATE {$this->table} SET MacDinh = 0 WHERE MaKH = ?")->execute([$userId]);

        if ($existing && !empty($existing['id'])) {
            $sql = "UPDATE {$this->table}
                    SET DiaChi = ?, PhuongXa = ?, QuanHuyen = ?, TinhThanh = ?, GhiChu = ?, MacDinh = 1
                    WHERE MaDC = ?";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([$diaChi, $phuongXa, $quanHuyen, $tinhThanh, $ghiChu, (int)$existing['id']])) {
                return (int)$existing['id'];
            }
            return false;
        }

        $sql = "INSERT INTO {$this->table} (MaKH, DiaChi, PhuongXa, QuanHuyen, TinhThanh, GhiChu, MacDinh)
                VALUES (?, ?, ?, ?, ?, ?, 1)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$userId, $diaChi, $phuongXa, $quanHuyen, $tinhThanh, $ghiChu])) {
            return $this->db->lastInsertId();
        }

        return false;
    }
}
