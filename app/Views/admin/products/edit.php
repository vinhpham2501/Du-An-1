<?php use App\Helpers\ImageHelper; $title = 'Chỉnh sửa sản phẩm - Admin Panel'; ?>

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

                <?php 
                // Bảo vệ toàn bộ view: nếu $product không tồn tại
                if (!isset($product) || !is_array($product)) {
                    echo '<div class="alert alert-danger">Không tìm thấy dữ liệu sản phẩm.</div>';
                    return;
                }
                ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <!-- LEFT -->
                        <div class="col-md-8">

                            <!-- Tên SP -->
                            <div class="mb-3">
                                <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="TenSP"
                                    value="<?= htmlspecialchars($_POST['TenSP'] ?? ($product['TenSP'] ?? '')) ?>" 
                                    required
                                >
                            </div>

                            <!-- Mô tả -->
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea 
                                    class="form-control" 
                                    name="MoTa" 
                                    rows="4"
                                ><?= htmlspecialchars($_POST['MoTa'] ?? ($product['MoTa'] ?? '')) ?></textarea>
                            </div>

                            <div class="row">

                                <!-- Giá -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Giá <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input 
                                                type="number" 
                                                class="form-control" 
                                                name="Gia"
                                                value="<?= htmlspecialchars($_POST['Gia'] ?? ($product['Gia'] ?? '')) ?>"
                                                min="0" step="1000" required
                                            >
                                            <span class="input-group-text">đ</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Trạng thái -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <?php $status = $_POST['TrangThai'] ?? ($product['TrangThai'] ?? 1); ?>
                                        <select class="form-select" name="TrangThai">
                                            <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Đang bán</option>
                                            <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Ngừng bán</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="col-md-4">

                            <!-- Danh mục -->
                            <div class="mb-3">
<<<<<<< HEAD
                                <label for="image_url" class="form-label">URL hình ảnh</label>
                                <input type="url" class="form-control" id="image_url" name="image_url" 
                                       value="<?= htmlspecialchars($_POST['image_url'] ?? $product['image_url'] ?? '') ?>" 
                                       placeholder="https://example.com/image.jpg">
                                <div class="form-text">Dán đường dẫn ảnh trực tiếp (https://... .jpg, .png, .webp ...)</div>
=======
                                <label class="form-label">Danh mục</label>
                                <select class="form-select" name="MaDM" required>
                                    <option value="">Chọn danh mục</option>

                                    <?php 
                                    $selectedDM = $_POST['MaDM'] ?? ($product['MaDM'] ?? '');
                                    foreach ($categories as $category): 
                                    ?>
                                        <option value="<?= $category['id'] ?>"
                                            <?= $selectedDM == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <!-- URL hình -->
                            <div class="mb-3">
                                <label class="form-label">URL hình ảnh</label>
                                <input 
                                    type="url" 
                                    class="form-control" 
                                    name="HinhAnh"
                                    value="<?= htmlspecialchars($_POST['HinhAnh'] ?? ($product['HinhAnh'] ?? '')) ?>"
                                >
                                <div class="form-text">Hoặc upload file bên dưới</div>
>>>>>>> fd36c9aff3eb5fad1d7ea9a2c8179c88c1b09686
                            </div>

                            <!-- Upload file -->
                            <div class="mb-3">
<<<<<<< HEAD
                                <label for="image_file" class="form-label">Tải lên hình ảnh</label>
                                <input type="file" class="form-control" id="image_file" name="image_file" accept="image/jpeg,image/png,image/webp">
                                <div class="form-text">Nếu chọn file, hệ thống sẽ ưu tiên ảnh tải lên và lưu tại /images/</div>
=======
                                <label class="form-label">Upload hình ảnh</label>
                                <input type="file" class="form-control" name="image"
                                       accept="image/jpeg,image/png,image/webp">
>>>>>>> fd36c9aff3eb5fad1d7ea9a2c8179c88c1b09686
                            </div>

                            <!-- Preview -->
                            <div class="mb-3">
                                <div id="image-preview">
<<<<<<< HEAD
                                    <?php 
                                        $imageSrc = ImageHelper::getImageSrc($_POST['image_url'] ?? ($product['image_url'] ?? null));
                                    ?>
                                    <?php if (!empty($imageSrc)): ?>
                                        <img id="preview-img" src="<?= htmlspecialchars($imageSrc) ?>" 
                                             alt="<?= htmlspecialchars($product['name']) ?>" 
                                             class="img-thumbnail" style="max-width: 100%; height: 200px; object-fit: cover;">
                                        <div class="mt-2">
                                            <small class="text-muted">Xem trước hình ảnh</small>
                                        </div>
=======

                                    <?php 
                                    $image = $product['HinhAnh'] ?? '';
                                    ?>

                                    <?php if (!empty($image)): ?>

                                        <?php 
                                        $imgSrc = (strpos($image, 'http') === 0)
                                            ? $image
                                            : '/uploads/' . $image;
                                        ?>

                                        <img 
                                            src="<?= htmlspecialchars($imgSrc) ?>" 
                                            class="img-thumbnail"
                                            style="max-width:100%; height:200px; object-fit:cover;"
                                        >
                                        <div class="mt-2 text-muted small">Hình ảnh hiện tại</div>

>>>>>>> fd36c9aff3eb5fad1d7ea9a2c8179c88c1b09686
                                    <?php else: ?>

                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-image text-muted fa-3x"></i>
                                        </div>
                                        <div class="text-muted small mt-2">Chưa có hình ảnh</div>

                                    <?php endif; ?>

                                </div>
                            </div>

                            <!-- Info -->
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Thông tin sản phẩm</h6>
                                    <ul class="list-unstyled">
                                        <li>
                                            <i class="fas fa-calendar me-2"></i>
                                            Ngày tạo: 
                                            <?= isset($product['NgayTao']) ? date('d/m/Y', strtotime($product['NgayTao'])) : '---' ?>
                                        </li>

                                        <li>
                                            <i class="fas fa-tag me-2"></i>
                                            ID: #<?= $product['MaSP'] ?? '---' ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/products" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật</button>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<<<<<<< HEAD

<script>
// Live preview when image_url changes
document.getElementById('image_url').addEventListener('input', function(e) {
    const url = e.target.value.trim();
    const preview = document.getElementById('image-preview');
    let img = document.getElementById('preview-img');
    if (!img) {
        img = document.createElement('img');
        img.id = 'preview-img';
        img.className = 'img-thumbnail';
        img.style.cssText = 'max-width: 100%; height: 200px; object-fit: cover;';
        preview.innerHTML = '';
        preview.appendChild(img);
    }
    if (url) {
        img.src = url;
    } else {
        preview.innerHTML = '<div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;"><i class="fas fa-image text-muted fa-3x"></i></div><div class="mt-2"><small class="text-muted">Chưa có hình ảnh</small></div>';
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
=======
>>>>>>> fd36c9aff3eb5fad1d7ea9a2c8179c88c1b09686
