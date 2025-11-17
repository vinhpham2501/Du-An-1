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
        $filters = [
            'limit' => 20,
            'offset' => ($_GET['page'] ?? 1) - 1
        ];
        
        if (!empty($_GET['role'])) {
            $filters['role'] = $_GET['role'];
        }
        
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        $users = $this->userModel->getAll($filters);
        $totalUsers = $this->userModel->count($filters);
        
        return $this->render('admin/users/index', [
            'users' => $users,
            'totalUsers' => $totalUsers,
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
            
            // Không cho phép xóa tài khoản admin hiện tại
            if ($user['id'] == $_SESSION['user_id']) {
                return $this->json([
                    'success' => false, 
                    'message' => 'Không thể xóa tài khoản của chính mình'
                ]);
            }
            
            // Kiểm tra xem user có đơn hàng đang xử lý không
            $activeOrders = $this->userModel->getActiveOrders($id);
            if ($activeOrders > 0) {
                return $this->json([
                    'success' => false, 
                    'message' => 'Không thể xóa tài khoản có đơn hàng đang xử lý. Vui lòng hoàn thành hoặc hủy các đơn hàng trước.'
                ]);
            }
            
            // Xóa tài khoản
            $result = $this->userModel->delete($id);
            
            if ($result) {
                return $this->json(['success' => true, 'message' => 'Xóa tài khoản thành công']);
            } else {
                return $this->json(['success' => false, 'message' => 'Không thể xóa tài khoản']);
            }
            
        } catch (\Exception $e) {
            error_log("Delete user error: " . $e->getMessage());
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa tài khoản']);
        }
    }
}