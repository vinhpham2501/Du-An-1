<?php

namespace App\Core;

class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findById($id)
    {
        $pk = $this->primaryKey;
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$pk} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll($filters = [])
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        $conditions = [];

        // Apply filters
        if (isset($filters['status'])) {
            $conditions[] = "status = ?";
            $params[] = $filters['status'];
        }

        if (isset($filters['search'])) {
            $conditions[] = "(name LIKE ? OR description LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        // Apply sorting
        if (isset($filters['sort'])) {
            switch ($filters['sort']) {
                case 'newest':
                    $sql .= " ORDER BY created_at DESC";
                    break;
                case 'oldest':
                    $sql .= " ORDER BY created_at ASC";
                    break;
                case 'name':
                    $sql .= " ORDER BY name ASC";
                    break;
                case 'price_low':
                    $sql .= " ORDER BY price ASC";
                    break;
                case 'price_high':
                    $sql .= " ORDER BY price DESC";
                    break;
            }
        } else {
            $pk = $this->primaryKey;
            $sql .= " ORDER BY {$pk} DESC";
        }

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
        $fields = array_keys($data);
        $placeholders = ':' . implode(', :', $fields);
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES ({$placeholders})";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    public function update($id, $data)
    {
        $fields = array_keys($data);
        $setClause = implode(' = ?, ', $fields) . ' = ?';

        $pk = $this->primaryKey;
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$pk} = ?";
        
        $params = array_values($data);
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $pk = $this->primaryKey;
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$pk} = ?");
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
            $conditions[] = "(name LIKE ? OR description LIKE ?)";
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
}
