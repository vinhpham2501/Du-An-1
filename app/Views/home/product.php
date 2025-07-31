<?php $title = htmlspecialchars($product['name']) . ' - Restaurant Order System'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6">
            <?php if ($product['image']): ?>
                <img src="/public/uploads/<?= htmlspecialchars($product['image']) ?>" 
                     class="img-fluid rounded" alt="<?= htmlspecialchars($product['name']) ?>">
            <?php else: ?>
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="fas fa-utensils fa-5x text-muted"></i>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-6">
            <h1 class="mb-3"><?= htmlspecialchars($product['name']) ?></h1>
            
            <div class="mb-3">
                <?php if ($product['sale_price']): ?>
                    <span class="text-muted text-decoration-line-through h5">
                        <?= number_format($product['price']) ?>đ
                    </span>
                    <span class="text-danger fw-bold h3 ms-2">
                        <?= number_format($product['sale_price']) ?>đ
                    </span>
                    <span class="badge bg-danger ms-2">
                        Giảm <?= round((($product['price'] - $product['sale_price']) / $product['price']) * 100) ?>%
                    </span>
                <?php else: ?>
                    <span class="fw-bold h3">
                        <?= number_format($product['price']) ?>đ
                    </span>
                <?php endif; ?>
            </div>
            
            <p class="text-muted mb-3">Đơn vị: <?= htmlspecialchars($product['unit']) ?></p>
            
            <?php if ($product['description']): ?>
                <div class="mb-4">
                    <h5>Mô tả</h5>
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($product['status'] === 'available'): ?>
                <div class="mb-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <label class="form-label">Số lượng:</label>
                        </div>
                        <div class="col-auto">
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="99">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button class="btn btn-primary btn-lg" onclick="addToCart(<?= $product['id'] ?>)">
                    <i class="fas fa-cart-plus me-2"></i>
                    Thêm vào giỏ hàng
                </button>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Sản phẩm hiện tại không có sẵn
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3>Đánh giá từ khách hàng</h3>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Viết đánh giá</h5>
                        <form id="review-form">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Đánh giá:</label>
                                <div class="rating">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>">
                                        <label for="star<?= $i ?>" class="star">★</label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nhận xét:</label>
                                <textarea class="form-control" name="comment" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (empty($reviews)): ?>
                <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6><?= htmlspecialchars($review['user_name']) ?></h6>
                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></small>
                            </div>
                            <div class="mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <?php if ($review['comment']): ?>
                                <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <h3>Sản phẩm liên quan</h3>
                <div class="row">
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <?php if ($relatedProduct['id'] != $product['id']): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="card h-100">
                                    <?php if ($relatedProduct['image']): ?>
                                        <img src="/public/uploads/<?= htmlspecialchars($relatedProduct['image']) ?>" 
                                             class="card-img-top" style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-utensils fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title">
                                            <a href="/product?id=<?= $relatedProduct['id'] ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($relatedProduct['name']) ?>
                                            </a>
                                        </h6>
                                        
                                        <div class="mt-auto">
                                            <div class="mb-2">
                                                <?php if ($relatedProduct['sale_price']): ?>
                                                    <span class="text-muted text-decoration-line-through small">
                                                        <?= number_format($relatedProduct['price']) ?>đ
                                                    </span><br>
                                                    <span class="text-danger fw-bold">
                                                        <?= number_format($relatedProduct['sale_price']) ?>đ
                                                    </span>
                                                <?php else: ?>
                                                    <span class="fw-bold">
                                                        <?= number_format($relatedProduct['price']) ?>đ
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if ($relatedProduct['status'] === 'available'): ?>
                                                <button class="btn btn-sm btn-primary w-100" onclick="addToCart(<?= $relatedProduct['id'] ?>)">
                                                    Thêm vào giỏ
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary w-100" disabled>
                                                    Hết hàng
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating input {
    display: none;
}

.rating label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    margin-right: 0.1rem;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffc107;
}
</style>

<script>
function changeQuantity(delta) {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    let newValue = currentValue + delta;
    
    if (newValue >= 1 && newValue <= 99) {
        quantityInput.value = newValue;
    }
}

function addToCart(productId) {
    const quantity = document.getElementById('quantity').value;
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=${quantity}`
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

// Review form submission
document.getElementById('review-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/add-review', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'Có lỗi xảy ra, vui lòng thử lại');
    });
});

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
