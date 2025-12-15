<?php $title = 'Chi tiết Người dùng - Admin'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Chi tiết Người dùng</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/users">Người dùng</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>
        </div>
        <a href="/admin/users" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <!-- User Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Thông tin người dùng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID:</strong> <?= $user['id'] ?></p>
                            <?php $username = explode('@', $user['email'] ?? '')[0]; ?>
                            <p><strong>Username:</strong> @<?= htmlspecialchars($username) ?></p>
                            <p><strong>Họ tên:</strong> <?= htmlspecialchars($user['full_name'] ?? '') ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '') ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Vai trò:</strong> 
                                <?php
                                $roleColors = [
                                    'admin' => 'danger',
                                    'user' => 'primary'
                                ];
                                $roleLabels = [
                                    'admin' => 'Admin',
                                    'user' => 'Người dùng'
                                ];
                                $color = $roleColors[$user['role']] ?? 'secondary';
                                $label = $roleLabels[$user['role']] ?? $user['role'];
                                ?>
                                <span class="badge bg-<?= $color ?>"><?= $label ?></span>
                            </p>
                            <p><strong>Trạng thái:</strong> 
                                <?php if (($user['status'] ?? 1) == 1): ?>
                                    <span class="badge bg-success">Đang hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Đã khóa</span>
                                <?php endif; ?>
                            </p>
                            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></p>
                            <?php
                                $createdAt = $user['created_at'] ?? null;
                                $updatedAt = $user['updated_at'] ?? null;
                            ?>
                            <p><strong>Ngày tạo:</strong>
                                <?= $createdAt ? date('d/m/Y H:i', strtotime($createdAt)) : 'Chưa có dữ liệu' ?>
                            </p>
                            <p><strong>Cập nhật lần cuối:</strong>
                                <?= $updatedAt ? date('d/m/Y H:i', strtotime($updatedAt)) : 'Chưa cập nhật' ?>
                            </p>
                        </div>
                    </div>
                    
                    <?php if (!empty($user['address'] ?? null)): ?>
                        <div class="mt-3">
                            <p><strong>Địa chỉ:</strong></p>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($user['address'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Cập nhật vai trò
                    </h5>
                </div>
                <div class="card-body">
                    <form id="updateRoleForm">
                        <div class="mb-3">
                            <label for="role" class="form-label">Vai trò mới</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Người dùng</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i>Cập nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('updateRoleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const role = formData.get('role');
    
    fetch('/admin/users/<?= $user['id'] ?>/update-role', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'role=' + encodeURIComponent(role)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cập nhật vai trò thành công!');
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật vai trò');
    });
});
</script> 