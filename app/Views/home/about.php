<?php $title = 'Giới thiệu - Restaurant Order System'; ?>

<!-- Custom CSS for About Page -->
<style>
.hero-about {
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.9), rgba(108, 117, 125, 0.8)), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
    background-size: cover;
    background-position: center;
    min-height: 60vh;
    display: flex;
    align-items: center;
    color: white;
}

.section-divider {
    height: 4px;
    background: linear-gradient(90deg, #007bff, #6c757d);
    border-radius: 2px;
    margin: 3rem auto;
    width: 100px;
}

.feature-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: none;
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
}

.stats-section {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 20px;
    padding: 3rem 2rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
}

.stat-number {
    font-size: 3rem;
    font-weight: 900;
    color: #007bff;
    display: block;
}

.timeline {
    position: relative;
    padding: 2rem 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(to bottom, #007bff, #6c757d);
    transform: translateX(-50%);
}

.timeline-item {
    position: relative;
    margin: 2rem 0;
    width: 50%;
}

.timeline-item:nth-child(odd) {
    left: 0;
    padding-right: 2rem;
}

.timeline-item:nth-child(even) {
    left: 50%;
    padding-left: 2rem;
}

.timeline-content {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    position: relative;
}

.timeline-year {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    background: #007bff;
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
    z-index: 10;
}

.team-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.team-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.team-image {
    height: 250px;
    background: linear-gradient(135deg, #007bff, #6c757d);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 4rem;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
}

.value-item {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: all 0.3s ease;
}

.value-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

@media (max-width: 768px) {
    .timeline::before {
        left: 30px;
    }
    
    .timeline-item {
        width: 100%;
        left: 0 !important;
        padding-left: 4rem !important;
        padding-right: 0 !important;
    }
    
    .timeline-year {
        left: 30px;
    }
}
</style>

<!-- Hero Section -->
<section class="hero-about">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-3 fw-bold mb-4">Về Nhà Hàng Chúng Tôi</h1>
                <p class="lead fs-4 mb-4">
                    Hành trình 15 năm mang đến những trải nghiệm ẩm thực tuyệt vời nhất
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="#story" class="btn btn-light btn-lg">
                        <i class="fas fa-book-open me-2"></i>Câu chuyện của chúng tôi
                    </a>
                    <a href="#menu" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-utensils me-2"></i>Xem thực đơn
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
    <div class="container">
        <div class="stats-section">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number" data-count="15">0</span>
                        <h5 class="text-muted">Năm Kinh Nghiệm</h5>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number" data-count="50000">0</span>
                        <h5 class="text-muted">Khách Hàng Hài Lòng</h5>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number" data-count="200">0</span>
                        <h5 class="text-muted">Món Ăn Đặc Sắc</h5>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number" data-count="5">0</span>
                        <h5 class="text-muted">Chi Nhánh</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Story Section -->
<section id="story" class="py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <div class="pe-lg-4">
                    <h2 class="display-5 fw-bold mb-4">Câu Chuyện Của Chúng Tôi</h2>
                    <p class="lead mb-4">
                        Bắt đầu từ một quán ăn nhỏ trên phố cổ Hà Nội năm 2008, chúng tôi đã không ngừng 
                        phát triển với tình yêu và đam mê dành cho ẩm thực Việt Nam truyền thống.
                    </p>
                    <p class="mb-4">
                        Với phương châm "Từ trái tim đến bàn ăn", mỗi món ăn của chúng tôi đều được 
                        chế biến từ những nguyên liệu tươi ngon nhất, kết hợp với công thức gia truyền 
                        được truyền qua nhiều thế hệ.
                    </p>
                    <div class="d-flex align-items-center">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&h=60&fit=crop&crop=face&auto=format" 
                             class="rounded-circle me-3" width="60" height="60" alt="CEO">
                        <div>
                            <h6 class="mb-0">Nguyễn Văn Nam</h6>
                            <small class="text-muted">Founder & CEO</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <!-- Story Carousel -->
                    <div id="storyCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#storyCarousel" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#storyCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#storyCarousel" data-bs-slide-to="2"></button>
                            <button type="button" data-bs-target="#storyCarousel" data-bs-slide-to="3"></button>
                            <button type="button" data-bs-target="#storyCarousel" data-bs-slide-to="4"></button>
                        </div>
                        <div class="carousel-inner rounded-4 shadow-lg">
                            <div class="carousel-item active">
                                <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&h=600&fit=crop" 
                                     class="d-block w-100" alt="Nhà hàng hiện tại" style="height: 400px; object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block">
                                    <div class="bg-primary text-white p-3 rounded-4 d-inline-block">
                                        <i class="fas fa-award fa-2x mb-2"></i>
                                        <div>
                                            <h6 class="mb-0">Nhà hàng</h6>
                                            <small>Được yêu thích nhất 2023</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&h=600&fit=crop" 
                                     class="d-block w-100" alt="Khởi đầu năm 2008" style="height: 400px; object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block">
                                    <div class="bg-dark bg-opacity-75 text-white p-3 rounded">
                                        <h5>Khởi Đầu Ước Mơ - 2008</h5>
                                        <p>Quán ăn nhỏ đầu tiên trên phố cổ Hà Nội</p>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=600&fit=crop" 
                                     class="d-block w-100" alt="Mở rộng 2012" style="height: 400px; object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block">
                                    <div class="bg-dark bg-opacity-75 text-white p-3 rounded">
                                        <h5>Mở Rộng Đầu Tiên - 2012</h5>
                                        <p>Chi nhánh thứ 2 tại quận Hai Bà Trưng</p>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1590846406792-0adc7f938f1d?w=800&h=600&fit=crop" 
                                     class="d-block w-100" alt="Giải thưởng 2018" style="height: 400px; object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block">
                                    <div class="bg-dark bg-opacity-75 text-white p-3 rounded">
                                        <h5>Giải Thưởng Danh Giá - 2018</h5>
                                        <p>Nhận giải "Nhà hàng Việt Nam xuất sắc"</p>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1559329007-40df8a9345d8?w=800&h=600&fit=crop" 
                                     class="d-block w-100" alt="Chuyển đổi số 2020" style="height: 400px; object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block">
                                    <div class="bg-dark bg-opacity-75 text-white p-3 rounded">
                                        <h5>Chuyển Đổi Số - 2020</h5>
                                        <p>Ra mắt hệ thống đặt món online</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#storyCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#storyCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Timeline Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Hành Trình Phát Triển</h2>
            <p class="lead">Những cột mốc quan trọng trong quá trình phát triển của chúng tôi</p>
        </div>
        
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-content">
                    <h5>Khởi Đầu Ước Mơ</h5>
                    <p>Mở quán ăn đầu tiên tại phố cổ Hà Nội với 4 bàn ăn và menu 20 món truyền thống.</p>
                </div>
                <div class="timeline-year">2008</div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-content">
                    <h5>Mở Rộng Đầu Tiên</h5>
                    <p>Khai trương chi nhánh thứ 2 tại quận Ba Đình, phục vụ hơn 200 khách mỗi ngày.</p>
                </div>
                <div class="timeline-year">2012</div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-content">
                    <h5>Giải Thưởng Danh Giá</h5>
                    <p>Nhận giải "Nhà hàng Việt Nam xuất sắc" từ Hiệp hội Du lịch Việt Nam.</p>
                </div>
                <div class="timeline-year">2016</div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-content">
                    <h5>Chuyển Đổi Số</h5>
                    <p>Ra mắt hệ thống đặt món online và giao hàng tận nơi, phục vụ toàn thành phố.</p>
                </div>
                <div class="timeline-year">2020</div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-content">
                    <h5>Mở Rộng Toàn Quốc</h5>
                    <p>Hiện tại có 5 chi nhánh tại Hà Nội, TP.HCM và Đà Nẵng, phục vụ hơn 1000 khách/ngày.</p>
                </div>
                <div class="timeline-year">2023</div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Giá Trị Cốt Lõi</h2>
            <p class="lead">Những nguyên tắc định hướng mọi hoạt động của chúng tôi</p>
        </div>
        
        <div class="values-grid">
            <div class="value-item">
                <div class="feature-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h4>Tận Tâm</h4>
                <p>Phục vụ khách hàng với tất cả tình yêu và sự chân thành, luôn đặt sự hài lòng của khách hàng lên hàng đầu.</p>
            </div>
            
            <div class="value-item">
                <div class="feature-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h4>Tự Nhiên</h4>
                <p>Sử dụng nguyên liệu hữu cơ, tươi sạch từ các trang trại địa phương, không chất bảo quản có hại.</p>
            </div>
            
            <div class="value-item">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h4>Cộng Đồng</h4>
                <p>Xây dựng mối quan hệ bền vững với cộng đồng, hỗ trợ nông dân địa phương và các hoạt động từ thiện.</p>
            </div>
            
            <div class="value-item">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h4>Chất Lượng</h4>
                <p>Không ngừng cải tiến và nâng cao chất lượng món ăn, dịch vụ để mang đến trải nghiệm tốt nhất.</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Tại Sao Chọn Chúng Tôi?</h2>
            <p class="lead">Những điều đặc biệt làm nên sự khác biệt</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h4>Chứng Nhận Chất Lượng</h4>
                    <p>Được cấp chứng nhận HACCP, ISO 22000 về an toàn thực phẩm và chứng nhận Green Restaurant về môi trường.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>HACCP Certified</li>
                        <li><i class="fas fa-check text-success me-2"></i>ISO 22000</li>
                        <li><i class="fas fa-check text-success me-2"></i>Green Restaurant</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h4>Giao Hàng Siêu Tốc</h4>
                    <p>Hệ thống giao hàng hiện đại với đội ngũ shipper chuyên nghiệp, cam kết giao hàng trong 30 phút.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-clock text-primary me-2"></i>Giao hàng 30 phút</li>
                        <li><i class="fas fa-motorcycle text-primary me-2"></i>100+ Shipper</li>
                        <li><i class="fas fa-map-marked-alt text-primary me-2"></i>Phủ sóng toàn thành phố</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4>Công Nghệ Hiện Đại</h4>
                    <p>Ứng dụng đặt món thông minh với AI gợi ý, thanh toán đa dạng và theo dõi đơn hàng real-time.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-robot text-info me-2"></i>AI Recommendation</li>
                        <li><i class="fas fa-credit-card text-info me-2"></i>Đa dạng thanh toán</li>
                        <li><i class="fas fa-eye text-info me-2"></i>Theo dõi real-time</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="display-6 fw-bold mb-3">Sẵn Sàng Trải Nghiệm?</h2>
                <p class="lead mb-4">
                    Hãy để chúng tôi mang đến cho bạn những trải nghiệm ẩm thực tuyệt vời nhất. 
                    Đặt món ngay hôm nay và cảm nhận sự khác biệt!
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="/" class="btn btn-light btn-lg">
                        <i class="fas fa-utensils me-2"></i>Xem Thực Đơn
                    </a>
                    <a href="/contact" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-phone me-2"></i>Liên Hệ Ngay
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-10 rounded-4 p-4">
                    <i class="fas fa-phone-alt fa-3x mb-3"></i>
                    <h4>Hotline 24/7</h4>
                    <h3 class="fw-bold">1900 1234</h3>
                    <p class="mb-0">Luôn sẵn sàng phục vụ bạn</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript for Animations -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate statistics
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const animateStats = () => {
        statNumbers.forEach(stat => {
            const target = parseInt(stat.getAttribute('data-count'));
            const increment = target / 100;
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                if (target >= 1000) {
                    stat.textContent = Math.floor(current).toLocaleString() + '+';
                } else {
                    stat.textContent = Math.floor(current) + '+';
                }
            }, 20);
        });
    };
    
    // Intersection Observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (entry.target.classList.contains('stats-section')) {
                    animateStats();
                }
                
                // Add fade-in animation
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    // Observe elements for animation
    document.querySelectorAll('.feature-card, .team-card, .value-item, .stats-section, .timeline-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
