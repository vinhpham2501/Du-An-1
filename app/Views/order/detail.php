<?php $title = 'Chi tiết đơn hàng #' . ($order['id'] ?? '') . ' - Sắc Việt'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/my-orders">Đơn hàng của tôi</a></li>
                    <li class="breadcrumb-item active">Đơn hàng #<?= $order['id'] ?? '' ?></li>
                </ol>
            </nav>
            
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>
                                Đơn hàng #<?= $order['id'] ?? '' ?>
                            </h4>
                            <small class="text-muted">
                                Đặt lúc: <?= date('d/m/Y H:i', strtotime($order['created_at'] ?? '')) ?>
                            </small>
                        </div>
                        
                        <div class="col-md-6 text-md-end">
                            <?php
                            $statusColors = [
                                'pending' => 'warning',
                                'confirmed' => 'secondary',
                                'preparing' => 'info',
                                'delivering' => 'primary',
                                'completed' => 'success',
                                'cancelled' => 'danger'
                            ];
                            
                            $statusLabels = [
                                'pending' => 'Chờ xác nhận',
                                'confirmed' => 'Đã xác nhận',
                                'preparing' => 'Đang chuẩn bị',
                                'delivering' => 'Đang giao hàng',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã hủy'
                            ];
                            
                            $color = $statusColors[$order['status'] ?? ''] ?? 'secondary';
                            $label = $statusLabels[$order['status'] ?? ''] ?? ($order['status'] ?? '');
                            ?>
                            <span class="badge bg-<?= $color ?> fs-6"><?= $label ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Thông tin giao hàng</h5>
                            <div class="border p-3 rounded">
                                <p class="mb-2"><strong>Người nhận:</strong> <?= htmlspecialchars($order['delivery_name'] ?? 'N/A') ?></p>
                                <p class="mb-2"><strong>Điện thoại:</strong> <?= htmlspecialchars($order['delivery_phone'] ?? 'N/A') ?></p>
                                <p class="mb-0"><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['delivery_address'] ?? 'N/A') ?></p>
                                
                                <hr>
                                <p class="mb-0">
                                    <strong>Ghi chú:</strong> 
                                    <?php if (!empty($order['notes']) && trim($order['notes']) !== ''): ?>
                                        <?= htmlspecialchars($order['notes']) ?>
                                    <?php else: ?>
                                        <em class="text-muted">Không có ghi chú</em>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Trạng thái đơn hàng</h5>
                            <div class="border p-3 rounded">
                                <div class="timeline">
                                    <div class="timeline-item <?= in_array($order['status'] ?? '', ['pending', 'preparing', 'delivering', 'completed']) ? 'active' : '' ?>">
                                        <i class="fas fa-clock"></i>
                                        <span>Chờ xác nhận</span>
                                    </div>
                                    
                                    <?php if ($order['status'] ?? '' !== 'cancelled'): ?>
                                        <div class="timeline-item <?= in_array($order['status'] ?? '', ['preparing', 'delivering', 'completed']) ? 'active' : '' ?>">
                                            <i class="fas fa-box"></i>
                                            <span>Đang chuẩn bị</span>
                                        </div>
                                        
                                        <div class="timeline-item <?= in_array($order['status'] ?? '', ['delivering', 'completed']) ? 'active' : '' ?>">
                                            <i class="fas fa-truck"></i>
                                            <span>Đang giao hàng</span>
                                        </div>
                                        
                                        <div class="timeline-item <?= $order['status'] ?? '' === 'completed' ? 'active' : '' ?>">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Hoàn thành</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="timeline-item active text-danger">
                                            <i class="fas fa-times-circle"></i>
                                            <span>Đã hủy</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($order['status'] ?? '' === 'pending'): ?>
                                    <div class="mt-3">
                                        <button class="btn btn-outline-danger btn-sm" onclick="cancelOrder(<?= $order['id'] ?? '' ?>)">
                                            <i class="fas fa-times me-1"></i>
                                            Hủy đơn hàng
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5>Chi tiết sản phẩm</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderItems as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($item['image'])): ?>
                                                    <?php 
                                                    // Kiểm tra xem có phải URL hay file local
                                                    $imageSrc = (strpos($item['image'], 'http') === 0) 
                                                        ? $item['image'] 
                                                        : '/uploads/' . $item['image'];
                                                    ?>
                                                    <img src="<?= htmlspecialchars($imageSrc) ?>" 
                                                         class="me-3 rounded" style="width: 50px; height: 50px; object-fit: cover;"
                                                         alt="<?= htmlspecialchars($item['name'] ?? '') ?>"
                                                         onerror="this.style.display='none'">
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($item['name'] ?? 'N/A') ?></h6>
                                                    <?php if (($order['status'] ?? '') === 'completed' && !empty($item['product_id'])): ?>
                                                        <a href="/product/<?= $item['product_id'] ?>#reviews" class="btn btn-sm btn-outline-primary mt-1">
                                                            Đánh giá sản phẩm
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= number_format($item['price'] ?? 0) ?>đ</td>
                                        <td><?= $item['quantity'] ?? 0 ?></td>
                                        <td class="fw-bold"><?= number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0)) ?>đ</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td class="fw-bold h5 text-primary"><?= number_format($order['total_amount'] ?? 0) ?>đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <a href="/my-orders" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Quay lại danh sách đơn hàng
                        </a>
                        
                        <?php if ($order['status'] ?? '' === 'completed'): ?>
                            <button class="btn btn-primary ms-2" onclick="reorder()">
                                <i class="fas fa-redo me-2"></i>
                                Đặt lại đơn hàng này
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    list-style: none;
    padding: 0;
}

.timeline-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    color: #6c757d;
}

.timeline-item.active {
    color: #28a745;
    font-weight: 500;
}

.timeline-item i {
    margin-right: 0.5rem;
    width: 20px;
}
</style>

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

function reorder() {
    if (confirm('Bạn có muốn đặt lại tất cả sản phẩm trong đơn hàng này?')) {
        // Add all items to cart
        <?php foreach ($orderItems as $item): ?>
            <?php if (!empty($item['product_id']) && !empty($item['quantity'])): ?>
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=<?= $item['product_id'] ?>&quantity=<?= $item['quantity'] ?>`
            });
            <?php endif; ?>
        <?php endforeach; ?>
        
        setTimeout(() => {
            showAlert('success', 'Đã thêm tất cả sản phẩm vào giỏ hàng');
            setTimeout(() => {
                window.location.href = '/cart';
            }, 1000);
        }, 500);
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
