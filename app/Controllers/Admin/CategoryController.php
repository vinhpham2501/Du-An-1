<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    private $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->categoryModel = new Category();
    }

    public function index()
    {
        $categories = $this->categoryModel->getAll();
        
        return $this->render('admin/categories/index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            // Checkbox: nếu không có trong POST thì là 0 (không tích), nếu có thì là 1 (đã tích)
            $isAvailable = isset($_POST['is_available']) ? 1 : 0;
            
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
            // Checkbox: nếu không có trong POST thì là 0 (không tích), nếu có thì là 1 (đã tích)
            $isAvailable = isset($_POST['is_available']) ? 1 : 0;
            
            if (empty($name)) {
                return $this->render('admin/categories/edit', [
                    'error' => 'Vui lòng nhập tên danh mục',
                    'category' => $category
                ]);
            }

            $updateData = [
                'name' => $name,
                'description' => $description,
                'is_available' => $isAvailable
            ];
            
            $result = $this->categoryModel->update($id, $updateData);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật danh mục thành công!';
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
        
        // Check if category has products
        $products = $this->categoryModel->getProductsCount($categoryId);
        if ($products > 0) {
            return $this->json([
                'success' => false, 
                'message' => "Không thể xóa danh mục này vì có {$products} sản phẩm liên quan"
            ]);
        }
        
        $result = $this->categoryModel->delete($categoryId);
        
        if ($result) {
            return $this->json(['success' => true, 'message' => 'Xóa danh mục thành công']);
        } else {
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa danh mục']);
        }
    }
}
