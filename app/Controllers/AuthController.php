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

            // Prepare user data - only save fields that exist in KHACH_HANG table
            $userData = [
                'full_name' => $fullName,
                'email' => $email,
                'password' => $password,
                'role' => 'user',
            ];
            
            // Only add optional fields if they have values
            if (!empty($phone)) {
                $userData['phone'] = $phone;
            }
            if (!empty($gender)) {
                $userData['gender'] = $gender;
            }

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
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            // Nếu chưa nhập email -> hiển thị form nhập email
            if (isset($_POST['step']) && $_POST['step'] == 1) {
                if (empty($email)) {
                    return $this->render('auth/forgot-password', ['error' => 'Vui lòng nhập email']);
                }

                $user = $this->userModel->findByEmail($email);
                
                if (!$user) {
                    return $this->render('auth/forgot-password', ['error' => 'Email không tồn tại trong hệ thống']);
                }

                // Email tồn tại -> hiển thị form nhập mật khẩu mới
                return $this->render('auth/forgot-password', ['step' => 2, 'email' => $email]);
            }

            // Step 2: Cập nhật mật khẩu
            if (isset($_POST['step']) && $_POST['step'] == 2) {
                if (empty($password) || empty($passwordConfirm)) {
                    return $this->render('auth/forgot-password', [
                        'step' => 2,
                        'email' => $email,
                        'error' => 'Vui lòng nhập đầy đủ mật khẩu'
                    ]);
                }

                if (strlen($password) < 6) {
                    return $this->render('auth/forgot-password', [
                        'step' => 2,
                        'email' => $email,
                        'error' => 'Mật khẩu phải có ít nhất 6 ký tự'
                    ]);
                }

                if ($password !== $passwordConfirm) {
                    return $this->render('auth/forgot-password', [
                        'step' => 2,
                        'email' => $email,
                        'error' => 'Mật khẩu xác nhận không khớp'
                    ]);
                }

                // Lấy user từ email
                $user = $this->userModel->findByEmail($email);
                if (!$user) {
                    return $this->render('auth/forgot-password', ['error' => 'Email không hợp lệ']);
                }

                // Cập nhật mật khẩu
                $this->userModel->updatePassword($user['id'], $password);

                $_SESSION['success'] = 'Mật khẩu đã được thay đổi thành công. Vui lòng đăng nhập.';
                return $this->redirect('/login');
            }
        }

        return $this->render('auth/forgot-password', ['step' => 1]);
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
        if ($defaultAddress) {
            $user['address_diachi'] = $defaultAddress['DiaChi'] ?? '';
            $user['address_phuongxa'] = $defaultAddress['PhuongXa'] ?? '';
            $user['address_quanhuyen'] = $defaultAddress['QuanHuyen'] ?? '';
            $user['address_tinhthanh'] = $defaultAddress['TinhThanh'] ?? '';
            $user['address_ghichu'] = $defaultAddress['GhiChu'] ?? '';
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $diaChi = $_POST['address_diachi'] ?? '';
            $phuongXa = $_POST['address_phuongxa'] ?? '';
            $quanHuyen = $_POST['address_quanhuyen'] ?? '';
            $tinhThanh = $_POST['address_tinhthanh'] ?? '';
            $ghiChu = $_POST['address_ghichu'] ?? '';
            
            if (empty($fullName)) {
                return $this->render('auth/profile', ['error' => 'Tên không được để trống', 'user' => $user]);
            }
            
            // Update user info (không bao gồm address - address lưu ở DIA_CHI_GIAO_HANG)
            $this->userModel->update($_SESSION['user_id'], [
                'full_name' => $fullName,
                'phone' => $phone
            ]);

            // Lưu địa chỉ giao hàng mặc định trong bảng DIA_CHI_GIAO_HANG
            if (!empty(trim((string)$diaChi))) {
                $this->addressModel->createOrUpdateDefault($_SESSION['user_id'], [
                    'DiaChi' => $diaChi,
                    'PhuongXa' => $phuongXa,
                    'QuanHuyen' => $quanHuyen,
                    'TinhThanh' => $tinhThanh,
                    'GhiChu' => $ghiChu,
                ], null);
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
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method']);
        }
        
        $currentPassword = trim($_POST['current_password'] ?? '');
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');
        
        // Validation
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            return $this->json(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin']);
        }
        
        if ($newPassword !== $confirmPassword) {
            return $this->json(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp']);
        }
        
        if (strlen($newPassword) < 6) {
            return $this->json(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
        }
        
        // Get user by ID
        $user = $this->userModel->findById($_SESSION['user_id']);
        
        if (!$user) {
            return $this->json(['success' => false, 'message' => 'Không tìm thấy người dùng']);
        }
        
        // Verify current password
        if (!$this->userModel->verifyPassword($currentPassword, $user['password'])) {
            return $this->json(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng']);
        }
        
        // Update password
        if ($this->userModel->updatePassword($_SESSION['user_id'], $newPassword)) {
            return $this->json(['success' => true, 'message' => 'Đổi mật khẩu thành công']);
        } else {
            return $this->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại']);
        }
    }
}
