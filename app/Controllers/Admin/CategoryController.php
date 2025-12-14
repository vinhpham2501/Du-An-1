<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    private $categoryModel;
    private $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->categoryModel = new Category();
        $this->productModel = new Product();
    }

    public function index()
    {
        $categories = $this->categoryModel->getAll();
        
        // Đồng bộ trạng thái sản phẩm với danh mục khi trang được load
        // Chỉ chạy một lần nếu có danh mục ở trạng thái "ngừng bán"
        foreach ($categories as $category) {
            if ((int)($category['is_available'] ?? 1) == 2) {
                // Đồng bộ sản phẩm trong danh mục này (chỉ cập nhật những sản phẩm chưa ở trạng thái 2)
                $this->productModel->updateStatusByCategory($category['id'], 2);
            }
        }
        
        return $this->render('admin/categories/index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            // Select: 1 = Đang hoạt động, 2 = Ngừng bán
            $isAvailable = (int)($_POST['is_available'] ?? 1);
            
            if (empty($name)) {
                return $this->render('admin/categories/create', [
                    'error' => 'Vui lòng nhập tên danh mục'
                ]);
            }

            $categoryId = $this->categoryModel->create([
                'name' => $name,
                'description' => $description,
                'is_available' => $isAvailable
            ]);
            
            if ($categoryId) {
                $_SESSION['success'] = 'Thêm danh mục thành công!';
                $this->redirect('/admin/categories');
            } else {
                return $this->render('admin/categories/create', [
                    'error' => 'Có lỗi xảy ra, vui lòng thử lại'
                ]);
            }
        }
        
        return $this->render('admin/categories/create');
    }

    public function edit($id)
    {
        $category = $this->categoryModel->findById($id);
        
        if (!$category) {
            http_response_code(404);
            return $this->render('errors/404');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            // Select: 1 = Đang hoạt động, 2 = Ngừng bán
            $isAvailable = (int)($_POST['is_available'] ?? 1);
            
            if (empty($name)) {
                return $this->render('admin/categories/edit', [
                    'error' => 'Vui lòng nhập tên danh mục',
                    'category' => $category
                ]);
            }

            // Lưu trạng thái cũ để so sánh
            $oldStatus = (int)($category['is_available'] ?? 1);
            
            $updateData = [
                'name' => $name,
                'description' => $description,
                'is_available' => $isAvailable
            ];
            
            $result = $this->categoryModel->update($id, $updateData);
            
            if ($result) {
                $message = 'Cập nhật danh mục thành công!';
                
                // Nếu danh mục ở trạng thái "ngừng bán" (2), 
                // cập nhật tất cả sản phẩm trong danh mục đó thành "ngừng bán" (2)
                if ($isAvailable == 2) {
                    $updatedCount = $this->productModel->updateStatusByCategory($id, 2);
                    if ($updatedCount > 0) {
                        $message = "Cập nhật danh mục thành công! Đã cập nhật {$updatedCount} sản phẩm trong danh mục thành 'ngừng bán'.";
                    } else {
                        $message = 'Cập nhật danh mục thành công! Tất cả sản phẩm trong danh mục đã ở trạng thái "ngừng bán".';
                    }
                } 
                // Nếu danh mục chuyển từ "ngừng bán" (2) về "đang hoạt động" (1),
                // cập nhật tất cả sản phẩm trong danh mục đó thành "đang bán" (1)
                elseif ($isAvailable == 1 && $oldStatus == 2) {
                    $updatedCount = $this->productModel->updateStatusByCategory($id, 1);
                    if ($updatedCount > 0) {
                        $message = "Cập nhật danh mục thành công! Đã cập nhật {$updatedCount} sản phẩm trong danh mục thành 'đang bán'.";
                    } else {
                        $message = 'Cập nhật danh mục thành công! Tất cả sản phẩm trong danh mục đã ở trạng thái "đang bán".';
                    }
                }
                
                $_SESSION['success'] = $message;
                $this->redirect('/admin/categories');
            } else {
                return $this->render('admin/categories/edit', [
                    'error' => 'Có lỗi xảy ra khi cập nhật danh mục',
                    'category' => $category
                ]);
            }
        }
        
        return $this->render('admin/categories/edit', [
            'category' => $category
        ]);
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $categoryId = $_POST['category_id'] ?? 0;
        
        if (empty($categoryId)) {
            return $this->json(['success' => false, 'message' => 'Category ID is required']);
        }
        
        // Soft delete: đặt TrangThai = 0 để ẩn danh mục (đã xóa)
        // Không xóa thực sự để tránh mất dữ liệu và ràng buộc FK
        $result = $this->categoryModel->update($categoryId, ['is_available' => 0]);
        
        if ($result) {
            return $this->json(['success' => true, 'message' => 'Xóa danh mục thành công. Danh mục đã được ẩn khỏi frontend.']);
        } else {
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa danh mục']);
        }
    }
}
