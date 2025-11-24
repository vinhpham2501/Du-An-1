<?php $title = 'Trang chủ - Sắc Việt Traditional Shop'; ?>

<!-- Include Homepage CSS -->
<link rel="stylesheet" href="/css/homepage.css">

<!-- Include Homepage JavaScript -->
<script src="/js/homepage.js"></script>

<?php
// Include CSS cho trang chủ
echo '<link href="/public/css/homepage.css" rel="stylesheet">';
?>

<!-- Hero Banner Section (Traditional Clothing) -->
<?php 
    $bannerImageUrl = $bannerImageUrl ?? 'https://i.pinimg.com/1200x/47/f4/65/47f4657188bf0f0a9917cf9236da9957.jpg';
?>
<section class="hero-banner" style="position:relative;background: url('<?= htmlspecialchars($bannerImageUrl) ?>') center/cover no-repeat;">
    <div style="background: rgba(255, 240, 245, 0.6);">
        <div class="container py-5 py-lg-6" style="min-height: 420px;">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="badge bg-danger-subtle text-danger mb-3" style="font-size: .9rem;">Bộ sưu tập mới</span>
                    <h1 class="display-5 fw-bold mb-3">Tỏa sáng cùng trang phục truyền thống</h1>
                    <p class="lead text-muted mb-4">Khám phá áo dài, áo tứ thân, và nhiều thiết kế đậm bản sắc Việt.</p>
                    <div>
                        <a href="#productsContainer" class="btn btn-warning btn-lg me-3">
                            <i class="fas fa-bag-shopping me-2"></i>Mua sắm ngay
                        </a>
                        <a href="/about" class="btn btn-outline-dark btn-lg">
                            <i class="fas fa-circle-info me-2"></i>Tìm hiểu thêm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Icons Row -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="feature">
                    <i class="fas fa-truck fa-2x text-primary mb-3"></i>
                    <h5 class="mb-2">Giao hàng nhanh chóng</h5>
                    <p class="text-muted">Giao hàng tận nơi trong vòng 24 giờ.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="feature">
                    <i class="fas fa-undo-alt fa-2x text-primary mb-3"></i>
                    <h5 class="mb-2">Đổi trả dễ dàng</h5>
                    <p class="text-muted">Đổi trả sản phẩm trong vòng 7 ngày.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature">
                    <i class="fas fa-lock fa-2x text-primary mb-3"></i>
                    <h5 class="mb-2">Thanh toán an toàn</h5>
                    <p class="text-muted">Hệ thống thanh toán an toàn và bảo mật.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hero Slider Section -->
<section class="hero-slider-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <?php foreach ($featuredProducts as $index => $product): ?>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $index ?>" 
                        <?= $index === 0 ? 'class="active"' : '' ?>></button>
            <?php endforeach; ?>
        </div>
        
        <div class="carousel-inner">
            <?php foreach ($featuredProducts as $index => $product): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="hero-slide" style="background: linear-gradient(135deg, 
                        <?php 
                        $gradients = [
                            'rgba(74, 144, 226, 0.8), rgba(143, 88, 188, 0.8)',
                            'rgba(255, 94, 77, 0.8), rgba(255, 154, 0, 0.8)', 
                            'rgba(67, 206, 162, 0.8), rgba(24, 90, 157, 0.8)',
                            'rgba(247, 151, 30, 0.8), rgba(255, 61, 87, 0.8)'
                        ];
                        echo $gradients[$index % 4];
                        ?>)">
                        <div class="container">
                            <div class="row align-items-center min-vh-60">
                                <div class="col-lg-6">
                                    <div class="hero-content text-white">
                                        <span class="badge bg-warning text-dark mb-3 fs-6">Sản phẩm nổi bật</span>
                                        <h1 class="display-4 fw-bold mb-4"><?= htmlspecialchars($product['name']) ?></h1>
                                        <p class="lead mb-4"><?= htmlspecialchars($product['description']) ?></p>
                                        <div class="hero-buttons">
                                            <a href="/product/<?= $product['id'] ?>" class="btn btn-warning btn-lg me-3">
                                                <i class="fas fa-eye me-2"></i>Xem chi tiết
                                            </a>
                                            <button class="btn btn-outline-light btn-lg" onclick="addToCart(<?= $product['id'] ?>)">
                                                <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="hero-image text-center">
                                        <div class="dish-image-container">
                                            <?php if (!empty($product['image_url'])): ?>
                                                <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                                     alt="<?= htmlspecialchars($product['name']) ?>" class="dish-image">
                                            <?php else: ?>
                                                <div class="dish-image d-flex align-items-center justify-content-center bg-light">
                                                    <i class="fas fa-shirt fa-5x text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</section>

<!-- Top Selling Products Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">
                    <i class="fas fa-fire text-danger me-2"></i>
                    Sản phẩm bán chạy
                </h2>
                <p class="text-muted">Những trang phục được yêu thích nhất tại cửa hàng</p>
            </div>
        </div>
        
        <div id="topSellingCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php 
                $chunks = array_chunk($topSellingProducts, 4);
                foreach ($chunks as $index => $chunk): 
                ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="row">
                            <?php foreach ($chunk as $product): ?>
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <div class="product-card h-100 shadow-sm">
                                        <div class="position-relative">
                                            <a href="/product/<?= $product['id'] ?>" class="text-decoration-none">
                                                <?php if (!empty($product['image_url'])): ?>
                                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                                         class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" 
                                                         style="height: 200px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                         style="height: 200px;">
                                                        <i class="fas fa-shirt fa-3x text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </a>
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-fire me-1"></i>Bán chạy
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                            <p class="card-text text-muted small"><?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="price">
                                                    <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                                        <span class="text-danger fw-bold"><?= number_format($product['sale_price']) ?>đ</span>
                                                        <small class="text-muted text-decoration-line-through"><?= number_format($product['price']) ?>đ</small>
                                                    <?php else: ?>
                                                        <span class="text-primary fw-bold"><?= number_format($product['price']) ?>đ</span>
                                                    <?php endif; ?>
                                                </div>
                                                <button class="btn btn-primary btn-sm" onclick="addToCart(<?= $product['id'] ?>)">
                                                    <i class="fas fa-cart-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($chunks) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#topSellingCarousel" data-bs-slide="prev">
                    <i class="fas fa-chevron-left text-dark"></i>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#topSellingCarousel" data-bs-slide="next">
                    <i class="fas fa-chevron-right text-dark"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Sale Products Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">
                    <i class="fas fa-tags text-warning me-2"></i>
                    Trang phục đang khuyến mãi
                </h2>
                <p class="text-muted">Ưu đãi hấp dẫn cho các mẫu áo dài, trang phục truyền thống</p>
            </div>
        </div>
        
        <div id="saleCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php 
                $saleChunks = array_chunk($saleProducts, 4);
                foreach ($saleChunks as $index => $chunk): 
                ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="row">
                            <?php foreach ($chunk as $product): ?>
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <div class="product-card h-100 shadow-sm">
                                        <div class="position-relative">
                                            <a href="/product/<?= $product['id'] ?>" class="text-decoration-none">
                                                <?php if (!empty($product['image_url'])): ?>
                                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                                         class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" 
                                                         style="height: 200px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                         style="height: 200px;">
                                                        <i class="fas fa-shirt fa-3x text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </a>
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-percent me-1"></i>
                                                    <?= round((($product['price'] - $product['sale_price']) / $product['price']) * 100) ?>% OFF
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                            <p class="card-text text-muted small"><?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="price">
                                                    <span class="text-danger fw-bold"><?= number_format($product['sale_price']) ?>đ</span>
                                                    <small class="text-muted text-decoration-line-through"><?= number_format($product['price']) ?>đ</small>
                                                </div>
                                                <button class="btn btn-primary rounded-circle p-2" onclick="addToCart(<?= $product['id'] ?>)" title="Thêm vào giỏ hàng">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($saleChunks) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#saleCarousel" data-bs-slide="prev">
                    <i class="fas fa-chevron-left text-dark"></i>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#saleCarousel" data-bs-slide="next">
                    <i class="fas fa-chevron-right text-dark"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- New Products Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">
                    <i class="fas fa-star text-success me-2"></i>
                    Bộ sưu tập mới
                </h2>
                <p class="text-muted">Những thiết kế truyền thống mới nhất vừa cập bến</p>
            </div>
        </div>
        
        <div id="newProductsCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php 
                $newChunks = array_chunk($newProducts, 4);
                foreach ($newChunks as $index => $chunk): 
                ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="row">
                            <?php foreach ($chunk as $product): ?>
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <div class="product-card h-100 shadow-sm">
                                        <div class="position-relative">
                                            <a href="/product/<?= $product['id'] ?>" class="text-decoration-none">
                                                <?php if (!empty($product['image_url'])): ?>
                                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                                         class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" 
                                                         style="height: 200px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                         style="height: 200px;">
                                                        <i class="fas fa-shirt fa-3x text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </a>
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-star me-1"></i>Mới
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                            <p class="card-text text-muted small"><?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="price">
                                                    <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                                        <span class="text-danger fw-bold"><?= number_format($product['sale_price']) ?>đ</span>
                                                        <small class="text-muted text-decoration-line-through"><?= number_format($product['price']) ?>đ</small>
                                                    <?php else: ?>
                                                        <span class="text-primary fw-bold"><?= number_format($product['price']) ?>đ</span>
                                                    <?php endif; ?>
                                                </div>
                                                <button class="btn btn-success btn-sm" onclick="addToCart(<?= $product['id'] ?>)">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($newChunks) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#newProductsCarousel" data-bs-slide="prev">
                    <i class="fas fa-chevron-left text-dark"></i>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#newProductsCarousel" data-bs-slide="next">
                    <i class="fas fa-chevron-right text-dark"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Main Products Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">
                    <i class="fas fa-list me-2"></i>
                    Sản phẩm của cửa hàng
                </h2>
                <p class="text-muted">Bộ sưu tập trang phục truyền thống đa dạng</p>
            </div>
        </div>

        <!-- Categories Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center gap-2" id="categoryFilter">
                    <button class="btn btn-outline-primary category-btn active" data-category="">
                        <i class="fas fa-th-large me-1"></i>Tất cả
                    </button>
                    <?php foreach ($categories as $category): ?>
                        <button class="btn btn-outline-primary category-btn" data-category="<?= $category['id'] ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center py-5" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Đang tải...</span>
            </div>
            <p class="mt-2 text-muted">Đang tải sản phẩm...</p>
        </div>

        <!-- Products Grid -->
        <div class="row" id="productsContainer">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="product-card h-100 shadow-sm">
                            <div class="position-relative">
                                <a href="/product/<?= $product['id'] ?>" class="text-decoration-none">
                                    <?php if (!empty($product['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                             class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" 
                                             style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-shirt fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>
                                
                                <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-warning">
                                            <i class="fas fa-percent me-1"></i>Sale
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="/product/<?= $product['id'] ?>" class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted small"><?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="price">
                                        <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                            <span class="text-danger fw-bold"><?= number_format($product['sale_price']) ?>đ</span>
                                            <small class="text-muted text-decoration-line-through"><?= number_format($product['price']) ?>đ</small>
                                        <?php else: ?>
                                            <span class="text-primary fw-bold"><?= number_format($product['price']) ?>đ</span>
                                        <?php endif; ?>
                                    </div>
                                    <button class="btn btn-primary btn-sm" onclick="addToCart(<?= $product['id'] ?>)">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-5x text-muted mb-3"></i>
                    <h3 class="text-muted">Không tìm thấy sản phẩm nào</h3>
                    <p class="text-muted">Vui lòng thử tìm kiếm với từ khóa khác</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer">
            <?php if ($pagination['totalPages'] > 1): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <nav aria-label="Phân trang sản phẩm">
                            <ul class="pagination justify-content-center">
                                <?php if ($pagination['hasPrev']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="#" onclick="filterProducts(<?= $pagination['currentPage'] - 1 ?>); return false;">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php 
                                $start = max(1, $pagination['currentPage'] - 2);
                                $end = min($pagination['totalPages'], $pagination['currentPage'] + 2);
                                for ($i = $start; $i <= $end; $i++): 
                                ?>
                                    <li class="page-item <?= $i == $pagination['currentPage'] ? 'active' : '' ?>">
                                        <a class="page-link" href="#" onclick="filterProducts(<?= $i ?>); return false;"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($pagination['hasNext']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="#" onclick="filterProducts(<?= $pagination['currentPage'] + 1 ?>); return false;">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
// Global variables
let currentCategory = '';
let currentPage = 1;

// Category filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const categoryButtons = document.querySelectorAll('.category-btn');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get category ID
            currentCategory = this.getAttribute('data-category');
            currentPage = 1; // Reset to first page
            
            // Filter products
            filterProducts(1);
        });
    });
});

// Filter products function
function filterProducts(page = 1) {
    currentPage = page;
    
    // Show loading spinner
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('productsContainer').style.opacity = '0.5';
    
    // Build query parameters
    const params = new URLSearchParams();
    params.append('page', page);
    
    if (currentCategory) {
        params.append('category_id', currentCategory);
    }
    
    // Make AJAX request
    fetch(`/api/products/filter?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update products container
                document.getElementById('productsContainer').innerHTML = data.products;
                
                // Update pagination
                document.getElementById('paginationContainer').innerHTML = data.pagination;
                
                // Scroll to products section
                document.getElementById('productsContainer').scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            } else {
                // Show error message
                document.getElementById('productsContainer').innerHTML = 
                    '<div class="col-12 text-center py-5">' +
                    '<i class="fas fa-exclamation-triangle fa-5x text-danger mb-3"></i>' +
                    '<h3 class="text-danger">Có lỗi xảy ra</h3>' +
                    '<p class="text-muted">' + (data.message || 'Vui lòng thử lại sau') + '</p>' +
                    '</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('productsContainer').innerHTML = 
                '<div class="col-12 text-center py-5">' +
                '<i class="fas fa-exclamation-triangle fa-5x text-danger mb-3"></i>' +
                '<h3 class="text-danger">Có lỗi xảy ra</h3>' +
                '<p class="text-muted">Vui lòng kiểm tra kết nối mạng và thử lại</p>' +
                '</div>';
        })
        .finally(() => {
            // Hide loading spinner
            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('productsContainer').style.opacity = '1';
        });
}

// Add to cart function (existing)
function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            document.getElementById('cart-count').textContent = data.cartCount;
            
            // Show success toast
            const toast = document.getElementById('cartToast');
            const toastBody = toast.querySelector('.toast-body');
            toastBody.textContent = 'Đã thêm sản phẩm vào giỏ hàng!';
            
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 1500
            });
            bsToast.show();
        } else {
            // Show error toast
            const toast = document.getElementById('cartToast');
            const toastHeader = toast.querySelector('.toast-header');
            const toastBody = toast.querySelector('.toast-body');
            
            toastHeader.className = 'toast-header bg-danger text-white';
            toastHeader.querySelector('strong').textContent = 'Lỗi';
            toastHeader.querySelector('i').className = 'fas fa-exclamation-circle me-2';
            toastBody.textContent = data.message || 'Có lỗi xảy ra, vui lòng thử lại!';
            
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 4000
            });
            bsToast.show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Show error toast
        const toast = document.getElementById('cartToast');
        const toastHeader = toast.querySelector('.toast-header');
        const toastBody = toast.querySelector('.toast-body');
        
        toastHeader.className = 'toast-header bg-danger text-white';
        toastHeader.querySelector('strong').textContent = 'Lỗi';
        toastHeader.querySelector('i').className = 'fas fa-exclamation-circle me-2';
        toastBody.textContent = 'Có lỗi xảy ra, vui lòng thử lại!';
        
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 4000
        });
        bsToast.show();
    });
}
</script>
