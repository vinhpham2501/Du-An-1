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
        
        foreach ($cart as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product) {
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
        
        // Add or update quantity
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        
        $cartCount = array_sum($_SESSION['cart']);
        
        return $this->json([
            'success' => true, 
            'message' => 'Đã thêm vào giỏ hàng',
            'cartCount' => $cartCount
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $productId = $_POST['product_id'] ?? 0;
        $quantity = (int)($_POST['quantity'] ?? 0);
        
        if (!$productId) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        if ($quantity <= 0) {
            // Remove item
            unset($_SESSION['cart'][$productId]);
        } else {
            // Update quantity
            $_SESSION['cart'][$productId] = $quantity;
        }
        
        $cartCount = array_sum($_SESSION['cart']);
        
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
        
        $productId = $_POST['product_id'] ?? 0;
        
        if (!$productId) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        unset($_SESSION['cart'][$productId]);
        
        $cartCount = array_sum($_SESSION['cart']);
        
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
        $cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
        
        return $this->json(['cartCount' => $cartCount]);
    }
}
