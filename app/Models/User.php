<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return parent::create($data);
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
        return $this->update($id, ['password' => $hashedPassword]);
    }

    public function getAll($filters = [])
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        $conditions = [];

        if (isset($filters['role'])) {
            $conditions[] = "role = ?";
            $params[] = $filters['role'];
        }

        if (isset($filters['search'])) {
            $conditions[] = "(full_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $sql .= " ORDER BY created_at DESC";

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

        if (isset($filters['role'])) {
            $conditions[] = "role = ?";
            $params[] = $filters['role'];
        }

        if (isset($filters['search'])) {
            $conditions[] = "(full_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
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
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getActiveOrders($userId)
    {
        $sql = "SELECT COUNT(*) FROM orders WHERE user_id = ? AND status NOT IN ('completed', 'cancelled')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }
}
