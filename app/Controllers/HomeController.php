<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\Contact;

class HomeController extends Controller
{
    private $productModel;
    private $categoryModel;
    private $reviewModel;
    private $contactModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->reviewModel = new Review();
        $this->contactModel = new Contact();
    }

    public function products()
    {
        try {
            $currentPage = (int)($_GET['page'] ?? 1);
            $currentPage = max(1, $currentPage);
            $itemsPerPage = 12;
            $offset = ($currentPage - 1) * $itemsPerPage;

            $filters = [
                'limit' => $itemsPerPage,
                'offset' => $offset,
                'sort' => $_GET['sort'] ?? 'newest'
            ];

            if (!empty($_GET['category_id'])) {
                $filters['category_id'] = $_GET['category_id'];
            }

            if (!empty($_GET['search'])) {
                $filters['search'] = $_GET['search'];
            }

            if (isset($_GET['price_max']) && (int)$_GET['price_max'] > 0) {
                $filters['price_max'] = (int)$_GET['price_max'];
            }

            $products = $this->productModel->getAll($filters);
            $totalProducts = $this->productModel->count($filters);
            $categories = $this->categoryModel->getAll();

            $totalPages = (int)ceil($totalProducts / $itemsPerPage);

            return $this->render('home/products', [
                'products' => $products,
                'categories' => $categories,
                'filters' => $filters,
                'pagination' => [
                    'currentPage' => $currentPage,
                    'totalPages' => $totalPages,
                    'totalProducts' => $totalProducts,
                    'itemsPerPage' => $itemsPerPage
                ]
            ]);
        } catch (\Exception $e) {
            error_log('Error in HomeController@products: ' . $e->getMessage());
            return $this->render('errors/500', [
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau'
            ]);
        }
    }

    public function index()
    {
        try {
            // Lấy trang hiện tại từ URL, mặc định là trang 1
            $currentPage = (int)($_GET['page'] ?? 1);
            $currentPage = max(1, $currentPage); // Đảm bảo trang >= 1
            
            // Số sản phẩm mỗi trang
            $itemsPerPage = 12;
            $offset = ($currentPage - 1) * $itemsPerPage;
            
            $filters = [
                'limit' => $itemsPerPage,
                'offset' => $offset,
                'sort' => $_GET['sort'] ?? 'newest'
            ];
            
            if (!empty($_GET['category_id'])) {
                $filters['category_id'] = $_GET['category_id'];
            }
            
            if (!empty($_GET['search'])) {
                $filters['search'] = $_GET['search'];
            }
            
            // Lấy sản phẩm và tổng số sản phẩm
            $products = $this->productModel->getAll($filters);
            $totalProducts = $this->productModel->count($filters);
            
            // Tính toán thông tin phân trang
            $totalPages = ceil($totalProducts / $itemsPerPage);
            
            // Lấy dữ liệu cho các section mới
            $categories = $this->categoryModel->getAll();
            $featuredProducts = $this->productModel->getFeaturedProducts(4); // Hero slider
            $topSellingProducts = $this->productModel->getTopSelling(8); // Sản phẩm bán chạy
            $saleProducts = $this->productModel->getSaleProducts(8); // Sản phẩm đang sale
            $newProducts = $this->productModel->getNewProducts(8); // Sản phẩm mới
            
            return $this->render('home/index', [
                'products' => $products,
                'categories' => $categories,
                'featuredProducts' => $featuredProducts,
                'topSellingProducts' => $topSellingProducts,
                'saleProducts' => $saleProducts,
                'newProducts' => $newProducts,
                'filters' => $filters,
                'pagination' => [
                    'currentPage' => $currentPage,
                    'totalPages' => $totalPages,
                    'totalProducts' => $totalProducts,
                    'itemsPerPage' => $itemsPerPage,
                    'hasNext' => $currentPage < $totalPages,
                    'hasPrev' => $currentPage > 1
                ]
            ]);
            
        } catch (\Exception $e) {
            // Log lỗi
            error_log("Error in HomeController@index: " . $e->getMessage());
            // Render trang lỗi
            return $this->render('errors/500', [
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

        if (!$this->reviewModel->userHasPurchasedProduct($_SESSION['user_id'], $productId)) {
            return $this->json(['success' => false, 'message' => 'Bạn chỉ có thể đánh giá sản phẩm đã mua']);
        }

        // Chặn đánh giá trùng, kể cả khi đánh giá cũ đang bị ẩn (TrangThai = 0)
        if ($this->reviewModel->userHasReviewedProduct($_SESSION['user_id'], $productId)) {
            return $this->json(['success' => false, 'message' => 'Bạn đã đánh giá sản phẩm này rồi']);
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
            
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->render('home/contact', ['error' => 'Email không hợp lệ']);
            }
            
            // Save to database
            try {
                $contactId = $this->contactModel->create([
                    'name' => $name,
                    'email' => $email,
                    'message' => $message,
                    'status' => 'new'
                ]);
                
                if ($contactId) {
                    $_SESSION['success'] = 'Gửi tin nhắn thành công! Chúng tôi sẽ phản hồi sớm nhất.';
                } else {
                    return $this->render('home/contact', ['error' => 'Có lỗi xảy ra, vui lòng thử lại']);
                }
            } catch (\Exception $e) {
                error_log("Contact form error: " . $e->getMessage());
                return $this->render('home/contact', ['error' => 'Có lỗi xảy ra, vui lòng thử lại']);
            }
            
            $this->redirect('/contact');
        }
        
        return $this->render('home/contact');
    }

    public function filterProducts()
    {
        try {
            // Lấy parameters từ GET request
            $currentPage = (int)($_GET['page'] ?? 1);
            $currentPage = max(1, $currentPage);
            $itemsPerPage = 12;
            $offset = ($currentPage - 1) * $itemsPerPage;
            
            $filters = [
                'limit' => $itemsPerPage,
                'offset' => $offset,
                'sort' => $_GET['sort'] ?? 'newest'
            ];
            
            if (!empty($_GET['category_id'])) {
                $filters['category_id'] = $_GET['category_id'];
            }
            
            if (!empty($_GET['search'])) {
                $filters['search'] = $_GET['search'];
            }
            
            // Lấy sản phẩm và tổng số
            $products = $this->productModel->getAll($filters);
            $totalProducts = $this->productModel->count($filters);
            $totalPages = ceil($totalProducts / $itemsPerPage);
            
            // Tạo HTML cho sản phẩm
            $productsHtml = '';
            if (!empty($products)) {
                foreach ($products as $product) {
                    $productsHtml .= $this->renderProductCard($product);
                }
            } else {
                $productsHtml = '<div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-5x text-muted mb-3"></i>
                    <h3 class="text-muted">Không tìm thấy sản phẩm nào</h3>
                    <p class="text-muted">Vui lòng thử tìm kiếm với từ khóa khác</p>
                </div>';
            }
            
            // Tạo HTML cho pagination
            $paginationHtml = $this->renderPagination($currentPage, $totalPages, $totalProducts, $_GET);
            
            // Trả về JSON response
            return $this->json([
                'success' => true,
                'products' => $productsHtml,
                'pagination' => $paginationHtml,
                'totalProducts' => $totalProducts
            ]);
            
        } catch (\Exception $e) {
            error_log("Error in HomeController@filterProducts: " . $e->getMessage());
            return $this->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau'
            ]);
        }
    }
    
    private function renderProductCard($product)
    {
        $salePrice = !empty($product['sale_price']) && $product['sale_price'] < $product['price'];
        
        return '<div class="col-lg-3 col-md-6 mb-4">
            <div class="product-card h-100 shadow-sm">
                <div class="position-relative">
                    <a href="/product/' . $product['id'] . '" class="text-decoration-none">
                        ' . (!empty($product['image_url']) ? 
                            '<img src="' . htmlspecialchars($product['image_url']) . '" 
                                 class="card-img-top" alt="' . htmlspecialchars($product['name']) . '" 
                                 style="height: 200px; object-fit: cover;">' :
                            '<div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-utensils fa-3x text-muted"></i>
                            </div>'
                        ) . '
                    </a>
                    ' . ($salePrice ? 
                        '<div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-warning">
                                <i class="fas fa-percent me-1"></i>Sale
                            </span>
                        </div>' : ''
                    ) . '
                </div>
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="/product/' . $product['id'] . '" class="text-decoration-none text-dark">
                            ' . htmlspecialchars($product['name']) . '
                        </a>
                    </h5>
                    <p class="card-text text-muted small">' . htmlspecialchars(substr($product['description'], 0, 80)) . '...</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="price">
                            ' . ($salePrice ? 
                                '<span class="text-danger fw-bold">' . number_format($product['sale_price']) . 'đ</span>
                                <small class="text-muted text-decoration-line-through">' . number_format($product['price']) . 'đ</small>' :
                                '<span class="text-primary fw-bold">' . number_format($product['price']) . 'đ</span>'
                            ) . '
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="addToCart(' . $product['id'] . ')">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>';
    }
    
    private function renderPagination($currentPage, $totalPages, $totalProducts, $params)
    {
        if ($totalPages <= 1) return '';
        
        $html = '<nav aria-label="Phân trang sản phẩm">
            <ul class="pagination justify-content-center">';
        
        // Previous button
        if ($currentPage > 1) {
            $prevParams = array_merge($params, ['page' => $currentPage - 1]);
            $html .= '<li class="page-item">
                <a class="page-link" href="#" onclick="filterProducts(' . ($currentPage - 1) . '); return false;">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>';
        }
        
        // Page numbers
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $active = $i == $currentPage ? 'active' : '';
            $html .= '<li class="page-item ' . $active . '">
                <a class="page-link" href="#" onclick="filterProducts(' . $i . '); return false;">' . $i . '</a>
            </li>';
        }
        
        // Next button
        if ($currentPage < $totalPages) {
            $html .= '<li class="page-item">
                <a class="page-link" href="#" onclick="filterProducts(' . ($currentPage + 1) . '); return false;">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>';
        }
        
        $html .= '</ul></nav>';
        return $html;
    }
}
