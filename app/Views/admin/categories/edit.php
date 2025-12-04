<?php $title = 'Chỉnh sửa Danh mục - Admin Panel'; ?>

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa Danh mục</h1>
        <a href="/admin/categories" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin Danh mục</h6>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="/admin/categories/<?= $category['id'] ?>/edit">
                <div class="row">
                    <div class="col-md-8">
                        <!-- ID danh mục -->
                        <div class="mb-3">
                            <label class="form-label">ID danh mục</label>
                            <input type="text" class="form-control" value="<?= $category['id'] ?>" readonly>
                        </div>

                        <!-- Tên danh mục -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= htmlspecialchars($_POST['name'] ?? $category['name']) ?>" required>
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($_POST['description'] ?? $category['description']) ?></textarea>
                        </div>

                        <!-- Trạng thái -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" 
                                       <?= ($category['is_available'] || isset($_POST['is_available'])) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_available">
                                    Hiển thị trên website
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Thông tin</h6>
                                <ul class="list-unstyled">
                                    <li>
                                        <i class="fas fa-calendar me-2"></i>
                                        Ngày tạo: 
                                        <?= date('d/m/Y', strtotime($category['created_at'])) ?>
                                    </li>
                                    <li>
                                        <i class="fas fa-tag me-2"></i>
                                        ID: #<?= $category['id'] ?>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card bg-light mt-3">
                            <div class="card-body">
                                <h6 class="card-title">Hướng dẫn</h6>
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-info-circle text-info me-2"></i>Tên danh mục nên ngắn gọn, dễ hiểu</li>
                                    <li><i class="fas fa-info-circle text-info me-2"></i>Mô tả giúp khách hàng hiểu rõ về danh mục</li>
                                    <li><i class="fas fa-info-circle text-info me-2"></i>Bỏ chọn "Hiển thị" để ẩn danh mục</li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật Danh mục
                            </button>
                            <a href="/admin/categories" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-slug functionality (optional)
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
        });
    }
});
</script>
