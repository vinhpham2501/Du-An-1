<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderController extends Controller
{
    private $orderModel;
    private $orderItemModel;
    private $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
        $this->productModel = new Product();
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
        
        foreach ($cart as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product && $product['status'] === 'available') {
                $price = $product['sale_price'] ?: $product['price'];
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
        
        if (empty($cartItems)) {
            $this->redirect('/cart');
        }
        
        return $this->render('order/checkout', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function placeOrder()
    {
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
        
        if (empty($deliveryName) || empty($deliveryPhone) || empty($deliveryAddress)) {
            return $this->render('order/checkout', [
                'error' => 'Vui lòng nhập đầy đủ thông tin giao hàng',
                'cartItems' => $this->getCartItems(),
                'total' => $this->calculateTotal()
            ]);
        }
        
        // Calculate total and prepare order items
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product && $product['status'] === 'available') {
                $price = $product['sale_price'] ?: $product['price'];
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
        
        // Create order
        $orderId = $this->orderModel->create([
            'user_id' => $_SESSION['user_id'],
            'total_amount' => $total,
            'delivery_name' => $deliveryName,
            'delivery_phone' => $deliveryPhone,
            'delivery_address' => $deliveryAddress,
            'note' => $note,
            'status' => 'pending'
        ]);
        
        if ($orderId) {
            // Create order items
            $this->orderItemModel->createMultiple($orderId, $cartItems);
            
            // Clear cart
            unset($_SESSION['cart']);
            
            $_SESSION['success'] = 'Đặt hàng thành công! Mã đơn hàng: #' . $orderId;
            $this->redirect('/orders/' . $orderId);
        } else {
            return $this->render('order/checkout', [
                'error' => 'Có lỗi xảy ra, vui lòng thử lại',
                'cartItems' => $this->getCartItems(),
                'total' => $this->calculateTotal()
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
        
        if ($order['status'] !== 'pending') {
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
                $price = $product['sale_price'] ?: $product['price'];
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
                $price = $product['sale_price'] ?: $product['price'];
                $total += $price * $quantity;
            }
        }
        
        return $total;
    }
}
