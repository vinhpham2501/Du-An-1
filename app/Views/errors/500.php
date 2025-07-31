<?php $title = '500 - Lỗi hệ thống'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="error-page">
                <h1 class="display-1 text-danger">500</h1>
                <h2 class="mb-4">Lỗi hệ thống</h2>
                <p class="lead mb-4">
                    <?= isset($message) ? htmlspecialchars($message) : 'Có lỗi xảy ra trên máy chủ. Vui lòng thử lại sau.' ?>
                </p>
                
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle fa-4x text-danger"></i>
                </div>
                
                <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                    <a href="/" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>
                        Về trang chủ
                    </a>
                    <button onclick="location.reload()" class="btn btn-outline-primary">
                        <i class="fas fa-redo me-2"></i>
                        Thử lại
                    </button>
                    <a href="/contact" class="btn btn-outline-secondary">
                        <i class="fas fa-envelope me-2"></i>
                        Báo lỗi
                    </a>
                </div>
                
                <div class="mt-4">
                    <small class="text-muted">
                        Nếu lỗi tiếp tục xảy ra, vui lòng liên hệ bộ phận hỗ trợ.
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
