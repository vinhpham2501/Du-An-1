<?php

namespace App\Models;

use App\Core\Model;

class Order extends Model
{
    protected $table = 'DON_HANG';

    public function normalizeStatus($status)
    {
        $status = trim((string)$status);
        if ($status === '') return 'pending';

        $map = [
            // internal
            'pending' => 'pending',
            'confirmed' => 'confirmed',
            'preparing' => 'preparing',
            'delivering' => 'delivering',
            'completed' => 'completed',
            'cancelled' => 'cancelled',

            // Vietnamese variants found in code/DB
            'Chờ xác nhận' => 'pending',
            'Chờ duyệt' => 'pending',
            'Đã xác nhận' => 'confirmed',
            'Đang chuẩn bị' => 'preparing',
            'Đang giao' => 'delivering',
            'Hoàn tất' => 'completed',
            'Hoàn thành' => 'completed',
            'Hủy' => 'cancelled',
            'Đã hủy' => 'cancelled',
        ];

        return $map[$status] ?? $status;
    }

    public function canTransitionStatus($from, $to)
    {
        $from = $this->normalizeStatus($from);
        $to = $this->normalizeStatus($to);

        if ($from === $to) return true;

        // Lock terminal states
        if (in_array($from, ['completed', 'cancelled'], true)) {
            return false;
        }

        // Only allow step-by-step transitions + cancel from pending/confirmed
        $allowed = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['delivering'],
            'delivering' => ['completed'],
        ];

        return in_array($to, $allowed[$from] ?? [], true);
    }

    public function getSummaryByRange($dateFrom, $dateTo)
    {
        $sql = "SELECT 
                    COUNT(*) as total_orders,
                    COALESCE(SUM(TongTien), 0) as total_revenue,
                    COALESCE(AVG(TongTien), 0) as avg_order_value
                FROM {$this->table}
                WHERE DATE(NgayDat) BETWEEN ? AND ?
                  AND TrangThai IN ('Hoàn tất', 'Hoàn thành', 'completed')";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dateFrom, $dateTo]);
        return $stmt->fetch();
    }

    public function getSummaryWithComparison($dateFrom, $dateTo)
    {
        $current = $this->getSummaryByRange($dateFrom, $dateTo);

        $from = new \DateTime($dateFrom);
        $to = new \DateTime($dateTo);
        $days = (int)$from->diff($to)->format('%a');
        $periodDays = $days + 1;

        $prevTo = (clone $from)->modify('-1 day');
        $prevFrom = (clone $prevTo)->modify('-' . ($periodDays - 1) . ' days');

        $prevFromStr = $prevFrom->format('Y-m-d');
        $prevToStr = $prevTo->format('Y-m-d');

        $previous = $this->getSummaryByRange($prevFromStr, $prevToStr);

        $pct = function ($currentValue, $previousValue) {
            $currentValue = (float)$currentValue;
            $previousValue = (float)$previousValue;
            if ($previousValue == 0.0) {
                return $currentValue == 0.0 ? 0.0 : 100.0;
            }
            return (($currentValue - $previousValue) / $previousValue) * 100.0;
        };

        return [
            'current' => [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'total_orders' => (int)($current['total_orders'] ?? 0),
                'total_revenue' => (float)($current['total_revenue'] ?? 0),
                'avg_order_value' => (float)($current['avg_order_value'] ?? 0),
            ],
            'previous' => [
                'date_from' => $prevFromStr,
                'date_to' => $prevToStr,
                'total_orders' => (int)($previous['total_orders'] ?? 0),
                'total_revenue' => (float)($previous['total_revenue'] ?? 0),
                'avg_order_value' => (float)($previous['avg_order_value'] ?? 0),
            ],
            'change_pct' => [
                'total_orders' => $pct($current['total_orders'] ?? 0, $previous['total_orders'] ?? 0),
                'total_revenue' => $pct($current['total_revenue'] ?? 0, $previous['total_revenue'] ?? 0),
                'avg_order_value' => $pct($current['avg_order_value'] ?? 0, $previous['avg_order_value'] ?? 0),
            ]
        ];
    }

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
            // Map Vietnamese status to English for consistent use in views
            $order['status'] = $this->reverseMapStatus($order['status'] ?? 'pending');
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
        $orders = $stmt->fetchAll();
        
        // Map Vietnamese status to English for each order
        foreach ($orders as &$order) {
            $order['status'] = $this->reverseMapStatus($order['status'] ?? 'pending');
        }
        
        return $orders;
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
        $order = $stmt->fetch();
        
        if ($order) {
            // Map Vietnamese status to English
            $order['status'] = $this->reverseMapStatus($order['status'] ?? 'pending');
        }
        
        return $order;
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
        $conditions = ["TrangThai IN ('Hoàn tất', 'Hoàn thành', 'completed')"];
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
                    AND TrangThai IN ('Hoàn tất', 'Hoàn thành', 'completed')
                GROUP BY DATE(NgayDat)
                ORDER BY date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }

    public function getDailyRevenueByRange($dateFrom, $dateTo)
    {
        $sql = "SELECT 
                    DATE(NgayDat) as date,
                    COUNT(*) as orders,
                    COALESCE(SUM(TongTien), 0) as revenue
                FROM {$this->table}
                WHERE DATE(NgayDat) BETWEEN ? AND ?
                  AND TrangThai IN ('Hoàn tất', 'Hoàn thành', 'completed')
                GROUP BY DATE(NgayDat)
                ORDER BY date ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dateFrom, $dateTo]);
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
                        o.PhuongThucThanhToan AS payment_method,
                        u.HoTen AS user_name,
                        u.Email AS user_email
                    FROM {$this->table} o 
                    JOIN KHACH_HANG u ON o.MaKH = u.MaKH";
            $params = [];
            $conditions = [];

            if (isset($filters['status'])) {
                // Map English status to Vietnamese for database query
                $vietnameseStatus = $this->mapStatus($filters['status']);
                $conditions[] = "o.TrangThai = ?";
                $params[] = $vietnameseStatus;
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
                // Map Vietnamese status to English
                $order['status'] = $this->reverseMapStatus($order['status'] ?? 'pending');
                
                // Tên và SĐT giao hàng: mặc định dùng tên khách hàng, SĐT để trống nếu không có
                if (!isset($order['delivery_name']) || $order['delivery_name'] === null) {
                    $order['delivery_name'] = $order['user_name'] ?? 'Khách hàng';
                }

                if (!isset($order['delivery_phone']) || $order['delivery_phone'] === null) {
                    $order['delivery_phone'] = '';
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
        $order = $this->findById($id);
        if (!$order) {
            return false;
        }

        $current = $this->normalizeStatus($order['status'] ?? 'pending');
        $target = $this->normalizeStatus($status);

        if (!$this->canTransitionStatus($current, $target)) {
            return false;
        }

        // Lưu về DB theo nhãn tiếng Việt chuẩn (giữ tương thích dữ liệu hiện có)
        $dbStatus = $this->mapStatus($target);

        $sql = "UPDATE {$this->table} SET TrangThai = ? WHERE MaDH = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$dbStatus, $id]);
    }

    // Ghi đè update để map field app -> cột DB và dùng khóa MaDH
    public function update($id, $data)
    {
        if (empty($data)) return false;
        $map = [
            'user_id' => 'MaKH',
            'address_id' => 'MaDC',
            'total_amount'   => 'TongTien',
            'status'         => 'TrangThai',
            'payment_method' => 'PhuongThucThanhToan',
            'notes'          => 'GhiChu',
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
            // Map English status to Vietnamese for database query
            $vietnameseStatus = $this->mapStatus($filters['status']);
            $conditions[] = "o.TrangThai = ?";
            $params[] = $vietnameseStatus;
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
        // Map English status to Vietnamese labels used in database
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

    private function reverseMapStatus($status)
    {
        // Map Vietnamese status from database to English for consistent use in views
        $map = [
            'Chờ duyệt' => 'pending',
            'Chờ xác nhận' => 'pending',
            'Đã xác nhận' => 'confirmed',
            'Đang chuẩn bị' => 'preparing',
            'Đang giao' => 'delivering',
            'Hoàn tất' => 'completed',
            'Hoàn thành' => 'completed',
            'Hủy' => 'cancelled',
            'Đã hủy' => 'cancelled',
        ];
        return $map[$status] ?? $status;
    }

    /**
     * Kiểm tra xem khách hàng đã mua sản phẩm chưa (từ đơn hàng đã hoàn thành)
     * @param int $userId ID khách hàng
     * @param int $productId ID sản phẩm
     * @return bool True nếu đã mua, False nếu chưa mua
     */
    public function hasUserPurchasedProduct($userId, $productId)
    {
        $sql = "SELECT COUNT(*) 
                FROM {$this->table} o
                INNER JOIN CHI_TIET_DON_HANG oi ON o.MaDH = oi.MaDH
                WHERE o.MaKH = ? 
                AND oi.MaSP = ? 
                AND o.TrangThai IN ('Hoàn tất', 'Hoàn thành', 'completed')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $productId]);
        $count = $stmt->fetchColumn();
        
        return $count > 0;
    }
}
