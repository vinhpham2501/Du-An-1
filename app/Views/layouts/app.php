<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? '' ?></title>
    
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
    <link href="/css/style.css" rel="stylesheet">
    
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
    
    .navbar-brand span {
        color: #ffc107 !important;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
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
    <!-- NAVBAR MỚI -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="/images/logo1.1.png" alt="Sắc Việt" 
                    class="frontend-logo me-2"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                <span class="brand-text">Sắc Việt</span>
            </a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                
                <!-- LEFT MENU -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="/products">Sản phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">Giới thiệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Liên hệ</a></li>
                </ul>

                <!-- SEARCH: chuyển về trang /products để xem kết quả tìm kiếm -->
                <form class="d-flex me-3 search-box" action="/products" method="GET">
                    <input class="form-control" type="text" placeholder="Tìm kiếm..."
                        name="search"
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button class="btn btn-search" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <!-- USER RIGHT -->
                <ul class="navbar-nav">

                    <!-- CART -->
                    <li class="nav-item me-2">
                        <a class="nav-link position-relative" href="/cart">
                            <i class="fas fa-shopping-cart fs-5"></i>
                            <span id="cart-count" class="badge bg-danger rounded-pill cart-badge">
                                <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>
                            </span>
                        </a>
                    </li>

                    <!-- IF LOGINED -->
                    <?php if (isset($_SESSION['user_id'])): ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>
                                <?= htmlspecialchars($_SESSION['user_name']) ?>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li><a class="dropdown-item" href="/profile">Thông tin cá nhân</a></li>
                                <li><a class="dropdown-item" href="/my-orders">Đơn hàng của tôi</a></li>

                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/admin/dashboard">Quản trị</a></li>
                                <?php endif; ?>

                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/logout">
                                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                </a></li>

                            </ul>
                        </li>

                    <?php else: ?>

                        <!-- LOGIN -->
                        <li class="nav-item">
                            <a class="nav-link btn-auth" href="/login">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                            </a>
                        </li>

                        <!-- REGISTER -->
                        <li class="nav-item ms-2">
                            <a class="nav-link btn-auth" href="/register">
                                <i class="bi bi-person-plus me-1"></i> Đăng ký
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
                                <img src="/images/logoo.png" alt="Sắc Việt" style="height: 45px; width: auto;" 
                                     onerror="this.style.display='none';">
                                <h4 class="fw-bold mt-2" style="color: #ffc107;">Sắc Việt</h4>
                            </div>
                            <p class="footer-desc">
                               Sắc Việt – Nơi lưu giữ và tôn vinh vẻ đẹp trang phục truyền thống Việt Nam. Từ những áo dài thướt tha, áo tứ thân duyên dáng đến các bộ cánh dân tộc đặc sắc, mỗi sản phẩm đều được chế tác tinh tế, kết hợp văn hóa và phong cách hiện đại. Mua sắm tại Sắc Việt mang đến trải nghiệm tiện lợi, an toàn và giúp bạn tỏa sáng vẻ đẹp truyền thống trong mọi khoảnh khắc.
                            </p>
                            <div class="footer-social">
                                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
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
                                    <span>Đà Nẵng</span>
                                </li>
                                <li>
                                    <i class="fas fa-phone"></i>
                                    <span>0111111111</span>
                                </li>
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <span>nhom3@gmail.com</span>
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
                    <div class="col-md-6 text-center text-md-start d-flex flex-column flex-md-row align-items-center gap-2">
                        <img src="/images/logo1.1.png" alt="Sắc Việt" style="height: 28px; width: auto;" onerror="this.style.display='none';">
                        <p class="mb-0">
                            &copy; 2025 <strong style="color: #ffc107;">Sắc Việt</strong> - All rights reserved.
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
        background-color: #5a0000;
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
        background-color: #ffc107;
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
        color: #ffc107;
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
        color: #ffc107;
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
        background: #8b0000;
        color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(139, 0, 0, 0.4);
    }
    
    .footer-bottom {
        background-color: #5a0000;
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
    
    /* Image lightbox overlay */
    .image-lightbox-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1060;
    }

    .image-lightbox-backdrop.active {
        display: flex;
    }

    .image-lightbox-content {
        max-width: 90vw;
        max-height: 90vh;
        box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        border-radius: 12px;
        overflow: hidden;
        background: #000;
    }

    .image-lightbox-content img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
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

    <!-- Image Lightbox Overlay -->
    <div id="imageLightbox" class="image-lightbox-backdrop">
        <div class="image-lightbox-content">
            <img src="" alt="Xem ảnh lớn" id="imageLightboxImg">
        </div>
    </div>

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
    <script src="/js/app.js"></script>
</body>
</html>
