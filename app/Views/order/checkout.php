<?php $title = 'Thanh toán - Restaurant Order System'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Thông tin thanh toán
                    </h4>
                </div>
                
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/checkout">
                        <h5 class="mb-3">Thông tin giao hàng</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="delivery_name" class="form-label">Tên người nhận *</label>
                                <input type="text" class="form-control" id="delivery_name" name="delivery_name" 
                                       value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="delivery_phone" class="form-label">Số điện thoại *</label>
                                <input type="tel" class="form-control" id="delivery_phone" name="delivery_phone" 
                                       value="<?= htmlspecialchars($_POST['delivery_phone'] ?? '') ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Địa chỉ giao hàng *</label>
                            <textarea class="form-control" id="delivery_address" name="delivery_address" 
                                      rows="3" required><?= htmlspecialchars($_POST['delivery_address'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="note" class="form-label">Ghi chú (tùy chọn)</label>
                            <textarea class="form-control" id="note" name="note" 
                                      rows="2" placeholder="Ghi chú đặc biệt cho đơn hàng..."><?= htmlspecialchars($_POST['note'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check me-2"></i>
                                Đặt hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Đơn hàng của bạn</h5>
                </div>
                
                <div class="card-body">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0"><?= htmlspecialchars($item['product']['name']) ?></h6>
                                <small class="text-muted">
                                    <?= number_format($item['price']) ?>đ × <?= $item['quantity'] ?>
                                </small>
                            </div>
                            <span class="fw-bold"><?= number_format($item['total']) ?>đ</span>
                        </div>
                    <?php endforeach; ?>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tổng cộng:</h5>
                        <h5 class="mb-0 text-primary"><?= number_format($total) ?>đ</h5>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Thanh toán khi nhận hàng (COD)
                        </small>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="/cart" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-2"></i>
                    Quay lại giỏ hàng
                </a>
            </div>
        </div>
    </div>
</div>
