<?php $title = 'Giỏ hàng - Sắc Việt'; ?>

<style>
/* Cart page brand colors */
.cart-page h2 {
    color: #8b0000 !important;
}

.cart-page h2 i {
    color: #ffc107;
}

.cart-page .card {
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.cart-page .card-header {
    background: linear-gradient(135deg, #8b0000, #5a0000);
    color: white;
    border: none;
}

.cart-page .btn-primary {
    background-color: #8b0000 !important;
    border-color: #8b0000 !important;
}

.cart-page .btn-primary:hover {
    background-color: #5a0000 !important;
    border-color: #5a0000 !important;
}

.cart-page .btn-outline-primary {
    color: #8b0000 !important;
    border-color: #8b0000 !important;
}

.cart-page .btn-outline-primary:hover {
    background-color: #8b0000 !important;
    color: white !important;
}

.cart-page .btn-outline-secondary {
    color: #000 !important;
    border-color: #000 !important;
}

.cart-page .btn-outline-secondary:hover {
    background-color: #ffc107 !important;
    color: #000 !important;
}

.cart-page .item-total {
    color: #8b0000 !important;
}

.cart-page #total-amount {
    color: #8b0000 !important;
}

.cart-page .text-decoration-none {
    color: #8b0000 !important;
}

.cart-page .text-decoration-none:hover {
    color: #ffc107 !important;
}
</style>

<div class="container py-5 cart-page">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-shopping-cart me-2"></i>
                Giỏ hàng của bạn
            </h2>
            
            <?php if (empty($cartItems)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h4>Giỏ hàng trống</h4>
                    <p class="text-muted">Hãy thêm một số sản phẩm vào giỏ hàng của bạn.</p>
                    <a href="/products" class="btn btn-primary">
                        <i class="fas fa-shirt me-2"></i>
                        Xem sản phẩm
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <?php foreach ($cartItems as $item): ?>
                                    <div class="border-bottom py-3" data-product-id="<?= $item['product']['id'] ?>" data-key="<?= $item['key'] ?>">
                                        <div class="row align-items-start">
                                            <!-- Image -->
                                            <div class="col-md-2">
                                                <?php if (!empty($item['product']['image_url'])): ?>
                                                    <img src="<?= htmlspecialchars($item['product']['image_url']) ?>" 
                                                         class="img-fluid rounded" alt="<?= htmlspecialchars($item['product']['name']) ?>"
                                                         style="height: 80px; width: 80px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                         style="height: 80px; width: 80px;">
                                                        <i class="fas fa-utensils text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Product Info -->
                                            <div class="col-md-3">
                                                <h6 class="mb-1">
                                                    <a href="/product/<?= $item['product']['id'] ?>" class="text-decoration-none">
                                                        <?= htmlspecialchars($item['product']['name']) ?>
                                                    </a>
                                                </h6>
                                                <small class="text-muted"><?= htmlspecialchars($item['product']['category_name'] ?? '') ?></small>
                                                
                                                <!-- Color and Size Display -->
                                                <div class="mt-2">
                                                    <?php if (!empty($item['color'])): ?>
                                                        <span class="badge bg-secondary me-1">
                                                            <i class="fas fa-palette me-1"></i><?= htmlspecialchars($item['color']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($item['size'])): ?>
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-ruler me-1"></i><?= htmlspecialchars($item['size']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <!-- Unit Price & Quantity -->
                                            <div class="col-md-3">
                                                <div class="mb-2">
                                                    <small class="text-muted d-block">Giá: <span class="fw-bold"><?= number_format($item['product']['price']) ?>đ</span></small>
                                                </div>
                                                <div class="input-group input-group-sm" style="width: 130px;">
                                                    <button class="btn btn-outline-secondary" type="button" 
                                                            onclick="updateQuantity('<?= $item['key'] ?>', -1)">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="form-control text-center quantity-input" 
                                                           value="<?= $item['quantity'] ?>" min="1" max="5"
                                                           data-key="<?= $item['key'] ?>"
                                                           onchange="setQuantity('<?= $item['key'] ?>', this.value)">
                                                    <button class="btn btn-outline-secondary" type="button" 
                                                            onclick="updateQuantity('<?= $item['key'] ?>', 1)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <?php if ($item['quantity'] >= 5): ?>
                                                    <small class="text-danger d-block mt-2">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Số lượng tối đa
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Total & Delete -->
                                            <div class="col-md-2 text-end">
                                                <div class="mb-2">
                                                    <span class="fw-bold item-total h6">
                                                        <?= number_format($item['item_total'] ?? 0) ?>đ
                                                    </span>
                                                </div>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="removeItem('<?= $item['key'] ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <?php if ($item['quantity'] >= 5): ?>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <small class="text-danger">
                                                        Nếu cần mua nhiều hơn, vui lòng <a href="/contact" class="text-danger fw-bold">liên hệ người bán</a>
                                                    </small>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                                
                                <div class="mt-3">
                                    <button class="btn btn-outline-danger" onclick="clearCart()">
                                        <i class="fas fa-trash me-2"></i>
                                        Xóa giỏ hàng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Tóm tắt đơn hàng</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Tổng tiền:</span>
                                    <span class="fw-bold h5" id="total-amount"><?= number_format($total ?? 0) ?>đ</span>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <a href="/checkout" class="btn btn-primary btn-lg">
                                            <i class="fas fa-credit-card me-2"></i>
                                            Thanh toán
                                        </a>
                                    <?php else: ?>
                                        <a href="/login" class="btn btn-primary btn-lg">
                                            <i class="fas fa-sign-in-alt me-2"></i>
                                            Đăng nhập để thanh toán
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="/" class="btn btn-outline-primary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Tiếp tục mua sắm
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function updateQuantity(cartKey, delta) {
    const row = document.querySelector(`[data-key="${cartKey}"]`);
    const quantityInput = row.querySelector('.quantity-input');
    let currentValue = parseInt(quantityInput.value);
    let newValue = currentValue + delta;
    
    if (newValue >= 1 && newValue <= 99) {
        quantityInput.value = newValue;
        updateCart(cartKey, newValue);
    } else if (newValue <= 0) {
        removeItem(cartKey);
    }
}

function setQuantity(cartKey, quantity) {
    quantity = parseInt(quantity);
    
    // Kiểm tra giới hạn tối đa 5
    if (quantity > 5) {
        alert('Tối đa chỉ có thể mua 5 sản phẩm. Nếu cần mua nhiều hơn, vui lòng liên hệ người bán.');
        // Reset về giá trị cũ
        const input = document.querySelector(`input[data-key="${cartKey}"]`);
        input.value = input.defaultValue;
        return;
    }
    
    if (quantity >= 1 && quantity <= 5) {
        updateCart(cartKey, quantity);
    } else if (quantity <= 0) {
        removeItem(cartKey);
    }
}

function updateCart(cartKey, quantity) {
    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `cart_key=${cartKey}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cart-count').textContent = data.cartCount;
            location.reload(); // Reload to update totals
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'Có lỗi xảy ra, vui lòng thử lại');
    });
}

function removeItem(cartKey) {
    if (confirm('Bạn có chắc muốn xóa món này khỏi giỏ hàng?')) {
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `cart_key=${cartKey}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cart-count').textContent = data.cartCount;
                location.reload();
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            showAlert('danger', 'Có lỗi xảy ra, vui lòng thử lại');
        });
    }
}

function clearCart() {
    if (confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
        fetch('/cart/clear', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cart-count').textContent = data.cartCount;
                location.reload();
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            showAlert('danger', 'Có lỗi xảy ra, vui lòng thử lại');
        });
    }
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
