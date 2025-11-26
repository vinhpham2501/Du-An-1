<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    public function index()
    {
        $filters = [
            'limit' => 20,
            'offset' => ($_GET['page'] ?? 1) - 1
        ];
        
        if (!empty($_GET['category_id'])) {
            $filters['category_id'] = $_GET['category_id'];
        }
        
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        if (isset($_GET['status']) && $_GET['status'] !== '') {
            $filters['is_available'] = (int)$_GET['status'];
        }
        
        $products = $this->productModel->getAll($filters);
        $categories = $this->categoryModel->getAll();
        $totalProducts = $this->productModel->count($filters);
        
        return $this->render('admin/products/index', [
            'products' => $products,
            'categories' => $categories,
            'totalProducts' => $totalProducts,
            'filters' => $filters
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $salePrice = $_POST['sale_price'] ?? null;
            $categoryId = $_POST['category_id'] ?? 0;
            $imageUrl = $_POST['image_url'] ?? '';
            $isAvailable = (int)($_POST['is_available'] ?? 1);
            
            if (empty($name) || empty($price) || empty($categoryId)) {
                return $this->render('admin/products/create', [
                    'error' => 'Vui lòng nhập đầy đủ thông tin bắt buộc',
                    'categories' => $this->categoryModel->getAll()
                ]);
            }
            
            // Prefer uploaded file over URL if provided
            if (!empty($_FILES['image_file']['tmp_name']) && ($_FILES['image_file']['error'] ?? UPLOAD_ERR_OK) === UPLOAD_ERR_OK) {
                $uploaded = $this->uploadImage($_FILES['image_file']);
                if ($uploaded) {
                    $imageUrl = $uploaded; // store filename; ImageHelper will render /images/<filename>
                }
            }

            $productId = $this->productModel->create([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'sale_price' => $salePrice ?: null,
                'category_id' => $categoryId,
                'image_url' => $imageUrl,
                'is_available' => $isAvailable
            ]);
            
            if ($productId) {
                $_SESSION['success'] = 'Thêm sản phẩm thành công!';
                $this->redirect('/admin/products');
            } else {
                return $this->render('admin/products/create', [
                    'error' => 'Có lỗi xảy ra, vui lòng thử lại',
                    'categories' => $this->categoryModel->getAll()
                ]);
            }
        }
        
        return $this->render('admin/products/create', [
            'categories' => $this->categoryModel->getAll()
        ]);
    }

    public function edit($id)
    {
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            http_response_code(404);
            return $this->render('errors/404');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $salePrice = $_POST['sale_price'] ?? null;
            $categoryId = $_POST['category_id'] ?? 0;
            $imageUrl = $_POST['image_url'] ?? '';
            $isAvailable = (int)($_POST['is_available'] ?? 1);
            
            if (empty($name) || empty($price) || empty($categoryId)) {
                return $this->render('admin/products/edit', [
                    'error' => 'Vui lòng nhập đầy đủ thông tin bắt buộc',
                    'product' => $product,
                    'categories' => $this->categoryModel->getAll()
                ]);
            }
            
            // Prefer uploaded file over URL if provided
            if (!empty($_FILES['image_file']['tmp_name']) && ($_FILES['image_file']['error'] ?? UPLOAD_ERR_OK) === UPLOAD_ERR_OK) {
                $uploaded = $this->uploadImage($_FILES['image_file']);
                if ($uploaded) {
                    $imageUrl = $uploaded; // store filename
                }
            }

            $updateData = [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'sale_price' => $salePrice ?: null,
                'category_id' => $categoryId,
                'image_url' => $imageUrl,
                'is_available' => $isAvailable
            ];
            
            // Bỏ xử lý upload file: chỉ sử dụng URL ảnh từ form
            
            $result = $this->productModel->update($id, $updateData);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
                $this->redirect('/admin/products');
            } else {
                return $this->render('admin/products/edit', [
                    'error' => 'Có lỗi xảy ra khi cập nhật sản phẩm',
                    'product' => $product,
                    'categories' => $this->categoryModel->getAll()
                ]);
            }
        }
        
        return $this->render('admin/products/edit', [
            'product' => $product,
            'categories' => $this->categoryModel->getAll()
        ]);
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $productId = $_POST['product_id'] ?? 0;
        
        if (!$productId) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        $product = $this->productModel->findById($productId);
        if (!$product) {
            return $this->json(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        }
        
        try {
            if ($this->productModel->delete($productId)) {
                return $this->json(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
            }
        } catch (\Throwable $e) {
            // Có thể do ràng buộc FK (đã có trong đơn hàng). Fallback: ẩn sản phẩm
        }
        // Soft delete: đặt TrangThai = 0
        $this->productModel->update($productId, ['is_available' => 0]);
        return $this->json(['success' => true, 'message' => 'Sản phẩm đã được ẩn do đang được tham chiếu trong đơn hàng']);
    }

    private function uploadImage($file)
    {
        $uploadDir = PUBLIC_PATH . '/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
        
        if ($file['size'] > $maxSize) {
            return false;
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filename;
        }
        
        return false;
    }
}
