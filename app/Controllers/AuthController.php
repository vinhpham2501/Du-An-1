<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                return $this->render('auth/login', ['error' => 'Vui lòng nhập đầy đủ thông tin']);
            }
            
            $user = $this->userModel->findByEmail($email);
            
            if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
                return $this->render('auth/login', ['error' => 'Email hoặc mật khẩu không đúng']);
            }
            
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on role
            if ($user['role'] === 'admin') {
                $this->redirect('/admin/dashboard');
            } else {
                $this->redirect('/');
            }
        }
        
        return $this->render('auth/login');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            
            // Validation
            if (empty($name) || empty($email) || empty($password)) {
                return $this->render('auth/register', ['error' => 'Vui lòng nhập đầy đủ thông tin bắt buộc']);
            }
            
            if ($password !== $confirmPassword) {
                return $this->render('auth/register', ['error' => 'Mật khẩu xác nhận không khớp']);
            }
            
            if (strlen($password) < 6) {
                return $this->render('auth/register', ['error' => 'Mật khẩu phải có ít nhất 6 ký tự']);
            }
            
            // Check if email already exists
            if ($this->userModel->findByEmail($email)) {
                return $this->render('auth/register', ['error' => 'Email đã tồn tại']);
            }
            
            // Create user
            $userId = $this->userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'address' => $address,
                'role' => 'user'
            ]);
            
            if ($userId) {
                $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
                $this->redirect('/login');
            } else {
                return $this->render('auth/register', ['error' => 'Có lỗi xảy ra, vui lòng thử lại']);
            }
        }
        
        return $this->render('auth/register');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }

    public function profile()
    {
        $this->requireAuth();
        
        $user = $this->userModel->findById($_SESSION['user_id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            
            if (empty($name)) {
                return $this->render('auth/profile', ['error' => 'Tên không được để trống', 'user' => $user]);
            }
            
            $this->userModel->update($_SESSION['user_id'], [
                'name' => $name,
                'phone' => $phone,
                'address' => $address
            ]);
            
            $_SESSION['user_name'] = $name;
            $_SESSION['success'] = 'Cập nhật thông tin thành công!';
            $this->redirect('/profile');
        }
        
        return $this->render('auth/profile', ['user' => $user]);
    }

    public function changePassword()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            $user = $this->userModel->findById($_SESSION['user_id']);
            
            if (!$this->userModel->verifyPassword($currentPassword, $user['password'])) {
                return $this->json(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng']);
            }
            
            if ($newPassword !== $confirmPassword) {
                return $this->json(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp']);
            }
            
            if (strlen($newPassword) < 6) {
                return $this->json(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
            }
            
            $this->userModel->updatePassword($_SESSION['user_id'], $newPassword);
            
            return $this->json(['success' => true, 'message' => 'Đổi mật khẩu thành công']);
        }
        
        return $this->json(['success' => false, 'message' => 'Invalid request']);
    }
}
