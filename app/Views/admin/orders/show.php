<?php $title = 'Chi tiết Đơn hàng #' . $order['id'] . ' - Admin'; ?>

<style>
/* Import Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

body {
    color: #1a202c;
}

.order-detail-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.info-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.info-card .card-header {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border: none;
    padding: 1.25rem;
    font-weight: 700;
    font-size: 1.15rem;
    color: #1a202c;
}

.info-card .card-body {
    padding: 1.5rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.1rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: #4a5568;
    font-weight: 600;
    font-size: 1rem;
}

.info-value {
    color: #1a202c;
    font-weight: 700;
    font-size: 1.05rem;
}

.status-badge {
    padding: 0.6rem 1.2rem;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.95rem;
    display: inline-block;
    letter-spacing: 0.3px;
}

.product-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.update-status-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 15px;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.update-status-card .card-header {
    background: rgba(255,255,255,0.1);
    border: none;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
}

.update-status-card .card-body {
    padding: 1.5rem;
}

.form-select-custom {
    border-radius: 10px;
    border: 2px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.98);
    padding: 0.85rem;
    font-weight: 600;
    font-size: 1rem;
    color: #1a202c;
    transition: all 0.3s ease;
}

.form-select-custom:focus {
    border-color: white;
    box-shadow: 0 0 0 0.2rem rgba(255,255,255,0.25);
}

.form-label {
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 0.75rem;
}

.btn-update {
    background: white;
    color: #667eea;
    border: none;
    padding: 0.85rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 1.05rem;
    transition: all 0.3s ease;
}

.btn-update:hover {
    background: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    color: #667eea;
}

.payment-method-badge {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.95rem;
    display: inline-block;
    letter-spacing: 0.3px;
}

.table-custom {
    border-radius: 10px;
    overflow: hidden;
}

.table-custom thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.table-custom thead th {
    border: none;
    padding: 1.1rem;
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.table-custom tbody tr {
    transition: background 0.2s ease;
}

.table-custom tbody tr:hover {
    background: #f7fafc;
}

.table-custom tbody td {
    padding: 1.1rem;
    vertical-align: middle;
    font-size: 1rem;
    color: #2d3748;
    font-weight: 500;
}

.table-custom tbody td .fw-bold {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1a202c;
}

.table-custom tbody td strong {
    font-weight: 700;
    color: #667eea;
}

.total-amount {
    font-size: 2rem;
    color: #667eea;
    font-weight: 800;
    letter-spacing: -0.5px;
}

.order-detail-header h1 {
    font-weight: 800;
    letter-spacing: -0.5px;
    font-size: 2rem;
}

.order-detail-header p {
    font-size: 1.05rem;
    font-weight: 500;
}

small.text-muted {
    font-size: 0.9rem;
    font-weight: 500;
    color: #718096 !important;
}
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="order-detail-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-receipt me-2"></i>
                    Đơn hàng #<?= $order['id'] ?>
                </h1>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Đặt ngày <?= date('d/m/Y lúc H:i', strtotime($order['created_at'])) ?>
                </p>
            </div>
            <a href="/admin/orders" class="btn btn-light btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Order Information -->
            <div class="info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Thông tin đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Mã đơn hàng</span>
                        <span class="info-value">#<?= $order['id'] ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày đặt</span>
                        <span class="info-value"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tổng tiền</span>
                        <span class="total-amount"><?= number_format($order['total_amount']) ?>đ</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Trạng thái</span>
                        <span>
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
                            $orderStatus = $order['status'] ?? 'pending';
                            $color = $statusColors[$orderStatus] ?? 'secondary';
                            $label = $statusLabels[$orderStatus] ?? ucfirst($orderStatus);
                            ?>
                            <span class="badge bg-<?= $color ?> status-badge"><?= $label ?></span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phương thức thanh toán</span>
                        <span>
                            <?php
                            $paymentMethod = $order['payment_method'] ?? 'cod';
                            $paymentMethodLabels = [
                                'cod' => 'Thanh toán khi nhận hàng (COD)',
                                'bank_transfer' => 'Chuyển khoản ngân hàng'
                            ];
                            $paymentMethodLabel = $paymentMethodLabels[$paymentMethod] ?? 'COD';
                            ?>
                            <span class="payment-method-badge">
                                <i class="fas fa-<?= $paymentMethod === 'bank_transfer' ? 'university' : 'money-bill-wave' ?> me-2"></i>
                                <?= $paymentMethodLabel ?>
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Cập nhật lần cuối</span>
                        <span class="info-value"><?= date('d/m/Y H:i', strtotime($order['updated_at'] ?? $order['created_at'])) ?></span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Chi tiết sản phẩm
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
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
                                                    <img src="<?= htmlspecialchars($item['image']) ?>" 
                                                         alt="<?= htmlspecialchars($item['name']) ?>" 
                                                         class="product-image me-3">
                                                <?php endif; ?>
                                                <span class="fw-bold"><?= htmlspecialchars($item['name']) ?></span>
                                            </div>
                                        </td>
                                        <td><?= number_format($item['price']) ?>đ</td>
                                        <td>
                                            <span class="badge bg-secondary"><?= $item['quantity'] ?></span>
                                        </td>
                                        <td><strong class="text-primary"><?= number_format($item['price'] * $item['quantity']) ?>đ</strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Thông tin khách hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Tên</span>
                        <span class="info-value"><?= htmlspecialchars($order['delivery_name']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số điện thoại</span>
                        <span class="info-value">
                            <i class="fas fa-phone me-2"></i><?= htmlspecialchars($order['delivery_phone']) ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Địa chỉ</span>
                        <span class="info-value text-end"><?= htmlspecialchars($order['delivery_address']) ?></span>
                    </div>
                    <?php if (!empty($order['notes'])): ?>
                        <div class="info-row">
                            <span class="info-label">Ghi chú</span>
                            <span class="info-value text-end"><?= htmlspecialchars($order['notes']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Update Status -->
            <div class="update-status-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Cập nhật trạng thái
                    </h5>
                </div>
                <div class="card-body">
                    <form id="updateStatusForm">
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Trạng thái mới</label>
                            <select class="form-select form-select-custom" id="status" name="status" required>
                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>⏳ Chờ xác nhận</option>
                                <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>✅ Đã xác nhận</option>
                                <option value="preparing" <?= $order['status'] === 'preparing' ? 'selected' : '' ?>>👨‍🍳 Đang chuẩn bị</option>
                                <option value="delivering" <?= $order['status'] === 'delivering' ? 'selected' : '' ?>>🚚 Đang giao</option>
                                <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>🎉 Hoàn thành</option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>❌ Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-update w-100">
                            <i class="fas fa-save me-2"></i>Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cập nhật trạng thái đơn hàng
document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Disable button và hiển thị loading
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang cập nhật...';
    
    const formData = new FormData(this);
    const status = formData.get('status');
    
    fetch('/admin/orders/<?= $order['id'] ?>/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'status=' + encodeURIComponent(status)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Response is not valid JSON:', text);
                throw new Error('Server returned invalid response');
            }
        });
    })
    .then(data => {
        if (data.success) {
            // Hiển thị thông báo thành công
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                Cập nhật trạng thái thành công!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            // Reload sau 1 giây
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Không thể cập nhật trạng thái');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Hiển thị thông báo lỗi
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        alertDiv.style.zIndex = '9999';
        alertDiv.innerHTML = `
            <i class="fas fa-exclamation-circle me-2"></i>
            Có lỗi xảy ra: ${error.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
    })
    .finally(() => {
        // Khôi phục button
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});
</script>
