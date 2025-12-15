<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->userModel = new User();
    }

    public function index()
    {
        $currentPage = (int)($_GET['page'] ?? 1);
        $limit = 20;
        
        $filters = [
            'limit' => $limit,
            'offset' => ($currentPage - 1) * $limit
        ];
        
        if (!empty($_GET['role'])) {
            $filters['role'] = $_GET['role'];
        }
        
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        $users = $this->userModel->getAll($filters);
        $totalUsers = $this->userModel->count($filters);
        $totalPages = ceil($totalUsers / $limit);
        
        return $this->render('admin/users/index', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'filters' => $filters
        ]);
    }

    public function show($id)
    {
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            http_response_code(404);
            return $this->render('errors/404');
        }
        
        return $this->render('admin/users/show', [
            'user' => $user
        ]);
    }

    public function updateRole($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $role = $_POST['role'] ?? '';
        $validRoles = ['user', 'admin'];
        
        if (!in_array($role, $validRoles)) {
            return $this->json(['success' => false, 'message' => 'Vai trò không hợp lệ']);
        }
        
        $user = $this->userModel->findById($id);
        if (!$user) {
            return $this->json(['success' => false, 'message' => 'Người dùng không tồn tại']);
        }
        
        $this->userModel->update($id, ['role' => $role]);
        
        return $this->json(['success' => true, 'message' => 'Cập nhật vai trò thành công']);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        try {
            $user = $this->userModel->findById($id);
            if (!$user) {
                return $this->json(['success' => false, 'message' => 'Người dùng không tồn tại']);
            }
            
            // Không cho phép khóa tài khoản admin hiện tại
            if ($user['id'] == $_SESSION['user_id']) {
                return $this->json([
                    'success' => false, 
                    'message' => 'Không thể khóa tài khoản của chính mình'
                ]);
            }
            
            // Kiểm tra xem user có đơn hàng đang xử lý không trước khi khóa
            $activeOrders = $this->userModel->getActiveOrders($id);
            if ($activeOrders > 0) {
                return $this->json([
                    'success' => false, 
                    'message' => 'Không thể khóa tài khoản có đơn hàng đang xử lý. Vui lòng hoàn thành hoặc hủy các đơn hàng trước.'
                ]);
            }
            
            // Khóa tài khoản (soft delete: cập nhật TrangThai = 0)
            $result = $this->userModel->delete($id);
            
            if ($result) {
                return $this->json(['success' => true, 'message' => 'Khóa tài khoản thành công']);
            } else {
                return $this->json(['success' => false, 'message' => 'Không thể khóa tài khoản']);
            }
            
        } catch (\Exception $e) {
            error_log("Delete user error: " . $e->getMessage());
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra khi khóa tài khoản']);
        }
    }

    public function toggleStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }

        $status = isset($_POST['is_active']) ? (int)$_POST['is_active'] : null;
        if ($status !== 0 && $status !== 1) {
            return $this->json(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
        }

        try {
            $user = $this->userModel->findById($id);
            if (!$user) {
                return $this->json(['success' => false, 'message' => 'Người dùng không tồn tại']);
            }

            // Không cho phép tự khóa tài khoản của chính mình
            if ($user['id'] == $_SESSION['user_id'] && $status === 0) {
                return $this->json([
                    'success' => false,
                    'message' => 'Không thể khóa tài khoản của chính mình'
                ]);
            }

            // Khi khóa tài khoản, kiểm tra đơn hàng đang xử lý
            if ($status === 0) {
                $activeOrders = $this->userModel->getActiveOrders($id);
                if ($activeOrders > 0) {
                    return $this->json([
                        'success' => false,
                        'message' => 'Không thể khóa tài khoản có đơn hàng đang xử lý. Vui lòng hoàn thành hoặc hủy các đơn hàng trước.'
                    ]);
                }
            }

            $result = $this->userModel->update($id, ['status' => $status]);

            if ($result) {
                $message = $status === 1 ? 'Mở khóa tài khoản thành công' : 'Khóa tài khoản thành công';
                return $this->json(['success' => true, 'message' => $message]);
            }

            return $this->json(['success' => false, 'message' => 'Không thể cập nhật trạng thái tài khoản']);

        } catch (\Exception $e) {
            error_log("Toggle user status error: " . $e->getMessage());
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật trạng thái tài khoản']);
        }
    }
}