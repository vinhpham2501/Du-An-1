<?php $title = '404 - Không tìm thấy trang'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="error-page">
                <h1 class="display-1 text-primary">404</h1>
                <h2 class="mb-4">Không tìm thấy trang</h2>
                <p class="lead mb-4">Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
                
                <div class="mb-4">
                    <i class="fas fa-search fa-4x text-muted"></i>
                </div>
                
                <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                    <a href="/" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>
                        Về trang chủ
                    </a>
                    <a href="/contact" class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-2"></i>
                        Liên hệ hỗ trợ
                    </a>
                </div>
                
                <div class="mt-4">
                    <small class="text-muted">
                        Hoặc bạn có thể <a href="javascript:history.back()" class="text-decoration-none">quay lại trang trước</a>
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
