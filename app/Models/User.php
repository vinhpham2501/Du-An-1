<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'KHACH_HANG';

    public function findByEmail($email)
    {
        $sql = "SELECT 
                    MaKH AS id,
                    HoTen AS full_name,
                    Email AS email,
                    MatKhau AS password,
                    SDT AS phone,
                    GioiTinh AS gender,
                    VaiTro AS role,
                    TrangThai AS status,
                    NgayDangKy AS created_at
                FROM {$this->table}
                WHERE Email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        // Map allowed inserts
        $map = [
            'full_name' => 'HoTen',
            'email' => 'Email',
            'password' => 'MatKhau',
            'phone' => 'SDT',
            'gender' => 'GioiTinh',
            'role' => 'VaiTro',
        ];
        $set = [];
        $params = [];
        foreach ($data as $k => $v) {
            // Skip empty values and address field (address not in database)
            if (isset($map[$k]) && $v !== '' && $v !== null) {
                $set[] = $map[$k] . ' = ?';
                $params[] = $v;
            }
        }
        if (empty($set)) return false;
        $sql = "INSERT INTO {$this->table} SET " . implode(', ', $set);
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($params)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    private function isHashed($hash)
    {
        // Detect bcrypt hash ($2y$...) commonly used by password_hash
        return is_string($hash) && preg_match('/^\$2y\$/', $hash) === 1;
    }

    public function verifyPassword($password, $hash)
    {
        if ($this->isHashed($hash)) {
            return password_verify($password, $hash);
        }
        // Legacy plaintext fallback
        return hash_equals((string)$hash, (string)$password);
    }

    public function upgradePasswordIfNeeded($id, $currentHash, $plainPassword)
    {
        if ($this->isHashed($currentHash)) {
            return false;
        }
        // Upgrade to hashed password
        return $this->updatePassword($id, $plainPassword);
    }

    public function updatePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE {$this->table} SET MatKhau = ? WHERE MaKH = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    public function getAll($filters = [])
    {
        $sql = "SELECT 
                    MaKH AS id,
                    HoTen AS full_name,
                    Email AS email,
                    MatKhau AS password,
                    SDT AS phone,
                    GioiTinh AS gender,
                    VaiTro AS role,
                    TrangThai AS status,
                    NgayDangKy AS created_at
                FROM {$this->table}";
        $params = [];
        $conditions = [];

        // Default only active users unless status filter provided
        if (isset($filters['status'])) {
            $conditions[] = "TrangThai = ?";
            $params[] = (int)$filters['status'];
        } else {
            $conditions[] = "TrangThai = ?";
            $params[] = 1;
        }

        if (isset($filters['role'])) {
            $conditions[] = "VaiTro = ?";
            $params[] = $filters['role'];
        }

        if (isset($filters['search'])) {
            $conditions[] = "(HoTen LIKE ? OR Email LIKE ? OR SDT LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $sql .= " ORDER BY NgayDangKy DESC";

        if (isset($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function count($filters = [])
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];
        $conditions = [];

        // Default only active users unless status filter provided
        if (isset($filters['status'])) {
            $conditions[] = "TrangThai = ?";
            $params[] = (int)$filters['status'];
        } else {
            $conditions[] = "TrangThai = ?";
            $params[] = 1;
        }

        if (isset($filters['role'])) {
            $conditions[] = "VaiTro = ?";
            $params[] = $filters['role'];
        }

        if (isset($filters['search'])) {
            $conditions[] = "(HoTen LIKE ? OR Email LIKE ? OR SDT LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function delete($id)
    {
        // Soft delete: mark as inactive instead of hard delete
        $sql = "UPDATE {$this->table} SET TrangThai = 0 WHERE MaKH = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getActiveOrders($userId)
    {
        $sql = "SELECT COUNT(*) FROM DON_HANG WHERE MaKH = ? AND TrangThai NOT IN ('Hoàn tất', 'Hủy')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function findById($id)
    {
        $sql = "SELECT 
                    MaKH AS id,
                    HoTen AS full_name,
                    Email AS email,
                    MatKhau AS password,
                    SDT AS phone,
                    GioiTinh AS gender,
                    VaiTro AS role,
                    TrangThai AS status,
                    NgayDangKy AS created_at
                FROM {$this->table}
                WHERE MaKH = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        // Map allowed updates
        $map = [
            'full_name' => 'HoTen',
            'phone' => 'SDT',
            'gender' => 'GioiTinh',
            'role' => 'VaiTro',
            'status' => 'TrangThai',
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
        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE MaKH = ?";
        $params[] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
