<?php $title = 'Đặt hàng thành công - Restaurant Order System'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h2 class="card-title text-success mb-3">🎉 Đặt hàng thành công!</h2>
                    
                    <p class="card-text mb-4">
                        Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được ghi nhận và sẽ được xử lý sớm nhất.
                    </p>
                    
                    <div class="alert alert-info">
                        <h5>Thông tin đơn hàng:</h5>
                        <p><strong>Mã đơn hàng:</strong> #<?= $orderId ?></p>
                        <p><strong>Tổng tiền:</strong> <?= number_format($total) ?>đ</p>
                        <p><strong>Phương thức thanh toán:</strong> Thanh toán khi nhận hàng (COD)</p>
                        <p><strong>Trạng thái thanh toán:</strong> <span class="badge bg-warning">Chưa thanh toán</span></p>
                        <p><strong>Thời gian đặt:</strong> <?= date('d/m/Y H:i:s') ?></p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-info-circle me-2"></i>Lưu ý:</h6>
                        <ul class="text-start mb-0">
                            <li>Đơn hàng sẽ được xác nhận trong thời gian sớm nhất</li>
                            <li>Bạn sẽ nhận được thông báo khi đơn hàng được chuẩn bị</li>
                            <li>Thanh toán bằng tiền mặt khi nhận hàng</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="/my-orders" class="btn btn-primary me-md-2">
                            <i class="fas fa-list me-2"></i>Xem đơn hàng của tôi
                        </a>
                        <a href="/" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>Về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 