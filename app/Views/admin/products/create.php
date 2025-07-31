<?php $title = 'Thêm sản phẩm - Admin Panel'; ?>

<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/admin/products">Sản phẩm</a></li>
                <li class="breadcrumb-item active">Thêm sản phẩm</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Thêm sản phẩm mới</h2>
            <a href="/admin/products" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Quay lại
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="POST" action="/admin/products/create" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tên sản phẩm *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Danh mục *</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Chọn danh mục</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" 
                                            <?= ($_POST['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Giá gốc (đ) *</label>
                            <input type="number" class="form-control" id="price" name="price" 
                                   value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" 
                                   min="0" step="1000" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="sale_price" class="form-label">Giá khuyến mãi (đ)</label>
                            <input type="number" class="form-control" id="sale_price" name="sale_price" 
                                   value="<?= htmlspecialchars($_POST['sale_price'] ?? '') ?>" 
                                   min="0" step="1000">
                            <div class="form-text">Để trống nếu không có khuyến mãi</div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="unit" class="form-label">Đơn vị</label>
                            <select class="form-select" id="unit" name="unit">
                                <option value="Phần" <?= ($_POST['unit'] ?? 'Phần') === 'Phần' ? 'selected' : '' ?>>Phần</option>
                                <option value="Tô" <?= ($_POST['unit'] ?? '') === 'Tô' ? 'selected' : '' ?>>Tô</option>
                                <option value="Ly" <?= ($_POST['unit'] ?? '') === 'Ly' ? 'selected' : '' ?>>Ly</option>
                                <option value="Cái" <?= ($_POST['unit'] ?? '') === 'Cái' ? 'selected' : '' ?>>Cái</option>
                                <option value="Con" <?= ($_POST['unit'] ?? '') === 'Con' ? 'selected' : '' ?>>Con</option>
                                <option value="Kg" <?= ($_POST['unit'] ?? '') === 'Kg' ? 'selected' : '' ?>>Kg</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status">
                                <option value="available" <?= ($_POST['status'] ?? 'available') === 'available' ? 'selected' : '' ?>>
                                    Có sẵn
                                </option>
                                <option value="out_of_stock" <?= ($_POST['status'] ?? '') === 'out_of_stock' ? 'selected' : '' ?>>
                                    Hết hàng
                                </option>
                                <option value="discontinued" <?= ($_POST['status'] ?? '') === 'discontinued' ? 'selected' : '' ?>>
                                    Ngừng bán
                                </option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control" id="image" name="image" 
                                   accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">Chấp nhận: JPG, PNG, WebP. Tối đa 5MB.</div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Lưu sản phẩm
                        </button>
                        
                        <a href="/admin/products" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>
                            Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Hướng dẫn</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Tên sản phẩm nên ngắn gọn và dễ hiểu
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Mô tả chi tiết giúp khách hàng hiểu rõ về món ăn
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Giá khuyến mãi phải nhỏ hơn giá gốc
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Hình ảnh nên có chất lượng tốt và thể hiện rõ món ăn
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Validate sale price
document.getElementById('sale_price').addEventListener('input', function() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const salePrice = parseFloat(this.value) || 0;
    
    if (salePrice > 0 && salePrice >= price) {
        this.setCustomValidity('Giá khuyến mãi phải nhỏ hơn giá gốc');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('price').addEventListener('input', function() {
    const salePriceInput = document.getElementById('sale_price');
    const price = parseFloat(this.value) || 0;
    const salePrice = parseFloat(salePriceInput.value) || 0;
    
    if (salePrice > 0 && salePrice >= price) {
        salePriceInput.setCustomValidity('Giá khuyến mãi phải nhỏ hơn giá gốc');
    } else {
        salePriceInput.setCustomValidity('');
    }
});
</script>
