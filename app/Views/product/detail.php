<?php use App\Helpers\ImageHelper; $title = htmlspecialchars($product['name']) . ' - Chi tiết sản phẩm'; ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="py-3">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/products?category_id=<?= $product['category_id'] ?>" class="text-decoration-none"><?= htmlspecialchars($product['category_name']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </div>
</nav>

<!-- Product Detail Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-6 mb-4">
                <div class="product-image-container">
                    <?php 
                        $detailImageSrc = ImageHelper::getImageSrc($product['image_url'] ?? null);
                    ?>
                    <?php if (!empty($detailImageSrc)): ?>
                        <div class="position-relative" style="width: 100%; height: 500px;">
                            <img src="<?= htmlspecialchars($detailImageSrc) ?>" 
                                 class="img-fluid rounded shadow-lg w-100 h-100" 
                                 alt="<?= htmlspecialchars($product['name']) ?>"
                                 style="object-fit: cover;"
                                 loading="eager" decoding="async"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="bg-light rounded shadow-lg position-absolute top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center">
                                <i class="fas fa-utensils fa-5x text-muted"></i>
                            </div>
        	            </div>
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-lg" 
                             style="width: 100%; height: 500px;">
                            <i class="fas fa-utensils fa-5x text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Product Name -->
                    <h1 class="product-title mb-3"><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <!-- Category -->
                    <div class="mb-3">
                        <span class="badge bg-primary fs-6">
                            <i class="fas fa-tag me-1"></i>
                            <?= htmlspecialchars($product['category_name']) ?>
                        </span>
                    </div>

                    <!-- Price -->
                    <div class="price-section mb-4">
                        <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                            <div class="d-flex align-items-center gap-3">
                                <span class="sale-price text-danger fs-2 fw-bold"><?= number_format($product['sale_price']) ?>đ</span>
                                <span class="original-price text-muted fs-4 text-decoration-line-through"><?= number_format($product['price']) ?>đ</span>
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="fas fa-percent me-1"></i>
                                    <?= round((($product['price'] - $product['sale_price']) / $product['price']) * 100) ?>% OFF
                                </span>
                            </div>
                        <?php else: ?>
                            <span class="current-price text-primary fs-2 fw-bold"><?= number_format($product['price']) ?>đ</span>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="description mb-4">
                        <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Mô tả sản phẩm</h5>
                        <p class="text-muted lh-lg"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    </div>

                    <!-- Availability Status -->
                    <div class="availability mb-4">
                        <?php if ($product['is_available']): ?>
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i>Còn hàng
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger fs-6">
                                <i class="fas fa-times-circle me-1"></i>Hết hàng
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Quantity and Add to Cart -->
                    <?php if ($product['is_available']): ?>
                        <div class="add-to-cart-section">
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-3">
                                    <label for="quantity" class="form-label fw-semibold">Số lượng:</label>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="10">
                                        <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <button class="btn btn-primary btn-lg w-100" onclick="addToCartDetail(<?= $product['id'] ?>)">
                                        <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Sản phẩm này hiện tại đã hết hàng. Vui lòng quay lại sau!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products Section -->
<?php if (!empty($relatedProducts)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">
                    <i class="fas fa-shirt me-2"></i>
                    Sản phẩm cùng loại
                </h2>
                <p class="text-muted">Những sản phẩm khác bạn có thể quan tâm</p>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($relatedProducts as $relatedProduct): ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="product-card h-100 shadow-sm">
                        <div class="position-relative">
                            <a href="/product/<?= $relatedProduct['id'] ?>" class="text-decoration-none">
                                <?php 
                                    $relatedImg = ImageHelper::getImageSrc($relatedProduct['image_url'] ?? null);
                                ?>
                                <?php if (!empty($relatedImg)): ?>
                                    <img src="<?= htmlspecialchars($relatedImg) ?>" 
                                         class="card-img-top" alt="<?= htmlspecialchars($relatedProduct['name']) ?>" 
                                         style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-shirt fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </a>
                            
                            <?php if (!empty($relatedProduct['sale_price']) && $relatedProduct['sale_price'] < $relatedProduct['price']): ?>
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-warning">
                                        <i class="fas fa-percent me-1"></i>Sale
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/product/<?= $relatedProduct['id'] ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($relatedProduct['name']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small"><?= htmlspecialchars(substr($relatedProduct['description'], 0, 80)) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="price">
                                    <?php if (!empty($relatedProduct['sale_price']) && $relatedProduct['sale_price'] < $relatedProduct['price']): ?>
                                        <span class="text-danger fw-bold"><?= number_format($relatedProduct['sale_price']) ?>đ</span>
                                        <small class="text-muted text-decoration-line-through"><?= number_format($relatedProduct['price']) ?>đ</small>
                                    <?php else: ?>
                                        <span class="text-primary fw-bold"><?= number_format($relatedProduct['price']) ?>đ</span>
                                    <?php endif; ?>
                                </div>
                                <button class="btn btn-primary btn-sm" onclick="addToCart(<?= $relatedProduct['id'] ?>)">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
// Quantity controls
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue < 10) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

// Add to cart with quantity
function addToCartDetail(productId) {
    const quantity = parseInt(document.getElementById('quantity').value);
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
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
            toastBody.textContent = `Đã thêm ${quantity} sản phẩm vào giỏ hàng!`;
            
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

// Regular add to cart for related products
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
