<?php $title = 'Quản lý Đơn hàng - Admin'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản lý Đơn hàng</h1>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả</option>
                        <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                        <option value="confirmed" <?= ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                        <option value="preparing" <?= ($filters['status'] ?? '') === 'preparing' ? 'selected' : '' ?>>Đang chuẩn bị</option>
                        <option value="delivering" <?= ($filters['status'] ?? '') === 'delivering' ? 'selected' : '' ?>>Đang giao</option>
                        <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                        <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Từ ngày</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Đến ngày</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Lọc
                    </button>
                    <a href="/admin/orders" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>Xóa lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                                         <thead>
                         <tr>
                             <th>Mã đơn</th>
                             <th>Khách hàng</th>
                             <th>Tổng tiền</th>
                             <th>Trạng thái</th>
                             <th>Ngày đặt</th>
                             <th>Thao tác</th>
                         </tr>
                     </thead>
                    <tbody>
                                                 <?php if (empty($orders)): ?>
                             <tr>
                                 <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Không có đơn hàng nào</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <strong>#<?= $order['id'] ?></strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($order['delivery_name']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($order['delivery_phone']) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-primary"><?= number_format($order['total_amount']) ?>đ</strong>
                                    </td>
                                    <td>
                                        <?php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'confirmed' => 'info',
                                            'preparing' => 'primary',
                                            'delivering' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Chờ xác nhận',
                                            'confirmed' => 'Đã xác nhận',
                                            'preparing' => 'Đang chuẩn bị',
                                            'delivering' => 'Đang giao',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                        $color = $statusColors[$order['status']] ?? 'secondary';
                                        $label = $statusLabels[$order['status']] ?? $order['status'];
                                        ?>
                                                                                 <span class="badge bg-<?= $color ?>"><?= $label ?></span>
                                     </td>
                                     <td>
                                         <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                     </td>
                                    <td>
                                        <a href="/admin/orders/<?= $order['id'] ?>" class="btn btn-sm btn-primary" title="Xem chi tiết đơn hàng">
                                            <i class="fas fa-eye me-1"></i>Xem
                                        </a>
                                        <?php if (in_array($order['status'], ['completed', 'cancelled'])): ?>
                                             <button class="btn btn-sm btn-outline-danger ms-1" 
                                                     onclick="deleteOrder(<?= $order['id'] ?>)" 
                                                     title="Xóa đơn hàng">
                                                 <i class="fas fa-trash"></i>
                                             </button>
                                         <?php endif; ?>
                                     </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> 

<script>
function deleteOrder(orderId) {
    if (confirm('Bạn có chắc muốn xóa đơn hàng này? Hành động này không thể hoàn tác.')) {
        fetch(`/admin/orders/${orderId}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
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