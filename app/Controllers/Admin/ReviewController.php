<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    private $reviewModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();

        $this->reviewModel = new Review();
    }

    public function index()
    {
        $filters = [
            'limit' => 50,
        ];

        if (isset($_GET['status']) && $_GET['status'] !== '') {
            $filters['status'] = (int)$_GET['status'];
        }

        if (!empty($_GET['product_id'])) {
            $filters['product_id'] = (int)$_GET['product_id'];
        }

        if (!empty($_GET['q'])) {
            $filters['q'] = trim($_GET['q']);
        }

        $reviews = $this->reviewModel->getAllForAdmin($filters);

        return $this->render('admin/reviews/index', [
            'reviews' => $reviews,
            'filters' => $filters,
        ]);
    }

    public function updateStatus()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return $this->json(['success' => false, 'message' => 'Invalid request method']);
            }

            $reviewId = (int)($_POST['review_id'] ?? 0);
            $status = $_POST['status'] ?? null;

            if (!$reviewId || !in_array((string)$status, ['0', '1'], true)) {
                return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            }

            $ok = $this->reviewModel->setStatus($reviewId, (int)$status);
            return $this->json([
                'success' => (bool)$ok,
                'message' => $ok ? 'Cập nhật trạng thái thành công' : 'Có lỗi xảy ra'
            ]);
        } catch (\Throwable $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return $this->json(['success' => false, 'message' => 'Invalid request method']);
            }

            $reviewId = (int)($_POST['review_id'] ?? 0);
            if (!$reviewId) {
                return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            }

            $ok = $this->reviewModel->deleteById($reviewId);
            return $this->json([
                'success' => (bool)$ok,
                'message' => $ok ? 'Xóa đánh giá thành công' : 'Có lỗi xảy ra'
            ]);
        } catch (\Throwable $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function reply()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return $this->json(['success' => false, 'message' => 'Invalid request method']);
            }

            $reviewId = (int)($_POST['review_id'] ?? 0);
            $content = $_POST['reply'] ?? '';

            if (!$reviewId || trim((string)$content) === '') {
                return $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            }

            $adminId = (int)($_SESSION['user_id'] ?? 0);
            $ok = $this->reviewModel->upsertAdminReply($reviewId, $adminId, $content);

            return $this->json([
                'success' => (bool)$ok,
                'message' => $ok ? 'Đã trả lời đánh giá' : 'Có lỗi xảy ra'
            ]);
        } catch (\Throwable $e) {
            // Thường gặp nhất: chưa tạo bảng BINH_LUAN_DANH_GIA_TRA_LOI
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
