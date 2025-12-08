<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Address;

class AuthController extends Controller
{
    private $userModel;
    private $addressModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->addressModel = new Address();
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
            
            // Auto-upgrade legacy plaintext passwords to hashed
            $this->userModel->upgradePasswordIfNeeded($user['id'], $user['password'], $password);

            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_phone'] = $user['phone'] ?? '';
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
            $fullName = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $phone = trim($_POST['phone'] ?? '');
            $address = $_POST['address'] ?? '';
            $gender = $_POST['gender'] ?? null;
            
            // Validation
            if (empty($fullName) || empty($email) || empty($password)) {
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
            
            // Generate username from email (hiện không lưu vào bảng KHACH_HANG nhưng giữ lại nếu cần mở rộng)
            $username = explode('@', $email)[0];

            // Chuẩn bị dữ liệu tạo user, chỉ thêm phone nếu người dùng thực sự nhập
            $userData = [
                'full_name' => $fullName,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'address' => $address,
                'role' => 'user',
            ];

            $userId = $this->userModel->create($userData);
            
            if ($userId) {
                $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
                $this->redirect('/login');
            } else {
                return $this->render('auth/register', ['error' => 'Có lỗi xảy ra, vui lòng thử lại']);
            }
        }
        
        return $this->render('auth/register');
    }

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            if ($email === '') {
                return $this->render('auth/forgot-password', ['error' => 'Vui lòng nhập email']);
            }

            $user = $this->userModel->findByEmail($email);
            // For now, do not send email; just show a friendly message if user exists
            if ($user) {
                // In production, generate token and send email here
                return $this->render('auth/forgot-password', [
                    'success' => 'Nếu email tồn tại, chúng tôi đã gửi liên kết đặt lại mật khẩu. Vui lòng kiểm tra hộp thư.'
                ]);
            }

            // Do not reveal whether email exists to avoid user enumeration
            return $this->render('auth/forgot-password', [
                'success' => 'Nếu email tồn tại, chúng tôi đã gửi liên kết đặt lại mật khẩu. Vui lòng kiểm tra hộp thư.'
            ]);
        }

        return $this->render('auth/forgot-password');
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

        // Lấy địa chỉ giao hàng mặc định từ bảng DIA_CHI_GIAO_HANG để hiển thị trong profile
        $defaultAddress = $this->addressModel->getDefaultForUser($_SESSION['user_id']);
        if ($defaultAddress && !empty($defaultAddress['DiaChi'])) {
            $user['address'] = $defaultAddress['DiaChi'];
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            
            if (empty($fullName)) {
                return $this->render('auth/profile', ['error' => 'Tên không được để trống', 'user' => $user]);
            }
            
            $this->userModel->update($_SESSION['user_id'], [
                'full_name' => $fullName,
                'phone' => $phone,
                'address' => $address
            ]);

            // Lưu địa chỉ giao hàng mặc định để checkout tự động lấy ra
            if (!empty($address)) {
                $this->addressModel->createOrUpdateDefault($_SESSION['user_id'], $address, null);
            }
            
            $_SESSION['user_name'] = $fullName;
            $_SESSION['user_phone'] = $phone;
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
