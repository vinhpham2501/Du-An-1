<?php $title = 'Liên hệ - Sắc Việt'; ?>

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
                            
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                                <?php unset($_SESSION['success']); ?>
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
                                    Hải Châu - Đà Nẵng<br>
                                    Việt Nam
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h5>
                                    <i class="fas fa-phone text-primary me-2"></i>
                                    Điện thoại
                                </h5>
                                <p class="ms-4">
                                    <a href="tel:0372886625" class="text-decoration-none">0372 886 625</a><br>
                                    <a href="tel:0987654321" class="text-decoration-none">0987 654 321</a>
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h5>
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    Email
                                </h5>
                                <p class="ms-4">
                                    <a
                                        href="mailbird:nhom3@gmail.com?subject=H%E1%BB%97%20tr%E1%BB%A3%20t%E1%BB%AB%20kh%C3%A1ch%20h%C3%A0ng%20S%E1%BA%AFc%20Vi%E1%BB%87t&body=Xin%20ch%C3%A0o%20S%E1%BA%AFc%20Vi%E1%BB%87t%2C%0A%0AT%C3%B4i%20c%E1%BA%A7n%20h%E1%BB%97%20tr%E1%BB%A3%20v%E1%BB%81%3A%20%5Bn%E1%BB%99i%20dung%5D%0A%0ATh%C3%B4ng%20tin%20li%C3%AAn%20h%E1%BB%87%3A%0A-%20H%E1%BB%8D%20t%C3%AAn%3A%20%0A-%20S%E1%BB%91%20%C4%91i%E1%BB%87n%20tho%E1%BA%A1i%3A%20%0A%0AXin%20c%E1%BA%A3m%20%C6%A1n." 
                                        class="text-decoration-none" title="Gửi email hỗ trợ">
                                        nhom3@gmail.com
                                    </a><br>
                                    <a
                                        href="mailto:support@shop.com?subject=H%E1%BB%97%20tr%E1%BB%A3%20kh%C3%A1ch%20h%C3%A0ng&body=Ch%C3%A0o%20b%E1%BA%A1n%2C%0A%0AT%C3%B4i%20mu%E1%BB%91n%20li%C3%AAn%20h%E1%BB%87%20v%E1%BB%81%3A%20%5Bn%E1%BB%99i%20dung%5D%0A%0AXin%20c%E1%BA%A3m%20%C6%A1n." 
                                        class="text-decoration-none" title="Gửi email hỗ trợ">
                                        support@shop.com
                                    </a>
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
                                <a href="https://www.facebook.com/PhamVinh2501" class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://www.instagram.com/phamzinh/" class="btn btn-outline-info btn-sm">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.instagram.com/phamzinh/" class="btn btn-outline-danger btn-sm">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="https://www.facebook.com/PhamVinh2501" class="btn btn-outline-success btn-sm">
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
