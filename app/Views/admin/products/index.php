<?php use App\Helpers\ImageHelper; $title = 'Quản lý sản phẩm - Admin Panel'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Quản lý sản phẩm</h2>
            <a href="/admin/products/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm sản phẩm
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
                               placeholder="Tên sản phẩm...">
                    </div>
                    <div class="col-md-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-select" id="category_id" name="category_id">
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
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả</option>
                            <option value="1" <?= ($_GET['status'] ?? '') == '1' ? 'selected' : '' ?>>Đang bán</option>
                            <option value="2" <?= ($_GET['status'] ?? '') == '2' ? 'selected' : '' ?>>Ngừng bán</option>
                            <option value="0" <?= ($_GET['status'] ?? '') == '0' ? 'selected' : '' ?>>Đã xóa</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search me-1"></i>Tìm kiếm
                            </button>
                        </div>
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
            <div class="card-header">
                <h5 class="card-title mb-0">Danh sách sản phẩm (<?= $totalProducts ?> sản phẩm)</h5>
            </div>
            <div class="card-body">
                <?php if (empty($products)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Không có sản phẩm nào</p>
                        <a href="/admin/products/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Thêm sản phẩm đầu tiên
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
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($product['image_url'])): ?>
                                                <?php $imageSrc = ImageHelper::getImageSrc($product['image_url']); ?>
                                                <img src="<?= htmlspecialchars($imageSrc) ?>" 
                                                     alt="<?= htmlspecialchars($product['name']) ?>" 
                                                     class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;"
                                                     onerror="this.style.display='none'">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?= htmlspecialchars($product['name']) ?></strong>
                                                <?php if ($product['description']): ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></span>
                                        </td>
                                        <td>
                                            <strong class="text-success"><?= number_format($product['price']) ?>đ</strong>
                                        </td>
                                        <td>
                                            <?php 
                                            $status = (int)($product['is_available'] ?? 1);
                                            if ($status == 1): ?>
                                                <span class="badge bg-success">Đang bán</span>
                                            <?php elseif ($status == 2): ?>
                                                <span class="badge bg-warning">Ngừng bán</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Đã xóa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="/admin/products/<?= $product['id'] ?>/edit" 
                                                   class="btn btn-sm btn-outline-primary" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function deleteProduct(productId) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
        fetch('/admin/products/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa sản phẩm');
        });
    }
}
</script>
