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
    const MAX_QUANTITY_PER_PRODUCT = 5; // Giới hạn tối đa 5 sản phẩm mỗi loại

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
            foreach ($cart as $key => $item) {
                // Hỗ trợ cả cấu trúc giỏ hàng cũ (productId => quantity)
                // và cấu trúc mới (cartKey => ['product_id','quantity','color','size'])
                if (is_array($item)) {
                    $productId = (int)($item['product_id'] ?? 0);
                    $quantity  = (int)($item['quantity'] ?? 1);
                    $color     = $item['color'] ?? '';
                    $size      = $item['size'] ?? '';
                } else {
                    $productId = (int)$key;
                    $quantity  = (int)$item;
                    $color     = '';
                    $size      = '';
                }

                if (!$productId || $quantity <= 0) {
                    continue;
                }

                $product = $this->productModel->findById($productId);
                if ($product && $product['is_available']) {
                    $price = $product['price'];
                    $itemTotal = $price * $quantity;
                    $total += $itemTotal;
                    
                    $cartItems[] = [
                        'product'  => $product,
                        'quantity' => $quantity,
                        'price'    => $price,
                        'total'    => $itemTotal,
                        'color'    => $color,
                        'size'     => $size,
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
            $violationProducts = []; // Lưu sản phẩm vượt quá giới hạn
            
            foreach ($cart as $key => $item) {
                // Hỗ trợ cả cấu trúc giỏ hàng cũ và mới
                if (is_array($item)) {
                    $productId = (int)($item['product_id'] ?? 0);
                    $quantity  = (int)($item['quantity'] ?? 1);
                    $color     = $item['color'] ?? '';
                    $size      = $item['size'] ?? '';
                } else {
                    $productId = (int)$key;
                    $quantity  = (int)$item;
                    $color     = '';
                    $size      = '';
                }

                if (!$productId || $quantity <= 0) {
                    continue;
                }
                
                // Kiểm tra giới hạn tối đa 5 sản phẩm
                if ($quantity > self::MAX_QUANTITY_PER_PRODUCT) {
                    $product = $this->productModel->findById($productId);
                    $violationProducts[] = $product['name'] ?? "Sản phẩm #$productId";
                    continue;
                }

                $product = $this->productModel->findById($productId);
                if ($product && $product['is_available']) {
                    $price = $product['price'];
                    $itemTotal = $price * $quantity;
                    $total += $itemTotal;
                    
                    // OrderItem hiện tại chưa lưu màu/size, nhưng vẫn giữ trong mảng để mở rộng sau
                    $cartItems[] = [
                        'product_id' => $productId,
                        'quantity'   => $quantity,
                        'price'      => $price,
                        'color'      => $color,
                        'size'       => $size,
                    ];
                }
            }
            
            // Nếu có sản phẩm vượt quá giới hạn, báo lỗi
            if (!empty($violationProducts)) {
                $cartItems = $this->getCartItems();
                $total = $this->calculateTotal();
                $errorMessage = 'Các sản phẩm sau vượt quá giới hạn mua tối đa ' . self::MAX_QUANTITY_PER_PRODUCT . ' cái: ' . implode(', ', $violationProducts) . '. Vui lòng liên hệ người bán để mua nhiều hơn.';
                return $this->render('order/checkout', [
                    'error' => $errorMessage,
                    'cartItems' => $cartItems,
                    'total' => $total
                ]);
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

        // Chuẩn hóa thông tin giao hàng cho từng đơn để tránh hiển thị N/A
        $defaultName = $_SESSION['user_name'] ?? 'Khách hàng';
        $defaultPhone = $_SESSION['user_phone'] ?? '';

        foreach ($orders as &$order) {
            $order['delivery_name'] = $defaultName;
            $order['delivery_phone'] = $defaultPhone;
            $order['delivery_address'] = '';

            if (!empty($order['address_id'])) {
                $address = $this->addressModel->findById($order['address_id']);
                if ($address) {
                    $parts = array_filter([
                        $address['DiaChi'] ?? null,
                        $address['PhuongXa'] ?? null,
                        $address['QuanHuyen'] ?? null,
                        $address['TinhThanh'] ?? null,
                    ]);
                    $order['delivery_address'] = implode(', ', $parts);
                }
            }
        }
        unset($order);

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
            
            // Chuẩn hóa thông tin giao hàng để hiển thị
            $deliveryName = $_SESSION['user_name'] ?? 'Khách hàng';
            $deliveryPhone = $_SESSION['user_phone'] ?? '';
            $deliveryAddress = '';

            if (!empty($order['address_id'])) {
                $address = $this->addressModel->findById($order['address_id']);
                if ($address) {
                    $parts = array_filter([
                        $address['DiaChi'] ?? null,
                        $address['PhuongXa'] ?? null,
                        $address['QuanHuyen'] ?? null,
                        $address['TinhThanh'] ?? null,
                    ]);
                    $deliveryAddress = implode(', ', $parts);
                }
            }

            $order['delivery_name'] = $deliveryName;
            $order['delivery_phone'] = $deliveryPhone;
            $order['delivery_address'] = $deliveryAddress;

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
        
        // Trong DB, cột TrangThai đang lưu tiếng Việt (Chờ duyệt, Đang chuẩn bị, Đang giao, Hoàn tất, Hủy)
        // Không cho phép hủy nếu đơn hàng đã ở trạng thái Đang giao hoặc Hoàn tất
        $nonCancellableStatuses = ['Đang giao', 'Hoàn tất'];
        if (in_array($order['status'], $nonCancellableStatuses, true)) {
            return $this->json(['success' => false, 'message' => 'Đơn hàng đang giao hoặc đã hoàn thành không thể hủy']);
        }

        // Cho phép hủy khi đơn hàng ở trạng thái Chờ duyệt / Đã xác nhận / Đang chuẩn bị
        $cancellableStatuses = ['Chờ duyệt', 'Đã xác nhận', 'Đang chuẩn bị'];
        if (!in_array($order['status'], $cancellableStatuses, true)) {
            return $this->json(['success' => false, 'message' => 'Không thể hủy đơn hàng này']);
        }

        // Cập nhật trực tiếp cột TrangThai sang 'Hủy'
        $this->orderModel->update($orderId, ['TrangThai' => 'Hủy']);
        
        return $this->json(['success' => true, 'message' => 'Hủy đơn hàng thành công']);
    }

    private function getCartItems()
    {
        $cart = $_SESSION['cart'] ?? [];
        $cartItems = [];
        
        foreach ($cart as $key => $item) {
            if (is_array($item)) {
                $productId = (int)($item['product_id'] ?? 0);
                $quantity  = (int)($item['quantity'] ?? 1);
                $color     = $item['color'] ?? '';
                $size      = $item['size'] ?? '';
            } else {
                $productId = (int)$key;
                $quantity  = (int)$item;
                $color     = '';
                $size      = '';
            }

            if (!$productId || $quantity <= 0) {
                continue;
            }

            $product = $this->productModel->findById($productId);
            if ($product) {
                $price = $product['price'];
                $itemTotal = $price * $quantity;
                
                $cartItems[] = [
                    'product'  => $product,
                    'quantity' => $quantity,
                    'price'    => $price,
                    'total'    => $itemTotal,
                    'color'    => $color,
                    'size'     => $size,
                ];
            }
        }
        
        return $cartItems;
    }

    private function calculateTotal()
    {
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        
        foreach ($cart as $key => $item) {
            if (is_array($item)) {
                $productId = (int)($item['product_id'] ?? 0);
                $quantity  = (int)($item['quantity'] ?? 1);
            } else {
                $productId = (int)$key;
                $quantity  = (int)$item;
            }

            if (!$productId || $quantity <= 0) {
                continue;
            }

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
