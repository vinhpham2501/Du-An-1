<?php 
if (empty($review)) {
    http_response_code(404);
    include APP_PATH . '/Views/errors/404.php';
    exit;
}
$title = 'Chi tiết đánh giá #' . $review['id'] . ' - Admin'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Chi tiết đánh giá #<?= $review['id'] ?></h1>
        <?php if (($review['is_deleted'] ?? 0) == 1): ?>
            <span class="badge bg-danger mt-2">Đã bị xóa bởi người dùng</span>
        <?php endif; ?>
    </div>
    <a href="/admin/reviews" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-star me-2"></i>
                    Nội dung đánh giá
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="text-muted">Từ khách hàng:</h6>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <?= strtoupper(substr($review['user_name'] ?? 'U', 0, 1)) ?>
                            </div>
                        </div>
                        <div>
                            <strong><?= htmlspecialchars($review['user_name'] ?? 'N/A') ?></strong><br>
                            <a href="mailto:<?= htmlspecialchars($review['user_email'] ?? '') ?>" class="text-decoration-none">
                                <?= htmlspecialchars($review['user_email'] ?? '') ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-muted">Sản phẩm:</h6>
                    <div class="d-flex align-items-center">
                        <?php 
                        use App\Helpers\ImageHelper;
                        if (!empty($review['product_image'])): 
                            $imageSrc = ImageHelper::getImageSrc($review['product_image']);
                        ?>
                            <img src="<?= htmlspecialchars($imageSrc) ?>" 
                                 alt="<?= htmlspecialchars($review['product_name'] ?? '') ?>" 
                                 class="me-3 img-thumbnail" 
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                 style="width: 60px; height: 60px; border-radius: 4px; display: none;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                 style="width: 60px; height: 60px; border-radius: 4px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <a href="/product/<?= $review['product_id'] ?>" target="_blank" class="text-decoration-none">
                                <strong><?= htmlspecialchars($review['product_name'] ?? 'N/A') ?></strong>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-muted">Đánh giá:</h6>
                    <div class="d-flex align-items-center mb-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star fa-lg <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                        <?php endfor; ?>
                        <span class="ms-2 fs-5"><strong><?= $review['rating'] ?>/5</strong></span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-muted">Thời gian đăng:</h6>
                    <p><?= date('d/m/Y H:i:s', strtotime($review['created_at'])) ?></p>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-muted">Nội dung bình luận:</h6>
                    <div class="border p-3 rounded <?= ($review['is_deleted'] ?? 0) == 1 ? 'bg-secondary text-muted opacity-75' : 'bg-light' ?>">
                        <?php if (($review['is_deleted'] ?? 0) == 1): ?>
                            <em><?= nl2br(htmlspecialchars($review['comment'])) ?></em>
                        <?php else: ?>
                            <?= nl2br(htmlspecialchars($review['comment'])) ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Phản hồi đánh giá -->
                <div class="mt-4">
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-reply me-2"></i>Phản hồi đánh giá:
                    </h6>
                    
                    <?php if (!empty($reply)): ?>
                        <div class="p-3 rounded mb-3" style="background: #fff; border: 1px solid #e0e0e0; border-left: 3px solid #8b0000; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #8b0000, #5a0000); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; margin-right: 10px;">
                                        <i class="fas fa-headset"></i>
                                    </div>
                                    <div>
                                        <strong style="color: #8b0000; font-size: 14px;">
                                            <?= htmlspecialchars($reply['replied_by'] ?? 'Shop') ?>
                                        </strong>
                                        <br>
                                        <small class="text-muted" style="font-size: 12px;">
                                            <?= date('d/m/Y H:i', strtotime($reply['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteReply()" title="Xóa phản hồi">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="mt-2" style="color: #333; line-height: 1.6;">
                                <?= nl2br(htmlspecialchars($reply['reply'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="border p-3 rounded">
                        <form id="replyForm" onsubmit="submitReply(event)">
                            <div class="mb-3">
                                <label for="replyContent" class="form-label">Nội dung phản hồi:</label>
                                <textarea class="form-control" id="replyContent" rows="4" 
                                          placeholder="Nhập nội dung phản hồi cho đánh giá này..."><?= !empty($reply) ? htmlspecialchars($reply['reply']) : '' ?></textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn" id="submitReplyBtn" style="background-color: #8b0000; border-color: #8b0000; color: white;">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    <?= !empty($reply) ? 'Cập nhật phản hồi' : 'Gửi phản hồi' ?>
                                </button>
                                <?php if (!empty($reply)): ?>
                                    <button type="button" class="btn btn-outline-danger" onclick="deleteReply()">
                                        <i class="fas fa-trash me-2"></i>Xóa phản hồi
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Thông tin đánh giá
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Trạng thái hiện tại:</label>
                    <br>
                    <?php if ($review['status'] == '1'): ?>
                        <span class="badge bg-success fs-6">Đã duyệt</span>
                    <?php else: ?>
                        <span class="badge bg-warning fs-6">Chưa duyệt</span>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Thay đổi trạng thái:</label>
                    <div class="d-grid gap-2">
                        <?php if ($review['status'] == '0'): ?>
                            <button class="btn btn-outline-success btn-sm" onclick="updateStatus('1')">
                                <i class="fas fa-check me-2"></i>Duyệt đánh giá
                            </button>
                        <?php else: ?>
                            <button class="btn btn-outline-warning btn-sm" onclick="updateStatus('0')">
                                <i class="fas fa-times me-2"></i>Hủy duyệt
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <label class="form-label">Thông tin chi tiết:</label>
                    <div class="small">
                        <strong>ID:</strong> <?= $review['id'] ?><br>
                        <strong>Khách hàng:</strong> <?= htmlspecialchars($review['user_name'] ?? 'N/A') ?><br>
                        <strong>Email:</strong> <?= htmlspecialchars($review['user_email'] ?? '') ?><br>
                        <strong>Sản phẩm ID:</strong> <?= $review['product_id'] ?><br>
                        <strong>Số sao:</strong> <?= $review['rating'] ?>/5<br>
                        <strong>Ngày đăng:</strong> <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?><br>
                    </div>
                </div>
                
                <hr>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Thao tác nhanh
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/product/<?= $review['product_id'] ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-external-link-alt me-2"></i>Xem sản phẩm
                    </a>
                    <button class="btn btn-outline-info btn-sm" onclick="copyEmail()">
                        <i class="fas fa-copy me-2"></i>Copy email
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    if (confirm('Bạn có chắc muốn thay đổi trạng thái đánh giá này?')) {
        fetch('/admin/reviews/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `review_id=<?= $review['id'] ?>&status=${status}`
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

function submitReply(event) {
    event.preventDefault();
    
    const replyContent = document.getElementById('replyContent').value.trim();
    const submitBtn = document.getElementById('submitReplyBtn');
    
    if (!replyContent) {
        showAlert('danger', 'Vui lòng nhập nội dung phản hồi');
        return;
    }
    
    // Disable button and show loading
    submitBtn.disabled = true;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...';
    
    fetch('/admin/reviews/reply', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `review_id=<?= $review['id'] ?>&reply=${encodeURIComponent(replyContent)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('danger', data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        showAlert('danger', 'Có lỗi xảy ra, vui lòng thử lại');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

function deleteReply() {
    if (confirm('Bạn có chắc muốn xóa phản hồi này?')) {
        fetch('/admin/reviews/delete-reply', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `review_id=<?= $review['id'] ?>`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            showAlert('danger', 'Có lỗi xảy ra, vui lòng thử lại');
        });
    }
}

function copyEmail() {
    navigator.clipboard.writeText('<?= htmlspecialchars($review['user_email'] ?? '') ?>').then(() => {
        showAlert('success', 'Đã copy email vào clipboard');
    });
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

