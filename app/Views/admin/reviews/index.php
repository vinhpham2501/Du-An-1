<?php $title = 'Quản lý đánh giá - Admin'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Quản lý đánh giá</h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?= $ratingCounts[5] ?? 0 ?></h4>
                        <p class="card-text mb-0">5 sao</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?= $ratingCounts[4] ?? 0 ?></h4>
                        <p class="card-text mb-0">4 sao</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?= $ratingCounts[3] ?? 0 ?></h4>
                        <p class="card-text mb-0">3 sao</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?= $ratingCounts[2] ?? 0 ?></h4>
                        <p class="card-text mb-0">2 sao</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?= $ratingCounts[1] ?? 0 ?></h4>
                        <p class="card-text mb-0">1 sao</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?= $totalReviews ?></h4>
                        <p class="card-text mb-0">Tổng đánh giá</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-comments fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Cards -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?= $statusCounts['1'] ?? 0 ?></h4>
                        <p class="card-text mb-0">Đã duyệt</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?= $statusCounts['0'] ?? 0 ?></h4>
                        <p class="card-text mb-0">Chưa duyệt</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Tìm kiếm</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
                       placeholder="Tên, sản phẩm, nội dung...">
            </div>
            <div class="col-md-2">
                <label for="rating" class="form-label">Số sao</label>
                <select class="form-select" id="rating" name="rating">
                    <option value="">Tất cả</option>
                    <option value="5" <?= ($_GET['rating'] ?? '') == '5' ? 'selected' : '' ?>>5 sao</option>
                    <option value="4" <?= ($_GET['rating'] ?? '') == '4' ? 'selected' : '' ?>>4 sao</option>
                    <option value="3" <?= ($_GET['rating'] ?? '') == '3' ? 'selected' : '' ?>>3 sao</option>
                    <option value="2" <?= ($_GET['rating'] ?? '') == '2' ? 'selected' : '' ?>>2 sao</option>
                    <option value="1" <?= ($_GET['rating'] ?? '') == '1' ? 'selected' : '' ?>>1 sao</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tất cả</option>
                    <option value="1" <?= ($_GET['status'] ?? '') == '1' ? 'selected' : '' ?>>Đã duyệt</option>
                    <option value="0" <?= ($_GET['status'] ?? '') == '0' ? 'selected' : '' ?>>Chưa duyệt</option>
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

<!-- Reviews Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Đánh giá</th>
                        <th>Nội dung</th>
                        <th>Trạng thái</th>
                        <th>Ngày đăng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review): ?>
                        <tr class="<?= ($review['status'] == '0' ? 'table-warning' : '') . (($review['is_deleted'] ?? 0) == 1 ? ' table-secondary opacity-75' : '') ?>">
                            <td><?= $review['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <strong><?= htmlspecialchars($review['user_name'] ?? 'N/A') ?></strong>
                                    <?php if (($review['is_deleted'] ?? 0) == 1): ?>
                                        <span class="badge bg-danger">Đã xóa</span>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted"><?= htmlspecialchars($review['user_email'] ?? '') ?></small>
                            </td>
                            <td>
                                <a href="/product/<?= $review['product_id'] ?>" target="_blank" class="text-decoration-none">
                                    <?= htmlspecialchars($review['product_name'] ?? 'N/A') ?>
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                    <?php endfor; ?>
                                    <span class="ms-2"><?= $review['rating'] ?>/5</span>
                                </div>
                            </td>
                            <td>
                                <div style="max-width: 300px;">
                                    <?php if (($review['is_deleted'] ?? 0) == 1): ?>
                                        <em class="text-muted"><?= htmlspecialchars(substr($review['comment'], 0, 100)) ?></em>
                                    <?php else: ?>
                                        <?= htmlspecialchars(substr($review['comment'], 0, 100)) ?>
                                    <?php endif; ?>
                                    <?= strlen($review['comment']) > 100 ? '...' : '' ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($review['status'] == '1'): ?>
                                    <span class="badge bg-success">Đã duyệt</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Chưa duyệt</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small>
                                    <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/admin/reviews/<?= $review['id'] ?>" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                data-bs-toggle="dropdown">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php if ($review['status'] == '0'): ?>
                                                <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $review['id'] ?>, '1')">
                                                    <i class="fas fa-check text-success me-2"></i>Duyệt đánh giá
                                                </a></li>
                                            <?php else: ?>
                                                <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $review['id'] ?>, '0')">
                                                    <i class="fas fa-times text-warning me-2"></i>Hủy duyệt
                                                </a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (empty($reviews)): ?>
            <div class="text-center py-4">
                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                <p class="text-muted">Không có đánh giá nào</p>
            </div>
        <?php endif; ?>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?><?= !empty($_GET['status']) ? '&status=' . $_GET['status'] : '' ?><?= !empty($_GET['rating']) ? '&rating=' . $_GET['rating'] : '' ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<script>
function updateStatus(reviewId, status) {
    if (confirm('Bạn có chắc muốn thay đổi trạng thái đánh giá này?')) {
        fetch('/admin/reviews/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `review_id=${reviewId}&status=${status}`
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

