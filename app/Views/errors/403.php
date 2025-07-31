<?php $title = '403 - Không có quyền truy cập'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="error-page">
                <h1 class="display-1 text-warning">403</h1>
                <h2 class="mb-4">Không có quyền truy cập</h2>
                <p class="lead mb-4">Bạn không có quyền truy cập vào trang này.</p>
                
                <div class="mb-4">
                    <i class="fas fa-lock fa-4x text-warning"></i>
                </div>
                
                <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                    <a href="/" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>
                        Về trang chủ
                    </a>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="/login" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Đăng nhập
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="mt-4">
                    <small class="text-muted">
                        Vui lòng liên hệ quản trị viên nếu bạn cần quyền truy cập.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    padding: 2rem 0;
}

.error-page h1 {
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}
</style>
