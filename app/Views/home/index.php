<?php $title = 'Trang chủ - Restaurant Order System'; ?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold">Chào mừng đến với nhà hàng của chúng tôi</h1>
                <p class="lead">Thưởng thức những món ăn ngon nhất với dịch vụ giao hàng tận nơi nhanh chóng và tiện lợi.</p>
                <a href="#products" class="btn btn-light btn-lg">
                    <i class="fas fa-utensils me-2"></i>
                    Đặt món ngay
                </a>
            </div>
            <div class="col-md-6 text-center">
                <i class="fas fa-utensils display-1"></i>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <form method="GET" action="/" class="d-flex">
                    <input type="text" class="form-control me-2" name="search" 
                           placeholder="Tìm kiếm món ăn..." 
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="sort-select" onchange="applySorting()">
                    <option value="newest" <?= ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="price_low" <?= ($filters['sort'] ?? '') === 'price_low' ? 'selected' : '' ?>>Giá thấp → cao</option>
                    <option value="price_high" <?= ($filters['sort'] ?? '') === 'price_high' ? 'selected' : '' ?>>Giá cao → thấp</option>
                    <option value="name" <?= ($filters['sort'] ?? '') === 'name' ? 'selected' : '' ?>>Tên A → Z</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-4">
    <div class="container">
        <h3 class="mb-4">Danh mục món ăn</h3>
        <div class="row">
            <div class="col">
                <div class="d-flex flex-wrap gap-2">
                    <a href="/" class="btn <?= empty($_GET['category_id']) ? 'btn-primary' : 'btn-outline-primary' ?>">
                        Tất cả
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="/?category_id=<?= $category['id'] ?>" 
                           class="btn <?= ($_GET['category_id'] ?? '') == $category['id'] ? 'btn-primary' : 'btn-outline-primary' ?>">
                            <?= htmlspecialchars($category['name']) ?>
                            <span class="badge bg-secondary ms-1"><?= $category['product_count'] ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Top Products Section -->
<?php if (!empty($topProducts)): ?>
<section class="py-4 bg-light">
    <div class="container">
        <h3 class="mb-4">Món ăn bán chạy</h3>
        <div class="row">
            <?php foreach ($topProducts as $product): ?>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 product-card">
                        <?php if ($product['image']): ?>
                            <img src="/public/uploads/<?= htmlspecialchars($product['image']) ?>" 
                                 class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" 
                                 style="height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="fas fa-utensils fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title"><?= htmlspecialchars($product['name']) ?></h6>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php if ($product['sale_price']): ?>
                                            <span class="text-muted text-decoration-line-through small">
                                                <?= number_format($product['price']) ?>đ
                                            </span><br>
                                            <span class="text-danger fw-bold">
                                                <?= number_format($product['sale_price']) ?>đ
                                            </span>
                                        <?php else: ?>
                                            <span class="fw-bold">
                                                <?= number_format($product['price']) ?>đ
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <button class="btn btn-sm btn-primary" onclick="addToCart(<?= $product['id'] ?>)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Products Section -->
<section class="py-5" id="products">
    <div class="container">
        <h3 class="mb-4">Thực đơn</h3>
        
        <?php if (empty($products)): ?>
            <div class="text-center py-5">
                <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                <h4>Không tìm thấy món ăn nào</h4>
                <p class="text-muted">Thử tìm kiếm với từ khóa khác hoặc chọn danh mục khác.</p>
                <a href="/" class="btn btn-primary">Xem tất cả món ăn</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100 product-card">
                            <?php if ($product['image']): ?>
                                <img src="/public/uploads/<?= htmlspecialchars($product['image']) ?>" 
                                     class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" 
                                     style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-utensils fa-4x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="/product?id=<?= $product['id'] ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </a>
                                </h5>
                                
                                <p class="card-text text-muted small flex-grow-1">
                                    <?= htmlspecialchars(substr($product['description'] ?? '', 0, 100)) ?>
                                    <?= strlen($product['description'] ?? '') > 100 ? '...' : '' ?>
                                </p>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-secondary"><?= htmlspecialchars($product['category_name']) ?></span>
                                        <small class="text-muted"><?= htmlspecialchars($product['unit']) ?></small>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php if ($product['sale_price']): ?>
                                                <span class="text-muted text-decoration-line-through">
                                                    <?= number_format($product['price']) ?>đ
                                                </span><br>
                                                <span class="text-danger fw-bold h5 mb-0">
                                                    <?= number_format($product['sale_price']) ?>đ
                                                </span>
                                            <?php else: ?>
                                                <span class="fw-bold h5 mb-0">
                                                    <?= number_format($product['price']) ?>đ
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if ($product['status'] === 'available'): ?>
                                            <button class="btn btn-primary" onclick="addToCart(<?= $product['id'] ?>)">
                                                <i class="fas fa-cart-plus me-1"></i>
                                                Thêm
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-secondary" disabled>
                                                Hết hàng
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function applySorting() {
    const sortValue = document.getElementById('sort-select').value;
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('sort', sortValue);
    window.location.search = urlParams.toString();
}

function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cart-count').textContent = data.cartCount;
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'Có lỗi xảy ra, vui lòng thử lại');
    });
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}
</script>
