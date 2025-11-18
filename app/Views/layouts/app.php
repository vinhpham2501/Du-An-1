<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Restaurant Order System' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logo1.1.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/logo1.1.png">
    <link rel="shortcut icon" href="/images/logo1.1.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/logo1.1.png">
    <meta name="msapplication-TileImage" content="/images/logo1.1.png">
    <meta name="msapplication-TileColor" content="#007bff">
    <meta name="theme-color" content="#007bff">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/public/css/style.css" rel="stylesheet">
    
    <style>
    .navbar-brand img {
        transition: transform 0.3s ease;
        border-radius: 4px;
    }
    
    .navbar-brand:hover img {
        transform: scale(1.05);
    }
    
    .navbar-brand {
        font-size: 1.2rem;
        font-weight: 600;
    }
    
    .frontend-logo {
        max-height: 36px;
        width: auto;
        object-fit: contain;
    }
    .nav-link.icon-link {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px !important;
        border-radius: 8px;
        transition: 0.25s ease;
        font-weight: 500;
    }

    .nav-link.icon-link:hover {
        background-color: #f0f2f5;
        color: #0d6efd !important;
        transform: translateY(-2px);
    }

    .nav-link.icon-link i {
        font-size: 1.1rem;
        transition: 0.25s ease;
    }

    .nav-link.icon-link:hover i {
        transform: scale(1.2);
    }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="images/logo1.1.png" alt="Logo" class="frontend-logo me-2" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                <i class="fas fa-utensils me-2" style="display: none;"></i>
                <span class="fw-bold">Sắc Việt</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Liên hệ</a>
                    </li>
                </ul>
                
                <!-- Search Form -->
                <form class="d-flex me-3" method="GET" action="/" style="width: 300px;">
                    <input class="form-control me-2" type="search" name="search" 
                           placeholder="Tìm kiếm..." 
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
                           style="border-radius: 20px;">
                    <button class="btn btn-outline-warning" type="submit" style="border-radius: 20px;">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="/cart">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="cart-count">
                                <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>
                            </span>
                        </a>
                    </li>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>
                                <?= htmlspecialchars($_SESSION['user_name']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/profile">Thông tin cá nhân</a></li>
                                <li><a class="dropdown-item" href="/my-orders">Đơn hàng của tôi</a></li>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/admin/dashboard">Quản trị</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout">Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link icon-link" href="/login">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link icon-link" href="/register">
                                <i class="bi bi-person-plus"></i>
                                Đăng ký
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="footer-modern mt-5">
        <div class="footer-top">
            <div class="container">
                <div class="row g-4">
                    <!-- About Section -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget">
                            <div class="footer-logo mb-3">
                                <img src="images/logoo.png" alt="Logo" style="height: 45px; width: auto;" 
                                     onerror="this.style.display='none';">
                                <h4 class="text-white fw-bold mt-2">Restaurant</h4>
                            </div>
                            <p class="footer-desc">
                                Hệ thống đặt món ăn trực tuyến tiện lợi, nhanh chóng và an toàn. 
                                Mang đến trải nghiệm ẩm thực tuyệt vời cho bạn.
                            </p>
                            <div class="footer-social">
                                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-title">Liên kết</h5>
                            <ul class="footer-links">
                                <li><a href="/"><i class="fas fa-chevron-right me-2"></i>Trang chủ</a></li>
                                <li><a href="/about"><i class="fas fa-chevron-right me-2"></i>Giới thiệu</a></li>
                                <li><a href="/contact"><i class="fas fa-chevron-right me-2"></i>Liên hệ</a></li>
                                <li><a href="/my-orders"><i class="fas fa-chevron-right me-2"></i>Đơn hàng</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Support -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-title">Hỗ trợ</h5>
                            <ul class="footer-links">
                                <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Chính sách đổi trả</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Bảo mật thông tin</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Điều khoản sử dụng</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Câu hỏi thường gặp</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-title">Liên hệ</h5>
                            <ul class="footer-contact">
                                <li>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Hải Châu, Đà Nẵng</span>
                                </li>
                                <li>
                                    <i class="fas fa-phone"></i>
                                    <span>0372886625</span>
                                </li>
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <span>vinhpham261206@gmail.com</span>
                                </li>
                                <li>
                                    <i class="fas fa-clock"></i>
                                    <span>8:00 - 22:00 hàng ngày</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">
                            &copy; 2025 <strong>Phạm Trường Vinh</strong> - PD12070. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">
                            Made with <i class="fas fa-heart text-danger"></i> in Đà Nẵng
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <style>
    /* Modern Footer Styles */
    .footer-modern {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        color: #e0e0e0;
    }
    
    .footer-top {
        padding: 60px 0 40px;
    }
    
    .footer-widget {
        margin-bottom: 30px;
    }
    
    .footer-logo img {
        filter: brightness(1.2);
    }
    
    .footer-desc {
        color: #b0b0b0;
        line-height: 1.8;
        margin-bottom: 20px;
        font-size: 0.95rem;
    }
    
    .footer-title {
        color: #ffffff;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 25px;
        position: relative;
        padding-bottom: 12px;
    }
    
    .footer-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, #007bff, #0056b3);
        border-radius: 2px;
    }
    
    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .footer-links li {
        margin-bottom: 12px;
    }
    
    .footer-links a {
        color: #b0b0b0;
        text-decoration: none;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .footer-links a:hover {
        color: #007bff;
        transform: translateX(5px);
    }
    
    .footer-links a i {
        font-size: 0.7rem;
        opacity: 0.7;
    }
    
    .footer-contact {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .footer-contact li {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        color: #b0b0b0;
        font-size: 0.95rem;
    }
    
    .footer-contact li i {
        color: #007bff;
        font-size: 1.1rem;
        margin-right: 12px;
        margin-top: 2px;
        min-width: 20px;
    }
    
    .footer-social {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }
    
    .social-link {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1rem;
    }
    
    .social-link:hover {
        background: #007bff;
        color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    }
    
    .footer-bottom {
        background: rgba(0, 0, 0, 0.2);
        padding: 20px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .footer-bottom p {
        color: #b0b0b0;
        font-size: 0.9rem;
    }
    
    .footer-bottom strong {
        color: #ffffff;
    }
    
    .footer-bottom .text-danger {
        animation: heartbeat 1.5s ease-in-out infinite;
    }
    
    @keyframes heartbeat {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    @media (max-width: 768px) {
        .footer-top {
            padding: 40px 0 20px;
        }
        
        .footer-widget {
            margin-bottom: 25px;
        }
        
        .footer-bottom .col-md-6 {
            margin-bottom: 10px;
        }
    }
    </style>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="cartToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Thành công</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Đã thêm sản phẩm vào giỏ hàng!
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/public/js/app.js"></script>
</body>
</html>
