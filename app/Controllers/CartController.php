<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

class CartController extends Controller
{
    private $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
    }

    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $key => $item) {
            // Parse key: productId_color_size
            $parts = explode('_', $key);
            $productId = $parts[0] ?? 0;
            $color = $parts[1] ?? '';
            $size = $parts[2] ?? '';
            
            $product = $this->productModel->findById($productId);
            if ($product) {
                $price = $product['price'];
                $quantity = $item['quantity'] ?? 1;
                $itemTotal = $price * $quantity;
                $total += $itemTotal;
                
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'color' => $color,
                    'size' => $size,
                    'item_total' => $itemTotal,
                    'key' => $key
                ];
            }
        }
        
        return $this->render('cart/index', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        // Đọc JSON data từ request body (an toàn với form-urlencoded)
        $rawBody = file_get_contents('php://input');
        $input = json_decode($rawBody, true);
        if (!is_array($input)) {
            $input = [];
        }
        
        $productId = $input['product_id'] ?? ($_POST['product_id'] ?? 0);
        $quantity = (int)($input['quantity'] ?? ($_POST['quantity'] ?? 1));
        $color = $input['color'] ?? ($_POST['color'] ?? '');
        $size = $input['size'] ?? ($_POST['size'] ?? '');
        
        // Debug: Log ra error_log để kiểm tra
        error_log("Cart Add - ProductID: $productId, Quantity: $quantity, Color: $color, Size: $size");
        
        if (!$productId || $quantity < 1) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        $product = $this->productModel->findById($productId);
        if (!$product || !$product['is_available']) {
            return $this->json(['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc không khả dụng']);
        }
        
        // Initialize cart if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Create unique key for product variant
        $cartKey = $productId . '_' . $color . '_' . $size;
        
        // Add or update quantity
        if (isset($_SESSION['cart'][$cartKey])) {
            $_SESSION['cart'][$cartKey]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$cartKey] = [
                'product_id' => $productId,
                'color' => $color,
                'size' => $size,
                'quantity' => $quantity
            ];
        }
        
        // Debug: Log session data
        error_log("Cart Key: $cartKey, Session Data: " . print_r($_SESSION['cart'][$cartKey], true));
        
        // Calculate total cart count
        $cartCount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $cartCount += $item['quantity'] ?? 1;
        }
        
        return $this->json([
            'success' => true, 
            'message' => 'Đã thêm vào giỏ hàng',
            'cartCount' => $cartCount,
            'debug' => [
                'cartKey' => $cartKey,
                'color' => $color,
                'size' => $size
            ]
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $cartKey = $_POST['cart_key'] ?? $_POST['product_id'] ?? 0;
        $quantity = (int)($_POST['quantity'] ?? 0);
        
        if (!$cartKey) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        if ($quantity <= 0) {
            // Remove item
            unset($_SESSION['cart'][$cartKey]);
        } else {
            // Update quantity
            if (isset($_SESSION['cart'][$cartKey])) {
                $_SESSION['cart'][$cartKey]['quantity'] = $quantity;
            }
        }
        
        // Calculate total cart count
        $cartCount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $cartCount += $item['quantity'] ?? 1;
        }
        
        return $this->json([
            'success' => true, 
            'message' => 'Cập nhật giỏ hàng thành công',
            'cartCount' => $cartCount
        ]);
    }

    public function remove()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $cartKey = $_POST['cart_key'] ?? $_POST['product_id'] ?? 0;
        
        if (!$cartKey) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        unset($_SESSION['cart'][$cartKey]);
        
        // Calculate total cart count
        $cartCount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $cartCount += $item['quantity'] ?? 1;
        }
        
        return $this->json([
            'success' => true, 
            'message' => 'Đã xóa khỏi giỏ hàng',
            'cartCount' => $cartCount
        ]);
    }

    public function clear()
    {
        unset($_SESSION['cart']);
        
        return $this->json([
            'success' => true, 
            'message' => 'Đã xóa giỏ hàng',
            'cartCount' => 0
        ]);
    }

    public function getCount()
    {
        $cartCount = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $cartCount += $item['quantity'] ?? 1;
            }
        }
        
        return $this->json(['cartCount' => $cartCount]);
    }
}
