<?php $title = 'Quản lý đánh giá - Sắc Việt Admin'; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Quản lý đánh giá</h3>
        <div class="text-muted">Ẩn/hiện, xóa và trả lời đánh giá của khách hàng.</div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form class="row g-2" method="get" action="/admin/reviews">
            <div class="col-md-5">
                <input class="form-control" type="text" name="q" placeholder="Tìm theo khách hàng / sản phẩm / nội dung" value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="status">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" <?= (isset($filters['status']) && (int)$filters['status'] === 1) ? 'selected' : '' ?>>Hiển thị</option>
                    <option value="0" <?= (isset($filters['status']) && (int)$filters['status'] === 0) ? 'selected' : '' ?>>Đã ẩn</option>
                </select>
            </div>
            <div class="col-md-2">
                <input class="form-control" type="number" name="product_id" placeholder="Mã SP" value="<?= htmlspecialchars($filters['product_id'] ?? '') ?>">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search me-1"></i>Lọc</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($reviews)): ?>
            <div class="text-center text-muted py-5">
                Chưa có đánh giá nào.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:90px;">Mã</th>
                            <th style="width:220px;">Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th style="width:120px;">Sao</th>
                            <th>Nội dung</th>
                            <th style="width:130px;">Trạng thái</th>
                            <th style="width:170px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $r): ?>
                            <tr id="review-row-<?= (int)$r['id'] ?>">
                                <td>#<?= (int)$r['id'] ?></td>
                                <td><?= htmlspecialchars($r['user_name'] ?? '') ?></td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($r['product_name'] ?? '') ?></div>
                                    <small class="text-muted">Mã SP: <?= (int)($r['product_id'] ?? 0) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark"><?= (int)($r['rating'] ?? 0) ?>/5</span>
                                </td>
                                <td>
                                    <div><?= nl2br(htmlspecialchars($r['comment'] ?? '')) ?></div>
                                    <?php if (!empty($r['admin_reply'])): ?>
                                        <div class="mt-2 p-2 bg-light border rounded">
                                            <div class="fw-semibold">Phản hồi admin</div>
                                            <div class="small text-muted mb-1">
                                                <?= !empty($r['admin_replied_at']) ? date('d/m/Y H:i', strtotime($r['admin_replied_at'])) : '' ?>
                                            </div>
                                            <div><?= nl2br(htmlspecialchars($r['admin_reply'])) ?></div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="small text-muted mt-2"><?= !empty($r['created_at']) ? date('d/m/Y H:i', strtotime($r['created_at'])) : '' ?></div>
                                </td>
                                <td>
                                    <?php if ((int)($r['status'] ?? 0) === 1): ?>
                                        <span class="badge bg-success">Hiển thị</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Đã ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php if ((int)($r['status'] ?? 0) === 1): ?>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="updateReviewStatus(<?= (int)$r['id'] ?>, 0)">Ẩn</button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-success" onclick="updateReviewStatus(<?= (int)$r['id'] ?>, 1)">Hiện</button>
                                        <?php endif; ?>
                                        <button
                                            class="btn btn-sm btn-outline-primary btn-reply"
                                            type="button"
                                            data-review-id="<?= (int)$r['id'] ?>"
                                            data-existing-reply="<?= htmlspecialchars((string)($r['admin_reply'] ?? ''), ENT_QUOTES) ?>"
                                        >Trả lời</button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteReview(<?= (int)$r['id'] ?>)">Xóa</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Trả lời đánh giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="replyReviewId" value="0">
                <textarea class="form-control" id="replyContent" rows="5" placeholder="Nhập phản hồi của admin..."></textarea>
                <small id="replyMessage" class="d-block mt-2"></small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="submitReply()">Gửi phản hồi</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('replyModal');
    if (!el || typeof bootstrap === 'undefined') return;

    document.querySelectorAll('.btn-reply').forEach(btn => {
        btn.addEventListener('click', function () {
            const reviewId = this.getAttribute('data-review-id') || '0';
            const existing = this.getAttribute('data-existing-reply') || '';

            document.getElementById('replyReviewId').value = reviewId;
            document.getElementById('replyContent').value = existing;
            document.getElementById('replyMessage').textContent = '';

            bootstrap.Modal.getOrCreateInstance(el).show();
        });
    });
});

function postForm(url, data) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data).toString()
    }).then(r => r.json());
}

function updateReviewStatus(reviewId, status) {
    postForm('/admin/reviews/update-status', { review_id: reviewId, status })
        .then(j => {
            if (j && j.success) location.reload();
            else alert((j && j.message) ? j.message : 'Có lỗi xảy ra');
        })
        .catch(() => alert('Có lỗi xảy ra'));
}

function deleteReview(reviewId) {
    if (!confirm('Bạn chắc chắn muốn xóa đánh giá này?')) return;
    postForm('/admin/reviews/delete', { review_id: reviewId })
        .then(j => {
            if (j && j.success) {
                const row = document.getElementById('review-row-' + reviewId);
                if (row) row.remove();
            } else {
                alert((j && j.message) ? j.message : 'Có lỗi xảy ra');
            }
        })
        .catch(() => alert('Có lỗi xảy ra'));
}

function submitReply() {
    const reviewId = document.getElementById('replyReviewId').value;
    const reply = document.getElementById('replyContent').value;

    postForm('/admin/reviews/reply', { review_id: reviewId, reply })
        .then(j => {
            const msg = document.getElementById('replyMessage');
            msg.textContent = (j && j.message) ? j.message : '';
            msg.className = 'd-block mt-2 ' + ((j && j.success) ? 'text-success' : 'text-danger');
            if (j && j.success) {
                setTimeout(() => location.reload(), 500);
            }
        })
        .catch(() => alert('Có lỗi xảy ra'));
}
</script>
