<?php

namespace App\Models;

use App\Core\Model;

class Order extends Model
{
    protected $table = 'DON_HANG';

    public function create($data)
    {
        // Map input fields to database columns
        $maKH = $data['user_id'] ?? null;
        $maDC = $data['address_id'] ?? null; // optional
        $tongTien = $data['total_amount'] ?? 0;
        $trangThai = $this->mapStatus($data['status'] ?? 'pending');
        $pttt = $data['payment_method'] ?? null;
        $ghiChu = $data['notes'] ?? null;

        $sql = "INSERT INTO {$this->table} (MaKH, MaDC, TongTien, TrangThai, PhuongThucThanhToan, GhiChu)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$maKH, $maDC, $tongTien, $trangThai, $pttt, $ghiChu])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function findById($id)
    {
        $sql = "SELECT 
                    MaDH AS id,
                    MaKH AS user_id,
                    MaDC AS address_id,
                    NgayDat AS created_at,
                    TongTien AS total_amount,
                    TrangThai AS status,
                    PhuongThucThanhToan AS payment_method,
                    GhiChu AS notes
                FROM {$this->table} WHERE MaDH = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        
        if ($order) {
            // Ensure default values for status and payment_status
            $order['status'] = $order['status'] ?? 'pending';
            $order['payment_status'] = $order['payment_status'] ?? 'pending';
        }
        
        return $order;
    }

    public function getByUserId($userId)
    {
        $sql = "SELECT 
                    MaDH AS id,
                    MaKH AS user_id,
                    MaDC AS address_id,
                    NgayDat AS created_at,
                    TongTien AS total_amount,
                    TrangThai AS status,
                    PhuongThucThanhToan AS payment_method,
                    GhiChu AS notes
                FROM {$this->table} WHERE MaKH = ? ORDER BY NgayDat DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getLastOrderByUserId($userId)
    {
        $sql = "SELECT 
                    MaDH AS id,
                    MaKH AS user_id,
                    NgayDat AS created_at,
                    TongTien AS total_amount,
                    TrangThai AS status
                FROM {$this->table} WHERE MaKH = ? ORDER BY NgayDat DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function getOrderItems($orderId)
    {
        // Lấy thông tin chi tiết sản phẩm trong đơn hàng, kèm ảnh đại diện từ bảng SANPHAM_HINHANH
        $sql = "SELECT 
                    oi.MaDH AS order_id,
                    oi.MaSP AS product_id,
                    oi.SoLuong AS quantity,
                    oi.DonGia AS price,
                    oi.ThanhTien AS line_total,
                    p.TenSP AS name,
                    (SELECT img.HinhAnh 
                     FROM SANPHAM_HINHANH img 
                     WHERE img.MaSP = p.MaSP 
                     ORDER BY img.MaHinh ASC 
                     LIMIT 1) AS image
                FROM CHI_TIET_DON_HANG oi 
                JOIN SAN_PHAM p ON oi.MaSP = p.MaSP 
                WHERE oi.MaDH = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function getStatistics($filters = [])
    {
        $conditions = ["TrangThai IN ('Hoàn tất', 'Đang giao', 'Chờ duyệt')"];
        $params = [];

        if (isset($filters['date_from'])) {
            $conditions[] = "DATE(NgayDat) >= ?";
            $params[] = $filters['date_from'];
        }

        if (isset($filters['date_to'])) {
            $conditions[] = "DATE(NgayDat) <= ?";
            $params[] = $filters['date_to'];
        }

        $sql = "SELECT 
                    COUNT(*) as total_orders,
                    COALESCE(SUM(TongTien), 0) as total_revenue,
                    COALESCE(AVG(TongTien), 0) as avg_order_value
                FROM {$this->table} 
                WHERE " . implode(' AND ', $conditions);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function getDailyRevenue($days = 7)
    {
        $sql = "SELECT 
                    DATE(NgayDat) as date,
                    COUNT(*) as orders,
                    COALESCE(SUM(TongTien), 0) as revenue
                FROM {$this->table} 
                WHERE DATE(NgayDat) >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                    AND TrangThai IN ('Hoàn tất', 'Đang giao', 'Chờ duyệt')
                GROUP BY DATE(NgayDat)
                ORDER BY date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }

    public function getAll($filters = [])
    {
        try {
            $sql = "SELECT 
                        o.MaDH AS id,
                        o.MaKH AS user_id,
                        o.NgayDat AS created_at,
                        o.TongTien AS total_amount,
                        o.TrangThai AS status,
                        u.HoTen AS user_name,
                        u.Email AS user_email
                    FROM {$this->table} o 
                    JOIN KHACH_HANG u ON o.MaKH = u.MaKH";
            $params = [];
            $conditions = [];

            if (isset($filters['status'])) {
                $conditions[] = "o.TrangThai = ?";
                $params[] = $filters['status'];
            }

            if (isset($filters['date_from'])) {
                $conditions[] = "DATE(o.NgayDat) >= ?";
                $params[] = $filters['date_from'];
            }

            if (isset($filters['date_to'])) {
                $conditions[] = "DATE(o.NgayDat) <= ?";
                $params[] = $filters['date_to'];
            }

            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(' AND ', $conditions);
            }

            $sql .= " ORDER BY o.NgayDat DESC";

            if (isset($filters['limit'])) {
                $sql .= " LIMIT " . (int)$filters['limit'];
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $orders = $stmt->fetchAll();

            // Bổ sung các field dùng trong view admin để tránh Undefined array key
            foreach ($orders as &$order) {
                // Tên và SĐT giao hàng: mặc định dùng tên khách hàng, SĐT để trống nếu không có
                if (!isset($order['delivery_name']) || $order['delivery_name'] === null) {
                    $order['delivery_name'] = $order['user_name'] ?? 'Khách hàng';
                }

                if (!isset($order['delivery_phone']) || $order['delivery_phone'] === null) {
                    $order['delivery_phone'] = '';
                }

                // Trạng thái thanh toán: mặc định là pending để tránh null truyền vào htmlspecialchars
                if (!isset($order['payment_status']) || $order['payment_status'] === null) {
                    $order['payment_status'] = 'pending';
                }
            }

            return $orders;
        } catch (\Exception $e) {
            error_log("Order getAll error: " . $e->getMessage());
            return [];
        }
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET TrangThai = ? WHERE MaDH = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    // Ghi đè update để map field app -> cột DB và dùng khóa MaDH
    public function update($id, $data)
    {
        if (empty($data)) return false;
        $map = [
            'user_id' => 'MaKH',
            'address_id' => 'MaDC',
            'total_amount' => 'TongTien',
            'status' => 'TrangThai',
            'payment_method' => 'PhuongThucThanhToan',
            'notes' => 'GhiChu',
        ];
        $set = [];
        $params = [];
        foreach ($data as $k => $v) {
            $col = $map[$k] ?? $k; // cho phép truyền trực tiếp tên cột VN nếu cần
            $set[] = $col . ' = ?';
            $params[] = $v;
        }
        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE MaDH = ?";
        $params[] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function count($filters = [])
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} o";
        $params = [];
        $conditions = [];

        if (isset($filters['status'])) {
            $conditions[] = "o.TrangThai = ?";
            $params[] = $filters['status'];
        }

        if (isset($filters['date_from'])) {
            $conditions[] = "DATE(o.NgayDat) >= ?";
            $params[] = $filters['date_from'];
        }

        if (isset($filters['date_to'])) {
            $conditions[] = "DATE(o.NgayDat) <= ?";
            $params[] = $filters['date_to'];
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
        $sql = "DELETE FROM {$this->table} WHERE MaDH = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function deleteOrderItems($orderId)
    {
        $sql = "DELETE FROM CHI_TIET_DON_HANG WHERE MaDH = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$orderId]);
    }

    private function mapStatus($status)
    {
        // Map internal status to Vietnamese labels used in reports
        $map = [
            'pending' => 'Chờ duyệt',
            'confirmed' => 'Đã xác nhận',
            'preparing' => 'Đang chuẩn bị',
            'delivering' => 'Đang giao',
            'completed' => 'Hoàn tất',
            'cancelled' => 'Hủy',
        ];
        return $map[$status] ?? $status;
    }
}
