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

                            <!-- Tên sản phẩm -->
                            <div class="mb-3">
                                <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="name"
                                    value="<?= htmlspecialchars($_POST['name'] ?? ($product['name'] ?? '')) ?>" 
                                    required
                                >
                            </div>

                            <!-- Giới thiệu -->
                            <div class="mb-3">
                                <label class="form-label">Giới thiệu</label>
                                <textarea 
                                    class="form-control" 
                                    name="intro" 
                                    rows="3"
                                ><?= htmlspecialchars($_POST['intro'] ?? ($product['intro'] ?? '')) ?></textarea>
                            </div>

                            <!-- Chi tiết sản phẩm -->
                            <div class="mb-3">
                                <label class="form-label">Chi tiết sản phẩm</label>
                                <textarea 
                                    class="form-control" 
                                    name="detail" 
                                    rows="5"
                                ><?= htmlspecialchars($_POST['detail'] ?? ($product['detail'] ?? '')) ?></textarea>
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
                                                name="price"
                                                value="<?= htmlspecialchars($_POST['price'] ?? ($product['price'] ?? '')) ?>"
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
                                        <?php $status = $_POST['is_available'] ?? ($product['is_available'] ?? 1); ?>
                                        <select class="form-select" name="is_available">
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
                                <label class="form-label">Danh mục</label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">Chọn danh mục</option>

                                    <?php 
                                    $selectedDM = $_POST['category_id'] ?? ($product['category_id'] ?? '');
                                    foreach ($categories as $category): 
                                    ?>
                                        <option value="<?= $category['id'] ?>"
                                            <?= $selectedDM == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <!-- Hình ảnh sản phẩm -->
                            <div class="mb-3">
                                <label for="image_file" class="form-label">Tải lên hình ảnh chính</label>
                                <input type="file" class="form-control" id="image_file" name="image_file" accept="image/jpeg,image/png,image/webp">
                                <div class="form-text">Tải lên hình ảnh chính của sản phẩm (JPG, PNG, WebP)</div>
                            </div>

                            <!-- Hình ảnh hiện có -->
                            <?php if (!empty($images)): ?>
                                <div class="mb-3">
                                    <label class="form-label">Hình ảnh hiện có (<?= count($images) ?>)</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php foreach ($images as $img): ?>
                                            <img src="<?= htmlspecialchars($img['image_url']) ?>" 
                                                 alt="Product image" 
                                                 class="img-thumbnail" 
                                                 style="width: 80px; height: 80px; object-fit: contain; background: #f8f9fa;"
                                                 title="<?= htmlspecialchars($img['image_url']) ?>">
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="form-text mt-1">Các hình ảnh này sẽ bị thay thế khi cập nhật</div>
                                </div>
                            <?php endif; ?>

                            <!-- Màu sắc -->
                            <div class="mb-3">
                                <label class="form-label">Màu sắc</label>
                                <input type="text" class="form-control" name="colors"
                                       value="<?= htmlspecialchars($_POST['colors'] ?? (isset($colors) ? implode(', ', array_column($colors, 'name')) : '')) ?>"
                                       placeholder="Đỏ, Xanh, Vàng (cách nhau bằng dấu phẩy)">
                                <div class="form-text">Nhập các màu, cách nhau bằng dấu phẩy</div>
                                <?php if (!empty($colors)): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Màu hiện tại: <?= htmlspecialchars(implode(', ', array_column($colors, 'name'))) ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Size -->
                            <div class="mb-3">
                                <label class="form-label">Size</label>
                                <input type="text" class="form-control" name="sizes"
                                       value="<?= htmlspecialchars($_POST['sizes'] ?? (isset($sizes) ? implode(', ', array_column($sizes, 'name')) : '')) ?>"
                                       placeholder="S, M, L, XL (cách nhau bằng dấu phẩy)">
                                <div class="form-text">Nhập các size, cách nhau bằng dấu phẩy</div>
                                <?php if (!empty($sizes)): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Size hiện tại: <?= htmlspecialchars(implode(', ', array_column($sizes, 'name'))) ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Hình ảnh gallery -->
                            <div class="mb-3">
                                <label class="form-label">Hình ảnh thêm (URL)</label>
                                <textarea class="form-control" name="gallery_image_urls" rows="3"
                                          placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg"><?= htmlspecialchars($_POST['gallery_image_urls'] ?? (isset($images) ? implode("\n", array_column($images, 'image_url')) : '')) ?></textarea>
                                <div class="form-text">Mỗi dòng một URL hình ảnh</div>
                                <?php if (!empty($images)): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Ảnh hiện tại (<?= count($images) ?>):</small>
                                        <div class="d-flex flex-wrap gap-2 mt-1">
                                            <?php foreach ($images as $img): ?>
                                                <img src="<?= htmlspecialchars($img['image_url']) ?>" 
                                                     alt="Product image" 
                                                     class="img-thumbnail" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
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

<script>
// Live preview when image_url changes
document.addEventListener('DOMContentLoaded', function () {
    const urlInput = document.getElementById('image_url');
    if (!urlInput) return;
    const preview = document.getElementById('image-preview');
    let img = document.getElementById('preview-img');
    urlInput.addEventListener('input', function(e) {
        const url = e.target.value.trim();
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
            img = null;
        }
    });
});
</script>
