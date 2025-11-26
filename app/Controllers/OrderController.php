<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Address;

class OrderController extends Controller
{
    private $orderModel;
    private $orderItemModel;
    private $productModel;
    private $addressModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
        $this->productModel = new Product();
        $this->addressModel = new Address();
    }

    public function checkout()
    {
        $this->requireAuth();
        
        $cart = $_SESSION['cart'] ?? [];
        
        if (empty($cart)) {
            $this->redirect('/cart');
        }
        
        $cartItems = [];
        $total = 0;
        
        try {
            foreach ($cart as $productId => $quantity) {
                $product = $this->productModel->findById($productId);
                if ($product && $product['is_available']) {
                    $price = $product['price'];
                    $itemTotal = $price * $quantity;
                    $total += $itemTotal;
                    
                    $cartItems[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $itemTotal
                    ];
                }
            }
        } catch (\Exception $e) {
            error_log("Checkout error: " . $e->getMessage());
            return $this->render('order/checkout', [
                'error' => 'Có lỗi xảy ra khi tải thông tin sản phẩm',
                'cartItems' => [],
                'total' => 0,
                'savedInfo' => []
            ]);
        }
        
        if (empty($cartItems)) {
            $this->redirect('/cart');
        }
        
        // Lấy thông tin giao hàng đã lưu từ địa chỉ mặc định hoặc đơn hàng gần nhất
        $savedInfo = $this->getSavedDeliveryInfo($_SESSION['user_id']);
        
        return $this->render('order/checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'savedInfo' => $savedInfo
        ]);
    }

    public function placeOrder()
    {
        try {
            $this->requireAuth();
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->redirect('/checkout');
            }
            
            $cart = $_SESSION['cart'] ?? [];
            
            if (empty($cart)) {
                $this->redirect('/cart');
            }
            
            $deliveryName = $_POST['delivery_name'] ?? '';
            $deliveryPhone = $_POST['delivery_phone'] ?? '';
            $deliveryAddress = $_POST['delivery_address'] ?? '';
            $note = $_POST['note'] ?? '';
            $paymentMethod = $_POST['payment_method'] ?? 'cod';
            
            if (empty($deliveryName) || empty($deliveryPhone) || empty($deliveryAddress)) {
                $cartItems = $this->getCartItems();
                $total = $this->calculateTotal();
                return $this->render('order/checkout', [
                    'error' => 'Vui lòng nhập đầy đủ thông tin giao hàng',
                    'cartItems' => $cartItems,
                    'total' => $total
                ]);
            }
            
            // Calculate total and prepare order items
            $cartItems = [];
            $total = 0;
            
            foreach ($cart as $productId => $quantity) {
                $product = $this->productModel->findById($productId);
                if ($product && $product['is_available']) {
                    $price = $product['price'];
                    $itemTotal = $price * $quantity;
                    $total += $itemTotal;
                    
                    $cartItems[] = [
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'price' => $price
                    ];
                }
            }
            
            if (empty($cartItems)) {
                $this->redirect('/cart');
            }
            
            // Lưu/ cập nhật địa chỉ giao hàng mặc định
            $fullAddress = $deliveryAddress;
            $addressId = $this->addressModel->createOrUpdateDefault($_SESSION['user_id'], $fullAddress, $note);

            // Create order
            $orderData = [
                'user_id' => $_SESSION['user_id'],
                'address_id' => $addressId ?: null,
                'total_amount' => $total,
                'notes' => $note,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentMethod === 'bank_transfer' ? 'pending' : 'cod'
            ];
            
            $orderId = $this->orderModel->create($orderData);
            
            if ($orderId) {
                // Create order items
                $this->orderItemModel->createMultiple($orderId, $cartItems);
                
                // Clear cart
                unset($_SESSION['cart']);
                
                // Set success message
                $_SESSION['success'] = 'Đặt hàng thành công! Mã đơn hàng: #' . $orderId;
                
                // Redirect to success page
                return $this->render('order/success', [
                    'orderId' => $orderId,
                    'total' => $total
                ]);
            } else {
                $cartItems = $this->getCartItems();
                $total = $this->calculateTotal();
                return $this->render('order/checkout', [
                    'error' => 'Có lỗi xảy ra khi tạo đơn hàng, vui lòng thử lại',
                    'cartItems' => $cartItems,
                    'total' => $total
                ]);
            }
        } catch (\Exception $e) {
            error_log("Checkout error: " . $e->getMessage());
            $cartItems = $this->getCartItems();
            $total = $this->calculateTotal();
            return $this->render('order/checkout', [
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'cartItems' => $cartItems,
                'total' => $total
            ]);
        }
    }

    public function myOrders()
    {
        $this->requireAuth();
        
        $orders = $this->orderModel->getByUserId($_SESSION['user_id']);
        
        return $this->render('order/my-orders', [
            'orders' => $orders
        ]);
    }

    public function orderDetail($id)
    {
        try {
            $this->requireAuth();
            
            $order = $this->orderModel->findById($id);
            
            if (!$order || $order['user_id'] != $_SESSION['user_id']) {
                http_response_code(404);
                return $this->render('errors/404');
            }
            
            $orderItems = $this->orderModel->getOrderItems($id);
            
            return $this->render('order/detail', [
                'order' => $order,
                'orderItems' => $orderItems
            ]);
        } catch (\Exception $e) {
            error_log("OrderController::orderDetail error: " . $e->getMessage());
            http_response_code(500);
            return $this->render('errors/500', [
                'error' => 'Có lỗi xảy ra khi tải chi tiết đơn hàng'
            ]);
        }
    }

    public function cancelOrder()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $orderId = $_POST['order_id'] ?? 0;
        
        if (!$orderId) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        $order = $this->orderModel->findById($orderId);
        
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            return $this->json(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        }
        
        // Không cho phép hủy nếu đơn hàng đã ở trạng thái delivering trở lên
        $nonCancellableStatuses = ['delivering', 'completed'];
        if (in_array($order['status'], $nonCancellableStatuses)) {
            return $this->json(['success' => false, 'message' => 'Đơn hàng đang giao hoặc đã hoàn thành không thể hủy']);
        }
        
        // Chỉ cho phép hủy khi đơn hàng ở trạng thái pending hoặc confirmed
        $cancellableStatuses = ['pending', 'confirmed', 'preparing'];
        if (!in_array($order['status'], $cancellableStatuses)) {
            return $this->json(['success' => false, 'message' => 'Không thể hủy đơn hàng này']);
        }
        
        $this->orderModel->update($orderId, ['status' => 'cancelled']);
        
        return $this->json(['success' => true, 'message' => 'Hủy đơn hàng thành công']);
    }

    private function getCartItems()
    {
        $cart = $_SESSION['cart'] ?? [];
        $cartItems = [];
        
        foreach ($cart as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product) {
                $price = $product['price'];
                $itemTotal = $price * $quantity;
                
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $itemTotal
                ];
            }
        }
        
        return $cartItems;
    }

    private function calculateTotal()
    {
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        
        foreach ($cart as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product) {
                $price = $product['price'];
                $total += $price * $quantity;
            }
        }
        
        return $total;
    }

    private function getSavedDeliveryInfo($userId)
    {
        // Ưu tiên địa chỉ giao hàng mặc định
        $address = $this->addressModel->getDefaultForUser($userId);
        $info = [
            'phone' => $_SESSION['user_phone'] ?? '',
            'address' => ''
        ];
        if ($address) {
            $info['address'] = $address['DiaChi'];
        }
        return $info;
    }
}
