<?php $title = 'Thanh toán - Sắc Việt'; ?>

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
                                       value="<?= htmlspecialchars($_POST['delivery_name'] ?? $_SESSION['user_name'] ?? '') ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="delivery_phone" class="form-label">Số điện thoại *</label>
                                <input type="tel" class="form-control" id="delivery_phone" name="delivery_phone" 
                                       value="<?= htmlspecialchars($_POST['delivery_phone'] ?? $savedInfo['phone'] ?? '') ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Địa chỉ giao hàng *</label>
                            <textarea class="form-control" id="delivery_address" name="delivery_address" 
                                      rows="3" required><?= htmlspecialchars($_POST['delivery_address'] ?? $savedInfo['address'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú (tùy chọn)</label>
                            <textarea class="form-control" id="note" name="note" 
                                      rows="2" placeholder="Ghi chú đặc biệt cho đơn hàng..."><?= htmlspecialchars($_POST['note'] ?? '') ?></textarea>
                        </div>
                        
                        <h5 class="mb-3 mt-4">Phương thức thanh toán</h5>
                        
                        <div class="mb-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" 
                                       value="cod" <?= (!isset($_POST['payment_method']) || $_POST['payment_method'] === 'cod') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="payment_cod">
                                    <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                    <strong>Thanh toán khi nhận hàng (COD)</strong>
                                    <div class="text-muted small mt-1">Thanh toán bằng tiền mặt khi nhận hàng</div>
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_bank" 
                                       value="bank_transfer" <?= (isset($_POST['payment_method']) && $_POST['payment_method'] === 'bank_transfer') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="payment_bank">
                                    <i class="fas fa-university me-2 text-primary"></i>
                                    <strong>Chuyển khoản ngân hàng</strong>
                                    <div class="text-muted small mt-1">Chuyển khoản trước khi nhận hàng</div>
                                </label>
                            </div>
                            
                            <div id="bank_info" class="alert alert-info mt-3" style="display: none;">
                           <!--  -->
                            </div>
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
                            <i class="fas fa-shield-alt me-1"></i>
                            Thông tin của bạn được bảo mật
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

<script>
// Toggle bank info when payment method changes
document.addEventListener('DOMContentLoaded', function() {
    const paymentCod = document.getElementById('payment_cod');
    const paymentBank = document.getElementById('payment_bank');
    const bankInfo = document.getElementById('bank_info');
    
    function toggleBankInfo() {
        if (paymentBank.checked) {
            bankInfo.style.display = 'block';
        } else {
            bankInfo.style.display = 'none';
        }
    }
    
    paymentCod.addEventListener('change', toggleBankInfo);
    paymentBank.addEventListener('change', toggleBankInfo);
    
    // Initial check
    toggleBankInfo();
});
</script>
