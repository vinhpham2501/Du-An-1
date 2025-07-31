<?php $title = 'Liên hệ - Restaurant Order System'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary">Liên hệ với chúng tôi</h1>
                <p class="lead">Chúng tôi luôn sẵn sàng lắng nghe ý kiến của quý khách</p>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-envelope me-2"></i>
                                Gửi tin nhắn
                            </h4>
                        </div>
                        
                        <div class="card-body">
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                            <?php endif; ?>
                            
                            <form method="POST" action="/contact">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Họ và tên *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= htmlspecialchars($_POST['name'] ?? $_SESSION['user_name'] ?? '') ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($_POST['email'] ?? $_SESSION['user_email'] ?? '') ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="message" class="form-label">Tin nhắn *</label>
                                    <textarea class="form-control" id="message" name="message" 
                                              rows="5" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Gửi tin nhắn
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Thông tin liên hệ
                            </h4>
                        </div>
                        
                        <div class="card-body">
                            <div class="mb-4">
                                <h5>
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    Địa chỉ
                                </h5>
                                <p class="ms-4">
                                    123 Đường ABC, Phường XYZ<br>
                                    Quận 1, TP. Hồ Chí Minh<br>
                                    Việt Nam
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h5>
                                    <i class="fas fa-phone text-primary me-2"></i>
                                    Điện thoại
                                </h5>
                                <p class="ms-4">
                                    <a href="tel:0123456789" class="text-decoration-none">0123 456 789</a><br>
                                    <a href="tel:0987654321" class="text-decoration-none">0987 654 321</a>
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h5>
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    Email
                                </h5>
                                <p class="ms-4">
                                    <a href="mailto:info@restaurant.com" class="text-decoration-none">info@restaurant.com</a><br>
                                    <a href="mailto:support@restaurant.com" class="text-decoration-none">support@restaurant.com</a>
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h5>
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    Giờ phục vụ
                                </h5>
                                <p class="ms-4">
                                    <strong>Thứ 2 - Chủ nhật:</strong> 8:00 - 22:00<br>
                                    <strong>Giao hàng:</strong> 9:00 - 21:30
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-body text-center">
                            <h5>Theo dõi chúng tôi</h5>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="btn btn-outline-success btn-sm">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
