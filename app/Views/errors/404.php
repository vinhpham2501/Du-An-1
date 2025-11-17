<?php $title = '404 - Không tìm thấy trang'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="error-content">
                <i class="fas fa-search fa-5x text-muted mb-4"></i>
                <h1 class="display-1 fw-bold text-primary">404</h1>
                <h2 class="mb-4">Không tìm thấy trang</h2>
                <p class="text-muted mb-4">
                    Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
                </p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="/" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Về trang chủ
                    </a>
                    <button onclick="history.back()" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-content {
    padding: 2rem;
}

.display-1 {
    font-size: 6rem;
    font-weight: 900;
}

@media (max-width: 768px) {
    .display-1 {
        font-size: 4rem;
    }
}
</style>
