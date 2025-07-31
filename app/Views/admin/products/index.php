<?php $title = 'Quản lý sản phẩm - Admin Panel'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Quản lý sản phẩm</h2>
            <a href="/admin/products/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Thêm sản phẩm
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="/admin/products" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Tìm kiếm sản phẩm..." 
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <select class="form-select" name="category_id">
                            <option value="">Tất cả danh mục</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" 
                                        <?= ($_GET['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <select class="form-select" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="available" <?= ($_GET['status'] ?? '') === 'available' ? 'selected' : '' ?>>
                                Có sẵn
                            </option>
                            <option value="out_of_stock" <?= ($_GET['status'] ?? '') === 'out_of_stock' ? 'selected' : '' ?>>
                                Hết hàng
                            </option>
                            <option value="discontinued" <?= ($_GET['status'] ?? '') === 'discontinued' ? 'selected' : '' ?>>
                                Ngừng bán
                            </option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-search me-1"></i>
                            Tìm kiếm
                        </button>
                        <a href="/admin/products" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($products)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                        <h4>Không tìm thấy sản phẩm nào</h4>
                        <p class="text-muted">Hãy thêm sản phẩm mới hoặc thay đổi điều kiện tìm kiếm.</p>
                        <a href="/admin/products/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Thêm sản phẩm đầu tiên
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th width="150">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td>
                                            <?php if ($product['image']): ?>
                                                <img src="/public/uploads/<?= htmlspecialchars($product['image']) ?>" 
                                                     class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-utensils text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td>
                                            <h6 class="mb-0"><?= htmlspecialchars($product['name']) ?></h6>
                                            <small class="text-muted"><?= htmlspecialchars($product['unit']) ?></small>
                                        </td>
                                        
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars($product['category_name']) ?>
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <?php if ($product['sale_price']): ?>
                                                <span class="text-muted text-decoration-line-through small">
                                                    <?= number_format($product['price']) ?>đ
                                                </span><br>
                                                <span class="text-danger fw-bold">
                                                    <?= number_format($product['sale_price']) ?>đ
                                                </span>
                                            <?php else: ?>
                                                <span class="fw-bold">
                                                    <?= number_format($product['price']) ?>đ
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td>
                                            <?php
                                            $statusColors = [
                                                'available' => 'success',
                                                'out_of_stock' => 'warning',
                                                'discontinued' => 'danger'
                                            ];
                                            
                                            $statusLabels = [
                                                'available' => 'Có sẵn',
                                                'out_of_stock' => 'Hết hàng',
                                                'discontinued' => 'Ngừng bán'
                                            ];
                                            
                                            $color = $statusColors[$product['status']] ?? 'secondary';
                                            $label = $statusLabels[$product['status']] ?? $product['status'];
                                            ?>
                                            <span class="badge bg-<?= $color ?>"><?= $label ?></span>
                                        </td>
                                        
                                        <td>
                                            <?= date('d/m/Y', strtotime($product['created_at'])) ?>
                                        </td>
                                        
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="/admin/products/<?= $product['id'] ?>/edit" 
                                                   class="btn btn-outline-primary" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <button class="btn btn-outline-danger" 
                                                        onclick="deleteProduct(<?= $product['id'] ?>)" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination info -->
                    <div class="mt-3">
                        <small class="text-muted">
                            Hiển thị <?= count($products) ?> / <?= $totalProducts ?> sản phẩm
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function deleteProduct(productId) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này? Hành động này không thể hoàn tác!')) {
        fetch('/admin/products/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`
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
