<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;

class HomeController extends Controller
{
    private $productModel;
    private $categoryModel;
    private $reviewModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->reviewModel = new Review();
    }

    public function index()
    {
        try {
            $filters = [
                'limit' => 12,
                'sort' => $_GET['sort'] ?? 'newest'
            ];
            
            if (!empty($_GET['category_id'])) {
                $filters['category_id'] = $_GET['category_id'];
            }
            
            if (!empty($_GET['search'])) {
                $filters['search'] = $_GET['search'];
            }
            
            $products = $this->productModel->getAll($filters);
            $categories = $this->categoryModel->getAll();
            $topProducts = $this->productModel->getTopSelling(6);
            
            return $this->render('home/index', [
                'products' => $products,
                'categories' => $categories,
                'topProducts' => $topProducts,
                'filters' => $filters
            ]);
            
        } catch (\Exception $e) {
            // Log lỗi
            error_log("Error in HomeController@index: " . $e->getMessage());
            // Render trang lỗi
            return $this->render('error/500', [
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau'
            ]);
        }
    }

    public function product()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            http_response_code(404);
            return $this->render('errors/404');
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            http_response_code(404);
            return $this->render('errors/404');
        }
        
        $reviews = $this->reviewModel->findByProductId($id, 10);
        $relatedProducts = $this->productModel->getByCategory($product['category_id'], 4);
        
        return $this->render('home/product', [
            'product' => $product,
            'reviews' => $reviews,
            'relatedProducts' => $relatedProducts
        ]);
    }

    public function category()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            http_response_code(404);
            return $this->render('errors/404');
        }
        
        $category = $this->categoryModel->findById($id);
        
        if (!$category) {
            http_response_code(404);
            return $this->render('errors/404');
        }
        
        $filters = [
            'category_id' => $id,
            'limit' => 12,
            'sort' => $_GET['sort'] ?? 'newest'
        ];
        
        $products = $this->productModel->getAll($filters);
        $categories = $this->categoryModel->getAll();
        
        return $this->render('home/category', [
            'category' => $category,
            'products' => $products,
            'categories' => $categories,
            'filters' => $filters
        ]);
    }

    public function search()
    {
        $search = $_GET['q'] ?? '';
        
        if (empty($search)) {
            $this->redirect('/');
        }
        
        $filters = [
            'search' => $search,
            'limit' => 20,
            'sort' => $_GET['sort'] ?? 'newest'
        ];
        
        $products = $this->productModel->getAll($filters);
        $categories = $this->categoryModel->getAll();
        
        return $this->render('home/search', [
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'filters' => $filters
        ]);
    }

    public function addReview()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $productId = $_POST['product_id'] ?? 0;
        $rating = $_POST['rating'] ?? 0;
        $comment = $_POST['comment'] ?? '';
        
        if (!$productId || !$rating || $rating < 1 || $rating > 5) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        // Check if user already reviewed this product
        $existingReview = $this->reviewModel->findByProductId($productId);
        foreach ($existingReview as $review) {
            if ($review['user_id'] == $_SESSION['user_id']) {
                return $this->json(['success' => false, 'message' => 'Bạn đã đánh giá món ăn này rồi']);
            }
        }
        
        $reviewId = $this->reviewModel->create([
            'user_id' => $_SESSION['user_id'],
            'product_id' => $productId,
            'rating' => $rating,
            'comment' => $comment
        ]);
        
        if ($reviewId) {
            return $this->json(['success' => true, 'message' => 'Đánh giá thành công']);
        } else {
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }

    public function about()
    {
        return $this->render('home/about');
    }

    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';
            
            if (empty($name) || empty($email) || empty($message)) {
                return $this->render('home/contact', ['error' => 'Vui lòng nhập đầy đủ thông tin']);
            }
            
            // Here you would typically send an email or save to database
            $_SESSION['success'] = 'Gửi tin nhắn thành công! Chúng tôi sẽ phản hồi sớm nhất.';
            $this->redirect('/contact');
        }
        
        return $this->render('home/contact');
    }
}