<?php $title = 'Giỏ hàng - Restaurant Order System'; ?>

<div class="container py-5">
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
                    <p class="text-muted">Hãy thêm một số món ăn vào giỏ hàng của bạn.</p>
                    <a href="/" class="btn btn-primary">
                        <i class="fas fa-utensils me-2"></i>
                        Xem thực đơn
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <?php foreach ($cartItems as $item): ?>
                                    <div class="row align-items-center border-bottom py-3" data-product-id="<?= $item['product']['id'] ?>">
                                        <div class="col-md-2">
                                            <?php if ($item['product']['image']): ?>
                                                <img src="/public/uploads/<?= htmlspecialchars($item['product']['image']) ?>" 
                                                     class="img-fluid rounded" alt="<?= htmlspecialchars($item['product']['name']) ?>"
                                                     style="height: 80px; width: 80px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="height: 80px; width: 80px;">
                                                    <i class="fas fa-utensils text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <h6 class="mb-1">
                                                <a href="/product?id=<?= $item['product']['id'] ?>" class="text-decoration-none">
                                                    <?= htmlspecialchars($item['product']['name']) ?>
                                                </a>
                                            </h6>
                                            <small class="text-muted"><?= htmlspecialchars($item['product']['unit']) ?></small>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <span class="fw-bold">
                                                <?= number_format($item['price']) ?>đ
                                            </span>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div class="input-group input-group-sm" style="width: 120px;">
                                                <button class="btn btn-outline-secondary" type="button" 
                                                        onclick="updateQuantity(<?= $item['product']['id'] ?>, -1)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" class="form-control text-center quantity-input" 
                                                       value="<?= $item['quantity'] ?>" min="1" max="99"
                                                       onchange="setQuantity(<?= $item['product']['id'] ?>, this.value)">
                                                <button class="btn btn-outline-secondary" type="button" 
                                                        onclick="updateQuantity(<?= $item['product']['id'] ?>, 1)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-1 text-end">
                                            <span class="fw-bold item-total">
                                                <?= number_format($item['total']) ?>đ
                                            </span>
                                        </div>
                                        
                                        <div class="col-md-1 text-end">
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="removeItem(<?= $item['product']['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
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
                                    <span class="fw-bold h5" id="total-amount"><?= number_format($total) ?>đ</span>
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
function updateQuantity(productId, delta) {
    const row = document.querySelector(`[data-product-id="${productId}"]`);
    const quantityInput = row.querySelector('.quantity-input');
    let currentValue = parseInt(quantityInput.value);
    let newValue = currentValue + delta;
    
    if (newValue >= 1 && newValue <= 99) {
        quantityInput.value = newValue;
        updateCart(productId, newValue);
    } else if (newValue <= 0) {
        removeItem(productId);
    }
}

function setQuantity(productId, quantity) {
    quantity = parseInt(quantity);
    if (quantity >= 1 && quantity <= 99) {
        updateCart(productId, quantity);
    } else if (quantity <= 0) {
        removeItem(productId);
    }
}

function updateCart(productId, quantity) {
    fetch('/cart/update', {
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
            location.reload(); // Reload to update totals
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'Có lỗi xảy ra, vui lòng thử lại');
    });
}

function removeItem(productId) {
    if (confirm('Bạn có chắc muốn xóa món này khỏi giỏ hàng?')) {
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`
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
