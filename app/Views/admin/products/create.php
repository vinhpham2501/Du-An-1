<?php $title = 'Thêm sản phẩm - Admin Panel'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Thêm sản phẩm mới</h2>
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
                        <!-- LEFT -->
                        <div class="col-md-8">

                            <!-- Tên sản phẩm -->
                            <div class="mb-3">
                                <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name"
                                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                            </div>

                            <!-- Giới thiệu -->
                            <div class="mb-3">
                                <label class="form-label">Giới thiệu</label>
                                <textarea class="form-control" name="intro" rows="3"><?= htmlspecialchars($_POST['intro'] ?? '') ?></textarea>
                            </div>

                            <!-- Chi tiết sản phẩm -->
                            <div class="mb-3">
                                <label class="form-label">Chi tiết sản phẩm</label>
                                <textarea class="form-control" name="detail" rows="5"><?= htmlspecialchars($_POST['detail'] ?? '') ?></textarea>
                            </div>

                            <div class="row">
                                <!-- Giá -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Giá <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="price"
                                                   value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" min="0" step="1000" required>
                                            <span class="input-group-text">đ</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Trạng thái -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <?php $status = $_POST['is_available'] ?? 1; ?>
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
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"
                                            <?= ($_POST['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name'] ?? '') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Hình ảnh -->
                            <div class="mb-3">
                                <label class="form-label">Hình ảnh sản phẩm</label>
                                <div class="border border-dashed rounded p-3 text-center bg-light">
                                    <input type="file" name="image_file" accept="image/*" class="form-control mb-2">
                                    <div class="form-text small">Tải lên hình ảnh chính của sản phẩm (JPG, PNG, WebP)</div>
                                </div>
                            </div>

                            <!-- Hình ảnh gallery -->
                            <div class="mb-3">
                                <label class="form-label">Hình ảnh thêm (URL)</label>
                                <textarea class="form-control" name="gallery_image_urls" rows="3"
                                          placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg"><?= htmlspecialchars($_POST['gallery_image_urls'] ?? '') ?></textarea>
                                <div class="form-text">Mỗi dòng một URL hình ảnh</div>
                            </div>

                            <!-- Màu sắc -->
                            <div class="mb-3">
                                <label class="form-label">Màu sắc</label>
                                <input type="text" class="form-control" name="colors"
                                       value="<?= htmlspecialchars($_POST['colors'] ?? '') ?>"
                                       placeholder="Đỏ, Xanh, Vàng (cách nhau bằng dấu phẩy)">
                                <div class="form-text">Nhập các màu, cách nhau bằng dấu phẩy</div>
                            </div>

                            <!-- Size -->
                            <div class="mb-3">
                                <label class="form-label">Size</label>
                                <input type="text" class="form-control" name="sizes"
                                       value="<?= htmlspecialchars($_POST['sizes'] ?? '') ?>"
                                       placeholder="S, M, L, XL (cách nhau bằng dấu phẩy)">
                                <div class="form-text">Nhập các size, cách nhau bằng dấu phẩy</div>
                            </div>

                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Hướng dẫn</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fas fa-info-circle text-info me-2"></i>Tên sản phẩm nên ngắn gọn, dễ hiểu</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>Mô tả chi tiết giúp khách hàng hiểu rõ sản phẩm</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>Giá sản phẩm phải hợp lý</li>
                                        <li><i class="fas fa-info-circle text-info me-2"></i>Hình ảnh chất lượng cao sẽ thu hút khách hàng</li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/products" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu sản phẩm
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
// Xem trước ảnh trực tiếp từ URL
document.addEventListener('DOMContentLoaded', function () {
    const urlInput = document.getElementById('image_url');
    if (!urlInput) return;
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    urlInput.addEventListener('input', function(e) {
        const url = e.target.value.trim();
        if (url) {
            previewImg.src = url;
            preview.classList.remove('d-none');
        } else {
            preview.classList.add('d-none');
        }
    });
});
</script>
