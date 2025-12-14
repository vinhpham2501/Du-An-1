<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Address;

class OrderController extends Controller
{
    private $orderModel;
    private $userModel;
    private $addressModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->orderModel = new Order();
        $this->userModel = new User();
        $this->addressModel = new Address();
    }

    public function index()
    {
        try {
            $filters = [
                'limit' => 20,
                'offset' => ($_GET['page'] ?? 1) - 1
            ];
            
            if (!empty($_GET['status'])) {
                $filters['status'] = $_GET['status'];
            }
            
            if (!empty($_GET['date_from'])) {
                $filters['date_from'] = $_GET['date_from'];
            }
            
            if (!empty($_GET['date_to'])) {
                $filters['date_to'] = $_GET['date_to'];
            }
            
            $orders = $this->orderModel->getAll($filters);
            $totalOrders = $this->orderModel->count($filters);
            
            return $this->render('admin/orders/index', [
                'orders' => $orders,
                'totalOrders' => $totalOrders,
                'filters' => $filters
            ]);
        } catch (\Exception $e) {
            error_log("Admin Orders error: " . $e->getMessage());
            return $this->render('admin/orders/index', [
                'orders' => [],
                'totalOrders' => 0,
                'filters' => $filters ?? [],
                'error' => 'Có lỗi xảy ra khi tải danh sách đơn hàng'
            ]);
        }
    }

    public function show($id)
    {
        try {
            $order = $this->orderModel->findById($id);
            
            if (!$order) {
                http_response_code(404);
                return $this->render('errors/404');
            }

            // Check if auto-update is requested
            $statusUpdated = false;
            if (isset($_GET['update']) && $_GET['update'] == '1') {
                $statusUpdated = $this->performSimpleUpdate($id, $order);
                // Refresh order data after update
                if ($statusUpdated) {
                    $order = $this->orderModel->findById($id);
                }
            }

            // Lấy thông tin khách hàng để map vào đơn hàng (phục vụ hiển thị)
            $user = $this->userModel->findById($order['user_id']);

            // Lấy địa chỉ giao hàng: ưu tiên theo address_id trong đơn, nếu không có thì lấy địa chỉ mặc định của user
            $address = null;
            if (!empty($order['address_id'])) {
                $address = $this->addressModel->findById($order['address_id']);
            }
            if (!$address && !empty($order['user_id'])) {
                $address = $this->addressModel->getDefaultForUser($order['user_id']);
            }

            // Ghép chuỗi địa chỉ đầy đủ nếu có dữ liệu
            $fullAddress = null;
            if ($address) {
                $parts = [];
                if (!empty($address['DiaChi'])) {
                    $parts[] = $address['DiaChi'];
                }
                if (!empty($address['PhuongXa'])) {
                    $parts[] = $address['PhuongXa'];
                }
                if (!empty($address['QuanHuyen'])) {
                    $parts[] = $address['QuanHuyen'];
                }
                if (!empty($address['TinhThanh'])) {
                    $parts[] = $address['TinhThanh'];
                }
                $fullAddress = implode(', ', $parts);
            }

            // Ensure required fields have default values
            $order['status'] = $this->orderModel->normalizeStatus($order['status'] ?? 'pending');

            // Nếu chưa có thông tin giao hàng thì fallback từ thông tin user / địa chỉ
            $order['delivery_name'] = $order['delivery_name']
                ?? ($user['full_name'] ?? 'N/A');
            $order['delivery_phone'] = $order['delivery_phone']
                ?? ($user['phone'] ?? 'N/A');
            $order['delivery_address'] = $order['delivery_address']
                ?? ($fullAddress ?: 'N/A');

            $order['notes'] = $order['notes'] ?? '';
            $order['updated_at'] = $order['updated_at'] ?? $order['created_at'];
            
            $orderItems = $this->orderModel->getOrderItems($id);
            
            return $this->render('admin/orders/show', [
                'order' => $order,
                'orderItems' => $orderItems ?? [],
                'user' => $user ?? null,
                'statusUpdated' => $statusUpdated
            ]);
        } catch (\Exception $e) {
            error_log("Admin Order show error: " . $e->getMessage());
            http_response_code(500);
            return $this->render('errors/500', [
                'error' => 'Có lỗi xảy ra khi tải chi tiết đơn hàng'
            ]);
        }
    }

    /**
     * Perform simple status update
     */
    private function performSimpleUpdate($id, $order)
    {
        try {
            $currentStatus = $this->orderModel->normalizeStatus($order['status'] ?? 'pending');
            
            // Không cập nhật nếu đã hoàn thành hoặc đã hủy
            if (in_array($currentStatus, ['completed', 'cancelled'], true)) {
                return false;
            }

            // Chuỗi trạng thái đơn giản
            $statusMap = [
                'pending' => 'confirmed',
                'confirmed' => 'preparing',
                'preparing' => 'delivering',
                'delivering' => 'completed'
            ];

            $newStatus = $statusMap[$currentStatus] ?? $currentStatus;

            if ($newStatus === $currentStatus) {
                return false;
            }

            // Cập nhật trực tiếp cột TrangThai trong DON_HANG
            $result = $this->orderModel->updateStatus($id, $newStatus);

            if ($result) {
                error_log("Updated order {$id}: {$currentStatus} -> {$newStatus}");
                return true;
            }

            return false;
        } catch (\Exception $e) {
            error_log("Simple update error: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $status = $_POST['status'] ?? '';
        $validStatuses = ['pending', 'confirmed', 'preparing', 'delivering', 'completed', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            return $this->json(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
        }
        
        $order = $this->orderModel->findById($id);
        if (!$order) {
            return $this->json(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        }

        $current = $this->orderModel->normalizeStatus($order['status'] ?? 'pending');
        $target = $this->orderModel->normalizeStatus($status);

        if (!$this->orderModel->canTransitionStatus($current, $target)) {
            return $this->json([
                'success' => false,
                'message' => 'Không thể chuyển trạng thái từ "' . $current . '" sang "' . $target . '"'
            ]);
        }

        // Cập nhật trạng thái đơn hàng vào cột TrangThai (model sẽ validate lại lần nữa)
        $updated = $this->orderModel->updateStatus($id, $target);

        if (!$updated) {
            return $this->json(['success' => false, 'message' => 'Không thể cập nhật trạng thái đơn hàng']);
        }

        return $this->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        try {
            $order = $this->orderModel->findById($id);
            if (!$order) {
                return $this->json(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
            }
            
            $current = $this->orderModel->normalizeStatus($order['status'] ?? 'pending');
            
            // Kiểm tra trạng thái đơn hàng - chỉ cho phép xóa đơn hàng đã hủy hoặc hoàn thành
            if (!in_array($current, ['cancelled', 'completed'], true)) {
                return $this->json([
                    'success' => false, 
                    'message' => 'Chỉ có thể xóa đơn hàng đã hủy hoặc đã hoàn thành'
                ]);
            }
            
            // Xóa các order items trước
            $this->orderModel->deleteOrderItems($id);
            
            // Xóa đơn hàng
            $result = $this->orderModel->delete($id);
            
            if ($result) {
                return $this->json(['success' => true, 'message' => 'Xóa đơn hàng thành công']);
            } else {
                return $this->json(['success' => false, 'message' => 'Không thể xóa đơn hàng']);
            }
            
        } catch (\Exception $e) {
            error_log("Delete order error: " . $e->getMessage());
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa đơn hàng']);
        }
    }
}