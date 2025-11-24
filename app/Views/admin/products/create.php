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

                            <!-- TenSP -->
                            <div class="mb-3">
                                <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="TenSP"
                                       value="<?= htmlspecialchars($_POST['TenSP'] ?? '') ?>" required>
                            </div>

                            <!-- MoTa -->
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="form-control" name="MoTa" rows="4"><?= htmlspecialchars($_POST['MoTa'] ?? '') ?></textarea>
                            </div>

                            <div class="row">
                                <!-- Gia -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Giá <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="Gia"
                                                   value="<?= htmlspecialchars($_POST['Gia'] ?? '') ?>" min="0" step="1000" required>
                                            <span class="input-group-text">đ</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- TrangThai -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <select class="form-select" name="TrangThai">
                                            <option value="1" <?= ($_POST['TrangThai'] ?? 1) == 1 ? 'selected' : '' ?>>Đang bán</option>
                                            <option value="0" <?= ($_POST['TrangThai'] ?? 1) == 0 ? 'selected' : '' ?>>Ngừng bán</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="col-md-4">

                            <!-- MaDM -->
                            <div class="mb-3">
                                <label class="form-label">Danh mục</label>
                                <select class="form-select" name="MaDM" required>
                                    <option value="">Chọn danh mục</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"
                                            <?= ($_POST['MaDM'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- URL IMAGE -->
                            <div class="mb-3">
                                <label class="form-label">URL hình ảnh (HinhAnh)</label>
                                <input type="url" class="form-control" name="HinhAnh"
                                       value="<?= htmlspecialchars($_POST['HinhAnh'] ?? '') ?>"
                                       placeholder="https://example.com/image.jpg">
                                <div class="form-text">Hoặc upload file bên dưới</div>
                            </div>

                            <!-- UPLOAD IMAGE -->
                            <div class="mb-3">
                                <label class="form-label">Upload hình ảnh</label>
                                <input type="file" class="form-control" name="image"
                                       accept="image/jpeg,image/png,image/webp">
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
