<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\ReviewReply;

class ReviewController extends Controller
{
    private $reviewModel;
    private $productModel;
    private $reviewReplyModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->reviewModel = new Review();
        $this->productModel = new Product();
        $this->reviewReplyModel = new ReviewReply();
    }

    public function index()
    {
        $filters = [
            'limit' => 20,
            'offset' => (($_GET['page'] ?? 1) - 1) * 20
        ];
        
        if (!empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        
        if (!empty($_GET['rating'])) {
            $filters['rating'] = $_GET['rating'];
        }
        
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        $reviews = $this->reviewModel->getAll($filters);
        $totalReviews = $this->reviewModel->count($filters);
        $ratingCounts = $this->reviewModel->getRatingCounts();
        $statusCounts = $this->reviewModel->getStatusCounts();
        
        return $this->render('admin/reviews/index', [
            'reviews' => $reviews,
            'totalReviews' => $totalReviews,
            'ratingCounts' => $ratingCounts,
            'statusCounts' => $statusCounts,
            'filters' => $filters,
            'currentPage' => $_GET['page'] ?? 1,
            'totalPages' => ceil($totalReviews / 20)
        ]);
    }

    public function show($id)
    {
        try {
            $review = $this->reviewModel->findById($id);
            
            if (!$review) {
                http_response_code(404);
                return $this->render('errors/404');
            }
            
            // Lấy phản hồi nếu có
            $reply = $this->reviewReplyModel->findByReviewId($id);
            
            return $this->render('admin/reviews/show', [
                'review' => $review,
                'reply' => $reply
            ]);
        } catch (\Exception $e) {
            error_log("ReviewController show error: " . $e->getMessage());
            http_response_code(500);
            return $this->render('errors/500', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $reviewId = $_POST['review_id'] ?? 0;
        $status = $_POST['status'] ?? '';
        
        if (!$reviewId || !in_array($status, ['0', '1'])) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        $review = $this->reviewModel->findById($reviewId);
        if (!$review) {
            return $this->json(['success' => false, 'message' => 'Đánh giá không tồn tại']);
        }
        
        $result = $this->reviewModel->updateStatus($reviewId, $status);
        
        if ($result) {
            return $this->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } else {
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $reviewId = $_POST['review_id'] ?? 0;
        
        if (!$reviewId) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        $review = $this->reviewModel->findById($reviewId);
        if (!$review) {
            return $this->json(['success' => false, 'message' => 'Đánh giá không tồn tại']);
        }
        
        $result = $this->reviewModel->delete($reviewId);
        
        if ($result) {
            return $this->json(['success' => true, 'message' => 'Xóa đánh giá thành công']);
        } else {
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }

    public function reply()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $reviewId = $_POST['review_id'] ?? 0;
        $reply = trim($_POST['reply'] ?? '');
        
        if (!$reviewId || !$reply) {
            return $this->json(['success' => false, 'message' => 'Vui lòng điền nội dung phản hồi']);
        }
        
        $review = $this->reviewModel->findById($reviewId);
        if (!$review) {
            return $this->json(['success' => false, 'message' => 'Đánh giá không tồn tại']);
        }
        
        // Lấy tên admin từ session
        $adminName = $_SESSION['user_name'] ?? $_SESSION['full_name'] ?? 'Admin';
        
        $result = $this->reviewReplyModel->update($reviewId, $reply, $adminName);
        
        if ($result) {
            return $this->json(['success' => true, 'message' => 'Phản hồi đã được lưu thành công']);
        } else {
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra khi lưu phản hồi']);
        }
    }

    public function deleteReply()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $reviewId = $_POST['review_id'] ?? 0;
        
        if (!$reviewId) {
            return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        
        $result = $this->reviewReplyModel->delete($reviewId);
        
        if ($result) {
            return $this->json(['success' => true, 'message' => 'Xóa phản hồi thành công']);
        } else {
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }
}

