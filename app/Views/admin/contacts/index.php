<?php $title = 'Quản lý tin nhắn liên hệ - Admin'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Quản lý tin nhắn liên hệ</h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?= $statusCounts['new'] ?></h4>
                        <p class="card-text">Tin nhắn mới</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?= $statusCounts['read'] ?></h4>
                        <p class="card-text">Đã đọc</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-envelope-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?= $statusCounts['replied'] ?></h4>
                        <p class="card-text">Đã phản hồi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-reply fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?= $totalContacts ?></h4>
                        <p class="card-text">Tổng tin nhắn</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-comments fa-2x"></i>
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
                       placeholder="Tên, email, nội dung...">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tất cả</option>
                    <option value="new" <?= ($_GET['status'] ?? '') == 'new' ? 'selected' : '' ?>>Tin nhắn mới</option>
                    <option value="read" <?= ($_GET['status'] ?? '') == 'read' ? 'selected' : '' ?>>Đã đọc</option>
                    <option value="replied" <?= ($_GET['status'] ?? '') == 'replied' ? 'selected' : '' ?>>Đã phản hồi</option>
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

<!-- Contacts Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên khách hàng</th>
                        <th>Email</th>
                        <th>Tin nhắn</th>
                        <th>Trạng thái</th>
                        <th>Ngày gửi</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                        <tr class="<?= $contact['status'] === 'new' ? 'table-warning' : '' ?>">
                            <td><?= $contact['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($contact['name']) ?></strong>
                            </td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($contact['email']) ?>
                                </a>
                            </td>
                            <td>
                                <div style="max-width: 300px;">
                                    <?= htmlspecialchars(substr($contact['message'], 0, 100)) ?>
                                    <?= strlen($contact['message']) > 100 ? '...' : '' ?>
                                </div>
                            </td>
                            <td>
                                <?php
                                $statusColors = [
                                    'new' => 'warning',
                                    'read' => 'info', 
                                    'replied' => 'success'
                                ];
                                $statusLabels = [
                                    'new' => 'Tin nhắn mới',
                                    'read' => 'Đã đọc',
                                    'replied' => 'Đã phản hồi'
                                ];
                                $color = $statusColors[$contact['status']] ?? 'secondary';
                                $label = $statusLabels[$contact['status']] ?? $contact['status'];
                                ?>
                                <span class="badge bg-<?= $color ?>"><?= $label ?></span>
                            </td>
                            <td>
                                <small>
                                    <?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/admin/contacts/<?= $contact['id'] ?>" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                data-bs-toggle="dropdown">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $contact['id'] ?>, 'new')">
                                                <i class="fas fa-envelope text-warning me-2"></i>Đánh dấu mới
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $contact['id'] ?>, 'read')">
                                                <i class="fas fa-envelope-open text-info me-2"></i>Đánh dấu đã đọc
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $contact['id'] ?>, 'replied')">
                                                <i class="fas fa-reply text-success me-2"></i>Đánh dấu đã phản hồi
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteContact(<?= $contact['id'] ?>)">
                                                <i class="fas fa-trash me-2"></i>Xóa tin nhắn
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (empty($contacts)): ?>
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Không có tin nhắn nào</p>
            </div>
        <?php endif; ?>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?><?= !empty($_GET['status']) ? '&status=' . $_GET['status'] : '' ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
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
function updateStatus(contactId, status) {
    if (confirm('Bạn có chắc muốn thay đổi trạng thái tin nhắn này?')) {
        fetch('/admin/contacts/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `contact_id=${contactId}&status=${status}`
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

function deleteContact(contactId) {
    if (confirm('Bạn có chắc muốn xóa tin nhắn này? Hành động này không thể hoàn tác.')) {
        fetch('/admin/contacts/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `contact_id=${contactId}`
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
