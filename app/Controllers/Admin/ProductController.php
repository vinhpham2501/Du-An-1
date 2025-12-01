<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductColor;
use App\Models\ProductSize;

class ProductController extends Controller
{
    private $productModel;
    private $categoryModel;
    private $productImageModel;
    private $productColorModel;
    private $productSizeModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->productImageModel = new ProductImage();
        $this->productColorModel = new ProductColor();
        $this->productSizeModel = new ProductSize();
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
            $name       = $_POST['name'] ?? '';
            $intro      = $_POST['intro'] ?? '';
            $detail     = $_POST['detail'] ?? '';
            $price      = $_POST['price'] ?? 0;
            $salePrice  = $_POST['sale_price'] ?? null;
            $categoryId = $_POST['category_id'] ?? 0;
            $isAvailable= (int)($_POST['is_available'] ?? 1);

            $colorsRaw  = $_POST['colors'] ?? '';
            $sizesRaw   = $_POST['sizes'] ?? '';
            $galleryRaw = $_POST['gallery_image_urls'] ?? '';

            if (empty($name) || empty($price) || empty($categoryId)) {
                return $this->render('admin/products/create', [
                    'error'      => 'Vui lòng nhập đầy đủ thông tin bắt buộc',
                    'categories' => $this->categoryModel->getAll()
                ]);
            }

            // Xử lý ảnh chính (URL hoặc file upload)
            $mainImageUrl = $_POST['image_url'] ?? '';
            $uploaded = null;
            if (!empty($_FILES['image_file']['tmp_name']) && ($_FILES['image_file']['error'] ?? UPLOAD_ERR_OK) === UPLOAD_ERR_OK) {
                $uploaded = $this->uploadImage($_FILES['image_file']);
            }

            $images = [];

            // Ưu tiên ảnh upload, sau đó đến URL
            if ($uploaded) {
                $images[] = $uploaded;
            } elseif (!empty($mainImageUrl)) {
                $images[] = $mainImageUrl;
            }

            // Các URL ảnh gallery (mỗi dòng một URL)
            if (!empty($galleryRaw)) {
                $extra = preg_split('/\r\n|\r|\n/', $galleryRaw);
                foreach ($extra as $url) {
                    $url = trim($url);
                    if ($url !== '') {
                        $images[] = $url;
                    }
                }
            }

            $productId = $this->productModel->create([
                'name'         => $name,
                'intro'        => $intro,
                'detail'       => $detail,
                'price'        => $price,
                'sale_price'   => $salePrice ?: null,
                'category_id'  => $categoryId,
                'is_available' => $isAvailable
            ]);
            
            if ($productId) {
                // Màu sắc: nhập dạng "Đỏ, Xanh, Vàng"
                $colors = [];
                if (!empty($colorsRaw)) {
                    $colors = array_filter(array_map('trim', explode(',', $colorsRaw)));
                }
                if (!empty($colors)) {
                    $this->productColorModel->addColors($productId, $colors);
                }

                // Size: nhập dạng "S, M, L, XL"
                $sizes = [];
                if (!empty($sizesRaw)) {
                    $sizes = array_filter(array_map('trim', explode(',', $sizesRaw)));
                }
                if (!empty($sizes)) {
                    $this->productSizeModel->addSizes($productId, $sizes);
                }

                // Hình ảnh
                if (!empty($images)) {
                    $this->productImageModel->addImages($productId, $images);
                }

                $_SESSION['success'] = 'Thêm sản phẩm thành công!';
                $this->redirect('/admin/products');
            } else {
                return $this->render('admin/products/create', [
                    'error'      => 'Có lỗi xảy ra, vui lòng thử lại',
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

        // Lấy danh sách màu & ảnh cho view
        $images = $this->productImageModel->getByProduct($id);
        $colors = $this->productColorModel->getByProduct($id);
        $sizes  = $this->productSizeModel->getByProduct($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name       = $_POST['name'] ?? '';
            $intro      = $_POST['intro'] ?? '';
            $detail     = $_POST['detail'] ?? '';
            $price      = $_POST['price'] ?? 0;
            $salePrice  = $_POST['sale_price'] ?? null;
            $categoryId = $_POST['category_id'] ?? 0;
            $isAvailable= (int)($_POST['is_available'] ?? 1);

            $colorsRaw  = $_POST['colors'] ?? '';
            $sizesRaw   = $_POST['sizes'] ?? '';
            $galleryRaw = $_POST['gallery_image_urls'] ?? '';

            if (empty($name) || empty($price) || empty($categoryId)) {
                return $this->render('admin/products/edit', [
                    'error'      => 'Vui lòng nhập đầy đủ thông tin bắt buộc',
                    'product'    => $product,
                    'categories' => $this->categoryModel->getAll(),
                    'images'     => $images,
                    'colors'     => $colors,
                    'sizes'      => $sizes,
                ]);
            }

            // Ảnh chính mới (nếu có)
            $mainImageUrl = $_POST['image_url'] ?? '';
            $uploaded = null;
            if (!empty($_FILES['image_file']['tmp_name']) && ($_FILES['image_file']['error'] ?? UPLOAD_ERR_OK) === UPLOAD_ERR_OK) {
                $uploaded = $this->uploadImage($_FILES['image_file']);
            }

            $newImages = [];

            if ($uploaded) {
                $newImages[] = $uploaded;
            } elseif (!empty($mainImageUrl)) {
                $newImages[] = $mainImageUrl;
            }

            if (!empty($galleryRaw)) {
                $extra = preg_split('/\r\n|\r|\n/', $galleryRaw);
                foreach ($extra as $url) {
                    $url = trim($url);
                    if ($url !== '') {
                        $newImages[] = $url;
                    }
                }
            }

            $updateData = [
                'name'         => $name,
                'intro'        => $intro,
                'detail'       => $detail,
                'price'        => $price,
                'sale_price'   => $salePrice ?: null,
                'category_id'  => $categoryId,
                'is_available' => $isAvailable
            ];
            
            $result = $this->productModel->update($id, $updateData);
            
            if ($result) {
                // Cập nhật màu: xoá hết rồi thêm lại theo form => cho phép thêm/xoá
                $this->productColorModel->deleteByProduct($id);
                $colorsNew = [];
                if (!empty($colorsRaw)) {
                    $colorsNew = array_filter(array_map('trim', explode(',', $colorsRaw)));
                }
                if (!empty($colorsNew)) {
                    $this->productColorModel->addColors($id, $colorsNew);
                }

                // Cập nhật size: xoá hết rồi thêm lại theo form => cho phép thêm/xoá
                $this->productSizeModel->deleteByProduct($id);
                $sizesNew = [];
                if (!empty($sizesRaw)) {
                    $sizesNew = array_filter(array_map('trim', explode(',', $sizesRaw)));
                }
                if (!empty($sizesNew)) {
                    $this->productSizeModel->addSizes($id, $sizesNew);
                }

                // Cập nhật hình ảnh: xoá toàn bộ và thêm lại theo form
                $this->productImageModel->deleteByProduct($id);
                if (!empty($newImages)) {
                    $this->productImageModel->addImages($id, $newImages);
                }

                $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
                $this->redirect('/admin/products');
            } else {
                return $this->render('admin/products/edit', [
                    'error'      => 'Có lỗi xảy ra khi cập nhật sản phẩm',
                    'product'    => $product,
                    'categories' => $this->categoryModel->getAll(),
                    'images'     => $images,
                    'colors'     => $colors,
                    'sizes'      => $sizes,
                ]);
            }
        }
        
        return $this->render('admin/products/edit', [
            'product'    => $product,
            'categories' => $this->categoryModel->getAll(),
            'images'     => $images,
            'colors'     => $colors,
            'sizes'      => $sizes,
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
