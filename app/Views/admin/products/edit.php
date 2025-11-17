<?php $title = 'Chỉnh sửa sản phẩm - Admin Panel'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Chỉnh sửa sản phẩm</h2>
            <a href="/admin/products" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Thông tin sản phẩm</h5>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($_POST['name'] ?? $product['name']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control" id="description" name="description" rows="4"
                                          placeholder="Mô tả chi tiết về sản phẩm..."><?= htmlspecialchars($_POST['description'] ?? $product['description']) ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Giá <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="price" name="price" 
                                                   value="<?= htmlspecialchars($_POST['price'] ?? $product['price']) ?>" min="0" step="1000" required>
                                            <span class="input-group-text">đ</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="sale_price" name="sale_price" 
                                                   value="<?= htmlspecialchars($_POST['sale_price'] ?? $product['sale_price']) ?>" min="0" step="1000">
                                            <span class="input-group-text">đ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">Chọn danh mục</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category['id'] ?>" 
                                                        <?= ($_POST['category_id'] ?? $product['category_id']) == $category['id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($category['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_available" class="form-label">Trạng thái</label>
                                        <select class="form-select" id="is_available" name="is_available">
                                            <option value="1" <?= ($product['is_available'] ?? 1) == 1 ? 'selected' : '' ?>>Có sẵn</option>
                                            <option value="0" <?= ($product['is_available'] ?? 1) == 0 ? 'selected' : '' ?>>Hết hàng</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="image_url" class="form-label">URL hình ảnh</label>
                                <input type="url" class="form-control" id="image_url" name="image_url" 
                                       value="<?= htmlspecialchars($_POST['image_url'] ?? $product['image_url'] ?? '') ?>" 
                                       placeholder="https://example.com/image.jpg">
                                <div class="form-text">Hoặc upload file bên dưới</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Upload hình ảnh</label>
                                <input type="file" class="form-control" id="image" name="image" 
                                       accept="image/jpeg,image/png,image/webp">
                                <div class="form-text">Chấp nhận: JPG, PNG, WEBP. Tối đa 5MB</div>
                            </div>
                            
                            <div class="mb-3">
                                <div id="image-preview">
                                    <?php if ($product['image_url']): ?>
                                        <?php 
                                        $imageSrc = (strpos($product['image_url'], 'http') === 0) 
                                            ? $product['image_url'] 
                                            : '/uploads/' . $product['image_url'];
                                        ?>
                                        <img src="<?= htmlspecialchars($imageSrc) ?>" 
                                             alt="<?= htmlspecialchars($product['name']) ?>" 
                                             class="img-thumbnail" style="max-width: 100%; height: 200px; object-fit: cover;"
                                             onerror="this.style.display='none'">
                                        <div class="mt-2">
                                            <small class="text-muted">Hình ảnh hiện tại</small>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-image text-muted fa-3x"></i>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">Chưa có hình ảnh</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Thông tin sản phẩm</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fas fa-calendar text-info me-2"></i>Tạo: <?= date('d/m/Y', strtotime($product['created_at'])) ?></li>
                                        <li><i class="fas fa-edit text-info me-2"></i>Cập nhật: <?= date('d/m/Y', strtotime($product['updated_at'])) ?></li>
                                        <li><i class="fas fa-tag text-info me-2"></i>ID: #<?= $product['id'] ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/products" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật sản phẩm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview for new upload
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" 
                     class="img-thumbnail" style="max-width: 100%; height: 200px; object-fit: cover;">
                <div class="mt-2">
                    <small class="text-success">Hình ảnh mới</small>
                </div>
            `;
        }
        reader.readAsDataURL(file);
    } else {
        // Reset to original image
        <?php if ($product['image_url']): ?>
            preview.innerHTML = `
                <?php 
                $imageSrc = (strpos($product['image_url'], 'http') === 0) 
                    ? $product['image_url'] 
                    : '/uploads/' . $product['image_url'];
                ?>
                <img src="<?= htmlspecialchars($imageSrc) ?>" 
                     alt="<?= htmlspecialchars($product['name']) ?>" 
                     class="img-thumbnail" style="max-width: 100%; height: 200px; object-fit: cover;"
                     onerror="this.style.display='none'">
                <div class="mt-2">
                    <small class="text-muted">Hình ảnh hiện tại</small>
                </div>
            `;
        <?php else: ?>
            preview.innerHTML = `
                <div class="bg-light d-flex align-items-center justify-content-center" 
                     style="height: 200px;">
                    <i class="fas fa-image text-muted fa-3x"></i>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Chưa có hình ảnh</small>
                </div>
            `;
        <?php endif; ?>
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const price = parseFloat(document.getElementById('price').value);
    const salePrice = parseFloat(document.getElementById('sale_price').value);
    
    if (salePrice && salePrice >= price) {
        e.preventDefault();
        alert('Giá khuyến mãi phải thấp hơn giá gốc!');
        return false;
    }
});
</script>
