<?php

namespace App\Models;

use App\Core\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    public function getAll($filters = [])
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        $conditions = [];

        if (isset($filters['status'])) {
            $conditions[] = "status = ?";
            $params[] = $filters['status'];
        }

        if (isset($filters['search'])) {
            $conditions[] = "(name LIKE ? OR email LIKE ? OR message LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $sql .= " ORDER BY created_at DESC";

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

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (name, email, message, status) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['name'],
            $data['email'],
            $data['message'],
            $data['status'] ?? 'new'
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function count($filters = [])
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];
        $conditions = [];

        if (isset($filters['status'])) {
            $conditions[] = "status = ?";
            $params[] = $filters['status'];
        }

        if (isset($filters['search'])) {
            $conditions[] = "(name LIKE ? OR email LIKE ? OR message LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getStatusCounts()
    {
        $sql = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        $counts = ['new' => 0, 'read' => 0, 'replied' => 0];
        foreach ($results as $result) {
            $counts[$result['status']] = $result['count'];
        }
        
        return $counts;
    }
}
