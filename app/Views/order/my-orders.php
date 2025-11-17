<?php $title = 'Đơn hàng của tôi - Restaurant Order System'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-receipt me-2"></i>
                Đơn hàng của tôi
            </h2>
            
            <?php if (empty($orders)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                    <h4>Chưa có đơn hàng nào</h4>
                    <p class="text-muted">Bạn chưa có đơn hàng nào. Hãy đặt món ngay!</p>
                    <a href="/" class="btn btn-primary">
                        <i class="fas fa-utensils me-2"></i>
                        Xem thực đơn
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($orders as $order): ?>
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0">Đơn hàng #<?= $order['id'] ?></h5>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                        </small>
                                    </div>
                                    
                                    <div class="text-end">
                                        <?php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'preparing' => 'info',
                                            'delivering' => 'primary',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        
                                        $statusLabels = [
                                            'pending' => 'Chờ xác nhận',
                                            'preparing' => 'Đang chuẩn bị',
                                            'delivering' => 'Đang giao hàng',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                        
                                        $color = $statusColors[$order['status']] ?? 'secondary';
                                        $label = $statusLabels[$order['status']] ?? $order['status'];
                                        ?>
                                        <span class="badge bg-<?= $color ?> fs-6"><?= $label ?></span>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Thông tin giao hàng:</h6>
                                            <p class="mb-1"><strong>Người nhận:</strong> <?= htmlspecialchars($order['delivery_name'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Điện thoại:</strong> <?= htmlspecialchars($order['delivery_phone'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['delivery_address'] ?? 'N/A') ?></p>
                                            
                                            <p class="mb-0">
                                                <strong>Ghi chú:</strong> 
                                                <?php if (!empty($order['notes']) && trim($order['notes']) !== ''): ?>
                                                    <?= htmlspecialchars($order['notes']) ?>
                                                <?php else: ?>
                                                    <em class="text-muted">Không có ghi chú</em>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        
                                        <div class="col-md-6 text-md-end">
                                            <h5 class="text-primary">
                                                Tổng tiền: <?= number_format($order['total_amount']) ?>đ
                                            </h5>
                                            
                                            <div class="mt-3">
                                                <a href="/orders/<?= $order['id'] ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Chi tiết
                                                </a>
                                                
                                                <?php if ($order['status'] === 'pending'): ?>
                                                    <button class="btn btn-outline-danger btn-sm ms-2" 
                                                            onclick="cancelOrder(<?= $order['id'] ?>)">
                                                        <i class="fas fa-times me-1"></i>
                                                        Hủy đơn
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
        fetch('/orders/cancel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}`
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
